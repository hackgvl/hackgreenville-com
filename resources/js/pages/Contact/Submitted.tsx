import { Head } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import { Button } from '../../components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '../../components/ui/card';
import { CheckCircle, ArrowLeft } from 'lucide-react';

export default function ContactSubmitted() {
  return (
    <AppLayout title="Message Sent - HackGreenville">
      <Head>
        <meta name="description" content="Thank you for contacting HackGreenville. We'll get back to you soon!" />
      </Head>

      <div className="container mx-auto px-4 py-8">
        <div className="max-w-2xl mx-auto">
          <Card>
            <CardHeader className="text-center">
              <div className="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <CheckCircle size={32} className="text-green-600" />
              </div>
              <CardTitle className="text-2xl text-green-800">Message Sent Successfully!</CardTitle>
            </CardHeader>
            <CardContent className="text-center space-y-4">
              <p className="text-lg text-gray-700">
                Thank you for reaching out to HackGreenville. We've received your message and will get back to you as soon as possible.
              </p>
              
              <p className="text-gray-600">
                In the meantime, feel free to join our active Slack community to connect with other members and stay updated on upcoming events.
              </p>

              <div className="flex flex-col sm:flex-row gap-4 justify-center mt-6">
                <a href="/">
                  <Button variant="outline">
                    <ArrowLeft size={16} className="mr-2" />
                    Back to Home
                  </Button>
                </a>
                <a href="/join-slack">
                  <Button>
                    Join Slack Community
                  </Button>
                </a>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </AppLayout>
  );
}