<?php
namespace Dao\Generator;

use Dao\Table;

class Generator extends Table
{
  public static function getDescription(string $tableName): array
  {
    $sql = "DESCRIBE `$tableName`;";
    return self::obtenerRegistros($sql, []);
  }

  
  public static function getTables(): array
  {
    $sql = "SHOW TABLES;";
    $rows = self::obtenerRegistros($sql, []);

    $out = [];
    foreach ($rows as $r) {
      $out[] = array_values($r)[0]; 
    return $out;
  }
}
}