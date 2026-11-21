<?php
require "../models/database/historias_db_interface.php";
require "../models/database/historias_db.php";
require "../models/entities/Sprint.php";
require "../models/mappers/sprint_mappers.php";
require "../models/repository/sprints_repository/sprints_repository_interface.php";
require "../models/repository/sprints_repository/sprints_repository.php";
require "../models/config/models_config.php";

require "../controllers/mappers/sprint_mappers_controller.php";
require "../controllers/sprints/sprints_controller_interface.php";
require "../controllers/sprints/sprints_controller.php";
require "../controllers/config/controllers_config.php";
use app\controllers\config\ControllersConfig;

$id = (empty($_GET["id"])) ? "" : $_GET["id"];
$titulo = "Crear un nuevo sprint";
$error = (empty($_GET["error"])) ? "" : "Ocurrió un error al procesar la solicitud, ingrese los datos correctamente";
if (!empty($_GET["error"]) && $_GET["error"] == 2) {
    $error = "Ingrese una fecha de cierre mayor a la fecha de inicio";
}
$nombreSpr = "";
$fechaIniSpr = "";
$fechaFinSpr = "";
$textBtn = "Crear nuevo sprint";
$action = "operaciones/sprint/registrar_sprint.php";

if (!empty($_GET["sinReg"])) {
    $titulo .= "<br> No hay registros, inicie creando un nuevo sprint...";
}

if (!empty($id)) {
    $controller = ControllersConfig::get_sprints_controller();
    $sprint = $controller->get_sprint($id);
    
    $titulo = "Editar un sprint existente";
    $nombreSpr = $sprint->getNombre();
    $fechaIniSpr = $sprint->getFechaInicio();
    $fechaFinSpr = $sprint->getFechaFin();
    $textBtn = "Guardar cambios";
    $action = "operaciones/sprint/actualizar_sprint.php";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(strip_tags($titulo)); ?></title>
    <link rel="stylesheet" href="assets/css/app.css">
</head>

<body class="form-page">
    <main class="form-card">
    <h1><?php echo $titulo?></h1>
    <h2><?php echo $error?></h2>
    <form action="<?php echo $action?>" method="post">
        <div>
            <input type="text" name="id" id="" value="<?php echo $id?>" hidden>
        </div>
        <div class="form-group">
            <label for="">Nombre del sprint</label>
            <input type="text" name="nombre" id="" value="<?php echo $nombreSpr?>">
        </div>
        <div class="form-row">
        <div class="form-group">
            <label for="">Fecha de inicio</label>
            <input type="date" name="fecha_inicio" id="" value="<?php echo $fechaIniSpr?>">
        </div>
        <div class="form-group">
            <label for="">Fecha de cierre</label>
            <input type="date" name="fecha_fin" id="" value="<?php echo $fechaFinSpr?>">
        </div>
        </div>
        <div class="form-actions">
            <a href="inicio.php">Cancelar</a>
            <button type="submit"><?php echo $textBtn?></button>
        </div>
    </form>
    </main>
</body>

</html>