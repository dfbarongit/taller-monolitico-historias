<?php
namespace app\models\mappers;
use app\models\entities\Sprint;
class SprintMappers {
    public static function to_entity(array $row_db): Sprint
    {
        return new Sprint(
            $row_db["id"],
            $row_db["nombre"],
            $row_db["fecha_inicio"],
            $row_db["fecha_fin"]
        );
    }
}
?>