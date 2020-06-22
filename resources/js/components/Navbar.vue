<template>
    <div>
        <div class="navbar">
            <div class="navbar-inner">
                <div
                    v-if="!loggedIn"
                    class="button-register"
                    @click="router.replace('/user-registration')">
                    Regristreer
                </div>
                <div v-if="loggedIn" class="navbar-user">
                    Welkom
                    <span class="navbar-user-name">{{ $user.data.name }}</span>!
                </div>
                <img class="navbar-logo" src="/img/nz-logo-light.png">
                <button class="navbar-hamburger" @click="sidebarIsOpen = true">
                    <div>
                        <div />
                    </div>
                </button>
            </div>
        </div>
        <div class="sidenav" :class="{ 'sidenav-hidden': !sidebarIsOpen }">
            <button
                class="sidenav-close modal-close"
                @click="sidebarIsOpen = false"
            >
                <div>
                    <div />
                </div>
            </button>
            <div class="sidenav-links">
                <router-link to="/" exact>
                    Home
                </router-link>
                <router-link v-if="loggedIn" to="/Profile">
                    Profiel
                </router-link>
                <div class="sidenav-link-divider" />
                <button v-if="!loggedIn" @click="openLogin">
                    Inloggen
                </button>
                <router-link v-if="!loggedIn" to="/user-registration">
                    Registreren
                </router-link>
                <button v-else @click="logout">
                    Uitloggen
                </button>
                <!--                <router-link v-if="loggedIn" to="/search">-->
                <!--                    Profiel zoeken-->
                <!--                </router-link>-->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['loggedIn', 'userData'],
    watch: {
        $route (to, from) {
            this.sidebarIsOpen = false;
        }
    },
    methods: {
        openLogin () {
            this.sidebarIsOpen = false;
            this.$emit('openLogin');
        },
        logout () {
            this.sidebarIsOpen = false;
            this.$emit('logout');
        }
    },
    data () {
        return {
            router: this.$router,
            sidebarIsOpen: false
        };
    }
};
</script>

<style>
    .button-register {
        padding: 12px 22px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        background-color: #1F1F1F;
        color: #C1C1C1;
        border: solid 2px #767676;
        align-items: center;
        display: flex;
        justify-content: center;
    }
</style>
