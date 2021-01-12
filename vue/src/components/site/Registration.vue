<template>
  <div class="registration">
    <h1>Registration</h1>

    <form id="user-form" class="form" method="post" action="/api/users" @submit.prevent="onSubmit" v-show="!completed">

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
      </div>

      <div class="form-group mt-1">
        <button type="submit" class="btn btn-success">Register</button>
        <button type="button" class="btn btn-warning" @click="completed = true">hide registration</button>
      </div>

    </form>

    <div v-show="completed">
      <p class="text-success">Done! Now please check your email and confirm it.</p>
    </div>

  </div>
</template>

<script>
import {defineComponent} from 'vue';
import axios from 'axios';
import FormHelper from "@/misc/FormHelper";

export default defineComponent({
  name: "Registration",
  data() {
    return {
      completed: false
    }
  },
  mounted() {
    FormHelper.setForm('user-form');
  },
  methods: {
    onSubmit() {
      let that = this;
      FormHelper.submit(
          function(response) {
            FormHelper.afterValidate();
            that.$router.push({name: 'login.index'});
          },
          function(response) {
            FormHelper.afterValidate(response.data.errors);
          }
      );
    }
  }
});
</script>

<style scoped>

</style>