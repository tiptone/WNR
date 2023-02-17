<?php

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

require_once __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Tiptone\Mvc\View\JsonView;
use Tiptone\Mvc\View\FragmentView;

$config = include __DIR__ . '/../config/app.php';

$appName = $config['app_name'];

session_start();

$isDevMode = false;

$builder = new ContainerBuilder();
$builder->writeProxiesToFile(true, __DIR__ . '/data/cache');
$builder->useAutowiring(false);
$builder->addDefinitions($config['container']);

$container = $builder->build();

$log = $container->get(LoggerInterface::class);
$twig = $container->get(Environment::class);

if (strstr($_SERVER['REQUEST_URI'], '?')) {
    list($requestUri, $queryString) = explode('?', $_SERVER['REQUEST_URI']);
} else {
    $requestUri = $_SERVER['REQUEST_URI'];
}

$app = [];

$routeComponents = explode('/', $requestUri);
$controllerPath = '/' . $routeComponents[1];

foreach ($config['routes'] as $name => $options) {
    if ($controllerPath === $options['path']) {
        $route = $name;
        $app = $options;
        break;
    }
}

if (empty($app)) {
    $log->error('Unknown route', [$routeComponents]);
    echo $twig->render(
        'layout.html.twig',
        [
            'template' => 'error/route.html.twig',
            'route' => $requestUri
        ]
    );
    exit;
}

if (isset($routeComponents[2]) && $routeComponents[2] != '') {
    // user supplied action
    $action = $routeComponents[2];
} else {
    // use default action from $config
    $action = $app['default'];
}

/*
 * If we get to here, there's a valid controller specified.
 */
if ($container->has($app['controller'])) {
    $controller = $container->get($app['controller']);
} else {
    echo $twig->render(
        'layout.html.twig',
        [
            'template' => 'error/controller.html.twig',
            'route' => $requestUri
        ]
    );
}

$controllerAction = $action . 'Action';

if (!method_exists($controller, $controllerAction)) {
    $log->error('Invalid Action requested', [$controllerAction, $app['controller']]);
    echo $twig->render(
        'layout.html.twig',
        [
            'template' => 'error/action.html.twig',
            'controller' => $app['controller'],
            'action' => $action,
        ]
    );
    exit;
}

$response = $controller->$controllerAction();

$templateVars = [];

$responseClassName = (new ReflectionClass($response))->getName();

if ($responseClassName === JsonView::class) {
    header('Content-Type: application/json');
    echo $response->serialize();
} else {
    foreach ($response->getVariables() as $key => $value) {
        $templateVars[$key] = $value;
    }

    if ($response->getTemplate() == '') {
        $templateName = sprintf('%s/%s.html.twig', $route, $action);
    } else {
        $templateName = $response->getTemplate();
    }

    $targetTemplate = sprintf(
        '%s/../templates/%s',
        __DIR__,
        $templateName
    );

    if (!file_exists($targetTemplate)) {
        $log->error('Invalid Template requested', [$templateName]);
        echo $twig->render(
            'layout.html.twig',
            [
                'template' => 'error/view.html.twig',
                'view' => $templateName
            ]
        );
        exit;
    }

    if ($responseClassName === FragmentView::class) {
        echo $twig->render($templateName, $response->getVariables());
    } else {
        if (isset($_SESSION['error'])) {
            $response->setVariable('ERROR', $_SESSION['error']);
            unset($_SESSION['error']);
        }

        $response->setVariable('template', $templateName);
        echo $twig->render('layout.html.twig', $response->getVariables());
    }
}
