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
    private ?DateTime $fecha_finalizacion;
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
        DateTime|string $fecha_creacion,
        DateTime|string|null $fecha_finalizacion,
        int $sprint_id,
        DateTime|string|null $created_at = null,
        DateTime|string|null $updated_at = null
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->responsable = $responsable;
        $this->estado = $estado;
        $this->puntos = $puntos;
        $this->fecha_creacion = $fecha_creacion instanceof DateTime
            ? $fecha_creacion
            : new DateTime($fecha_creacion);
        $this->fecha_finalizacion = $fecha_finalizacion === null
            ? null
            : ($fecha_finalizacion instanceof DateTime ? $fecha_finalizacion : new DateTime($fecha_finalizacion));
        $this->sprint_id = $sprint_id;
        $this->created_at = $created_at instanceof DateTime
            ? $created_at
            : new DateTime($created_at ?? "now");
        $this->updated_at = $updated_at instanceof DateTime
            ? $updated_at
            : new DateTime($updated_at ?? "now");
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
        return $this->fecha_creacion->format('Y-m-d');
    }

    public function getFechaFinalizacion(): string
    {
        if (!empty($this->fecha_finalizacion)) {
            return $this->fecha_finalizacion->format('Y-m-d');
        } else {
            return "Sin fecha de fin";
        }
    }

    public function getFechaCreacionDateTime(): DateTime
    {
        return $this->fecha_creacion;
    }

    public function getFechaFinalizacionDateTime(): ?DateTime
    {
        return $this->fecha_finalizacion;
    }

    public function getSprintId(): int
    {
        return $this->sprint_id;
    }

    public function getCreatedAt(): DateTime|null
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTime|null
    {
        return $this->updated_at;
    }
}
?>