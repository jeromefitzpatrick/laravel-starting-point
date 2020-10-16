<template>
  <v-dialog v-model="dialog" persistent max-width="500px">
    <template v-slot:activator="{ on }">
      <v-btn v-if="resource.id" icon text small v-on="on"><v-icon small>mdi-pencil</v-icon></v-btn>
      <v-btn v-else outlined color="primary" dark class="mb-2" v-on="on">Add {{ resourceName }}</v-btn>
    </template>
    <v-card>
      <v-card-title>
        <span class="headline">{{ resource.id ? 'Edit' : 'Add' }} {{ resourceName }}</span>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12">
              <errors
                :submitting="form.submitting"
                :errors="form.errors"
              >
              </errors>
            </v-col>
          </v-row>
          <slot v-bind:form="form">

          </slot>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <div class="flex-grow-1"></div>
        <v-btn color="darken-1" text @click="close">Cancel</v-btn>
        <v-btn v-if="resource.id" color="blue darken-1" text @click="update">Update</v-btn>
        <v-btn v-else color="blue darken-1" text @click="create">Create</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>


<script>
  function defaultForm(el) {
    return {
      errors: {},
      submitting: false
    }
  }

  import {JResourceFormModalMixin} from "./JResourceFormModalMixin";

  export default {
    mixins: [JResourceFormModalMixin],
    props: {
      resourceName: {
        type: String,
        default: "resource"
      },
      url: {
        type: String,
        required: true
      },
      resource: {
        type: Object,
        default: () => { return {} }
      }
    },
    computed: {
      currentUser () {
        return this.$store.state.currentUser
      }
    },
    watch: {
      dialog(newVal,oldVal) {
        if (newVal === true) {
          console.log('resetting')
          this.reset()
        }
      }
    },
    created () {
      this.form.data = { ...this.resource }
      this.form.url = this.resource.id ? this.url + '/' + this.resource.id : this.url
    },
    data () {
      return {
        dialog: false,
        form: {...defaultForm(this)}
      }
    },
    methods:{
      reset() {
        this.form = { ...defaultForm() }
        this.form.data = { ...this.resource }
        this.form.url = this.resource.id ? this.url + '/' + this.resource.id : this.url
      },
    },
  }
</script>
