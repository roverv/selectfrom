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
    public static function escapeMysqlId($field)
    {
        return "`".str_replace("`", "``", $field)."`";
    }

    /**
     * Taken from: https://stackoverflow.com/questions/147821/loading-sql-files-from-within-php
     * Note: expects trim() to have already been run on $sql.
     *
     * @param $sql
     * @param $delimiter
     * @return array
     */
    public static function splitSql($sql, $delimiter = ";") {
        // Split up our string into "possible" SQL statements.
        $tokens = explode($delimiter, $sql);

        // try to save mem.
        $sql    = "";
        $output = array();

        // we don't actually care about the matches preg gives us.
        $matches = array();

        // this is faster than calling count($oktens) every time thru the loop.
        $token_count = count($tokens);
        for ($i = 0; $i < $token_count; $i++) {
            // Don't wanna add an empty string as the last thing in the array.
            if (($i != ($token_count - 1)) || (strlen($tokens[$i]) > 0)) {
                // This is the total number of single quotes in the token.
                $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
                // Counts single quotes that are preceded by an odd number of backslashes,
                // which means they're escaped quotes.
                $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

                $unescaped_quotes = $total_quotes - $escaped_quotes;

                // If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
                if (($unescaped_quotes % 2) == 0) {
                    // It's a complete sql statement.
                    $output[] = $tokens[$i];
                    // save memory.
                    $tokens[$i] = "";
                } else {
                    // incomplete sql statement. keep adding tokens until we have a complete one.
                    // $temp will hold what we have so far.
                    $temp = $tokens[$i].$delimiter;
                    // save memory..
                    $tokens[$i] = "";

                    // Do we have a complete statement yet?
                    $complete_stmt = false;

                    for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++) {
                        // This is the total number of single quotes in the token.
                        $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
                        // Counts single quotes that are preceded by an odd number of backslashes,
                        // which means they're escaped quotes.
                        $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

                        $unescaped_quotes = $total_quotes - $escaped_quotes;

                        if (($unescaped_quotes % 2) == 1) {
                            // odd number of unescaped quotes. In combination with the previous incomplete
                            // statement(s), we now have a complete statement. (2 odds always make an even)
                            $output[] = $temp.$tokens[$j];

                            // save memory.
                            $tokens[$j] = "";
                            $temp       = "";

                            // exit the loop.
                            $complete_stmt = true;
                            // make sure the outer loop continues at the right point.
                            $i = $j;
                        } else {
                            // even number of unescaped quotes. We still don't have a complete statement.
                            // (1 odd and 1 even always make an odd)
                            $temp .= $tokens[$j].$delimiter;
                            // save memory.
                            $tokens[$j] = "";
                        }
                    } // for..
                } // else
            }
        }

        return $output;
    }

    /**
     * Taken from: https://stackoverflow.com/questions/9690448/regular-expression-to-remove-comments-from-sql-statement
     *
     * @param $sql_text
     * @return string
     */
    public static function removeComments($sql_text) {
        $re = '/["\'`][^"\'`]*(?!\\\\)["\'`](*SKIP)(*F)       # Make sure we\'re not matching inside of quotes, double quotes or backticks
|(?m-s:\s*(?:\-{2}|\#)[^\n]*$) # Single line comment
|(?:
  # \/\*.*?\*\/                  # Multi-line comment
  (?(?=(?m-s:[\t ]+$))         # Get trailing whitespace if any exists and only if it\'s the rest of the line
    [\t ]+
  )
)/xs';

        $result = preg_replace($re, '', $sql_text);

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
