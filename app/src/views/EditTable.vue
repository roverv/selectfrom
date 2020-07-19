<template>
  <div class="page-content-container">

    <div class="edit-table-container">

      <div class="table-page-header" v-if="$route.name != 'addtable'">
        <h2>
          {{ tableid }}
        </h2>
        <table-nav :tableid="tableid"></table-nav>
        <div></div>
      </div>

      <h1 class="text-xl mb-4">
        <span v-if="$route.name == 'addtable'">Create new table</span>
        <span v-else>Edit row</span>
      </h1>

      <div v-if="query_result.result == 'error'" class="error-box mb-4">
        {{ query_result.message }}
      </div>

      <form method="post" @submit.prevent="saveRow()" autocomplete="off">

        <div class="mb-8">
          <div class="flex w-full mb-1">

            <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
              <div>Table name</div>
            </div>

            <input type="text" class="default-text-input pl-4 w-64" v-on:keyup.esc="focusToApp">
          </div>

          <div class="flex w-full mb-1">

            <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
              <div>Engine</div>
            </div>

            <select name="Engine" data-enpassusermodified="yes" class="default-select w-64">
              <option value=""></option>
              <option selected="">InnoDB</option>
              <option>MRG_MYISAM</option>
              <option>MEMORY</option>
              <option>BLACKHOLE</option>
              <option>MyISAM</option>
              <option>CSV</option>
              <option>ARCHIVE</option>
              <option>PERFORMANCE_SCHEMA</option>
            </select>
          </div>

          <div class="flex w-full mb-1">

            <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
              <div>Collation</div>
            </div>

            <select name="Collation" class="default-select w-64">
              <option value="">(collation)</option>
              <optgroup label="armscii8">
                <option>armscii8_bin</option>
                <option>armscii8_general_ci</option>
              </optgroup>
              <optgroup label="ascii">
                <option>ascii_bin</option>
                <option>ascii_general_ci</option>
              </optgroup>
              <optgroup label="big5">
                <option>big5_bin</option>
                <option>big5_chinese_ci</option>
              </optgroup>
              <optgroup label="binary">
                <option>binary</option>
              </optgroup>
              <optgroup label="cp1250">
                <option>cp1250_bin</option>
                <option>cp1250_croatian_ci</option>
                <option>cp1250_czech_cs</option>
                <option>cp1250_general_ci</option>
                <option>cp1250_polish_ci</option>
              </optgroup>
              <optgroup label="cp1251">
                <option>cp1251_bin</option>
                <option>cp1251_bulgarian_ci</option>
                <option>cp1251_general_ci</option>
                <option>cp1251_general_cs</option>
                <option>cp1251_ukrainian_ci</option>
              </optgroup>
              <optgroup label="cp1256">
                <option>cp1256_bin</option>
                <option>cp1256_general_ci</option>
              </optgroup>
              <optgroup label="cp1257">
                <option>cp1257_bin</option>
                <option>cp1257_general_ci</option>
                <option>cp1257_lithuanian_ci</option>
              </optgroup>
              <optgroup label="cp850">
                <option>cp850_bin</option>
                <option>cp850_general_ci</option>
              </optgroup>
              <optgroup label="cp852">
                <option>cp852_bin</option>
                <option>cp852_general_ci</option>
              </optgroup>
              <optgroup label="cp866">
                <option>cp866_bin</option>
                <option>cp866_general_ci</option>
              </optgroup>
              <optgroup label="cp932">
                <option>cp932_bin</option>
                <option>cp932_japanese_ci</option>
              </optgroup>
              <optgroup label="dec8">
                <option>dec8_bin</option>
                <option>dec8_swedish_ci</option>
              </optgroup>
              <optgroup label="eucjpms">
                <option>eucjpms_bin</option>
                <option>eucjpms_japanese_ci</option>
              </optgroup>
              <optgroup label="euckr">
                <option>euckr_bin</option>
                <option>euckr_korean_ci</option>
              </optgroup>
              <optgroup label="gb18030">
                <option>gb18030_bin</option>
                <option>gb18030_chinese_ci</option>
                <option>gb18030_unicode_520_ci</option>
              </optgroup>
              <optgroup label="gb2312">
                <option>gb2312_bin</option>
                <option>gb2312_chinese_ci</option>
              </optgroup>
              <optgroup label="gbk">
                <option>gbk_bin</option>
                <option>gbk_chinese_ci</option>
              </optgroup>
              <optgroup label="geostd8">
                <option>geostd8_bin</option>
                <option>geostd8_general_ci</option>
              </optgroup>
              <optgroup label="greek">
                <option>greek_bin</option>
                <option>greek_general_ci</option>
              </optgroup>
              <optgroup label="hebrew">
                <option>hebrew_bin</option>
                <option>hebrew_general_ci</option>
              </optgroup>
              <optgroup label="hp8">
                <option>hp8_bin</option>
                <option>hp8_english_ci</option>
              </optgroup>
              <optgroup label="keybcs2">
                <option>keybcs2_bin</option>
                <option>keybcs2_general_ci</option>
              </optgroup>
              <optgroup label="koi8r">
                <option>koi8r_bin</option>
                <option>koi8r_general_ci</option>
              </optgroup>
              <optgroup label="koi8u">
                <option>koi8u_bin</option>
                <option>koi8u_general_ci</option>
              </optgroup>
              <optgroup label="latin1">
                <option>latin1_bin</option>
                <option>latin1_danish_ci</option>
                <option>latin1_general_ci</option>
                <option>latin1_general_cs</option>
                <option>latin1_german1_ci</option>
                <option>latin1_german2_ci</option>
                <option>latin1_spanish_ci</option>
                <option selected="">latin1_swedish_ci</option>
              </optgroup>
              <optgroup label="latin2">
                <option>latin2_bin</option>
                <option>latin2_croatian_ci</option>
                <option>latin2_czech_cs</option>
                <option>latin2_general_ci</option>
                <option>latin2_hungarian_ci</option>
              </optgroup>
              <optgroup label="latin5">
                <option>latin5_bin</option>
                <option>latin5_turkish_ci</option>
              </optgroup>
              <optgroup label="latin7">
                <option>latin7_bin</option>
                <option>latin7_estonian_cs</option>
                <option>latin7_general_ci</option>
                <option>latin7_general_cs</option>
              </optgroup>
              <optgroup label="macce">
                <option>macce_bin</option>
                <option>macce_general_ci</option>
              </optgroup>
              <optgroup label="macroman">
                <option>macroman_bin</option>
                <option>macroman_general_ci</option>
              </optgroup>
              <optgroup label="sjis">
                <option>sjis_bin</option>
                <option>sjis_japanese_ci</option>
              </optgroup>
              <optgroup label="swe7">
                <option>swe7_bin</option>
                <option>swe7_swedish_ci</option>
              </optgroup>
              <optgroup label="tis620">
                <option>tis620_bin</option>
                <option>tis620_thai_ci</option>
              </optgroup>
              <optgroup label="ucs2">
                <option>ucs2_bin</option>
                <option>ucs2_croatian_ci</option>
                <option>ucs2_czech_ci</option>
                <option>ucs2_danish_ci</option>
                <option>ucs2_esperanto_ci</option>
                <option>ucs2_estonian_ci</option>
                <option>ucs2_general_ci</option>
                <option>ucs2_general_mysql500_ci</option>
                <option>ucs2_german2_ci</option>
                <option>ucs2_hungarian_ci</option>
                <option>ucs2_icelandic_ci</option>
                <option>ucs2_latvian_ci</option>
                <option>ucs2_lithuanian_ci</option>
                <option>ucs2_persian_ci</option>
                <option>ucs2_polish_ci</option>
                <option>ucs2_roman_ci</option>
                <option>ucs2_romanian_ci</option>
                <option>ucs2_sinhala_ci</option>
                <option>ucs2_slovak_ci</option>
                <option>ucs2_slovenian_ci</option>
                <option>ucs2_spanish2_ci</option>
                <option>ucs2_spanish_ci</option>
                <option>ucs2_swedish_ci</option>
                <option>ucs2_turkish_ci</option>
                <option>ucs2_unicode_520_ci</option>
                <option>ucs2_unicode_ci</option>
                <option>ucs2_vietnamese_ci</option>
              </optgroup>
              <optgroup label="ujis">
                <option>ujis_bin</option>
                <option>ujis_japanese_ci</option>
              </optgroup>
              <optgroup label="utf16">
                <option>utf16_bin</option>
                <option>utf16_croatian_ci</option>
                <option>utf16_czech_ci</option>
                <option>utf16_danish_ci</option>
                <option>utf16_esperanto_ci</option>
                <option>utf16_estonian_ci</option>
                <option>utf16_general_ci</option>
                <option>utf16_german2_ci</option>
                <option>utf16_hungarian_ci</option>
                <option>utf16_icelandic_ci</option>
                <option>utf16_latvian_ci</option>
                <option>utf16_lithuanian_ci</option>
                <option>utf16_persian_ci</option>
                <option>utf16_polish_ci</option>
                <option>utf16_roman_ci</option>
                <option>utf16_romanian_ci</option>
                <option>utf16_sinhala_ci</option>
                <option>utf16_slovak_ci</option>
                <option>utf16_slovenian_ci</option>
                <option>utf16_spanish2_ci</option>
                <option>utf16_spanish_ci</option>
                <option>utf16_swedish_ci</option>
                <option>utf16_turkish_ci</option>
                <option>utf16_unicode_520_ci</option>
                <option>utf16_unicode_ci</option>
                <option>utf16_vietnamese_ci</option>
              </optgroup>
              <optgroup label="utf16le">
                <option>utf16le_bin</option>
                <option>utf16le_general_ci</option>
              </optgroup>
              <optgroup label="utf32">
                <option>utf32_bin</option>
                <option>utf32_croatian_ci</option>
                <option>utf32_czech_ci</option>
                <option>utf32_danish_ci</option>
                <option>utf32_esperanto_ci</option>
                <option>utf32_estonian_ci</option>
                <option>utf32_general_ci</option>
                <option>utf32_german2_ci</option>
                <option>utf32_hungarian_ci</option>
                <option>utf32_icelandic_ci</option>
                <option>utf32_latvian_ci</option>
                <option>utf32_lithuanian_ci</option>
                <option>utf32_persian_ci</option>
                <option>utf32_polish_ci</option>
                <option>utf32_roman_ci</option>
                <option>utf32_romanian_ci</option>
                <option>utf32_sinhala_ci</option>
                <option>utf32_slovak_ci</option>
                <option>utf32_slovenian_ci</option>
                <option>utf32_spanish2_ci</option>
                <option>utf32_spanish_ci</option>
                <option>utf32_swedish_ci</option>
                <option>utf32_turkish_ci</option>
                <option>utf32_unicode_520_ci</option>
                <option>utf32_unicode_ci</option>
                <option>utf32_vietnamese_ci</option>
              </optgroup>
              <optgroup label="utf8">
                <option>utf8_bin</option>
                <option>utf8_croatian_ci</option>
                <option>utf8_czech_ci</option>
                <option>utf8_danish_ci</option>
                <option>utf8_esperanto_ci</option>
                <option>utf8_estonian_ci</option>
                <option>utf8_general_ci</option>
                <option>utf8_general_mysql500_ci</option>
                <option>utf8_german2_ci</option>
                <option>utf8_hungarian_ci</option>
                <option>utf8_icelandic_ci</option>
                <option>utf8_latvian_ci</option>
                <option>utf8_lithuanian_ci</option>
                <option>utf8_persian_ci</option>
                <option>utf8_polish_ci</option>
                <option>utf8_roman_ci</option>
                <option>utf8_romanian_ci</option>
                <option>utf8_sinhala_ci</option>
                <option>utf8_slovak_ci</option>
                <option>utf8_slovenian_ci</option>
                <option>utf8_spanish2_ci</option>
                <option>utf8_spanish_ci</option>
                <option>utf8_swedish_ci</option>
                <option>utf8_turkish_ci</option>
                <option>utf8_unicode_520_ci</option>
                <option>utf8_unicode_ci</option>
                <option>utf8_vietnamese_ci</option>
              </optgroup>
              <optgroup label="utf8mb4">
                <option>utf8mb4_bin</option>
                <option>utf8mb4_croatian_ci</option>
                <option>utf8mb4_czech_ci</option>
                <option>utf8mb4_danish_ci</option>
                <option>utf8mb4_esperanto_ci</option>
                <option>utf8mb4_estonian_ci</option>
                <option>utf8mb4_general_ci</option>
                <option>utf8mb4_german2_ci</option>
                <option>utf8mb4_hungarian_ci</option>
                <option>utf8mb4_icelandic_ci</option>
                <option>utf8mb4_latvian_ci</option>
                <option>utf8mb4_lithuanian_ci</option>
                <option>utf8mb4_persian_ci</option>
                <option>utf8mb4_polish_ci</option>
                <option>utf8mb4_roman_ci</option>
                <option>utf8mb4_romanian_ci</option>
                <option>utf8mb4_sinhala_ci</option>
                <option>utf8mb4_slovak_ci</option>
                <option>utf8mb4_slovenian_ci</option>
                <option>utf8mb4_spanish2_ci</option>
                <option>utf8mb4_spanish_ci</option>
                <option>utf8mb4_swedish_ci</option>
                <option>utf8mb4_turkish_ci</option>
                <option>utf8mb4_unicode_520_ci</option>
                <option>utf8mb4_unicode_ci</option>
                <option>utf8mb4_vietnamese_ci</option>
              </optgroup>
            </select>
          </div>

          <div class="flex w-full mb-1">

            <div class="bg-dark-400 flex justify-between items-center w-64 pl-3 flex-shrink-0 relative flex-wrap mr-2">
              <div>Comment</div>
            </div>

            <input type="text" class="default-text-input pl-4 w-64" v-on:keyup.esc="focusToApp">
          </div>

        </div>

        <h2 class="mb-3 text-lg">Columns</h2>


        <div class="columns-table mb-8">
          <span class="head">Name</span>
          <span class="head">Type</span>
          <span class="head">Length</span>
          <span class="head">Attributes</span>
          <span class="head">NULL</span>
          <span class="head">Auto increment</span>
          <span class="head">Default value</span>
          <span class="head">Comment</span>
          <span class=""></span>


          <template v-for="(column_row, index) in column_rows">
            <div class="columns-table-cell">
              <input type="text" v-model="column_row.name" class="default-text-input pl-2 w-48"
                     v-on:keyup.esc="focusToApp">
            </div>

            <div class="columns-table-cell">
              <select class="default-select w-32" v-model="column_row.type">
                <optgroup label="Getallen">
                  <option>tinyint</option>
                  <option>smallint</option>
                  <option>mediumint</option>
                  <option selected="">int</option>
                  <option>bigint</option>
                  <option>decimal</option>
                  <option>float</option>
                  <option>double</option>
                </optgroup>
                <optgroup label="Datum en tijd">
                  <option>date</option>
                  <option>datetime</option>
                  <option>timestamp</option>
                  <option>time</option>
                  <option>year</option>
                </optgroup>
                <optgroup label="Tekst">
                  <option>char</option>
                  <option>varchar</option>
                  <option>tinytext</option>
                  <option>text</option>
                  <option>mediumtext</option>
                  <option>longtext</option>
                  <option>json</option>
                </optgroup>
                <optgroup label="Lijsten">
                  <option>enum</option>
                  <option>set</option>
                </optgroup>
                <optgroup label="Binaire gegevens">
                  <option>bit</option>
                  <option>binary</option>
                  <option>varbinary</option>
                  <option>tinyblob</option>
                  <option>blob</option>
                  <option>mediumblob</option>
                  <option>longblob</option>
                </optgroup>
                <optgroup label="Geometrie">
                  <option>geometry</option>
                  <option>point</option>
                  <option>linestring</option>
                  <option>polygon</option>
                  <option>multipoint</option>
                  <option>multilinestring</option>
                  <option>multipolygon</option>
                  <option>geometrycollection</option>
                </optgroup>
              </select>
            </div>


            <div class="columns-table-cell ">
              <input type="text" v-model="column_row.length" class="default-text-input pl-2 w-16"
                     v-on:keyup.esc="focusToApp">
            </div>

            <div class="columns-table-cell">
              <select class="default-select w-32" v-model="column_row.attribute">
                <option></option>
                <option selected="">unsigned</option>
                <option>zerofill</option>
                <option>unsigned zerofill</option>
              </select>
            </div>

            <div class="columns-table-cell justify-center">
              <label class="custom-checkbox">
                <input type="checkbox" value="1" class="hidden" autocomplete="off" v-model="column_row.null">
                <span class="input-box"></span>
              </label>
            </div>

            <div class="columns-table-cell justify-center">
              <label class="custom-checkbox">
                <input type="checkbox" value="1" class="hidden" autocomplete="off" v-model="column_row.auto_increment">
                <span class="input-box"></span>
              </label>
            </div>

            <div class="columns-table-cell">
              <label class="custom-checkbox">
                <input type="checkbox" value="1" class="hidden" autocomplete="off"
                       v-model="column_row.has_default_value">
                <span class="input-box"></span>
              </label>
              <input type="text" class="default-text-input pl-2 w-24" v-on:keyup.esc="focusToApp"
                     v-model="column_row.default_value">
            </div>

            <div class="columns-table-cell">
              <input type="text" class="default-text-input pl-2 w-32" v-on:keyup.esc="focusToApp"
                     v-model="column_row.comment">
            </div>

            <div class="flex items-start pt-1">
              <a @click="addColumn(index)" class="btn icon ml-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current">
                  <path class="text-light-300" fill-rule="evenodd"
                        d="M17 11a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4h4z"></path>
                </svg>
              </a>
              <a @click="removeColumn(index)" class="btn icon ml-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 fill-current"
                     style="transform: rotate(45deg);">
                  <path class="text-light-300" fill-rule="evenodd"
                        d="M17 11a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4h4z"></path>
                </svg>
              </a>
            </div>

          </template>

        </div>


      </form>

      <div class="flex justify-center">
        <button class="btn" @click="saveRow()">Save</button>
      </div>

    </div>
  </div>
</template>

<script>

  import axios from "axios";
  import TableNav from '@/components/TableNav.vue'
  import sqlFormatter from "sql-formatter";
  import HandleApiError from '@/mixins/HandleApiError.js'
  import {clone} from '../util'

  var default_column_row = {
    name: '',
    type: '',
    length: '',
    attribute: '',
    null: false,
    auto_increment: false,
    has_default_value: false,
    default_value: '',
    comment: '',
  };

  export default {
    name: 'editrow',
    props: ['tableid'],
    data() {
      return {
        endpoint_create_table: 'createtable.php?db=',
        query_result: {},
        column_rows: [clone(default_column_row)],
      }
    },

    components: {
      TableNav,
    },

    mixins: [
      HandleApiError
    ],

    mounted() {
      // this.getTableStructure();
      // if(this.$route.name == 'editrow') {
      //   this.getRowData();
      // }

    },

    computed: {
      active_database() {
        return this.$store.state.activeDatabase;
      },
      api_endpoint() {
        return this.$store.state.apiEndPoint;
      },
    },

    filters: {
      format: function (query_string) {
        return sqlFormatter.format(query_string);
      }
    },

    methods: {

      focusToApp() {
        document.getElementById('app').focus();
      },

      addColumn(from_index) {
        let new_column_row = clone(default_column_row);
        this.column_rows.splice(from_index + 1, 0, new_column_row);
      },

      removeColumn(index) {
        this.column_rows.splice(index, 1);
        if(this.column_rows.length == 0)  {
          this.addColumn(-1);
        }
      }

    }
  }
</script>

<style>

  .columns-table {
    display:               grid;
    grid-template-columns: repeat(9, auto);
  }

  .columns-table .head {
    @apply bg-dark-400 py-3 px-2 mb-3 font-bold;
  }

  .columns-table-cell {
    @apply flex items-center mb-2 border-b border-light-100 pb-2 px-2;
  }


  .edit-table-container {
    min-width: 1100px;
  }

</style>
