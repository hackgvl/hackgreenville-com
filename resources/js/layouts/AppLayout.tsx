import { Head, Link } from '@inertiajs/react';
import { PropsWithChildren } from 'react';
import { Button } from '@/components/ui/button';
import {
  NavigationMenu,
  NavigationMenuContent,
  NavigationMenuItem,
  NavigationMenuLink,
  NavigationMenuList,
  NavigationMenuTrigger,
} from '@/components/ui/navigation-menu';
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
      <nav className="bg-primary shadow-sm" style={{ backgroundColor: '#3b82f6' }}>
        <div className="container mx-auto px-4">
          <div className="flex justify-between items-center h-16">
            {/* Logo */}
            <div className="flex items-center">
              <Link href={route('home')} className="flex items-center">
                <img
                  className="h-8 w-auto"
                  src="/img/logo-v2.png"
                  alt="HackGreenville"
                />
              </Link>
            </div>

            {/* Desktop Navigation Menu */}
            <div className="hidden md:flex items-center flex-1 justify-center">
              <NavigationMenu>
                <NavigationMenuList>
                  {/* Primary Navigation Items */}
                  {navItems.slice(0, 4).map((item) => {
                    const IconComponent = item.icon;
                    return (
                      <NavigationMenuItem key={item.route}>
                        <NavigationMenuLink asChild>
                          <Link
                            href={route(item.route)}
                            className="group inline-flex h-9 w-max items-center justify-center rounded-md px-4 py-2 text-sm font-medium transition-colors hover:bg-white/10 hover:text-white focus:bg-white/10 focus:text-white focus:outline-none disabled:pointer-events-none disabled:opacity-50 text-white"
                          >
                            <IconComponent size={16} className="mr-2" />
                            <span>{item.label}</span>
                          </Link>
                        </NavigationMenuLink>
                      </NavigationMenuItem>
                    );
                  })}
                  
                  {/* More Menu Dropdown */}
                  <NavigationMenuItem>
                    <NavigationMenuTrigger className="bg-transparent text-white hover:bg-white/10 hover:text-white focus:bg-white/10 focus:text-white data-[state=open]:bg-white/10">
                      <Menu size={16} className="mr-2" />
                      More
                    </NavigationMenuTrigger>
                    <NavigationMenuContent>
                      <div className="grid w-[400px] gap-3 p-4 md:w-[500px] md:grid-cols-2 lg:w-[600px]">
                        {navItems.slice(4).map((item) => {
                          const IconComponent = item.icon;
                          return (
                            <NavigationMenuLink key={item.route} asChild>
                              <Link
                                href={route(item.route)}
                                className="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                              >
                                <div className="flex items-center space-x-2">
                                  <IconComponent size={16} className="text-muted-foreground" />
                                  <div className="text-sm font-medium leading-none">{item.label}</div>
                                </div>
                                <p className="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                  {item.label === 'HG Nights' && 'Monthly community events and meetups'}
                                  {item.label === 'About Us' && 'Learn about our tech community'}
                                  {item.label === 'Give' && 'Support our mission and volunteer'}
                                  {item.label === 'Contact' && 'Get in touch with our team'}
                                </p>
                              </Link>
                            </NavigationMenuLink>
                          );
                        })}
                      </div>
                    </NavigationMenuContent>
                  </NavigationMenuItem>
                </NavigationMenuList>
              </NavigationMenu>
            </div>

            {/* Right side buttons */}
            <div className="hidden md:flex items-center space-x-3">
              <Link href={route('join-slack')}>
                <Button
                  variant="outline"
                  size="sm"
                  className="border-white/20 text-white hover:bg-white/10 hover:text-white"
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
                className="p-2 rounded-md hover:bg-white/10 text-white"
              >
                {isMenuOpen ? <X size={24} /> : <Menu size={24} />}
              </button>
            </div>
          </div>

          {/* Mobile Navigation */}
          {isMenuOpen && (
            <div className="md:hidden border-t border-white/20">
              <div className="px-2 pt-2 pb-3 space-y-1">
                {navItems.map((item) => {
                  const IconComponent = item.icon;
                  return (
                    <Link
                      key={item.route}
                      href={route(item.route)}
                      className="flex items-center space-x-2 px-3 py-2 text-sm font-medium hover:bg-white/10 rounded-md text-white"
                      onClick={() => setIsMenuOpen(false)}
                    >
                      <IconComponent size={16} />
                      <span>{item.label}</span>
                    </Link>
                  );
                })}
                <div className="border-t border-white/20 pt-2 mt-2 space-y-2">
                  <Link
                    href={route('join-slack')}
                    onClick={() => setIsMenuOpen(false)}
                  >
                    <Button
                      variant="outline"
                      size="sm"
                      className="w-full border-white/20 text-white hover:bg-white/10"
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
