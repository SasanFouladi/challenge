<template>
    <div class="home-page">
        Home Page
    </div>
</template>

<script>
    export default {
        name: "Home",
        mounted() {
            this.$router.push('/code-management')
        }
    }
</script>
