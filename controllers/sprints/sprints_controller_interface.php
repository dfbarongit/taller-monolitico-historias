<?php
namespace app\controllers\sprints;

use app\models\entities\Sprint;

interface SprintsControllerInterface
{
    public function get_sprints_all(): array;
    public function save_sprint(array $request): bool;
    public function delete_sprint(int $id): bool;
    public function get_sprint(int $id): Sprint;
    public function update_sprint(array $request): bool;
}

?>