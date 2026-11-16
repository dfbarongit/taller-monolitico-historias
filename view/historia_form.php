<?php
require_once __DIR__ . "/../models/database/historias_db_interface.php";
require_once __DIR__ . "/../models/database/historias_db.php";
require_once __DIR__ . "/../models/entities/Sprint.php";
require_once __DIR__ . "/../models/entities/Historia.php";
require_once __DIR__ . "/../models/mappers/sprint_mappers.php";
require_once __DIR__ . "/../models/mappers/historia_mappers.php";
require_once __DIR__ . "/../models/repository/sprints_repository/sprints_repository_interface.php";
require_once __DIR__ . "/../models/repository/sprints_repository/sprints_repository.php";
require_once __DIR__ . "/../models/repository/historias_repository/historias_repository_interface.php";
require_once __DIR__ . "/../models/repository/historias_repository/historias_repository.php";
require_once __DIR__ . "/../models/config/models_config.php";
require_once __DIR__ . "/../controllers/mappers/sprint_mappers_controller.php";
require_once __DIR__ . "/../controllers/mappers/historia_mappers_controller.php";
require_once __DIR__ . "/../controllers/sprints/sprints_controller_interface.php";
require_once __DIR__ . "/../controllers/sprints/sprints_controller.php";
require_once __DIR__ . "/../controllers/historias/historias_controller_interface.php";
require_once __DIR__ . "/../controllers/historias/historias_controller.php";
require_once __DIR__ . "/../controllers/config/controllers_config.php";

use app\controllers\config\ControllersConfig;

$id = empty($_GET["id"]) ? 0 : (int)$_GET["id"];
$sprintActualId = empty($_GET["idSprint"]) ? 0 : (int)$_GET["idSprint"];
$titulo = "Crear una nueva historia";
$titleHis = "";
$descripcionHis = "";
$responsableHis = "";
$estadoHis = "nueva";
$puntosHis = "1";
$fechaIniHis = "";
$fechaFinHis = "";
$textBtn = "Crear historia";
$action = "operaciones/historia/registrar_historia.php";

$sprintsController = ControllersConfig::get_sprints_controller();
$sprints = $sprintsController->get_sprints_all();
if (empty($sprints)) {
    header("Location: sprint_form.php?sinReg=1");
    exit;
}

$historiasController = ControllersConfig::get_historias_controller();
if ($id > 0) {
    $historia = $historiasController->get_historia($id);
    if ($historia === null) {
        http_response_code(404);
        exit("No se encontró la historia solicitada.");
    }

    $titulo = "Editar historia";
    $titleHis = $historia->getTitulo();
    $descripcionHis = $historia->getDescripcion();
    $responsableHis = $historia->getResponsable();
    $estadoHis = $historia->getEstado();
    $puntosHis = (string)$historia->getPuntos();
    $fechaIniHis = $historia->getFechaCreacion();
    $fechaFinalizacion = $historia->getFechaFinalizacionDateTime();
    $fechaFinHis = $fechaFinalizacion ? $fechaFinalizacion->format("Y-m-d") : "";
    $sprintActualId = $historia->getSprintId();
    $textBtn = "Guardar cambios";
    $action = "operaciones/historia/actualizar_historia.php";
}

if ($sprintActualId < 1 || !in_array($sprintActualId, array_map(
    static fn($sprint): int => $sprint->getId(),
    $sprints
), true)) {
    $sprintActualId = $sprints[0]->getId();
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($titulo); ?></title>
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
    <main class="form-page">
        <section class="form-card">
            <h1><?php echo e($titulo); ?></h1>
            <?php if (!empty($_GET["error"])): ?>
                <p class="notice notice--error" role="alert">No se pudo guardar la historia. Revise los campos e inténtelo de nuevo.</p>
            <?php endif; ?>
            <form class="form-grid" action="<?php echo e($action); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="form-field">
            <label for="titulo">Título</label>
            <input id="titulo" type="text" name="titulo" value="<?php echo e($titleHis); ?>" required>
        </div>
        <div class="form-field">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" required><?php echo e($descripcionHis); ?></textarea>
        </div>
        <div class="form-field">
            <label for="responsable">Responsable</label>
            <input id="responsable" type="text" name="responsable" value="<?php echo e($responsableHis); ?>" required>
        </div>
        <div class="form-field">
            <label for="sprint_id">Sprint</label>
            <select id="sprint_id" name="sprint_id" required>
                <?php foreach ($sprints as $sprint): ?>
                    <option value="<?php echo $sprint->getId(); ?>" <?php echo $sprint->getId() === $sprintActualId ? "selected" : ""; ?>>
                        <?php echo e($sprint->getNombre()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-field">
            <label for="estado">Estado</label>
            <select id="estado" name="estado" required>
                <?php
                $estados = [
                    "nueva" => "Nueva",
                    "activa" => "Activa",
                    "finalizada" => "Finalizada",
                    "impedimento" => "Con impedimento"
                ];
                foreach ($estados as $valor => $etiqueta):
                ?>
                    <option value="<?php echo e($valor); ?>" <?php echo $estadoHis === $valor ? "selected" : ""; ?>>
                        <?php echo e($etiqueta); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-field">
            <label for="puntos">Puntos de complejidad</label>
            <select id="puntos" name="puntos" required>
                <?php foreach ([1, 2, 3, 5, 8, 13, 21] as $puntos): ?>
                    <option value="<?php echo $puntos; ?>" <?php echo (int)$puntosHis === $puntos ? "selected" : ""; ?>>
                        <?php echo $puntos; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-field">
            <label for="fecha_creacion">Fecha de creación</label>
            <input id="fecha_creacion" type="date" name="fecha_creacion" value="<?php echo e($fechaIniHis); ?>" required>
            <label for="fecha_finalizacion">Fecha de finalización</label>
            <input id="fecha_finalizacion" type="date" name="fecha_finalizacion" value="<?php echo e($fechaFinHis); ?>">
        </div>
                <div class="form-actions">
                    <a class="secondary-link" href="inicio.php?id=<?php echo $sprintActualId; ?>">Cancelar</a>
                    <button class="button button--primary" type="submit"><?php echo e($textBtn); ?></button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
