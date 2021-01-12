<template>
  <div class="app">
    <Header />

    <div class="app__content">
      <Sidebar class="app__nav"/>
      <main class="app__inner-content">
        <router-view/>

<!--        <div class="images">
          <div class="images__dropzone text-center border">
            <label>
              <i class="fa fa-camera"></i>&nbsp;&nbsp;Загрузить фото
              <input type="file" class="images__file">
            </label>
          </div>
          <div class="images__uploaded mt-3">

            <div class="row uploaded-images__item">
              <div class="col-5">
                <img src="/media/images/sample/sample_image.jpeg" class="rounded" alt="">
              </div>
              <div class="col-7">
                <div>file name.jpg</div>
                <div class="progress mt-3 border-info">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="mt-3">
                  <button class="btn btn-danger"><i class="fa fa-trash-alt"></i>&nbsp;&nbsp;Удалить</button>
                </div>
                <div class="mt-3 text-danger">
                  Error message
                </div>
              </div>
            </div>

          </div>
        </div>-->

      </main>
    </div>

  </div>
</template>
<script lang="ts">

import {defineComponent} from 'vue';
import Sidebar from "@/components/layout/components/Sidebar.vue";
import Header from "@/components/layout/components/Header.vue";
import axios from "axios";

export default defineComponent({
  name: 'App',
  components: {
    Sidebar,
    Header,
  },
  mounted() {
    axios.interceptors.request.use(function (config) {
      config.headers['Content-Type'] =  'application/json';
      return config;
    });

    this.$store.dispatch('initStore');
  }
});

</script>

<style lang="scss">

.app {
  &__content {
    max-width: $size-app-max-width;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    padding-bottom: $grid-20;

    @media screen and (min-width: $size-mobile-breakpoint){
      flex-direction: row;
    }
  }

  &__nav {
    flex: 0 0 $grid-40;
  }

  &__inner-content {
    padding: $grid-3 $grid-7;
    flex-grow: 1;
  }
}

</style>
