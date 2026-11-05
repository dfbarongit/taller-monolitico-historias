<?php
namespace app\models\repository\historias_repository;
use app\models\database\HistoriasDBInterface;
use app\models\entities\Historia;

class HistoriasRepository implements HistoriasRepositoryInterface
{
    public HistoriasDBInterface $historias_db;
    public function __construct(HistoriasDBInterface $historias_db)
    {
        $this->historias_db = $historias_db;
    }
    public function get_historias(): array{
    
    }
    public function save_new_historia(Historia $historia): bool{
        $sql = 'INSERT INTO `historias`(`titulo`, `descripcion`, `responsable`, `estado`, `puntos`, `fecha_creacion`, `fecha_finalizacion`, `sprint_id`) ';
        $sql .= 'VALUES ('.$historia->getTitulo().','.$historia->getDescripcion().','.$historia->getResponsable().','.$historia->getEstado().','.$historia->getPuntos().','.$historia->getFechaCreacion().','.$historia->getFechaFinalizacion().','.$historia->getSprintId().')';
        return $this->historias_db->query($sql);
    }
}
?>