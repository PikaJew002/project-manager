import "./bootstrap.js";
import "../css/app.css";
import { createApp, h } from "vue";
import { ZiggyVue } from "ziggy-js";
import { createInertiaApp, router } from '@inertiajs/vue3';
import { createPinia } from "pinia";
import * as Sentry from '@sentry/vue';

let pinia = createPinia();

router.on('before', (event) => {
  event.detail.visit.headers = {
    ...event.detail.visit.headers,
    'X-Timezone': Intl.DateTimeFormat().resolvedOptions().timeZone,
  };
});

router.on('navigate', (event) => {
  Sentry.setTag('inertia_page', event.detail.page.component);
});

createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.glob("./pages/**/*.vue", { eager: true });
    return pages[`./pages/${name}.vue`];
  },
  setup({ el, App, props, plugin }) {
    const vueApp = createApp({
      render: () => h(App, props),
    }).use(plugin).use(ZiggyVue).use(pinia);

    Sentry.init({
      app: vueApp,
      dsn: import.meta.env.VITE_SENTRY_DSN,
      integrations: [Sentry.browserTracingIntegration()],
      tracesSampleRate: 0.1,
    });

    vueApp.mount(el);
  },
});
