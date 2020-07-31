<?php

    require 'ColumnStructure.php';

    class TableStatus implements \JsonSerializable
    {
        private $name;
        private $engine;
        private $collation;
        private $comment;
        private $auto_increment_value;
        private $columns;

        private function __construct()
        {

        }

        /**
         * @param $post_data
         * @return TableStatus
         */
        public static function createFromPost($post_data)
        {
            $table_status                       = new self;
            $table_status->name                 = $post_data['table_name'];
            $table_status->engine               = $post_data['engine'];
            $table_status->collation            = $post_data['collation'];
            $table_status->comment              = $post_data['comment'];
            $table_status->auto_increment_value = (!empty($post_data['auto_increment_value'])) ? (int) $post_data['auto_increment_value'] : null;

            foreach ($post_data['columns'] as $column_index => $column) {
                $is_auto_increment = false;
                if (!empty($post_data['auto_increment_field']) && $post_data['auto_increment_field'] === $column['name']) {
                    $is_auto_increment = true;
                }
                $column_structure        = ColumnStructure::createFromPost($column_index, $column, $is_auto_increment);
                $table_status->columns[] = $column_structure;
            }

            return $table_status;
        }

        /**
         * @param $table_status_data
         * @param $columns_data
         * @param $data_type_attributes
         * @return TableStatus
         */
        public static function createFromDatabase($table_status_data, $columns_data, $data_type_attributes)
        {
            $table_status                       = new self;
            $table_status->name                 = $table_status_data['Name'];
            $table_status->engine               = $table_status_data['Engine'];
            $table_status->collation            = $table_status_data['Collation'];
            $table_status->comment              = $table_status_data['Comment'];
            $table_status->auto_increment_value = (!empty($table_status_data['Auto_increment'])) ? (int)$table_status_data['Auto_increment'] : null;

            foreach ($columns_data as $column_index => $column) {
                $column_structure        = ColumnStructure::createFromDatabase($column_index, $column,
                  $data_type_attributes);
                $table_status->columns[] = $column_structure;
            }

            return $table_status;
        }

        /**
         * @param $column_name
         * @return ColumnStructure|false
         */
        public function getColumnByName($column_name)
        {
            $column = array_filter($this->columns, function(ColumnStructure $column) use($column_name) {
                return $column->getName() == $column_name;
            });

            return reset($column); // we dont want an multi array, just the first and only element
        }

        /**
         * @return ColumnStructure|false
         */
        public function getAutoIncrementColumn()
        {
            $column = array_filter($this->columns, function(ColumnStructure $column) {
                return $column->getIsAutoIncrement() === true;
            });

            return reset($column); // we dont want an multi array, just the first and only element
        }

        public function jsonSerialize()
        {
            $vars = get_object_vars($this);

            return $vars;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * @return string
         */
        public function getEngine()
        {
            return $this->engine;
        }

        /**
         * @return string
         */
        public function getCollation()
        {
            return $this->collation;
        }

        /**
         * @return string
         */
        public function getComment()
        {
            return $this->comment;
        }

        /**
         * @return ColumnStructure[]
         */
        public function getColumns()
        {
            return $this->columns;
        }

        /**
         * @return null|int
         */
        public function getAutoIncrementValue()
        {
            return $this->auto_increment_value;
        }

    }
