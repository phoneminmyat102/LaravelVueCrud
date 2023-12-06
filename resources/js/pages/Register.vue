<template>
    <nav class="navbar">
        <div class="container-fluid">
            <a class="navbar-brand">Panacea-Soft</a>
            <div class="d-flex">
                <router-link to="/" class="btn btn-primary">Home</router-link>
                <router-link to="/login" class="btn btn-primary mx-2">Login</router-link>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div v-if="error !== null" class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" @click="clearError"></button>

                    <span v-html="error"></span>
                </div>

                <div class="card card-default">
                    <div class="card-header">
                        <h5>Register New User</h5>
                    </div>
                    <div class="card-body">
                        <form>

                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-8">
                                    <input id="name" type="text" class="form-control" v-model="name" autofocus
                                        autocomplete="off" placeholder="Enter your name">
                                </div>
                            </div>

                            <div class="form-group row mt-1">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control" v-model="email" autofocus
                                        autocomplete="off" placeholder="Enter your email">
                                </div>
                            </div>


                            <div class="form-group row mt-1">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-8">
                                    <input id="password" type="password" class="form-control" v-model="password"
                                        autocomplete="off" placeholder="Enter your password">
                                </div>
                            </div>

                            <div class="form-group row mt-1 mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" @click="handleSubmit">
                                        Register
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-8 offset-md-4">
                                    <small class="text-muted">
                                        Have an account? Please
                                        <router-link to="/login">login</router-link>
                                    </small>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: "",
            email: "",
            password: "",
            error: null
        }
    },

    methods: {
        clearError() {
            this.error = null;
        },
        handleSubmit(e) {
            e.preventDefault()
            this.error = null;
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;
                this.$axios.post('api/user/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password
                })
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/login"
                        } else {
                            existingObj.error = response.data.message
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        if (error.response.data.errors) {
                            let errors = error.response.data.errors;
                            let error_msg = Object.values(errors).flat().join('<br>');
                            console.log('error_msg', error_msg);
                            existingObj.error = error_msg;
                        }
                    });
            })
        }
    },

    beforeRouteEnter(to, from, next) {
        if (window.Laravel.isLoggedin) {
            return next('dashboard');
        }
        next();
    }
}
</script>
<style scoped>
.navbar{
    background-color: #d8d5e4;
}
</style>
