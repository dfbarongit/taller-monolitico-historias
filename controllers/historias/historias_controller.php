<?php
namespace app\controllers\historias;
use app\controllers\mappers\HistoriaMappersController;
use app\models\entities\Historia;
use app\models\repository\historias_repository\HistoriasRepositoryInterface;
class HistoriasController implements HistoriasControllerInterface
{
    private HistoriasRepositoryInterface $historia_model;

    public function __construct(HistoriasRepositoryInterface $historia_model)
    {
        $this->historia_model = $historia_model;
    }

    public function get_historias_all(): array
    {
        return $this->historia_model->get_historias();

    }
    public function save_historia(array $request): bool
    {
        $historia = HistoriaMappersController::to_entity($request);
        return $this->historia_model->save_new_history($historia);
    }
    public function get_historia(int $id): Historia
    {
        return $this->historia_model->get_historia($id);
    }

    public function update_historia(array $request): bool
    {
        $historia = HistoriaMappersController::to_entity($request);
        return $this->historia_model->update_historia($historia);
    }
    public function delete_historia_by_id(int $id): bool
    {
        return $this->historia_model->delete_historia($id);

    }
    public function get_historias_by_sprint(int $sprintId): array
    {
        return $this->historia_model->get_historias_by_sprint($sprintId);

    }
    public function get_conteo_por_estado(): array
    {
        return $this->historia_model->get_conteo_por_estado();
    }
    public function get_conteo_por_responsable_y_estado(): array
    {
        return $this->historia_model->get_conteo_por_responsable_y_estado();
    }
}
?>