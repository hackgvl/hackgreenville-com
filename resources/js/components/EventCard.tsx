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
    });
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric',
    });
  };

  const isLive = () => {
    const now = new Date();
    const startTime = new Date(event.active_at);
    const endTime = event.expire_at
      ? new Date(event.expire_at)
      : new Date(startTime.getTime() + 2 * 60 * 60 * 1000);

    return now >= startTime && now <= endTime && !event.cancelled_at;
  };

  const getVenueDisplay = () => {
    if (!event.venue) return 'Online';
    return event.venue.name || 'Venue TBD';
  };

  return (
    <Card className="group hover:shadow-lg transition-all duration-200 border border-border/50 hover:border-border cursor-pointer">
      <a href={`/events/${event.id}`} className="block">
        <CardContent className="p-4">
          <div className="flex gap-4">
            {/* Date/Time Block */}
            <div className="flex-shrink-0">
              <div className="text-center">
                <div className="text-sm font-semibold text-primary">
                  {formatDate(event.active_at)}
                </div>
                <div className="text-xs text-muted-foreground mt-0.5">
                  {formatTime(event.active_at)}
                </div>
              </div>
            </div>

            {/* Event Details */}
            <div className="flex-1 min-w-0 flex flex-col">
              {/* Status badges and Event Title - Aligned with date */}
              <div className="flex flex-col">
                {/* Status badges */}
                {(isLive() || event.cancelled_at) && (
                  <div className="flex items-center gap-2 mb-1">
                    {isLive() && (
                      <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-500 text-white">
                        <span className="w-1.5 h-1.5 bg-white rounded-full mr-1.5 animate-pulse"></span>
                        LIVE
                      </span>
                    )}
                    {event.cancelled_at && (
                      <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        CANCELLED
                      </span>
                    )}
                  </div>
                )}

                {/* Event Title - Aligned with date block */}
                <h3 className="font-semibold text-foreground leading-snug mb-2 group-hover:text-primary transition-colors">
                  <span
                    className="block overflow-hidden"
                    style={{
                      display: '-webkit-box',
                      WebkitLineClamp: 2,
                      WebkitBoxOrient: 'vertical',
                    }}
                  >
                    {event.event_name}
                  </span>
                </h3>
              </div>

              {/* Organization & Location */}
              <div className="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3 text-sm text-muted-foreground flex-grow">
                <span className="font-medium">{event.organization.title}</span>
                <div className="flex items-center gap-1">
                  <MapPin className="w-3 h-3 flex-shrink-0" />
                  <span className="truncate">{getVenueDisplay()}</span>
                </div>
              </div>
            </div>

            {/* Actions - Vertically centered */}
            <div className="flex-shrink-0 self-center">
              <div className="flex flex-col gap-2">
                {/* Primary Action Button */}
                {!event.cancelled_at && isLive() && (
                  <Button
                    size="sm"
                    className="h-8 px-3 text-sm"
                    asChild
                    onClick={(e) => e.stopPropagation()}
                  >
                    <a
                      href={event.uri}
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      <Clock className="w-3 h-3 mr-1.5" />
                      Join Now
                    </a>
                  </Button>
                )}
              </div>
            </div>
          </div>
        </CardContent>
      </a>
    </Card>
  );
}
