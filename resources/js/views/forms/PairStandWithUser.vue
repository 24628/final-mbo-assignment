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
                            Aanpassen ronde
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
                            class="form admin-form-find-user"
                            method="post"
                            @submit.prevent="checkForm"
                        >
                            <div class="form-line admin-form-find-user-input">
                                <input
                                    id="search"
                                    v-model="search"
                                    class="form-text-input"
                                    type="text"
                                    name="search"
                                    placeholder="Zoek profiel"
                                >
                            </div>

                            <div class="admin-from-submit">
                                <input
                                    type="submit"
                                    value="Zoek"
                                    class="submit-btn admin-form-submit"
                                >
                            </div>
                        </form>

                        <div class="admin-display-page-main-container">
                            <div class="admin-find-user-display-table">
                                <div class="admin-find-user-table-cell">
                                    Naam
                                </div>
                                <div class="admin-find-user-table-cell">
                                    E-mail
                                </div>
                                <div class="admin-find-user-table-cell">
                                    Rol
                                </div>
                                <div class="admin-find-user-table-cell-actions" />
                            </div>
                            <div
                                v-for="user in users"
                                :id="'admin-user-' + user.id"
                                :key="'admin-user-' + user.id"
                                class="admin-find-user-display-table"
                            >
                                <div class="admin-find-user-table-cell">
                                    {{ user.name }}
                                </div>
                                <div class="admin-find-user-table-cell">
                                    {{ user.email }}
                                </div>
                                <div class="admin-find-user-table-cell">
                                    {{ user.role.role_name }}
                                </div>
                                <div class="admin-find-user-table-cell-actions">
                                    <div
                                        style="text-decoration: underline"
                                        class="admin-find-user-action-icon"
                                        @click="pairUser(user.id)"
                                    >
                                        Koppel
                                    </div>
                                </div>
                            </div>
                        </div>
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
    import API from '../../Api';

    export default {
        data () {
            return {
                users: [],
            };
        },
        name: 'PairStandWithUser',
        methods: {
            close () {
                this.$emit('close');
            },
            async checkForm (e) {
                const data = {
                    search: this.search
                };

                const response = await API.post(data, '/api/admin/search');
                this.users = response.data.message;

                e.preventDefault();
            },
            pairUser(id){
                console.log(id);
            },
        },
    };
</script>
