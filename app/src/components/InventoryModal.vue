<template>

  <div class="modal-container" :class="{ 'open' : modalisopen }">

    <div class="modal-content scroll-bar bg-dark-400 border border-highlight-100 w-auto px-5">

      <div>
        <h3 class="modal-title bg-dark-400 mt-2 mb-1 px-0 font-bold">
          Inventory
        </h3>
        <hr class="border-light-100 mb-4">

      </div>


      <div v-if="inventory_items.length == 0">
        <p>
          The inventory allows you to view and compare data from any table or query result.
          <br>
          You can add rows by selecting them and use "Add to inventory" in the action menu.
          <br>
          Their columns and data will appear here.
        </p>
      </div>

      <div class="inventory-items" v-if="inventory_items.length > 0">

        <div class=" pr-1 mr-4 inventory-item scroll-bar" v-for="(inventory_item, item_index) in inventory_items">
          <div
              class="bg-dark-600 border-b-2 border-light-300 font-bold py-2 flex items-center text-center justify-center w-full">
            <div>{{ inventory_item.table }}</div>
            <a @click="deleteItem(item_index)" class="btn btn-icon ml-2">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 fill-current">
                <path class="text-light-200"
                      d="M5 5h14l-.89 15.12a2 2 0 0 1-2 1.88H7.9a2 2 0 0 1-2-1.88L5 5zm5 5a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1zm4 0a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1z"></path>
                <path class="text-light-300"
                      d="M8.59 4l1.7-1.7A1 1 0 0 1 11 2h2a1 1 0 0 1 .7.3L15.42 4H19a1 1 0 0 1 0 2H5a1 1 0 1 1 0-2h3.59z"></path>
              </svg>
            </a>
          </div>

          <div class="column-row"
               :style="{ gridTemplateColumns: 'repeat('+(inventory_item.rows.length+1)+', minmax(150px, 1fr))' }"
               v-if="inventory_item.rows.length > 1">
            <div class="header bg-dark-600 pl-3"></div>
            <div class="bg-light-100 border-b border-light-300"
                 v-for="(row, row_index) in inventory_item.rows">
              <div class="flex py-2">
                <a @click="deleteRow(item_index, row_index)" class="ml-3">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 fill-current">
                    <path class="text-light-200"
                          d="M5 5h14l-.89 15.12a2 2 0 0 1-2 1.88H7.9a2 2 0 0 1-2-1.88L5 5zm5 5a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1zm4 0a1 1 0 0 0-1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0-1-1z"></path>
                    <path class="text-light-300"
                          d="M8.59 4l1.7-1.7A1 1 0 0 1 11 2h2a1 1 0 0 1 .7.3L15.42 4H19a1 1 0 0 1 0 2H5a1 1 0 1 1 0-2h3.59z"></path>
                  </svg>
                </a>
              </div>
            </div>
          </div>

          <div class="column-row"
               :style="{ gridTemplateColumns: 'repeat('+(inventory_item.rows.length+1)+', minmax(150px, 1fr))' }"
               v-for="(column, column_index) in inventory_item.columns">
            <div class="header bg-dark-600 pl-3" style="padding-top: 2px; padding-bottom: 2px;">
              <div class="text-gray-300 mr-6"><a>
                {{ column }}
              </a>
              </div>
            </div>
            <div class="data bg-light-100 border-b border-light-300 pr-3 py-1 text-left"
                 v-for="(row, row_index) in inventory_item.rows"
                 style="padding-top: 2px; padding-bottom: 2px;">
              <div class="ml-3 overflow-hidden data-value">
                <span>{{ row[column_index] }}</span>
              </div>
            </div>
          </div>

        </div>

      </div>

      <div class="flex flex-col justify-center w-full my-6">
        <div class="text-center">
          <a @click="close()" class="btn light">Close</a>
        </div>
      </div>
    </div>

  </div>
</template>

<script>

export default {
  name: 'InventoryModal',
  props: ['modalisopen'],
  data() {
    return {}
  },

  created() {
    document.addEventListener('keydown', this.triggerKeyDown);
  },

  beforeDestroy() {
    document.removeEventListener('keydown', this.triggerKeyDown);
  },

  computed: {
    active_database() {
      return this.$store.state.activeDatabase;
    },

    inventory_items() {
      return this.$store.getters["inventory/items"];
    },
  },

  methods: {

    triggerKeyDown: function (evt) {
      if (evt.key === 'Escape') {
        this.close();
        evt.preventDefault();
      }
    },

    close() {
      this.$emit('closeinventory');
    },

    deleteItem(item_index) {
      this.$store.commit('inventory/REMOVE_TABLE', this.inventory_items[item_index].table);
    },

    deleteRow(item_index, row_index) {
      this.$store.commit('inventory/REMOVE_ROW', {item_index: item_index, row_index: row_index});
    },

  },

}
</script>


<style scoped>
.modal-content {
  max-height:   calc(100vh - 100px);
  margin-right: 50px;
  margin-left:  50px;
  overflow-y:   hidden;
  overflow-x:   auto;
}

.inventory-items {
  display: flex;
}

.inventory-item {
  height:     calc(100vh - 250px);
  overflow-y: scroll;
  min-width:  350px;
}

div.column-row {
  display: grid;
}

.data-value {
  @apply truncate;
}

.data-value:hover {
  overflow:      visible;
  text-overflow: unset;
  white-space:   normal;
}

</style>
