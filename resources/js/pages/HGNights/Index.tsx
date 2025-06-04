import { Head } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import { Card, CardContent, CardHeader, CardTitle } from '../../components/ui/card';
import { Button } from '../../components/ui/button';
import { Moon, Users, Mic, ExternalLink, Calendar, Video, MessageSquare } from 'lucide-react';
import { route } from '../../helpers/route';

export default function HGNightsIndex() {
  const pastEvents = [
    {
      year: '2025',
      month: 'Feb',
      title: 'Souperman IV: Quest for Peas',
      recap: 'https://www.meetup.com/hack-greenville/events/305856459/',
      videos: 'https://www.youtube.com/@HackGreenville/playlists',
      sponsor: { name: 'myMechanic', url: 'https://home.mymechanic.app' },
      speakers: [
        {
          name: 'David He',
          topic: 'Beyond Coding: How Windsurf AI is Making Software Development Accessible to Everyone',
          video: 'https://www.youtube.com/watch?v=ZaRFJqOg28s&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=1'
        },
        {
          name: 'Paul Sullivan',
          topic: 'The Elixir Ecosystem',
          video: 'https://www.youtube.com/watch?v=u-2QHjU3Y3c&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=2'
        },
        {
          name: 'Zach Hall',
          topic: 'Simulating Analog Television on the Web',
          video: 'https://www.youtube.com/watch?v=wfiDn5Ff2i4&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=3'
        },
        {
          name: 'Andrew Lechowicz',
          topic: 'Detecting Flaky Tests: Increasing Trust in Your Test',
          video: 'https://www.youtube.com/watch?v=OSVy1nGj5Y8&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=4'
        }
      ]
    },
    {
      year: '2024',
      month: 'Oct',
      title: 'Starch Trek',
      recap: 'https://www.meetup.com/hack-greenville/events/303551633/',
      videos: 'https://www.youtube.com/@HackGreenville/playlists',
      sponsor: { name: 'Brightball', url: 'https://www.brightball.com' },
      speakers: [
        {
          name: 'Caleb McQuaid',
          topic: 'Encore! Encore!',
          video: 'https://www.youtube.com/watch?v=-1FoF2T2ZZU&list=PL8vFrjH8DfOHk8ACRhnu0WktPBVIwi-Dt&index=1'
        },
        {
          name: 'Barry Jones',
          topic: 'Story Points are Pointless, Measure Queues',
          video: 'https://www.youtube.com/watch?v=AOis3O5kO70&list=PL8vFrjH8DfOHk8ACRhnu0WktPBVIwi-Dt&index=2'
        }
      ]
    }
  ];

  return (
    <AppLayout title="HackGreenville Nights">
      <Head>
        <meta name="description" content="A quarterly event with social gathering and short talks for Greenville SC tech, hacker, tinkerer, maker, and DIY community members." />
      </Head>

      {/* Hero Section */}
      <div className="relative bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 text-white">
        <div className="absolute inset-0 bg-black/30"></div>
        <div className="relative container mx-auto px-4 py-20">
          <div className="max-w-4xl mx-auto text-center">
            <h1 className="text-6xl md:text-7xl font-bold mb-6">
              <Moon className="inline-block mr-4 mb-2" size={64} />
              HackGreenville Nights
            </h1>
            <div className="bg-white/10 backdrop-blur-sm rounded-lg p-6 mb-8">
              <p className="text-xl md:text-2xl mb-6 leading-relaxed">
                A Quarterly Gathering of Greenville's Tech, Hacker, Tinkerer, Maker, and DIY Community
              </p>
              <a 
                href="https://www.meetup.com/hack-greenville/"
                target="_blank"
                rel="noopener noreferrer"
              >
                <Button size="lg" className="bg-green-600 hover:bg-green-700 text-white">
                  <Users size={20} className="mr-2" />
                  Join our Meetup Group
                </Button>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div className="container mx-auto px-4 py-12">
        {/* Submit a Talk Section */}
        <Card className="mb-12">
          <CardHeader>
            <CardTitle className="flex items-center text-2xl">
              <Mic size={28} className="mr-3 text-blue-600" />
              Submit a Talk
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              <p className="text-lg">
                Talks are typically 5, 10, or 15 minutes on tech or tech-adjacent topics that 
                don't fit the format of our existing local meetups or conferences.
              </p>
              <p className="text-gray-700">
                Thinking about starting a new group? Pitch the topic here and get a feel for the level of interest.
              </p>
              <a 
                href="https://forms.gle/oz4vDwrwG9c4h5Bo6"
                target="_blank"
                rel="noopener noreferrer"
              >
                <Button size="lg" className="bg-green-600 hover:bg-green-700">
                  <ExternalLink size={20} className="mr-2" />
                  Submit a Talk
                </Button>
              </a>
            </div>
          </CardContent>
        </Card>

        {/* How to Get Involved */}
        <Card className="mb-12">
          <CardHeader>
            <CardTitle className="text-2xl">How to Get Involved</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="grid gap-4 md:grid-cols-2">
              <div className="flex items-start space-x-3">
                <MessageSquare size={20} className="mt-1 text-blue-600" />
                <div>
                  <p>Spread the word and invite others to 
                    <a href="https://forms.gle/oz4vDwrwG9c4h5Bo6" 
                       className="text-blue-600 hover:text-blue-800 underline ml-1"
                       target="_blank" rel="noopener noreferrer">
                      pitch a talk
                    </a>
                  </p>
                </div>
              </div>
              
              <div className="flex items-start space-x-3">
                <Users size={20} className="mt-1 text-blue-600" />
                <div>
                  <p>Join our 
                    <a href="https://www.meetup.com/hack-greenville/" 
                       className="text-blue-600 hover:text-blue-800 underline ml-1"
                       target="_blank" rel="noopener noreferrer">
                      Meetup group
                    </a> to receive updates
                  </p>
                </div>
              </div>
              
              <div className="flex items-start space-x-3">
                <MessageSquare size={20} className="mt-1 text-blue-600" />
                <div>
                  <p>Hop into the 
                    <a href={route('join-slack')} className="text-blue-600 hover:text-blue-800 underline ml-1">
                      HackGreenville Slack
                    </a> <em>#community-organizers</em> channel to volunteer
                  </p>
                </div>
              </div>
              
              <div className="flex items-start space-x-3">
                <ExternalLink size={20} className="mt-1 text-blue-600" />
                <div>
                  <p>
                    <a href={route('contact')} className="text-blue-600 hover:text-blue-800 underline">
                      Become a <em>HG Nights</em> sponsor
                    </a>
                  </p>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Past Events */}
        <div>
          <h2 className="text-3xl font-bold mb-8">Past HackGreenville Nights Events</h2>
          
          <div className="space-y-8">
            {pastEvents.map((event, index) => (
              <Card key={index}>
                <CardHeader>
                  <CardTitle className="text-2xl">
                    {event.year} - {event.month} | <em>"{event.title}"</em>
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="space-y-6">
                    {/* Event Links */}
                    <div className="flex flex-wrap gap-4">
                      <a href={event.recap} target="_blank" rel="noopener noreferrer">
                        <Button variant="outline">
                          <Calendar size={16} className="mr-2" />
                          Recap
                        </Button>
                      </a>
                      <a href={event.videos} target="_blank" rel="noopener noreferrer">
                        <Button variant="outline">
                          <Video size={16} className="mr-2" />
                          Videos
                        </Button>
                      </a>
                    </div>

                    {/* Kudos */}
                    <div>
                      <h4 className="font-bold text-lg mb-2">Kudos</h4>
                      <p className="text-gray-700">
                        Event Sponsorship by <a href={event.sponsor.url} 
                                               className="text-blue-600 hover:text-blue-800 underline font-semibold"
                                               target="_blank" rel="noopener noreferrer">
                          {event.sponsor.name}
                        </a> | 
                        Hosted by <a href="https://joinopenworks.com" 
                                   className="text-blue-600 hover:text-blue-800 underline"
                                   target="_blank" rel="noopener noreferrer">
                          OpenWorks Coworking
                        </a> |
                        Video by <a href="https://synergymill.com" 
                                  className="text-blue-600 hover:text-blue-800 underline"
                                  target="_blank" rel="noopener noreferrer">
                          Synergy Mill Makerspace
                        </a>
                      </p>
                      <p className="text-gray-600 mt-2">
                        Food and event logistics by HackGreenville volunteers | 
                        Fiscal support by <a href="https://refactorgvl.com/" 
                                           className="text-blue-600 hover:text-blue-800 underline"
                                           target="_blank" rel="noopener noreferrer">
                          RefactorGVL
                        </a>
                      </p>
                    </div>

                    {/* Speakers */}
                    <div>
                      <h4 className="font-bold text-lg mb-3">Speakers</h4>
                      <div className="space-y-2">
                        {event.speakers.map((speaker, speakerIndex) => (
                          <div key={speakerIndex} className="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <Mic size={16} className="mt-1 text-gray-500" />
                            <div className="flex-1">
                              <span className="font-medium">{speaker.name}</span> on{' '}
                              <a href={speaker.video} 
                                 className="text-blue-600 hover:text-blue-800 underline"
                                 target="_blank" rel="noopener noreferrer">
                                <em>{speaker.topic}</em>
                              </a>
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </div>
    </AppLayout>
  );
}