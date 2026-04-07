<?php
namespace Dao\Products;

use Dao\Table;

class Productss extends Table
{
  public static function getProducts(
    string $partialName = "",
    string $status = "",
    string $orderBy = "",
    bool $orderDescending = false,
    int $page = 0,
    int $itemsPerPage = 10
  ): array {

    $sqlstr = "SELECT 
                  p.productId,
                  p.productName,
                  p.productDescription,
                  p.productPrice,
                  p.productImgUrl,
                  p.productStatus,
                  CASE
                      WHEN p.productStatus = 'ACT' THEN 'Activo'
                      WHEN p.productStatus = 'INA' THEN 'Inactivo'
                      ELSE 'Sin Asignar'
                  END as productStatusDsc
              FROM products p";

    $sqlstrCount = "SELECT COUNT(*) as count FROM products p";

    $conditions = [];
    $params = [];

    if ($partialName !== "") {
      $conditions[] = "p.productName LIKE :partialName";
      $params["partialName"] = "%" . $partialName . "%";
    }

    // Por si llega EMP desde el filtro
    if ($status === "EMP") {
      $status = "";
    }

    if (!in_array($status, ["ACT", "INA", ""], true)) {
      throw new \Exception("Status inválido");
    }

    if ($status !== "") {
      $conditions[] = "p.productStatus = :status";
      $params["status"] = $status;
    }

    if (count($conditions) > 0) {
      $where = " WHERE " . implode(" AND ", $conditions);
      $sqlstr .= $where;
      $sqlstrCount .= $where;
    }

    $allowedOrderBy = ["productId", "productName", "productPrice", ""];
    if (!in_array($orderBy, $allowedOrderBy, true)) {
      throw new \Exception("Campo orderBy inválido");
    }

    if ($orderBy !== "") {
      $sqlstr .= " ORDER BY p." . $orderBy;
      if ($orderDescending) {
        $sqlstr .= " DESC";
      }
    }

    $total = intval(self::obtenerUnRegistro($sqlstrCount, $params)["count"] ?? 0);

    if ($itemsPerPage < 1) $itemsPerPage = 10;

    $pagesCount = (int)ceil($total / $itemsPerPage);
    if ($pagesCount < 1) $pagesCount = 1;

    if ($page < 0) $page = 0;
    if ($page > $pagesCount - 1) $page = $pagesCount - 1;

    $offset = $page * $itemsPerPage;
    $sqlstr .= " LIMIT " . $offset . ", " . $itemsPerPage;

    $rows = self::obtenerRegistros($sqlstr, $params);

    return [
      "products" => $rows,
      "total" => $total,
      "page" => $page,
      "itemsPerPage" => $itemsPerPage
    ];
  }

  public static function getProductById(int $productId): array
  {
    $sqlstr = "SELECT * FROM products WHERE productId = :productId";
    return self::obtenerUnRegistro($sqlstr, ["productId" => $productId]);
  }

  public static function insertProduct(
    string $productName,
    string $productDescription,
    float $productPrice,
    string $productImgUrl,
    string $productStatus
  ): int {
    $sqlstr = "INSERT INTO products
      (productName, productDescription, productPrice, productImgUrl, productStatus)
      VALUES
      (:productName, :productDescription, :productPrice, :productImgUrl, :productStatus)";
    return self::executeNonQuery($sqlstr, [
      "productName" => $productName,
      "productDescription" => $productDescription,
      "productPrice" => $productPrice,
      "productImgUrl" => $productImgUrl,
      "productStatus" => $productStatus
    ]);
  }

  public static function updateProduct(
    int $productId,
    string $productName,
    string $productDescription,
    float $productPrice,
    string $productImgUrl,
    string $productStatus
  ): int {
    $sqlstr = "UPDATE products SET
        productName = :productName,
        productDescription = :productDescription,
        productPrice = :productPrice,
        productImgUrl = :productImgUrl,
        productStatus = :productStatus
      WHERE productId = :productId";
    return self::executeNonQuery($sqlstr, [
      "productId" => $productId,
      "productName" => $productName,
      "productDescription" => $productDescription,
      "productPrice" => $productPrice,
      "productImgUrl" => $productImgUrl,
      "productStatus" => $productStatus
    ]);
  }

  public static function deleteProduct(int $productId): int
  {
    $sqlstr = "DELETE FROM products WHERE productId = :productId";
    return self::executeNonQuery($sqlstr, ["productId" => $productId]);
  }
}