<template>
    <transition name="modal-fade">
        <div class="admin-modal-backdrop">
            <div
                class="admin-modal"
                role="dialog"
                aria-labelledby="modalTitle"
                aria-describedby="modalDescription"
            >
                <header
                    id="modalTitle"
                    class="admin-modal-header"
                >
                    <slot name="header">
                        <p
                            class="admin-modal-title"
                        >
                            Gebruiker aanpassen
                        </p>

                        <button
                            type="button"
                            class="admin-modal-btn-close"
                            aria-label="Close modal"
                            @click="close"
                        >
                            x
                        </button>
                    </slot>
                </header>
                <section
                    id="modalDescription"
                    class="admin-modal-body"
                >
                    <slot name="body">
                        <form
                            class="form"
                            method="post"
                            @submit.prevent="checkForm"
                        >
                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="name"
                                >Naam</label>
                                <input
                                    id="name"
                                    v-model="name"
                                    class="form-text-input"
                                    type="text"
                                    name="name"
                                    placeholder="Naam"
                                >
                            </div>

                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="email"
                                >Email</label>
                                <input
                                    id="email"
                                    v-model="email"
                                    class="form-text-input"
                                    type="text"
                                    name="email"
                                    placeholder="E mail"
                                >
                            </div>

                            <select v-model="role_id">
                                <option v-for="role in roles" :key="role.id" :value="role.id">
                                    {{ role.role_name }}
                                </option>
                            </select>

                            <div class="form-line admin-from-submit">
                                <input
                                    type="submit"
                                    value="Indienen"
                                    class="submit-btn admin-form-submit"
                                >
                            </div>
                        </form>
                    </slot>
                </section>
                <footer class="admin-modal-footer">
                    <slot name="footer">
                        <button
                            type="button"
                            class="admin-modal-btn-green"
                            aria-label="Close modal"
                            @click="close"
                        >
                            Sluiten
                        </button>
                    </slot>
                </footer>
            </div>
        </div>
    </transition>
</template>

<script>
import API from '../../../../../Api';

export default {
    data () {
        return {
            userId: null,
            email: null,
            name: null,
            role_id: null,
            roles: []
        };
    },
    name: 'UpdateUserModal',
    props: ['id'],
    methods: {
        close () {
            this.$emit('close');
        },
        checkForm: async function (e) {
            const data = {
                email: this.email,
                name: this.name,
                role_id: this.role_id
            };

            await API.post(data, '/api/admin/update/user/' + this.userId, true);

            this.close();

            e.preventDefault();
        },
        async setFormData (res) {
            const data = res.data;
            this.userId = data.id;
            this.email = data.email;
            this.name = data.name;
            this.role_id = data.role_id;

            const response = await API.get('/api/role');
            this.roles = response.data;
        }
    },
    watch: {
        id: async function (newVal, oldVal) {
            await this.setFormData(await API.get('/api/admin/user/' + this.id));
        }
    },
    async mounted () {
        await this.setFormData(await API.get('/api/admin/user/' + this.id));
    }
};
</script>
