<?php

namespace Dao\Carretilla;

use Dao\Table;

class Carretilla extends Table
{
    public static function addItem(int $usercod, int $productId, int $quantity, float $unitPrice): bool
    {
        return self::executeNonQuery(
            "INSERT INTO carretilla (usercod, productId, crrctd, crrprc, crrfching)
             VALUES (:usercod, :productId, :quantity, :unitPrice, NOW())
             ON DUPLICATE KEY UPDATE crrctd = crrctd + :quantity, crrprc = :unitPrice, crrfching = NOW()",
            [
                "usercod" => $usercod,
                "productId" => $productId,
                "quantity" => $quantity,
                "unitPrice" => $unitPrice
            ]
        );
    }

    public static function getItemsByUser(int $usercod): array
    {
        return self::obtenerRegistros(
            "SELECT
                c.productId,
                c.usercod,
                c.crrctd AS cantidad,
                COALESCE(p.productPrice, c.crrprc) AS precio,
                p.productName,
                p.productImgUrl
             FROM carretilla c
             LEFT JOIN products p ON c.productId = p.productId
             WHERE c.usercod = :usercod",
            [
                "usercod" => $usercod
            ]
        );
    }

    public static function removeItem(int $usercod, int $productId): bool
    {
        return self::executeNonQuery(
            "DELETE FROM carretilla
             WHERE usercod = :usercod AND productId = :productId",
            [
                "usercod" => $usercod,
                "productId" => $productId
            ]
        );
    }

    public static function clearCart(int $usercod): bool
    {
        return self::executeNonQuery(
            "DELETE FROM carretilla WHERE usercod = :usercod",
            [
                "usercod" => $usercod
            ]
        );
    }
}