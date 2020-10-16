import {JHttpMixin} from "../Http/JHttpMixin";

export const JResourceFormModalMixin = {
  mixins: [JHttpMixin],
  methods:{
    create() {
      this.action('create')
    },
    update() {
      this.action('update')
    },
    action (action) {
      this.beforeSubmit()
      const params = [
        this.form.url,
        this.form.data,
        this.httpSuccess,
        this.httpFailed
      ]
      action === 'create'
        ? this.httpCreate(...params)
        : this.httpUpdate(...params)
    },
    beforeSubmit() {
      this.form.errors = {};
      this.form.submitting = true;
    },
    close () {
      this.dialog = false
      setTimeout(() => {
        this.reset()
      }, 300)
    },
    httpSuccess(data, response) {
      this.$emit(response.status === 201 ? 'create' : 'update', data.data)
      this.close()
    },
    httpFailed(e) {
      this.form.submitting = false
      this.form.errors = e
    }
  }
}
