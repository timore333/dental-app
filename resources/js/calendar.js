import {Calendar} from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

export function initializeCalendar(elementId, events, callbacks = {}) {

    const calendarEl = document.getElementById(elementId);
    const locale = document.documentElement.lang || 'en';
    const isRTL = locale === 'ar';

    if (!calendarEl) {
        console.error(`Calendar element not found: ${elementId}`);
        return null;
    }

    const calendar = new Calendar(calendarEl, {

        locale: locale,
        direction: isRTL ? 'rtl' : 'ltr',

        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],

        initialView: 'timeGridWeek',
        timeZone: 'Africa/Cairo', // Force EET timezone
         firstDay: 6,
        slotMinTime: '12:00:00',   // start at 12 PM
        slotMaxTime: '24:00:00',   // end at 10 PM
        slotDuration: '00:30:00',  // 45 minutes slots

        slotLabelInterval: '00:30:00',
        slotLabelFormat: {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true,
        },

        allDaySlot: false,
        expandRows: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        contentHeight: 'auto',
        editable: true,
        eventStartEditable: true,
        eventDurationEditable: false,
        events: events,

        eventContent: function (info) {
            let props = info.event.extendedProps;

            let html = `
                <div style="padding: 2px;">

                    <div class="event-name">${props.patientName}</div>
                    <div class="event-phone">${props.phone}</div>
                    ${props.file_number ? `<div class="event-file">File: ${props.file_number}</div>` : ''}
                </div>
            `;

            return {html: html};
        },

        dateClick: (info) => {
            if (callbacks.onDateClick) {
                callbacks.onDateClick(info);
            }
        },

        eventClick: (info) => {
            if (callbacks.onEventClick) {
                callbacks.onEventClick(info);
            }
        },

        eventDrop: (info) => {
            if (callbacks.onEventDrop) {
                callbacks.onEventDrop(info);
            }
        },

        eventClassNames: (arg) => {
            return ['status-' + arg.event.extendedProps.status];
        }
    });

    calendar.render();
    return calendar;
}
