<?php
namespace Controllers\Productos;

use Controllers\PublicController;
use Views\Renderer;

class Hello extends PublicController
{
    public function run(): void
    {
        $viewData = [];
        Renderer::render("productos/hello", $viewData);
    }
}