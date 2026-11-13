<?php
namespace app\controllers\informes;

interface InformesControllerInterface{
    public function get_conteo_por_estado_por_sprint(int $idSprint): array;
    public function get_conteo_por_responsable_por_sprint(int $idSprint): array;
    public function get_conteo_por_estado(): array;
    public function get_conteo_por_responsable(): array;
}
?>