<?php
/*require "../models/database/historias_db_interface.php";
require "../models/database/historias_db.php";
require "../models/entities/Historia.php";
require "../models/mappers/historia_mappers.php";
require "../models/repository/historias_repository/historias_repository_interface.php";
require "../models/repository/historias_repository/historias_repository.php";
require "../models/config/models_config.php";

require "../controllers/mappers/historia_mappers_controller.php";
require "../controllers/historias/historias_controller_interface.php";
require "../controllers/historias/historias_controller.php";
require "../controllers/config/controllers_config.php";*/
use app\controllers\config\ControllersConfig;

$id = (empty($_GET["id"])) ? "" : $_GET["id"];
$title = "Crear un nuevo historia";
$titleHis = "";
$descripcionHis = "";
$responsableHis = "";
$estadoHis = "";
$puntosHis = "";
$fechaIniHis = "";
$fechaFinHis = "";
$sprintActualId = ($_GET["idSprint"]);
$textBtn = "Crear nueva historia";
$action = "operaciones/historia/registrar_historia.php";

if (!empty($id)) {
    $controller = ControllersConfig::get_historias_controller();
    $historia = $controller->get_historia($id);

    $title = "Editar una historia existente";
    $titleHis = $historia->getTitulo();
    $descripcionHis = $historia->getDescripcion();
    $responsableHis = $historia->getResponsable();
    $estadoHis = $historia->getEstado();
    $puntosHis = $historia->getPuntos();
    $fechaIniHis = $historia->getFechaCreacion();
    $fechaFinHis = $historia->getFechaFinalizacion();
    $sprintActualId = $historia->getSprintId();
    $textBtn = "Guardar cambios";
    $action = "operaciones/historia/actualizar_historia.php";
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
    <h1><?php echo $title ?></h1>
    <form action="<?php echo $action ?>" method="post">
        <div>
            <input type="text" name="id" id="" value="<?php echo $id ?>" hidden>
        </div>
        <div>
            <label for="">Título de la historia</label>
            <input type="text" name="titulo" value="<?php echo $titleHis ?>">
        </div>
        <div>
            <label for="">Descripción de la historia</label>
            <input type="text" name="descripcion" value="<?php echo $descripcionHis ?>">
        </div>
        <div>
            <label for="">Responsable</label>
            <input type="text" name="responsable" value="<?php echo $responsableHis ?>">
        </div>
        <div>
            <label for="">Estado</label>
            <select name="estado" value="<?php echo $estadoHis ?>">
                <option value="nueva">Nueva</option>
                <option value="activa">Activa</option>
                <option value="finalizada">Finalizada</option>
                <option value="impedimento">Con impedimento</option>
            </select>
        </div>
        <div>
            <label for="">Puntos</label>
            <select name="puntos" value="<?php echo $puntosHis ?>">
                <option value="1">1 pt - Tarea minima</option>
                <option value="2">2 pts - Muy simple</option>
                <option value="3">3 pts - Ligeras dependencias</option>
                <option value="5">5 pts - Complejidad media</option>
                <option value="8">8 pts - Compleja</option>
                <option value="13">13 pts - Muy compleja</option>
                <option value="21">21 pts - Muy grande, debe dividirse</option>
            </select>
        </div>
        <div>
            <label for="">Fecha de inicio</label>
            <input type="date" name="fecha_creacion" value="<?php echo $fechaIniHis ?>">
            <label for="">Fecha de cierre</label>
            <input type="date" name="fecha_finalizacion" value="<?php echo $fechaFinHis ?>">
        </div>
        <div>
            <a href="inicio.php">Cancelar</a>
            <button type="submit"><?php echo $textBtn ?></button>
        </div>
    </form>
</body>

</html>