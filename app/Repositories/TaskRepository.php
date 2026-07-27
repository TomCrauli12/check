<?php

class TaskRepository{

    private string $file;

    public function __construct(?string $file=null){

        $this->file = $file ?? __DIR__ . '/../../database/tasks.json';
    }

    public function create($name, $description, $created){

        $tasks = $this->getAll();

        $ids = array_column($tasks, 'id');

        $id = empty($ids) ? 1 : max($ids) + 1;

        $tasks[] = [
            'id'=>$id,
            'name'=>$name,
            'description'=>$description,
            'created'=>$created,
        ];

        $this->save($tasks);

        return $id;
    }

    public function getAll(){

        if(!file_exists($this->file)){

            return [];
        }

        $content = file_get_contents($this->file);

        $tasks = json_decode($content ?: '[]', true);

        return is_array($tasks) ? $tasks : [];
    }

    public function getById($id){

        foreach($this->getAll() as $task){

            if((int)$task['id']===(int)$id){

                return $task;
            }
        }

        return null;
    }

    public function update($id, $name, $description){

        $tasks = $this->getAll();

        foreach($tasks as &$task){

            if((int)$task['id']===(int)$id){

                $task['name'] = $name;

                $task['description'] = $description;

                $this->save($tasks);

                return true;
            }
        }

        return false;
    }

    public function delete($id){

        $tasks = $this->getAll();

        $filteredTasks = array_filter($tasks, function($task) use ($id){

            return (int)$task['id']!==(int)$id;
        });

        if(count($tasks)===count($filteredTasks)){

            return false;
        }

        $this->save(array_values($filteredTasks));

        return true;
    }

    private function save(array $tasks): void{

        $directory = dirname($this->file);

        if(!is_dir($directory)){

            mkdir($directory, 0777, true);
        }

        $json = json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if(file_put_contents($this->file, $json, LOCK_EX)===false){

            throw new RuntimeException('Не удалось сохранить данные');
        }
    }
}

?>
