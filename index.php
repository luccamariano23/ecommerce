<?php 

require_once("vendor/autoload.php");

use \Slim\Slim; 

$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/', function() {
    
    $page = new \Hcode\Page\Page();

    $page->setTpl("index");

});

$app->run();

 ?>