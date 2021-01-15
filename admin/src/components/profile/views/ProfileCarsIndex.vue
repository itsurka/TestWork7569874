<template>
  <div class="cars-index">
    <h1>ProfileCarsIndex</h1>

    <div v-if="isLoading">loading...</div>
    <div v-if="!isLoading && error !== null">error: {{error.message}}</div>
    <div v-if="!isLoading && error === null" class="cars-list">
      <template v-for="item in data.items">
        <CarListItem :car="item"></CarListItem>
      </template>
    </div>

  </div>
</template>

<script>
import {defineComponent, ref} from 'vue';
import axios from 'axios';
import CarListItem from "@/components/cars/layout/CarListItem";

function prepareResponse(data) {
  return {
    items: data.items,
    current_page: data.current_page,
    page_count: data.page_count,
  };
}

export default defineComponent({
  name: "ProfileCarsIndex",
  components: {CarListItem},
  setup() {
    const isLoading = ref(false);
    const error = ref(null);
    const data = ref(null);

    isLoading.value = true;
    axios.get('/api/profile/cars')
    .then(response => {
      data.value = prepareResponse(response.data);
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