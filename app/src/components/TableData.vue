<template>
  <div class="flex-grow py-6 relative">
    <div class="flex justify-between items-center mb-2">
      <h2 class=" text-gray-700 text-xl">{{ tableid }}</h2>
      <a href="">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-6 fill-current text-gray-400">
          <path class="secondary"
                d="M9.53 16.93a1 1 0 0 1-1.45-1.05l.47-2.76-2-1.95a1 1 0 0 1 .55-1.7l2.77-.4 1.23-2.51a1 1 0 0 1 1.8 0l1.23 2.5 2.77.4a1 1 0 0 1 .55 1.71l-2 1.95.47 2.76a1 1 0 0 1-1.45 1.05L12 15.63l-2.47 1.3z" />
        </svg>
      </a>
    </div>

    <router-link :to="{ name: 'structure', params: { tableid: tableid } }">
      structure
    </router-link>

    <router-link :to="{ name: 'table', params: { tableid: tableid } }">
      data
    </router-link>

    <div class="w-full flex items-start">


      <div class="relative w-full">
      <table cellspacing="0" class="flex-grow w-full bg-white relative" style="box-shadow: 0 2px 3px 2px rgba(0,0,0,.03);"
             v-if="tabledata.length > 1">
        <thead class="bg-gray-700 text-gray-200 text-left">
        <tr class="font-normal">
          <th class="sticky top-0 z-20 bg-gray-700 text-gray-200 py-3">
            <input type='checkbox' id='all-page' class="mx-3" />
          </th>
          <th :name="column_header.Field" :id="column_header.Field | lowercase"
              class="sticky top-0 z-20 bg-gray-700 text-gray-200 px-2" v-for="column_header in columns"
              :class="{ ' highlight' : (column_header.Field.toLowerCase() == column)}">
            {{ column_header.Field }}
          </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="row in tabledata">
          <td class="sticky bg-white left-0 z-10 w-12 text-center"><input type='checkbox' name='check[]' value=''></td>
          <td class="whitespace-pre px-1 py-1" v-for="(column_name, index) in columns"
              :class="{ ' bg-white sticky id-field-offset z-10' : (index == 0)}"
          >{{ row[column_name.Field] }}</td>
        </tr>
        </tbody>
      </table>

      <div class="row-actions sticky bottom-0 left-0  z-30 w-full hidden" v-if="tabledata.length > 1">
        <div class="py-5 px-4 border-t-2 border-b-2 border-orange-400 bg-orange-100 flex items-center">

          <div class="font-bold mr-6">
            5 rows
          </div>

          <a
            class="border-2 border-gray-600 bg-gray-300 text-gray-800 px-4 rounded-full py-2 font-semibold mr-3 inline-flex items-center"
            href="">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mr-2 fill-current text-gray-600">
              <path class="primary"
                    d="M4 14a1 1 0 0 1 .3-.7l11-11a1 1 0 0 1 1.4 0l3 3a1 1 0 0 1 0 1.4l-11 11a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-3z" />
              <rect width="20" height="2" x="2" y="20" class="secondary" rx="1" />
            </svg>
            <span>Edit</span>
          </a>

          <a
            class="border-2 border-gray-600 bg-gray-300 text-gray-800 px-4 rounded-full py-2 font-semibold mr-3 inline-flex items-center"
            href="">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mr-2 fill-current text-gray-600">
              <rect width="14" height="14" x="3" y="3" class="secondary" rx="2" />
              <rect width="14" height="14" x="7" y="7" class="primary" rx="2" />
            </svg>
            <span>Duplicate</span>
          </a>

          <a
            class="border-2 border-gray-600 bg-gray-300 text-gray-800 px-4 rounded-full py-2 font-semibold mr-3 inline-flex items-center"
            href="">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mr-2 fill-current text-gray-600">
              <path class="primary"
                    d="M5 5h14l-.89 15.12a2 2 0 0 1-2 1.88H7.9a2 2 0 0 1-2-1.88L5 5zm5 5a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1zm4 0a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1z" />
              <path class="secondary"
                    d="M8.59 4l1.7-1.7A1 1 0 0 1 11 2h2a1 1 0 0 1 .7.3L15.42 4H19a1 1 0 0 1 0 2H5a1 1 0 1 1 0-2h3.59z" />
            </svg>
            <span>Delete</span>
          </a>

          <a
            class="border-2 border-gray-600 bg-gray-300 text-gray-800 px-4 rounded-full py-2 font-semibold mr-3 inline-flex items-center"
            href="">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 mr-2 fill-current text-gray-600">
              <path class="primary"
                    d="M6 2h6v6c0 1.1.9 2 2 2h6v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2zm2 11a1 1 0 0 0 0 2h8a1 1 0 0 0 0-2H8zm0 4a1 1 0 0 0 0 2h4a1 1 0 0 0 0-2H8z" />
              <polygon class="secondary" points="14 2 20 8 14 8" />
            </svg>
            <span>Export</span>
          </a>

        </div>
      </div>

      </div>

    </div>

    <div class="flex w-full bg-white p-3" v-if="tabledata.length == 1">
      <div class="w-1/2" v-for="columns_half in columns_halved">
        <div class="row-data-field" v-for="column in columns_half">
          <div class="w-48 text-right"><strong>{{ column.Field }}</strong></div>
          <div class="ml-4">{{ tabledata[0][column.Field] }}</div>
        </div>
      </div>
    </div>
    <br>
  </div>

</template>

<script>

  import axios from 'axios'

  export default {
    name: 'TableData',
    props: ['tableid', 'column', 'value', 'active_database'],
    data() {
      return {
        tabledata: [],
        columns: [],
        endpoint: 'http://localhost/rove/api/tabledata.php?db=',
      }
    },

    mounted() {
      console.log(this.tableid);
      this.getAllPosts();
      this.saveRecentTable();
    },

    computed: {
      columns_halved: function () {
        let halfwayThrough = Math.floor(this.columns.length / 2)
        let arrayFirstHalf = this.columns.slice(0, halfwayThrough);
        let arraySecondHalf = this.columns.slice(halfwayThrough, this.columns.length);
        return [arrayFirstHalf, arraySecondHalf];
      }
    },

    watch: {
      // force update on route change
      '$route.params': function () {
        this.$nextTick();
      }
    },

    filters: {
      lowercase: function(string) {
        return string.toLowerCase();
      }
    },

    methods: {
      getAllPosts() {

        let api_url = '';
        if (this.tableid) {
          api_url = this.endpoint + this.active_database + '&tablename=' + this.tableid;
        }
        if (this.column && this.value) {
          api_url += '&column=' + this.column + '&value=' + this.value;
        }

        console.log(api_url);

        let vue_instance = this;

        axios.get(api_url).then(response => {
          this.tabledata = response.data.data;
          this.columns   = response.data.columns;
          this.$nextTick().then(function () {
            // DOM updated
            if(vue_instance.column) {
              console.log(vue_instance.column);
              vue_instance.gotocolumn(vue_instance.column);
            }
          });

        }).catch(error => {
          console.log('-----error-------');
          console.log(error);
        })
      },

      gotocolumn(field_id) {
        var element = document.getElementById(field_id);
        if(element) {
          element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
        }
      },

      saveRecentTable() {
        console.log(' tot hier ');

        let recent_tables = [];
        if(sessionStorage.getItem('recent_tables')) {
          recent_tables = JSON.parse(sessionStorage.getItem('recent_tables'));
        }

        console.log(recent_tables.indexOf(this.tableid));

        if(recent_tables.indexOf(this.tableid)) {
          recent_tables.splice(recent_tables.indexOf(this.tableid), 1 );
        }

        recent_tables.unshift(this.tableid);
        sessionStorage.setItem('recent_tables', JSON.stringify(recent_tables));
      }

    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  table tbody td {
    border-bottom: 1px solid #edf2f7;
  }

  tbody tr:hover td {
    @apply bg-gray-200;
  }

  tbody td:hover {
    @apply bg-orange-200 !important;
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
    background: #e2e8f0;
  }

  .highlight {
    @apply bg-orange-300 text-white;
  }


</style>
