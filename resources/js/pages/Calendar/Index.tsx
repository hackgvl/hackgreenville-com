import { Head, Link } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import FullCalendarComponent from '../../components/FullCalendar';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Calendar, ExternalLink, Plus } from 'lucide-react';
import { EventClickArg } from '@fullcalendar/core';

interface CalendarEvent {
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

interface CalendarIndexProps {
  initialEvents: CalendarEvent[];
  eventsDataUrl: string;
}

export default function CalendarIndex({
  initialEvents,
  eventsDataUrl,
}: CalendarIndexProps) {
  const handleEventClick = (eventInfo: EventClickArg) => {
    // Custom event click handler - could show a modal or navigate
    if (eventInfo.event.extendedProps?.event_url) {
      window.open(eventInfo.event.extendedProps.event_url, '_blank');
      eventInfo.jsEvent.preventDefault();
    }
  };

  return (
    <AppLayout title="Calendar - HackGreenville">
      <Head>
        <meta
          name="description"
          content="View all upcoming tech events in the Greenville, SC area on our interactive calendar."
        />
      </Head>

      <div className="container mx-auto px-6 py-8">
        {/* Header */}
        <div className="mb-8">
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h1 className="text-3xl font-bold text-foreground mb-2">
                Event Calendar
              </h1>
              <p className="text-muted-foreground">
                View all upcoming tech events in the Greenville, SC area on our
                interactive calendar.
              </p>
            </div>
            <div className="flex gap-3">
              <Button asChild variant="outline" size="sm">
                <Link href="/calendar-feed">
                  <Plus className="w-4 h-4 mr-2" />
                  Subscribe to Feed
                </Link>
              </Button>
              <Button asChild size="sm">
                <Link href="/events">View All Events</Link>
              </Button>
            </div>
          </div>
        </div>

        {/* Calendar */}
        <div className="mb-8">
          <FullCalendarComponent
            initialEvents={initialEvents}
            eventsDataUrl={eventsDataUrl}
            onEventClick={handleEventClick}
          />
        </div>

        {/* Help Section */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Calendar className="w-5 h-5" />
                Calendar Features
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-3">
              <div>
                <h4 className="font-medium text-sm mb-1">View Options</h4>
                <p className="text-sm text-muted-foreground">
                  Switch between month, week, and day views using the buttons in
                  the calendar header.
                </p>
              </div>
              <div>
                <h4 className="font-medium text-sm mb-1">Event Details</h4>
                <p className="text-sm text-muted-foreground">
                  Click on any event to visit the event page with full details
                  and registration information.
                </p>
              </div>
              <div>
                <h4 className="font-medium text-sm mb-1">Navigation</h4>
                <p className="text-sm text-muted-foreground">
                  Use the navigation arrows to browse different months and
                  dates.
                </p>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <ExternalLink className="w-5 h-5" />
                Calendar Integration
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-3">
              <div>
                <h4 className="font-medium text-sm mb-1">
                  Subscribe to Calendar
                </h4>
                <p className="text-sm text-muted-foreground mb-3">
                  Add our calendar feed to your personal calendar app to stay
                  updated with all events.
                </p>
                <Button asChild variant="outline" size="sm">
                  <Link href="/calendar-feed">
                    <Plus className="w-4 h-4 mr-2" />
                    Get Calendar Feed
                  </Link>
                </Button>
              </div>
              <div>
                <h4 className="font-medium text-sm mb-1">Supported Apps</h4>
                <p className="text-sm text-muted-foreground">
                  Works with Google Calendar, Apple Calendar, Outlook, and other
                  calendar applications.
                </p>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </AppLayout>
  );
}
