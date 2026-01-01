// resources/js/components/datepicker.js

export default function datepickerComponent({ model, holidays = {} }) {
    return {
        open: false,
        flipUp: false,

        panelGlow: false,
        panelGlowAnimate: false,
        panelGlowStatic: false,

        yearSelectOpen: false,
        yearInputActive: false,
        yearInputValue: '',
        minYear: null,
        maxYear: null,
        years: [],

        holidays,

        model,
        value: null,
        formattedValue: '',

        currentYear: null,
        currentMonth: null,
        days: [],
        weekdays: ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'],

        // ----------------------------------------------------
        // INIT
        // ----------------------------------------------------
        init() {
            if (this.model) {
                const d = new Date(this.model);
                if (!isNaN(d)) {
                    this.value = d;
                    this.formattedValue = this.formatDate(d);
                }
            }

            // Holidays aus Blade normalisieren (Multi-Event)
            this.normalizeHolidays();

            const now = new Date().getFullYear();
            this.minYear = this.minCalendarYear ?? (now - 100);
            this.maxYear = this.maxCalendarYear ?? (now + 30);

            this.generateYearList();

            window.addEventListener('ui:closeAllDropdowns', e => {
                if (e.detail?.except === 'datepicker') return;
                this.open = false;
                this.yearSelectOpen = false;
                this.yearInputActive = false;
                this.panelGlow = false;
                this.panelGlowAnimate = false;
                this.panelGlowStatic = false;
            });

            this.$watch('open', (value) => {
                if (!value) {
                    requestAnimationFrame(() => {
                        this.yearSelectOpen = false;
                        this.yearInputActive = false;
                    });
                }
            });
        },


        // ----------------------------------------------------
        // HOLIDAYS NORMALISIEREN (Multi-Event)
        // ----------------------------------------------------
        normalizeHolidays() {
            const source = this.holidays || {};
            const normalized = {};

            Object.entries(source).forEach(([key, items]) => {
                if (!Array.isArray(items)) {
                    return;
                }

                const dateKey = String(key).slice(0, 10);
                if (!dateKey) return;

                const cleaned = items
                    .filter(ev => ev && ev.display_date !== false)
                    .map(ev => ({
                        type: ev.type,
                        name_de: ev.name_de ?? null,
                        confession: ev.confession ?? null,
                        is_school: ev.type === 'school',
                        is_business: ev.type === 'business',
                    }));

                if (cleaned.length > 0) {
                    normalized[dateKey] = cleaned;
                }
            });

            this.holidays = normalized;
        },

        // ----------------------------------------------------
        // PANEL OPEN
        // ----------------------------------------------------
        openDropdown() {
            window.dispatchEvent(new CustomEvent('ui:closeAllDropdowns', {
                detail: { except: 'datepicker' }
            }));

            this.open = true;

            this.panelGlow = true;
            this.panelGlowAnimate = true;
            this.panelGlowStatic = false;

            setTimeout(() => {
                this.panelGlowAnimate = false;
                this.panelGlowStatic = true;
            }, 1650);
        },

        initCalendar() {
            const today = this.value ? new Date(this.value) : new Date();
            this.currentYear = today.getFullYear();
            this.currentMonth = today.getMonth();
            this.generateCalendar();
        },

        // ----------------------------------------------------
        // FLIP LOGIC
        // ----------------------------------------------------
        computeFlip() {
            this.$nextTick(() => {
                const rect = this.$root.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                const panelHeight = 330;

                this.flipUp = spaceBelow < panelHeight;
            });
        },

        // ----------------------------------------------------
        // CALENDAR
        // ----------------------------------------------------
        formatDate(date) {
            const d = String(date.getDate()).padStart(2, '0');
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const y = date.getFullYear();
            return `${d}.${m}.${y}`;
        },

        generateCalendar() {
            const firstDay = new Date(this.currentYear, this.currentMonth, 1);
            const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);

            const startWeekday = (firstDay.getDay() + 6) % 7;
            const totalDays = lastDay.getDate();

            let items = [];

            for (let i = 0; i < startWeekday; i++) {
                items.push({ label: '', date: null, classes: 'opacity-0', key: `e-${i}` });
            }

            for (let d = 1; d <= totalDays; d++) {
                const date = new Date(this.currentYear, this.currentMonth, d);

                const isFocusedDay =
                    !this.value &&
                    date.toDateString() === new Date().toDateString();

                const key = [
                    date.getFullYear(),
                    String(date.getMonth() + 1).padStart(2, '0'),
                    String(date.getDate()).padStart(2, '0'),
                ].join('-');

                const events = this.holidays[key] ?? [];
                const hasHoliday = events.some(ev => ev.type === 'holiday');
                const hasSchool = events.some(ev => ev.type === 'school');
                const hasBusiness = events.some(ev => ev.type === 'business');

                const names = events.map(ev => ev.name_de).filter(Boolean);
                const titleName = names.join('\n');

                const isToday =
                    this.value && date.toDateString() === this.value.toDateString();

                const weekday = date.getDay();
                const isSunday = weekday === 0;
                const isSaturday = weekday === 6;

                const dayClasses = [
                    "dp-day",
                    isToday && "dp-day-today",
                    isFocusedDay && "dp-day-focus",
                    isSunday && "dp-day-sun",
                    isSaturday && "dp-day-sat",

                    // Reihenfolge exakt: holiday → school → business
                    hasHoliday && "dp-day-holiday",
                    hasSchool && "dp-day-school",
                    hasBusiness && "dp-day-business",

                    "dp-day-hover",
                ];

                const classes = dayClasses.filter(Boolean).join(" ");

                items.push({
                    label: d,
                    date,
                    key: `d-${d}`,
                    classes,
                    holidayName: titleName || null,
                });
            }

            this.days = items;
        },

        get monthLabel() {
            return new Date(this.currentYear, this.currentMonth).toLocaleString(
                'de-DE',
                { month: 'long', year: 'numeric' }
            );
        },

        get monthName() {
            return new Date(this.currentYear, this.currentMonth).toLocaleString(
                'de-DE',
                { month: 'long' }
            );
        },

        // ----------------------------------------------------
        // YEAR SELECT
        // ----------------------------------------------------
        generateYearList() {
            this.years = Array.from(
                { length: this.maxYear - this.minYear + 1 },
                (_, i) => this.minYear + i
            );
        },

        openYearSelect() {
            this.yearInputActive = false;
            this.yearSelectOpen = true;
        },

        openYearInput() {
            this.yearSelectOpen = false;
            this.yearInputActive = true;
            this.yearInputValue = this.currentYear;
            this.$nextTick(() => this.$refs.yearInput?.focus());
        },

        normalizeYear(value) {
            let y = parseInt(value);
            if (isNaN(y)) return null;

            if (value.length === 2) {
                y = (y >= 30) ? 1900 + y : 2000 + y;
            }
            return y;
        },

        confirmYearInput() {
            let y = this.normalizeYear(this.yearInputValue);
            if (!y) return this.cancelYearInput();

            y = Math.min(this.maxYear, Math.max(this.minYear, y));
            this.selectYear(y);
        },

        cancelYearInput() {
            this.yearInputActive = false;
        },

        selectYear(year) {
            if (year < this.minYear || year > this.maxYear) return;

            this.currentYear = year;

            this.value = new Date(this.currentYear, this.currentMonth, 1);
            this.model = this.value.toISOString().slice(0, 10);
            this.formattedValue = this.formatDate(this.value);

            this.yearSelectOpen = false;
            this.yearInputActive = false;

            this.generateCalendar();
        },

        // ----------------------------------------------------
        // MONTH CHANGE
        // ----------------------------------------------------
        prevMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
            this.generateCalendar();
        },

        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
            this.generateCalendar();
        },

        // ----------------------------------------------------
        // SELECT DAY
        // ----------------------------------------------------
        select(date) {
            if (!date) return;

            this.value = date;
            this.model = date.toISOString().slice(0, 10);
            this.formattedValue = this.formatDate(date);

            this.open = false;
            this.panelGlow = false;
            this.panelGlowAnimate = false;
            this.panelGlowStatic = false;
            this.yearSelectOpen = false;
            this.yearInputActive = false;
        },

        clear() {
            this.value = null;
            this.model = null;
            this.formattedValue = '';
            this.open = false;
            this.panelGlow = false;
            this.panelGlowAnimate = false;
            this.panelGlowStatic = false;
            this.yearSelectOpen = false;
            this.yearInputActive = false;
        },

        // ----------------------------------------------------
        // KEYBOARD MOVEMENT
        // ----------------------------------------------------
        move(diff) {
            let base = this.value ? new Date(this.value) : new Date();
            base.setDate(base.getDate() + diff);

            this.value = base;
            this.model = base.toISOString().slice(0, 10);
            this.formattedValue = this.formatDate(base);

            this.currentYear = base.getFullYear();
            this.currentMonth = base.getMonth();
            this.generateCalendar();
        },

        confirm() {
            this.open = false;
            this.panelGlow = false;
            this.panelGlowAnimate = false;
            this.panelGlowStatic = false;
            this.yearSelectOpen = false;
            this.yearInputActive = false;
        }
    };
}
