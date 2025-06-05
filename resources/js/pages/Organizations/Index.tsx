import { Head, Link } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import AppLayout from '../../layouts/AppLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import {
  Building,
  ExternalLink,
  Users,
  MapPin,
  Filter,
  Eye,
  EyeOff,
} from 'lucide-react';

interface Organization {
  id: number;
  title: string;
  description: string;
  slug: string;
  focus_area: string;
  established_at: string;
  events_count: number;
  status: string;
  status_label: string;
}

interface OrganizationsIndexProps {
  organizations: Organization[];
}

export default function OrganizationsIndex({
  organizations,
}: OrganizationsIndexProps) {
  const [statusFilter, setStatusFilter] = useState<
    'all' | 'active' | 'inactive'
  >('all');
  const [showInactive, setShowInactive] = useState(true);

  // Filter and sort organizations
  const filteredOrganizations = useMemo(() => {
    let filtered = organizations;

    if (statusFilter === 'active') {
      filtered = organizations.filter((org) => org.status === 'active');
    } else if (statusFilter === 'inactive') {
      filtered = organizations.filter((org) => org.status === 'inactive');
    }

    // Sort with active first, then by title
    return filtered.sort((a, b) => {
      if (a.status !== b.status) {
        return a.status === 'active' ? -1 : 1;
      }
      return a.title.localeCompare(b.title);
    });
  }, [organizations, statusFilter]);

  const activeCount = organizations.filter(
    (org) => org.status === 'active',
  ).length;
  const inactiveCount = organizations.filter(
    (org) => org.status === 'inactive',
  ).length;
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

        {/* Filter Controls */}
        <div className="mb-6 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
          <div className="flex flex-wrap gap-2">
            <Button
              variant={statusFilter === 'all' ? 'default' : 'outline'}
              size="sm"
              onClick={() => setStatusFilter('all')}
              className="flex items-center space-x-2"
            >
              <Filter size={16} />
              <span>All ({organizations.length})</span>
            </Button>
            <Button
              variant={statusFilter === 'active' ? 'default' : 'outline'}
              size="sm"
              onClick={() => setStatusFilter('active')}
              className="flex items-center space-x-2"
            >
              <Eye size={16} />
              <span>Active ({activeCount})</span>
            </Button>
            <Button
              variant={statusFilter === 'inactive' ? 'default' : 'outline'}
              size="sm"
              onClick={() => setStatusFilter('inactive')}
              className="flex items-center space-x-2"
            >
              <EyeOff size={16} />
              <span>Inactive ({inactiveCount})</span>
            </Button>
          </div>

          <div className="text-sm text-gray-600">
            Showing {filteredOrganizations.length} organization
            {filteredOrganizations.length !== 1 ? 's' : ''}
          </div>
        </div>

        {filteredOrganizations.length === 0 ? (
          <Card>
            <CardContent className="py-12 text-center">
              <Building size={48} className="mx-auto text-gray-400 mb-4" />
              <h3 className="text-xl font-semibold text-gray-900 mb-2">
                No organizations found
              </h3>
              <p className="text-gray-600">
                {statusFilter === 'all'
                  ? 'Check back soon for new organizations!'
                  : `No ${statusFilter} organizations found.`}
              </p>
            </CardContent>
          </Card>
        ) : (
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            {filteredOrganizations.map((org) => (
              <Card
                key={org.id}
                className={`hover:shadow-lg transition-all duration-200 ${
                  org.status === 'inactive'
                    ? 'opacity-60 hover:opacity-80 border-dashed'
                    : 'hover:shadow-lg'
                }`}
              >
                <CardHeader>
                  <div className="flex items-start justify-between">
                    <CardTitle
                      className={`text-xl line-clamp-2 ${
                        org.status === 'inactive' ? 'text-gray-500' : ''
                      }`}
                    >
                      {org.title}
                    </CardTitle>
                    <div
                      className={`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2 flex-shrink-0 ${
                        org.status === 'active'
                          ? 'bg-green-100 text-green-800'
                          : 'bg-gray-100 text-gray-600'
                      }`}
                    >
                      {org.status_label}
                    </div>
                  </div>
                  {org.focus_area && (
                    <div className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 w-fit">
                      {org.focus_area}
                    </div>
                  )}
                </CardHeader>
                <CardContent>
                  <div className="space-y-3">
                    {org.description && (
                      <p
                        className={`text-sm line-clamp-3 ${
                          org.status === 'inactive'
                            ? 'text-gray-500'
                            : 'text-gray-700'
                        }`}
                      >
                        {org.description.substring(0, 150)}...
                      </p>
                    )}

                    <div
                      className={`flex items-center space-x-4 text-sm ${
                        org.status === 'inactive'
                          ? 'text-gray-400'
                          : 'text-gray-600'
                      }`}
                    >
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
                        <Button
                          size="sm"
                          className="w-full"
                          variant={
                            org.status === 'inactive' ? 'outline' : 'default'
                          }
                        >
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
