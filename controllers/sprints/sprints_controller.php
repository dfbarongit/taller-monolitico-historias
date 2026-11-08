<?php
namespace app\controllers\sprints;
use app\models\entities\Sprint;
use app\models\repository\sprints_repository\SprintsRepositoryInterface;
use app\controllers\mappers\SprintMappersController;

class SprintsController implements SprintsControllerInterface {
    private SprintsRepositoryInterface $sprint_model;

    public function __construct(SprintsRepositoryInterface $sprint_model)
    {
        $this->sprint_model = $sprint_model;
    }

    public function get_sprints_all(): array{
        return $this->sprint_model->get_sprints();
    }
    public function save_sprint(array $request): bool{
        $sprint = SprintMappersController::to_entity($request);
        return $this->sprint_model->save_new_sprint($sprint);
    }
    public function delete_sprint(int $id): bool{
        return $this->sprint_model->delete_sprint($id);
    }
    public function get_sprint(int $id): Sprint{
        return $this->sprint_model->get_sprint_by_id($id);
    }
    public function update_sprint(array $request): bool{
        $sprint = SprintMappersController::to_entity($request);
        return $this->sprint_model->update_sprint($sprint);
    }
}
?>