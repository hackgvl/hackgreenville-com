import { Head } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { CheckCircle, ArrowLeft, Users } from 'lucide-react';

export default function SlackSubmitted() {
  return (
    <AppLayout title="Request Submitted - HackGreenville">
      <Head>
        <meta
          name="description"
          content="Your Slack join request has been submitted. We'll review and send you an invitation soon!"
        />
      </Head>

      <div className="container mx-auto px-4 py-8">
        <div className="max-w-2xl mx-auto">
          <Card>
            <CardHeader className="text-center">
              <div className="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <CheckCircle size={32} className="text-green-600" />
              </div>
              <CardTitle className="text-2xl text-green-800">
                Request Submitted Successfully!
              </CardTitle>
            </CardHeader>
            <CardContent className="text-center space-y-4">
              <p className="text-lg text-gray-700">
                Thank you for your interest in joining the HackGreenville Slack
                community! We've received your request and will review it
                shortly.
              </p>

              <div className="bg-blue-50 rounded-lg p-4 my-6">
                <h3 className="font-semibold text-blue-900 mb-2">
                  What happens next?
                </h3>
                <ul className="text-sm text-blue-800 space-y-1 text-left">
                  <li>• Our community moderators will review your request</li>
                  <li>
                    • You'll receive an invitation email within 1-2 business
                    days
                  </li>
                  <li>
                    • Check your spam folder if you don't see the invitation
                  </li>
                  <li>
                    • Once you join, introduce yourself in the #introductions
                    channel
                  </li>
                </ul>
              </div>

              <p className="text-gray-600">
                While you wait, check out our upcoming events and get involved
                in the local tech community!
              </p>

              <div className="flex flex-col sm:flex-row gap-4 justify-center mt-6">
                <a href="/">
                  <Button variant="outline">
                    <ArrowLeft size={16} className="mr-2" />
                    Back to Home
                  </Button>
                </a>
                <a href="/events">
                  <Button>
                    <Users size={16} className="mr-2" />
                    View Events
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
