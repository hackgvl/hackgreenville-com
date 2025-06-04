import { route as ziggyRoute } from 'ziggy-js';
import { Ziggy } from '../ziggy.js';

// Type-safe route helper
export function route(name: string, params?: any, absolute?: boolean): string {
  if (typeof window !== 'undefined' && window.route) {
    return window.route(name, params, absolute);
  }

  // Use Ziggy data directly as fallback
  if (Ziggy.routes[name]) {
    let uri = Ziggy.routes[name].uri;

    // Simple parameter replacement
    if (params && typeof params === 'object') {
      for (const [key, value] of Object.entries(params)) {
        uri = uri.replace(`{${key}}`, String(value));
      }
    }

    // Remove optional parameters that weren't provided
    uri = uri.replace(/\{[^}]+\?\}/g, '');

    const baseUrl = absolute ? Ziggy.url : '';
    return baseUrl + (uri.startsWith('/') ? uri : '/' + uri);
  }

  // Ultimate fallback
  return '/';
}

// Declare global route function
declare global {
  interface Window {
    route: typeof ziggyRoute;
  }
}
