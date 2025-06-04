import { Head, Link } from '@inertiajs/react';
import { PropsWithChildren } from 'react';
import { Button } from '../components/ui/button';
import { route } from '../helpers/route';
import {
  Calendar,
  CalendarCheck,
  Building,
  TestTube,
  Moon,
  Users,
  Handshake,
  Mail,
  Slack,
  Menu,
  X,
} from 'lucide-react';
import { useState } from 'react';

interface AppLayoutProps extends PropsWithChildren {
  title?: string;
}

export default function AppLayout({ title, children }: AppLayoutProps) {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  const navItems = [
    { route: 'calendar.index', label: 'Calendar', icon: Calendar },
    { route: 'calendar-feed.index', label: 'Calendar Feed', icon: Calendar },
    { route: 'events.index', label: 'Events', icon: CalendarCheck },
    { route: 'orgs.index', label: 'Organizations', icon: Building },
    { route: 'labs.index', label: 'Labs', icon: TestTube },
    { route: 'hg-nights.index', label: 'HG Nights', icon: Moon },
    { route: 'about', label: 'About Us', icon: Users },
    { route: 'give', label: 'Give', icon: Handshake },
    { route: 'contact', label: 'Contact', icon: Mail },
  ];

  return (
    <div className="min-h-screen" style={{ backgroundColor: '#f8fafc' }}>
      <Head title={title} />

      {/* Navigation */}
      <nav
        className="bg-primary text-primary-foreground shadow-sm"
        style={{ backgroundColor: '#3b82f6', color: 'white' }}
      >
        <div className="container mx-auto px-4">
          <div className="flex justify-between h-16">
            <div className="flex items-center">
              <Link href={route('home')} className="flex items-center">
                <img
                  className="h-8 w-auto"
                  src="/img/logo-v2.png"
                  alt="HackGreenville"
                />
              </Link>
            </div>

            {/* Desktop Navigation */}
            <div className="hidden md:flex items-center space-x-6">
              {navItems.map((item) => {
                const IconComponent = item.icon;
                return (
                  <Link
                    key={item.route}
                    href={route(item.route)}
                    className="flex items-center space-x-1 px-3 py-2 text-sm font-medium hover:text-primary-foreground/80 transition-colors"
                  >
                    <IconComponent size={16} />
                    <span>{item.label}</span>
                  </Link>
                );
              })}
            </div>

            {/* Right side buttons */}
            <div className="hidden md:flex items-center space-x-3">
              <Link href={route('join-slack')}>
                <Button
                  variant="outline"
                  size="sm"
                  className="border-primary-foreground/20 text-primary-foreground hover:bg-primary-foreground/10"
                >
                  <Slack size={16} className="mr-1" />
                  Join Slack
                </Button>
              </Link>
              <a
                href="https://hackgreenville.slack.com"
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex"
              >
                <Button variant="secondary" size="sm">
                  <Slack size={16} className="mr-1" />
                  Log In to Slack
                </Button>
              </a>
            </div>

            {/* Mobile menu button */}
            <div className="md:hidden flex items-center">
              <button
                onClick={() => setIsMenuOpen(!isMenuOpen)}
                className="p-2 rounded-md hover:bg-primary-foreground/10"
              >
                {isMenuOpen ? <X size={24} /> : <Menu size={24} />}
              </button>
            </div>
          </div>

          {/* Mobile Navigation */}
          {isMenuOpen && (
            <div className="md:hidden border-t border-primary-foreground/20">
              <div className="px-2 pt-2 pb-3 space-y-1">
                {navItems.map((item) => {
                  const IconComponent = item.icon;
                  return (
                    <Link
                      key={item.route}
                      href={route(item.route)}
                      className="flex items-center space-x-2 px-3 py-2 text-sm font-medium hover:bg-primary-foreground/10 rounded-md"
                      onClick={() => setIsMenuOpen(false)}
                    >
                      <IconComponent size={16} />
                      <span>{item.label}</span>
                    </Link>
                  );
                })}
                <div className="border-t border-primary-foreground/20 pt-2 mt-2 space-y-2">
                  <Link
                    href={route('join-slack')}
                    onClick={() => setIsMenuOpen(false)}
                  >
                    <Button
                      variant="outline"
                      size="sm"
                      className="w-full border-primary-foreground/20"
                    >
                      <Slack size={16} className="mr-1" />
                      Join Slack
                    </Button>
                  </Link>
                  <a
                    href="https://hackgreenville.slack.com"
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    <Button variant="secondary" size="sm" className="w-full">
                      <Slack size={16} className="mr-1" />
                      Log In to Slack
                    </Button>
                  </a>
                </div>
              </div>
            </div>
          )}
        </div>
      </nav>

      {/* Main Content */}
      <main>{children}</main>
    </div>
  );
}
