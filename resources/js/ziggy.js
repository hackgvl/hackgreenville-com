const Ziggy = {
  url: 'https://hackgreenville-com.test',
  port: null,
  defaults: {},
  routes: {
    'debugbar.openhandler': { uri: '_debugbar/open', methods: ['GET', 'HEAD'] },
    'debugbar.clockwork': {
      uri: '_debugbar/clockwork/{id}',
      methods: ['GET', 'HEAD'],
      parameters: ['id'],
    },
    'debugbar.telescope': {
      uri: '_debugbar/telescope/{id}',
      methods: ['GET', 'HEAD'],
      parameters: ['id'],
    },
    'debugbar.assets.css': {
      uri: '_debugbar/assets/stylesheets',
      methods: ['GET', 'HEAD'],
    },
    'debugbar.assets.js': {
      uri: '_debugbar/assets/javascript',
      methods: ['GET', 'HEAD'],
    },
    'debugbar.cache.delete': {
      uri: '_debugbar/cache/{key}/{tags?}',
      methods: ['DELETE'],
      parameters: ['key', 'tags'],
    },
    'debugbar.queries.explain': {
      uri: '_debugbar/queries/explain',
      methods: ['POST'],
    },
    'filament.exports.download': {
      uri: 'filament/exports/{export}/download',
      methods: ['GET', 'HEAD'],
      parameters: ['export'],
      bindings: { export: 'id' },
    },
    'filament.imports.failed-rows.download': {
      uri: 'filament/imports/{import}/failed-rows/download',
      methods: ['GET', 'HEAD'],
      parameters: ['import'],
      bindings: { import: 'id' },
    },
    'filament.admin.auth.login': {
      uri: 'admin/login',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.auth.password-reset.request': {
      uri: 'admin/password-reset/request',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.auth.password-reset.reset': {
      uri: 'admin/password-reset/reset',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.auth.logout': { uri: 'admin/logout', methods: ['POST'] },
    'filament.admin.pages.dashboard': {
      uri: 'admin',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.resources.categories.index': {
      uri: 'admin/categories',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.resources.categories.create': {
      uri: 'admin/categories/create',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.resources.categories.edit': {
      uri: 'admin/categories/{record}/edit',
      methods: ['GET', 'HEAD'],
      parameters: ['record'],
    },
    'filament.admin.resources.events.index': {
      uri: 'admin/events',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.resources.events.create': {
      uri: 'admin/events/create',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.resources.events.edit': {
      uri: 'admin/events/{record}/edit',
      methods: ['GET', 'HEAD'],
      parameters: ['record'],
    },
    'filament.admin.resources.orgs.index': {
      uri: 'admin/orgs',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.resources.orgs.create': {
      uri: 'admin/orgs/create',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.resources.orgs.edit': {
      uri: 'admin/orgs/{record}/edit',
      methods: ['GET', 'HEAD'],
      parameters: ['record'],
    },
    'filament.admin.resources.states.index': {
      uri: 'admin/states',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.resources.states.create': {
      uri: 'admin/states/create',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.resources.states.edit': {
      uri: 'admin/states/{record}/edit',
      methods: ['GET', 'HEAD'],
      parameters: ['record'],
    },
    'filament.admin.resources.tags.index': {
      uri: 'admin/tags',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.resources.tags.create': {
      uri: 'admin/tags/create',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.resources.tags.edit': {
      uri: 'admin/tags/{record}/edit',
      methods: ['GET', 'HEAD'],
      parameters: ['record'],
    },
    'filament.admin.resources.venues.index': {
      uri: 'admin/venues',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.resources.venues.create': {
      uri: 'admin/venues/create',
      methods: ['GET', 'HEAD'],
    },
    'filament.admin.resources.venues.edit': {
      uri: 'admin/venues/{record}/edit',
      methods: ['GET', 'HEAD'],
      parameters: ['record'],
    },
    'api.v0.events.index': { uri: 'api/v0/events', methods: ['GET', 'HEAD'] },
    'api.v0.orgs.index': { uri: 'api/v0/orgs', methods: ['GET', 'HEAD'] },
    'livewire.update': { uri: 'livewire/update', methods: ['POST'] },
    'livewire.upload-file': { uri: 'livewire/upload-file', methods: ['POST'] },
    'livewire.preview-file': {
      uri: 'livewire/preview-file/{filename}',
      methods: ['GET', 'HEAD'],
      parameters: ['filename'],
    },
    'ignition.healthCheck': {
      uri: '_ignition/health-check',
      methods: ['GET', 'HEAD'],
    },
    'ignition.executeSolution': {
      uri: '_ignition/execute-solution',
      methods: ['POST'],
    },
    'ignition.updateConfig': {
      uri: '_ignition/update-config',
      methods: ['POST'],
    },
    home: { uri: '/', methods: ['GET', 'HEAD'] },
    'calendar.index': { uri: 'calendar', methods: ['GET', 'HEAD'] },
    'calendar.data': { uri: 'calendar/data', methods: ['GET', 'HEAD'] },
    'calendar-feed.index': { uri: 'calendar-feed', methods: ['GET', 'HEAD'] },
    'calendar-feed.show': {
      uri: 'calendar-feed.ics',
      methods: ['GET', 'HEAD'],
    },
    'events.index': { uri: 'events', methods: ['GET', 'HEAD'] },
    'labs.index': { uri: 'labs', methods: ['GET', 'HEAD'] },
    'hg-nights.index': { uri: 'hg-nights', methods: ['GET', 'HEAD'] },
    give: { uri: 'give', methods: ['GET', 'HEAD'] },
    about: { uri: 'about', methods: ['GET', 'HEAD'] },
    'code-of-conduct': { uri: 'code-of-conduct', methods: ['GET', 'HEAD'] },
    'orgs.index': { uri: 'orgs', methods: ['GET', 'HEAD'] },
    'orgs.show': {
      uri: 'orgs/{org}',
      methods: ['GET', 'HEAD'],
      parameters: ['org'],
      bindings: { org: 'slug' },
    },
    'orgs.inactive': { uri: 'orgs/inactive', methods: ['GET', 'HEAD'] },
    contact: { uri: 'contact', methods: ['GET', 'HEAD'] },
    'contact.submit': { uri: 'contact', methods: ['POST'] },
    'join-slack': { uri: 'join-slack', methods: ['GET', 'HEAD'] },
    'join-slack.submit': { uri: 'join-slack', methods: ['POST'] },
    'styles.index': { uri: 'styles', methods: ['GET', 'HEAD'] },
  },
};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
