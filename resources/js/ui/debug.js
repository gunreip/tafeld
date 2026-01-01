// tafeld/resources/js/tafeld/debug.js

window.TafeldDebug = {

    enabled: false,
    scopes: {},

    /**
     * Initialisiert das JS-Debug-System.
     * config: vollständige Debug-Konfiguration aus PHP
     * logLevel: Wert von LOG_LEVEL aus .env
     */
    init(config, logLevel) {
        // Defensive Guards gegen NULL / undefined / falsche Typen
        const safeConfig = (config && typeof config === 'object') ? config : {};

        this.enabled = (logLevel === 'debug') && Boolean(safeConfig.enabled);

        // Scopes müssen ein Objekt sein, ansonsten leeren Safe-Fallback verwenden
        this.scopes = (safeConfig.scopes && typeof safeConfig.scopes === 'object')
            ? safeConfig.scopes
            : {};
    },

    /**
     * Prüft, ob der Scope im JS-Debug-System aktiv ist.
     */
    scopeEnabled(scope) {
        if (!this.enabled) return false;

        const entry = this.scopes[scope];
        if (!entry || typeof entry !== 'object') return false;

        return Boolean(entry.enabled);
    },

    /**
     * Zentrale Debug-Methode: wird aus Komponenten, Alpine oder PHP verwendet.
     */
    log(scope, message, context = {}) {
        if (!this.scopeEnabled(scope)) return;
        this.consoleOutput(scope, message, context);
    },

    /**
     * Ausgabe in die Browser-Konsole.
     */
    consoleOutput(scope, message, context) {
        console.debug(`[TAFELD][${scope}]`, message, context);
    },

    /**
     * Bridge: PHP → Livewire → JS
     */
    fromPHP(payload) {
        if (!payload || typeof payload !== 'object') return;

        const scope = payload.scope ?? null;
        const message = payload.message ?? null;
        const context = payload.context ?? {};

        if (!scope) return;

        this.log(scope, message, context);
    }
};
