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
    exit;
}

$id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);
$sprintId = filter_var($_POST["sprint_id"] ?? null, FILTER_VALIDATE_INT);
if ($id === false || $id < 1 || $sprintId === false || $sprintId < 1) {
    header("Location: ../../inicio.php?error=1");
    exit;
}

try {
    $updated = ControllersConfig::get_historias_controller()->update_historia($_POST);
} catch (\InvalidArgumentException $exception) {
    header("Location: ../../historia_form.php?id=" . $id . "&idSprint=" . $sprintId . "&error=1");
    exit;
}

if (!$updated) {
    header("Location: ../../historia_form.php?id=" . $id . "&idSprint=" . $sprintId . "&error=1");
    exit;
}

header("Location: ../../inicio.php?id=" . $sprintId);
exit;
