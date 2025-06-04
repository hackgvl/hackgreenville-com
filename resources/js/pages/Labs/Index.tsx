import { Head } from '@inertiajs/react';
import AppLayout from '../../layouts/AppLayout';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '../../components/ui/card';
import { Button } from '../../components/ui/button';
import { TestTube, Github, ExternalLink, Globe } from 'lucide-react';

interface Project {
  name: string;
  description: string;
  link: string;
  linkType: string;
  status: string;
}

interface LabsIndexProps {
  projects: Project[];
}

export default function LabsIndex({ projects }: LabsIndexProps) {
  const getIcon = (linkType: string) => {
    return linkType === 'github' ? Github : Globe;
  };

  const getLinkText = (linkType: string) => {
    return linkType === 'github' ? 'View on GitHub' : 'Visit Website';
  };

  return (
    <AppLayout title="Labs - HackGreenville">
      <Head>
        <meta
          name="description"
          content="Explore open source projects and experiments from the HackGreenville community."
        />
      </Head>

      <div className="container mx-auto px-4 py-8">
        <div className="mb-8">
          <div className="flex items-center mb-4">
            <TestTube size={32} className="mr-3 text-blue-600" />
            <h1 className="text-4xl font-bold text-gray-900">
              HackGreenville Labs
            </h1>
          </div>
          <p className="text-lg text-gray-600">
            Explore open source projects and experiments from the HackGreenville
            community. These projects are built by and for our members.
          </p>
        </div>

        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          {projects.map((project, index) => {
            const IconComponent = getIcon(project.linkType);

            return (
              <Card key={index} className="hover:shadow-lg transition-shadow">
                <CardHeader>
                  <CardTitle className="flex items-start justify-between">
                    <span className="line-clamp-2">{project.name}</span>
                    {project.status === 'active' && (
                      <span className="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2 flex-shrink-0">
                        Active
                      </span>
                    )}
                  </CardTitle>
                </CardHeader>
                <CardContent>
                  <div className="space-y-4">
                    <p className="text-gray-700 line-clamp-3">
                      {project.description}
                    </p>

                    <div className="pt-2">
                      <a
                        href={project.link}
                        target="_blank"
                        rel="noopener noreferrer"
                      >
                        <Button size="sm" className="w-full">
                          <IconComponent size={16} className="mr-2" />
                          {getLinkText(project.linkType)}
                          <ExternalLink size={14} className="ml-1" />
                        </Button>
                      </a>
                    </div>
                  </div>
                </CardContent>
              </Card>
            );
          })}
        </div>

        <div className="mt-12 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg p-8 text-center">
          <h3 className="text-2xl font-bold mb-4">Want to Contribute?</h3>
          <p className="text-lg mb-6">
            Join our community and help build amazing projects that benefit the
            entire tech ecosystem in Greenville!
          </p>
          <div className="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
            <a
              href="https://github.com/hackgvl"
              target="_blank"
              rel="noopener noreferrer"
              className="inline-block bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
            >
              <Github size={20} className="inline mr-2" />
              Visit Our GitHub
            </a>
            <a
              href="/join-slack"
              className="inline-block border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors"
            >
              Join Our Slack
            </a>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
