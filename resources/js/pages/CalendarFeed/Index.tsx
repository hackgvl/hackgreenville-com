import { Head } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '../../layouts/AppLayout';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { CalendarDays, Copy, Check, ExternalLink } from 'lucide-react';

interface Organization {
  id: number;
  title: string;
  checked: boolean;
}

interface CalendarFeedIndexProps {
  organizations: Organization[];
  baseUrl: string;
}

export default function CalendarFeedIndex({
  organizations: initialOrganizations,
  baseUrl,
}: CalendarFeedIndexProps) {
  const [organizations, setOrganizations] =
    useState<Organization[]>(initialOrganizations);
  const [copied, setCopied] = useState(false);

  const allSelected = organizations.every((org) => org.checked);
  const someSelected = organizations.some((org) => org.checked) && !allSelected;

  const toggleAll = () => {
    const newValue = !allSelected;
    setOrganizations((orgs) =>
      orgs.map((org) => ({ ...org, checked: newValue })),
    );
  };

  const toggleOrganization = (id: number) => {
    setOrganizations((orgs) =>
      orgs.map((org) =>
        org.id === id ? { ...org, checked: !org.checked } : org,
      ),
    );
  };

  const generateFeedUrl = (scheme?: string) => {
    const params = new URLSearchParams();

    if (!allSelected) {
      const selected = organizations
        .filter((org) => org.checked)
        .map((org) => org.id)
        .join('-');

      if (selected) {
        params.append('orgs', selected);
      }
    }

    let url = `${baseUrl}?${params.toString()}`;

    if (scheme) {
      url = url.replace(/^.*?:\/\//, scheme);
    }

    return url;
  };

  const copyLink = async () => {
    try {
      await navigator.clipboard.writeText(generateFeedUrl());
      setCopied(true);
      setTimeout(() => setCopied(false), 2000);
    } catch (err) {
      alert('Failed to copy link');
    }
  };

  return (
    <AppLayout title="Build a Calendar Feed of the events you're interested in">
      <Head>
        <meta
          name="description"
          content="Generate an iCal calendar feed to pull events for one, many, or all organizations promoted by HackGreenville into your calendar app"
        />
      </Head>

      <div className="container mx-auto px-4 py-8">
        <div className="max-w-2xl mx-auto">
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center text-2xl">
                <CalendarDays size={28} className="mr-3 text-blue-600" />
                Curated Event Feed
              </CardTitle>
            </CardHeader>
            <CardContent className="space-y-6">
              {/* Select All */}
              <div
                className="flex items-center space-x-3 p-4 bg-slate-50 hover:bg-slate-100 rounded-lg cursor-pointer transition-colors"
                onClick={toggleAll}
              >
                <Checkbox checked={allSelected} onCheckedChange={toggleAll} />
                <span className="font-medium text-slate-700">
                  Select All / None
                </span>
              </div>

              <div className="border-t border-slate-200"></div>

              {/* Organization List */}
              <div className="space-y-2">
                {organizations.map((org) => (
                  <div
                    key={org.id}
                    className="flex items-center space-x-3 p-3 bg-slate-50 hover:bg-slate-100 rounded-lg cursor-pointer transition-colors"
                    onClick={() => toggleOrganization(org.id)}
                  >
                    <Checkbox
                      checked={org.checked}
                      onCheckedChange={() => toggleOrganization(org.id)}
                    />
                    <span
                      className={`${
                        org.checked
                          ? 'font-semibold text-slate-900'
                          : 'font-medium text-slate-700'
                      }`}
                    >
                      {org.title}
                    </span>
                  </div>
                ))}
              </div>

              {/* Actions */}
              <div className="space-y-4 pt-4 border-t border-slate-200">
                <div className="text-center">
                  <a
                    href={generateFeedUrl('webcal://')}
                    className="inline-block"
                  >
                    <Button size="lg" className="w-full sm:w-auto">
                      <CalendarDays size={20} className="mr-2" />
                      Subscribe to Calendar Feed
                      <ExternalLink size={16} className="ml-2" />
                    </Button>
                  </a>
                </div>

                <div className="space-y-3">
                  <label
                    htmlFor="feed-url"
                    className="block text-sm font-medium text-slate-700"
                  >
                    Calendar Feed URL:
                  </label>
                  <Input
                    id="feed-url"
                    type="text"
                    value={generateFeedUrl()}
                    readOnly
                    className="font-mono text-sm"
                  />

                  <Button
                    variant="outline"
                    onClick={copyLink}
                    className={`w-full ${
                      copied
                        ? 'bg-green-50 border-green-200 text-green-700 hover:bg-green-100'
                        : ''
                    }`}
                  >
                    {copied ? (
                      <>
                        <Check size={16} className="mr-2" />
                        Copied!
                      </>
                    ) : (
                      <>
                        <Copy size={16} className="mr-2" />
                        Copy Link
                      </>
                    )}
                  </Button>
                </div>
              </div>

              {/* Instructions */}
              <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 className="font-semibold text-blue-900 mb-2">
                  How to use this feed:
                </h4>
                <ul className="text-sm text-blue-800 space-y-1">
                  <li>
                    • Click "Subscribe to Calendar Feed" to add to your default
                    calendar app
                  </li>
                  <li>
                    • Or copy the URL and add it manually to your calendar
                    application
                  </li>
                  <li>
                    • Select specific organizations to create a customized feed
                  </li>
                  <li>
                    • Your calendar will automatically update with new events
                  </li>
                </ul>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </AppLayout>
  );
}
