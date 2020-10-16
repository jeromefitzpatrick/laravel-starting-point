<template>
  <span>
    <loading :active.sync="tableOptions.loading" :can-cancel="false" :is-full-page="false"></loading>
    <v-data-table
            :single-expand="tableOptions.singleExpand"
            :show-expand="tableOptions.showExpand"
            :headers="tableOptions.headers"
            :items="resourceOptions.items"
            :item-key="tableOptions.itemKey"
            :loading-text="tableOptions.loadingText"
            :items-per-page="resourceOptions.itemsPerPage"
            :class="tableOptions.class"
            :expanded="tableOptions.expanded"
            :options.sync="apiOptions"
            :server-items-length="resourceOptions.serverItemsLength"
            @update:options="fetchResources"

    >
      <slot v-for="(_, name) in $slots" :name="name" :slot="name" />
      <template v-for="(_, name) in $scopedSlots" :slot="name" slot-scope="slotData"><slot :name="name" v-bind="slotData" /></template>
    </v-data-table>
  </span>

</template>

<script>
  import {JTableMixin} from "./JTableMixin";
  import {JHttpMixin} from "../Http/JHttpMixin";
  import Loading from 'vue-loading-overlay'

  export default {
    mixins: [JHttpMixin, JTableMixin],
    components: {Loading},
    props: {
      refresh: {
        type: Boolean,
        default: false
      },
      search: {
        type: String,
        default: ''
      },
      options: {
        type: Object,
        default: () => { return {} }
      }
    },
    watch: {
      refresh: function (newVal, oldVal) {
        if (newVal === true) {
          this.fetchResources()
        }
      },
      search: function (newVal, oldVal) {
        this._fetchResources()
      }
    },
    created() {
      this.tableOptions = Object.assign({}, this.tableOptions, this.options.table)
      this.resourceOptions = Object.assign({}, this.resourceOptions, this.options.resource)
      this.apiOptions = Object.assign({}, this.apiOptions, this.options.api) // this line kicks off api call
    },
    data () {
      return {

      }
    },
  }
</script>
