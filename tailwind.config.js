/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './app/View/Components/**/*.php',
    './app/Http/Livewire/**/*.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('daisyui'),
  ],
  daisyui: {
    themes: [
      {
        light: {
          "primary": "#167bff",
          "secondary": "#6c757d",
          "accent": "#20c997",
          "neutral": "#212529",
          "base-100": "#ffffff",
          "base-200": "#f8f9fa",
          "base-300": "#e9ecef",
          "info": "#0dcaf0",
          "success": "#198754",
          "warning": "#ffc107",
          "error": "#dc3545",
        },
      },
      {
        dark: {
          "primary": "#167bff",
          "secondary": "#6c757d",
          "accent": "#20c997",
          "neutral": "#f8f9fa",
          "base-100": "#212529",
          "base-200": "#343a40",
          "base-300": "#495057",
          "info": "#0dcaf0",
          "success": "#198754",
          "warning": "#ffc107",
          "error": "#dc3545",
        },
      },
    ],
    darkTheme: "dark",
    base: true,
    styled: true,
    utils: true,
    prefix: "",
    logs: true,
    themeRoot: ":root",
  },
}