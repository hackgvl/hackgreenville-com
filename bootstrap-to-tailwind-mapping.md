# Bootstrap to Tailwind CSS Mapping for Navigation

## Bootstrap Classes Found in Navigation

### Layout & Container Classes

#### `container-fluid`
**CSS Properties:**
```css
width: 100%;
padding-right: 15px;
padding-left: 15px;
margin-right: auto;
margin-left: auto;
```
**Tailwind Equivalent:** `w-full px-4 mx-auto`

### Navbar Core Classes

#### `navbar` (Note: using `tw-navbar` in code)
**CSS Properties:**
```css
position: relative;
display: flex;
flex-wrap: wrap;
align-items: center;
justify-content: space-between;
padding: 0.5rem 1rem;
```
**Tailwind Equivalent:** `relative flex flex-wrap items-center justify-between py-2 px-4`

#### `navbar-expand-md` (Note: using `tw-navbar-expand-md`)
**CSS Properties:**
```css
/* Mobile first - collapsed by default */
flex-flow: row nowrap;
justify-content: flex-start;

/* At md breakpoint and up */
@media (min-width: 768px) {
  flex-flow: row nowrap;
  justify-content: flex-start;
}
```
**Tailwind Equivalent:** `flex-row flex-nowrap justify-start`

#### `navbar-dark` (Note: using `tw-navbar-dark`)
**CSS Properties:**
```css
/* Affects child elements */
.navbar-brand { color: rgba(255, 255, 255, 0.9); }
.navbar-nav .nav-link { color: rgba(255, 255, 255, 0.5); }
.navbar-nav .nav-link:hover { color: rgba(255, 255, 255, 0.75); }
.navbar-nav .nav-link.active { color: rgba(255, 255, 255, 0.9); }
.navbar-toggler { color: rgba(255, 255, 255, 0.5); border-color: rgba(255, 255, 255, 0.1); }
.navbar-toggler-icon { background-image: /* white hamburger icon SVG */; }
```
**Tailwind Equivalent:** Custom classes needed for child elements

#### `bg-primary` (Note: using `tw-bg-primary`)
**CSS Properties:**
```css
background-color: #007bff; /* Bootstrap default primary color */
```
**Tailwind Equivalent:** `bg-blue-600` or custom color

### Navbar Components

#### `navbar-brand`
**CSS Properties:**
```css
display: inline-block;
padding-top: 0.3125rem;
padding-bottom: 0.3125rem;
margin-right: 1rem;
font-size: 1.25rem;
line-height: inherit;
white-space: nowrap;
```
**Tailwind Equivalent:** `inline-block py-1.5 mr-4 text-xl leading-inherit whitespace-nowrap`

#### `navbar-toggler`
**CSS Properties:**
```css
padding: 0.25rem 0.75rem;
font-size: 1.25rem;
line-height: 1;
background-color: transparent;
border: 1px solid transparent;
border-radius: 0.25rem;
/* Hidden on desktop */
@media (min-width: 768px) {
  display: none;
}
```
**Tailwind Equivalent:** `py-1 px-3 text-xl leading-none bg-transparent border border-transparent rounded md:hidden`

#### `navbar-toggler-icon`
**CSS Properties:**
```css
display: inline-block;
width: 1.5em;
height: 1.5em;
vertical-align: middle;
content: "";
background: center center no-repeat;
background-size: 100% 100%;
```
**Tailwind Equivalent:** `inline-block w-6 h-6 align-middle bg-center bg-no-repeat bg-cover`

#### `navbar-collapse`
**CSS Properties:**
```css
/* Mobile - collapsed */
display: none;
width: 100%;
flex-basis: 100%;
flex-grow: 1;
align-items: center;

/* When expanded */
.show {
  display: flex;
}

/* Desktop */
@media (min-width: 768px) {
  display: flex !important;
  flex-basis: auto;
}
```
**Tailwind Equivalent:** `hidden w-full basis-full grow items-center md:flex md:basis-auto`

#### `navbar-nav`
**CSS Properties:**
```css
display: flex;
flex-direction: column;
padding-left: 0;
margin-bottom: 0;
list-style: none;

/* Desktop */
@media (min-width: 768px) {
  flex-direction: row;
}
```
**Tailwind Equivalent:** `flex flex-col pl-0 mb-0 list-none md:flex-row`

### Navigation Item Classes

#### `nav-item`
**CSS Properties:**
```css
/* No specific styles in Bootstrap 4, just semantic */
```
**Tailwind Equivalent:** No specific classes needed

#### `nav-link`
**CSS Properties:**
```css
display: block;
padding: 0.5rem 1rem;
color: #007bff; /* or inherited from navbar-dark */
text-decoration: none;
background-color: transparent;
```
**Tailwind Equivalent:** `block py-2 px-4 text-blue-600 no-underline bg-transparent`

#### `nav-link.active`
**CSS Properties:**
```css
/* Depends on parent context (navbar-dark, etc.) */
color: rgba(255, 255, 255, 0.9); /* in navbar-dark */
```
**Tailwind Equivalent:** `text-white/90` (in dark navbar context)

### Spacing Utilities

#### `mr-auto`
**CSS Properties:**
```css
margin-right: auto !important;
```
**Tailwind Equivalent:** `mr-auto`

#### `ml-auto`
**CSS Properties:**
```css
margin-left: auto !important;
```
**Tailwind Equivalent:** `ml-auto`

#### `ml-2`
**CSS Properties:**
```css
margin-left: 0.5rem !important;
```
**Tailwind Equivalent:** `ml-2`

### Button Classes

#### `btn`
**CSS Properties:**
```css
display: inline-block;
font-weight: 400;
text-align: center;
vertical-align: middle;
user-select: none;
border: 1px solid transparent;
padding: 0.375rem 0.75rem;
font-size: 1rem;
line-height: 1.5;
border-radius: 0.25rem;
transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, 
            border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
```
**Tailwind Equivalent:** `inline-block font-normal text-center align-middle select-none border border-transparent py-1.5 px-3 text-base leading-normal rounded transition-all duration-150`

#### `btn-outline-secondary`
**CSS Properties:**
```css
color: #6c757d;
background-color: transparent;
border-color: #6c757d;

/* Hover */
:hover {
  color: #fff;
  background-color: #6c757d;
  border-color: #6c757d;
}
```
**Tailwind Equivalent:** `text-gray-600 bg-transparent border-gray-600 hover:text-white hover:bg-gray-600 hover:border-gray-600`

#### `btn-outline-success`
**CSS Properties:**
```css
color: #28a745;
background-color: transparent;
border-color: #28a745;

/* Hover */
:hover {
  color: #fff;
  background-color: #28a745;
  border-color: #28a745;
}
```
**Tailwind Equivalent:** `text-green-600 bg-transparent border-green-600 hover:text-white hover:bg-green-600 hover:border-green-600`

#### `btn.active` (when combined with btn-outline-success)
**CSS Properties:**
```css
color: #fff;
background-color: #28a745;
border-color: #28a745;
```
**Tailwind Equivalent:** `text-white bg-green-600 border-green-600`

### Flexbox Utilities

#### `justify-content-end`
**CSS Properties:**
```css
justify-content: flex-end !important;
```
**Tailwind Equivalent:** `justify-end`

### Display Utilities

#### `d-md-none`
**CSS Properties:**
```css
/* Hidden at md breakpoint and up */
@media (min-width: 768px) {
  display: none !important;
}
```
**Tailwind Equivalent:** `md:hidden`

#### `d-lg-inline-block`
**CSS Properties:**
```css
/* Display inline-block at lg breakpoint and up */
@media (min-width: 992px) {
  display: inline-block !important;
}
```
**Tailwind Equivalent:** `lg:inline-block`

### Collapse/Toggler Behavior

#### `collapse`
**CSS Properties:**
```css
display: none;
/* When .show is added */
.collapse.show {
  display: block;
}
```
**Tailwind Equivalent:** `hidden` (toggle with JavaScript to add/remove class)

## JavaScript Behavior Notes

The Bootstrap navigation relies on JavaScript for:
1. **Toggler button**: Clicking toggles the `show` class on the `navbar-collapse` div
2. **Data attributes**: `data-toggle="collapse"` and `data-target="#navbarSupportedContent"`

For Tailwind implementation, you'll need to:
1. Replace Bootstrap's JavaScript with custom JavaScript or Alpine.js
2. Toggle visibility classes (e.g., `hidden` vs `block` or `flex`)
3. Handle responsive behavior programmatically

## Inline Styles Found

#### `style="color: #202020;"`
**CSS Properties:**
```css
color: #202020; /* Very dark gray, almost black */
```
**Tailwind Equivalent:** `text-gray-900` or `text-[#202020]` for exact match

## Summary of Key Conversions

1. **Container**: `container-fluid` → `w-full px-4 mx-auto`
2. **Navbar base**: Multiple Bootstrap classes → `relative flex flex-wrap items-center justify-between py-2 px-4`
3. **Responsive nav**: `navbar-collapse` → `hidden w-full md:flex md:w-auto`
4. **Nav list**: `navbar-nav` → `flex flex-col md:flex-row list-none`
5. **Buttons**: `btn btn-outline-*` → Combination of Tailwind utilities with hover states
6. **Responsive display**: `d-md-none d-lg-inline-block` → `md:hidden lg:inline-block`