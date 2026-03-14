import * as Turbo from '@hotwired/turbo';

// Lazy-load FullCalendar + CSS only when needed (calendar page)
window.loadCalendarLibs = () => import('./calendar-libs.js');

// Lazy-load Alpine.js only when page has Alpine components
function maybeLoadAlpine() {
  if (document.querySelector('[x-data]') && !window.Alpine) {
    import('alpinejs').then(({ default: Alpine }) => {
      window.Alpine = Alpine;
      Alpine.start();
    });
  }
}

document.addEventListener('turbo:load', maybeLoadAlpine);
maybeLoadAlpine();
