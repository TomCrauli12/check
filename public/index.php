<?php

require_once __DIR__ . '/../app/Services/TaskService.php';
require_once __DIR__ . '/../app/Middleware/CsrfMiddleware.php';

if(session_status()!==PHP_SESSION_ACTIVE){

    session_start();
}

$tasks = Task::getAll();

$success = $_SESSION['success'] ?? null;

$error = $_SESSION['error'] ?? null;

unset($_SESSION['success'], $_SESSION['error']);

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Простые задачи</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <h1>Простые задачи</h1>

        <?php if($success):?>
            <p class="message success"><?=htmlspecialchars($success, ENT_QUOTES, 'UTF-8')?></p>
        <?php endif;?>

        <?php if($error):?>
            <p class="message error"><?=htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?></p>
        <?php endif;?>

        <section class="card">
            <h2>Создать задачу</h2>

            <form action="task.php?action=createTask" method="post">
                <?=Csrf::input()?>

                <label for="name">Название:</label>
                <input type="text" name="name" id="name" minlength="2" maxlength="100" required>

                <label for="description">Описание:</label>
                <textarea name="description" id="description" minlength="2" maxlength="500" required></textarea>

                <button type="submit">Создать</button>
            </form>
        </section>

        <section class="card">
            <h2>Список задач</h2>

            <?php if(empty($tasks)):?>
                <p>Задач пока нет</p>
            <?php else:?>
                <div class="tasks">
                    <?php foreach($tasks as $task):?>
                        <article class="task">
                            <h3><?=htmlspecialchars($task['name'], ENT_QUOTES, 'UTF-8')?></h3>

                            <p><?=nl2br(htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8'))?></p>

                            <small><?=htmlspecialchars($task['created'], ENT_QUOTES, 'UTF-8')?></small>

                            <div class="actions">
                                <a class="button secondary" href="edit.php?id=<?=$task['id']?>">Изменить</a>

                                <form action="task.php?action=deleteTask" method="post" onsubmit="return confirm('Удалить задачу?')">
                                    <?=Csrf::input()?>
                                    <input type="hidden" name="id" value="<?=$task['id']?>">
                                    <button class="danger" type="submit">Удалить</button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach;?>
                </div>
            <?php endif;?>
        </section>
    </main>
</body>
</html>
