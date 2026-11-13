<?php
require "../../../models/database/historias_db_interface.php";
require "../../../models/database/historias_db.php";
require "../../../models/entities/Historia.php";
require "../../../models/mappers/historia_mappers.php";
require "../../../models/repository/historias_repository/historias_repository_interface.php";
require "../../../models/repository/historias_repository/historias_repository.php";
require "../../../models/config/models_config.php";

require "../../../controllers/mappers/historia_mappers_controller.php";
require "../../../controllers/historias/historias_controller_interface.php";
require "../../../controllers/historias/historias_controller.php";
require "../../../controllers/config/controllers_config.php";
use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_historias_controller();

$historia = $controller->get_historia($_GET["id"]);

if (empty($historia)) {
    header("Location: ../../inicio.php?error=1");
} else {
    header("Location: ../../inicio.php");
}
?>