<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">Items</h5>
                <div>
                    <button class="btn btn-primary" type="button" @click="this.$router.push('/items/add')"><font-awesome-icon class="fa" icon="fa fa-plus"/> Add Item</button>
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
                            <th width="50" class="text-center">NO</th>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Owner Name</th>
                            <th>Publish</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in items.data" :key="item.id">
                            <td class="text-center">
                                <router-link :to="{ name: 'edititem', params: { id: item.id } }"
                                    class="btn btn-success btn-sm"><font-awesome-icon class="fa" icon="fa fa-pen"/></router-link>
                                <button class="btn btn-danger btn-sm" @click="deleteItem(item.id)"><font-awesome-icon class="fa" icon="fa fa-trash"/></button>
                            </td>
                            <td class="text-center">{{ index + 1 }}.</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.category.name }}</td>
                            <td v-html="item.description"></td>
                            <td>{{ item.price }}</td>
                            <td>{{ item.owner_name }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    :id="'flexSwitchCheckDefault-' + item.id"
                                    v-model="item.status"
                                    @change="togglePublishStatus(item)"
                                    :checked="item.status"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <Bootstrap5Pagination
                align="center"
                :data="items"
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
            items: [],
            strSuccess: '',
            strError: ''
        }
    },
    created() {
        this.list()
    },
    methods: {
        list(page=1){
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                axios.get(`/api/items?page=${page}`)
                .then(response =>{
                    response.data.data.forEach((item, index) => {
                        response.data.data[index].status = Boolean(response.data.data[index].status)
                    });

                    this.items = response.data
                }).catch(error =>{
                    console.error(error)
                })
            })
        },
        deleteItem(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;
                if (confirm("Do you really want to delete this data?")) {
                    this.$axios.delete(`/api/items/delete/${id}`)
                        .then(response => {
                            let i = this.items.data.map(cat => cat.id).indexOf(id);
                            this.items.data.splice(i, 1);
                            existingObj.strError = "";
                            existingObj.strSuccess = response.data.message;
                        })
                        .catch(error => {
                            console.error(error)
                            existingObj.strError = "";
                            existingObj.strError = error.response.statusText;
                        });
                }
            });
        },
        togglePublishStatus(item) {
            this.$axios.put(`/api/items/toggle-publish/${item.id}`)
                .then(response => {
                    this.strError = '';
                    this.strSuccess = response.data.message;
                })
                .catch(error => {
                    console.error(error)
                    this.strError = error.response.data.message;
                    this.strSuccess = '';
                    item.status = Boolean(!item.status);
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
