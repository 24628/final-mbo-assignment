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
                            Creëer programma
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
                                >Programma naam</label>
                                <input
                                    id="name"
                                    v-model="name"
                                    class="form-text-input"
                                    type="text"
                                    name="name"
                                    placeholder="Programma naam"
                                >
                            </div>

                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="description"
                                >Programma omschrijving</label>
                                <textarea
                                    id="description"
                                    v-model="description"
                                    class="form-text-input"
                                    type="text"
                                    name="description"
                                    placeholder="Programma omschrijving"
                                />
                            </div>

                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="active"
                                >Zet het programma op actief</label>
                                <input
                                    id="active"
                                    v-model="active"
                                    class="form-text-input"
                                    type="checkbox"
                                    name="active"
                                    placeholder="Event name"
                                >
                            </div>

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
            name: null,
            description: null,
            active: false
        };
    },
    name: 'CreateProgramModal',
    props: ['id'],
    methods: {
        close () {
            this.$emit('close');
        },
        checkForm: function (e) {
            const data = {
                event_id: this.id,
                name: this.name,
                description: this.description,
                active: this.active
            };

            API.post(data, '/api/program');

            this.close();

            e.preventDefault();
        }
    }
};
</script>
