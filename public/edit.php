<?php

require_once __DIR__ . '/../app/Services/TaskService.php';
require_once __DIR__ . '/../app/Middleware/CsrfMiddleware.php';

if(session_status()!==PHP_SESSION_ACTIVE){

    session_start();
}

$id = (int)($_GET['id'] ?? 0);

$task = Task::getById($id);

$error = $_SESSION['error'] ?? null;

unset($_SESSION['error']);

if(!$task){

    http_response_code(404);

    exit('Задача не найдена');
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменить задачу</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container small">
        <section class="card">
            <h1>Изменить задачу</h1>

            <?php if($error):?>
                <p class="message error"><?=htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?></p>
            <?php endif;?>

            <form action="task.php?action=updateTask" method="post">
                <?=Csrf::input()?>
                <input type="hidden" name="id" value="<?=$task['id']?>">

                <label for="name">Название:</label>
                <input type="text" name="name" id="name" minlength="2" maxlength="100" value="<?=htmlspecialchars($task['name'], ENT_QUOTES, 'UTF-8')?>" required>

                <label for="description">Описание:</label>
                <textarea name="description" id="description" minlength="2" maxlength="500" required><?=htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8')?></textarea>

                <div class="actions">
                    <button type="submit">Сохранить</button>
                    <a class="button secondary" href="index.php">Отмена</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
