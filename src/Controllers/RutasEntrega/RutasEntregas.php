<?php

namespace Controllers\RutasEntrega;

use Controllers\PublicController;
use Views\Renderer;
use Dao\RutasEntrega\RutasEntrega as RutasEntregaDao;

class RutasEntregas extends PublicController
{
    private array $viewData = [];

    public function run(): void
    {
        $this->viewData["rutas"] = RutasEntregaDao::getAll();
        Renderer::render("rutasentrega/rutasentregas", $this->viewData);
    }
}