<div>
    <div
        class="block justify-between items-center p-4 mx-4 mb-5 mt-9 bg-white rounded-2xl shadow-lg shadow-gray-200 sm:flex lg:mt-5">
        <div class="flex items-center divide-x divide-gray-100">


        </div>

        <div class="hidden items-center space-y-3 space-x-0 sm:flex sm:space-y-0 sm:space-x-3">
            <a href="#"
               class="inline-flex justify-center p-1 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2
                          2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2
                           0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>

            </a>
            <a href="#"
               class="inline-flex justify-center p-1 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                          clip-rule="evenodd"></path>
                </svg>
            </a>
            <a href="#"
               class="inline-flex justify-center p-1 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                          clip-rule="evenodd"></path>
                </svg>
            </a>
            <span class="font-normal text-gray-500 sm:text-xs md:text-sm">Show <span
                    class="font-semibold text-gray-900">1-25</span> of
                <span class="font-semibold text-gray-900">2290</span></span>
        </div>
    </div>


    <div class="flex flex-col mx-4">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden mb-6 rounded-2xl">

                    <div id="calendar" wire:ignore
                         class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <livewire:modal/>


    <script>

        document.addEventListener('livewire:init', () => {

            window.initializeCalendar('calendar', @json($calendarEvents), {

                onDateClick: function (info) { @this.dispatch('dateClick', {dateStr: info.dateStr})
                },

                onEventClick: function (info) { @this.dispatch('eventClick', {appointmentId: parseInt(info.event.id)})
                },

                onEventDrop: function (info) { @this.dispatch('eventDrop', {
                    appointmentId: parseInt(info.event.id),
                    newStart: info.event.startStr
                })
                }

            });

            Livewire.on('appointment-created', (event) => {
                calendar.addEvent({
                    id: event.id,
                    title: event.title,
                    start: event.start,
                    end: event.end,
                    extendedProps: event.extendedProps
                });
            });




        });
    </script>


    <style>
        /* FullCalendar colors */
        .status-scheduled {
            background-color: #6ccbe8 !important;
            color: #efe9e9;
        }

        .status-completed {
            background-color: #5ddfb4 !important;
            color: #efe9e9;
        }

        .status-cancelled {
            background-color: #e68484 !important;
            color: #efe9e9;
        }

        .status-no-show {
            background-color: #F97316 !important;
            color: #efe9e9;
        }

        .fc .fc-event {
            cursor: pointer;
            border: none;
        }

        .fc .fc-event:hover {
            opacity: 0.8;
        }

        /* cell hight */
        .fc .fc-timegrid-slot {
            border-bottom: 0px;
            height: 4em;
        }

        /* Make time-grid events fill the slot height */
        .fc-timegrid-event {
            inset: 0 !important;
            min-height: 100% !important;
            height: 100% !important;
        }

        /* Improve readability */
        .fc-timegrid-event .fc-event-main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 6px 8px;
        }

         Allow text wrapping
        .fc-event-title {
            white-space: normal;
            line-height: 1.4;
        }

        .fc .fc-event {
            cursor: pointer;
            border: none;
            min-height: 4em !important;
        }

        .fc .fc-toolbar.fc-header-toolbar {
            margin-bottom: 1.25rem !important;
            margin-top: 1.25rem;
        }

        .fc .fc-button {
            padding: 0.7em 1.65em;
        }

        .fc .fc-button-primary {
            background-color: #aa5ca9;
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active {
            background-color: #7aa268;
            border-color: #7aa268;
        }

        .fc .fc-toolbar-title {
            font-size: 1.75em;
            margin: 1px 5rem;
        }

        .fc-event.nice-event {
            font-weight: 50;
            border-width: 3px;
        }

        /*.fc-event-main {*/
        /*    white-space: normal !important;*/
        /*    padding: 4px;*/
        /*    line-height: 1.3;*/
        /*}*/

        /*.fc-event-title {*/
        /*    font-weight: normal;*/
        /*}*/

        /*.event-time {*/
        /*    font-weight: bold;*/
        /*    font-size: 0.85em;*/
        /*}*/

        /*.event-name {*/
        /*    font-size: 0.9em;*/
        /*    margin-top: 2px;*/
        /*}*/

        /*.event-phone {*/
        /*    font-size: 0.85em;*/
        /*    color: rgba(255, 255, 255, 0.9);*/
        /*    margin-top: 1px;*/
        /*}*/

        /*.event-file {*/
        /*    font-size: 0.8em;*/
        /*    color: rgba(255, 255, 255, 0.85);*/
        /*    margin-top: 1px;*/
        /*}*/

    </style>
</div>
