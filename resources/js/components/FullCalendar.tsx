import { useRef, useEffect, useState } from 'react';
import FullCalendar from '@fullcalendar/react';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import { EventClickArg, DateSelectArg, EventInput } from '@fullcalendar/core';

interface CalendarEvent extends EventInput {
  title: string;
  start: string;
  end?: string;
  description?: string;
  allDay: boolean;
  cancelled: boolean;
  color?: string;
  add_to_google_calendar_url?: string;
  event_url?: string;
}

interface FullCalendarComponentProps {
  initialEvents: CalendarEvent[];
  eventsDataUrl?: string;
  onEventClick?: (event: EventClickArg) => void;
  onDateSelect?: (selectInfo: DateSelectArg) => void;
}

export default function FullCalendarComponent({
  initialEvents,
  eventsDataUrl,
  onEventClick,
  onDateSelect,
}: FullCalendarComponentProps) {
  const calendarRef = useRef<FullCalendar>(null);
  const [isMobile, setIsMobile] = useState(false);

  useEffect(() => {
    const checkMobile = () => {
      setIsMobile(window.innerWidth < 768);
    };
    
    checkMobile();
    window.addEventListener('resize', checkMobile);
    
    return () => window.removeEventListener('resize', checkMobile);
  }, []);

  const handleEventClick = (eventInfo: EventClickArg) => {
    if (onEventClick) {
      onEventClick(eventInfo);
    } else if (eventInfo.event.extendedProps?.event_url) {
      // Default behavior: open event URL in new tab
      window.open(eventInfo.event.extendedProps.event_url, '_blank');
      eventInfo.jsEvent.preventDefault();
    }
  };

  const handleDateSelect = (selectInfo: DateSelectArg) => {
    if (onDateSelect) {
      onDateSelect(selectInfo);
    }
  };

  // Custom event content renderer
  const renderEventContent = (eventInfo: any) => {
    const { event } = eventInfo;
    const isCancelled = event.extendedProps?.cancelled;

    return (
      <div
        className={`fc-event-content ${
          isCancelled ? 'line-through opacity-75' : ''
        }`}
      >
        <div className="fc-event-title font-medium text-sm">{event.title}</div>
      </div>
    );
  };

  return (
    <div className="bg-card rounded-lg border shadow-sm overflow-hidden">
      <FullCalendar
        ref={calendarRef}
        plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin]}
        headerToolbar={
          isMobile
            ? {
                left: 'prev,next',
                center: 'title',
                right: 'today',
              }
            : {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay',
              }
        }
        initialView="dayGridMonth"
        aspectRatio={isMobile ? 1.0 : 1.35}
        editable={false}
        selectable={true}
        selectMirror={true}
        dayMaxEvents={isMobile ? 2 : true}
        dayMaxEventRows={isMobile ? 2 : false}
        moreLinkClick="popover"
        weekends={true}
        initialEvents={initialEvents}
        eventClick={handleEventClick}
        select={handleDateSelect}
        eventContent={renderEventContent}
        eventDisplay="block"
        height="auto"
        // Custom styling classes
        eventClassNames={(arg) => {
          const classes = [
            'cursor-pointer',
            'hover:opacity-90',
            'transition-opacity',
          ];
          if (arg.event.extendedProps?.cancelled) {
            classes.push('opacity-75');
          }
          return classes;
        }}
        // Set event colors based on cancelled status
        eventDidMount={(info) => {
          if (info.event.extendedProps?.cancelled) {
            info.el.style.backgroundColor = '#ef4444';
            info.el.style.borderColor = '#dc2626';
          } else if (info.event.color) {
            info.el.style.backgroundColor = info.event.color;
            info.el.style.borderColor = info.event.color;
          }
        }}
        // Load events dynamically when navigating
        events={
          eventsDataUrl
            ? (info, successCallback, failureCallback) => {
                fetch(
                  `${eventsDataUrl}?start=${info.startStr}&end=${info.endStr}`,
                )
                  .then((response) => response.json())
                  .then((data) => successCallback(data))
                  .catch((error) => failureCallback(error));
              }
            : initialEvents
        }
        // Custom button text
        buttonText={{
          today: 'Today',
          month: 'Month',
          week: 'Week',
          day: 'Day',
        }}
        // Start week on Monday for better UX
        firstDay={1}
        // Show weekend
        weekends={true}
        // Event time format
        eventTimeFormat={{
          hour: 'numeric',
          minute: '2-digit',
          hour12: true,
        }}
        // Slot time format
        slotLabelFormat={{
          hour: 'numeric',
          minute: '2-digit',
          hour12: true,
        }}
      />
    </div>
  );
}
