@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/date/debug-range-datepicker.blade.php -->
    <!-- {{ $__path }} -->
@endif

<div
    class="debug-range-picker relative"
    x-data="debugRangeDatepicker()"
    @click.outside="close()"
>
    {{-- Input / Trigger --}}
    <div
        class="debug-range-input flex items-center gap-2 border rounded-md px-3 py-2 cursor-pointer"
        :class="{ 'is-active': open }"
        tabindex="0"
        role="button"
        @click="toggle()"
        @keydown.enter.prevent="!open && toggle()"
        @keydown.space.prevent="!open && toggle()"
    >
        <span
            class="debug-range-value text-base"
            :class="hasValue() ? 'is-filled text-default' : 'is-empty text-muted'"
            x-text="displayLabel()"
        ></span>

        <span class="debug-range-icons ml-auto flex items-center gap-2">
            <x-ui.clear-button
                x-show="hasValue()"
                @click.stop="clear()"
                aria-label="Clear date range"
            />

            <span class="debug-range-calendar">
                <x-ui.icon
                    name="calendar-days"
                    class="w-4 h-4"
                />
            </span>
        </span>
    </div>

    {{-- Overlay Panel --}}
    <div
        class="debug-range-panel absolute z-50 mt-2"
        x-show="open"
        x-transition
        tabindex="-1"
        x-init="$watch('open', v => v && $el.focus())"
        @keydown.escape.prevent="onEscape()"
        @keydown.enter.prevent="onConfirm()"
        @keydown.space.prevent="onConfirm()"
        @keydown.arrow-left.prevent="onArrow(-1)"
        @keydown.arrow-right.prevent="onArrow(1)"
        @keydown.arrow-up.prevent="onArrow(-7)"
        @keydown.arrow-down.prevent="onArrow(7)"
    >
        {{-- Presets --}}
        <div class="debug-range-presets flex flex-wrap gap-2 p-2 border-b">
            <button type="button" class="preset-btn" :class="{ 'is-active': isPreset('today') }" @click="applyPreset('today')">Heute</button>
            <button type="button" class="preset-btn" :class="{ 'is-active': isPreset('yesterday') }" @click="applyPreset('yesterday')">Gestern</button>
            <button type="button" class="preset-btn" :class="{ 'is-active': isPreset('last_24h') }" @click="applyPreset('last_24h')">Letzte 24h</button>
            <button type="button" class="preset-btn" :class="{ 'is-active': isPreset('last_7_days') }" @click="applyPreset('last_7_days')">Letzte 7 Tage</button>
            <button type="button" class="preset-btn" :class="{ 'is-active': isPreset('last_30_days') }" @click="applyPreset('last_30_days')">Letzte 30 Tage</button>
        </div>

        {{-- Kalender Header --}}
        <div class="debug-range-calendar-header flex items-center justify-between px-3 py-2">
            <button type="button" class="calendar-nav prev" @click="prevMonth()">
                <x-ui.icon name="chevron-left" class="w-4 h-4"/>
            </button>
            <div class="calendar-current text-base font-medium" x-text="monthYearLabel()"></div>
            <button type="button" class="calendar-nav next" @click="nextMonth()">
                <x-ui.icon name="chevron-right" class="w-4 h-4"/>
            </button>
        </div>

        {{-- Kalender Grid --}}
        <div class="debug-range-calendar-grid px-3 pb-3">
            <div class="calendar-weekdays grid grid-cols-7 text-xs mb-1">
                <span class="calendar-weekday">Mo</span>
                <span class="calendar-weekday">Di</span>
                <span class="calendar-weekday">Mi</span>
                <span class="calendar-weekday">Do</span>
                <span class="calendar-weekday">Fr</span>
                <span class="calendar-saturday">Sa</span>
                <span class="calendar-sunday">So</span>
            </div>

            <div class="calendar-days grid grid-cols-7 gap-1">
                <template x-for="(date, index) in calendarDays()" :key="index">
                    <button
                        type="button"
                        class="calendar-day"
                        :class="{
                            'is-focused-day': isFocused(date),
                            'is-today': isToday(date),
                            'is-in-range': isInRange(date),
                            'is-start': isStart(date),
                            'is-span-preview': isSpanPreview(date),
                            'is-end': isEnd(date),
                            'is-saturday': date && date.getDay() === 6,
                            'is-sunday': date && date.getDay() === 0,
                            'is-disabled': !date
                        }"
                        @mouseenter="date && selecting && (focusedDate = date)"
                        @click="date && selectDay(date)"
                        x-text="date ? date.getDate() : ''"
                    ></button>
                </template>
            </div>
        </div>

        {{-- Footer --}}
        <div class="debug-range-footer flex items-center justify-between px-3 py-2 border-t">
            <button type="button" class="footer-today text-sm" @click="setToday()">Heute</button>
            <button type="button" class="footer-clear text-sm text-muted" @click="clear()">Löschen</button>
        </div>
    </div>
</div>
