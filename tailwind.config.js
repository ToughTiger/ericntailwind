import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
const typography = require('@tailwindcss/typography');
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
       
    ],
    options: {
        safelist: ['sm:flex-col-reverse'], // Safelist the class
      },

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
        screens: {
            sm: '640px', // Ensure the 'sm' breakpoint is defined
            md: '768px',
            lg: '1024px',
            xl: '1280px',
          },
    },

    plugins: [forms,typography],
};
