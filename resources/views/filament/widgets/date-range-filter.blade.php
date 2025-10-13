<x-filament-widgets::widget>
    <x-filament::section>
        @php
            $start = $startDate;
            $end = $endDate;

            $today = now()->format('Y-m-d');
            $yesterday = now()->subDay()->format('Y-m-d');
            $sevenDaysAgo = now()->subDays(6)->format('Y-m-d');
            $monthAgo = now()->subDays(29)->format('Y-m-d');
            $allTimeStart = '2000-01-01'; // Начало отсчета для "Всё время"

            $isToday = $start == $today && $end == $today;
            $isYesterday = $start == $yesterday && $end == $yesterday;
            $is7Days = $start == $sevenDaysAgo && $end == $today;
            $isMonth = $start == $monthAgo && $end == $today;
            $isAllTime = $start == $allTimeStart && $end == $today;
            $isCustom = !$isToday && !$isYesterday && !$is7Days && !$isMonth && !$isAllTime;
        @endphp

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1rem; font-weight: 600;">{{ __('dashboard.date_filter.title') }}</h3>
                <span style="font-size: 0.875rem; color: #6b7280;">
                    {{ date('d.m.Y', strtotime($start)) }} - {{ date('d.m.Y', strtotime($end)) }}
                </span>
            </div>

            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                <x-filament::button
                    wire:click="setToday"
                    :color="$isToday ? 'primary' : 'gray'"
                    :outlined="!$isToday"
                    size="sm"
                >
                    {{ __('dashboard.date_filter.today') }}
                </x-filament::button>

                <x-filament::button
                    wire:click="setYesterday"
                    :color="$isYesterday ? 'primary' : 'gray'"
                    :outlined="!$isYesterday"
                    size="sm"
                >
                    {{ __('dashboard.date_filter.yesterday') }}
                </x-filament::button>

                <x-filament::button
                    wire:click="setLast7Days"
                    :color="$is7Days ? 'primary' : 'gray'"
                    :outlined="!$is7Days"
                    size="sm"
                >
                    {{ __('dashboard.date_filter.last_7_days') }}
                </x-filament::button>

                <x-filament::button
                    wire:click="setLastMonth"
                    :color="$isMonth ? 'primary' : 'gray'"
                    :outlined="!$isMonth"
                    size="sm"
                >
                    {{ __('dashboard.date_filter.last_month') }}
                </x-filament::button>

                <x-filament::button
                    wire:click="setAllTime"
                    :color="$isAllTime ? 'primary' : 'gray'"
                    :outlined="!$isAllTime"
                    size="sm"
                >
                    {{ __('dashboard.date_filter.all_time') }}
                </x-filament::button>

                <div
                    x-data="{
                        pickerInstance: null,
                        startDate: '{{ $start }}',
                        endDate: '{{ $end }}',
                        init() {
                            this.$nextTick(() => {
                                this.loadFlatpickr();
                            });
                        },
                        loadFlatpickr() {
                            const self = this;
                            if (typeof flatpickr === 'undefined') {
                                const script = document.createElement('script');
                                script.src = 'https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js';
                                script.onload = () => self.initPicker();
                                document.head.appendChild(script);

                                const link = document.createElement('link');
                                link.rel = 'stylesheet';
                                link.href = 'https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css';
                                document.head.appendChild(link);
                            } else {
                                this.initPicker();
                            }
                        },
                        initPicker() {
                            const self = this;

                            // Парсим даты из формата Y-m-d в объекты Date
                            const [startY, startM, startD] = this.startDate.split('-');
                            const [endY, endM, endD] = this.endDate.split('-');
                            const startDateObj = new Date(startY, parseInt(startM) - 1, startD);
                            const endDateObj = new Date(endY, parseInt(endM) - 1, endD);

                            // Создаем видимый input для привязки календаря
                            const fakeInput = document.createElement('input');
                            fakeInput.type = 'text';
                            fakeInput.style.position = 'absolute';
                            fakeInput.style.opacity = '0';
                            fakeInput.style.pointerEvents = 'none';
                            this.$refs.button.appendChild(fakeInput);

                            this.pickerInstance = flatpickr(fakeInput, {
                                mode: 'range',
                                dateFormat: 'd.m.Y',
                                defaultDate: [startDateObj, endDateObj],
                                locale: {
                                    firstDayOfWeek: 1,
                                    rangeSeparator: ' - ',
                                    weekdays: {
                                        shorthand: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
                                        longhand: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота']
                                    },
                                    months: {
                                        shorthand: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
                                        longhand: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']
                                    }
                                },
                                onClose: function(selectedDates) {
                                    if (selectedDates.length === 2) {
                                        const startDate = selectedDates[0].toISOString().split('T')[0];
                                        const endDate = selectedDates[1].toISOString().split('T')[0];
                                        const baseUrl = window.location.origin + window.location.pathname;
                                        const url = baseUrl + '?start_date=' + startDate + '&end_date=' + endDate;
                                        window.location.href = url;
                                    }
                                }
                            });
                        },
                        openPicker() {
                            if (this.pickerInstance) {
                                this.pickerInstance.open();
                            }
                        }
                    }"
                    x-ref="container"
                    style="position: relative; display: inline-block;"
                >
                    <input type="text" x-ref="hiddenInput" style="display: none;" />

                    <div x-ref="button">
                        <x-filament::button
                            @click="openPicker()"
                            :color="$isCustom ? 'primary' : 'gray'"
                            :outlined="!$isCustom"
                            size="sm"
                            icon="heroicon-o-calendar"
                        >
                            {{ __('dashboard.date_filter.custom_date') }}
                        </x-filament::button>
                    </div>
                </div>

                @if(request()->query('start_date'))
                    <x-filament::button
                        wire:click="resetFilter"
                        color="gray"
                        icon="heroicon-o-arrow-path"
                        outlined
                        size="sm"
                    >
                        {{ __('dashboard.date_filter.reset') }}
                    </x-filament::button>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
