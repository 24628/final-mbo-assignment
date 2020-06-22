<template>
    <div class="force-height">
        <NavBar
            :logged-in="loggedIn"
            @logout="logOut()"
            @openLogin="openLogin"
        />
        <Modal
            v-if="modal_open"
            @loggedIn="loggedInHandler"
            @close="closeLogin"
        />
        <div class="main">
            <transition name="fade">
                <router-view @logout="logOut()" />
            </transition>
        </div>
    </div>
</template>

<script>
import NavBar from '@/js/components/Navbar.vue';
import Modal from '@/js/components/modal/Modal.vue';
import API from './Api';

export default {
    data () {
        return {
            loggedIn: false,
            modal_open: false
        };
    },
    methods: {
        openLogin () {
            this.modal_open = true;
        },
        closeLogin () {
            this.modal_open = false;
        },
        logOut () {
            this.loggedIn = false;
            this.$user.data = {};
            API.stopInterval();
            localStorage.removeItem('user');
        },
        loggedInHandler (e) {
            this.loggedIn = true;
            this.$user.data = JSON.parse(e).data;
            localStorage.setItem('user', JSON.stringify(this.$user.data));
        }
    },
    created () {
        if (localStorage.getItem('user') !== null) {
            this.loggedIn = true;
            this.$user.data = JSON.parse(localStorage.getItem('user'));
        }
    },
    components: { Modal, NavBar }
};
</script>

<style lang="scss">

</style>
