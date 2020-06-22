import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '@/js/views/Home';
import Logout from '@/js/views/Logout';
import Event from '@/js/views/Event';
import Search from '@/js/views/Search';
import Profile from '@/js/views/Profile';
import EventForm from '@/js/views/forms/Event-form';
import ResetPasswordForm from '@/js/views/forms/ResetPasswordForm';
import EventSettingsForm from '@/js/views/forms/Event-Settings-form';
import MapForm from '@/js/views/forms/Map-form';
import ProfileForm from '@/js/views/forms/Profile-form';
import CVForm from '@/js/views/forms/CV-form';
import datepickerExample from '@/js/views/DateTimeExample';
import AdminIndex from '@/js/views/Admin/Admin';
import UserRegistrationForm from '@/js/views/forms/User-Registration-form';
import NotFound from '@/js/views/NotFound';
import Map from '@/js/views/Map';
import QRCode from './views/QRCode';
import printBadge from './views/PrintBadge';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },{
            path: '/logout',
            name: 'logout',
            component: Logout
        },
        {
            path: '/event/:id',
            name: 'event',
            component: Event
        },
        {
            path: '/profile',
            name: 'profile',
            component: Profile
        },
        {
            path: '/profile/:id',
            name: 'profile',
            component: Profile
        },
        // {
        //     path: '/search',
        //     name: 'search',
        //     component: Search
        // },
        {
            path: '/form/event',
            name: 'event-form',
            component: EventForm
        },
        {
            path: '/form/map/:event_id',
            name: 'map-form',
            component: MapForm
        },
        {
            path: '/form/profile',
            name: 'profile-form',
            component: ProfileForm
        },
        {
            path: '/form/profile-cv',
            name: 'profile-cv-form',
            component: CVForm
        },
        {
            path: '/form/event-settings/:event_id',
            name: 'event-settings-form',
            component: EventSettingsForm,
            meta: {
                auth: true
            }
        },
        {
            path: '/reset-password/:token',
            name: 'reset-password-form',
            component: ResetPasswordForm
        },
        {
            path: '/user-registration',
            name: 'user-registration-form',
            component: UserRegistrationForm
        },
        {
            path: '/admin',
            name: 'admin',
            component: AdminIndex
        },
        {
            path: '/datepicker',
            name: 'datepicker',
            component: datepickerExample
        },
        {
            path: '/event/map/:event_id',
            name: 'Map',
            component: Map
        },
        {
            path: '/profile/qr-code/:id',
            name: 'QRCode',
            component: QRCode
        },
        {
            path: '/print/badge/:id',
            name: 'printBadge',
            component: printBadge
        },
        {
            path: '*',
            name: 'Pagina niet gevonden',
            component: NotFound
        }
    ]
});

export default router;
