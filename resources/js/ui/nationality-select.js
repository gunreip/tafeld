// tafeld/resources/js/components/nationality.select.js

export default function nationalitySelect({ model, countries, placeholder }) {
    return {
        open: false,
        openUp: false,

        searchQuery: '',
        search: '',
        debounceTimer: null,

        model,
        placeholder,

        selected: null,
        selectedName: placeholder,
        selectedFlag: 'xx',

        activeIndex: -1,

        // Auto-Scroll Vars
        autoScrollTimer: null,

        init() {
            const found = countries.find(c => c.id === this.model);
            if (found) {
                this.selected = found;
                this.selectedName = found.name_de;
                this.selectedFlag = found.iso_3166_2;
            }

            // Flags vorladen
            this.preloadImages();

            // ----------------------------------------------------
            // UI-Event-Bus Listener (andere Dropdowns schließen Nationality)
            // ----------------------------------------------------
            window.addEventListener('ui:closeAllDropdowns', e => {
                if (e.detail?.except === 'nationality') return;
                this.open = false;
            });
        },

        openDropdown() {

            // ----------------------------------------------------
            // Globales „Schließe alle Dropdowns, außer mich“-Signal
            // ----------------------------------------------------
            window.dispatchEvent(new CustomEvent('ui:closeAllDropdowns', {
                detail: { except: 'nationality' }
            }));

            this.open = true;
            this.computeFlip();
            this.initActiveOnOpen();

            // Auto-Reset der Suche
            if (this.searchQuery !== '') {
                this.searchQuery = '';
                this.search = '';
            }

            this.$nextTick(() => this.$refs.search?.focus());
        },

        closeDropdown() {
            this.open = false;
            this.$nextTick(() => this.$refs.button?.focus());
        },

        normalize(str) {
            return str
                .toLowerCase()
                .normalize('NFD')
                .replace(/[̀-ͯ]/g, '')
                .replace(/ä/g, 'ae')
                .replace(/ö/g, 'oe')
                .replace(/ü/g, 'ue')
                .replace(/ß/g, 'ss');
        },

        updateSearch(value) {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.search = value;
            }, 120);
        },

        highlight(name) {
            if (!this.search) return name;

            const needle = this.normalize(this.search);
            const normalized = this.normalize(name);
            const index = normalized.indexOf(needle);
            if (index === -1) return name;

            return (
                name.substring(0, index) +
                '<mark>' +
                name.substring(index, index + needle.length) +
                '</mark>' +
                name.substring(index + needle.length)
            );
        },

        get filtered() {
            if (!this.search) {
                return countries.map(c => ({ ...c, highlight: c.name_de }));
            }

            const needle = this.normalize(this.search);

            return countries
                .map(c => {
                    const name = this.normalize(c.name_de ?? '');
                    const alpha2 = (c.iso_3166_2 ?? '').toLowerCase();
                    const alpha3 = (c.iso_3166_3 ?? '').toLowerCase();

                    let score = 0;

                    // Name
                    if (name === needle) score += 1000;
                    if (name.startsWith(needle)) score += 500;
                    if (name.includes(needle)) score += 100;

                    // ISO2
                    if (alpha2 === needle) score += 300;
                    if (alpha2.startsWith(needle)) score += 200;
                    if (alpha2.includes(needle)) score += 50;

                    // ISO3
                    if (alpha3 === needle) score += 150;
                    if (alpha3.startsWith(needle)) score += 120;
                    if (alpha3.includes(needle)) score += 50;

                    return { c, score };
                })
                .filter(x => x.score > 0)
                .sort((a, b) => b.score - a.score)
                .map(x => ({
                    ...x.c,
                    highlight: this.highlight(x.c.name_de)
                }));
        },

        computeFlip() {
            const rect = this.$root.getBoundingClientRect();
            const spaceBelow = window.innerHeight - rect.bottom;
            const dropdownHeight = 260;
            this.openUp = spaceBelow < dropdownHeight;
        },

        initActiveOnOpen() {
            const idx = this.filtered.findIndex(c => c.id === this.model);
            this.activeIndex = idx >= 0 ? idx : 0;
            this.scrollToActive();
        },

        moveDown() {
            if (!this.filtered.length) return;

            if (this.activeIndex === -1) {
                this.activeIndex = 0;
                this.scrollToActive();
                return;
            }

            if (this.activeIndex < this.filtered.length - 1) {
                this.activeIndex++;
            }

            this.scrollToActive();
        },

        moveUp() {
            if (!this.filtered.length) return;

            // Von erstem Element ins Suchfeld springen
            if (this.activeIndex === 0) {
                this.activeIndex = -1;
                this.$nextTick(() => {
                    const panel = this.$root.querySelector('[data-search-container]');
                    if (panel) panel.scrollTop = 0;

                    if (this.$refs.search) {
                        this.$refs.search.focus();
                        this.$refs.search.select();
                    }
                });
                return;
            }

            if (this.activeIndex > 0) {
                this.activeIndex--;
            }

            this.scrollToActive();
        },

        selectActive() {
            if (this.activeIndex < 0 || this.activeIndex >= this.filtered.length) return;
            this.choose(this.filtered[this.activeIndex]);
        },

        scrollToActive() {
            this.$nextTick(() => {
                const items = this.$root.querySelectorAll('[data-country-item]');
                const el = items[this.activeIndex];
                if (el) {
                    el.scrollIntoView({
                        block: 'nearest',
                        behavior: 'smooth'
                    });
                }
            });
        },

        handleKeydown(event) {
            const key = event.key;
            const allowed = ['Escape', 'Enter', 'ArrowUp', 'ArrowDown', 'Tab'];

            if (document.activeElement === this.$refs.search) return;
            if (allowed.includes(key)) return;

            event.preventDefault();
        },

        clearSearch() {
            this.search = '';
            this.searchQuery = '';
            this.debounceTimer = null;
            this.$nextTick(() => this.$refs.search?.focus());
        },

        // -------------------------------------
        // Auto-Scroll (Maus)
        // -------------------------------------
        stopAutoScroll() {
            if (this.autoScrollTimer) {
                clearInterval(this.autoScrollTimer);
                this.autoScrollTimer = null;
            }
        },

        startAutoScroll(direction, speed) {
            const panel = this.$root.querySelector('[data-search-container]');
            if (!panel) return;

            this.stopAutoScroll();

            this.autoScrollTimer = setInterval(() => {
                panel.scrollTop += direction * speed;
            }, 16);
        },

        handleMouseMove(event) {
            const panel = this.$root.querySelector('[data-search-container]');
            if (!panel) return;

            const rect = panel.getBoundingClientRect();
            const y = event.clientY - rect.top;
            const threshold = 40;
            const maxSpeed = 6;

            if (y < threshold) {
                const dist = y;
                const speed = ((threshold - dist) / threshold) * maxSpeed;
                this.startAutoScroll(-1, speed);
                return;
            }

            if (y > rect.height - threshold) {
                const dist = rect.height - y;
                const speed = ((threshold - dist) / threshold) * maxSpeed;
                this.startAutoScroll(1, speed);
                return;
            }

            this.stopAutoScroll();
        },

        handleMouseLeave() {
            this.stopAutoScroll();
        },

        // -------------------------------------
        // Mousewheel-Boost
        // -------------------------------------
        handleWheel(event) {
            const panel = this.$root.querySelector('[data-search-container]');
            if (!panel) return;

            const base = 1;
            const factor = 1.8;
            const boosted = event.deltaY * factor + (event.deltaY > 0 ? base : -base);

            panel.scrollTop += boosted;
        },

        // -------------------------------------
        // Flaggen-Preloading
        // -------------------------------------
        preloadImages() {
            if (this._preloaded) return;
            this._preloaded = true;

            countries.forEach(c => {
                if (!c.iso_3166_2) return;

                const img = new Image();
                img.src = `/flags/${c.iso_3166_2.toLowerCase()}.svg`;
            });
        },

        // -------------------------------------
        // Auswahl
        // -------------------------------------
        choose(country) {
            this.model = country.id;
            this.selected = country;
            this.selectedName = country.name_de;
            this.selectedFlag = country.iso_3166_2;

            this.search = '';
            this.searchQuery = '';
            this.debounceTimer = null;

            this.closeDropdown();
            this.$dispatch('input', country.id);
        }
    };
}
