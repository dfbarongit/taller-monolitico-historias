<?php
namespace app\controllers\sprints;

interface SprintsControllerInterface{
    public function get_sprints_all(): array;
    public function save_sprint(array $request): bool;
}
?>