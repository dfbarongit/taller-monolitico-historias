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

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

$sprintsController = ControllersConfig::get_sprints_controller();
$sprints = $sprintsController->get_sprints_all();
$sprintSeleccionado = null;
$conteoPorEstado = [];
$conteoPorResponsable = [];

if (!empty($sprints)) {
    $sprintId = empty($_GET["idSprint"]) ? $sprints[0]->getId() : (int)$_GET["idSprint"];
    foreach ($sprints as $sprint) {
        if ($sprint->getId() === $sprintId) {
            $sprintSeleccionado = $sprint;
            break;
        }
    }
    if ($sprintSeleccionado === null) {
        $sprintSeleccionado = $sprints[0];
    }

    $historiasController = ControllersConfig::get_historias_controller();
    $conteoPorEstado = $historiasController->get_conteo_por_estado($sprintSeleccionado->getId());
    $conteoPorResponsable = $historiasController->get_conteo_por_responsable_y_estado($sprintSeleccionado->getId());
}

$estados = ["nueva", "activa", "finalizada", "impedimento"];
$totales = array_fill_keys($estados, 0);
foreach ($conteoPorEstado as $fila) {
    if (isset($totales[$fila["estado"]])) {
        $totales[$fila["estado"]] = (int)$fila["total"];
    }
}
$responsables = [];
foreach ($conteoPorResponsable as $fila) {
    $responsable = $fila["responsable"];
    if (!isset($responsables[$responsable])) {
        $responsables[$responsable] = array_fill_keys($estados, 0);
    }
    if (isset($responsables[$responsable][$fila["estado"]])) {
        $responsables[$responsable][$fila["estado"]] = (int)$fila["total"];
    }
}
$totalHistorias = array_sum($totales);
$totalPendientes = $totales["nueva"] + $totales["activa"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes por sprint</title>
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
    <main class="report-page">
        <h1>Informes por sprint</h1>
        <nav>
            <a href="inicio.php">Volver al tablero</a>
        </nav>

        <?php if (empty($sprints)): ?>
            <section class="report-card">
                <p>No hay sprints para generar informes.</p>
                <a class="button button--primary" href="sprint_form.php">Crear sprint</a>
            </section>
        <?php else: ?>
            <form class="report-filter" method="get" action="informes.php">
                <label for="idSprint">Seleccionar sprint
                    <select id="idSprint" name="idSprint">
                        <?php foreach ($sprints as $sprint): ?>
                            <option value="<?php echo $sprint->getId(); ?>" <?php echo $sprint->getId() === $sprintSeleccionado->getId() ? "selected" : ""; ?>>
                                <?php echo e($sprint->getNombre()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button class="button button--primary" type="submit">Ver informe</button>
            </form>

            <section class="report-card">
                <h2><?php echo e($sprintSeleccionado->getNombre()); ?>: resumen general</h2>
                <div class="report-table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Total de historias</th>
                                <th>Finalizadas</th>
                                <th>Pendientes (nuevas + activas)</th>
                                <th>Con impedimento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $totalHistorias; ?></td>
                                <td><?php echo $totales["finalizada"]; ?></td>
                                <td><?php echo $totalPendientes; ?></td>
                                <td><?php echo $totales["impedimento"]; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2>Detalle por responsable</h2>
                <?php if (empty($responsables)): ?>
                    <p>Este sprint todavía no tiene historias.</p>
                <?php else: ?>
                    <div class="report-table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Responsable</th>
                                    <th>Nuevas</th>
                                    <th>Activas</th>
                                    <th>Finalizadas</th>
                                    <th>Con impedimento</th>
                                    <th>Pendientes</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($responsables as $responsable => $conteos): ?>
                                    <?php $pendientesResponsable = $conteos["nueva"] + $conteos["activa"]; ?>
                                    <tr>
                                        <td><?php echo e((string)$responsable); ?></td>
                                        <td><?php echo $conteos["nueva"]; ?></td>
                                        <td><?php echo $conteos["activa"]; ?></td>
                                        <td><?php echo $conteos["finalizada"]; ?></td>
                                        <td><?php echo $conteos["impedimento"]; ?></td>
                                        <td><?php echo $pendientesResponsable; ?></td>
                                        <td><?php echo array_sum($conteos); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
    <?php endif; ?>
    </main>
</body>
</html>
