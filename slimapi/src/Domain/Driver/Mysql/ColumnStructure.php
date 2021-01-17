<?php

declare(strict_types=1);

namespace App\Domain\Driver\Mysql;

use App\Application\Helpers\QueryHelper;

class ColumnStructure implements \JsonSerializable
{

    private $index;
    private $name;
    private $original_field_name;
    private $type;
    private $length;
    private $attribute; // unsigned/zerofill & collation
    private $null;
    private $has_default_value;
    private $default_value;
    private $comment;
    private $is_auto_increment;
    private $after_column;

    private function __construct()
    {
    }

    /**
     * @param $column_index
     * @param $column_data
     * @param $data_type_attributes
     * @return ColumnStructure
     */
    public static function createFromDatabase($column_index, $column_data, $data_type_attributes)
    {
        $column_structure        = new self;
        $column_structure->index = $column_index;

        preg_match(
          '~^([^( ]+)(?:\\((.+)\\))?( unsigned)?( zerofill)?$~',
          $column_data["Type"],
          $column_type_matches
        );

        $column_data_type = $column_type_matches[1];
        $column_attribute = '';
        if (isset($data_type_attributes[$column_data_type]) && $data_type_attributes[$column_data_type] === 'numeric') {
            // zerofill & unsigned
            if (!empty($column_type_matches[3])) {
                $column_attribute .= str_replace(' ', '', $column_type_matches[3]);
            }
            if (!empty($column_type_matches[4])) {
                $column_attribute .= ' '.str_replace(' ', '', $column_type_matches[4]);
            }
        } elseif (isset($data_type_attributes[$column_data_type]) && $data_type_attributes[$column_data_type] === 'collation') {
            $column_attribute = $column_data["Collation"];
        }

        $column_structure->name              = $column_data["Field"];
        $column_structure->type              = $column_data_type;
        $column_structure->length            = (isset($column_type_matches[2])) ? (int)$column_type_matches[2] : '';
        $column_structure->attribute         = $column_attribute;
        $column_structure->null              = ($column_data["Null"] === "YES") ? true : false;
        $column_structure->has_default_value = ($column_data["Default"] === null) ? false : true;
        $column_structure->default_value     = $column_data["Default"];
        $column_structure->comment           = $column_data["Comment"];

        $column_structure->is_auto_increment = false;
        if ($column_data["Extra"] === "auto_increment") {
            $column_structure->is_auto_increment = true;
        }

        return $column_structure;
    }


    /**
     * @param $column_index
     * @param $column_data
     * @return ColumnStructure
     */
    public static function createFromPost($column_index, $column_data)
    {
        $column_structure                      = new self;
        $column_structure->index               = $column_index;
        $column_structure->name                = $column_data['name'];
        $column_structure->type                = $column_data['type'];
        $column_structure->length              = (isset($column_data['length']) && $column_data['length'] != '') ? $column_data['length'] : null;
        $column_structure->attribute           = (isset($column_data['attribute']) && $column_data['attribute'] != '') ? $column_data['attribute'] : null;
        $column_structure->null                = (!empty($column_data['null']) && $column_data['null'] === 'true') ? true : false;
        $column_structure->has_default_value   = (!empty($column_data['has_default_value']) && $column_data['has_default_value'] === 'true') ? true : false;
        $column_structure->default_value       = ($column_structure->has_default_value === true) ? $column_data['default_value'] : null;
        $column_structure->comment             = $column_data['comment'];
        $column_structure->is_auto_increment   = (!empty($column_data['is_auto_increment']) && $column_data['is_auto_increment'] === 'true') ? true : false;
        $column_structure->original_field_name = (isset($column_data['original_field_name'])) ? $column_data['original_field_name'] : null;
        $column_structure->after_column        = (isset($column_data['after_column']) && $column_data['after_column'] !== 'null') ? $column_data['after_column'] : null;

        return $column_structure;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    public function asQueryWithoutName($pdo, array $table_indexes)
    {
        $structure_query = $this->type;
        $structure_query .= (!empty($this->length)) ? "(".$this->length.") " : " ";

        if (!empty($this->attribute)) {
            if (in_array($this->attribute, ['unsigned', 'zerofill', 'unsigned zerofill'])) {
                $structure_query .= strtoupper($this->attribute)." ";
            } else {
                $structure_query .= "COLLATE ".$pdo->quote(strtoupper($this->attribute))." ";
            }
        }

        if ($this->null === true) {
            $structure_query .= "NULL";
        } else {
            $structure_query .= "NOT NULL";
        }

        if ($this->has_default_value === true) {
            $structure_query .= " DEFAULT ".$pdo->quote($this->default_value);
        }

        if (!empty($this->comment)) {
            $structure_query .= " COMMENT ".$pdo->quote($this->comment);
        }

        if ($this->is_auto_increment) {
            $column_name = $this->name;
            // check if this column has a primary key index or unique index
            $column_index_key = array_filter(
              $table_indexes,
              function (array $table_index) use ($column_name) {
                  return ($table_index['Column_name'] == $column_name && ($table_index['Key_name'] === 'PRIMARY' || $table_index['Non_unique'] == '0'));
              }
            );

            if (count($column_index_key) > 0) {
                $structure_query .= " AUTO_INCREMENT";
            } else {
                // find primary key from indexes
                $primary_key_index = array_filter(
                  $table_indexes,
                  function (array $table_index) {
                      return $table_index['Key_name'] === 'PRIMARY';
                  }
                );

                // this table has a primary key
                if (count($primary_key_index) > 0) {
                    // add unique key
                    $structure_query .= " AUTO_INCREMENT UNIQUE";
                } else {
                    $structure_query .= " AUTO_INCREMENT PRIMARY KEY";
                }
            }
        }

        if (!empty($this->after_column)) {
            if (strtolower($this->after_column) == 'first') {
                $structure_query .= ' FIRST';
            } else {
                $structure_query .= " AFTER ". QueryHelper::escapeMysqlId($this->after_column);
            }
        }

        return $structure_query;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getOriginalFieldName()
    {
        return $this->original_field_name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int|null
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return string
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @return bool
     */
    public function getNull()
    {
        return $this->null;
    }

    /**
     * @return bool
     */
    public function getHasDefaultValue()
    {
        return $this->has_default_value;
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->default_value;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return bool
     */
    public function getIsAutoIncrement()
    {
        return $this->is_auto_increment;
    }

    /**
     * @return mixed
     */
    public function getAfterColumn()
    {
        return $this->after_column;
    }


}
