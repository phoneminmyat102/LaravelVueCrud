<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">Categories</h5>
                <div>
                    <button class="btn btn-primary" type="button" @click="this.$router.push('/categories/add')"><font-awesome-icon class="fa" icon="fa fa-plus"/> Add Categories</button>
                </div>
            </div>

            <div v-if="strSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                <strong v-html="strSuccess"></strong>
            </div>

            <div v-if="strError" class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong v-html="strError"></strong>
            </div>

            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th class="text-center" width="200">Actions</th>
                            <th style="width: 50px;" class="text-center">NO</th>
                            <th class="text-center">Category Name</th>
                            <!-- <th>Category Photo</th> -->
                            <th>Publish</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(category, index) in categories.data" :key="category.id">
                            <td class="text-center">
                                <router-link :to="{ name: 'editcategory', params: { id: category.id } }"
                                    class="btn btn-success btn-sm"><font-awesome-icon class="fa" icon="fa fa-pen"/></router-link>
                                <button class="btn btn-danger btn-sm" @click="deleteCategory(category.id)"><font-awesome-icon class="fa" icon="fa fa-trash"/></button>
                            </td>
                            <td class="text-center">{{ index + 1 }}.</td>
                            <td class="text-center">{{ category.name }}</td>
                            <!-- <td>
                            <div v-if="category.image">
                                <img alt="category-photo" width="100" v-bind:src="'/storage/' + category.image">
                            </div>
                        </td> -->
                            <td>
                                <div class="form-check form-switch">
                                    <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    :id="'flexSwitchCheckDefault-' + category.id"
                                    v-model="category.status"
                                    @change="togglePublishStatus(category)"
                                    :checked="category.status"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <Bootstrap5Pagination
                align="center"
                :data="categories"
                @pagination-change-page="list"
            />
        </div>
    </div>
</template>

<script>
import {Bootstrap5Pagination} from 'laravel-vue-pagination'

export default {
    components:{
        Bootstrap5Pagination
    },
    data() {
        return {
            categories: [],
            strSuccess: '',
            strError: '',
        }
    },
    created() {
        this.list();
    },
    methods: {
        list(page=1){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                axios.get(`/api/categories?page=${page}`)
                .then(response =>{
                    response.data.data.forEach((item, index) => {
                        response.data.data[index].status = Boolean(response.data.data[index].status)
                    });
                    this.categories = response.data
                }).catch( error => {
                    console.error(error)
                })
            })
        },
        deleteCategory(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;
                if (confirm("Do you really want to delete this data?")) {
                    this.$axios.delete(`/api/categories/delete/${id}`)
                        .then(response => {
                            let i = this.categories.data.map(cat => cat.id).indexOf(id);
                            this.categories.data.splice(i, 1);
                            existingObj.strError = "";
                            existingObj.strSuccess = response.data.message;
                        })
                        .catch(error => {
                            console.log(error)
                            existingObj.strError = "";
                            existingObj.strError = error.response.statusText;
                        });
                }
            });
        },
        togglePublishStatus(category) {
            console.log('cat id ', category.id)
            this.$axios.put(`/api/categories/toggle-publish/${category.id}`)
                .then(response => {
                    this.strError = '';
                    this.strSuccess = response.data.message;
                })
                .catch(error => {
                    console.error(error)
                    this.strError = error.response.data.message;
                    this.strSuccess = '';
                    category.status = Boolean(!category.status);
                });
        }
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>
