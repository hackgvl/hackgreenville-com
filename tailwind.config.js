/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#201748',
        'info': '#292634',
        'success': '#60ae6d',
        'warning': '#de9d35',
        'danger': '#f44336',
      },
      fontFamily: {
        'sans': ['Geist Variable', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif'],
      },
      screens: {
        'nav-break': '1220px',
      },
    },
  },
  plugins: [],
}