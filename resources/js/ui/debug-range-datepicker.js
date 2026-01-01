// tafeld/resources/js/ui/debug-range-datepicker.js

export function debugRangeDatepicker() {
    return {
        /* -------------------------
         * UI state
         * ------------------------- */
        open: false,

        /* -------------------------
         * Data model
         * ------------------------- */
        from: null,
        to: null,
        preset: null,

        /* -------------------------
         * Calendar state
         * ------------------------- */
        currentDate: new Date(),
        focusedDate: null, // internal keyboard / hover cursor

        /* -------------------------
         * Selection state
         * ------------------------- */
        selecting: false,   // true = selecting "to"
        confirmed: false,   // true = range explicitly confirmed

        /* -------------------------
         * Panel handling
         * ------------------------- */
        toggle() {
            if (this.open) {
                this.close();
                return;
            }

            // Wiederöffnen → alte Auswahl verwerfen
            if (this.from && this.to) {
                this.clear();
            }

            this.open = true;
            this.initFocus();
        },

        close() {
            this.open = false;
        },

        /* -------------------------
         * Presets
         * ------------------------- */
        applyPreset(id) {
            const now = new Date();

            this.preset = id;
            this.selecting = false;
            this.confirmed = true;

            switch (id) {
                case 'today':
                    this.from = this.startOfToday();
                    this.to = now;
                    break;

                case 'yesterday':
                    this.from = this.startOfYesterday();
                    this.to = this.endOfYesterday();
                    break;

                case 'last_24h':
                    this.from = new Date(now.getTime() - 24 * 60 * 60 * 1000);
                    this.to = now;
                    break;

                case 'last_7_days':
                    this.from = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                    this.to = now;
                    break;

                case 'last_30_days':
                    this.from = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
                    this.to = now;
                    break;
            }

            if (this.to) {
                this.focusedDate = new Date(this.to);
                this.setMonthFrom(this.focusedDate);
            }

            this.close();
        },

        /* -------------------------
         * Manual selection
         * ------------------------- */
        selectDay(date) {
            this.preset = null;
            this.focusedDate = new Date(date);

            // Neuer Startpunkt oder Neubeginn nach bestätigter Range
            if (!this.from || this.confirmed) {
                this.from = this.startOfDay(date);
                this.to = null;
                this.selecting = true;
                this.confirmed = false;
                return;
            }

            const nextTo = this.endOfDay(date);

            // Mouse-Confirm: gleicher Endpunkt erneut geklickt
            if (
                this.to &&
                !this.selecting &&
                nextTo.getTime() === this.to.getTime()
            ) {
                this.confirmed = true;
                this.close();
                return;
            }

            this.to = nextTo;
            this.selecting = false;
            this.confirmed = false;

            if (this.to < this.from) {
                const tmp = this.from;
                this.from = this.to;
                this.to = tmp;
            }
        },

        /* -------------------------
         * Footer actions
         * ------------------------- */
        setToday() {
            this.preset = null;
            this.from = this.startOfToday();
            this.to = new Date();
            this.selecting = false;
            this.confirmed = true;

            this.focusedDate = new Date(this.to);
            this.setMonthFrom(this.focusedDate);

            this.close();
        },

        clear() {
            this.from = null;
            this.to = null;
            this.preset = null;
            this.selecting = false;
            this.confirmed = false;
            this.focusedDate = null;
        },

        /* -------------------------
         * Display helpers
         * ------------------------- */
        displayLabel() {
            if (this.from && this.to) {
                return `${this.formatDate(this.from)} – ${this.formatDate(this.to)}`;
            }

            if (this.from && !this.to) {
                return `${this.formatDate(this.from)} – …`;
            }

            return 'Von – Bis';
        },

        monthYearLabel() {
            return this.currentDate.toLocaleDateString('de-DE', {
                month: 'long',
                year: 'numeric',
            });
        },

        /* -------------------------
         * Calendar navigation
         * ------------------------- */
        prevMonth() {
            const d = new Date(this.currentDate);
            d.setMonth(d.getMonth() - 1);
            this.currentDate = d;
        },

        nextMonth() {
            const d = new Date(this.currentDate);
            d.setMonth(d.getMonth() + 1);
            this.currentDate = d;
        },

        /* -------------------------
         * Calendar grid
         * ------------------------- */
        calendarDays() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();

            const firstOfMonth = new Date(year, month, 1);
            const lastOfMonth = new Date(year, month + 1, 0);

            const days = [];

            const weekday = firstOfMonth.getDay() === 0
                ? 6
                : firstOfMonth.getDay() - 1;

            for (let i = 0; i < weekday; i++) {
                days.push(null);
            }

            for (let d = 1; d <= lastOfMonth.getDate(); d++) {
                days.push(new Date(year, month, d));
            }

            return days;
        },

        /* -------------------------
         * Day state helpers
         * ------------------------- */
        isToday(date) {
            if (!date) return false;
            const t = new Date();
            return date.toDateString() === t.toDateString();
        },

        isInRange(date) {
            if (!date || !this.from || !this.to) return false;
            return date >= this.startOfDay(this.from)
                && date <= this.endOfDay(this.to);
        },

        isStart(date) {
            if (!date || !this.from) return false;
            return this.startOfDay(date).getTime()
                === this.startOfDay(this.from).getTime();
        },

        isEnd(date) {
            if (!date || !this.to) return false;
            return this.startOfDay(date).getTime()
                === this.startOfDay(this.to).getTime();
        },

        isFocused(date) {
            if (!date || !this.focusedDate) return false;
            return date.toDateString() === this.focusedDate.toDateString();
        },

        // Preview-Zwischenzustand (Hover / Keyboard)
        isSpanPreview(date) {
            if (!date) return false;
            if (!this.selecting || !this.from || this.to) return false;
            if (!this.focusedDate) return false;

            const start = this.startOfDay(this.from);
            const end = this.startOfDay(this.focusedDate);

            const min = start < end ? start : end;
            const max = start > end ? start : end;

            return date > min && date < max;
        },

        /* -------------------------
         * Keyboard handling
         * ------------------------- */
        initFocus() {
            // Panel-Initialisierung: KEIN Fokus setzen
            if (this.from && !this.confirmed) {
                this.setMonthFrom(this.from);
            } else {
                this.setMonthFrom(new Date());
            }
        },

        onArrow(deltaDays) {
            if (!this.open) return;

            // Erste Tastatur-Interaktion → Fokus initialisieren
            if (!this.focusedDate) {
                const t = new Date();
                this.focusedDate = new Date(
                    t.getFullYear(),
                    t.getMonth(),
                    t.getDate()
                );
            }

            const next = this.addDays(this.focusedDate, deltaDays);

            const monthChanged =
                next.getMonth() !== this.currentDate.getMonth() ||
                next.getFullYear() !== this.currentDate.getFullYear();

            if (monthChanged) {
                this.setMonthFrom(next);

                const year = next.getFullYear();
                const month = next.getMonth();

                this.focusedDate = deltaDays > 0
                    ? new Date(year, month, 1)
                    : new Date(year, month + 1, 0);

                return;
            }

            this.focusedDate = next;
        },

        onEscape() {
            this.close();
        },

        onConfirm() {
            if (!this.open) return;

            // 🔹 FIX: fokussierter Tag überschreibt bestehendes to
            if (this.from && this.to && !this.selecting) {
                if (
                    this.focusedDate &&
                    this.startOfDay(this.focusedDate).getTime() !==
                    this.startOfDay(this.to).getTime()
                ) {
                    this.to = this.endOfDay(this.focusedDate);

                    if (this.to < this.from) {
                        const tmp = this.from;
                        this.from = this.to;
                        this.to = tmp;
                    }
                }

                this.confirmed = true;
                this.close();
                return;
            }

            if (this.focusedDate) {
                this.selectDay(this.focusedDate);
            }
        },

        /* -------------------------
         * Helpers
         * ------------------------- */
        addDays(date, days) {
            const d = new Date(date);
            d.setDate(d.getDate() + days);
            return d;
        },

        setMonthFrom(date) {
            this.currentDate = new Date(
                date.getFullYear(),
                date.getMonth(),
                1
            );
        },

        startOfDay(date) {
            const d = new Date(date);
            d.setHours(0, 0, 0, 0);
            return d;
        },

        endOfDay(date) {
            const d = new Date(date);
            d.setHours(23, 59, 59, 999);
            return d;
        },

        startOfToday() {
            return this.startOfDay(new Date());
        },

        startOfYesterday() {
            const d = new Date();
            d.setDate(d.getDate() - 1);
            return this.startOfDay(d);
        },

        endOfYesterday() {
            const d = new Date();
            d.setDate(d.getDate() - 1);
            return this.endOfDay(d);
        },

        formatDate(date) {
            return date.toLocaleDateString('de-DE');
        },

        /* -------------------------
         * UI helpers
         * ------------------------- */
        hasValue() {
            return this.from !== null;
        },

        isPreset(id) {
            return this.preset === id;
        }
    };
}
