<?php
namespace app\controllers\historias;
use app\models\repository\historias_repository\HistoriasRepositoryInterface;
class HistoriasController implements HistoriasControllerInterface {
    private HistoriasRepositoryInterface $historia_model;

    public function __construct(HistoriasRepositoryInterface $historia_model)
    {
        $this->historia_model = $historia_model;
    }

    public function get_historias_all(): array{
        return $this->historia_model->get_historias();

    }
    public function save_historia(array $request): bool{
        //return false;
    }
}
?>