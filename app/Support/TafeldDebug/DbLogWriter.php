<?php

// tafeld/app/Support/TafeldDebug/DbLogWriter.php

namespace App\Support\TafeldDebug;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DbLogWriter
{
    /**
     * Persistiert einen Debug-Log-Eintrag.
     * Reine Persistenz, keine Entscheidungen, keine Exceptions nach außen.
     */
    public function write(
        string $scope,
        string $level,
        string $message,
        array $context = []
    ): void {
        if (! Schema::hasTable('debug_logs')) {
            return;
        }

        // run_id aus Context extrahieren (DB-Constraint: NOT NULL)
        $runId = $context['_run_id'] ?? null;
        unset($context['_run_id']);

        // Context sanitizen (Depth/Size-Limits) + defensive JSON-Encode
        $contextJson = null;
        if (! empty($context)) {
            try {
                $sanitized = $this->sanitizeContext($context);
                if (! empty($sanitized)) {
                    $contextJson = $this->encodeContextJson($sanitized);
                }
            } catch (\Throwable $ignored) {
                // absolut defensiv: Context dann vollständig verwerfen
                $contextJson = null;
            }
        }

        DB::table('debug_logs')->insert([
            'id'          => (string) Str::ulid(),
            'run_id'      => $runId,
            'scope'       => $scope,
            'level'       => $level,
            'message'     => $message,
            'context'     => $contextJson,
            'channel'     => 'db',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    /**
     * Sanitized Context:
     * - Depth-Limit
     * - Item-Limit pro Array
     * - String-Limit
     * - Objekte/Closures/Resources werden auf sichere Platzhalter reduziert
     *
     * @return array
     */
    protected function sanitizeContext(
        array $context,
        int $maxDepth = 6,
        int $maxItems = 200,
        int $maxString = 4000
    ): array {
        $walk = function ($value, int $depth) use (&$walk, $maxDepth, $maxItems, $maxString) {
            if ($depth > $maxDepth) {
                return '[max-depth]';
            }

            if (is_null($value) || is_bool($value) || is_int($value) || is_float($value)) {
                return $value;
            }

            if (is_string($value)) {
                if (strlen($value) > $maxString) {
                    return substr($value, 0, $maxString) . '…';
                }
                return $value;
            }

            if ($value instanceof \Throwable) {
                return [
                    '__exception' => true,
                    'class'       => get_class($value),
                    'message'     => substr((string) $value->getMessage(), 0, $maxString),
                    'code'        => $value->getCode(),
                    'file'        => $value->getFile(),
                    'line'        => $value->getLine(),
                ];
            }

            if ($value instanceof \Closure) {
                return '[closure]';
            }

            if (is_resource($value)) {
                return '[resource:' . get_resource_type($value) . ']';
            }

            if (is_object($value)) {
                return [
                    '__object' => true,
                    'class'    => get_class($value),
                ];
            }

            if (is_array($value)) {
                $out = [];
                $i = 0;
                foreach ($value as $k => $v) {
                    $i++;
                    if ($i > $maxItems) {
                        $out['__truncated'] = true;
                        $out['__reason'] = 'max-items';
                        break;
                    }

                    // Keys defensiv normalisieren (json safe)
                    if (is_int($k) || is_string($k)) {
                        $key = (string) $k;
                    } else {
                        $key = '[key:' . gettype($k) . ']';
                    }

                    if (strlen($key) > 200) {
                        $key = substr($key, 0, 200) . '…';
                    }

                    $out[$key] = $walk($v, $depth + 1);
                }
                return $out;
            }

            return '[unsupported:' . gettype($value) . ']';
        };

        $sanitized = $walk($context, 0);
        return is_array($sanitized) ? $sanitized : [];
    }

    /**
     * Encodes sanitized context to JSON with a hard byte limit.
     * If too large or encoding fails, reduces to a minimal payload.
     */
    protected function encodeContextJson(array $sanitized, int $maxBytes = 65536): ?string
    {
        $json = json_encode(
            $sanitized,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        if ($json === false) {
            $json = json_encode([
                '__sanitizer_error' => true,
                'message'           => json_last_error_msg(),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (! is_string($json)) {
            return null;
        }

        if (strlen($json) > $maxBytes) {
            $json = json_encode([
                '__truncated' => true,
                '__reason'    => 'max-bytes',
                '__bytes'     => strlen($json),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return is_string($json) ? $json : null;
    }
}
