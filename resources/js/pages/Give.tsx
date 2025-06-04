import { Head } from '@inertiajs/react';
import AppLayout from '../layouts/AppLayout';
import { Card, CardContent, CardHeader, CardTitle } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Heart, HandHeart, ExternalLink } from 'lucide-react';
import { route } from '../helpers/route';

export default function Give() {
  const donationOrgs = [
    {
      name: 'Agile Learning Institute',
      description: 'A 501(c)(3) educational nonprofit whose mission is to provide free mentorship and one-on-one coaching services to support software engineers',
      link: 'https://agile-learning.institute/become-a-mentor',
      target: 'ali-mentorship'
    },
    {
      name: 'Build Carolina',
      description: 'Support tech workforce development in SC through a local 501(c)(3) nonprofit',
      link: 'https://buildcarolina.org/build-carolina-giving',
      target: 'build_carolina'
    },
    {
      name: 'RefactorGVL',
      description: 'To support a HackGreenville initiative, enter "HackGreenville" in the "Requested earmark" field of RefactorGVL\'s 501(c)(3) nonprofit donation form.',
      link: 'https://refactorgvl.com/#donate',
      target: 'refactor_gvl'
    },
    {
      name: 'Synergy Mill',
      description: 'Help keep Synergy Mill, a 501(c)(3) nonprofit, available for the makers, crafters, inventors, and small business owners in Greenville',
      link: 'https://www.synergymill.com/donate',
      target: 'synergy_mill_donate'
    }
  ];

  const volunteerOrgs = [
    {
      name: 'Agile Learning Institute',
      description: 'A 501(c)3 educational nonprofit whose mission is to provide free mentorship and one-on-one coaching services to support software engineers',
      link: 'https://agile-learning.institute/become-a-mentor',
      target: 'ali-mentorship'
    },
    {
      name: 'Carolina Code Conference',
      description: 'A welcoming and community-driven "polyglot" conference',
      link: 'https://carolina.codes/',
      target: 'carolina_code_conf'
    },
    {
      name: 'Code with the Carolinas',
      description: 'A community of civic tech volunteers working together to improve wellbeing in North and South Carolina',
      link: 'https://codewiththecarolinas.org/volunteer.html',
      target: 'code_carolinas'
    },
    {
      name: 'HackGreenville Labs',
      description: 'Mentoring, Inspiring and Innovating Local Tech',
      link: route('labs.index'),
      target: undefined
    },
    {
      name: 'HackGreenville Nights',
      description: 'A "Quarterly-ish" Gathering of Greenville\'s Tech Community',
      link: route('hg-nights.index'),
      target: undefined
    },
    {
      name: 'Synergy Mill',
      description: 'Needs: Gardening, Shop Cleanup',
      link: 'https://www.synergymill.com/',
      target: 'synergy_mill'
    }
  ];

  return (
    <AppLayout title="Tech Giving Opportunities around Greenville, SC">
      <Head>
        <meta name="description" content="Contribute time or money to local Upstate, SC tech, maker, and tinker non-profits or open-source projects through with these HackGreenville partners." />
      </Head>

      <div className="container mx-auto px-4 py-8">
        <div className="text-center mb-12">
          <h1 className="text-5xl md:text-6xl font-light text-blue-600 mb-6">
            How do I contribute?
          </h1>
          <p className="text-xl text-gray-700 max-w-4xl mx-auto">
            Contribute time or money to local Upstate, SC tech, maker, and tinker 
            non-profits or open-source projects through with these HackGreenville partners.
          </p>
        </div>

        {/* Donation Section */}
        <section className="mb-16">
          <div className="flex items-center mb-8">
            <Heart size={32} className="mr-3 text-red-500" />
            <h2 className="text-3xl font-bold text-gray-900">Donate</h2>
          </div>
          
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-2">
            {donationOrgs.map((org, index) => (
              <Card key={index} className="hover:shadow-lg transition-shadow">
                <CardHeader>
                  <CardTitle className="text-xl">{org.name}</CardTitle>
                </CardHeader>
                <CardContent>
                  <p className="text-gray-700 mb-4 line-clamp-4">
                    {org.description}
                  </p>
                  <a 
                    href={org.link} 
                    target={org.target}
                    rel="noopener noreferrer"
                  >
                    <Button className="w-full">
                      Learn More
                      <ExternalLink size={16} className="ml-2" />
                    </Button>
                  </a>
                </CardContent>
              </Card>
            ))}
          </div>
        </section>

        {/* Volunteer Section */}
        <section>
          <div className="flex items-center mb-8">
            <HandHeart size={32} className="mr-3 text-blue-500" />
            <h2 className="text-3xl font-bold text-gray-900">Volunteer</h2>
          </div>
          
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            {volunteerOrgs.map((org, index) => (
              <Card key={index} className="hover:shadow-lg transition-shadow">
                <CardHeader>
                  <CardTitle className="text-xl">{org.name}</CardTitle>
                </CardHeader>
                <CardContent>
                  <p className="text-gray-700 mb-4 line-clamp-4">
                    {org.description}
                  </p>
                  <a 
                    href={org.link} 
                    target={org.target}
                    rel="noopener noreferrer"
                  >
                    <Button className="w-full">
                      Learn More
                      {org.target && <ExternalLink size={16} className="ml-2" />}
                    </Button>
                  </a>
                </CardContent>
              </Card>
            ))}
          </div>
        </section>

        {/* Call to Action */}
        <div className="mt-16 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg p-8 text-center">
          <h3 className="text-2xl font-bold mb-4">Ready to Make a Difference?</h3>
          <p className="text-lg mb-6">
            Join our community and start contributing to the amazing tech ecosystem in Greenville!
          </p>
          <div className="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
            <a href={route('join-slack')}>
              <Button variant="secondary" size="lg">
                Join Our Slack
              </Button>
            </a>
            <a href={route('contact')}>
              <Button variant="outline" size="lg" className="border-white text-white hover:bg-white hover:text-green-600">
                Get in Touch
              </Button>
            </a>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}