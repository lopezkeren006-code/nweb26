<?php

namespace Controllers;

use Views\Renderer;

class About extends PublicController
{
    public function run(): void
    {
        $viewData = [
            "nombre" => "Stefany Lopez",
            "correo" => "lopezkeren006@gmail.com",
            "telefono" => "98451226"
        ];

        Renderer::render("about", $viewData);
    }
}