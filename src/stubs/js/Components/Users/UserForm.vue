<template>
  <j-resource-form-modal
    :resource="resource"
    url="/local-api/users"
    resource-name="user"
    @create="(data) => $emit('create', data)"
    @update="(data) => $emit('update', data)">
    <template v-slot:default="slotProps">
      <v-row>
        <v-col cols="12">
          <v-text-field v-model="slotProps.form.data.name" label="Name" autocomplete="new-password"></v-text-field>
        </v-col>
        <v-col cols="12">
          <v-text-field v-model="slotProps.form.data.email" label="Email" autocomplete="new-password"></v-text-field>
        </v-col>
        <v-col cols="12">
          <v-checkbox
            v-model="slotProps.form.data.enabled"
            label="Enabled"
          ></v-checkbox>
        </v-col>
      </v-row>
    </template>
  </j-resource-form-modal>
</template>

<script>
  import JResourceFormModal from "../../JCommon/ResourceForm/JResourceFormModal";
  export default {
    components: {JResourceFormModal},
    props: {
      resource: {
        type: Object,
        default: () => {
          return {
            name: "",
            email: "",
            enabled: false,
            includes: []
          }
        }
      }
    }
  }
</script>
