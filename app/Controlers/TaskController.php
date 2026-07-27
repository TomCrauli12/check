<?php

require_once __DIR__ . '/../Services/TaskService.php';
require_once __DIR__ . '/../Middleware/CsrfMiddleware.php';
require_once __DIR__ . '/../Validators/Validator.php';
require_once __DIR__ . '/../core/Router.php';

if(session_status()!==PHP_SESSION_ACTIVE){

    session_start();
}

$router = new Router();

$action = $router->getAction();

$id = 0;

$router->post('createTask', function(){

    Csrf::requireValid();

    $name = Validator::text($_POST['name'] ?? '', 'Название');

    $description = Validator::text($_POST['description'] ?? '', 'Описание', 2, 500);

    date_default_timezone_set('Europe/Moscow');

    $created = date('Y-m-d H:i:s');

    Task::create($name, $description, $created);

    $_SESSION['success'] = 'Задача создана';

    Router::redirect('index.php');
});

$router->post('updateTask', function() use (&$id){

    Csrf::requireValid();

    $id = Validator::integer($_POST['id'] ?? 0, 'Задача');

    $name = Validator::text($_POST['name'] ?? '', 'Название');

    $description = Validator::text($_POST['description'] ?? '', 'Описание', 2, 500);

    if(!Task::update($id, $name, $description)){

        throw new RuntimeException('Задача не найдена');
    }

    $_SESSION['success'] = 'Задача изменена';

    Router::redirect('index.php');
});

$router->post('deleteTask', function() use (&$id){

    Csrf::requireValid();

    $id = Validator::integer($_POST['id'] ?? 0, 'Задача');

    if(!Task::delete($id)){

        throw new RuntimeException('Задача не найдена');
    }

    $_SESSION['success'] = 'Задача удалена';

    Router::redirect('index.php');
});

try{

    $router->dispatch('index.php');

}catch(RuntimeException $exception){

    $_SESSION['error'] = $exception->getMessage();

    Router::redirect($action==='updateTask' && $id>0 ? 'edit.php?id=' . $id : 'index.php');
}

Router::redirect('index.php');

?>
