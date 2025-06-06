import { Head } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Link } from '@/components/ui/link';
import {
  Moon,
  Users,
  Mic,
  ExternalLink,
  Calendar,
  Video,
  MessageSquare,
  Stars,
  Sparkles,
  Play,
  ArrowRight,
  Heart,
  Trophy,
  Coffee,
  Zap,
  Slack,
} from 'lucide-react';
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
          topic:
            'Beyond Coding: How Windsurf AI is Making Software Development Accessible to Everyone',
          video:
            'https://www.youtube.com/watch?v=ZaRFJqOg28s&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=1',
        },
        {
          name: 'Paul Sullivan',
          topic: 'The Elixir Ecosystem',
          video:
            'https://www.youtube.com/watch?v=u-2QHjU3Y3c&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=2',
        },
        {
          name: 'Zach Hall',
          topic: 'Simulating Analog Television on the Web',
          video:
            'https://www.youtube.com/watch?v=wfiDn5Ff2i4&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=3',
        },
        {
          name: 'Andrew Lechowicz',
          topic: 'Detecting Flaky Tests: Increasing Trust in Your Test',
          video:
            'https://www.youtube.com/watch?v=OSVy1nGj5Y8&list=PL8vFrjH8DfOHEdVwM3ZlJGre_UBm3hWEa&index=4',
        },
      ],
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
          video:
            'https://www.youtube.com/watch?v=-1FoF2T2ZZU&list=PL8vFrjH8DfOHk8ACRhnu0WktPBVIwi-Dt&index=1',
        },
        {
          name: 'Barry Jones',
          topic: 'Story Points are Pointless, Measure Queues',
          video:
            'https://www.youtube.com/watch?v=AOis3O5kO70&list=PL8vFrjH8DfOHk8ACRhnu0WktPBVIwi-Dt&index=2',
        },
      ],
    },
  ];

  return (
    <AppLayout title="HackGreenville Nights">
      <Head>
        <meta
          name="description"
          content="A quarterly event with social gathering and short talks for Greenville SC tech, hacker, tinkerer, maker, and DIY community members."
        />
      </Head>

      {/* Enhanced Hero Section */}
      <div className="relative overflow-hidden bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 text-white z-0">
        {/* Animated Background Elements */}
        <div className="absolute inset-0 z-0">
          <div className="absolute top-20 left-10 animate-pulse">
            <Stars className="w-8 h-8 text-yellow-400/30" />
          </div>
          <div className="absolute top-40 right-20 animate-bounce" style={{ animationDelay: '1s' }}>
            <Sparkles className="w-6 h-6 text-blue-300/40" />
          </div>
          <div className="absolute bottom-32 left-1/4 animate-pulse" style={{ animationDelay: '2s' }}>
            <Stars className="w-5 h-5 text-purple-300/30" />
          </div>
          <div className="absolute top-1/3 right-1/3 animate-bounce" style={{ animationDelay: '0.5s' }}>
            <Sparkles className="w-4 h-4 text-pink-300/40" />
          </div>
        </div>
        <div className="absolute inset-0 bg-black/30 z-0"></div>
        
        <div className="relative container mx-auto px-4 py-24">
          <div className="max-w-5xl mx-auto text-center">
            {/* Icon and Title */}
            <div className="mb-8 relative">
              <div className="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 mb-6 shadow-2xl">
                <Moon className="w-12 h-12 text-white" />
              </div>
              <h1 className="text-5xl md:text-7xl font-bold mb-4 bg-gradient-to-r from-yellow-400 via-pink-500 to-purple-600 bg-clip-text text-transparent">
                HackGreenville Nights
              </h1>
              <div className="w-32 h-1 bg-gradient-to-r from-yellow-400 to-purple-600 mx-auto rounded-full"></div>
            </div>

            {/* Enhanced Description Card */}
            <div className="bg-white/10 backdrop-blur-md rounded-2xl p-8 mb-10 border border-white/20 shadow-2xl">
              <p className="text-xl md:text-2xl mb-6 leading-relaxed font-light">
                ✨ A Quarterly Gathering of Greenville's Tech, Hacker, Tinkerer,
                Maker, and DIY Community ✨
              </p>
              <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <Button
                  size="lg"
                  className="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all"
                  asChild
                >
                  <Link external href="https://www.meetup.com/hack-greenville/">
                    <Users size={20} className="mr-2" />
                    Join our Meetup Group
                    <ArrowRight size={16} className="ml-2" />
                  </Link>
                </Button>
                <Button
                  size="lg"
                  variant="outline"
                  className="bg-white/10 border-white/30 text-white hover:bg-white/20 shadow-lg"
                  asChild
                >
                  <Link external href="#submit-talk">
                    <Mic size={20} className="mr-2" />
                    Submit a Talk
                  </Link>
                </Button>
              </div>
            </div>

            {/* Stats Banner */}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12">
              <div className="text-center">
                <div className="text-3xl font-bold text-yellow-400">15+</div>
                <div className="text-sm text-white/80">Past Events</div>
              </div>
              <div className="text-center">
                <div className="text-3xl font-bold text-green-400">50+</div>
                <div className="text-sm text-white/80">Amazing Talks</div>
              </div>
              <div className="text-center">
                <div className="text-3xl font-bold text-blue-400">500+</div>
                <div className="text-sm text-white/80">Community Members</div>
              </div>
              <div className="text-center">
                <div className="text-3xl font-bold text-purple-400">4x</div>
                <div className="text-sm text-white/80">Per Year</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="container mx-auto px-4 py-16">
        {/* Enhanced Submit a Talk Section */}
        <div id="submit-talk" className="mb-16">
          <div className="text-center mb-10">
            <h2 className="text-4xl font-bold mb-4 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
              Share Your Passion
            </h2>
            <p className="text-xl text-muted-foreground max-w-2xl mx-auto">
              Got something cool to share? HG Nights is the perfect stage for your ideas!
            </p>
          </div>
          
          <div className="grid md:grid-cols-2 gap-8 mb-12">
            <Card className="relative overflow-hidden border-2 border-blue-200 hover:border-blue-400 transition-all duration-300 hover:shadow-lg">
              <div className="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-bl-3xl flex items-center justify-center">
                <Mic className="w-8 h-8 text-white" />
              </div>
              <CardHeader className="pb-4">
                <CardTitle className="text-2xl flex items-center">
                  <Zap className="mr-3 text-yellow-500" size={28} />
                  Lightning Talks
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                <p className="text-lg">
                  Quick 5, 10, or 15-minute talks on tech or tech-adjacent topics that don't fit existing meetup formats.
                </p>
                <div className="flex flex-wrap gap-2">
                  <span className="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">5-15 minutes</span>
                  <span className="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Any tech topic</span>
                  <span className="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">Casual format</span>
                </div>
              </CardContent>
            </Card>

            <Card className="relative overflow-hidden border-2 border-green-200 hover:border-green-400 transition-all duration-300 hover:shadow-lg">
              <div className="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-green-500 to-blue-600 rounded-bl-3xl flex items-center justify-center">
                <Users className="w-8 h-8 text-white" />
              </div>
              <CardHeader className="pb-4">
                <CardTitle className="text-2xl flex items-center">
                  <Heart className="mr-3 text-red-500" size={28} />
                  Community Pitches
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                <p className="text-lg">
                  Thinking about starting a new group? Pitch your idea and gauge interest from the community.
                </p>
                <div className="flex flex-wrap gap-2">
                  <span className="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">New groups</span>
                  <span className="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Get feedback</span>
                  <span className="px-3 py-1 bg-pink-100 text-pink-800 rounded-full text-sm">Find collaborators</span>
                </div>
              </CardContent>
            </Card>
          </div>

          <div className="text-center">
            <Button
              size="lg"
              className="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all text-lg px-8 py-4"
              asChild
            >
              <Link external href="https://forms.gle/oz4vDwrwG9c4h5Bo6">
                <Sparkles size={24} className="mr-3" />
                Submit Your Talk Idea
                <ArrowRight size={20} className="ml-3" />
              </Link>
            </Button>
          </div>
        </div>

        {/* Enhanced How to Get Involved Section */}
        <div className="mb-16">
          <div className="text-center mb-10">
            <h2 className="text-4xl font-bold mb-4 bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
              Join the Movement
            </h2>
            <p className="text-xl text-muted-foreground max-w-2xl mx-auto">
              Ready to be part of something bigger? Here's how you can get involved in our amazing community!
            </p>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <Card className="relative overflow-hidden border-2 hover:border-purple-400 transition-all duration-300 hover:shadow-lg group">
              <div className="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-bl-3xl flex items-center justify-center">
                <Mic className="w-6 h-6 text-white" />
              </div>
              <CardContent className="p-6 pt-8">
                <h3 className="font-semibold text-lg mb-3 group-hover:text-purple-600 transition-colors">
                  Share Your Ideas
                </h3>
                <p className="text-sm text-muted-foreground mb-4">
                  Spread the word and invite others to pitch amazing talks that inspire our community.
                </p>
                <Button size="sm" variant="outline" className="w-full" asChild>
                  <Link external href="https://forms.gle/oz4vDwrwG9c4h5Bo6">
                    <Sparkles size={16} className="mr-2" />
                    Pitch a Talk
                  </Link>
                </Button>
              </CardContent>
            </Card>

            <Card className="relative overflow-hidden border-2 hover:border-blue-400 transition-all duration-300 hover:shadow-lg group">
              <div className="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-bl-3xl flex items-center justify-center">
                <Users className="w-6 h-6 text-white" />
              </div>
              <CardContent className="p-6 pt-8">
                <h3 className="font-semibold text-lg mb-3 group-hover:text-blue-600 transition-colors">
                  Stay Connected
                </h3>
                <p className="text-sm text-muted-foreground mb-4">
                  Join our Meetup group to get the latest updates on events and community happenings.
                </p>
                <Button size="sm" variant="outline" className="w-full" asChild>
                  <Link external href="https://www.meetup.com/hack-greenville/">
                    <Calendar size={16} className="mr-2" />
                    Join Meetup
                  </Link>
                </Button>
              </CardContent>
            </Card>

            <Card className="relative overflow-hidden border-2 hover:border-green-400 transition-all duration-300 hover:shadow-lg group">
              <div className="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-bl-3xl flex items-center justify-center">
                <MessageSquare className="w-6 h-6 text-white" />
              </div>
              <CardContent className="p-6 pt-8">
                <h3 className="font-semibold text-lg mb-3 group-hover:text-green-600 transition-colors">
                  Volunteer & Lead
                </h3>
                <p className="text-sm text-muted-foreground mb-4">
                  Jump into Slack's #community-organizers channel and help shape our events.
                </p>
                <Button size="sm" variant="outline" className="w-full" asChild>
                  <Link href={route('join-slack')}>
                    <Slack size={16} className="mr-2" />
                    Join Slack
                  </Link>
                </Button>
              </CardContent>
            </Card>

            <Card className="relative overflow-hidden border-2 hover:border-orange-400 transition-all duration-300 hover:shadow-lg group">
              <div className="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-bl-3xl flex items-center justify-center">
                <Trophy className="w-6 h-6 text-white" />
              </div>
              <CardContent className="p-6 pt-8">
                <h3 className="font-semibold text-lg mb-3 group-hover:text-orange-600 transition-colors">
                  Sponsor Events
                </h3>
                <p className="text-sm text-muted-foreground mb-4">
                  Support HG Nights and help us create even more amazing experiences for the community.
                </p>
                <Button size="sm" variant="outline" className="w-full" asChild>
                  <Link href={route('contact')}>
                    <Heart size={16} className="mr-2" />
                    Become Sponsor
                  </Link>
                </Button>
              </CardContent>
            </Card>
          </div>
        </div>

        {/* Enhanced Past Events Section */}
        <div>
          <div className="text-center mb-12">
            <h2 className="text-4xl font-bold mb-4 bg-gradient-to-r from-pink-600 to-orange-600 bg-clip-text text-transparent">
              Epic Nights of the Past
            </h2>
            <p className="text-xl text-muted-foreground max-w-2xl mx-auto">
              Relive the magic! Check out highlights from our previous events, amazing talks, and the incredible speakers who shared their knowledge.
            </p>
          </div>

          <div className="space-y-12">
            {pastEvents.map((event, index) => (
              <Card key={index} className="relative overflow-hidden border-2 hover:border-gradient-to-r hover:shadow-2xl transition-all duration-300">
                {/* Decorative Elements */}
                <div className="absolute top-0 left-0 w-2 h-full bg-gradient-to-b from-purple-500 via-pink-500 to-orange-500"></div>
                <div className="absolute top-4 right-4">
                  <div className="flex items-center space-x-1">
                    <Stars className="w-5 h-5 text-yellow-400" />
                    <Stars className="w-4 h-4 text-yellow-300" />
                    <Stars className="w-3 h-3 text-yellow-200" />
                  </div>
                </div>

                <div className="pl-8">
                  <CardHeader className="pb-4">
                    <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                      <div>
                        <div className="flex items-center mb-2">
                          <span className="px-3 py-1 bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 rounded-full text-sm font-medium mr-3">
                            {event.year}
                          </span>
                          <span className="px-3 py-1 bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-800 rounded-full text-sm font-medium">
                            {event.month}
                          </span>
                        </div>
                        <CardTitle className="text-3xl mb-2">
                          <span className="bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                            "{event.title}"
                          </span>
                        </CardTitle>
                      </div>
                      <div className="flex flex-wrap gap-3 mt-4 lg:mt-0">
                        <Button variant="outline" size="sm" asChild>
                          <Link external href={event.recap}>
                            <Calendar size={16} className="mr-2" />
                            Event Recap
                          </Link>
                        </Button>
                        <Button variant="outline" size="sm" asChild>
                          <Link external href={event.videos}>
                            <Video size={16} className="mr-2" />
                            Watch Videos
                          </Link>
                        </Button>
                      </div>
                    </div>
                  </CardHeader>

                  <CardContent className="space-y-8">
                    {/* Speakers Section */}
                    <div>
                      <h4 className="text-xl font-semibold mb-4 flex items-center">
                        <Mic className="mr-3 text-purple-600" size={24} />
                        Featured Speakers
                      </h4>
                      <div className="grid gap-4 md:grid-cols-2">
                        {event.speakers.map((speaker, speakerIndex) => (
                          <Card key={speakerIndex} className="relative overflow-hidden border border-purple-200 hover:border-purple-400 hover:shadow-md transition-all duration-200">
                            <div className="absolute top-0 right-0 w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-500 rounded-bl-2xl flex items-center justify-center">
                              <Play className="w-4 h-4 text-white" />
                            </div>
                            <CardContent className="p-4 pr-16">
                              <h5 className="font-semibold text-lg mb-2">{speaker.name}</h5>
                              <p className="text-sm text-muted-foreground mb-3 line-clamp-2">
                                {speaker.topic}
                              </p>
                              <Button size="sm" variant="ghost" className="p-0 h-auto text-purple-600 hover:text-purple-800" asChild>
                                <Link external href={speaker.video}>
                                  <Play size={14} className="mr-1" />
                                  Watch Talk
                                </Link>
                              </Button>
                            </CardContent>
                          </Card>
                        ))}
                      </div>
                    </div>

                    {/* Community Kudos */}
                    <div className="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 border border-blue-200">
                      <h4 className="text-lg font-semibold mb-4 flex items-center">
                        <Heart className="mr-3 text-red-500" size={20} />
                        Community Kudos
                      </h4>
                      <div className="space-y-3 text-sm">
                        <div className="flex flex-wrap items-center gap-1">
                          <span className="text-muted-foreground">Event Sponsorship by</span>
                          <Button variant="link" size="sm" className="p-0 h-auto font-semibold" asChild>
                            <Link external href={event.sponsor.url}>
                              {event.sponsor.name}
                            </Link>
                          </Button>
                          <span className="text-muted-foreground">•</span>
                          <span className="text-muted-foreground">Hosted by</span>
                          <Button variant="link" size="sm" className="p-0 h-auto" asChild>
                            <Link external href="https://joinopenworks.com">
                              OpenWorks Coworking
                            </Link>
                          </Button>
                        </div>
                        <div className="flex flex-wrap items-center gap-1">
                          <span className="text-muted-foreground">Video production by</span>
                          <Button variant="link" size="sm" className="p-0 h-auto" asChild>
                            <Link external href="https://synergymill.com">
                              Synergy Mill Makerspace
                            </Link>
                          </Button>
                          <span className="text-muted-foreground">•</span>
                          <span className="text-muted-foreground">Fiscal support by</span>
                          <Button variant="link" size="sm" className="p-0 h-auto" asChild>
                            <Link external href="https://refactorgvl.com/">
                              RefactorGVL
                            </Link>
                          </Button>
                        </div>
                        <p className="text-muted-foreground italic">
                          <Coffee className="inline w-4 h-4 mr-1" />
                          Food and event logistics lovingly organized by HackGreenville volunteers
                        </p>
                      </div>
                    </div>
                  </CardContent>
                </div>
              </Card>
            ))}
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
