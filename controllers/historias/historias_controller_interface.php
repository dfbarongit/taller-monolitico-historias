<?php
namespace app\controllers\historias;

interface HistoriasControllerInterface{
    public function get_historias_all(): array;
    public function save_historia(array $request): bool;
}
?>