<?php
namespace app\models\repository\sprints_repository;
use app\models\entities\Sprint;
interface SprintsRepositoryInterface
{
    public function get_sprints(): array;
    public function save_new_sprint(Sprint $sprint): bool;
    public function delete_sprint(int $id): bool;
    public function get_sprint_by_id(int $id): Sprint;
}
?>