<template>
    <div>
      <l-map :center="mapCenter" :zoom="mapZoom" @click="onMapClick">
        <l-tile-layer :url="tileLayerUrl"></l-tile-layer>
        <l-marker v-if="selectedLocation" :lat-lng="selectedLocation"></l-marker>
      </l-map>
      <!-- Selected Location :
        <span>Latitude: {{ selectedLocation[0] }}</span>
        <span>Longitude: {{ selectedLocation[1] }}</span>
        <span>Address: {{ locationAddress }}</span> -->
    </div>
  </template>

  <script>
  import { ref, watch } from 'vue';
  import { LMap, LTileLayer, LMarker } from '@vue-leaflet/vue-leaflet';
  import 'leaflet/dist/leaflet.css';

  export default {
    components: {
      LMap,
      LTileLayer,
      LMarker,
    },
    props: {
      initialLocation: {
        type: Array,
        default: () => [0, 0],
      },
    },
    data() {
      return {
        mapCenter: this.initialLocation,
        mapZoom: 10,
        tileLayerUrl: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        selectedLocation: this.initialLocation,
        locationAddress: '',
      };
    },
    watch: {
      selectedLocation: 'fetchLocationAddress',
    },
    methods: {
      onMapClick(event) {
        this.selectedLocation = [event.latlng.lat, event.latlng.lng];
      },
      async fetchLocationAddress() {
        const apiKey = '7412e1b57df24ca09fc2ca0a6b860d4b'; // Replace with OpenCage API key
        const [lat, lng] = this.selectedLocation;
        console.log('lat, lng ', lat, lng)

        try {
          const response = await fetch(
            `https://api.opencagedata.com/geocode/v1/json?q=${lat}+${lng}&key=${apiKey}`
          );
          const data = await response.json();
          console.log('data ', data)
          if (data.results && data.results.length > 0) {
            console.log('data ',  data.results[0].formatted)
            this.locationAddress = data.results[0].formatted;
          } else {
            this.locationAddress = 'Address not found';
          }
          this.$emit('location-selected', {lat: lat, lng: lng, addr: this.locationAddress});
        } catch (error) {
          console.error('Error fetching location address:', error);
          this.locationAddress = 'Error fetching address';
        }
      },
    },
  };
  </script>

  <style scoped>
  /* Add any custom styles here */
  div {
    position: relative;
    height: 200px;
  }

  label {
    margin-top: 10px;
    display: block;
  }

  div {
    margin-top: 2px;
  }
  </style>
