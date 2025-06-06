import { Head } from '@inertiajs/react';
import { Link } from '@/components/ui/link';
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
  Sun,
} from 'lucide-react';
import { useState } from 'react';
import { ThemeProvider, useTheme } from '@/contexts/ThemeContext';

interface AppLayoutProps extends PropsWithChildren {
  title?: string;
}

function ThemeToggle() {
  const { theme, toggleTheme } = useTheme();

  return (
    <Button variant="ghost" size="icon" onClick={toggleTheme}>
      {theme === 'light' ? (
        <Moon className="h-4 w-4" />
      ) : (
        <Sun className="h-4 w-4" />
      )}
    </Button>
  );
}

function AppLayoutContent({ title, children }: AppLayoutProps) {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  const primaryNavItems = [
    { route: 'events.index', label: 'Events' },
    { route: 'orgs.index', label: 'Organizations' },
    { route: 'calendar.index', label: 'Calendar' },
    { route: 'about', label: 'About Us' },
    { route: 'contact', label: 'Contact' },
  ];

  const secondaryNavItems = [
    {
      route: 'labs.index',
      label: 'Labs',
      description: 'Open source projects and code',
    },
    {
      route: 'hg-nights.index',
      label: 'HG Nights',
      description: 'Monthly community events',
    },
    { route: 'give', label: 'Give', description: 'Support our mission' },
    {
      route: 'calendar-feed.index',
      label: 'Calendar Feed',
      description: 'Subscribe to events',
    },
  ];

  return (
    <div className="min-h-screen bg-background">
      <Head title={title} />

      {/* Navigation - Mainline inspired */}
      <nav className="border-b border-border bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
        <div className="container mx-auto px-6">
          <div className="flex items-center justify-between h-16">
            {/* Logo */}
            <div className="flex items-center">
              <Link
                href={route('home')}
                variant="ghost"
                className="flex items-center space-x-3 hover:opacity-80 transition-opacity"
              >
                <div className="w-8 h-8 rounded-full bg-foreground flex items-center justify-center">
                  <span className="text-background font-bold text-sm">HG</span>
                </div>
                <span className="font-semibold text-lg text-foreground">
                  HackGreenville
                </span>
              </Link>
            </div>

            {/* Desktop Navigation */}
            <div className="hidden md:flex items-center justify-center flex-1">
              <NavigationMenu>
                <NavigationMenuList className="gap-1">
                  {primaryNavItems.slice(0, 4).map((item) => (
                    <NavigationMenuItem key={item.route}>
                      <NavigationMenuLink asChild>
                        <Link
                          href={route(item.route)}
                          variant="navigation"
                          className="group inline-flex h-9 w-max items-center justify-center rounded-md px-4 py-2 text-sm font-medium hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground focus:outline-none"
                        >
                          {item.label}
                        </Link>
                      </NavigationMenuLink>
                    </NavigationMenuItem>
                  ))}

                  {/* More dropdown */}
                  <NavigationMenuItem>
                    <NavigationMenuTrigger className="bg-transparent text-foreground hover:bg-accent hover:text-accent-foreground data-[state=open]:bg-accent !cursor-pointer">
                      More
                    </NavigationMenuTrigger>
                    <NavigationMenuContent>
                      <div className="grid w-[400px] gap-3 p-6 md:w-[500px] md:grid-cols-2 lg:w-[600px]">
                        {secondaryNavItems.map((item) => (
                          <NavigationMenuLink key={item.route} asChild>
                            <Link
                              href={route(item.route)}
                              variant="ghost"
                              className="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                            >
                              <div className="text-sm font-medium leading-none">
                                {item.label}
                              </div>
                              <p className="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                {item.description}
                              </p>
                            </Link>
                          </NavigationMenuLink>
                        ))}
                      </div>
                    </NavigationMenuContent>
                  </NavigationMenuItem>
                </NavigationMenuList>
              </NavigationMenu>
            </div>

            {/* Right side actions */}
            <div className="hidden md:flex items-center space-x-4">
              <ThemeToggle />
              <Button
                asChild
                variant="outline"
                size="sm"
                className="!cursor-pointer"
              >
                <Link href={route('join-slack')}>Join Slack</Link>
              </Button>
            </div>

            {/* Mobile menu button */}
            <div className="md:hidden">
              <Button
                variant="ghost"
                size="icon"
                onClick={() => setIsMenuOpen(!isMenuOpen)}
              >
                {isMenuOpen ? (
                  <X className="h-5 w-5" />
                ) : (
                  <Menu className="h-5 w-5" />
                )}
              </Button>
            </div>
          </div>

          {/* Mobile Navigation */}
          {isMenuOpen && (
            <div className="md:hidden border-t border-border py-4">
              <div className="space-y-2">
                {[...primaryNavItems, ...secondaryNavItems].map((item) => (
                  <Link
                    key={item.route}
                    href={route(item.route)}
                    variant="ghost"
                    className="block px-3 py-2 text-sm font-medium hover:bg-accent hover:text-accent-foreground rounded-md transition-colors"
                    onClick={() => setIsMenuOpen(false)}
                  >
                    {item.label}
                  </Link>
                ))}
                <div className="border-t border-border pt-4 mt-4 space-y-2">
                  <div className="flex items-center justify-between">
                    <span className="text-sm font-medium">Theme</span>
                    <ThemeToggle />
                  </div>
                  <Button
                    asChild
                    variant="outline"
                    size="sm"
                    className="w-full !cursor-pointer"
                  >
                    <Link href={route('join-slack')}>Join Slack</Link>
                  </Button>
                </div>
              </div>
            </div>
          )}
        </div>
      </nav>

      {/* Main Content */}
      <main className="flex-1">{children}</main>
    </div>
  );
}

export default function AppLayout({ title, children }: AppLayoutProps) {
  return (
    <ThemeProvider>
      <AppLayoutContent title={title}>{children}</AppLayoutContent>
    </ThemeProvider>
  );
}
