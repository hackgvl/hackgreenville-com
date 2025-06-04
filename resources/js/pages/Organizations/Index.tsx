import { Head, Link } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '../../components/ui/card';
import { Button } from '../../components/ui/button';
import { Building, ExternalLink, Users, MapPin } from 'lucide-react';

interface Organization {
  id: number;
  title: string;
  description: string;
  slug: string;
  focus_area: string;
  established_at: string;
  events_count: number;
}

interface OrganizationsIndexProps {
  organizations: Organization[];
}

export default function OrganizationsIndex({
  organizations,
}: OrganizationsIndexProps) {
  return (
    <AppLayout title="Organizations - HackGreenville">
      <Head>
        <meta
          name="description"
          content="Discover tech organizations, user groups, and communities in the Greenville, SC area."
        />
      </Head>

      <div className="container mx-auto px-4 py-8">
        <div className="mb-8">
          <h1 className="text-4xl font-bold text-gray-900 mb-4">
            Organizations
          </h1>
          <p className="text-lg text-gray-600">
            Discover tech organizations, user groups, and communities in the
            Greenville, SC area.
          </p>
        </div>

        {organizations.length === 0 ? (
          <Card>
            <CardContent className="py-12 text-center">
              <Building size={48} className="mx-auto text-gray-400 mb-4" />
              <h3 className="text-xl font-semibold text-gray-900 mb-2">
                No organizations found
              </h3>
              <p className="text-gray-600">
                Check back soon for new organizations!
              </p>
            </CardContent>
          </Card>
        ) : (
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            {organizations.map((org) => (
              <Card key={org.id} className="hover:shadow-lg transition-shadow">
                <CardHeader>
                  <CardTitle className="text-xl line-clamp-2">
                    {org.title}
                  </CardTitle>
                  {org.focus_area && (
                    <div className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 w-fit">
                      {org.focus_area}
                    </div>
                  )}
                </CardHeader>
                <CardContent>
                  <div className="space-y-3">
                    {org.description && (
                      <p className="text-sm text-gray-700 line-clamp-3">
                        {org.description.substring(0, 150)}...
                      </p>
                    )}

                    <div className="flex items-center space-x-4 text-sm text-gray-600">
                      {org.established_at && (
                        <div className="flex items-center space-x-1">
                          <MapPin size={14} />
                          <span>
                            Est. {new Date(org.established_at).getFullYear()}
                          </span>
                        </div>
                      )}
                      <div className="flex items-center space-x-1">
                        <Users size={14} />
                        <span>{org.events_count} events</span>
                      </div>
                    </div>

                    <div className="pt-3">
                      <Link href={`/orgs/${org.slug}`}>
                        <Button size="sm" className="w-full">
                          <ExternalLink size={16} className="mr-2" />
                          View Organization
                        </Button>
                      </Link>
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
