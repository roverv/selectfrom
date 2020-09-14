<?php

declare(strict_types=1);

namespace App\Application\Helpers;

class QueryHelper
{

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
        return "`".str_replace("`", "``", $field)."`";
    }

    /**
     * Taken from: https://stackoverflow.com/questions/4747808/split-mysql-queries-in-array-each-queries-separated-by
     *
     * @param $sql_text
     * @return array|mixed
     */
    public static function splitSql($sql_text)
    {
        // Return array of ; terminated SQL statements in $sql_text.
        $re_split_sql = '%(?#!php/x re_split_sql Rev:20170816_0600)
        # Match an SQL record ending with ";"
        \s*                                     # Discard leading whitespace.
        (                                       # $1: Trimmed non-empty SQL record.
          (?:                                   # Group for content alternatives.
            \'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'  # Either a single quoted string,
          | "[^"\\\\]*(?:\\\\.[^"\\\\]*)*"      # or a double quoted string,
          | /\*[^*]*\*+(?:[^*/][^*]*\*+)*/      # or a multi-line comment,
          | \#.*                                # or a # single line comment,
          | --.*                                # or a -- single line comment,
          | [^"\';#]                            # or one non-["\';#-]
          )+                                    # One or more content alternatives
          (?:;|$)                               # Record end is a ; or string end.
        )                                       # End $1: Trimmed SQL record.
        %x';  // End $re_split_sql.
        if (preg_match_all($re_split_sql, $sql_text, $matches)) {
            return $matches[1];
        }

        return array();
    }

    /**
     * Taken from: https://stackoverflow.com/questions/9690448/regular-expression-to-remove-comments-from-sql-statement
     *
     * @param $sql_text
     * @return string
     */
    public static function removeComments($sql_text)
    {
        $regex = '/["\'`][^"\'`]*(?!\\\\)["\'`](*SKIP)(*F)|(?m-s:\s*(?:\-{2}|\#)[^\n]*$)|(?:\/\*.*?\*\/(?(?=(?m-s:[\t ]+$))[\t ]+))/s';
        // alternative, not using PCRE:
        // $RXSQLComments = '@(--[^\r\n]*)|(\#[^\r\n]*)|(/\*[\w\W]*?(?=\*/)\*/)@ms';
        $result = preg_replace($regex, '', $sql_text);

        return $result;
    }


    /**
     * Return the type of the query.
     * Taken from: https://github.com/jasny/dbquery-mysql/blob/master/src/Jasny/DB/MySQL/QuerySplitter.php
     *
     * @param  string  $sql  SQL query statement (or an array with parts)
     * @return string
     */
    public static function getQueryType($sql)
    {
        if (is_array($sql)) {
            $sql = key($sql);
        }

        $matches = null;
        if (!preg_match(
          '/^\s*(SELECT|INSERT|REPLACE|UPDATE|DELETE|TRUNCATE|CALL|DO|HANDLER|LOAD\s+(?:DATA|XML)\s+INFILE|(?:ALTER|CREATE|DROP|RENAME)\s+(?:DATABASE|TABLE|VIEW|FUNCTION|PROCEDURE|TRIGGER|INDEX)|PREPARE|EXECUTE|DEALLOCATE\s+PREPARE|DESCRIBE|EXPLAIN|HELP|USE|LOCK\s+TABLES|UNLOCK\s+TABLES|SET|SHOW|START\s+TRANSACTION|BEGIN|COMMIT|ROLLBACK|SAVEPOINT|RELEASE SAVEPOINT|CACHE\s+INDEX|FLUSH|KILL|LOAD|RESET|PURGE\s+BINARY\s+LOGS|START\s+SLAVE|STOP\s+SLAVE)\b/si',
          $sql,
          $matches
        )) {
            return null;
        }

        $type = strtoupper(preg_replace('/\s++/', ' ', $matches[1]));
        if ($type === 'BEGIN') {
            $type = 'START TRANSACTION';
        }

        return $type;
    }
}
