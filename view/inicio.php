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

function escape_html(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

$sprintsController = ControllersConfig::get_sprints_controller();
$sprints = $sprintsController->get_sprints_all();

if (empty($sprints)) {
    header("Location: sprint_form.php?sinReg=1");
    exit;
}

$idSprintActual = empty($_GET["id"]) ? $sprints[0]->getId() : (int)$_GET["id"];
$sprint = null;
foreach ($sprints as $sprintItem) {
    if ($idSprintActual === $sprintItem->getId()) {
        $sprint = $sprintItem;
        break;
    }
}
if ($sprint === null) {
    header("Location: inicio.php");
    exit;
}

$historiasController = ControllersConfig::get_historias_controller();
$historiasDelSprint = $historiasController->get_historias_by_sprint($idSprintActual);
$totalHistoriasSprint = count($historiasDelSprint);
$busqueda = isset($_GET["q"]) && is_string($_GET["q"]) ? trim($_GET["q"]) : "";
$responsableSeleccionado = isset($_GET["responsable"]) && is_string($_GET["responsable"])
    ? $_GET["responsable"]
    : "";
$estadoColumnas = [
    "nueva" => ["titulo" => "Nuevas", "clase" => "new"],
    "activa" => ["titulo" => "Activas", "clase" => "active"],
    "impedimento" => ["titulo" => "En impedimento", "clase" => "blocked"],
    "finalizada" => ["titulo" => "Finalizadas", "clase" => "done"]
];
$responsables = [];
$puntosTotales = 0;
$puntosFinalizados = 0;

foreach ($historiasDelSprint as $historia) {
    $estado = $historia->getEstado();
    $responsable = $historia->getResponsable();
    if (!in_array($responsable, $responsables, true)) {
        $responsables[] = $responsable;
    }
    $puntosTotales += $historia->getPuntos();
    if ($estado === "finalizada") {
        $puntosFinalizados += $historia->getPuntos();
    }
}
sort($responsables, SORT_NATURAL | SORT_FLAG_CASE);

$historiasFiltradas = array_filter($historiasDelSprint, static function ($historia) use ($busqueda, $responsableSeleccionado): bool {
    $coincideResponsable = $responsableSeleccionado === "" ||
        $historia->getResponsable() === $responsableSeleccionado;
    $texto = "HU-" . str_pad((string)$historia->getId(), 3, "0", STR_PAD_LEFT) . " " .
        $historia->getId() . " " . $historia->getTitulo() . " " .
        $historia->getDescripcion() . " " . $historia->getResponsable();
    $coincideBusqueda = $busqueda === "" || stripos($texto, $busqueda) !== false;
    return $coincideResponsable && $coincideBusqueda;
});

$historiasPorEstado = array_fill_keys(array_keys($estadoColumnas), []);
foreach ($historiasFiltradas as $historia) {
    $estado = $historia->getEstado();
    if (isset($historiasPorEstado[$estado])) {
        $historiasPorEstado[$estado][] = $historia;
    }
}

$porcentajeCompletado = $puntosTotales > 0 ? (int)round($puntosFinalizados / $puntosTotales * 100) : 0;

$fechaInicio = new DateTimeImmutable($sprint->getFechaInicio());
$fechaFin = new DateTimeImmutable($sprint->getFechaFin());
$hoy = new DateTimeImmutable("today");
$estadoSprint = $hoy > $fechaFin ? "Cerrado" : ($hoy < $fechaInicio ? "Próximo" : "En curso");
$estadoSprintClase = $hoy > $fechaFin ? "closed" : ($hoy < $fechaInicio ? "upcoming" : "current");
$duracionSprint = $fechaInicio->diff($fechaFin)->days;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero Sprint | Gestor de Historias</title>
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
    <header class="app-header">
        <a class="brand" href="inicio.php" aria-label="Gestor de Historias de Usuario">
            <span class="brand-mark">GH</span>
            <span class="brand-copy">
                <strong>Gestor de Historias de Usuario</strong>
                <small>Metodología ágil para equipos y docencia</small>
            </span>
        </a>
        <nav class="main-nav" aria-label="Navegación principal">
            <a class="nav-link nav-link--active" href="inicio.php?id=<?php echo $idSprintActual; ?>">Tablero Sprint</a>
            <a class="nav-link" href="inicio.php?id=<?php echo $idSprintActual; ?>#board">Historias y Backlog</a>
            <a class="nav-link" href="informes.php?idSprint=<?php echo $idSprintActual; ?>">Informes y Métricas</a>
        </nav>
        <a class="button button--primary header-action" href="historia_form.php?idSprint=<?php echo $idSprintActual; ?>">+ Nueva historia</a>
    </header>

    <main class="page-content">
        <?php if (!empty($_GET["error"])): ?>
            <p class="notice notice--error" role="alert">No se pudo completar la operación. Verifique los datos e inténtelo de nuevo.</p>
        <?php endif; ?>

        <section class="sprint-switcher" aria-label="Sprints">
            <span class="section-label">Sprints</span>
            <div class="sprint-tabs">
                <?php foreach ($sprints as $sprintItem): ?>
                    <a class="sprint-tab <?php echo $sprintItem->getId() === $idSprintActual ? "sprint-tab--active" : ""; ?>"
                       href="inicio.php?id=<?php echo $sprintItem->getId(); ?>">
                        Sprint <?php echo $sprintItem->getId(); ?>
                        <?php if ($sprintItem->getId() === $idSprintActual): ?><span class="active-dot"></span><?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="sprint-actions">
                <a class="text-link" href="sprint_form.php?id=<?php echo $idSprintActual; ?>">Editar sprint</a>
                <a class="text-link" href="sprint_form.php">+ Nuevo sprint</a>
            </div>
        </section>

        <section class="sprint-summary">
            <div class="sprint-summary__main">
                <div class="sprint-title-row">
                    <h1>Sprint <?php echo $sprint->getId(); ?>: <?php echo escape_html($sprint->getNombre()); ?></h1>
                    <span class="status-pill status-pill--<?php echo $estadoSprintClase; ?>"><?php echo escape_html($estadoSprint); ?></span>
                </div>
                <p class="sprint-dates">
                    <span><?php echo $fechaInicio->format("d/m/Y"); ?> - <?php echo $fechaFin->format("d/m/Y"); ?></span>
                    <span><?php echo $duracionSprint; ?> días</span>
                </p>
            </div>
            <div class="points-summary" aria-label="Puntos completados">
                <div class="points-summary__heading">
                    <span>Puntos de historia</span>
                    <strong><?php echo $porcentajeCompletado; ?>%</strong>
                </div>
                <div class="points-summary__value">
                    <strong><?php echo $puntosFinalizados; ?></strong>
                    <span>/ <?php echo $puntosTotales; ?> pts</span>
                </div>
                <div class="progress-track">
                    <span style="width: <?php echo $porcentajeCompletado; ?>%"></span>
                </div>
            </div>
        </section>

        <form class="board-toolbar" method="get" action="inicio.php" aria-label="Filtros de historias">
            <input type="hidden" name="id" value="<?php echo $idSprintActual; ?>">
            <label class="search-field">
                <span class="search-icon" aria-hidden="true"></span>
                <input type="search" name="q" value="<?php echo escape_html($busqueda); ?>" aria-label="Buscar por título, código o responsable" placeholder="Buscar por título, código o responsable...">
            </label>
            <div class="board-toolbar__right">
                <label class="filter-field">
                    <span>Responsable</span>
                    <select name="responsable">
                        <option value="">Todos los responsables</option>
                        <?php foreach ($responsables as $responsable): ?>
                            <option value="<?php echo escape_html($responsable); ?>" <?php echo $responsable === $responsableSeleccionado ? "selected" : ""; ?>>
                                <?php echo escape_html($responsable); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button class="button button--primary" type="submit">Filtrar</button>
                <a class="text-link" href="inicio.php?id=<?php echo $idSprintActual; ?>">Limpiar</a>
                <span class="results-count">Mostrando <?php echo count($historiasFiltradas); ?> de <?php echo $totalHistoriasSprint; ?> historias</span>
            </div>
        </form>

        <section class="kanban-board" id="board" aria-label="Historias agrupadas por estado">
            <?php foreach ($estadoColumnas as $estado => $columna): ?>
                <?php $historiasColumna = $historiasPorEstado[$estado]; ?>
                <section class="kanban-column kanban-column--<?php echo $columna["clase"]; ?>">
                    <header class="column-header">
                        <h2><span class="state-indicator"></span><?php echo escape_html($columna["titulo"]); ?></h2>
                        <span class="column-count"><?php echo count($historiasColumna); ?></span>
                        <span class="column-points">
                            <?php echo array_sum(array_map(static fn($item): int => $item->getPuntos(), $historiasColumna)); ?> pts
                        </span>
                    </header>
                    <div class="column-cards">
                        <?php foreach ($historiasColumna as $historia): ?>
                            <?php
                            $codigoHistoria = "HU-" . str_pad((string)$historia->getId(), 3, "0", STR_PAD_LEFT);
                            $fechaFinHistoria = $historia->getFechaFinalizacionDateTime();
                            ?>
                            <article class="story-card">
                                <div class="story-card__meta">
                                    <span class="story-id"><?php echo $codigoHistoria; ?></span>
                                    <span class="story-points"><?php echo $historia->getPuntos(); ?> pts</span>
                                    <a class="icon-link" href="historia_form.php?idSprint=<?php echo $idSprintActual; ?>&id=<?php echo $historia->getId(); ?>" aria-label="Editar historia">Editar</a>
                                </div>
                                <h3><?php echo escape_html($historia->getTitulo()); ?></h3>
                                <p class="story-description"><?php echo escape_html($historia->getDescripcion()); ?></p>
                                <div class="story-card__footer">
                                    <span class="responsible-avatar" aria-hidden="true"><?php echo escape_html(strtoupper(substr($historia->getResponsable(), 0, 1))); ?></span>
                                    <span class="story-responsible"><?php echo escape_html($historia->getResponsable()); ?></span>
                                </div>
                                <div class="story-card__dates">
                                    <span>Creada <?php echo escape_html($historia->getFechaCreacion()); ?></span>
                                    <span><?php echo $fechaFinHistoria ? "Fin " . escape_html($fechaFinHistoria->format("Y-m-d")) : "Sin fecha fin"; ?></span>
                                </div>
                                <form class="delete-form" action="operaciones/historia/eliminar_historia.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $historia->getId(); ?>">
                                    <input type="hidden" name="sprint_id" value="<?php echo $idSprintActual; ?>">
                                    <button class="delete-button" type="submit">Eliminar</button>
                                </form>
                            </article>
                        <?php endforeach; ?>
                        <p class="column-empty <?php echo empty($historiasColumna) ? "" : "is-hidden"; ?>">
                            <?php
                            if (empty($historiasColumna)) {
                                echo empty($historiasFiltradas) && $totalHistoriasSprint > 0
                                    ? "No se encontraron historias con esos filtros."
                                    : "No hay historias en este estado.";
                            }
                            ?>
                        </p>
                    </div>
                    <?php if ($estado === "nueva"): ?>
                        <a class="column-add" href="historia_form.php?idSprint=<?php echo $idSprintActual; ?>">+ Crear historia</a>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>
        </section>
    </main>
</body>
</html>
