import { Head, router } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import AppLayout from '../../layouts/AppLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import {
  Calendar,
  MapPin,
  ExternalLink,
  Grid3X3,
  List,
  Filter,
  Tag,
} from 'lucide-react';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';

interface Category {
  id: number;
  label: string;
}

interface Event {
  id: number;
  event_name: string;
  description: string;
  active_at: string;
  cancelled_at: string | null;
  uri: string;
  organization: {
    title: string;
    category?: Category;
  };
  venue?: {
    name: string;
    address: string;
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
  const [viewMode, setViewMode] = useState<'grid' | 'horizontal'>('grid');

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
    });
  };

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
        <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
          <div className="flex items-center gap-4">
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
                    <SelectItem
                      key={category.id}
                      value={category.id.toString()}
                    >
                      {category.label}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
          </div>

          {/* View Toggle */}
          <div className="flex items-center gap-2">
            <Button
              variant={viewMode === 'grid' ? 'default' : 'outline'}
              size="sm"
              onClick={() => setViewMode('grid')}
            >
              <Grid3X3 size={16} />
            </Button>
            <Button
              variant={viewMode === 'horizontal' ? 'default' : 'outline'}
              size="sm"
              onClick={() => setViewMode('horizontal')}
            >
              <List size={16} />
            </Button>
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
        ) : viewMode === 'grid' ? (
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            {events.map((event) => (
              <Card
                key={event.id}
                className="hover:shadow-lg transition-shadow"
              >
                <CardHeader>
                  <div className="flex justify-between items-start mb-2">
                    <div className="flex flex-wrap gap-2">
                      {event.cancelled_at && (
                        <div className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                          CANCELLED
                        </div>
                      )}
                      {event.organization.category && (
                        <div className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                          <Tag size={12} className="mr-1" />
                          {event.organization.category.label}
                        </div>
                      )}
                    </div>
                  </div>
                  <CardTitle className="text-xl line-clamp-2">
                    {event.event_name}
                  </CardTitle>
                  <p className="text-sm text-muted-foreground font-medium">
                    {event.organization.title}
                  </p>
                </CardHeader>
                <CardContent>
                  <div className="space-y-3">
                    <div className="flex items-start space-x-2 text-sm">
                      <Calendar size={16} className="mt-0.5 text-gray-500" />
                      <span>{formatDate(event.active_at)}</span>
                    </div>

                    {event.venue && (
                      <div className="flex items-start space-x-2 text-sm">
                        <MapPin size={16} className="mt-0.5 text-gray-500" />
                        <div>
                          <div className="font-medium">{event.venue.name}</div>
                          <div className="text-gray-600">
                            {event.venue.address}
                          </div>
                        </div>
                      </div>
                    )}

                    {event.description && (
                      <p className="text-sm text-gray-700 line-clamp-3">
                        {event.description
                          .replace(/(<([^>]+)>)/gi, '')
                          .substring(0, 120)}
                        ...
                      </p>
                    )}

                    <div className="pt-3">
                      <Button asChild size="sm" className="w-full">
                        <a
                          href={event.uri}
                          target="_blank"
                          rel="noopener noreferrer"
                        >
                          <ExternalLink size={16} className="mr-2" />
                          View Details
                        </a>
                      </Button>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        ) : (
          <div className="space-y-4">
            {events.map((event) => (
              <Card
                key={event.id}
                className="hover:shadow-lg transition-shadow"
              >
                <CardContent className="p-6">
                  <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div className="flex-1 min-w-0">
                      <div className="flex flex-wrap gap-2 mb-3">
                        {event.cancelled_at && (
                          <div className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            CANCELLED
                          </div>
                        )}
                        {event.organization.category && (
                          <div className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <Tag size={12} className="mr-1" />
                            {event.organization.category.label}
                          </div>
                        )}
                      </div>

                      <h3 className="text-xl font-bold text-foreground line-clamp-1 mb-2">
                        {event.event_name}
                      </h3>

                      <p className="text-sm text-muted-foreground font-medium mb-3">
                        {event.organization.title}
                      </p>

                      <div className="flex flex-col sm:flex-row sm:items-center gap-3 text-sm text-gray-600">
                        <div className="flex items-center space-x-2">
                          <Calendar size={16} className="text-gray-500" />
                          <span>{formatDate(event.active_at)}</span>
                        </div>

                        {event.venue && (
                          <div className="flex items-center space-x-2">
                            <MapPin size={16} className="text-gray-500" />
                            <span>{event.venue.name}</span>
                          </div>
                        )}
                      </div>

                      {event.description && (
                        <p className="text-sm text-gray-700 line-clamp-2 mt-3">
                          {event.description
                            .replace(/(<([^>]+)>)/gi, '')
                            .substring(0, 200)}
                          ...
                        </p>
                      )}
                    </div>

                    <div className="flex-shrink-0">
                      <Button asChild size="sm">
                        <a
                          href={event.uri}
                          target="_blank"
                          rel="noopener noreferrer"
                        >
                          <ExternalLink size={16} className="mr-2" />
                          View Details
                        </a>
                      </Button>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        )}
      </div>
    </AppLayout>
  );
}
