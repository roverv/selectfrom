<script>

export default {
  name: 'TableDataRow',

  functional: true,

  props: ['row', 'truncateAmount'],

  render: function (createElement, context) {
    let objects   = [];
    let row_array = Object.values(context.props.row);
    for (let key in row_array) {

      let cell_value = row_array[key];

      if(cell_value == null) {
        cell_value = 'NULL';
      }
      else if(cell_value && cell_value.length > context.props.truncateAmount) {
        cell_value = cell_value.replace(/(\r\n|\n|\r)/gm, "");
        cell_value = cell_value.substring(0, context.props.truncateAmount) + '...';
      }

      objects.push(createElement('div', {
            attrs: {
              class: "table-data-row" + (key == 0 ? ' sticky-first-row-cell' : ''),
            },
          },
          [createElement('span', {
            domProps: {
              innerHTML: (cell_value)
            },
            attrs: {
              class: (row_array[key] == null ? 'null-value' : ''),
              title: (cell_value.length > context.props.truncateAmount) ? row_array[key] : '',
            },
          })]
      ));
    }

    return objects;
  },
}
</script>
