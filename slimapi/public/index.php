<?php
declare(strict_types=1);

// protection for session hijacking, see: https://stackoverflow.com/questions/5081025/php-session-fixation-hijacking
@ini_set("session.use_trans_sid", "0");
@ini_set("session.use_only_cookies", "1");

use App\Application\Handlers\HttpErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use App\Application\ResponseEmitter\ResponseEmitter;
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Csrf\Guard;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

if (false) { // Should be set to true in production
	$containerBuilder->enableCompilation(__DIR__ . '/../var/cache');
}

// Set up settings
$settings = require __DIR__ . '/../app/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
$repositories = require __DIR__ . '/../app/repositories.php';
$repositories($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();

/** !IMPORTANT */
/** point to the index.php, we removed the .htaccess so we can also support nginx */
$app->setBasePath((function () {
    // get the dir of the location of where the script is executed, this way the user can put the application in any folder
    $script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    return $script_dir . '/index.php';
})());

$callableResolver = $app->getCallableResolver();

// Register middleware
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

/** @var bool $displayErrorDetails */
$displayErrorDetails = $container->get('settings')['displayErrorDetails'];

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$logger = $app->getContainer()->get(LoggerInterface::class);
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory, $logger);

// Register Middleware On Container
$container->set('csrf', function () use ($responseFactory) {
    $csrf_guard = new Guard($responseFactory);
    // required for ajax, if this would be false, we would need to generate a new CSRF token for every request
    $csrf_guard->setPersistentTokenMode(true);
    return $csrf_guard;
});

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// Add Routing Middleware
$app->addRoutingMiddleware();

// NOTE: for default PHP debugging, comment out these lines
// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
