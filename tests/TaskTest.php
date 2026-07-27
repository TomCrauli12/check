<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Services/TaskService.php';

class TaskTest extends TestCase{

    private string $file;

    protected function setUp(): void{

        $this->file = tempnam(sys_get_temp_dir(), 'simple-crud-');

        file_put_contents($this->file, '[]');

        Task::setRepository(new TaskRepository($this->file));
    }

    protected function tearDown(): void{

        Task::setRepository(null);

        if(file_exists($this->file)){

            unlink($this->file);
        }
    }

    public function testCreateTask(): void{

        $id = Task::create('Купить продукты', 'Молоко и хлеб', '2026-07-27 12:00:00');

        $task = Task::getById($id);

        $this->assertSame(1, $id);

        $this->assertSame('Купить продукты', $task['name']);

        $this->assertSame('Молоко и хлеб', $task['description']);
    }

    public function testUpdateTask(): void{

        $id = Task::create('Старая задача', 'Старое описание', '2026-07-27 12:00:00');

        $result = Task::update($id, 'Новая задача', 'Новое описание');

        $task = Task::getById($id);

        $this->assertTrue($result);

        $this->assertSame('Новая задача', $task['name']);

        $this->assertSame('Новое описание', $task['description']);
    }

    public function testDeleteTask(): void{

        $id = Task::create('Удалить задачу', 'Эта задача будет удалена', '2026-07-27 12:00:00');

        $result = Task::delete($id);

        $this->assertTrue($result);

        $this->assertNull(Task::getById($id));

        $this->assertSame([], Task::getAll());
    }
}

?>
