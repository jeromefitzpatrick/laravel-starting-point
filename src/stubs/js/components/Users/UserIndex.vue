<template>
  <v-container>
    <div class="clearfix">
      <div class="float-right">
        <user-form @create="triggerRefresh"></user-form>
      </div>
    </div>
    <v-card outlined>
      <v-card-title>
        {{ resourceConfig.namePlural }}
        <div class="flex-grow-1"></div>
        <j-search-input v-model="tableOptions.api.search"></j-search-input>
      </v-card-title>
      <v-card-text>
        <j-table
                :options="tableOptions"
                :refresh="tableOptions.refresh"
                :search="tableOptions.api.search"
                @refresh="acknowledgeRefresh"
        >
          <template v-slot:item.enabled="{item}">
            <v-icon v-if="item.enabled" color="success" class="mr-2">mdi-account</v-icon>
            <v-icon v-else class="mr-2">mdi-account-off</v-icon>
          </template>
          <template v-slot:item.action="{ item }">
            <user-form :resource="{...item}" @update="triggerRefresh"></user-form>
          </template>
        </j-table>
      </v-card-text>
    </v-card>
  </v-container>
</template>


<script>
  import UserForm from "./UserForm";
  import JTable from "../../JCommon/IndexTable/JTable";
  import JSearchInput from "../../JCommon/Inputs/JSearchInput";
  import {JTableConsumerMixin} from "../../JCommon/IndexTable/JTableConsumerMixin";

  export default {
    name: 'UserIndex',
    mixins: [JTableConsumerMixin],
    components: {JSearchInput, UserForm, JTable},
    data() {
      return {
        resourceConfig: {
          name: 'User',
          namePlural: 'Users',
        },
        tableOptions: {
          refresh: false,
          table: {
            headers: [
              { text: 'ID', value: 'id', width: '5%' },
              { text: 'Name', value: 'name', width: '25%' },
              { text: 'Email', value: 'email', width: '25%' },
              { text: 'Enabled', value: 'enabled' },
              { text: 'Actions', align: 'right', value: 'action', sortable: false }
            ],
          },
          resource: {
            url: '/local-api/users',
          },
          api: {
            sortBy: ['enabled', 'name'],
            sortDesc: [true, false],
            includes: [],
            search: ""
          }
        }
      }
    }
  }
</script>
