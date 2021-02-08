<?php

declare(strict_types=1);

namespace App\Application\Helpers;

class QueryHelper
{

    /**
     * Escape query identifiers like tablename, table column or database name
     * use as: UPDATE user SET QueryHelper::escapeMysqlId('name') = :name
     * See: https://phpdelusions.net/pdo/sql_injection_example and https://phpdelusions.net/pdo_examples/insert_helper
     * NOTE: MYSQL ONLY!
     *
     * @param $name
     * @return string
     */
    public static function escapeMysqlId($field) {
        return "`".str_replace("`", "``", $field)."`";
    }

    /**
     * Split SQL string
     * see: https://stackoverflow.com/a/15354406/1856264
     *
     * @param  string path to sql file
     */
    public static function splitSql($sql_string) {
        // see: https://stackoverflow.com/questions/1462720/iterate-over-each-line-in-a-string-in-php
        // write to temporary file for fast and easy access, also no need for splitting on linebreaks with regex
        $file = fopen("php://temp", 'r+');
        fputs($file, $sql_string);
        rewind($file);

        $delimiter             = ';';
        $is_first_row          = true;
        $is_multi_line_comment = false;
        $sql                   = '';

        $queries = [];

        while (!feof($file)) {
            $row = fgets($file);

            // remove BOM for utf-8 encoded file
            if ($is_first_row) {
                $row          = preg_replace('/^\x{EF}\x{BB}\x{BF}/', '', $row);
                $is_first_row = false;
            }

            // 1. ignore empty string and comment row
            if (trim($row) == '' || preg_match('/^\s*(#|--\s)/sUi', $row)) {
                continue;
            }

            // 2. clear comments
            $row = trim(self::removeComments($row, $is_multi_line_comment));

            // 3. parse delimiter row
            if (preg_match('/^DELIMITER\s+[^ ]+/sUi', $row)) {
                $delimiter = preg_replace('/^DELIMITER\s+([^ ]+)$/sUi', '$1', $row);
                continue;
            }

            // 4. separate sql queries by delimiter
            $offset = 0;
            while (strpos($row, $delimiter, $offset) !== false) {
                $delimiter_offset = strpos($row, $delimiter, $offset);
                if (self::isQuoted($delimiter_offset, $row)) {
                    $offset = $delimiter_offset + strlen($delimiter);
                } else {
                    $sql       = trim($sql.' '.trim(substr($row, 0, $delimiter_offset)));
                    $queries[] = $sql;

                    $row    = substr($row, $delimiter_offset + strlen($delimiter));
                    $offset = 0;
                    $sql    = '';
                }
            }
            $sql = trim($sql.' '.$row);
        }
        if (strlen($sql) > 0) {
            $queries[] = $row;
        }

        fclose($file);

        return $queries;
    }

    /**
     * Remove comments from sql
     *
     * @param  string sql
     * @param  boolean is multicomment line
     * @return string
     */
    private static function removeComments($sql, &$is_multi_line_comment) {
        if ($is_multi_line_comment) {
            if (preg_match('#\*/#sUi', $sql)) {
                $sql                   = preg_replace('#^.*\*/\s*#sUi', '', $sql);
                $is_multi_line_comment = false;
            } else {
                $sql = '';
            }
            if (trim($sql) == '') {
                return $sql;
            }
        }

        $offset = 0;
        while (preg_match('{--\s|#|/\*[^!]}sUi', $sql, $matched, PREG_OFFSET_CAPTURE, $offset)) {
            list($comment, $found_on) = $matched[0];
            if (self::isQuoted($found_on, $sql)) {
                $offset = $found_on + strlen($comment);
            } else {
                if (substr($comment, 0, 2) == '/*') {
                    $closed_on = strpos($sql, '*/', $found_on);
                    if ($closed_on !== false) {
                        $sql = substr($sql, 0, $found_on).substr($sql, $closed_on + 2);
                    } else {
                        $sql                   = substr($sql, 0, $found_on);
                        $is_multi_line_comment = true;
                    }
                } else {
                    $sql = substr($sql, 0, $found_on);
                    break;
                }
            }
        }

        return $sql;
    }

    /**
     * Check if "offset" position is quoted
     *
     * @param  int  $offset
     * @param  string  $text
     * @return boolean
     */
    private static function isQuoted($offset, $text) {
        if ($offset > strlen($text)) {
            $offset = strlen($text);
        }

        // the text up until the delimiter
        $sub_text    = substr($text, 0, $offset);
        $quote_count = substr_count($sub_text, "'");
        if ($quote_count === 0) {
            return false;
        }

        $escaped_quote_count = substr_count($sub_text, "\'");
        // subtract escaped quotes as these do not count
        $quote_count -= $escaped_quote_count;
        // if the number of quotes is uneven, it means the delimiter is quoted, eg "INSERT INTO `test` VALUES('test case; abcd');"
        if ($quote_count % 2 === 1) {
            return true;
        }

        return false;
    }


    /**
     * Return the type of the query.
     * Taken from: https://github.com/jasny/dbquery-mysql/blob/master/src/Jasny/DB/MySQL/QuerySplitter.php
     *
     * @param  string  $sql  SQL query statement (or an array with parts)
     * @return string
     */
    public static function getQueryType($sql) {
        if (is_array($sql)) {
            $sql = key($sql);
        }

        $matches = null;
        if (!preg_match('/^\s*(SELECT|INSERT|REPLACE|UPDATE|DELETE|TRUNCATE|CALL|DO|HANDLER|LOAD\s+(?:DATA|XML)\s+INFILE|(?:ALTER|CREATE|DROP|RENAME)\s+(?:DATABASE|TABLE|VIEW|FUNCTION|PROCEDURE|TRIGGER|INDEX)|PREPARE|EXECUTE|DEALLOCATE\s+PREPARE|DESCRIBE|EXPLAIN|HELP|USE|LOCK\s+TABLES|UNLOCK\s+TABLES|SET|SHOW|START\s+TRANSACTION|BEGIN|COMMIT|ROLLBACK|SAVEPOINT|RELEASE SAVEPOINT|CACHE\s+INDEX|FLUSH|KILL|LOAD|RESET|PURGE\s+BINARY\s+LOGS|START\s+SLAVE|STOP\s+SLAVE)\b/si',
                        $sql,
                        $matches)) {
            return null;
        }

        $type = strtoupper(preg_replace('/\s++/', ' ', $matches[1]));
        if ($type === 'BEGIN') {
            $type = 'START TRANSACTION';
        }

        return $type;
    }
}
