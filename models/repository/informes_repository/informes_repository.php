<?php
namespace app\models\repository\informes_repository;

use app\models\database\HistoriasDBInterface;

class InformesRepository implements InformesRepositoryInterface
{
    public HistoriasDBInterface $historias_db;
    public function __construct(HistoriasDBInterface $historias_db)
    {
        $this->historias_db = $historias_db;
    }
    public function get_conteo_por_estado_por_sprint(int $idSprint): array
    {
        $sql = "SELECT estado, COUNT(*) AS total FROM historias WHERE sprint_id = $idSprint GROUP BY estado";
        return $this->historias_db->query_select($sql);
    }

    public function get_conteo_por_responsable_por_sprint(int $idSprint): array
    {
        $sql = "SELECT 
        responsable, 
        COUNT(*) AS total,
        SUM(CASE WHEN estado = 'nueva' THEN 1 ELSE 0 END) AS nuevas,
        SUM(CASE WHEN estado = 'activa' THEN 1 ELSE 0 END) AS activas,
        SUM(CASE WHEN estado = 'finalizada' THEN 1 ELSE 0 END) AS finalizadas,
        SUM(CASE WHEN estado = 'impedimento' THEN 1 ELSE 0 END) AS impedimentos
        FROM historias 
        WHERE sprint_id = $idSprint 
        GROUP BY responsable 
        ORDER BY responsable ASC";
        return $this->historias_db->query_select($sql);
    }
    public function get_conteo_por_estado(): array
    {
        $sql = "SELECT estado, COUNT(*) AS total FROM historias GROUP BY estado";
        return $this->historias_db->query_select($sql);
    }

    public function get_conteo_por_responsable(): array
    {
        $sql = "SELECT 
        responsable, 
        COUNT(*) AS total,
        SUM(CASE WHEN estado = 'nueva' THEN 1 ELSE 0 END) AS nuevas,
        SUM(CASE WHEN estado = 'activa' THEN 1 ELSE 0 END) AS activas,
        SUM(CASE WHEN estado = 'finalizada' THEN 1 ELSE 0 END) AS finalizadas,
        SUM(CASE WHEN estado = 'impedimento' THEN 1 ELSE 0 END) AS impedimentos
        FROM historias
        GROUP BY responsable 
        ORDER BY responsable ASC";
        return $this->historias_db->query_select($sql);
    }
}
?>