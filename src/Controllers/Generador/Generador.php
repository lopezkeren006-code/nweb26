<?php

namespace Controllers\Generator;

use Controllers\PublicController;
use Views\Renderer;

class Generator extends PublicController
{
  public function run(): void
  {
    $viewData = [];

    $viewData["tables"] = \Dao\Generator\Generator::getTables();

    if ($this->isPostBack()) {
      $table = $_POST["table"] ?? "";
      $viewData["table"] = $table;

      $viewData["columns"] = \Dao\Generator\Generator::getDescription($table);

      $gen = new GeneratorHelper($viewData["columns"], $table);
      $viewData["genResult"] = $gen->getDaoPhpCode();
      $viewData["genController"] = $gen->getControllerPhpCode();
      $viewData["genSimpleController"] = $gen->getSimpleControllerPhpCode();
      $viewData["genForm"] = $gen->getFormTemplateCode();
      $viewData["genList"] = $gen->getListTemplateCode();
    }

    
    Renderer::render("generator/generador", $viewData);
  }
}