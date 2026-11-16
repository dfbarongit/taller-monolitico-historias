<?php
namespace app\models\repository\historias_repository;

use app\models\database\HistoriasDBInterface;
use app\models\entities\Historia;
use app\models\mappers\HistoriaMapper; 

class HistoriasRepository implements HistoriasRepositoryInterface
{
    public HistoriasDBInterface $historias_db;

    public function __construct(HistoriasDBInterface $historias_db)
    {
        $this->historias_db = $historias_db;
    }

     public function get_historias(): array 
    {
        $sql = "SELECT id, titulo, descripcion, responsable, estado, puntos, fecha_creacion, fecha_finalizacion, sprint_id, created_at, updated_at FROM historias";
        $historias_db = $this->historias_db->query_select($sql);
    
        return array_map(
            fn($row): Historia => HistoriaMapper::to_entity($row),
            $historias_db
        );
    }

    public function get_historia_by_id(int $id): ?Historia
    {
        $sql = "SELECT id, titulo, descripcion, responsable, estado, puntos, fecha_creacion, fecha_finalizacion, sprint_id, created_at, updated_at FROM historias WHERE id = ?";
        $historias_db = $this->historias_db->query_select_prepared($sql, [$id]);
        if (empty($historias_db)) {
            return null;
        }
        return HistoriaMapper::to_entity($historias_db[0]);
    }
    
    public function save_new_history(Historia $historia): bool 
    {
        $sql = "INSERT INTO `historias` (`titulo`, `descripcion`, `responsable`, `estado`, `puntos`, `fecha_creacion`, `fecha_finalizacion`, `sprint_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $fechaFinalizacion = $historia->getFechaFinalizacionDateTime();
        return $this->historias_db->query_prepared($sql, [
            $historia->getTitulo(),
            $historia->getDescripcion(),
            $historia->getResponsable(),
            $historia->getEstado(),
            $historia->getPuntos(),
            $historia->getFechaCreacionDateTime()->format('Y-m-d'),
            $fechaFinalizacion ? $fechaFinalizacion->format('Y-m-d') : null,
            $historia->getSprintId()
        ]);
    }
    public function get_historia(int $id): Historia 
    {
        $sql = "SELECT id, titulo, descripcion, responsable, estado, puntos, fecha_creacion, fecha_finalizacion, sprint_id ";
        $sql .= "FROM historias WHERE id=" . $id;
        $historias_db = $this->historias_db->query_select($sql);
        
        if (count($historias_db) === 0) {
            throw new \Exception("Historia no encontrada", 2);
        }
        
        return HistoriaMapper::to_entity($historias_db[0]);
    }

    public function update_historia(Historia $historia): bool 
    {
        $sql = "UPDATE historias SET titulo = ?, descripcion = ?, responsable = ?, estado = ?, puntos = ?, fecha_creacion = ?, fecha_finalizacion = ?, sprint_id = ? WHERE id = ?";
        $fechaFinalizacion = $historia->getFechaFinalizacionDateTime();
        return $this->historias_db->query_prepared($sql, [
            $historia->getTitulo(),
            $historia->getDescripcion(),
            $historia->getResponsable(),
            $historia->getEstado(),
            $historia->getPuntos(),
            $historia->getFechaCreacionDateTime()->format('Y-m-d'),
            $fechaFinalizacion ? $fechaFinalizacion->format('Y-m-d') : null,
            $historia->getSprintId(),
            $historia->getId()
        ]);
    }

    public function delete_historia(int $id): bool 
    {
        return $this->historias_db->query_prepared("DELETE FROM historias WHERE id = ?", [$id]);
    }

    public function get_historias_by_sprint(int $sprintId): array 
    {
        $sql = "SELECT id, titulo, descripcion, responsable, estado, puntos, fecha_creacion, fecha_finalizacion, sprint_id, created_at, updated_at FROM historias WHERE sprint_id = ?";
        $historias_db = $this->historias_db->query_select_prepared($sql, [$sprintId]);
        
        return array_map(
            fn($row): Historia => HistoriaMapper::to_entity($row),
            $historias_db
        );
    }
    
    public function get_conteo_por_estado(?int $sprintId = null): array
    {
         if ($sprintId === null) {
             $sql = "SELECT estado, COUNT(*) AS total FROM historias GROUP BY estado";
             return $this->historias_db->query_select($sql);
         }
         $sql = "SELECT estado, COUNT(*) AS total FROM historias WHERE sprint_id = ? GROUP BY estado";
         return $this->historias_db->query_select_prepared($sql, [$sprintId]);
    }

    public function get_conteo_por_responsable_y_estado(?int $sprintId = null): array
    {
         if ($sprintId === null) {
             $sql = "SELECT responsable, estado, COUNT(*) AS total FROM historias GROUP BY responsable, estado ORDER BY responsable ASC";
             return $this->historias_db->query_select($sql);
         }
         $sql = "SELECT responsable, estado, COUNT(*) AS total FROM historias WHERE sprint_id = ? GROUP BY responsable, estado ORDER BY responsable ASC";
         return $this->historias_db->query_select_prepared($sql, [$sprintId]);
    }
}
?>
