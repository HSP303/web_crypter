// tailwind.config.js
const plugin = require('tailwindcss/plugin');

module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  plugins: [
    require('tailwindcss-dark-mode')()
  ],
  theme: {
    darkSelector: '.dark-mode', // classe que ativará o modo escuro
    extend: {
      // Adicione variações escuras para cores personalizadas se necessário
      backgroundColor: {
        'dark': '#1a202c',
      },
      textColor: {
        'dark': '#e2e8f0',
      }
    }
  },
  variants: {
    backgroundColor: ['dark', 'dark-hover', 'dark-group-hover'],
    textColor: ['dark', 'dark-hover', 'dark-group-hover'],
    borderColor: ['dark', 'dark-focus', 'dark-focus-within'],
  },
}