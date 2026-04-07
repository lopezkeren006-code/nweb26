<?php

namespace Controllers\Carretilla;

use Controllers\PublicController;
use Dao\Carretilla\Carretilla as CarretillaDAO;
use Views\Renderer;
use Utilities\Site;

class Carretilla extends PublicController
{
    public function run(): void
    {
        session_start();

        $usercod = $_SESSION["usercod"] ?? 1;

        if ($this->isPostBack() && isset($_POST["productId"], $_POST["quantity"], $_POST["price"])) {
            $productId = intval($_POST["productId"]);
            $quantity = max(1, intval($_POST["quantity"]));
            $price = floatval($_POST["price"]);

            CarretillaDAO::addItem($usercod, $productId, $quantity, $price);
            Site::redirectTo("index.php?page=Carretilla-Carretilla");
            return;
        }

        if (isset($_GET["remove"])) {
            $productId = intval($_GET["remove"]);
            if ($productId > 0) {
                CarretillaDAO::removeItem($usercod, $productId);
            }
            Site::redirectTo("index.php?page=Carretilla-Carretilla");
            return;
        }

        if (isset($_GET["clear"]) && $_GET["clear"] == "1") {
            CarretillaDAO::clearCart($usercod);
            Site::redirectTo("index.php?page=Carretilla-Carretilla");
            return;
        }

        $items = CarretillaDAO::getItemsByUser($usercod);
        $total = 0.0;

        foreach ($items as &$item) {
            $item["subtotal"] = floatval($item["cantidad"]) * floatval($item["precio"]);
            $total += $item["subtotal"];
        }
        unset($item);

        Renderer::render("carretilla/lista", [
            "carretilla" => $items,
            "total" => number_format($total, 2)
        ]);
    }
}