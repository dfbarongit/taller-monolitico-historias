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
$nombreSpr = "";
$fechaIniSpr = "";
$fechaFinSpr = "";

if (!empty($id)) {
    $controller = ControllersConfig::get_sprints_controller();
    $sprint = $controller->get_sprint($id);
    
    $titulo = "Editar un sprint existente";
    $nombreSpr = $sprint->getNombre();
    $fechaIniSpr = $sprint->getFechaInicio();
    $fechaFinSpr = $sprint->getFechaFin();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1><?php echo $titulo?></h1>
    <form action="operaciones/registrar_sprint.php" method="post">
        <div>
            <input type="text" name="id" id="" value="<?php echo $id?>" hidden>
        </div>
        <div>
            <label for="">Nombre del sprint</label>
            <input type="text" name="nombre" id="" value="<?php echo $nombreSpr?>">
        </div>
        <div>
            <label for="">Fecha de inicio</label>
            <input type="date" name="fecha_inicio" id="" value="<?php echo $fechaIniSpr?>">
            <label for="">Fecha de cierre</label>
            <input type="date" name="fecha_fin" id="" value="<?php echo $fechaFinSpr?>">
        </div>
        <div>
            <button type="submit">Crear nuevo sprint</button>
        </div>
    </form>
</body>

</html>