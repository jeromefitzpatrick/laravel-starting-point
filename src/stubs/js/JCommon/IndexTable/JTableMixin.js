import {JAlertMixin} from "../Alerts/JAlertMixin";

export const JTableMixin = {
  mixins: [JAlertMixin],
  data() {
    return {
      tableOptions: {
        singleExpand: false,
        showExpand: false,
        expanded: [],
        headers: [],
        itemKey: 'id',
        loadingText: 'Fetching records...',
        class: 'elevation-0',
        loading: false,
        perPage: 0
      },
      resourceOptions: {
        items: [],
        serverItemsLength: 0
      },
      apiOptions: {
        itemsPerPage: 10
      },
    }
  },
  watch: {
  },

  methods: {
    _fetchResources: _.debounce(function () {
      if (this.apiOptions.page === 1) {
        this.fetchResources()
      } else {
        this.apiOptions.page = 1 // This triggers a fetch of the resources
      }
    }, 500),

    fetchResources() {
      this.tableOptions.loading = true

      let params = Object.assign({}, this.apiOptions, { search: this.search } )

      this.httpGet(
        this.resourceOptions.url,
        this.resourcesFetched,
        this.resourcesNotFetched,
        { params }
      )
    },
    resourcesFetched(response) {
      this.resourceOptions.items = response.data
      this.resourceOptions.serverItemsLength = response.meta.pagination.total;
      this.tableOptions.loading = false
      this.$emit('refresh')
    },
    resourcesNotFetched(e) {
      this.http.fetching = false
      this.tableOptions.loading = false
      this.$emit('refresh')
      this.snackbarFailure('An error occurred while fetching items', e)
    }
  }
}
