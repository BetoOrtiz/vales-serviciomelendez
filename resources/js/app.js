require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import FontAwesomeIcon from "@/Components/fontawesome-icons";
import Notifications from 'notiwind';
import { VOffline } from 'v-offline';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Vales Servcio Melendez';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    progress: {
        color: '#4B5563',
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
                .component("font-awesome-icon", FontAwesomeIcon)
                .use(plugin)
            .use(Notifications)
            .mixin({ methods: { route } })
            .mount(el);
    },
});
