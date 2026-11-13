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
    
    public function save_new_history(Historia $historia): bool 
    {
        $sql = "INSERT INTO `historias` (`titulo`, `descripcion`, `responsable`, `estado`, `puntos`, `fecha_creacion`, `fecha_finalizacion`, `sprint_id`) VALUES (";
        $sql .= "'" . $historia->getTitulo() . "', ";
        $sql .= "'" . $historia->getDescripcion() . "', ";
        $sql .= "'" . $historia->getResponsable() . "', ";
        $sql .= "'" . $historia->getEstado() . "', ";
        $sql .= $historia->getPuntos() . ", ";
        $sql .= "'" . $historia->getFechaCreacion() . "', ";
        $sql .= $historia->getFechaFinalizacion() ? "'" . $historia->getFechaFinalizacion() . "', " : "NULL, ";
        $sql .= $historia->getSprintId() ?: "NULL";
        $sql .= ")";
        
        return $this->historias_db->query($sql);
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
        $sql = "UPDATE historias SET ";
        $sql .= "titulo='" . $historia->getTitulo() . "', ";
        $sql .= "descripcion='" . $historia->getDescripcion() . "', ";
        $sql .= "responsable='" . $historia->getResponsable() . "', ";
        $sql .= "estado='" . $historia->getEstado() . "', ";
        $sql .= "puntos=" . $historia->getPuntos() . ", ";
        $sql .= "fecha_creacion='" . $historia->getFechaCreacion() . "', ";
        
        if ($historia->getFechaFinalizacion() !== null) {
            $sql .= "fecha_finalizacion='" . $historia->getFechaFinalizacion() . "', ";
        } else {
            $sql .= "fecha_finalizacion=NULL, ";
        }
        
        $sql .= "sprint_id=" . ($historia->getSprintId() ?: "NULL");
        $sql .= " WHERE id=" . $historia->getId();
        
        return $this->historias_db->query($sql);
    }

    public function delete_historia(int $id): bool 
    {
        $sql = "DELETE FROM historias WHERE id=" . $id;
        return $this->historias_db->query($sql);
    }

    public function get_historias_by_sprint(int $sprintId): array 
    {
        $sql = "SELECT id, titulo, descripcion, responsable, estado, puntos, fecha_creacion, fecha_finalizacion, sprint_id, created_at, updated_at FROM historias WHERE sprint_id=" . $sprintId;
        $historias_db = $this->historias_db->query_select($sql);
        
        return array_map(
            fn($row): Historia => HistoriaMapper::to_entity($row),
            $historias_db
        );
    }
    
     public function get_conteo_por_estado(): array 
    {
        $sql = "SELECT estado, COUNT(*) AS total FROM historias GROUP BY estado";
        return $this->historias_db->query_select($sql); 
    }

    public function get_conteo_por_responsable_y_estado(): array 
    {
        $sql = "SELECT responsable, estado, COUNT(*) AS total FROM historias GROUP BY responsable, estado ORDER BY responsable ASC";
        return $this->historias_db->query_select($sql);
    }
}
?>
