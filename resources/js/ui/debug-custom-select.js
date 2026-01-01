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

        handleEnter(e) {
            // If closed, open the dropdown
            if (!this.open) {
                this.openDropdown();
                return;
            }

            if (this.activeIndex !== -1) {
                this.activate(this.activeIndex);
            }

            this.closeDropdown();

            // Default: jump to next focusable outside this component (Enter is main action)
            try {
                this.focusNextOutside();
            } catch (err) {
                console.warn('Focus after enter failed', err);
            }
        },

        handleTab(e) {
            // Tab -> focus next outside
            try {
                this.focusNextOutside();
            } catch (err) {
                console.warn('handleTab failed', err);
            }
        },

        handleShiftTab(e) {
            // Shift+Tab -> focus previous outside
            try {
                this.focusPrevOutside();
            } catch (err) {
                console.warn('handleShiftTab failed', err);
            }
        },

        handleEscape(e) {
            // Escape: if a value is selected, clear it and keep focus on the trigger; else close the dropdown if open
            try {
                if (this.selectedIndex() > 0) {
                    this.clear();
                    // keep focus on trigger — allow for DOM updates / Livewire re-render
                    const focusWithRetry = () => {
                        let attempts = 0;
                        const id = setInterval(() => {
                            const trigger = this.$el.querySelector('.debug-custom-select-trigger');
                            if (trigger && typeof trigger.focus === 'function') trigger.focus();
                            const active = document.activeElement;
                            if (trigger === active || ++attempts >= 8) clearInterval(id);
                        }, 75);
                    };

                    if (typeof this.$nextTick === 'function') {
                        this.$nextTick(() => setTimeout(focusWithRetry, 100));
                    } else {
                        setTimeout(focusWithRetry, 100);
                    }

                    return;
                }

                if (this.open) {
                    this.closeDropdown();
                }
            } catch (err) {
                console.warn('handleEscape failed', err);
            }
        },

        focusClearButton() {
            const btn = this.$el.querySelector('.ui-clear-button');
            if (btn && typeof btn.focus === 'function') {
                btn.focus();
                return true;
            }
            return false;
        },

        focusNextOutside() {
            const focusable = Array.from(document.querySelectorAll('a[href], area[href], input:not([disabled]):not([type=hidden]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), [tabindex]:not([tabindex="-1"])'))
                .filter(el => el.offsetParent !== null || el === document.activeElement);

            let lastIndex = -1;
            for (let i = 0; i < focusable.length; i++) {
                if (this.$el.contains(focusable[i])) lastIndex = i;
            }

            const next = focusable.slice(lastIndex + 1).find(el => !this.$el.contains(el));
            if (next && typeof next.focus === 'function') {
                next.focus();
                return true;
            }
            return false;
        },

        focusPrevOutside() {
            const focusable = Array.from(document.querySelectorAll('a[href], area[href], input:not([disabled]):not([type=hidden]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), [tabindex]:not([tabindex="-1"])'))
                .filter(el => el.offsetParent !== null || el === document.activeElement);

            let firstIndex = -1;
            for (let i = 0; i < focusable.length; i++) {
                if (this.$el.contains(focusable[i])) { firstIndex = i; break; }
            }

            // Focus the element just before the first element that belongs to this component
            const prev = focusable.slice(0, firstIndex).reverse().find(el => !this.$el.contains(el));
            if (prev && typeof prev.focus === 'function') {
                prev.focus();
                return true;
            }
            return false;
        },

        syncActiveFromValue() {
            this.activeIndex = this.options.findIndex(
                o => o.value === this.value
            );
        },

        selectedIndex() {
            return this.options.findIndex(o => o.value === this.value);
        },

        clear() {
            this.value = null;
            this.syncActiveFromValue();
            this.closeDropdown();

            // Keep focus in the trigger after clearing — retry for a short time in case Livewire re-renders
            try {
                const focusWithRetry = () => {
                    let attempts = 0;
                    const id = setInterval(() => {
                        const trigger = this.$el.querySelector('.debug-custom-select-trigger');
                        if (trigger && typeof trigger.focus === 'function') trigger.focus();
                        const active = document.activeElement;
                        if (trigger === active || ++attempts >= 30) clearInterval(id);
                    }, 100);
                };

                if (typeof this.$nextTick === 'function') {
                    this.$nextTick(() => setTimeout(focusWithRetry, 150));
                } else {
                    setTimeout(focusWithRetry, 150);
                }
            } catch (e) {
                // ignore
            }

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
