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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo strip_tags($titulo); ?></title>
    <link rel="stylesheet" href="assets/css/app.css">
</head>

<body>
    <main class="form-page">
        <section class="form-card">
            <h1><?php echo $titulo; ?></h1>
            <form class="form-grid" action="<?php echo htmlspecialchars($action, ENT_QUOTES, "UTF-8"); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$id, ENT_QUOTES, "UTF-8"); ?>">
                <div class="form-field">
                    <label for="nombre">Nombre del sprint</label>
                    <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($nombreSpr, ENT_QUOTES, "UTF-8"); ?>" required>
                </div>
                <div class="form-field">
                    <label for="fecha_inicio">Fecha de inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo htmlspecialchars($fechaIniSpr, ENT_QUOTES, "UTF-8"); ?>" required>
                </div>
                <div class="form-field">
                    <label for="fecha_fin">Fecha de cierre</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" value="<?php echo htmlspecialchars($fechaFinSpr, ENT_QUOTES, "UTF-8"); ?>" required>
                </div>
                <div class="form-actions">
                    <a class="secondary-link" href="inicio.php">Cancelar</a>
                    <button class="button button--primary" type="submit"><?php echo htmlspecialchars($textBtn, ENT_QUOTES, "UTF-8"); ?></button>
                </div>
            </form>
        </section>
    </main>
</body>

</html>