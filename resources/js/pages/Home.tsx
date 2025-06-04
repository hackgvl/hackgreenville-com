import { Head } from '@inertiajs/react';
import AppLayout from '../layouts/AppLayout';
import { Button } from '../components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '../components/ui/card';
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
        <meta name="description" content="HackGreenville exists to foster personal growth among the hackers of Greenville, SC and the surrounding area." />
      </Head>

      {/* Hero Section */}
      <div className="relative bg-gradient-to-br from-blue-900 to-purple-900 text-white" style={{ backgroundColor: '#1e3a8a', minHeight: '400px' }}>
        <div className="absolute inset-0 bg-black/20"></div>
        <div className="relative container mx-auto px-4 py-20 text-center">
          <h1 className="text-5xl md:text-6xl font-bold mb-6">
            Build Stuff. Meet People. Do cool things.
          </h1>
          <p className="text-xl mb-8">Meetups · Talks · Projects</p>
          <Button size="lg" className="bg-green-600 hover:bg-green-700 text-white">
            <Slack className="mr-2 h-5 w-5" />
            Request to Join Slack
          </Button>
        </div>
      </div>

      <div className="container mx-auto px-4 py-12">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Main Content */}
          <div className="lg:col-span-2">
            <div className="text-center mb-12">
              <h2 className="text-4xl font-bold text-blue-600 mb-6">
                What is HackGreenville?
              </h2>
              <p className="text-lg text-gray-700 max-w-3xl mx-auto">
                HackGreenville is a community of "hackers" located in and around Greenville, SC. 
                Our community exists to foster personal growth for community members through 
                sharing and promoting local tech opportunities.
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 items-center mb-12">
              <div className="text-center">
                <img 
                  src="/img/meetup.jpeg" 
                  alt="Join Us" 
                  className="w-full rounded-lg shadow-lg"
                />
              </div>
              <div>
                <p className="text-lg mb-4">
                  HG is the <code className="bg-gray-100 px-2 py-1 rounded">"GO TO"</code> resource 
                  for discovering and connecting with Upstate SC tech hackers, makers, and tinkerers.
                </p>
                <p className="text-lg mb-6">
                  Explore the site for more meetups and events, and make sure to join our active{' '}
                  <a href="/join-slack" className="text-blue-600 hover:text-blue-800 underline">
                    Slack community
                  </a>{' '}
                  to connect further!
                </p>
                <Button size="lg" variant="outline">
                  Join Us
                </Button>
              </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div className="text-center">
                <h3 className="text-3xl font-bold mb-4">Contribute</h3>
                <p className="text-lg">
                  hackgreenville.com is built on the{' '}
                  <a href="https://laravel.com/" className="text-blue-600 hover:text-blue-800 underline">
                    Laravel
                  </a>{' '}
                  PHP framework
                </p>
              </div>
              <div className="text-center">
                <a 
                  href="https://github.com/hackgvl/hackgreenville-com"
                  className="block hover:text-gray-600 transition-colors"
                >
                  <Github size={120} className="mx-auto mb-4" />
                  <p className="text-lg font-semibold">Join the Project</p>
                </a>
              </div>
            </div>
          </div>

          {/* Sidebar - Upcoming Events */}
          <div className="lg:col-span-1">
            <Card>
              <CardHeader>
                <CardTitle className="text-2xl text-center">
                  Upcoming Events
                </CardTitle>
              </CardHeader>
              <CardContent>
                {upcoming_events.length === 0 ? (
                  <p className="text-center text-gray-500">
                    <strong>No</strong> events to display.
                  </p>
                ) : (
                  <div className="space-y-4">
                    {upcoming_events.map((event) => (
                      <div key={event.id} className="border-l-4 border-green-500 pl-4">
                        <div className="bg-green-100 rounded-full w-8 h-8 flex items-center justify-center -ml-6 mb-2">
                          <Calendar size={16} className="text-green-600" />
                        </div>
                        <div>
                          {event.cancelled_at && (
                            <div className="text-red-600 font-semibold text-sm mb-1">
                              [CANCELLED]
                            </div>
                          )}
                          <h4 className="font-semibold text-lg mb-1">
                            {event.event_name}
                          </h4>
                          <p className="text-sm text-gray-600 mb-1">
                            {event.organization.title}
                          </p>
                          <p className="text-sm text-gray-500 mb-2">
                            <Calendar size={14} className="inline mr-1" />
                            {formatDate(event.active_at)}
                          </p>
                          <Button size="sm" variant="secondary">
                            Details
                          </Button>
                        </div>
                      </div>
                    ))}
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