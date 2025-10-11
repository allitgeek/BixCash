/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./resources/**/*.css",
    "./storage/framework/views/*.php",
    "./app/View/Components/**/*.php",
    "./app/Livewire/**/*.php"
  ],
  theme: {
    extend: {
      colors: {
        'bix-dark-blue': '#021c47',
        'bix-navy': '#021c47',
        'bix-green': '#76d37a',
        'bix-light-green': '#93db4d',
        'bix-white': '#ffffff',
        'bix-light-gray-1': '#f8f8f8',
        'bix-light-gray-2': '#eef2f5',
        'bix-medium-gray': '#707070',
        'bix-black': '#000000',
      },
      fontFamily: {
        'inter': ['Inter', 'Segoe UI', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif'],
      }
    },
  },
  plugins: [],
  // Preserve all CSS variables and custom classes
  safelist: [
    {
      pattern: /^(main-header|hero-slider|brands-section|customer-dashboard|promotions|contact|footer|auth-buttons|btn-signin|btn-signup)/,
      variants: ['hover', 'focus', 'active', 'before', 'after', 'sm', 'md', 'lg', 'xl']
    },
    // CSS variables
    {
      pattern: /--bix-.*/
    },
    // Auth button classes
    'auth-buttons',
    'btn-signin',
    'btn-signup'
  ]
}