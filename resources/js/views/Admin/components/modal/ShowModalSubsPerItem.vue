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
                            Aantal bezoekers per item
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
                        <div v-for="item in data" style="color: white;padding: 1em">

                            name: {{item.item.name}}</br>
                            amount: {{item.amount}}
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
    import API from '../../../../Api';

    export default {
        data () {
            return {
                data: [],
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
                this.data = data.data;
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
