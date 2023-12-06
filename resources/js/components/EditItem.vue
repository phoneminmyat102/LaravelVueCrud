<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">Update Item Data</h5>
                <div>
                    <router-link :to="{name: 'items'}" class="btn btn-primary">Go Back</router-link>
                </div>
            </div>

            <div v-if="strSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                <strong v-html="strSuccess"></strong>
            </div>

            <div v-if="strError" class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong v-html="strError"></strong>
            </div>

            <form @submit.prevent="updateItem" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label>Item Name</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="name" placeholder="Enter Item name">
                </div>

                <div class="form-gorup mb-2">
                    <label>Select Category</label><span class="text-danger"> *</span>
                    <select v-model="selectedCategory" class="form-select mb-2" v-on:change="onChangeCat">
                        <option value="" disabled>Select a category</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Price</label><span class="text-danger"> *</span>
                    <input type="number" class="form-control" v-model="price" placeholder="Enter Item price">
                </div>

                <div class="form-group mb-3">
                    <label>Description</label><span class="text-danger"> *</span>
                    <ContentEditor v-model="description"/>
                </div>

                <div class="form-gorup mb-2">
                    <label>Select Item Condition</label><span class="text-danger"> *</span>
                    <select v-model="selectedItemCondition" class="form-select mb-2" v-on:change="onChangeItemCondition">
                        <option value="" disabled>Select Item Condition</option>
                        <option v-for="each in item_conditions" :key="each.id" :value="each.name">{{ each.name }}</option>
                    </select>
                </div>

                <div class="form-gorup mb-2">
                    <label>Select Item Type</label><span class="text-danger"> *</span>
                    <select v-model="selectedItemType" class="form-select mb-2" v-on:change="onChangeItemType">
                        <option value="" disabled>Select Item Type</option>
                        <option v-for="each in item_types" :key="each.id" :value="each.name">{{ each.name }}</option>
                    </select>
                </div>

                <div class="form-gorup mb-2">
                    <label>Item Photo</label><span class="text-danger"> *</span>
                    <input type="file" class="form-control mb-2" v-on:change="onChangeImage">

                    <div v-if="img">
                        <img v-bind:src="imgPreview" width="100" height="100"/>
                    </div>
                </div>

                <!-- Owner Information -->
                <div class="form-group mb-3">
                    <label>Owner Name</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="owner_name" placeholder="Input Owner name">
                </div>

                <div class="form-group mb-3">
                    <label>Contact Number</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="phone_number" placeholder="Add Number">
                </div>

                <div class="form-group mb-3">
                    <label>Address</label><span class="text-danger"> *</span>
                    <textarea class="form-control" rows="3" v-model="address" placeholder="Enter Address"></textarea>
                </div>

                <div class="form-gorup mb-2">
                    <!-- Location Picker Component -->
                    <location-picker
                    :initialLocation="initialLocation"
                    @location-selected="onLocationSelected"
                    class="form-gorup mb-2" ></location-picker>
                </div>

                <button type="submit" class="btn btn-primary mt-4 mb-4"> Update Item</button>

            </form>

        </div>
    </div>
</template>

<script>
import LocationPicker from './LocationPicker.vue'; // Adjust the path based on your project structure
import ContentEditor from './ContentEditor.vue';
import VueTelInput from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';

export default{
    components: {
        LocationPicker,
        ContentEditor,
        VueTelInput
    },
    data() {
        return {
            name: '',
            img: '',
            strSuccess: '',
            strError: '',
            imgPreview: null,
            selectedCategory: '',
            selectedItemCondition: '',
            selectedItemType: '',
            isChecked: 0,
            item_conditions: [
                { id: 1, name: 'New' },
                { id: 2, name: 'Used' },
                { id: 3, name: 'Good Second Hand' },
            ],
            item_types: [
                { id: 1, name: 'For Sell' },
                { id: 2, name: 'For Buy' },
                { id: 3, name: 'For Exchange' },
            ],
            owner_name: '',
            phone_number: '',
            categories: [],
            price: '',
            description: '',
            initialLocation: [16.806131740991844, 96.15124940872194], // Initial location for the LocationPicker
            selectedLocation: null,
            address: ''
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/categories/get/no_pagination')
                .then(response => {
                    this.categories = response.data
                })
                .catch(error => {
                    console.log('get ', error);
                });
            this.$axios.get(`/api/items/edit/${this.$route.params.id}`)
                .then(response => {
                    this.name = response.data['name'];
                    this.img = "/storage/"+response.data['image'];
                    this.imgPreview = this.img;

                    this.selectedCategory = response.data['category_id'];
                    this.price = response.data['price'];
                    this.description = response.data['description'];

                    this.selectedItemCondition = response.data['item_condition'];
                    this.selectedItemType = response.data['item_type'];

                    this.owner_name = response.data['owner_name'];
                    this.phone_number = response.data['phone_number'];
                    this.address = response.data['address'];
                })
                .catch(function(error) {
                    console.log(error);
                });
        });
    },
    methods: {
        onLocationSelected(selectedLocation) {
            this.selectedLocation = selectedLocation;
            this.address = selectedLocation.lat+'\n'+selectedLocation.lng+'\n'+selectedLocation.addr
        },
        onChangeCat(e) {
            this.selectedCategory = e.target.value;
        },
        onChangeItemCondition(e) {
            this.selectedItemCondition = e.target.options[e.target.selectedIndex].text;
        },
        onChangeItemType(e) {
            this.selectedItemType = e.target.options[e.target.selectedIndex].text;
        },
        onChangeImage(e) {
            const newImg = e.target.files[0];
            if (newImg) {
                this.img = newImg.name; // Store the file name or URL string
                let reader = new FileReader();
                reader.addEventListener("load", () => {
                this.imgPreview = reader.result;
                }, false);
                if (/\.(jpe?g|png|gif)$/i.test(newImg.name)) {
                reader.readAsDataURL(newImg);
                }
            }
        },
        updateItem(e) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                const formData = new FormData();
                formData.append('name', this.name);
                formData.append('category', this.selectedCategory);
                formData.append('price', this.price);
                formData.append('description', this.description);
                formData.append('category_id', this.selectedCategory);
                formData.append('item_condition', this.selectedItemCondition);
                formData.append('item_type', this.selectedItemType);
                formData.append('status', this.isChecked);
                formData.append('owner_name', this.owner_name);
                formData.append('phone_number', this.phone_number);
                formData.append('address', this.address);

                if (typeof this.img === 'object') {
                    formData.append('image', this.img);
                }

                this.$axios.post(`/api/items/update/${this.$route.params.id}`, formData, config)
                .then(response => {
                    if(response.data.success){
                        existingObj.strError = "";
                        existingObj.strSuccess = response.data.message;
                    }else{
                        existingObj.strSuccess = "";
                        existingObj.strError = response.data.message;
                    }
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                })
                .catch(error => {
                    console.error(error)
                    if (error.response.data.errors) {
                        let errors = error.response.data.errors;
                        let error_msg = Object.values(errors).flat().join('<br>');
                        console.log('error_msg', error_msg);
                        existingObj.strError = error_msg;
                    }else{
                        existingObj.strSuccess = "";
                        existingObj.strError = error.response.statusText;
                    }
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
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
