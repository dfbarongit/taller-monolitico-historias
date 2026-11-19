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

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    header("Location: ../../inicio.php");
}

$id = empty($_GET["id"]) ? null : $_GET["id"];
$sprintId = empty($_GET["idSprint"]) ? null : $_GET["idSprint"];
if (empty($id) || empty($sprintId)) {
    header("Location: ../../inicio.php?error=1");
}

$deleted = ControllersConfig::get_historias_controller()->delete_historia($id);
header("Location: ../../inicio.php?id=" . $sprintId . ($deleted ? "" : "&error=1"));

