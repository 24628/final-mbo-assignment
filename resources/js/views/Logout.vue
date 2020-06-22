<template>
    <div>
        Logging out.
    </div>
</template>

<script>
export default ({
    mounted () {
        this.$emit('logout');
        this.$router.replace("/");
    }
});
</script>
