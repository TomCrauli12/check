<?php

class Validator{

    public static function integer($value, string $field): int{

        $value = filter_var($value, FILTER_VALIDATE_INT);

        if($value===false || $value<1){

            throw new RuntimeException("Поле «{$field}» заполнено неверно");
        }

        return $value;
    }

    public static function text($value, string $field, int $min=2, int $max=100): string{

        $value = trim((string)$value);

        $length = mb_strlen($value);

        if($length<$min || $length>$max){

            throw new RuntimeException("Поле «{$field}» должно содержать от {$min} до {$max} символов");
        }

        return $value;
    }
}

?>
