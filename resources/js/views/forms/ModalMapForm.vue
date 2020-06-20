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
                            Admin controls
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
                        <h1>Hieronder alle informatie over de plattegrond voor de admin</h1>
                        <ul>
                            <li>Dubbel klik op een blok om deze te verwijderen</li>
                            <li>when color is selected and items is holden it will change that items color</li>
                            <li>when items is tapped it will take the see that as the selected item when the copy state is on true</li>
                            <li>key ctrl + shift links start copy state</li>
                            <li>key ctrl + v start copying items and copy state on false in case user mis clicks</li>
                            <li>key ctrl + z undo last item from copy state</li>
                            <li>key escape to stop the copy state and clear the copy item</li>
                        </ul>
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
                            Close
                        </button>
                    </slot>
                </footer>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    data () {
        return {
        };
    },
    name: 'CreateBlockModal',
    methods: {
        close () {
            this.$emit('close');
        }
    }
};
</script>
