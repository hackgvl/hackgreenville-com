import { Head } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import { Calendar } from 'lucide-react';

export default function CalendarIndex() {
  return (
    <AppLayout title="Calendar - HackGreenville">
      <Head>
        <meta name="description" content="View all upcoming tech events in the Greenville, SC area on our interactive calendar." />
      </Head>

      <div className="container mx-auto px-4 py-8">
        <div className="mb-8">
          <h1 className="text-4xl font-bold text-gray-900 mb-4">Event Calendar</h1>
          <p className="text-lg text-gray-600">
            View all upcoming tech events in the Greenville, SC area on our interactive calendar.
          </p>
        </div>

        <div className="bg-white rounded-lg shadow-sm border">
          <div className="p-6">
            <div className="flex items-center justify-center h-96 text-gray-500">
              <div className="text-center">
                <Calendar size={48} className="mx-auto mb-4" />
                <h3 className="text-xl font-semibold mb-2">Calendar Integration</h3>
                <p>Full calendar integration will be implemented here.</p>
                <p className="text-sm mt-2">This will use FullCalendar.js to display events.</p>
              </div>
            </div>
          </div>
        </div>

        <div className="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
          <h3 className="text-lg font-semibold text-blue-900 mb-2">Calendar Feed</h3>
          <p className="text-blue-700 mb-3">
            You can also subscribe to our calendar feed to stay updated with all events.
          </p>
          <a 
            href="/calendar-feed" 
            className="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          >
            Subscribe to Calendar Feed
          </a>
        </div>
      </div>
    </AppLayout>
  );
}