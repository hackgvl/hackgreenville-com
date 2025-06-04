import { Head, useForm } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import { Button } from '../../components/ui/button';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '../../components/ui/card';
import { Mail, MessageSquare, User } from 'lucide-react';

export default function ContactIndex() {
  const { data, setData, post, processing, errors } = useForm({
    name: '',
    contact: '',
    message: '',
  });

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    post('/contact');
  };

  return (
    <AppLayout title="Contact - HackGreenville">
      <Head>
        <meta
          name="description"
          content="Get in touch with the HackGreenville community. We'd love to hear from you!"
        />
      </Head>

      <div className="container mx-auto px-4 py-8">
        <div className="max-w-2xl mx-auto">
          <div className="text-center mb-8">
            <h1 className="text-4xl font-bold text-gray-900 mb-4">
              Contact Us
            </h1>
            <p className="text-lg text-gray-600">
              Get in touch with the HackGreenville community. We'd love to hear
              from you!
            </p>
          </div>

          <Card>
            <CardHeader>
              <CardTitle className="flex items-center">
                <Mail className="mr-2" size={24} />
                Send us a message
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
                    Your Name
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
                    Email or Phone
                  </label>
                  <input
                    id="contact"
                    type="text"
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
                    htmlFor="message"
                    className="block text-sm font-medium text-gray-700 mb-2"
                  >
                    <MessageSquare size={16} className="inline mr-1" />
                    Message
                  </label>
                  <textarea
                    id="message"
                    value={data.message}
                    onChange={(e) => setData('message', e.target.value)}
                    rows={6}
                    className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                  />
                  {errors.message && (
                    <p className="mt-1 text-sm text-red-600">
                      {errors.message}
                    </p>
                  )}
                </div>

                <Button type="submit" disabled={processing} className="w-full">
                  {processing ? 'Sending...' : 'Send Message'}
                </Button>
              </form>
            </CardContent>
          </Card>

          <div className="mt-8 text-center text-gray-600">
            <p>You can also reach us on our</p>
            <a
              href="/join-slack"
              className="text-blue-600 hover:text-blue-800 underline font-medium"
            >
              Slack community
            </a>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
