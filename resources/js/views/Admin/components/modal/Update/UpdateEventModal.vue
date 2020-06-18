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
                            Aanpassen evenement
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
                                >Evenement naam</label>
                                <input
                                    id="name"
                                    v-model="name"
                                    class="form-text-input"
                                    type="text"
                                    name="name"
                                    placeholder="Evenement naam"
                                >
                            </div>

                            <div class="form-line">
                                <label
                                    class="form-label"
                                    for="description"
                                >Evenement omschrijving</label>
                                <textarea
                                    id="description"
                                    v-model="description"
                                    class="form-text-input"
                                    type="text"
                                    name="description"
                                    placeholder="Evenement omschrijving"
                                />
                            </div>

                            <input
                                type="file"
                                name="image"
                                @change="onFileChange"
                            >

                            <div
                                ref="previewImage"
                                class="event-form-preview-image-holder"
                            />

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
import create from 'dom-create-element';

export default {
    data () {
        return {
            name: '',
            description: '',
            image: null
        };
    },
    name: 'UpdateEventModal',
    props: ['id'],
    methods: {
        close () {
            this.$emit('close');
        },
        checkForm: function (e) {
            if (this.image === null) return;

            const data = {
                name: this.name,
                description: this.description,
                image: this.image
            };

            API.post(data, '/api/event/' + this.id, true);

            this.close();

            e.preventDefault();
        },
        async onFileChange (e) {
            let base64;
            const file = e.target.files[0];
            const reader = new FileReader();
            const imageTypes = ['png', 'jpeg'];
            const prev = this.$refs.previewImage;

            if (!imageTypes.includes(file.type.split('/')[1])) {
                e.target.value = null;
                this.image = null;
                alert('Verkeerde image type toegestaan [png, jpeg]');
                return;
            }

            reader.onload = function () {
                base64 = this.result;

                const image = create({
                    selector: 'img',
                    styles: 'event-form-preview-image'
                });

                image.src = base64;
                prev.innerHTML = '';
                prev.appendChild(image);
            };

            await reader.readAsDataURL(file);

            this.image = base64;
        },
        setFormData (data) {
            const res = data.data;
            this.name = res.name;
            this.description = res.description;
            this.image = res.image;

            const prev = this.$refs.previewImage;
            const image = create({
                selector: 'img',
                styles: 'event-form-preview-image'
            });

            image.src = res.image;
            prev.innerHTML = '';
            prev.appendChild(image);
        }
    },
    watch: {
        id: async function (newVal, oldVal) {
            if (this.id) this.setFormData(await API.get('/api/event/' + this.id));
        }
    },
    async mounted () {
        if (this.id) this.setFormData(await API.get('/api/event/' + this.id));
    }
};
</script>
