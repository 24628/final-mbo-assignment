<template>
    <div class="admin-item-display-holder">
        <input
            :id="'name' + item.id"
            v-model="item.name"
            class="admin-item-title-input"
            type="text"
            name="name"
            placeholder="Naam"
            readonly
        >

        <div class="form-line">
            <textarea
                :id="'description' + item.id"
                v-model="item.description"
                class="admin-item-text-editor"
                type="text"
                name="description"
                placeholder="Omschrijving"
                readonly
            />
        </div>
    </div>
</template>

<script>
export default {
    name: 'Item',
    props: ['item'],
    data () {
        return {

        };
    },
    methods: {

    }
};
</script>
