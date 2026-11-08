<?php
namespace app\models\entities;

use DateTime;
class Historia
{
    private int $id;
    private string $titulo;
    private string $descripcion;
    private string $responsable;
    private string $estado;
    private int $puntos;
    private DateTime $fecha_creacion;
    private DateTime|null $fecha_finalizacion;
    private int $sprint_id;
    private DateTime $created_at;
    private DateTime $updated_at;

    public function __construct(
        int $id,
        string $titulo,
        string $descripcion,
        string $responsable,
        string $estado,
        int $puntos,
        DateTime $fecha_creacion,
        DateTime $fecha_finalizacion = null,
        int $sprint_id,
        DateTime $created_at,
        DateTime $updated_at
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->responsable = $responsable;
        $this->estado = $estado;
        $this->puntos = $puntos;
        $this->fecha_creacion = $fecha_creacion;
        $this->fecha_finalizacion = $fecha_finalizacion;
        $this->sprint_id = $sprint_id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function getResponsable(): string
    {
        return $this->responsable;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function getPuntos(): int
    {
        return $this->puntos;
    }

    public function getFechaCreacion(): string
    {
        return $this->fecha_creacion->format('Y-M-D');
    }

    public function getFechaFinalizacion(): string
    {
        if (!empty($this->fecha_finalizacion)) {
            return $this->fecha_finalizacion->format('Y-M-D');
        } else {
            return "Sin fecha de fin";
        }
    }

    public function getSprintId(): int
    {
        return $this->sprint_id;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updated_at;
    }
}
?>