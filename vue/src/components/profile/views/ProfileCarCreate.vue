<template>
  <div class="cars-create">
    <h1>Добавить объявление</h1>

    <form class="form" id="car-create" @submit.prevent="onSubmit" action="/api/profile/cars" method="post">

      <div class="row">
        <div class="col mt-3">
          <label>Заголовок</label>
          <input type="text" name="title" v-bind="car.brand" class="form-control">
        </div>
      </div>

      <div class="row">
        <div class="col mt-3">
          <label>Описание</label>
          <textarea name="description" class="form-control"></textarea>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3 col-sm-12 mt-3">
          <label>Год</label>
          <input type="number" name="prod_year" class="form-control">
        </div>
        <div class="col-md-3 col-sm-12 mt-3">
          <label>Марка</label>
          <select name="brand" v-model="car.brand" class="form-control">
            <option></option>
            <option v-for="brand in brands" :value="brand.id">{{ brand.title }}</option>
          </select>
        </div>
        <div class="col-md-3 col-sm-12 mt-3">
          <label>Модель</label>
          <select name="brand_model" class="form-control">
            <option></option>
            <option v-for="model in brandModels" :value="model.id">{{ model.title }}</option>
          </select>
        </div>
        <div class="col-md-3 col-sm-12 mt-3">
          <label>Коробка передач</label>
          <select name="gearbox_type" class="form-control">
            <option v-for="(model, key) in gearboxTypes" :value="key">{{ model }}</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3 col-sm-12 mt-3">
          <label>Кузов</label>
          <select name="body_type" class="form-control">
            <option v-for="(model, key) in bodyTypes" :value="key">{{ model }}</option>
          </select>
        </div>
        <div class="col-md-3 col-sm-12 mt-3">
          <label>Кол-во мест</label>
          <select name="seats" class="form-control">
            <option value="3">3</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
          </select>
        </div>
        <div class="col-md-3 col-sm-12 mt-3">
          <label>Топливо</label>
          <select name="fuel" class="form-control">
            <option v-for="(model, key) in fuelTypes" :value="key">{{ model }}</option>
          </select>
        </div>
        <div class="col-md-3 col-sm-12 mt-3">
          <label>Обьем двигателя</label>
          <input type="number" name="engine_capacity" class="form-control">
        </div>
      </div>

      <div class="row">
        <div class="col-md-3 col-sm-12 mt-3">
          <label>Привод</label>
          <select name="wheel_drive" class="form-control">
            <option v-for="(model, key) in wheelDriveTypes" :value="key">{{ model }}</option>
          </select>
        </div>
        <div class="col-md-3 col-sm-12 mt-3">
          <label>Пробег</label>
          <input type="number" name="odometer" class="form-control">
        </div>
        <div class="col-md-3 col-sm-12 mt-3">
          <label>Город</label>
          <select name="city" class="form-control">
            <option v-for="model in cities" :value="model.id">{{ model.title }}</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-12 mt-3">
          <div class="images">
            <div class="images__dropzone text-center border">
              <label>
                <i class="fa fa-camera"></i>&nbsp;&nbsp;Загрузить фото
                <input type="file" class="images__input_file">
              </label>
            </div>
            <div class="images__uploaded mt-3"></div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 mt-3">
          <div class="form-group">
            <button type="submit" class="btn btn-success">Добавить</button>
          </div>
        </div>
      </div>

    </form>

  </div>
</template>

<script>
import {defineComponent, createApp} from 'vue';
import FormHelper from "@/misc/FormHelper";
import * as Upchunk from '@mux/upchunk';
import UploadImage from "@/components/layout/components/UploadImage";

export default defineComponent({
  name: "ProfileCarCreate",
  data() {
    return {
      car: {
        brand: ''
      }
    }
  },
  beforeMount() {

  },
  mounted() {
    const images = document.getElementsByClassName('images__uploaded')[0];

    const file = document.getElementsByClassName('images__input_file')[0];
    const that = this;
    file.onchange = () => {

      let imagePreview = defineComponent({
        extends: UploadImage,
        data() {
          return {
            file: file.files[0],
            accessToken: that.user.token,
          }
        }
      });
      const div = document.createElement('div');
      images.appendChild(div);
      createApp(imagePreview).mount(div);
    }

    FormHelper.setForm('car-create');
  },
  methods: {
    onSubmit() {

      FormHelper.submit(
          function (response) {
            FormHelper.afterValidate();
            that.$router.push({name: 'cars.index'});
          },
          function (response) {
            FormHelper.afterValidate(response.data.errors);
          }
      );

    }
  },
  computed: {
    brands() { return this.$store.getters.brands; },
    brandModels() {
      let result = [];
      let models = this.$store.getters.brandModels;
      for (let k in models) {
        let model = models[k];
        if (model.brand_id === this.car.brand) {
          result.push(model);
        }
      }
      return result;
    },
    gearboxTypes() { return this.$store.getters.gearboxTypes; },
    bodyTypes() { return this.$store.getters.bodyTypes; },
    fuelTypes() { return this.$store.getters.fuelTypes; },
    wheelDriveTypes() { return this.$store.getters.wheelDriveTypes; },
    cities() { return this.$store.getters.cities; },
    user() { return this.$store.getters.user; },
  }
});
</script>

<style lang="scss">
.images {
  .images__dropzone {
    input[type=file] {
      display: none;
    }
    label {
      width: 100%;
      padding: 2em;
      font-size: 1.7em;

      &:hover {
        cursor: pointer;
      }
    }
  }
}
</style>