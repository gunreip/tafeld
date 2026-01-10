// tafeld/resources/js/ui/debug-suggest-input.js

export default function debugSuggestInput({ value, options, mode = 'hierarchical' }) {
    return {
        open: false,
        activeIndex: -1,
        value,
        options,
        mode,
        currentPrefix: '',
        panelStyle: '',
        suppressNextOpen: false,

        init() {
            this.resetActive();
        },

        /* ---------- helpers ---------- */

        hasValue() {
            return typeof this.value === 'string' && this.value.trim().length > 0;
        },

        topLevelOptions() {
            if (this.mode === 'flat') {
                return (Array.isArray(this.options) ? this.options : []).slice().sort();
            }

            const prefixes = new Set();

            (Array.isArray(this.options) ? this.options : []).forEach(opt => {
                const seg = String(opt).split('.')[0];
                if (seg) prefixes.add(seg + '.*');
            });

            return Array.from(prefixes).sort();
        },

        scopedOptions() {
            if (this.mode === 'flat') {
                return (Array.isArray(this.options) ? this.options : []).slice().sort();
            }

            if (!this.currentPrefix) {
                return this.topLevelOptions();
            }

            const base = this.currentPrefix;
            const results = [];

            const children = new Set();

            (Array.isArray(this.options) ? this.options : []).forEach(opt => {
                if (!String(opt).startsWith(base)) return;

                const rest = String(opt).slice(base.length);

                // weitere Ebene vorhanden
                if (rest.includes('.')) {
                    children.add(rest.split('.')[0] + '.*');
                } else {
                    // finales Scope
                    results.push(opt);
                }
            });

            const aggregated = Array.from(children)
                .map(c => base + c);

            return [...aggregated, ...results].sort();
        },

        safeOptions() {
            let opts = this.scopedOptions();

            // kein Textfilter bei Aggregation (*. )
            if (this.hasValue() && !this.value.endsWith('.*')) {
                const needle = this.value.split('.').pop().toLowerCase();
                opts = opts.filter(opt =>
                    String(opt).toLowerCase().includes(needle)
                );
            }

            return opts;
        },

        highlight(option) {
            if (!this.hasValue()) return option;

            const token = this.value.split('.').pop();
            if (!token) return option;

            const escaped = token.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const regex = new RegExp(`(${escaped})`, 'ig');

            return String(option).replace(regex, '<mark>$1</mark>');
        },

        resetActive() {
            this.activeIndex = -1;
        },

        /* ---------- panel sizing ---------- */

        updatePanelWidth() {
            this.$nextTick(() => {
                const input = this.$el.querySelector('.debug-suggest-select-field');
                const panel = this.$el.querySelector('.debug-suggest-select-panel');
                if (!input || !panel) return;

                const inputWidth = input.offsetWidth;
                const minWidth = inputWidth;
                const maxWidth = inputWidth * 2;

                let longest = minWidth;

                panel.querySelectorAll('li span').forEach(span => {
                    longest = Math.max(longest, span.scrollWidth + 32);
                });

                const finalWidth = Math.min(
                    Math.max(longest, minWidth),
                    maxWidth
                );

                const rect = input.getBoundingClientRect();
                const viewportRight = window.innerWidth - 16;

                let left = 0;
                if (rect.left + finalWidth > viewportRight) {
                    left = viewportRight - (rect.left + finalWidth);
                }

                this.panelStyle = `
                    min-width: ${minWidth}px;
                    width: ${finalWidth}px;
                    max-width: ${maxWidth}px;
                    left: ${left}px;
                `;
            });
        },

        /* ---------- panel control ---------- */

        close() {
            this.open = false;
            this.resetActive();
        },

        handleEscape() {
            if (this.mode === 'flat') {
                this.value = '';
                this.close();
                return;
            }

            if (this.currentPrefix) {
                const parts = this.currentPrefix.split('.').filter(Boolean);
                parts.pop();

                this.currentPrefix = parts.length
                    ? parts.join('.') + '.'
                    : '';

                this.value = this.currentPrefix
                    ? this.currentPrefix + '*'
                    : '';

                this.resetActive();

                if (this.value) {
                    this.open = true;
                    this.updatePanelWidth();
                } else {
                    this.close();
                }

                return;
            }

            this.value = '';
            this.close();
        },

        /* ---------- input ---------- */

        onInput(e) {
            if (this.suppressNextOpen) {
                this.suppressNextOpen = false;
                this.close();
                return;
            }

            // DOM-Wert als Quelle (enthält das gerade getippte Zeichen)
            let domValue = '';
            try {
                domValue = e && e.target ? String(e.target.value ?? '') : '';
            } catch (_) {
                domValue = '';
            }

            console.log(`debugSuggestInput.onInput(e) -> e.target.value: `, e.target.value);
            console.log(`debugSuggestInput.onInput(e) -> domValue: `, domValue);
            console.log(`debugSuggestInput.onInput(e) -> this.mode: `, this.mode);

            // Aggregations-Reparatur nur im hierarchical-Modus
            if (this.mode === 'hierarchical') {
                // Option B:
                // Entfernt exakt das Aggregations-* (".*"), lässt den getippten Text bestehen
                const starPos = domValue.lastIndexOf('.*');
                if (starPos !== -1) {
                    domValue =
                        domValue.slice(0, starPos + 1) +
                        domValue.slice(starPos + 2);
                }
            }

            console.log(`debugSuggestInput.onInput(e) -> this.mode: `, this.mode);

            this.value = domValue;

            this.resetActive();
            this.open = true;
            this.updatePanelWidth();
        },

        /* ---------- keyboard navigation ---------- */

        next() {
            const opts = this.safeOptions();
            if (!opts.length) return;

            if (!this.open) {
                this.open = true;
                this.updatePanelWidth();
            }

            this.activeIndex = Math.min(
                this.activeIndex + 1,
                opts.length - 1
            );
        },

        prev() {
            const opts = this.safeOptions();
            if (!opts.length) return;

            if (!this.open) {
                this.open = true;
                this.updatePanelWidth();
            }

            this.activeIndex = Math.max(
                this.activeIndex - 1,
                0
            );
        },

        /* ---------- selection ---------- */

        select(index) {
            const opts = this.safeOptions();
            if (index < 0 || index >= opts.length) return;

            const opt = opts[index];

            if (this.mode === 'hierarchical' && String(opt).endsWith('.*')) {
                this.currentPrefix = String(opt).replace('*', '');
                this.value = String(opt);
                this.resetActive();
                this.open = true;
                this.updatePanelWidth();
                return;
            }

            this.value = opt;
            const parts = String(opt).split('.').filter(Boolean);
            parts.pop();
            this.currentPrefix = parts.length ? parts.join('.') + '.' : '';

            this.close();
            this.suppressNextOpen = true;

            this.$nextTick(() => {
                const input = this.$el.querySelector('.debug-suggest-select-field');
                if (input) {
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }

                try {
                    const modelName = this.$el.dataset.wireModel;
                    if (modelName && window.Livewire) {
                        const root = this.$el.closest('[wire\\:id]');
                        const lwId = root?.getAttribute('wire:id');

                        if (lwId && typeof window.Livewire.find === 'function') {
                            const lw = window.Livewire.find(lwId);
                            lw?.set?.(modelName, this.value);
                        }
                    }
                } catch (_) { }
            });
        },

        applyActive() {
            if (!this.open) return;
            if (this.activeIndex === -1) {
                this.close();
                return;
            }

            this.select(this.activeIndex);
        },

        clear() {
            this.handleEscape();

            try {
                const modelName = this.$el?.dataset?.wireModel;

                const focusWithRetry = () => {
                    let attempts = 0;
                    const id = setInterval(() => {
                        attempts++;

                        const wrapper = modelName
                            ? document.querySelector(
                                `.debug-suggest-select-wrapper[data-wire-model="${modelName}"]`
                            )
                            : null;

                        const input =
                            wrapper?.querySelector?.('.debug-suggest-select-field') ??
                            null;

                        if (input && typeof input.focus === 'function') {
                            input.focus();
                            if (
                                document.activeElement === input ||
                                attempts >= 30
                            ) {
                                clearInterval(id);
                            }
                            return;
                        }

                        if (attempts >= 30) clearInterval(id);
                    }, 50);
                };

                this.$nextTick(() => focusWithRetry());
            } catch (_) { }
        },
    };
}
