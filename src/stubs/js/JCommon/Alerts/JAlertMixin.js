export const JAlertMixin = {
  methods: {
    snackbarSuccess(text = 'Success', title ='', manualClose = false) {
      this.$notify({
        type: 'success',
        group: 'main',
        title,
        text
      });
    },
    snackbarFailure(text = 'Error', e) {

      let title = 'An error has occurred'
      if (e && e.response.data.errorMsg) {
        text = e.response.data.errorMsg
      }

      if (e.response) {
        switch (e.response.status) {
          case 401:
            title = 'You are not logged in'
            break;
          case 403:
            title = 'You are not authorised'
            break;
          case 404:
            title = 'Not found'
            break;
          case 413:
            title = 'Too large'
            break;
          case 419:
            title = 'Session expired'
            break;
          case 422:
            title = 'Invalid data received'
            break;
        }
      }

      this.$notify({
        type: 'error',
        group: 'main',
        title,
        text
      });
    },
  }
}
