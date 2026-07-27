<?php

class Csrf{

    public static function token(): string{

        if(empty($_SESSION['csrf_token'])){

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function input(): string{

        $token = htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8');

        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    public static function requireValid(): void{

        $token = $_POST['csrf_token'] ?? '';

        if(!is_string($token) || !hash_equals(self::token(), $token)){

            throw new RuntimeException('Не удалось проверить форму. Обновите страницу и попробуйте ещё раз');
        }
    }
}

?>
