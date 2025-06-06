import { Head, Link } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
  ArrowLeft,
  MapPin,
  Calendar,
  Users,
  ExternalLink,
  Building2,
  Clock,
} from 'lucide-react';

interface Event {
  id: number;
  event_name: string;
  description: string;
  active_at: string;
  expire_at?: string;
  cancelled_at: string | null;
  uri: string;
  venue?: {
    name: string;
    city?: string;
    state?: string;
  };
}

interface Organization {
  id: number;
  title: string;
  slug: string;
  description?: string;
  focus_area?: string;
  established_at?: string;
  uri?: string;
  events: Event[];
}

interface OrganizationShowProps {
  org: Organization;
}

export default function OrganizationShow({ org }: OrganizationShowProps) {
  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      weekday: 'long',
      month: 'long',
      day: 'numeric',
      year: 'numeric',
    });
  };

  const formatTime = (dateString: string) => {
    return new Date(dateString).toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
    });
  };

  const getVenueDisplay = (event: Event) => {
    if (!event.venue) return 'Online';
    return event.venue.name || 'Venue TBD';
  };

  return (
    <AppLayout title={`${org.title} - HackGreenville`}>
      <Head>
        <meta
          name="description"
          content={`${org.title} - ${
            org.description || `Tech organization in Greenville, SC`
          }`}
        />
      </Head>

      <div className="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
        {/* Header */}
        <div className="mb-6">
          <div className="flex items-center gap-4 mb-4">
            <Button variant="ghost" size="sm" asChild>
              <Link href="/orgs">
                <ArrowLeft className="w-4 h-4 mr-2" />
                Back to Organizations
              </Link>
            </Button>
          </div>

          <div className="space-y-4">
            <div>
              <h1 className="text-3xl sm:text-4xl font-bold text-foreground mb-2">
                {org.title}
              </h1>
              {org.focus_area && (
                <p className="text-lg text-muted-foreground mb-2">
                  {org.focus_area}
                </p>
              )}
              {org.established_at && (
                <p className="text-sm text-muted-foreground">
                  Established {new Date(org.established_at).getFullYear()}
                </p>
              )}
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Main Content */}
          <div className="lg:col-span-2 space-y-6">
            {/* About */}
            {org.description && (
              <Card>
                <CardHeader>
                  <CardTitle className="flex items-center gap-2">
                    <Building2 className="w-5 h-5" />
                    About {org.title}
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  <div
                    className="prose prose-sm max-w-none text-foreground"
                    dangerouslySetInnerHTML={{
                      __html: org.description.replace(/\n/g, '<br>'),
                    }}
                  />
                </CardContent>
              </Card>
            )}

            {/* Upcoming Events */}
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center gap-2">
                  <Calendar className="w-5 h-5" />
                  Upcoming Events
                  {org.events.length > 0 && (
                    <span className="ml-2 text-sm bg-primary/10 text-primary px-2 py-1 rounded-full">
                      {org.events.length}
                    </span>
                  )}
                </CardTitle>
              </CardHeader>
              <CardContent>
                {org.events.length === 0 ? (
                  <div className="text-center py-8">
                    <Calendar className="w-12 h-12 mx-auto text-muted-foreground mb-4" />
                    <p className="text-muted-foreground">
                      No upcoming events scheduled
                    </p>
                  </div>
                ) : (
                  <div className="space-y-4">
                    {org.events.map((event) => (
                      <Card key={event.id} className="border">
                        <CardContent className="p-4">
                          <div className="space-y-3">
                            <div>
                              <h3 className="font-semibold text-foreground leading-tight">
                                {event.event_name}
                              </h3>
                              <p className="text-sm text-muted-foreground mt-1">
                                {formatDate(event.active_at)} at{' '}
                                {formatTime(event.active_at)}
                              </p>
                            </div>

                            <div className="flex items-center gap-4 text-sm text-muted-foreground">
                              <div className="flex items-center gap-1">
                                <MapPin className="w-3 h-3" />
                                <span>{getVenueDisplay(event)}</span>
                              </div>
                            </div>

                            <div className="flex items-center justify-between">
                              {event.cancelled_at ? (
                                <span className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-destructive/10 text-destructive">
                                  CANCELLED
                                </span>
                              ) : (
                                <div />
                              )}
                              <Button variant="ghost" size="sm" asChild>
                                <Link href={`/events/${event.id}`}>
                                  View Details{' '}
                                  <ExternalLink className="w-3 h-3 ml-1" />
                                </Link>
                              </Button>
                            </div>
                          </div>
                        </CardContent>
                      </Card>
                    ))}
                  </div>
                )}
              </CardContent>
            </Card>
          </div>

          {/* Sidebar */}
          <div className="space-y-4">
            {/* Organization Info */}
            <Card>
              <CardHeader>
                <CardTitle className="text-base">Organization Info</CardTitle>
              </CardHeader>
              <CardContent className="space-y-3">
                {org.established_at && (
                  <div className="flex items-center gap-2 text-sm">
                    <Clock className="w-4 h-4 text-muted-foreground" />
                    <span className="text-muted-foreground">Established:</span>
                    <span>{new Date(org.established_at).getFullYear()}</span>
                  </div>
                )}
                {org.focus_area && (
                  <div className="flex items-center gap-2 text-sm">
                    <Building2 className="w-4 h-4 text-muted-foreground" />
                    <span className="text-muted-foreground">Focus:</span>
                    <span>{org.focus_area}</span>
                  </div>
                )}
                <div className="flex items-center gap-2 text-sm">
                  <Users className="w-4 h-4 text-muted-foreground" />
                  <span className="text-muted-foreground">Events:</span>
                  <span>{org.events.length} upcoming</span>
                </div>
              </CardContent>
            </Card>

            {/* External Links */}
            {org.uri && (
              <Card>
                <CardContent className="p-6">
                  <Button className="w-full" asChild>
                    <a href={org.uri} target="_blank" rel="noopener noreferrer">
                      <ExternalLink className="w-4 h-4 mr-2" />
                      Visit Website
                    </a>
                  </Button>
                </CardContent>
              </Card>
            )}
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
