<?php

declare(strict_types=1);

namespace App\Application\Helpers;

class QueryHelper {

    /**
     * Escape query identifiers like tablename, table column or database name
     * use as: UPDATE user SET escape_mysql_identifier('name') = :name
     * See: https://phpdelusions.net/pdo/sql_injection_example and https://phpdelusions.net/pdo_examples/insert_helper
     * NOTE: MYSQL ONLY!
     *
     * @param $name
     * @return string
     */
    public static function escapeMysqlId($field)
    {
        return "`" . str_replace("`", "``", $field) . "`";
    }
}
