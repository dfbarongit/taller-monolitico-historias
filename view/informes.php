<?php
require "../models/database/historias_db_interface.php";
require "../models/database/historias_db.php";
require "../models/entities/Sprint.php";
require "../models/mappers/sprint_mappers.php";
require "../models/repository/sprints_repository/sprints_repository_interface.php";
require "../models/repository/sprints_repository/sprints_repository.php";
require "../models/repository/informes_repository/informes_repository_interface.php";
require "../models/repository/informes_repository/informes_repository.php";
require "../models/config/models_config.php";

require "../controllers/mappers/sprint_mappers_controller.php";
require "../controllers/mappers/historia_mappers_controller.php";
require "../controllers/sprints/sprints_controller_interface.php";
require "../controllers/sprints/sprints_controller.php";
require "../controllers/informes/informes_controller_interface.php";
require "../controllers/informes/informes_controller.php";
require "../controllers/config/controllers_config.php";
use app\controllers\config\ControllersConfig;

$controllerSpr = ControllersConfig::get_sprints_controller();
$sprints = $controllerSpr->get_sprints_all();

$idSprintActual = (empty($_GET["id"])) ? -1 : $_GET["id"];
$sprint = "";
$controllerInf = ControllersConfig::get_informes_controller();
$sprintSeleccionado = "";
foreach ($sprints as $spr) {
    if ($idSprintActual == $spr->getId()) {
        $sprint = $spr;
        break;
    }
}
//si el id es -1, se muestran los informes globales, sino se muestran los informes del sprint seleccionado
if ($idSprintActual == -1) {
    $historiasPorEstado = $controllerInf->get_conteo_por_estado();
    $historiasPorResponsable = $controllerInf->get_conteo_por_responsable();
    $sprintSeleccionado = "Todos los sprints";
} else {
    $historiasPorEstado = $controllerInf->get_conteo_por_estado_por_sprint($idSprintActual);
    $historiasPorResponsable = $controllerInf->get_conteo_por_responsable_por_sprint($idSprintActual);
    $sprintSeleccionado = (array_search($sprint, $sprints) + 1) . ': ' . $sprint->getNombre();
}




/*if (empty($sprint)) {
    header("Location: sprint_form.php?sinReg=1");
}*/

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes por sprint</title>
    <link rel="stylesheet" href="assets/css/app.css">
</head>

<body class="app-shell">
    <header>
        <div class="topbar">
            <h1>Gestor de historias de usuario</h1>
            <nav aria-label="Navegacion principal">
                <ul>
                    <li><a href="inicio.php">Sprints</a></li>
                    <li><a class="active" href="informes.php">Informes</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="report-layout">
        <aside class="report-nav">
            <nav aria-label="Filtros de informes">
                <ul>
                    <li><a class="<?php echo $idSprintActual == -1 ? 'active' : ''; ?>" href="informes.php">Global
                            (Todos los sprints)</a></li>
                    <?php foreach ($sprints as $spr) { ?>
                        <li>
                            <a class="<?php echo $idSprintActual == $spr->getId() ? 'active' : ''; ?>"
                                href="informes.php?id=<?php echo $spr->getId(); ?>">
                                Informe del Sprint <?php echo array_search($spr, $sprints) + 1; ?>:
                                <?php echo htmlspecialchars($spr->getNombre()); ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <?php if (!empty($_GET['error'])) { ?>
                <p class="error-message">Error al guardar los datos del usuario.</p>
            <?php } ?>

            <section class="page-heading">
                <h2>Informes de <?php echo htmlspecialchars($sprintSeleccionado); ?></h2>
            </section>

            <div class="report-grid">
                <section class="report-section">
                    <h2>Historias por estado</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historiasPorEstado as $item) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(ucfirst($item["estado"])); ?></td>
                                    <td><?php echo htmlspecialchars($item["total"]); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </section>

                <section class="report-section">
                    <h2>Historias por responsable</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Responsable</th>
                                <th>Total</th>
                                <th>Nuevas</th>
                                <th>Activas</th>
                                <th>Finalizadas</th>
                                <th>Impedimentos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historiasPorResponsable as $item) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item["responsable"]); ?></td>
                                    <td><?php echo htmlspecialchars($item["total"]); ?></td>
                                    <td><?php echo htmlspecialchars($item["nuevas"]); ?></td>
                                    <td><?php echo htmlspecialchars($item["activas"]); ?></td>
                                    <td><?php echo htmlspecialchars($item["finalizadas"]); ?></td>
                                    <td>
                                        <span <?php echo $item["impedimentos"] > 0 ? 'class="impedimento"' : ''; ?>>
                                            <?php echo htmlspecialchars($item["impedimentos"]); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </main>
    </div>
</body>

</html>