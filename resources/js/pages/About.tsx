import { Head } from '@inertiajs/react';
import AppLayout from '../layouts/AppLayout';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '../components/ui/card';
import { Users, Target, Heart } from 'lucide-react';

export default function About() {
  return (
    <AppLayout title="About Us - HackGreenville">
      <Head>
        <meta
          name="description"
          content="Learn about HackGreenville's mission to foster personal growth among the tech community in Greenville, SC."
        />
      </Head>

      <div className="container mx-auto px-4 py-8">
        <div className="max-w-4xl mx-auto">
          <div className="text-center mb-12">
            <h1 className="text-4xl font-bold text-foreground mb-4">
              About HackGreenville
            </h1>
            <p className="text-xl text-gray-600">
              Fostering personal growth among the hackers of Greenville, SC and
              the surrounding area.
            </p>
          </div>

          <div className="grid gap-8 md:grid-cols-3 mb-12">
            <Card>
              <CardHeader className="text-center">
                <Users size={48} className="mx-auto text-blue-600 mb-4" />
                <CardTitle>Community</CardTitle>
              </CardHeader>
              <CardContent className="text-center">
                <p className="text-gray-600">
                  We bring together developers, designers, entrepreneurs, and
                  tech enthusiasts from all backgrounds and skill levels.
                </p>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="text-center">
                <Target size={48} className="mx-auto text-green-600 mb-4" />
                <CardTitle>Growth</CardTitle>
              </CardHeader>
              <CardContent className="text-center">
                <p className="text-gray-600">
                  Our mission is to foster personal and professional growth
                  through knowledge sharing, collaboration, and networking.
                </p>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="text-center">
                <Heart size={48} className="mx-auto text-red-600 mb-4" />
                <CardTitle>Passion</CardTitle>
              </CardHeader>
              <CardContent className="text-center">
                <p className="text-gray-600">
                  We're passionate about technology, innovation, and helping
                  each other succeed in the ever-evolving tech landscape.
                </p>
              </CardContent>
            </Card>
          </div>

          <div className="prose max-w-none">
            <h2 className="text-3xl font-bold mb-6">Our Story</h2>
            <div className="grid gap-6 md:grid-cols-2">
              <div>
                <p className="text-lg mb-4">
                  HackGreenville began as a simple idea: to create a space where
                  technology enthusiasts in the Greenville, SC area could come
                  together, share knowledge, and grow professionally.
                </p>
                <p className="text-lg mb-4">
                  What started as informal gatherings has evolved into a
                  thriving community that hosts regular meetups, workshops, and
                  networking events. We've become the go-to resource for
                  discovering tech opportunities in the Upstate.
                </p>
              </div>
              <div>
                <p className="text-lg mb-4">
                  Today, HackGreenville serves hundreds of members across dozens
                  of organizations, from startups to established companies, from
                  students to seasoned professionals.
                </p>
                <p className="text-lg mb-4">
                  Our active Slack community provides a platform for continuous
                  learning, job opportunities, project collaboration, and
                  meaningful connections that extend far beyond our monthly
                  meetups.
                </p>
              </div>
            </div>
          </div>

          <div className="mt-12 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg p-8 text-center">
            <h3 className="text-2xl font-bold mb-4">Join Our Community</h3>
            <p className="text-lg mb-6">
              Ready to connect with fellow tech enthusiasts and grow your
              career?
            </p>
            <div className="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
              <a
                href="/join-slack"
                className="inline-block bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
              >
                Join Our Slack
              </a>
              <a
                href="/events"
                className="inline-block border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors"
              >
                View Events
              </a>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
