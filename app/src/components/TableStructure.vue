<template>
  <div class="flex-grow py-6 relative">

    <table-nav :tableid="tableid"></table-nav>

    <div class="w-full flex items-start">

      <div class="relative w-full">
        <table cellspacing="0" class="flex-grow  bg-light-100 relative" style="box-shadow: 0 2px 3px 2px rgba(0,0,0,.03);"
               v-if="columns.length > 1">
          <thead class="bg-dark-500 text-gray-200">
          <tr class="font-normal">
            <th class="sticky top-0 z-20 bg-dark-500 text-gray-200 px-2 py-3"
                v-for="(column_header) in Object.keys(columns[0])">{{ column_header }}
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="column_fields in columns">
            <td class="whitespace-pre px-1 py-1" v-for="column_field in column_fields">{{ column_field }}</td>
          </tr>
          </tbody>
        </table>


      </div>

    </div>

    <br>
  </div>

</template>

<script>

  import axios from 'axios'
  import TableNav from '@/components/TableNav.vue'

  export default {
    name: 'TableStructure',
    props: ['tableid', 'active_database'],
    data() {
      return {
        columns: [],
        endpoint: 'http://localhost/rove/api/table_structure.php?db=',
      }
    },

    components: {
      TableNav
    },

    mounted() {
      this.getAllPosts();
    },

    computed: {},

    watch: {
      // force update on route change
      '$route.params': function () {
        this.$nextTick();
      }
    },

    filters: {
      lowercase: function (string) {
        return string.toLowerCase();
      }
    },

    methods: {
      getAllPosts() {

        let api_url = '';
        if (this.tableid) {
          api_url = this.endpoint + this.active_database + '&tablename=' + this.tableid;
        }

        console.log(api_url);

        let vue_instance = this;

        axios.get(api_url).then(response => {
          this.columns = response.data;

        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  table tbody td {
    border-bottom: 1px solid #edf2f7;
  }

  tbody tr:hover td {
    @apply bg-light-200;
  }

  tbody td:hover {
    @apply bg-highlight-400 !important;
  }

  tbody td:first-child {
    width:      2rem;
    text-align: center;
  }

  .id-field-offset {
    left: 24px;
  }

  .row-data-field {
    @apply flex w-full;
    border-bottom: 1px solid #edf2f7;
  }

  .row-data-field:hover {
    @apply bg-light-200;
  }


</style>
