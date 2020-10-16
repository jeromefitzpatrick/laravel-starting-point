export const JTableConsumerMixin = {
  methods: {
    triggerRefresh() {
      this.tableOptions.refresh = true
    },
    acknowledgeRefresh() {
      this.tableOptions.refresh = false
    }
  }
}
