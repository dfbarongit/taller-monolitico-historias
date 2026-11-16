<?php
namespace app\controllers\historias;

use app\models\entities\Historia;

interface HistoriasControllerInterface{
    public function get_historias_all(): array;
    public function get_historia(int $id): ?Historia;
    public function save_history(array $request): bool;
    public function delete_historia(int $id): bool;
    public function update_historia(array $request): bool;
    public function get_historias_by_sprint(int $sprintId): array;
    public function get_conteo_por_estado(?int $sprintId = null): array;
    public function get_conteo_por_responsable_y_estado(?int $sprintId = null): array;

}
?>