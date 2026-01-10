// tafeld/resources/js/ui/debug-custom-select.js

export default function debugCustomSelect({ value, options }) {
    return {
        open: false,
        activeIndex: -1,
        previewIndex: -1,
        value,
        options,

        toggleDropdown() {
            this.open = !this.open;
        },

        currentOption() {
            return this.options.find(o => o.value === this.value) ?? null;
        },

        displayedOption() {
            if (this.previewIndex !== -1) {
                return this.options[this.previewIndex] ?? null;
            }
            return this.currentOption();
        },

        currentIcon() {
            return this.displayedOption()?.['icon-name'] ?? null;
        },

        init() {
            this.syncActiveFromValue();
        },

        openDropdown() {
            this.open = true;
        },

        closeDropdown() {
            this.open = false;
            this.previewIndex = -1;
        },

        activate(index) {
            if (index < 0 || index >= this.options.length) return;
            this.activeIndex = index;
            this.value = this.options[index].value ?? null;
            this.previewIndex = -1;
        },

        preview(index) {
            if (index < 0 || index >= this.options.length) return;
            this.previewIndex = index;
        },

        handleArrowDown(e) {
            e.preventDefault();

            if (!this.open) {
                this.openDropdown();
                this.activeIndex = 0;
                this.previewIndex = this.activeIndex;
                return;
            }

            this.activeIndex = Math.min(
                this.activeIndex + 1,
                this.options.length - 1
            );
            this.previewIndex = this.activeIndex;
        },

        handleArrowUp(e) {
            e.preventDefault();

            if (!this.open) {
                this.openDropdown();
                this.activeIndex = this.options.length - 1;
                this.previewIndex = this.activeIndex;
                return;
            }

            this.activeIndex = Math.max(
                this.activeIndex - 1,
                0
            );
            this.previewIndex = this.activeIndex;
        },

        handleEnter(e) {
            // If closed, open the dropdown
            if (!this.open) {
                this.openDropdown();
                this.activeIndex = this.activeIndex === -1 ? 0 : this.activeIndex;
                return;
            }

            if (this.activeIndex !== -1) {
                this.activate(this.activeIndex);
            }

            this.closeDropdown();
            this.activeIndex = -1;

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
            // Escape: close dropdown and reset active index
            try {
                if (this.open) {
                    this.closeDropdown();
                    this.activeIndex = -1;
                    return;
                }

                if (this.selectedIndex() > 0) {
                    this.clear();
                }
            } catch (err) {
                console.warn('handleEscape failed', err);
            }
        },

        previewFromEvent(e) {
            try {
                const li = e?.target?.closest?.('li[role="option"][data-value]');
                if (!li) return;

                const val = li.getAttribute('data-value');
                const idx = this.options.findIndex(o => String(o?.value ?? '') === String(val ?? ''));
                if (idx === -1) return;

                this.previewIndex = idx;
            } catch (err) {
                console.warn('previewFromEvent failed', err);
            }
        },

        clearPreview() {
            this.previewIndex = -1;
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
