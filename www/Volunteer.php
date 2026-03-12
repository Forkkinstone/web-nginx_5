<?php
class Volunteer {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function add($name, $age, $direction, $has_experience, $help_type) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO volunteers (name, age, direction, has_experience, help_type) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $age, $direction, $has_experience, $help_type]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM volunteers ORDER BY id DESC");
        return $stmt->fetchAll();
    }
}
