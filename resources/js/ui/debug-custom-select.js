// tafeld/resources/js/ui/debug-custom-select.js

export default function debugCustomSelect({ value, options }) {
    return {
        open: false,
        activeIndex: -1,
        value,
        options,

        currentOption() {
            return this.options.find(o => o.value === this.value) ?? null;
        },

        currentIcon() {
            return this.currentOption()?.['icon-name'] ?? null;
        },

        init() {
            this.syncActiveFromValue();
        },

        openDropdown() {
            this.open = true;
        },

        closeDropdown() {
            this.open = false;
        },

        activate(index) {
            if (index < 0 || index >= this.options.length) return;
            this.activeIndex = index;
            this.value = this.options[index].value ?? null;
        },

        handleEnter() {
            if (!this.open) {
                this.openDropdown();
                return;
            }

            if (this.activeIndex !== -1) {
                this.activate(this.activeIndex);
            }

            this.closeDropdown();
        },

        syncActiveFromValue() {
            this.activeIndex = this.options.findIndex(
                o => o.value === this.value
            );
        },

        clear() {
            this.value = null;
            this.syncActiveFromValue();
            this.closeDropdown();

            // Try to set Livewire property immediately similar to suggest-input
            try {
                const modelName = this.$el.dataset.wireModel;
                if (modelName && window.Livewire) {
                    const root = this.$el.closest('[wire\\:id]');
                    const lwId = root ? root.getAttribute('wire:id') : null;

                    if (lwId && typeof window.Livewire.find === 'function') {
                        const lw = window.Livewire.find(lwId);
                        if (lw && typeof lw.set === 'function') {
                            lw.set(modelName, null);
                        }
                    }
                }
            } catch (e) {
                console.warn('Livewire set fallback failed', e);
            }
        },

        next() {
            this.activate(
                Math.min(this.activeIndex + 1, this.options.length - 1)
            );
        },

        prev() {
            this.activate(
                Math.max(this.activeIndex - 1, 0)
            );
        }
    };
}
