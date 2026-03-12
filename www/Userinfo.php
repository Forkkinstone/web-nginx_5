<?php
class UserInfo {
    public static function getInfo(): array {
        return [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'time' => date('Y-m-d H:i:s')
        ];
    }
}
