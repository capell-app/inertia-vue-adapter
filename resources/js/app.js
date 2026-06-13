import { createInertiaApp } from '@inertiajs/vue3'
import { createApp, h } from 'vue'
import Page from './Pages/Capell/Page.vue'
import BookingRequest from './Pages/Capell/Bookings/Request.vue'

const pages = {
    'Capell/Page': Page,
    'Capell/Bookings/Request': BookingRequest,
}

createInertiaApp({
    resolve: (name) => pages[name] ?? Page,
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
})
