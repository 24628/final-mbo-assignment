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
                        <h1>test</h1>
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
    import API from '../../../../Api';

    export default {
        data () {
            return {
            };
        },
        name: 'ShowModalSubsPerItem',
        props: ['id'],
        methods: {
            close () {
                this.$emit('close');
            },
            update (data) {
                console.log(data);
            }
        },
        watch: {
            id: async function (newVal, oldVal) {
                if(this.id)this.update(await API.get('/api/admin/sub/' + this.id));
            }
        },
        async mounted () {
            if(this.id)this.update(await API.get('/api/admin/sub/' + this.id));
        }
    };
</script>
