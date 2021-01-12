<template>
  <div class="cars-index">
    <h1>ProfileCarsIndex</h1>

    <div v-if="isLoading">loading...</div>
    <div v-if="!isLoading && error !== null">error: {{error.message}}</div>
    <div v-if="!isLoading && error === null">data: {{data}}</div>

  </div>
</template>

<script>
import {defineComponent, ref} from 'vue';
import axios from 'axios';
import {Car} from '@components/cars/models/Car';

function prepareResponse(data) {
  const result = {
    items: [],
    current_page: data.current_page,
    page_count: data.page_count,
  };

  for (let i in data.items) {
    let item = data.items[i];
    const car = new Car();
  }
}

export default defineComponent({
  name: "ProfileCarsIndex",
  setup() {
    const isLoading = ref(false);
    const error = ref(null);
    const data = ref(null);

    isLoading.value = true;
    axios.get('/api/profile/cars')
    .then(response => {
      data.value = response.data;
    })
    .catch(response => {
      console.error('request failed', response);
      error.value = response;
    })
    .then(() => {
      isLoading.value = false;
    });

    return {
      isLoading,
      error,
      data
    };
  },
});
</script>

<style scoped>

</style>