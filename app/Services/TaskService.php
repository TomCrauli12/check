<?php

require_once __DIR__ . '/../Repositories/TaskRepository.php';

class Task{

    private static ?TaskRepository $taskRepository = null;

    public static function setRepository(?TaskRepository $taskRepository): void{

        self::$taskRepository = $taskRepository;
    }

    static function create($name, $description, $created){

        return self::repository()->create($name, $description, $created);
    }

    static function getAll(){

        return self::repository()->getAll();
    }

    static function getById($id){

        return self::repository()->getById($id);
    }

    static function update($id, $name, $description){

        return self::repository()->update($id, $name, $description);
    }

    static function delete($id){

        return self::repository()->delete($id);
    }

    private static function repository(): TaskRepository{

        if(!self::$taskRepository){

            self::$taskRepository = new TaskRepository();
        }

        return self::$taskRepository;
    }
}

?>
