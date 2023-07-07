import { createApp, h } from "vue";
import { createInertiaApp, Head, Link } from '@inertiajs/inertia-vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../../vendor/tightenco/ziggy/dist/vue.m';
import * as Sentry from "@sentry/vue";
import { InertiaProgress } from "@inertiajs/progress";
import NProgress from 'nprogress'
import { dateTimeHumanize, trans, convertJsonToFormData, loadTypeform } from "@/helpers";

import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import DropZone from "dropzone-vue";

import "dropzone-vue/dist/dropzone-vue.common.css";

import CKEditor from "@ckeditor/ckeditor5-vue";

// import AppNotifications from "@/Common/Notifications.vue";
import Notifications from '@kyvg/vue3-notification'

//import { VueReCaptcha } from 'vue-recaptcha-v3'

import { createPinia } from 'pinia'
import { PiniaSharedState } from 'pinia-shared-state';
const pinia = createPinia()

import { useAppStore } from '@/store/index'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

export default function xmeCreateInertiaApp() {
    createInertiaApp({
        title: (title) => `${title} - ${appName}`,
        resolve: async (name) => {
            const page = await resolvePageComponent(`../Pages/${name}.vue`, import.meta.glob('../Pages/**/*.vue'))
            if (page.default.layout === undefined) {
                // page.default.layout = AuthenticatedLayout
            }
            return page
        },
        async setup({ el, app, props, plugin }) {
            const { user } = props.initialPage.props;
       
            const application = createApp({
                render: () => h(app, props),
                mounted() {
                    useAppStoreObj.subscribeAppVersionReleaseEvent()
                },
                async created() {
                },
            });

            application
                .mixin(Sentry.createTracingMixins({ trackComponents: true }))
                .use(plugin)
                .component('InertiaHead', Head)
                .component('InertiaLink', Link)
                .use(ZiggyVue, Ziggy)
                .use(CKEditor)
                .use(VueDatePicker)
                .use(DropZone)
                .mixin({ methods: { route, dateTimeHumanize, convertJsonToFormData, loadTypeform } })
                .use(Notifications);

            if (import.meta.env.VITE_APP_ENV != 'local') {
                Sentry.attachErrorHandler(application, { logErrors: true })
            }

            // application.use(store);
            pinia.use(
                PiniaSharedState({
                    // Enables the plugin for all stores. Defaults to true.
                    enable: true,
                    // If set to true this tab tries to immediately recover the shared state from another tab. Defaults to true.
                    initialize: false,
                    // Enforce a type. One of native, idb, localstorage or node. Defaults to native.
                    type: 'localstorage',
                }),
            );
            application.use(pinia);
            const useAppStoreObj = useAppStore()
            await useAppStoreObj.initialData();

            if (user) {
                Sentry.setUser({
                    id: user.id,
                    username: user.name,
                    email: user.email,
                });
                useAppStoreObj.getFeatureAvailability()
            } else {
                Sentry.setUser(null);
            }


            // application.use(VueReCaptcha, {
            //     siteKey: import.meta.env.VITE_RECAPTCHA_SITE_KEY,
            //     loaderOptions: {
            //         autoHideBadge: true,
            //     },
            // });
            application.config.globalProperties.mediaPath =
            import.meta.env.VITE_AWS_S3;
            application.config.globalProperties.trans = trans;
            // Register components globally
            // application.component('Dialog', Dialog);
            // application.component('DialogOverlay', DialogOverlay);
            // application.component('DialogTitle', DialogTitle);
            // application.component('TransitionChild', TransitionChild);
            // application.component('TransitionRoot', TransitionRoot);
            // application.component('WarningMessage', WarningMessage);
            // application.component('ModalMessage', ModalMessage);
            // application.directive('tooltip', tooltip);
            // application.component('UpgradeWarningMessage', UpgradeWarningMessage);
            // application.directive('click-outside', clickOutside);

            //Sentry configuration
            if (import.meta.env.VITE_APP_ENV != 'local') {
                Sentry.init({
                    app: application,
                    dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
                    // Set tracesSampleRate to 1.0 to capture 100%
                    // of transactions for performance monitoring.
                    // We recommend adjusting this value in production
                    tracesSampleRate: import.meta.env.VITE_SENTRY_TRACES_SAMPLE_RATE,
                    environment: import.meta.env.VITE_APP_ENV,
                    trackComponents: true,
                    logErrors: true,
                });
            }
            return application.mount(el);
        },
    });
}
InertiaProgress.init({ color: '#4B5563' });

window.axios.defaults.transformRequest.push(function (data, headers) {
    NProgress.start();
    return data;
})

window.axios.defaults.transformResponse.push(function (data, headers) {
    NProgress.done();
    return data;
})