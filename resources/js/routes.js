import {createWebHistory, createRouter} from 'vue-router'
// import Home from './../../resources/js/Pages/Welcome.vue'
// import EmailPage from './Pages/Email/Email.vue'

const routes = [
    // {
    //     name: 'Home',
    //     path: '/',
    //     component: EmailPage
    // },
    // {
    //     name: 'Login',
    //     path: '/email',
    //     component: EmailPage
    // },
    // {
    //     name: 'NotFound',
    //     path: '/:pathMatch(.*)*',
    //     component: Error,
    //     meta: {
    //         hideNavbar: true,
    //        }
    // },
];

const router = createRouter({
    history: createWebHistory(),routes
})

export default router;
