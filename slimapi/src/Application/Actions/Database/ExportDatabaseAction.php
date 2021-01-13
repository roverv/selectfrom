<?php

declare(strict_types=1);

namespace App\Application\Actions\Database;

use App\Application\Actions\Action;
use App\Domain\Driver\Mysql\Mysql;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Ifsnop\Mysqldump as IMysqldump;

class ExportDatabaseAction extends Action
{
    public function __construct(LoggerInterface $logger, Mysql $mysql_driver) {
        parent::__construct($logger);
        $this->mysql_driver = $mysql_driver;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response {
        $post_params  = (array)$this->request->getParsedBody();
        $query_params = $this->request->getQueryParams();

        $session = $this->request->getAttribute('session');

        if (empty($_COOKIE['password_key']) || empty($_COOKIE['password_nonce'])) {
            $response_json = [
              'result'  => 'error',
              'message' => 'Login data expired, please login again',
            ];

            return $this->respondWithData($response_json);
        }

        $key                = $_COOKIE['password_key'];
        $nonce              = $_COOKIE['password_nonce'];
        $decrypted_password = sodium_crypto_secretbox_open($session['password'] ?? '', $nonce, $key);

        $host     = $session['host'] ?? '';
        $username = $session['username'] ?? '';
        $password = $decrypted_password ?: '';

        $dsn = "mysql:host=$host;dbname=".$query_params['db'];

        $default_character_set = IMysqldump\Mysqldump::UTF8;
        if ($post_params['default_character_set'] === 'utf8mb4') {
            $default_character_set = IMysqldump\Mysqldump::UTF8MB4;
        }
        $dump_settings = [
          'include-tables'        => $post_params['tables'] ?? [],
          'include-views'         => $post_params['views'] ?? [],
          'no-create-info'        => ($post_params['structure_data'] == 'data'),
          'no-data'               => ($post_params['structure_data'] == 'structure'),
          'compress'              => IMysqldump\Mysqldump::NONE,
          'default-character-set' => $default_character_set,
          'reset-auto-increment'  => ($post_params['reset_auto_increment'] === 'true'),
          //          'if-not-exists'         => ($post_params['if_not_exists'] === 'true'), // @todo: activeren als de nieuwste versie van mysqldump php is geupload (staat nog niet op github)
          'add-drop-table'        => ($post_params['add_drop_table'] === 'true'),
          'add-locks'             => ($post_params['add_locks'] === 'true'),
          'lock-tables'           => ($post_params['lock_tables'] === 'true'),
          'lock-tables'           => ($post_params['lock_tables'] === 'true'),
        ];

        try {
            $response = new \Slim\Psr7\Response();

            $dump = new IMysqldump\Mysqldump($dsn, $username, $password, $dump_settings);
            // output will echo the dump text, ob wil retrieve it and save in variable
            // we dont want to write to a temporary file, because we don't want to save files on a public server
            ob_start();
            $dump->start('php://output');
            $contents = ob_get_contents();
            ob_end_clean();

            $contents_return = $contents;
            if ($post_params['compress_gzip'] === 'true') {
                $contents_return = gzencode($contents);
            }

            $content = $response->getBody();
            $content->write($contents_return);

            $response;

            $filename = $query_params['db'].'-'.(new \DateTime())->format('YmdHis');
            if ($post_params['compress_gzip'] === 'true') {
                return $response->withHeader('Content-Disposition', 'attachment; filename='.$filename.'.gz')
                  // allows the client side to read out these headers
                                ->withHeader('Access-Control-Expose-Headers', 'X-Suggested-Filename')
                                ->withHeader('X-Suggested-Filename', $filename.'.gz')
                                ->withHeader('Content-Type', 'application/x-gzip')
                                ->withHeader('Content-Description', 'File Transfer');
            }

            return $response->withHeader('Content-Disposition', 'attachment; filename='.$filename.'.sql')
              // allows the client side to read out these headers
                            ->withHeader('Access-Control-Expose-Headers', 'X-Suggested-Filename')
                            ->withHeader('X-Suggested-Filename', $filename.'.sql')
                            ->withHeader('Content-Type', 'application/force-download')
                            ->withHeader('Content-Type', 'application/octet-stream')
                            ->withHeader('Content-Type', 'application/download')
                            ->withHeader('Content-Description', 'File Transfer');
        } catch (\Exception $e) {
            $payload = [
              'result'  => 'error',
              'message' => $e->getMessage(),
              'code'    => $e->getCode(),
            ];

            return $this->respondWithData($payload);
        }
        //        return $this->respondWithData($result_data);
    }
}
