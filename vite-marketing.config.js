import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';

export default defineConfig({
  css: {
    postcss: {
      plugins: [tailwindcss('./tailwind-marketing.config.js'), autoprefixer()],
    },
  },
  plugins: [
    laravel({
      input: 'resources/js/marketing.js',
      buildDirectory: 'build-marketing',
      refresh: true,
    }),
  ],
});
