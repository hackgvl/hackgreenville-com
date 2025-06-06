import { Head, Link } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
  Calendar,
  MapPin,
  ExternalLink,
  ArrowLeft,
  Users,
  Clock,
  Building2,
  CalendarPlus,
} from 'lucide-react';

interface Event {
  id: number;
  event_name: string;
  description: string;
  active_at: string;
  expire_at?: string;
  cancelled_at: string | null;
  rsvp_count?: number;
  uri: string;
  service: string;
  organization: {
    id: number;
    title: string;
    slug: string;
  };
  venue?: {
    name: string;
    address?: string;
    city?: string;
    state?: string;
  };
  google_calendar_url: string;
}

interface EventShowProps {
  event: Event;
}

export default function EventShow({ event }: EventShowProps) {
  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    });
  };

  const formatTime = (dateString: string) => {
    return new Date(dateString).toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
      timeZoneName: 'short',
    });
  };

  const getFullAddress = () => {
    if (!event.venue) return 'Online Event';

    const parts = [
      event.venue.name,
      event.venue.address,
      event.venue.city,
      event.venue.state,
    ].filter(Boolean);

    return parts.join(', ');
  };

  const isLive = () => {
    const now = new Date();
    const startTime = new Date(event.active_at);
    const endTime = event.expire_at
      ? new Date(event.expire_at)
      : new Date(startTime.getTime() + 2 * 60 * 60 * 1000);

    return now >= startTime && now <= endTime && !event.cancelled_at;
  };

  const isUpcoming = () => {
    const now = new Date();
    const startTime = new Date(event.active_at);
    return startTime > now && !event.cancelled_at;
  };

  const isPast = () => {
    const now = new Date();
    const endTime = event.expire_at
      ? new Date(event.expire_at)
      : new Date(new Date(event.active_at).getTime() + 2 * 60 * 60 * 1000);
    return endTime < now;
  };

  const cleanDescription = (html: string) => {
    return html
      .replace(/<[^>]*>/g, '')
      .replace(/&amp;/g, '&')
      .replace(/&lt;/g, '<')
      .replace(/&gt;/g, '>')
      .replace(/&quot;/g, '"')
      .replace(/&#x27;/g, "'")
      .replace(/&nbsp;/g, ' ')
      .trim();
  };

  return (
    <AppLayout title={`${event.event_name} - HackGreenville`}>
      <Head>
        <meta
          name="description"
          content={`${event.event_name} hosted by ${
            event.organization.title
          } in Greenville, SC. ${cleanDescription(event.description).substring(
            0,
            160,
          )}`}
        />
      </Head>

      <div className="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
        {/* Header */}
        <div className="mb-6">
          <div className="flex items-center gap-4 mb-4">
            <Button variant="ghost" size="sm" asChild>
              <Link href="/events">
                <ArrowLeft className="w-4 h-4 mr-2" />
                Back to Events
              </Link>
            </Button>
          </div>

          <div className="space-y-4">
            {/* Status Badges */}
            <div className="flex flex-wrap gap-2">
              {isLive() && (
                <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                  • LIVE
                </span>
              )}
              {event.cancelled_at && (
                <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-destructive/10 text-destructive">
                  CANCELLED
                </span>
              )}
              {isPast() && !event.cancelled_at && (
                <span className="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                  PAST EVENT
                </span>
              )}
            </div>

            {/* Title */}
            <div>
              <h1 className="text-3xl sm:text-4xl font-bold text-foreground mb-2">
                {event.event_name}
              </h1>
              <p className="text-lg text-muted-foreground">
                Hosted by {event.organization.title}
              </p>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Main Content */}
          <div className="lg:col-span-2 space-y-6">
            {/* Event Details */}
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <Calendar className="w-5 h-5" />
                  Event Details
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <h4 className="font-medium text-sm mb-1 text-muted-foreground">
                      Date
                    </h4>
                    <p className="text-foreground">
                      {formatDate(event.active_at)}
                    </p>
                  </div>
                  <div>
                    <h4 className="font-medium text-sm mb-1 text-muted-foreground">
                      Time
                    </h4>
                    <p className="text-foreground">
                      {formatTime(event.active_at)}
                    </p>
                  </div>
                  {event.venue && (
                    <div className="sm:col-span-2">
                      <h4 className="font-medium text-sm mb-1 text-muted-foreground">
                        Location
                      </h4>
                      <div className="flex items-start gap-2">
                        <MapPin className="w-4 h-4 mt-0.5 text-muted-foreground" />
                        <p className="text-foreground">{getFullAddress()}</p>
                      </div>
                    </div>
                  )}
                  {event.rsvp_count !== null && event.rsvp_count > 0 && (
                    <div>
                      <h4 className="font-medium text-sm mb-1 text-muted-foreground">
                        Attendees
                      </h4>
                      <div className="flex items-center gap-2">
                        <Users className="w-4 h-4 text-muted-foreground" />
                        <p className="text-foreground">
                          {event.rsvp_count} attending
                        </p>
                      </div>
                    </div>
                  )}
                </div>
              </CardContent>
            </Card>

            {/* Description */}
            {event.description && (
              <Card>
                <CardHeader>
                  <CardTitle>About This Event</CardTitle>
                </CardHeader>
                <CardContent>
                  <div
                    className="prose prose-sm max-w-none text-foreground"
                    dangerouslySetInnerHTML={{
                      __html: event.description.replace(/\n/g, '<br>'),
                    }}
                  />
                </CardContent>
              </Card>
            )}

            {/* Organization */}
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <Building2 className="w-5 h-5" />
                  Organizer
                </CardTitle>
              </CardHeader>
              <CardContent>
                <div className="flex items-center justify-between">
                  <div>
                    <h3 className="font-medium text-foreground">
                      {event.organization.title}
                    </h3>
                    <p className="text-sm text-muted-foreground">
                      Event organizer
                    </p>
                  </div>
                  <Button variant="outline" size="sm" asChild>
                    <Link href={`/orgs/${event.organization.slug}`}>
                      View Profile
                    </Link>
                  </Button>
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Sidebar */}
          <div className="space-y-4">
            {/* RSVP Section */}
            <Card>
              <CardContent className="p-6">
                <div className="space-y-4">
                  {isLive() && (
                    <div className="text-center">
                      <h3 className="font-semibold text-lg mb-2">
                        Event is Live!
                      </h3>
                      <Button size="lg" className="w-full" asChild>
                        <a
                          href={event.uri}
                          target="_blank"
                          rel="noopener noreferrer"
                        >
                          <Clock className="w-4 h-4 mr-2" />
                          Join Now
                        </a>
                      </Button>
                    </div>
                  )}

                  {isUpcoming() && (
                    <div className="text-center">
                      <h3 className="font-semibold text-lg mb-2">
                        Ready to attend?
                      </h3>
                      <Button size="lg" className="w-full" asChild>
                        <a
                          href={event.uri}
                          target="_blank"
                          rel="noopener noreferrer"
                        >
                          <ExternalLink className="w-4 h-4 mr-2" />
                          RSVP
                        </a>
                      </Button>
                    </div>
                  )}

                  {isPast() && !event.cancelled_at && (
                    <div className="text-center">
                      <h3 className="font-semibold text-lg mb-2">
                        This event has ended
                      </h3>
                      <Button
                        variant="outline"
                        size="lg"
                        className="w-full"
                        asChild
                      >
                        <a
                          href={event.uri}
                          target="_blank"
                          rel="noopener noreferrer"
                        >
                          <ExternalLink className="w-4 h-4 mr-2" />
                          View Event Details
                        </a>
                      </Button>
                    </div>
                  )}

                  {event.cancelled_at && (
                    <div className="text-center">
                      <h3 className="font-semibold text-lg mb-2 text-destructive">
                        Event Cancelled
                      </h3>
                      <p className="text-sm text-muted-foreground">
                        This event has been cancelled by the organizer.
                      </p>
                    </div>
                  )}

                  {/* Add to Calendar */}
                  {!event.cancelled_at && (
                    <div className="pt-4 border-t">
                      <Button
                        variant="outline"
                        size="sm"
                        className="w-full"
                        asChild
                      >
                        <a
                          href={event.google_calendar_url}
                          target="_blank"
                          rel="noopener noreferrer"
                        >
                          <CalendarPlus className="w-4 h-4 mr-2" />
                          Add to Google Calendar
                        </a>
                      </Button>
                    </div>
                  )}
                </div>
              </CardContent>
            </Card>

            {/* Quick Info */}
            <Card>
              <CardHeader>
                <CardTitle className="text-base">Quick Info</CardTitle>
              </CardHeader>
              <CardContent className="space-y-3">
                <div className="flex items-center gap-2 text-sm">
                  <Clock className="w-4 h-4 text-muted-foreground" />
                  <span className="text-muted-foreground">Platform:</span>
                  <span className="capitalize">{event.service}</span>
                </div>
                {event.venue && (
                  <div className="flex items-center gap-2 text-sm">
                    <MapPin className="w-4 h-4 text-muted-foreground" />
                    <span className="text-muted-foreground">Type:</span>
                    <span>In-person</span>
                  </div>
                )}
                {!event.venue && (
                  <div className="flex items-center gap-2 text-sm">
                    <Clock className="w-4 h-4 text-muted-foreground" />
                    <span className="text-muted-foreground">Type:</span>
                    <span>Online</span>
                  </div>
                )}
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
