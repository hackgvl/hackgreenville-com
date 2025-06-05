import { Clock, MapPin, ExternalLink } from 'lucide-react';
import { Button } from './ui/button';
import { Card, CardContent } from './ui/card';

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

interface EventCardProps {
  event: Event;
}

export default function EventCard({ event }: EventCardProps) {
  const formatTime = (dateString: string) => {
    return new Date(dateString).toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
      timeZoneName: 'short',
    });
  };

  const formatTimeRange = (start: string, end?: string) => {
    const startTime = formatTime(start);
    if (end) {
      const endTime = formatTime(end);
      return `${startTime} - ${endTime}`;
    }
    return startTime;
  };

  const isLive = () => {
    const now = new Date();
    const startTime = new Date(event.active_at);
    const endTime = event.expire_at
      ? new Date(event.expire_at)
      : new Date(startTime.getTime() + 2 * 60 * 60 * 1000); // Default 2 hours

    return now >= startTime && now <= endTime && !event.cancelled_at;
  };

  const getVenueDisplay = () => {
    if (!event.venue) {
      return 'Online';
    }

    return event.venue.name || 'Venue TBD';
  };

  return (
    <Card className="hover:shadow-md transition-shadow bg-card border">
      <CardContent className="p-3">
        <div className="space-y-2">
          {/* Time and Live Status */}
          <div className="flex items-center gap-2">
            {isLive() && (
              <span className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                • LIVE
              </span>
            )}
            <span className="text-sm font-medium text-orange-600 dark:text-orange-400">
              {formatTimeRange(event.active_at, event.expire_at)}
            </span>
          </div>

          {/* Event Title */}
          <h3 className="font-semibold text-foreground leading-tight">
            {event.event_name}
          </h3>

          {/* Organization and Venue - Single Line */}
          <div className="flex items-center gap-3 text-sm text-muted-foreground">
            <span>By {event.organization.title}</span>
            <div className="flex items-center gap-1">
              <MapPin className="w-3 h-3" />
              <span>{getVenueDisplay()}</span>
            </div>
          </div>

          {/* Actions and Status */}
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-2">
              {/* Cancelled Status */}
              {event.cancelled_at && (
                <span className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-destructive/10 text-destructive">
                  CANCELLED
                </span>
              )}

              {/* Live Check In Button */}
              {!event.cancelled_at && isLive() && (
                <Button
                  size="sm"
                  className="bg-black text-white hover:bg-gray-800 dark:bg-white dark:text-black dark:hover:bg-gray-200"
                  asChild
                >
                  <a href={event.uri} target="_blank" rel="noopener noreferrer">
                    <Clock className="w-3 h-3 mr-1" />
                    Check In
                  </a>
                </Button>
              )}
            </div>

            {/* View Event Link - Always visible */}
            <Button variant="ghost" size="sm" asChild>
              <a href={`/events/${event.id}`}>
                View Details <ExternalLink className="w-3 h-3 ml-1" />
              </a>
            </Button>
          </div>
        </div>
      </CardContent>
    </Card>
  );
}
