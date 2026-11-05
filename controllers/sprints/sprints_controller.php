<?php
namespace app\controllers\sprints;
use app\models\repository\sprints_repository\SprintsRepositoryInterface;

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
        //return false;
    }
}
?>