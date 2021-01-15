<template>
  <div class="row images__item mt-3">
    <div class="col-5">
      <img src="" class="rounded" alt="">
    </div>
    <div class="col-7">
      <div>{{file.name}}</div>
      <div class="progress mt-3 border-info">
        <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
      <div class="mt-3 images__item-delete">
        <button class="btn btn-danger images__item-delete-btn" type="button"><i class="fa fa-trash-alt"></i>&nbsp;&nbsp;Удалить</button>
      </div>
      <div class="mt-3 text-danger images__item-error"></div>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue';
import * as Upchunk from "@mux/upchunk";

export default defineComponent({
  name: 'UploadImage',
  data() {
    return {
      file: null,
      accessToken: null
    }
  },
  mounted() {
    let imageList = document.getElementsByClassName('images__item');
    let cont = imageList[imageList.length - 1];
    let progressBarCont = cont.getElementsByClassName('progress')[0];
    let progressBar = progressBarCont.getElementsByClassName('progress-bar')[0];
    let deleteBtn = cont.getElementsByClassName('images__item-delete')[0];
    let errorMsg = cont.getElementsByClassName('images__item-error')[0];

    let image = cont.getElementsByTagName('img')[0];
    image.src = URL.createObjectURL(this.file);
    image.onload = () => {
      URL.revokeObjectURL(image.src);
    }

    const upload = Upchunk.createUpload({
      endpoint: '/api/profile/files/cars',
      file: this.file,
      chunkSize: 256,
      headers: {
        Authorization: this.accessToken,
        'File-Name': this.file.name,
      }
    });

    upload.on('error', err => {
      progressBar.setAttribute('class', progressBar.getAttribute('class') + ' bg-error');
      errorMsg.innerHTML = 'Ошибка, не удалось загрузить изображение'
      errorMsg.setAttribute('style', 'display: block');
      deleteBtn.setAttribute('style', 'display: block');
    });

    upload.on('progress', progress => {
      progressBar.setAttribute('aria-valuenow', progress.detail);
      progressBar.setAttribute('style', `width: ${progress.detail}%`);
    });

    upload.on('chunkSuccess', (customEvent: CustomEvent) => {
      let data = JSON.parse(customEvent.detail.response.body);
      if (!parseInt(data.file_id)) {
        return;
      }

      progressBar.setAttribute('class', progressBar.getAttribute('class') + ' bg-success');
      deleteBtn.setAttribute('style', 'display: block');

      let file = document.createElement('input');
      file.type = 'hidden';
      file.name = `file_ids[${data.file_id}]`;
      file.id = `file_ids_${data.file_id}`;
      file.className = 'images__file';
      file.value = data.file_id;
      cont.appendChild(file);
    });

    deleteBtn.addEventListener('click', (event: Event) => {
      console.log('delete', event);
      let button = event.currentTarget as HTMLButtonElement;
      button.closest('.images__item').remove();
    });
  },
});
</script>

<style lang="scss">
.images {
  .images__item {
    img {
      width: 100%;
    }
    .images__item-error {
      display: none;
    }
    .images__item-delete {
      display: none;
    }
  }
}
</style>