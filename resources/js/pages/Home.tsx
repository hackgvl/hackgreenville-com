import { Head } from '@inertiajs/react';
import AppLayout from '../layouts/AppLayout';
import { Button } from '../components/ui/button';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '../components/ui/card';
import { Calendar, Github, Slack } from 'lucide-react';

interface Event {
  id: number;
  event_name: string;
  description: string;
  active_at: string;
  cancelled_at: string | null;
  organization: {
    title: string;
  };
}

interface HomeProps {
  upcoming_events: Event[];
}

export default function Home({ upcoming_events }: HomeProps) {
  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric',
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
    });
  };

  return (
    <AppLayout title="Hackgreenville - A Developer Community in the Greenville SC Area">
      <Head>
        <meta
          name="description"
          content="HackGreenville exists to foster personal growth among the hackers of Greenville, SC and the surrounding area."
        />
      </Head>

      {/* Hero Section - Mainline Style */}
      <div className="container mx-auto px-6 py-16 md:py-24">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          {/* Hero Content */}
          <div className="space-y-8">
            <div className="space-y-6">
              <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold text-foreground leading-tight">
                HackGreenville your product team
              </h1>
              <p className="text-lg md:text-xl text-muted-foreground leading-relaxed max-w-2xl">
                HackGreenville is the fit-for-purpose tool for
                planning and building modern software products in the Greenville SC area.
              </p>
            </div>
            
            <div className="flex flex-col sm:flex-row gap-4">
              <Button size="lg" className="font-medium">
                Get started
              </Button>
              <Button variant="ghost" size="lg" className="font-medium">
                HackGreenville raises $12M from Roba Ventures →
              </Button>
            </div>
          </div>

          {/* Hero Visual/Features */}
          <div className="space-y-6">
            <div className="bg-card rounded-xl border p-6 space-y-4">
              <div className="flex items-center space-x-3">
                <div className="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                  <Calendar className="w-4 h-4 text-primary" />
                </div>
                <div>
                  <h3 className="font-semibold">Modern product teams</h3>
                  <p className="text-sm text-muted-foreground">
                    HackGreenville is built on the habits that make the best product teams successful
                  </p>
                </div>
              </div>
            </div>

            <div className="bg-card rounded-xl border p-6 space-y-4">
              <div className="flex items-center space-x-3">
                <div className="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                  <Github className="w-4 h-4 text-primary" />
                </div>
                <div>
                  <h3 className="font-semibold">Resource Allocation</h3>
                  <p className="text-sm text-muted-foreground">
                    Mainline your resource allocation and execution
                  </p>
                </div>
              </div>
            </div>

            <div className="bg-card rounded-xl border p-6 space-y-4">
              <div className="flex items-center space-x-3">
                <div className="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                  <Slack className="w-4 h-4 text-primary" />
                </div>
                <div>
                  <h3 className="font-semibold">Cross-functional Alignments</h3>
                  <p className="text-sm text-muted-foreground">
                    Collaborate across teams and departments.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Features Grid */}
      <div className="border-t border-border bg-muted/30">
        <div className="container mx-auto px-6 py-16">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div className="space-y-3">
              <div className="w-12 h-12 bg-card rounded-xl border flex items-center justify-center">
                <Calendar className="w-6 h-6 text-foreground" />
              </div>
              <h3 className="font-semibold text-foreground">Milestones</h3>
              <p className="text-sm text-muted-foreground">
                Break projects down into concrete phases.
              </p>
            </div>

            <div className="space-y-3">
              <div className="w-12 h-12 bg-card rounded-xl border flex items-center justify-center">
                <Github className="w-6 h-6 text-foreground" />
              </div>
              <h3 className="font-semibold text-foreground">Progress insights</h3>
              <p className="text-sm text-muted-foreground">
                Track scope, velocity, and progress over time.
              </p>
            </div>

            <div className="space-y-3">
              <div className="w-12 h-12 bg-card rounded-xl border flex items-center justify-center">
                <Slack className="w-6 h-6 text-foreground" />
              </div>
              <h3 className="font-semibold text-foreground">Community</h3>
              <p className="text-sm text-muted-foreground">
                Connect with local developers and tech enthusiasts.
              </p>
            </div>

            <div className="space-y-3">
              <div className="w-12 h-12 bg-card rounded-xl border flex items-center justify-center">
                <Calendar className="w-6 h-6 text-foreground" />
              </div>
              <h3 className="font-semibold text-foreground">Events</h3>
              <p className="text-sm text-muted-foreground">
                Discover meetups, workshops, and networking opportunities.
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Upcoming Events Section */}
      {upcoming_events.length > 0 && (
        <div className="container mx-auto px-6 py-16">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-foreground mb-4">
              Upcoming Events
            </h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              Join our community events and connect with fellow developers, makers, and tech enthusiasts in the Greenville area.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {upcoming_events.slice(0, 6).map((event) => (
              <Card key={event.id} className="hover:shadow-md transition-shadow">
                <CardContent className="p-6">
                  <div className="space-y-3">
                    {event.cancelled_at && (
                      <div className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-destructive/10 text-destructive">
                        CANCELLED
                      </div>
                    )}
                    <h3 className="font-semibold text-foreground line-clamp-2">
                      {event.event_name}
                    </h3>
                    <p className="text-sm text-muted-foreground">
                      {event.organization.title}
                    </p>
                    <p className="text-sm text-muted-foreground">
                      {formatDate(event.active_at)}
                    </p>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      )}
    </AppLayout>
  );
}
