<?php
namespace app\models\repository\sprints_repository;
use app\models\database\HistoriasDBInterface;
use app\models\mappers\SprintMappers;
use app\models\entities\Sprint;
use Exception;

class SprintsRepository implements SprintsRepositoryInterface
{
    public HistoriasDBInterface $historias_db;

    public function __construct(HistoriasDBInterface $historias_db)
    {
        $this->historias_db = $historias_db;
    }
    public function get_sprints(): array
    {
        $sql = "select * from sprints";
        $users_db = $this->historias_db->query_select($sql);
        // $users = [];
        // foreach ($users_db as $row) {
        //     array_push($users, UserMappers::to_entity($row));
        // }
        // return $users;
        return array_map(
            
            fn($row): Sprint => SprintMappers::to_entity($row),
            $users_db
        );
    }
    public function save_new_sprint(Sprint $sprint): bool
    {
        $sql = 'INSERT INTO `sprints`(`nombre`, `fecha_inicio`, `fecha_fin`)';
        $sql .= ' VALUES ("' . $sprint->getNombre() . '","' . $sprint->getFechaInicio() . '","' . $sprint->getFechaFin() . '")';
        return $this->historias_db->query($sql);
    }
    public function delete_sprint(int $id): bool
    {
        $sql = "DELETE FROM `sprints` WHERE id = $id;";
        return $this->historias_db->query($sql);
    }
    public function get_sprint_by_id(int $id): Sprint
    {
        $sql = "SELECT * FROM `sprints` WHERE id = $id;";
        $sprints = $this->historias_db->query_select($sql);
        if (count($sprints) == 0) {
            throw new Exception("Usuario no encontrado", 2);
        }
        return SprintMappers::to_entity($sprints[0]);
    }
    public function update_sprint(Sprint $sprint): bool
    {
        $sql = 'UPDATE `sprints` ';
        $sql .= 'SET `nombre`="'.$sprint->getNombre().'",`fecha_inicio`="'.$sprint->getFechaInicio().'",`fecha_fin`="'.$sprint->getFechaFin().'" WHERE id = '.$sprint->getId();
        return $this->historias_db->query($sql);
    }
}
?>