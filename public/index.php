<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Quizz\Core\Controller\FastRouteCore;

// Gestion des fichiers environnement
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

// Couche Controller
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $route) {
    $route->addRoute(['GET','POST'], '/', 'Quizz\Controller\HomeController');
    $route->addRoute(['GET','POST'], '/lister', 'Quizz\Controller\Questionnaire\ListController');
    $route->addRoute(['GET','POST'], '/detail/{id:\d+}', 'Quizz\Controller\Questionnaire\ViewController');
    $route->addRoute(['GET','POST'], '/etudiant[/{id:\d+}]', 'Quizz\Controller\Etudiant\ListeEtudiantController');
    $route->addRoute(['GET','POST'], '/etudiant/add', 'Quizz\Controller\Etudiant\AddEtudiantController');
    $route->addRoute(['GET','POST'], '/etudiant/{id:\d+}/update', 'Quizz\Controller\Etudiant\UpdateEtudiantController');

});
// Dispatcher -> Couche view
echo FastRouteCore::getDispatcher($dispatcher);