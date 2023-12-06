<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">Add Category Data</h5>
                <div>
                    <router-link :to="{name: 'categories'}" class="btn btn-primary">Go Back</router-link>
                </div>
            </div>

            <div v-if="strSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                <strong v-html="strSuccess"></strong>
            </div>

            <div v-if="strError" class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong v-html="strError"></strong>
            </div>

            <form @submit.prevent="addCategory" enctype="multipart/form-data">
                <div class="form-group mb-2">
                    <label>Name</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="name" placeholder="Enter Category name">
                </div>

                <div class="form-gorup mb-2">
                    <label>Category Image</label><span class="text-danger"> *</span>
                    <input type="file" class="form-control mb-2" v-on:change="onChangeImage">

                    <div v-if="img">
                        <img v-bind:src="imgPreview" width="100" height="100"/>
                    </div>
                </div>

                <div class="form-group mb-2">
                <label>Status</label><span class="text-danger"> *</span>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheckbox" v-model="isChecked">
                    <label class="form-check-label" for="exampleCheckbox">Publish</label>
                </div>
            </div>

                <button type="submit" class="btn btn-primary mx-2 mt-4 mb-4"> Add Category</button>
            </form>

        </div>
    </div>
</template>
<script>

export default{
    data() {
        return {
            name: '',
            img: '',
            strSuccess: '',
            strError: '',
            imgPreview: null,
            isChecked: 0
        }
    },
    methods: {
        onChangeImage(e) {
            this.img = e.target.files[0];
            let reader = new FileReader();
            reader.addEventListener("load", function () {
                this.imgPreview = reader.result;
            }.bind(this), false);

            if (this.img) {
                if ( /\.(jpe?g|png|gif)$/i.test( this.img.name ) ) {
                    reader.readAsDataURL( this.img );
                }
            }
        },
        addCategory(e) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                const formData = new FormData();
                formData.append('name', this.name);
                formData.append('image', this.img);
                formData.append('status', this.isChecked);

                this.$axios.post('/api/categories/add', formData, config)
                .then(response => {
                    if(response.data.success){
                        existingObj.strError = "";
                        existingObj.strSuccess = response.data.message;
                    }else{
                        existingObj.strSuccess = "";
                        existingObj.strError = response.data.message;
                    }
                })
                .catch(error => {
                    console.error(error)
                    if (error.response.data.errors) {
                        let errors = error.response.data.errors;
                        let error_msg = Object.values(errors).flat().join('<br>');
                        existingObj.strError = error_msg;
                    }else{
                        existingObj.strSuccess = "";
                        existingObj.strError = error.response.statusText;
                    }
                });
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
