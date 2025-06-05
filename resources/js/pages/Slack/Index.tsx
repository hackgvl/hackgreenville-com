import { Head, useForm } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Mail, MessageSquare, User, Link, Users } from 'lucide-react';

export default function SlackIndex() {
  const { data, setData, post, processing, errors } = useForm({
    name: '',
    contact: '',
    reason: '',
    url: '',
    rules: false,
    'h-captcha-response': '',
  });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    post('/join-slack');
  };

  return (
    <AppLayout title="Join Slack - HackGreenville">
      <Head>
        <meta
          name="description"
          content="Join the HackGreenville Slack community! Connect with local developers, designers, and tech enthusiasts."
        />
      </Head>

      <div className="container mx-auto px-4 py-8">
        <div className="max-w-2xl mx-auto">
          <div className="text-center mb-8">
            <h1 className="text-4xl font-bold text-foreground mb-4">
              Join Our Slack Community
            </h1>
            <p className="text-lg text-gray-600">
              Connect with local developers, designers, and tech enthusiasts in
              the Greenville area. Join our vibrant community on Slack!
            </p>
          </div>

          <Card>
            <CardHeader>
              <CardTitle className="flex items-center">
                <Users className="mr-2" size={24} />
                Request to Join
              </CardTitle>
            </CardHeader>
            <CardContent>
              <form onSubmit={submit} className="space-y-6">
                <div>
                  <label
                    htmlFor="name"
                    className="block text-sm font-medium text-gray-700 mb-2"
                  >
                    <User size={16} className="inline mr-1" />
                    Your Name *
                  </label>
                  <input
                    id="name"
                    type="text"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                  />
                  {errors.name && (
                    <p className="mt-1 text-sm text-red-600">{errors.name}</p>
                  )}
                </div>

                <div>
                  <label
                    htmlFor="contact"
                    className="block text-sm font-medium text-gray-700 mb-2"
                  >
                    <Mail size={16} className="inline mr-1" />
                    Email Address *
                  </label>
                  <input
                    id="contact"
                    type="email"
                    value={data.contact}
                    onChange={(e) => setData('contact', e.target.value)}
                    className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                  />
                  {errors.contact && (
                    <p className="mt-1 text-sm text-red-600">
                      {errors.contact}
                    </p>
                  )}
                </div>

                <div>
                  <label
                    htmlFor="reason"
                    className="block text-sm font-medium text-gray-700 mb-2"
                  >
                    <MessageSquare size={16} className="inline mr-1" />
                    Why do you want to join HackGreenville? *
                  </label>
                  <textarea
                    id="reason"
                    value={data.reason}
                    onChange={(e) => setData('reason', e.target.value)}
                    rows={4}
                    className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Tell us about your interest in the tech community..."
                    required
                  />
                  {errors.reason && (
                    <p className="mt-1 text-sm text-red-600">{errors.reason}</p>
                  )}
                </div>

                <div>
                  <label
                    htmlFor="url"
                    className="block text-sm font-medium text-gray-700 mb-2"
                  >
                    <Link size={16} className="inline mr-1" />
                    Website/LinkedIn/GitHub (Optional)
                  </label>
                  <input
                    id="url"
                    type="url"
                    value={data.url}
                    onChange={(e) => setData('url', e.target.value)}
                    className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="https://..."
                  />
                  {errors.url && (
                    <p className="mt-1 text-sm text-red-600">{errors.url}</p>
                  )}
                </div>

                <div className="flex items-start">
                  <input
                    id="rules"
                    type="checkbox"
                    checked={data.rules}
                    onChange={(e) => setData('rules', e.target.checked)}
                    className="mt-1 mr-3"
                    required
                  />
                  <label htmlFor="rules" className="text-sm text-gray-700">
                    I agree to follow the{' '}
                    <a
                      href="/code-of-conduct"
                      target="_blank"
                      className="text-blue-600 hover:text-blue-800 underline"
                    >
                      HackGreenville Code of Conduct
                    </a>{' '}
                    and community guidelines *
                  </label>
                </div>
                {errors.rules && (
                  <p className="mt-1 text-sm text-red-600">{errors.rules}</p>
                )}

                {/* HCaptcha placeholder - would need actual implementation */}
                <div className="border border-gray-300 rounded-lg p-4 bg-gray-50">
                  <p className="text-sm text-gray-600">
                    Captcha verification would go here
                  </p>
                </div>
                {errors['h-captcha-response'] && (
                  <p className="mt-1 text-sm text-red-600">
                    {errors['h-captcha-response']}
                  </p>
                )}

                <Button type="submit" disabled={processing} className="w-full">
                  {processing ? 'Submitting...' : 'Request to Join Slack'}
                </Button>
              </form>
            </CardContent>
          </Card>

          <div className="mt-8 p-4 bg-blue-50 rounded-lg">
            <h3 className="font-semibold text-blue-900 mb-2">
              What to expect:
            </h3>
            <ul className="text-sm text-blue-800 space-y-1">
              <li>
                • Your request will be reviewed by our community moderators
              </li>
              <li>
                • You'll receive an invitation email within 1-2 business days
              </li>
              <li>
                • Join discussions about local tech events, jobs, and projects
              </li>
              <li>• Connect with developers, designers, and entrepreneurs</li>
            </ul>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
