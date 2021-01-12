<template>
  <div class="registration">
    <h1>Login</h1>

    <form id="user-form" class="form" method="post" @submit.prevent="onSubmit" action="/api/auth/login">

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
      </div>

      <div class="form-group mt-1">
        <button type="submit" class="btn btn-success">Login</button>
      </div>

    </form>

  </div>
</template>

<script>
import {defineComponent} from 'vue';
import FormHelper from "@/misc/FormHelper";

export default defineComponent({
  name: "Login",
  mounted() {
    FormHelper.setForm('user-form');
  },
  methods: {
    onSubmit() {
      let that = this;
      FormHelper.submit(
          function(response) {
            FormHelper.afterValidate();
            that.$store.dispatch('setWebUser', response.data.user);
            that.$router.push({name: 'cars.index'});
          },
          function(response) {
            FormHelper.afterValidate({email: [response.data.error]});
          }
      );
    }
  }
});
</script>

<style scoped>

</style>