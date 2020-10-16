export const JHttpMixin = {
  data() {
    return {
      http: {
        fetching: false,
        action: null,
        resetState: false
      }
    }
  },
  computed: {
    submitting() {
      return !!this.http.action
    }
  },

  methods: {
    httpGet(url, successHandler, failureHandler, options = {}) {
      this.$http
        .get(url, options)
        .then((response) => {
          if (response.status === 200) {
            successHandler(response.data)
          }
        })
        .catch((e) => {
          failureHandler(e)
        })
    },
    httpCreate(url, payload, successHandler, failureHandler, options = {}) {
      this.$http
        .post(url, payload, options)
        .then((response) => {
          if (response.status === 201) {
            successHandler(response.data, response)
          }
        })
        .catch((e) => {
          failureHandler(e)
        })
    },
    httpUpdate(url, payload, successHandler, failureHandler) {
      this.$http
        .patch(url, payload)
        .then((response) => {
          if (response.status === 200 || response.status === 204) {
            successHandler(response.data, response)
          }
        })
        .catch((e) => {
          failureHandler(e)
        })
    },
    httpDelete(url, successHandler, failureHandler) {
      this.$http
        .delete(url)
        .then((response) => {
          if (response.status === 200) {
            successHandler(response.data)
          }
          if (response.status === 204) {
            successHandler()
          }
        })
        .catch((e) => {
          failureHandler(e)
        })
    },
    resetHttpState() {
      this.http.resetState = true
    }
  }
}
