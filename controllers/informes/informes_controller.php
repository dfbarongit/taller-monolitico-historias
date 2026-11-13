<?php
namespace app\controllers\informes;
use app\models\repository\informes_repository\InformesRepositoryInterface;
class InformesController implements InformesControllerInterface
{
    private InformesRepositoryInterface $informes_model;

    public function __construct(InformesRepositoryInterface $informes_model)
    {
        $this->informes_model = $informes_model;
    }

    public function get_conteo_por_estado_por_sprint(int $idSprint): array
    {
        return $this->informes_model->get_conteo_por_estado_por_sprint($idSprint);
    }
    public function get_conteo_por_responsable_por_sprint(int $idSprint): array
    {
        return $this->informes_model->get_conteo_por_responsable_por_sprint($idSprint);
    }
    public function get_conteo_por_estado(): array
    {
        return $this->informes_model->get_conteo_por_estado();
    }
    public function get_conteo_por_responsable(): array
    {
        return $this->informes_model->get_conteo_por_responsable();
    }
}
?>