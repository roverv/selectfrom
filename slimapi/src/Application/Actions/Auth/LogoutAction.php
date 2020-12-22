<?php

  declare(strict_types=1);

  namespace App\Application\Actions\Auth;

  use App\Application\Actions\Action;
  use Psr\Container\ContainerInterface;
  use Psr\Http\Message\ResponseInterface as Response;
  use Psr\Log\LoggerInterface;

  class LogoutAction extends Action {

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger) {
      parent::__construct($logger);
    }


    /**
     * {@inheritdoc}
     */
    protected function action(): Response {

      session_destroy();

      $payload = [
        'result' => 'success',
      ];

      return $this->respondWithData($payload);
    }
  }
