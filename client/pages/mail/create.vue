<template>
  <div class="row">
    <div class="col-lg-12 m-0">
      <card :title="$t('create_mail')">
        <form @submit.prevent="create" @keydown="form.onKeydown($event)">
          <!-- From email -->
          <div class="form-group row">
            <label class="col-md-1 col-form-label text-md-right">{{ $t('from_email') }}</label>
            <div class="col-md-11">
              <input v-model="form.from" :class="{ 'is-invalid': form.errors.has('from') }" type="email" name="from" class="form-control" />
              <has-error :form="form" field="from" />
            </div>
          </div>

          <!-- Email -->
          <div class="form-group row">
            <label class="col-md-1 col-form-label text-md-right">{{ $t('to_email') }}</label>
            <div class="col-md-11">
              <input v-model="form.to" :class="{ 'is-invalid': form.errors.has('to') }" type="email" name="to" class="form-control" />
              <has-error :form="form" field="to" />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-1 col-form-label text-md-right">{{ $t('mail_subject') }}</label>
            <div class="col-md-11">
              <input v-model="form.subject" :class="{ 'is-invalid': form.errors.has('subject') }" type="text" name="subject" class="form-control" />
              <has-error :form="form" field="subject" />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-1 col-form-label text-md-right"></label>
            <div class="col-md-11">
              <editor @changed="onTextChange"/>
              <input type="hidden" v-model="form.content" :class="{ 'is-invalid': form.errors.has('content') }" name="content" />
              <has-error :form="form" field="content" />
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-1 col-form-label text-md-right"></label>
            <div class="col-md-11">
              <vue-dropzone
                ref="myVueDropzone" id="dropzone"
                :options="dropzoneOptions"
                @vdropzone-success="fileUploadSuccess"
                @vdropzone-removed-file="vremoved"
                @vdropzone-file-added="uploading = true"
                @vdropzone-error="uploading=false"
              ></vue-dropzone>
              <input v-model="form.files" type="hidden" name="files" />
              <has-error :form="form" field="content" />
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-2 offset-md-1">
              <!-- Submit Button -->
              <v-button :loading="form.busy || uploading" class="btn btn-success">
                {{ $t('send_mail') }}
              </v-button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1 offset-md-1">
              <router-link :to="{ name: 'home' }"><button class="btn btn-primary">Back</button></router-link>
            </div>
          </div>
        </form>
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import vue2Dropzone from 'vue2-dropzone'
import { mapGetters } from 'vuex'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import Editor from '../../components/global/Editor'

export default {
  components: {
    Editor,
    vueDropzone: vue2Dropzone
  },
  middleware: 'auth',
  data: () => ({
    text: '',
    uploading: false,
    form: new Form({
      from: '',
      to: '',
      subject: '',
      content: '',
      files: []
    }),
    dropzoneOptions: {
      url: `${process.env.apiUrl}/attachments`,
      thumbnailWidth: 150,
      maxFilesize: 0.5,
      headers: {},
      addRemoveLinks: true
    }
  }),
  head () {
    return { title: this.$t('create_mail') }
  },
  computed: mapGetters({
    user: 'auth/user',
    token: 'auth/token'
  }),
  mounted () {
    this.dropzoneOptions.headers.Authorization = `Bearer ${this.token}`
  },
  methods: {
    onTextChange (text) {
      this.form.content = text
    },
    async create () {
      // Create mail.
      try {
        await this.form.post('/mail')
      } catch (e) {
        return
      }

      this.$router.push({ name: 'home' })
    },
    fileUploadSuccess (file, response) {
      this.uploading = false
      if (response && response.url) {
        this.form.files = [...this.form.files, {
          id: file.upload.uuid,
          name: file.name,
          url: response.url,
          size: file.size
        }]
      }
    },

    vremoved (file, xhr, formData) {
      this.form.files = this.form.files.filter(f => f.id !== file.upload.uuid)
    }
  }
}
</script>
