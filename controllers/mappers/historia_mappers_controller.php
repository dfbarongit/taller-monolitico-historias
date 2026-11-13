<?php
namespace app\controllers\mappers;
use app\models\entities\Historia;
class HistoriaMappersController
{
    public static function to_entity(array $request): Historia
    {
        return new Historia(
            (empty($request['id'])) ? 0 : $request['id'],
            $request['titulo'],
            $request['descripcion'],
            $request['responsable'],
            $request['estado'],
            $request['puntos'],
            $request['fecha_creacion'],
            $request['fecha_finalizacion'],
            $request['sprint_id']
        );
    }
}
?>