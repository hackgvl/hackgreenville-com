import * as React from 'react';
import '../css/app.css';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { route } from 'ziggy-js';
import { Ziggy } from './ziggy.js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Make route function available globally with Ziggy config
window.route = (name: string, params?: any, absolute?: boolean) => {
  return route(name, params, absolute, Ziggy);
};

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./pages/${name}.tsx`,
      import.meta.glob('./pages/**/*.tsx'),
    ),
  setup({ el, App, props }) {
    const root = createRoot(el);
    root.render(<App {...props} />);
  },
  progress: {
    color: '#4B5563',
  },
});
