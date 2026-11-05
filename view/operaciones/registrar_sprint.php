<?php
use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_sprints_controller();

$sprint = $controller->save_sprint($_POST);
if (empty($sprint)) {
    header("Location: ../inicio.php?error=1");
} else {
    header("Location: ../inicio.php");
}
?>