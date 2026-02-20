# Bootstrap to Tailwind CSS Conversion Plan

## Overview
This document tracks the progress of converting the HackGreenville website from Bootstrap to Tailwind CSS while preserving the existing appearance and functionality.

## Conversion Status

### Phase 1: Core Layout Components (High Priority)

#### ✅ Homepage (`resources/views/index.blade.php`)
- **Status**: COMPLETED
- Converted all Bootstrap classes to Tailwind
- Preserved timeline component styles
- Maintained responsive grid layout
- Created backup: `index-bootstrap-backup.blade.php`

#### ✅ Navigation Bar (`layouts/top-nav.blade.php`)
- **Status**: COMPLETED
- Converted Bootstrap navbar to Tailwind with hybrid approach
- Added JavaScript toggle for mobile menu
- Updated responsive utilities
- Created backup: `top-nav-bootstrap-backup.blade.php`

#### ✅ Footer (`layouts/footer.blade.php`)
- **Status**: COMPLETED
- Converted Bootstrap grid to Tailwind flexbox
- Updated spacing and color utilities
- Maintained link structure
- Created backup: `footer-bootstrap-backup.blade.php`

#### ✅ Main Layout (`layouts/app.blade.php`)
- **Status**: COMPLETED
- Updated breadcrumb styling
- Converted loading spinner to Tailwind
- Updated utility classes (d-none → hidden, etc.)

### Phase 2: Reusable Components

#### ✅ Nav Link Component (`components/nav-link.blade.php`)
- **Status**: COMPLETED
- Converted Bootstrap nav classes to Tailwind
- Maintained active state styling

#### ✅ Calendar Feed Promo (`components/calendar-feed-promo.blade.php`)
- **Status**: COMPLETED
- Converted Bootstrap grid to Tailwind flexbox
- Updated button and spacing utilities
- Preserved gradient background styling

### Phase 3: Content Pages

#### ⏳ Events Pages
- **Status**: PENDING
- `events/index.blade.php` - Convert form controls and containers
- `events/_item.blade.php` - Update event item styling

#### ⏳ Organizations Pages
- **Status**: PENDING
- `orgs/index.blade.php` - Replace card-columns with Tailwind grid
- `orgs/show.blade.php` - Update detail page styling
- `orgs/inactive.blade.php` - Update inactive orgs page

#### ⏳ Contact Form (`contact/contact.blade.php`)
- **Status**: PENDING
- Work with Aire forms (Bootstrap-styled form builder)
- Create custom Tailwind styling for Aire components

#### ⏳ Slack Sign-up (`slack/sign-up.blade.php`)
- **Status**: PENDING
- Convert badge and button classes
- Update form layout with Tailwind grid

#### ⏳ Other Pages
- **Status**: IN PROGRESS
- ✅ `about.blade.php` - About Us page - COMPLETED
  - Added custom styles for title-heading, lead-text, highlight-link, etc.
  - Converted to use Tailwind container and spacing utilities
- `calendar/index.blade.php` - Calendar page - PENDING
- `labs/index.blade.php` - Labs page - PENDING
- `hg-nights/index.blade.php` - HG Nights page - PENDING
- `contribute.blade.php` - Contribute page - PENDING
- `code-of-conduct.blade.php` - Code of Conduct page - PENDING
- `styleguide/index.blade.php` - Style guide page - PENDING

### Phase 4: Form Handling
- **Status**: PENDING
- Research Tailwind-compatible Aire package options
- Create custom Tailwind styles for Aire forms if needed
- Consider manual form conversion as fallback

### Phase 5: Custom Components & Styles

#### ✅ Timeline Component
- **Status**: COMPLETED
- Kept existing timeline CSS
- Only updated surrounding Bootstrap utilities

#### ✅ Tailwind Configuration
- **Status**: COMPLETED
- Added custom colors matching Bootstrap theme
- Created `tailwind.config.js` with theme extensions
- Added `postcss.config.js`

#### ⏳ Additional Custom Styles
- **Status**: PENDING
- Convert remaining SCSS custom styles
- Add any missing component classes

### Phase 6: JavaScript Dependencies
- **Status**: PENDING
- Replace Bootstrap JavaScript (collapse, modal, dropdown)
- Implement Tailwind-compatible alternatives
- Update any Bootstrap-dependent scripts

### Phase 7: Testing & Cleanup
- **Status**: PENDING
- [ ] Remove Bootstrap from package.json
- [ ] Remove Bootstrap imports from SCSS
- [ ] Test all responsive breakpoints
- [ ] Verify interactive components
- [ ] Run full build process
- [ ] Cross-browser testing

## Technical Notes

### Tailwind Setup
- Installed: `@tailwindcss/postcss`, `tailwindcss`, `autoprefixer`
- Created: `tailwind.config.js`, `postcss.config.js`, `resources/css/tailwind.css`
- Updated: `vite.config.js` to include Tailwind CSS

### Hybrid Approach
Currently using a hybrid approach where Bootstrap classes are redefined using Tailwind utilities. This allows for gradual migration without breaking existing functionality.

### Backup Files Created
- `index-bootstrap-backup.blade.php`
- `top-nav-bootstrap-backup.blade.php`
- `footer-bootstrap-backup.blade.php`

## Next Steps
1. ✅ Convert footer layout - COMPLETED
2. ✅ Update main app layout - COMPLETED  
3. ✅ Convert calendar feed promo component - COMPLETED
4. Begin converting content pages starting with simpler ones
5. Handle form styling (Aire forms)
6. Update remaining Bootstrap JavaScript dependencies

## Commands
- Build assets: `npm run build`
- Development: `npm run dev`