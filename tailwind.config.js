/** @type {import('tailwindcss').Config} */
const theme = require('./theme-flowbite');

module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.twig",
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
    extend: {
      fill: theme => ({
        'red-500': theme('colors.red.500'),
        'green-500': theme('colors.green.500'),
      }),
    },
  },
  variants: {
    extend: {
      fill: ['hover', 'focus'],
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

