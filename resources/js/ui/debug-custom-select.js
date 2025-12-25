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
