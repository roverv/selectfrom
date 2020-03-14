export default {
  methods: {
    focusCellNext($event, step) {
      let cell_index = Array.from($event.target.parentNode.children).indexOf($event.target);
      let element    = $event.target.parentElement.getElementsByTagName('td')[cell_index + step];
      if (element) {
        element.focus();
        element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
      }
    },

    focusCellPrevious($event, step) {
      let cell_index  = Array.from($event.target.parentNode.children).indexOf($event.target);
      let cell_offset = (cell_index - step <= 0) ? 0 : cell_index - step;
      let element     = $event.target.parentElement.getElementsByTagName('td')[cell_offset];
      if (element) {
        element.focus();
        element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
      }
    },

    focusRowUp($event, step) {
      let cell_index = Array.from($event.target.parentNode.children).indexOf($event.target);
      let row_index  = Array.from($event.target.parentNode.parentNode.children).indexOf($event.target.parentNode);
      let row_offset = (row_index - step <= 0) ? 0 : row_index - step;
      console.log(row_offset);
      let element = $event.target.parentElement.parentElement.getElementsByTagName('tr')[row_offset].getElementsByTagName('td')[cell_index];
      if (element) {
        element.focus();
        element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
      }
    },

    focusRowDown($event, step) {
      let cell_index = Array.from($event.target.parentNode.children).indexOf($event.target);
      let row_index  = Array.from($event.target.parentNode.parentNode.children).indexOf($event.target.parentNode);
      let row_offset = row_index + step;
      if (row_offset >= $event.target.parentNode.parentNode.children.length - 1) {
        row_offset = $event.target.parentNode.parentNode.children.length - 1;
        // retrieve the next batch of rows, but only if not already fetching (prevents doing multiple ajax requests at the same time)
        if (this.showLoadMoreDataButtons() && this.is_fetching_data === false) {
          this.loadMoreRows();
        }
      }
      let element = $event.target.parentElement.parentElement.getElementsByTagName('tr')[row_offset].getElementsByTagName('td')[cell_index];
      if (element) {
        element.focus();
        element.scrollIntoView({behavior: "auto", block: "center", inline: "center"});
      }
    },

    // remove focus from table cell, so user can use dialog shortcuts again
    unfocusDatatable() {
      document.activeElement.blur();
      document.getElementById('app').focus();
    },
  }
}
