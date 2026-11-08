<?php
namespace app\models\mappers;

use app\models\entities\Historia;
use DateTime;

class HistoriaMapper {

    public static function map(array $row_db): Historia 
    {
        $fecha_creacion = !empty($row_db["fecha_creacion"]) ? new DateTime($row_db["fecha_creacion"]) : new DateTime();
        $fecha_finalizacion = !empty($row_db["fecha_finalizacion"]) ? new DateTime($row_db["fecha_finalizacion"]) : null;
        $created_at = !empty($row_db["created_at"]) ? new DateTime($row_db["created_at"]) : new DateTime();
        $updated_at = !empty($row_db["updated_at"]) ? new DateTime($row_db["updated_at"]) : new DateTime();

        return new Historia(
            (int)($row_db["id"] ?? 0),
            $row_db["titulo"] ?? '',
            $row_db["descripcion"] ?? '',
            $row_db["responsable"] ?? '',
            $row_db["estado"] ?? '',
            (int)($row_db["puntos"] ?? 0),
            $fecha_creacion,
            $fecha_finalizacion,
            (int)($row_db["sprint_id"] ?? 0),
            $created_at,
            $updated_at
        );
    }
}
?>
