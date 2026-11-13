<?php
namespace app\models\mappers;

use app\models\entities\Historia;
use DateTime;

class HistoriaMapper {

    public static function to_entity(array $row_db): Historia 
    {
        return new Historia(
            (int)($row_db["id"] ?? 0),
            $row_db["titulo"] ?? '',
            $row_db["descripcion"] ?? '',
            $row_db["responsable"] ?? '',
            $row_db["estado"] ?? '',
            (int)($row_db["puntos"] ?? 0),
            $row_db["fecha_creacion"],
            $row_db["fecha_finalizacion"] ?? null,
            (int)($row_db["sprint_id"] ?? 0)
        );
    }
}
?>
