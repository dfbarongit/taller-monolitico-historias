<?php 
namespace app\models\repository\historias_repository;

use app\models\entities\Historia;

interface HistoriasRepositoryInterface{
    public function get_historias(): array;
    public function get_historia_by_id(int $id): ?Historia;
    public function save_new_history(Historia $historia): bool;
    public function update_historia(Historia $historia): bool;
    public function delete_historia(int $id): bool;
    public function get_historias_by_sprint(int $sprintId): array;
    public function get_conteo_por_estado(?int $sprintId = null): array;
    public function get_conteo_por_responsable_y_estado(?int $sprintId = null): array;
}
?>