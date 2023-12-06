<template>
    <nav class="navbar">
        <div class="container-fluid">
            <a class="navbar-brand">Panacea-Soft</a>
            <div class="d-flex">
                <router-link to="/login" class="btn btn-primary">Login</router-link>
                <router-link to="/register" class="btn btn-primary mx-2">Register</router-link>
            </div>

        </div>
    </nav>
</template>

<script>
export default {

}
</script>
<style scoped>
.navbar{
    background-color: #d8d5e4;
}
</style>
