import EventCard from './EventCard';

interface Event {
  id: number;
  event_name: string;
  description: string;
  active_at: string;
  expire_at?: string;
  cancelled_at: string | null;
  rsvp_count?: number;
  uri: string;
  organization: {
    title: string;
  };
  venue?: {
    name: string;
    address?: string;
    city?: string;
    state?: string;
  };
  service: string;
}

interface EventsListProps {
  events: Event[];
}

interface GroupedEvents {
  [key: string]: {
    date: Date;
    events: Event[];
    label: string;
    dayName: string;
  };
}

export default function EventsList({ events }: EventsListProps) {
  const groupEventsByDate = (events: Event[]): GroupedEvents => {
    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);

    const grouped: GroupedEvents = {};

    events.forEach((event) => {
      const eventDate = new Date(event.active_at);
      const eventDateOnly = new Date(
        eventDate.getFullYear(),
        eventDate.getMonth(),
        eventDate.getDate(),
      );
      const dateKey = eventDateOnly.toISOString().split('T')[0];

      if (!grouped[dateKey]) {
        let label: string;
        let dayName: string;

        if (eventDateOnly.getTime() === today.getTime()) {
          label = 'Today';
          dayName = eventDateOnly.toLocaleDateString('en-US', {
            weekday: 'long',
          });
        } else if (eventDateOnly.getTime() === tomorrow.getTime()) {
          label = 'Tomorrow';
          dayName = eventDateOnly.toLocaleDateString('en-US', {
            weekday: 'long',
          });
        } else {
          // For other dates, show the month and day
          label = eventDateOnly.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
          });
          dayName = eventDateOnly.toLocaleDateString('en-US', {
            weekday: 'long',
          });
        }

        grouped[dateKey] = {
          date: eventDateOnly,
          events: [],
          label,
          dayName,
        };
      }

      grouped[dateKey].events.push(event);
    });

    // Sort events within each group by time
    Object.values(grouped).forEach((group) => {
      group.events.sort(
        (a, b) =>
          new Date(a.active_at).getTime() - new Date(b.active_at).getTime(),
      );
    });

    return grouped;
  };

  const groupedEvents = groupEventsByDate(events);
  const sortedGroups = Object.entries(groupedEvents).sort(
    ([a], [b]) => new Date(a).getTime() - new Date(b).getTime(),
  );

  if (sortedGroups.length === 0) {
    return (
      <div className="text-center py-12">
        <p className="text-muted-foreground">
          No upcoming events at this time.
        </p>
      </div>
    );
  }

  return (
    <div className="space-y-4">
      {sortedGroups.map(([dateKey, group]) => (
        <div key={dateKey} className="space-y-2">
          {/* Date Header */}
          <div className="flex items-center gap-2">
            <div className="flex items-center gap-2">
              <span className="text-lg font-semibold text-foreground">
                {group.label}
              </span>
              <span className="text-sm text-muted-foreground">
                {group.dayName}
              </span>
            </div>
            <div className="h-px bg-border flex-1 ml-4" />
          </div>

          {/* Events for this date */}
          <div className="space-y-2">
            {group.events.map((event) => (
              <EventCard key={event.id} event={event} />
            ))}
          </div>
        </div>
      ))}
    </div>
  );
}
