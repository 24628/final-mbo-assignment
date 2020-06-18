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
                            Aanpassen congres
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
                                >Congres naam</label>
                                <input
                                    id="name"
                                    v-model="name"
                                    class="form-text-input"
                                    type="text"
                                    name="name"
                                    placeholder="Congres naam"
                                >
                            </div>

                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="description"
                                >Congres omschrijving</label>
                                <textarea
                                    id="description"
                                    v-model="description"
                                    class="form-text-input"
                                    type="text"
                                    name="description"
                                    placeholder="Congres omschrijving"
                                />
                            </div>

                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="active"
                                >Zet het congres op actief</label>
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
    name: 'UpdateCongressModal',
    props: ['id'],
    methods: {
        close () {
            this.$emit('close');
        },
        checkForm: function (e) {
            const data = {
                name: this.name,
                description: this.description,
                active: this.active
            };

            API.post(data, '/api/congress/' + this.id, true);

            this.close();

            e.preventDefault();
        },
        setFormData (res) {
            const data = res.data;
            this.name = data.name;
            this.description = data.description;
            this.active = data.active;
        }
    },
    watch: {
        id: async function (newVal, oldVal) {
            if (this.id) this.setFormData(await API.get('/api/congress/' + this.id));
        }
    },
    async mounted () {
        if (this.id) this.setFormData(await API.get('/api/congress/' + this.id));
    }
};
</script>
