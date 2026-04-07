<?php

namespace Dao\RutasEntrega;

use Dao\Table;

class RutasEntrega extends Table
{
    public static function getAll()
    {
        $sqlstr = "SELECT * FROM RutasEntrega;";
        return self::obtenerRegistros($sqlstr, []);
    }

    public static function getById(int $id_ruta)
    {
        $sqlstr = "SELECT * FROM RutasEntrega WHERE id_ruta = :id_ruta;";
        return self::obtenerUnRegistro($sqlstr, ["id_ruta" => $id_ruta]);
    }

    public static function insertRuta(
        string $origen,
        string $destino,
        float $distancia_km,
        int $duracion_min
    ) {
        $sqlstr = "INSERT INTO RutasEntrega
                    (origen, destino, distancia_km, duracion_min)
                   VALUES
                    (:origen, :destino, :distancia_km, :duracion_min);";

        return self::executeNonQuery(
            $sqlstr,
            [
                "origen" => $origen,
                "destino" => $destino,
                "distancia_km" => $distancia_km,
                "duracion_min" => $duracion_min
            ]
        );
    }

    public static function updateRuta(
        int $id_ruta,
        string $origen,
        string $destino,
        float $distancia_km,
        int $duracion_min
    ) {
        $sqlstr = "UPDATE RutasEntrega
                   SET origen = :origen,
                       destino = :destino,
                       distancia_km = :distancia_km,
                       duracion_min = :duracion_min
                   WHERE id_ruta = :id_ruta;";

        return self::executeNonQuery(
            $sqlstr,
            [
                "id_ruta" => $id_ruta,
                "origen" => $origen,
                "destino" => $destino,
                "distancia_km" => $distancia_km,
                "duracion_min" => $duracion_min
            ]
        );
    }

    public static function deleteRuta(int $id_ruta)
    {
        $sqlstr = "DELETE FROM RutasEntrega WHERE id_ruta = :id_ruta;";
        return self::executeNonQuery(
            $sqlstr,
            [
                "id_ruta" => $id_ruta
            ]
        );
    }
}
?>