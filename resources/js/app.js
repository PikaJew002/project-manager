import "./bootstrap.js";
import "../css/app.css";
import { createApp, h } from "vue";
import { ZiggyVue } from "ziggy-js";
import { createInertiaApp, router } from '@inertiajs/vue3';
import { createPinia } from "pinia";

let pinia = createPinia();

router.on('before', (event) => {
  event.detail.visit.headers = {
    ...event.detail.visit.headers,
    'X-Timezone': Intl.DateTimeFormat().resolvedOptions().timeZone,
  };
});

createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.glob("./pages/**/*.vue", { eager: true });
    return pages[`./pages/${name}.vue`];
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(pinia)
      .mount(el);
  },
});
