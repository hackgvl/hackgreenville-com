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
        <style>{`
          @media (max-width: 768px) {
            .fc-toolbar {
              padding: 0.5rem !important;
              flex-wrap: wrap !important;
              gap: 0.5rem !important;
            }
            .fc-toolbar-title {
              font-size: 1.1rem !important;
              margin: 0 !important;
            }
            .fc-button {
              padding: 0.25rem 0.5rem !important;
              font-size: 0.75rem !important;
            }
            .fc-daygrid-day-number {
              font-size: 0.75rem !important;
              padding: 0.25rem !important;
            }
            .fc-event {
              font-size: 0.65rem !important;
              padding: 0.125rem 0.25rem !important;
              margin-bottom: 1px !important;
            }
            .fc-daygrid-day-frame {
              min-height: 60px !important;
            }
            .fc-col-header-cell {
              padding: 0.25rem 0.125rem !important;
            }
            .fc-daygrid-day {
              padding: 0.125rem !important;
            }
            .fc-event-title {
              font-weight: 500 !important;
            }
          }
        `}</style>
      </Head>

      <div className="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
        {/* Header */}
        <div className="mb-6 sm:mb-8">
          <div className="flex flex-col gap-4">
            <div className="text-center sm:text-left">
              <h1 className="text-2xl sm:text-3xl font-bold text-foreground mb-2">
                Event Calendar
              </h1>
              <p className="text-muted-foreground text-sm sm:text-base">
                View all upcoming tech events in the Greenville, SC area
              </p>
            </div>
            <div className="flex flex-col sm:flex-row gap-3 sm:justify-center md:justify-start">
              <Button asChild variant="outline" size="sm" className="flex-1 sm:flex-none">
                <Link href="/calendar-feed">
                  <Plus className="w-4 h-4 mr-2" />
                  Subscribe to Feed
                </Link>
              </Button>
              <Button asChild size="sm" className="flex-1 sm:flex-none">
                <Link href="/events">View List</Link>
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

        {/* Help Section - Hidden on mobile for better focus on calendar */}
        <div className="hidden md:grid grid-cols-1 md:grid-cols-2 gap-6">
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

        {/* Mobile-specific help - Simple call-to-action */}
        <div className="md:hidden">
          <Card>
            <CardContent className="p-4 text-center">
              <p className="text-sm text-muted-foreground mb-3">
                Tap events to view details. Want to add these events to your calendar?
              </p>
              <Button asChild variant="outline" size="sm">
                <Link href="/calendar-feed">
                  <Plus className="w-4 h-4 mr-2" />
                  Get Calendar Feed
                </Link>
              </Button>
            </CardContent>
          </Card>
        </div>
      </div>
    </AppLayout>
  );
}
