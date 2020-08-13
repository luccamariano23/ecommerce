<?php 

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim; 
use \Hcode\Model\User;

$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/', function() {
    
    $page = new \Hcode\Page\Page();

    $page->setTpl("index");

});

$app->get('/admin', function() {

	User::verifyLogin();
    
    $page = new \Hcode\Page\PageAdmin();

    $page->setTpl("index");

});

$app->get('/admin/login', function() {
    
    $page = new \Hcode\Page\PageAdmin([
    	"header"=>false,
    	"footer"=>false
    ]);

    $page->setTpl("login");

});

$app->post('/admin/login', function(){

	User::login($_POST['login'],$_POST['password']);

	header("Location: /admin");

	exit;

});

$app->get('/admin/logout', function(){

	User::logout();

	header("Location: /admin/login");

	exit;

});

$app->get('/admin/users', function() {

    User::verifyLogin();

    $users = User::listAll();
    
    $page = new \Hcode\Page\PageAdmin();

    $page->setTpl("users",array(
        "users"=>$users
    ));

});

$app->get("/admin/users/create", function(){

    User::verifyLogin();

    $page = new \Hcode\Page\PageAdmin();

    $page->setTpl("users-create");

});

$app->get("/admin/users/:iduser", function($iduser){

    User::verifyLogin();

    $user = new User();

    $user->get((int)$iduser);

    $page = new \Hcode\Page\PageAdmin();

    $page->setTpl("users-update", array(
        "user"=>$user->getValues()
    ));

});

$app->get("/admin/users/:iduser/delete", function($iduser){

    User::verifyLogin();

    $user = new User();

    $user->get((int)$iduser);

    $user->delete();

    header("Location: /admin/users");
    exit;

});

$app->post("/admin/users/create", function(){

    User::verifyLogin();

    $user = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    $_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

        "cost"=>12

    ]);

    $user->setData($_POST);

    $user->save();

    header("Location: /admin/users");
    exit;


});

$app->post("/admin/users/:iduser", function($iduser){

    User::verifyLogin();

    $user = new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    $user->get((int)$iduser);

    $user->setData($_POST);

    $user->update();

    header("Location: /admin/users");
    exit;

});

$app->delete("/admin/users/:iduser", function($iduser){

    User::verifyLogin();

});

$app->run();

 ?>