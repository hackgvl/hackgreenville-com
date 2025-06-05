import { Head, router } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import { Card, CardContent } from '@/components/ui/card';
import { Calendar, Filter } from 'lucide-react';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import EventsList from '@/components/EventsList';

interface Category {
  id: number;
  label: string;
}

interface Event {
  id: number;
  event_name: string;
  description: string;
  active_at: string;
  expire_at?: string;
  cancelled_at: string | null;
  rsvp_count?: number;
  uri: string;
  service: string;
  organization: {
    title: string;
    category?: Category;
  };
  venue?: {
    name: string;
    address?: string;
    city?: string;
    state?: string;
  };
}

interface EventsIndexProps {
  events: Event[];
  categories: Category[];
  selectedCategory?: string;
}

export default function EventsIndex({
  events,
  categories,
  selectedCategory,
}: EventsIndexProps) {
  const handleCategoryChange = (categoryId: string) => {
    router.get(
      '/events',
      categoryId === 'all' ? {} : { category: categoryId },
      { preserveState: true },
    );
  };

  return (
    <AppLayout title="Events - HackGreenville">
      <Head>
        <meta
          name="description"
          content="Discover upcoming tech events, meetups, and workshops in the Greenville, SC area."
        />
      </Head>

      <div className="container mx-auto px-4 py-8">
        <div className="mb-8">
          <h1 className="text-4xl font-bold text-foreground mb-4">
            Upcoming Events
          </h1>
          <p className="text-lg text-gray-600">
            Discover upcoming tech events, meetups, and workshops in the
            Greenville, SC area.
          </p>
        </div>

        {/* Controls */}
        <div className="flex items-center gap-4 mb-8">
          {/* Category Filter */}
          <div className="flex items-center gap-2">
            <Filter size={16} />
            <Select
              value={selectedCategory || 'all'}
              onValueChange={handleCategoryChange}
            >
              <SelectTrigger className="w-48">
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">All Categories</SelectItem>
                {categories.map((category) => (
                  <SelectItem key={category.id} value={category.id.toString()}>
                    {category.label}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        </div>

        {events.length === 0 ? (
          <Card>
            <CardContent className="py-12 text-center">
              <Calendar size={48} className="mx-auto text-gray-400 mb-4" />
              <h3 className="text-xl font-semibold text-foreground mb-2">
                No upcoming events
              </h3>
              <p className="text-gray-600">Check back soon for new events!</p>
            </CardContent>
          </Card>
        ) : (
          <div className="max-w-4xl mx-auto">
            <EventsList events={events} />
          </div>
        )}
      </div>
    </AppLayout>
  );
}
