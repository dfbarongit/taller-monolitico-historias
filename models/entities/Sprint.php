<?php
namespace app\models\entities;
use DateTime;
class Sprint
{
    private int $id;
    private string $nombre;
    private DateTime $fecha_inicio;
    private DateTime $fecha_fin;
    private DateTime $created_at;
    private DateTime $updated_at;

    public function __construct(
        int $id,
        string $nombre,
        string $fecha_inicio,
        string $fecha_fin,
        string $created_at = null,
        string $updated_at = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->fecha_inicio = new DateTime($fecha_inicio);
        $this->fecha_fin = new DateTime($fecha_fin);
        $this->created_at = new DateTime($created_at);
        $this->updated_at = new DateTime($updated_at);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getFechaInicio(): string
    {
        return $this->fecha_inicio->format('Y-m-d');
    }

    public function getFechaFin(): string
    {
        return $this->fecha_fin->format('Y-m-d');
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