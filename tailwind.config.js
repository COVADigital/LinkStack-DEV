/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php', // Include all Blade templates in resources/views
    './resources/js/**/*.js',          // Include all JavaScript files in resources/js
    './resources/views/vendor/**/*.blade.php', // Include all vendor Blade templates
  ],
  theme: {
    extend: {
      colors: {
        primary: '#1DB954', // Spotify Green
        dark: '#121212', // Spotify Dark
        secondary: '#282828', // Spotify Gray
        gray: {
          100: '#2E2E30', // Slightly lighter gray
        },
        border: '#4A4A4D', // Neutral Border
        navy: '#1A1B25', // Dark Navy
        pink: '#FF5D8F', // Bright Pink
        success: '#29CC97', // Vibrant Green
        info: '#4A90E2', // Soft Blue
        warning: '#FFC107', // Warm Yellow
        danger: '#F94144', // Bold Red
        inverse: '#FFFFFF', // Soft White for Contrast
        purple: '#9D4EDD', // Rich Purple
        white: '#FFFFFF', // Pure White
        'logo-blue': '#1DB954', // Spotify Green for Logo
      },
      borderRadius: {
        DEFAULT: '0.25rem',
      },
      fontFamily: {
        sans: ['Arial', 'sans-serif'], // Adjusted font for consistency
        icon: ['Material Design Icons', 'sans-serif'],
      },
      fontSize: {
        base: '.875rem',
        h1: '2.5rem',
        h2: '2rem',
        h3: '1.5rem',
        h4: '1.25rem',
        h5: '1.125rem',
        h6: '1rem',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'), // For Tailwind CSS forms plugin
  ],
};
