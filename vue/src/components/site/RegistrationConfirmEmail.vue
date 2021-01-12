<template>
  <div class="registration">
    <h1>RegistrationConfirmEmail</h1>
    <div v-show="confirmed === null" class="text-info">Checking email confirmation...</div>
    <div v-show="confirmed === false" class="text-danger">{{errorMessage}}</div>
    <div v-show="confirmed === true" class="text-success">
      <p>Email successfully confirmed!</p>
      <p>Now you can to <router-link>sign in</router-link></p>
    </div>
  </div>
</template>

<script>
import {defineComponent} from 'vue';
import axios from 'axios';
import {useStore} from "vuex";

export default defineComponent({
  name: "RegistrationConfirmEmail",
  data() {
    return {
      key: null,
      confirmed: null,
      errorMessage: null,
    }
  },
  methods: {
  },
  beforeMount() {
    let urlParams = new URLSearchParams(
        window.location.href.substr(window.location.href.indexOf('?'))
    );
    this.key = urlParams.get('key');
    if (!this.key) {
      this.confirmed = false;
      this.errorMessage = 'Key not found';
      return;
    }

    const that = this;
    const store = useStore();

    axios.post('/api/users/confirm-email/' + this.key).then(response => {
      if (response.data.user) {
        that.confirmed = true;
        /*store.dispatch('setWebUser', response.data.user);*/
      }
    }).catch(function (error) {
      that.confirmed = false;
      that.errorMessage = 'Error occurred, try later.'
      if (error.response) {
        if (error.response.status === 404) {
          that.errorMessage = 'Confirmation key is invalid';
        }
      }
    });
  },
});
</script>

<style scoped>

</style>