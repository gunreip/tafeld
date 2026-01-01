// tafeld/resources/js/ui/debug-suggest-input.js

export default function debugSuggestInput({ value, options }) {
    return {
        open: false,
        activeIndex: -1,
        value,
        options,
        panelStyle: '',

        init() {
            this.resetActive();
            this.suppressNextOpen = false; // used to prevent re-opening after keyboard selection
        },

        // ---------- helpers ----------
        hasValue() {
            return typeof this.value === 'string' && this.value.trim().length > 0;
        },

        safeOptions() {
            if (!this.hasValue()) return [];

            const needle = this.value.toLowerCase();

            return (Array.isArray(this.options) ? this.options : [])
                .filter(opt =>
                    String(opt).toLowerCase().includes(needle)
                );
        },

        highlight(option) {
            if (!this.hasValue()) return option;

            const escaped = this.value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const regex = new RegExp(`(${escaped})`, 'ig');

            return String(option).replace(regex, '<mark>$1</mark>');
        },

        resetActive() {
            this.activeIndex = -1;
        },

        // ---------- width logic ----------
        updatePanelWidth() {
            this.$nextTick(() => {
                const input = this.$el.querySelector('.debug-suggest-input-field');
                const panel = this.$el.querySelector('.debug-suggest-input-panel');
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

        // ---------- panel control ----------
        openIfHasOptions() {
            const has = this.safeOptions().length > 0;
            this.open = has;

            if (has) {
                this.updatePanelWidth();
            }
        },

        close() {
            this.open = false;
            this.resetActive();
        },

        // ---------- input ----------
        onInput() {
            // If the input event was caused by a programmatic selection (keyboard/mouse),
            // suppress the immediate re-open that would otherwise happen here.
            if (this.suppressNextOpen) {
                this.suppressNextOpen = false;
                // ensure the panel stays closed after a selection
                this.close();
                return;
            }

            if (!this.hasValue()) {
                this.close();
                return;
            }

            this.resetActive();
            this.openIfHasOptions();
        },

        // ---------- navigation ----------
        next() {
            const opts = this.safeOptions();
            if (!opts.length) return;

            this.activeIndex = Math.min(this.activeIndex + 1, opts.length - 1);
        },

        prev() {
            const opts = this.safeOptions();
            if (!opts.length) return;

            this.activeIndex = Math.max(this.activeIndex - 1, 0);
        },

        // ---------- selection ----------
        select(index) {
            const opts = this.safeOptions();
            if (index < 0 || index >= opts.length) return;

            this.value = opts[index];
            this.close();

            // prevent the immediate @input handler from re-opening the panel
            this.suppressNextOpen = true;

            // Dispatch an 'input' event on the real input so Livewire's
            // wire:model handler notices the change and schedules a commit
            // (e.g. debounce .300ms) without needing a manual pagination click.
            this.$nextTick(() => {
                const input = this.$el.querySelector('.debug-suggest-input-field');
                if (input) {
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }

                // Also try to set the Livewire property directly for immediate effect
                try {
                    const modelName = this.$el.dataset.wireModel;
                    if (modelName && window.Livewire) {
                        // Find closest Livewire component root that has the wire:id
                        const root = this.$el.closest('[wire\\:id]');
                        const lwId = root ? root.getAttribute('wire:id') : null;

                        if (lwId && typeof window.Livewire.find === 'function') {
                            const lw = window.Livewire.find(lwId);
                            if (lw && typeof lw.set === 'function') {
                                lw.set(modelName, this.value);
                            }
                        }
                    }
                } catch (e) {
                    // swallow errors - fallback to debounced input above
                    console.warn('Livewire set fallback failed', e);
                }
            });
        },

        applyActive() {
            if (this.activeIndex === -1) {
                this.close();
                return;
            }

            this.select(this.activeIndex);
        },

        clear() {
            this.value = '';
            this.close();

            this.$nextTick(() => {
                const input = this.$el.querySelector('.debug-suggest-input-field');
                if (input) {
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }

                try {
                    const modelName = this.$el.dataset.wireModel;
                    if (modelName && window.Livewire) {
                        const root = this.$el.closest('[wire\\:id]');
                        const lwId = root ? root.getAttribute('wire:id') : null;

                        if (lwId && typeof window.Livewire.find === 'function') {
                            const lw = window.Livewire.find(lwId);
                            if (lw && typeof lw.set === 'function') {
                                lw.set(modelName, this.value);
                            }
                        }
                    }
                } catch (e) {
                    console.warn('Livewire set fallback failed', e);
                }
            });
        },
    };
}
