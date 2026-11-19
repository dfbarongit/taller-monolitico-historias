<?php
require "../models/database/historias_db_interface.php";
require "../models/database/historias_db.php";
require "../models/entities/Sprint.php";
require "../models/entities/Historia.php";
require "../models/mappers/sprint_mappers.php";
require "../models/mappers/historia_mappers.php";
require "../models/repository/sprints_repository/sprints_repository_interface.php";
require "../models/repository/sprints_repository/sprints_repository.php";
require "../models/repository/historias_repository/historias_repository_interface.php";
require "../models/repository/historias_repository/historias_repository.php";
require "../models/config/models_config.php";

require "../controllers/mappers/sprint_mappers_controller.php";
require "../controllers/mappers/historia_mappers_controller.php";
require "../controllers/sprints/sprints_controller_interface.php";
require "../controllers/sprints/sprints_controller.php";
require "../controllers/historias/historias_controller_interface.php";
require "../controllers/historias/historias_controller.php";
require "../controllers/config/controllers_config.php";
use app\controllers\config\ControllersConfig;

$controllerSpr = ControllersConfig::get_sprints_controller();
$sprints = $controllerSpr->get_sprints_all();

$idSprintActual = (empty($_GET["id"])) ? $sprints[0]->getId() : $_GET["id"];
$sprint = "";

$controllerHis = ControllersConfig::get_historias_controller();
$historiasDelSprint = $controllerHis->get_historias_by_sprint($idSprintActual);


foreach ($sprints as $spr) {
    if ($idSprintActual == $spr->getId()) {
        $sprint = $spr;
        break;
    }
}
if (empty($sprint)) {
    header("Location: sprint_form.php?sinReg=1");
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de historias de usuario</title>
    <link rel="stylesheet" href="assets/css/app.css">
</head>

<body class="app-shell">
    <header>
        <div class="topbar">
            <h1>Gestor de historias de usuario</h1>
            <nav aria-label="Navegacion principal">
                <ul>
                    <li><a class="active" href="inicio.php">Sprints</a></li>
                    <li><a href="informes.php">Informes</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="app-layout">
        <aside class="sidebar">
            <nav class="sprint-nav" aria-label="Sprints">
                <ul>
                    <?php foreach ($sprints as $spr) { ?>
                        <li>
                            <a class="<?php echo $idSprintActual == $spr->getId() ? 'active' : ''; ?>"
                               href="inicio.php?id=<?php echo $spr->getId(); ?>">
                                Sprint <?php echo array_search($spr, $sprints) + 1; ?>: <?php echo htmlspecialchars($spr->getNombre()); ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <a class="new-link" href="sprint_form.php">Nuevo Sprint</a>
            </nav>
        </aside>
        <main class="main-content">
            <?php if (!empty($_GET['error'])) { ?>
                <p class="error-message">Error al guardar los datos del usuario.</p>
            <?php } ?>

            <section class="sprint-header">
                <div class="sprint-header-title">
                    <h2><?php echo htmlspecialchars($sprint->getNombre()); ?></h2>
                    <div>
                        <span class="story-info">Fecha inicio: <strong><?php echo htmlspecialchars($sprint->getFechaInicio()); ?></strong></span>
                        <span class="story-info">Fecha fin: <strong><?php echo htmlspecialchars($sprint->getFechaFin()); ?></strong></span>
                    </div>
                </div>
                <div class="actions">
                    <a href="sprint_form.php?id=<?php echo $idSprintActual; ?>">Modificar sprint</a>
                    <a class="danger-link" href="operaciones/sprint/eliminar_sprint.php?id=<?php echo $idSprintActual; ?>">Eliminar sprint</a>
                </div>
            </section>

            <a class="button button-primary create-story" href="historia_form.php?idSprint=<?php echo $idSprintActual; ?>">Crear nueva historia</a>

            <section class="story-grid" aria-label="Historias del sprint">
                <?php if (empty($historiasDelSprint)) { ?>
                    <p class="empty-state">Este sprint todavia no tiene historias registradas.</p>
                <?php } ?>
                <?php foreach ($historiasDelSprint as $historia) { ?>
                    <article class="story-card">
                        <h3><?php echo htmlspecialchars($historia->getTitulo()); ?></h3>
                        <p><?php echo htmlspecialchars($historia->getDescripcion()); ?></p>
                        <div class="story-meta">
                            <span class="story-info">
                                Estado: <strong class="<?php echo $historia->getEstado() == 'impedimento' ? 'impedimento' : '' ?>"><?php echo htmlspecialchars(ucfirst($historia->getEstado())); ?></strong>
                            </span>
                            <span class="story-info">Puntos: <strong><?php echo htmlspecialchars($historia->getPuntos()); ?></strong></span>
                            <span class="story-info">Inicio: <strong><?php echo htmlspecialchars($historia->getFechaCreacion()); ?></strong></span>
                            <span class="story-info">Cierre: <strong><?php echo htmlspecialchars($historia->getFechaFinalizacion()); ?></strong></span>
                        </div>
                        <a class="button" href="historia_form.php?idSprint=<?php echo $idSprintActual; ?>&amp;id=<?php echo $historia->getId(); ?>">Modificar historia</a>
                        <a class="button danger-link" href="operaciones/historia/eliminar_historia.php?idSprint=<?php echo $idSprintActual; ?>&amp;id=<?php echo $historia->getId(); ?>">Eliminar historia</a>
                    </article>
                <?php } ?>
            </section>
        </main>
    </div>
</body>

</html>