<?php
namespace app\controllers\mappers;

use app\models\entities\Historia;
use DateTime;
use InvalidArgumentException;

class HistoriaMapperController {

    public static function to_entity(array $row_db): Historia 
    {
        $idValue = $row_db["id"] ?? 0;
        $id = $idValue === "" ? 0 : filter_var($idValue, FILTER_VALIDATE_INT);
        $sprintId = filter_var($row_db["sprint_id"] ?? null, FILTER_VALIDATE_INT);
        $puntos = filter_var($row_db["puntos"] ?? null, FILTER_VALIDATE_INT);
        $titulo = trim((string)($row_db["titulo"] ?? ""));
        $descripcion = trim((string)($row_db["descripcion"] ?? ""));
        $responsable = trim((string)($row_db["responsable"] ?? ""));
        $estado = (string)($row_db["estado"] ?? "");

        if ($id === false || $id < 0 || $sprintId === false || $sprintId < 1) {
            throw new InvalidArgumentException("La historia debe tener un ID y un sprint válidos.");
        }
        if ($titulo === "" || $descripcion === "" || $responsable === "") {
            throw new InvalidArgumentException("Título, descripción y responsable son obligatorios.");
        }
        if (!in_array($estado, ["nueva", "activa", "finalizada", "impedimento"], true)) {
            throw new InvalidArgumentException("El estado de la historia no es válido.");
        }
        if ($puntos === false || !in_array($puntos, [1, 2, 3, 5, 8, 13, 21], true)) {
            throw new InvalidArgumentException("Los puntos de complejidad no son válidos.");
        }

        $fechaCreacion = self::parse_date((string)($row_db["fecha_creacion"] ?? ""));
        $fechaFinalizacion = null;
        if (!empty($row_db["fecha_finalizacion"])) {
            $fechaFinalizacion = self::parse_date((string)$row_db["fecha_finalizacion"]);
            if ($fechaFinalizacion < $fechaCreacion) {
                throw new InvalidArgumentException("La fecha de finalización no puede ser anterior a la fecha de creación.");
            }
        }

        return new Historia(
            $id,
            $titulo,
            $descripcion,
            $responsable,
            $estado,
            $puntos,
            $fechaCreacion,
            $fechaFinalizacion,
            $sprintId,
            new DateTime(),
            new DateTime()
        );
    }

    private static function parse_date(string $value): DateTime
    {
        $date = DateTime::createFromFormat("!Y-m-d", $value);
        $errors = DateTime::getLastErrors();
        if ($date === false || ($errors !== false && ($errors["warning_count"] > 0 || $errors["error_count"] > 0))) {
            throw new InvalidArgumentException("Las fechas deben tener el formato válido AAAA-MM-DD.");
        }
        return $date;
    }
}
?>