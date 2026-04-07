<?php

namespace Controllers;

use Controllers\PublicController;
use Dao\Cart\Cart;
use Utilities\Site;
use Views\Renderer;

class Carrit extends PublicController
{
    public function run(): void
    {
        session_start();

        $action = $_GET["action"] ?? "view";
        $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = [];
        }

        switch ($action) {
            case "add":
                $this->add($id);
                break;

            case "remove":
                $this->remove($id);
                break;

            case "clear":
                $this->clear();
                break;

            default:
                $this->view();
                break;
        }
    }

    private function add(int $id): void
    {
        $product = Cart::getById($id);

        if ($product) {
            if (isset($_SESSION["cart"][$id])) {
                $_SESSION["cart"][$id]["cantidad"]++;
            } else {
                $_SESSION["cart"][$id] = [
                    "id" => $product["productId"],
                    "nombre" => $product["productName"],
                    "precio" => $product["productPrice"],
                    "imagen" => $product["productImgUrl"],
                    "cantidad" => 1
                ];
            }
        }

        Site::redirectTo("index.php?page=Carrit");
    }

    private function remove(int $id): void
    {
        if (isset($_SESSION["cart"][$id])) {
            unset($_SESSION["cart"][$id]);
        }

        Site::redirectTo("index.php?page=Carrit");
    }

    private function clear(): void
    {
        $_SESSION["cart"] = [];
        Site::redirectTo("index.php?page=Carrit");
    }

    private function view(): void
    {
        $cart = $_SESSION["cart"];
        $total = 0;

        foreach ($cart as $key => $item) {
            $cart[$key]["subtotal"] = $item["precio"] * $item["cantidad"];
            $total += $cart[$key]["subtotal"];
        }

        Renderer::render("paypal/cart", [
            "cart" => $cart,
            "total" => $total
        ]);
    }
}