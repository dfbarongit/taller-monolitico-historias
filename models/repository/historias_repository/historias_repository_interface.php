<?php 
namespace app\models\repository\historias_repository;
use app\models\entities\Historia;
interface HistoriasRepositoryInterface{
    public function get_historias(): array;

    public function save_new_historia(Historia $historia): bool;
}
?>