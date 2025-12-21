{{-- resources/views/components/ui/date/datepicker.blade.php --}}

@props([
    'model' => null,
    'placeholder' => 'Datum auswählen',
    'holidays' => [],
])

<div
    x-data="datepickerComponent({
        model: @entangle($attributes->wire('model')),
        holidays: @js($holidays ?? []),
    })"
    class="relative w-full"
>

    {{-- TRIGGER --}}
    <div
        @click="initCalendar(); computeFlip(); openDropdown(); $nextTick(() => $refs.calendarPanel?.focus())"
        class="select-trigger cursor-pointer flex items-center gap-2"
    >
        <x-ui.icon name="calendar" class="text-muted w-5 h-5" />
        <span x-text="formattedValue || '{{ $placeholder }}'" class="flex-1"></span>
        <x-ui.icon name="chevron-down" class="text-muted w-4 h-4" />
    </div>

    {{-- PANEL --}}
    <div
        x-show="open"
        x-transition
        @click.outside="open = false; panelGlow = false; panelGlowAnimate = false; panelGlowStatic = false"
        tabindex="0"
        x-ref="calendarPanel"
        :class="[
            flipUp ? 'bottom-full mb-2 origin-bottom' : 'top-full mt-2 origin-top',
            panelGlowAnimate ? 'dp-glow-animate' : '',
            panelGlowStatic ? 'dp-glow-static' : ''
        ]"
        @keydown.escape.stop="open = false; panelGlow = false; panelGlowAnimate = false; panelGlowStatic = false"

        {{-- FIXED ARROW KEYS (CTRL-GUARD) --}}
        @keydown.arrow-right.prevent="if (!$event.ctrlKey) move(1)"
        @keydown.arrow-left.prevent="if (!$event.ctrlKey) move(-1)"

        @keydown.arrow-up.prevent="move(-7)"
        @keydown.arrow-down.prevent="move(7)"
        @keydown.enter.prevent="confirm()"

        {{-- CTRL-HOTKEYS --}}
        @keydown.ctrl.arrow-left.prevent.stop="prevMonth()"
        @keydown.ctrl.arrow-right.prevent.stop="nextMonth()"

        class="absolute z-50 w-64 min-h-[320px] bg-elevated border border-default rounded-lg shadow-xl shadow-black/20"
    >

        {{-- HEADER --}}
        <div class="dp-header px-3 py-2 border-b border-default">

            {{-- PREV MONTH --}}
            <button @click.prevent.stop="prevMonth()" class="dp-nav-btn">
                <x-ui.icon name="chevron-left" class="w-4 h-4"/>
            </button>

            {{-- MONTH + YEAR --}}
            <div class="flex items-center gap-3">

                {{-- MONTH --}}
                <span class="dp-month" x-text="monthName"></span>

                {{-- YEAR DISPLAY --}}
                <span
                    class="dp-year cursor-pointer"
                    @click.stop="openYearSelect()"
                    @dblclick.stop="openYearInput()"
                    x-text="currentYear"
                ></span>

            </div>

            {{-- NEXT MONTH --}}
            <button @click.prevent.stop="nextMonth()" class="dp-nav-btn">
                <x-ui.icon name="chevron-right" class="w-4 h-4"/>
            </button>

        </div>

        {{-- YEAR SELECT OVERLAY --}}
        <div
            x-show="yearSelectOpen || yearInputActive"
            class="absolute inset-0 bg-elevated flex flex-col items-center justify-start z-[60] p-4"
        >

            {{-- YEAR INPUT --}}
            <input
                x-show="yearInputActive"
                x-ref="yearInput"
                x-model="yearInputValue"
                @keydown.enter.prevent="confirmYearInput()"
                @keydown.tab.prevent="confirmYearInput()"
                @keydown.esc.prevent="cancelYearInput()"
                class="dp-year-input w-24 text-center border border-default rounded bg-surface text-default py-1 px-2 mb-2"
            />

            {{-- YEAR LIST --}}
            <div
                x-show="yearSelectOpen"
                class="dp-year-list max-h-80 overflow-y-auto w-full text-center space-y-1"
            >
                <template x-for="year in years" :key="year">
                    <div
                        @click="selectYear(year)"
                        class="dp-year-item py-1 rounded cursor-pointer hover:bg-hover"
                        :class="year === currentYear ? 'dp-year-active' : ''"
                        x-text="year"
                    ></div>
                </template>
            </div>

        </div>

        {{-- WEEKDAYS --}}
        <div class="dp-weekdays px-3 py-2" x-show="!yearSelectOpen && !yearInputActive">
            <template x-for="(day, idx) in weekdays">
                <div
                    x-text="day"
                    :class="idx === 5 ? 'dp-weekday-sat' : (idx === 6 ? 'dp-weekday-sun' : '')"
                ></div>
            </template>
        </div>

        {{-- DAYS --}}
        <div class="dp-grid px-3 pb-1 mb-3 text-sm border-b border-default h-[10.5rem]"
             x-show="!yearSelectOpen && !yearInputActive">
            <template x-for="day in days" :key="day.key">
                <div
                    @click="select(day.date)"
                    class="dp-day"
                    :class="day.classes"
                    x-bind:title="day.holidayName || ''"
                    x-text="day.label"
                ></div>
            </template>
        </div>

        {{-- FOOTER --}}
        <div class="flex justify-between px-3 pb-3 text-xs text-default">
            <button @click="select(new Date())" class="hover:text-brand-600">Heute</button>
            <button @click="clear()" class="hover:text-danger">Löschen</button>
        </div>

    </div>
</div>
