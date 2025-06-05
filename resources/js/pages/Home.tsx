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
import EventsList from '../components/EventsList';

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

interface HomeProps {
  upcoming_events: Event[];
}

export default function Home({ upcoming_events }: HomeProps) {
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
                Where Greenville's tech community comes alive
              </h1>
              <p className="text-lg md:text-xl text-muted-foreground leading-relaxed max-w-2xl">
                Join hundreds of developers, makers, and innovators in the
                Upstate SC area. Discover events, connect with like-minded
                people, and grow your career in tech.
              </p>
            </div>

            <div className="flex flex-col sm:flex-row gap-4">
              <Button size="lg" className="font-medium" asChild>
                <a href="/join-slack">Join Our Community</a>
              </Button>
              <Button variant="ghost" size="lg" className="font-medium" asChild>
                <a href="/events">Explore Events →</a>
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
                  <h3 className="font-semibold">Tech Events & Meetups</h3>
                  <p className="text-sm text-muted-foreground">
                    Discover workshops, talks, and networking events happening
                    across the Upstate SC tech scene
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
                  <h3 className="font-semibold">Active Slack Community</h3>
                  <p className="text-sm text-muted-foreground">
                    Connect with local developers, share knowledge, and get help
                    from the community 24/7
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
                  <h3 className="font-semibold">Open Source & Collaboration</h3>
                  <p className="text-sm text-muted-foreground">
                    Built by the community, for the community. Contribute to
                    local tech projects and initiatives
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
          <div className="text-center mb-12">
            <h2 className="text-2xl font-bold text-foreground mb-4">
              Everything you need to thrive in Greenville's tech community
            </h2>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div className="space-y-3 text-center">
              <div className="w-12 h-12 bg-card rounded-xl border flex items-center justify-center mx-auto">
                <Calendar className="w-6 h-6 text-foreground" />
              </div>
              <h3 className="font-semibold text-foreground">Event Discovery</h3>
              <p className="text-sm text-muted-foreground">
                Never miss a meetup, workshop, or tech talk happening in your
                area.
              </p>
            </div>

            <div className="space-y-3 text-center">
              <div className="w-12 h-12 bg-card rounded-xl border flex items-center justify-center mx-auto">
                <Slack className="w-6 h-6 text-foreground" />
              </div>
              <h3 className="font-semibold text-foreground">Real-time Chat</h3>
              <p className="text-sm text-muted-foreground">
                Get instant help, share ideas, and build relationships in our
                Slack.
              </p>
            </div>

            <div className="space-y-3 text-center">
              <div className="w-12 h-12 bg-card rounded-xl border flex items-center justify-center mx-auto">
                <Github className="w-6 h-6 text-foreground" />
              </div>
              <h3 className="font-semibold text-foreground">
                Local Organizations
              </h3>
              <p className="text-sm text-muted-foreground">
                Find and connect with tech groups, startups, and companies
                nearby.
              </p>
            </div>

            <div className="space-y-3 text-center">
              <div className="w-12 h-12 bg-card rounded-xl border flex items-center justify-center mx-auto">
                <Calendar className="w-6 h-6 text-foreground" />
              </div>
              <h3 className="font-semibold text-foreground">Career Growth</h3>
              <p className="text-sm text-muted-foreground">
                Learn new skills, find mentors, and advance your tech career.
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Events Section */}
      {upcoming_events.length > 0 && (
        <div className="container mx-auto px-6 py-16">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-foreground mb-4">
              What's happening in Greenville tech
            </h2>
            <p className="text-muted-foreground max-w-2xl mx-auto">
              Join your fellow developers, designers, and innovators at these
              upcoming events
            </p>
          </div>

          <div className="max-w-4xl mx-auto">
            <EventsList events={upcoming_events} />
          </div>
        </div>
      )}
    </AppLayout>
  );
}
