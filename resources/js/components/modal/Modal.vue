<template>
    <div class="modal_holder">
        <div class="modal_screen column-desktop-5 column-tablet-9 column-mobile-11">
            <button
                v-if="!($route.params.loggedIn !== undefined && !$route.params.loggedIn)"
                class="modal-close"
                @click="close"
            >
                <div>
                    <div />
                </div>
            </button>
            <div>
                <p v-if="($route.params.loggedIn !== undefined && !$route.params.loggedIn)">
                    You need to be logged in to acces this route!
                </p>
                <login
                    v-if="selectroute==='login'"
                    @close="close"
                    @forgotpassword="selectroute='ForgotPassword'"
                    @loggedIn="loggedInHandler"
                    @openAccountVerification="selectroute='Accountverification'"
                />
                <forgot-password
                    v-else-if="selectroute==='ForgotPassword'"
                    @login="selectroute='login'"
                />
            </div>
        </div>
    </div>
</template>

<script>
import login from '@/js/components/modal/Login';
import ForgotPassword from '@/js/components/modal/ForgotPassword';
export default {
    name: 'Modal',
    data () {
        return {
            selectroute: 'login'

        };
    },
    components: { login, ForgotPassword },
    methods: {
        close () {
            this.$emit('close');
        },
        loggedInHandler (e) {
            this.$emit('loggedIn', e);
        }
    }
};

</script>
