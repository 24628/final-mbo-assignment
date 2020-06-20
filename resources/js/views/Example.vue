<template>
    <div>
        <h1 v-if="boolean">
            {{ title }}
        </h1>
        <h2 v-else-if="title.length">
            Cool
        </h2>
        <input
            v-model="title"
            :disabled="boolean"
            type="text"
        >
        <button @click="boolean = !boolean">
            Yay
        </button>
        <button @click="checkDate">
            Date!
        </button>
        <!--<loading/>-->
        <div :class="{'active' : boolean}">
            I'm a div!
        </div>
    </div>
</template>

<script>
// import loading from './loading';

export default {
    name: 'Example',
    data () {
        return {
            title: 'Jari is een lekkerding',
            boolean: false
        };
    },
    // components: {loading},
    methods: {
        checkDate () {
            this.boolean = true;
            this.useless();
        },
        useless () {
        // i do nothing!
        }
    }
};
</script>

<style>
    .active{
        background: red;
    }
</style>
