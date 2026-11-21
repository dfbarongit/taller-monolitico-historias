<?php
require_once __DIR__ . "/../../../models/database/historias_db_interface.php";
require_once __DIR__ . "/../../../models/database/historias_db.php";
require_once __DIR__ . "/../../../models/entities/Historia.php";
require_once __DIR__ . "/../../../models/mappers/historia_mappers.php";
require_once __DIR__ . "/../../../models/repository/historias_repository/historias_repository_interface.php";
require_once __DIR__ . "/../../../models/repository/historias_repository/historias_repository.php";
require_once __DIR__ . "/../../../models/entities/Sprint.php";
require_once __DIR__ . "/../../../models/mappers/sprint_mappers.php";
require_once __DIR__ . "/../../../models/repository/sprints_repository/sprints_repository_interface.php";
require_once __DIR__ . "/../../../models/repository/sprints_repository/sprints_repository.php";
require_once __DIR__ . "/../../../models/config/models_config.php";
require_once __DIR__ . "/../../../controllers/mappers/historia_mappers_controller.php";
require_once __DIR__ . "/../../../controllers/historias/historias_controller_interface.php";
require_once __DIR__ . "/../../../controllers/historias/historias_controller.php";
require_once __DIR__ . "/../../../controllers/config/controllers_config.php";

use app\controllers\config\ControllersConfig;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../inicio.php");
}
//comprobar fechas, si la fecha de cierre es menor a la fecha de inicio, redirigir con error
if (isset($_POST["fecha_finalizacion"]) && isset($_POST["fecha_creacion"])) {
    $fechaFinalizacion = new DateTime($_POST["fecha_finalizacion"]);
    $fechaCreacion = new DateTime($_POST["fecha_creacion"]);

    if ($fechaFinalizacion < $fechaCreacion) {
        header("Location: ../../historia_form.php?id=" . $id . "&idSprint=" . $sprintId . "&error=2");
        exit;
    }
}

$sprintId = (int)($_POST["sprint_id"] ?? 0);
try {
    $saved = ControllersConfig::get_historias_controller()->save_history($_POST);
} catch (\InvalidArgumentException $exception) {
    header("Location: ../../historia_form.php?idSprint=" . $sprintId . "&error=1");
}

if (!$saved) {
    header("Location: ../../historia_form.php?idSprint=" . $sprintId . "&error=1");
}

header("Location: ../../inicio.php?id=" . $sprintId);
