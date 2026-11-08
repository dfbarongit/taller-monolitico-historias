<?php
require "../../../models/database/historias_db_interface.php";
require "../../../models/database/historias_db.php";
require "../../../models/entities/Sprint.php";
require "../../../models/mappers/sprint_mappers.php";
require "../../../models/repository/sprints_repository/sprints_repository_interface.php";
require "../../../models/repository/sprints_repository/sprints_repository.php";
require "../../../models/config/models_config.php";

require "../../../controllers/mappers/sprint_mappers_controller.php";
require "../../../controllers/sprints/sprints_controller_interface.php";
require "../../../controllers/sprints/sprints_controller.php";
require "../../../controllers/config/controllers_config.php";
use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_sprints_controller();

$sprint = $controller->update_sprint($_POST);
if (empty($sprint)) {
    header("Location: ../../inicio.php?error=1");
} else {
    header("Location: ../../inicio.php?id=".$_POST["id"]);
}
?>