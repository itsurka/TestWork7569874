<template>
  <div class="cars-index">
    <h1>cars index 2</h1>
  </div>
</template>

<script>
import {defineComponent} from 'vue';
import axios from 'axios';

export default defineComponent({
  name: "CarsIndex",
  setup() {
    axios.get('/api/cars').then(response => {
      console.log('response', response)
    })
  }
});
</script>

<style scoped>

</style>