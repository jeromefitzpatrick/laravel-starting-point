<template>
  <v-text-field
          :value="value"
          @input="e => $emit('input', e)"
          append-icon="mdi-magnify"
          label="Search"
          hide-details
          clearable
          autocomplete="off"
  ></v-text-field>
</template>

<script>
  export default {
    name: "SearchInput",
    props: {
      value: {
        type: String,
        default: ""
      }
    }
  }
</script>

<style scoped>

</style>