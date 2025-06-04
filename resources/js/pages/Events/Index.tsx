import { Head } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import { Card, CardContent, CardHeader, CardTitle } from '../../components/ui/card';
import { Button } from '../../components/ui/button';
import { Calendar, MapPin, ExternalLink } from 'lucide-react';

interface Event {
  id: number;
  event_name: string;
  description: string;
  active_at: string;
  cancelled_at: string | null;
  organization: {
    title: string;
  };
  venue?: {
    name: string;
    address: string;
  };
}

interface EventsIndexProps {
  events: Event[];
}

export default function EventsIndex({ events }: EventsIndexProps) {
  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
    });
  };

  return (
    <AppLayout title="Events - HackGreenville">
      <Head>
        <meta name="description" content="Discover upcoming tech events, meetups, and workshops in the Greenville, SC area." />
      </Head>

      <div className="container mx-auto px-4 py-8">
        <div className="mb-8">
          <h1 className="text-4xl font-bold text-gray-900 mb-4">Upcoming Events</h1>
          <p className="text-lg text-gray-600">
            Discover upcoming tech events, meetups, and workshops in the Greenville, SC area.
          </p>
        </div>

        {events.length === 0 ? (
          <Card>
            <CardContent className="py-12 text-center">
              <Calendar size={48} className="mx-auto text-gray-400 mb-4" />
              <h3 className="text-xl font-semibold text-gray-900 mb-2">No upcoming events</h3>
              <p className="text-gray-600">Check back soon for new events!</p>
            </CardContent>
          </Card>
        ) : (
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            {events.map((event) => (
              <Card key={event.id} className="hover:shadow-lg transition-shadow">
                <CardHeader>
                  {event.cancelled_at && (
                    <div className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 mb-2 w-fit">
                      CANCELLED
                    </div>
                  )}
                  <CardTitle className="text-xl line-clamp-2">{event.event_name}</CardTitle>
                  <p className="text-sm text-muted-foreground font-medium">
                    {event.organization.title}
                  </p>
                </CardHeader>
                <CardContent>
                  <div className="space-y-3">
                    <div className="flex items-start space-x-2 text-sm">
                      <Calendar size={16} className="mt-0.5 text-gray-500" />
                      <span>{formatDate(event.active_at)}</span>
                    </div>
                    
                    {event.venue && (
                      <div className="flex items-start space-x-2 text-sm">
                        <MapPin size={16} className="mt-0.5 text-gray-500" />
                        <div>
                          <div className="font-medium">{event.venue.name}</div>
                          <div className="text-gray-600">{event.venue.address}</div>
                        </div>
                      </div>
                    )}

                    {event.description && (
                      <p className="text-sm text-gray-700 line-clamp-3">
                        {event.description.replace(/(<([^>]+)>)/gi, "").substring(0, 120)}...
                      </p>
                    )}

                    <div className="pt-3">
                      <Button size="sm" className="w-full">
                        <ExternalLink size={16} className="mr-2" />
                        View Details
                      </Button>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        )}
      </div>
    </AppLayout>
  );
}