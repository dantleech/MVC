Lightweight MVC Framework
=========================

This is a very lightweight MVC framework featuring:

- Dependency Injection
- URL Router
- Request Dispatcher

The components are inspired by Symfony2 (https://github.com/symfony).

No view or model layers are included. This is just a framework to facilitate the usage
of third party components.

You will need to setup an autoloader. I used the Symfony one - https://github.com/symfony/ClassLoader.

This is a personal project and has NO TESTS.

Example usage
-------------

An example `index.php` with including service configuration::

    // web/index.php
    <?php
    require_once(__DIR__.'/../bootstrap.php');

    use DTL\MVC\Controller\Request;
    use DTL\MVC\Router\Route;
    use DTL\MVC\Controller\Dispatcher;
    use DTL\MVC\Service\Service;
    use DTL\MVC\Service\ServiceContainer;

    $sc = new ServiceContainer;

    // set router service
    $sc->set('router', new Service('DTL\MVC\Router\Router', array(
        array(
            // add routes
            new Route('&/foobar/(.*)/(.*)&', array('pronoun', 'subject'), 'DTL\FixturatorWeb\Controller\HomeController::index'),
        )
    )));

    // set monolog for logging (https://github.com/Seldaek/monolog)
    $sc->set('logger', Service::create('Monolog\Logger', array('MVC'))
        ->addMethodCall('pushHandler', array(new Service('Monolog\Handler\StreamHandler', array(
            __DIR__.'/../log/web.log',
        ))))
    )
    ;

    // set the TWIG template engine for use in the controllers (https://github.com/fabpot/twig)
    $sc->set('twig', new Service('\Twig_Environment', array(
        new Service('\Twig_Loader_Filesystem', array(
            __DIR__.'/../lib/DTL/FixturatorWeb/views',
        ))
    )));


    $dispatcher = new Dispatcher($sc);
    $request = Request::createFromGlobals();
    $dispatcher->dispatch($request);

And an example controller::

    <?php

    namespace DTL\FixturatorWeb\Controller;
    use DTL\MVC\Controller\Request;
    use DTL\MVC\Controller\Response;
    use DTL\MVC\Service\ServiceContainer;

    class HomeController
    {
        public function index(ServiceContainer $sc, Request $request)
        {
            return Response::create()
                ->setBody($sc->get('twig')->render('Home/index.html.twig', array('hello' => 'world')))
                ->setStatusCode(200)
                ->setHeader('Content-Type', 'text/html');
        }
    }
