<template>
  <div class="car-list__item">
    <div class="car-list__image">
      <img v-if="hasImages()" :src="getFirstImage()" style="width: 100px">
    </div>
    <div class="car-list__data">
      <div class="car-list__title">
<!--        <router-link :to="{name: 'cars.view'}">{{car.title}}</router-link>-->
        {{car.title}}
      </div>
    </div>
  </div>
</template>

<script>
import {defineComponent, ref} from 'vue';
import axios from 'axios';

export default defineComponent({
  name: "CarListItem",
  props: {
    car: Object
  },
  methods: {
    hasImages() {
      return this.car.images.length > 0;
    },
    getFirstImage() {
      console.log('this.car', this.car);
      if (!this.hasImages()) {
        return null;
      }
      return this.car.images[0]['url'];
    }
  }
  // data() {
  //   return {
  //     car: null
  //   }
  // }
});
</script>

<style scoped>

</style>