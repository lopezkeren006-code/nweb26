<?php

namespace Controllers\RutasEntrega;

use Controllers\PublicController;
use Views\Renderer;
use Dao\RutasEntrega\RutasEntrega as RutasEntregaDao;
use Utilities\Site;
use Utilities\Validators;

class RutaEntrega extends PublicController
{
    private array $viewData = [];
    private string $mode = "DSP";
    private array $modeDescriptions = [
        "DSP" => "Detalle de Ruta %s",
        "INS" => "Nueva Ruta de Entrega",
        "UPD" => "Editar Ruta %s",
        "DEL" => "Eliminar Ruta %s"
    ];
    private string $readonly = "";
    private bool $showCommitBtn = true;

    private array $ruta = [
        "id_ruta" => 0,
        "origen" => "",
        "destino" => "",
        "distancia_km" => 0,
        "duracion_min" => 0
    ];

    private string $xss_token = "";

    public function run(): void
    {
        try {
            $this->getData();

            if ($this->isPostBack()) {
                if ($this->validateData()) {
                    $this->handlePostAction();
                }
            }

            $this->setViewData();
            Renderer::render("rutasentrega/rutaentrega", $this->viewData);
        } catch (\Exception $ex) {
            Site::redirectToWithMsg(
                "index.php?page=RutasEntrega_RutasEntregas",
                $ex->getMessage()
            );
        }
    }

    private function getData()
    {
        $this->mode = $_GET["mode"] ?? "NOF";

        if (isset($this->modeDescriptions[$this->mode])) {
            $this->readonly = ($this->mode === "DSP" || $this->mode === "DEL") ? "readonly" : "";
            $this->showCommitBtn = $this->mode !== "DSP";

            if ($this->mode !== "INS") {
                $id_ruta = intval($_GET["id_ruta"] ?? 0);
                $this->ruta = RutasEntregaDao::getById($id_ruta);

                if (!$this->ruta) {
                    throw new \Exception("No se encontró la ruta de entrega");
                }
            }
        } else {
            throw new \Exception("Formulario cargado en modalidad inválida");
        }
    }

    private function validateData()
    {
        $errors = [];

        $this->xss_token = $_POST["xss_token"] ?? "";
        $this->ruta["id_ruta"] = intval($_POST["id_ruta"] ?? 0);
        $this->ruta["origen"] = htmlspecialchars(strval($_POST["origen"] ?? ""), ENT_QUOTES, 'UTF-8');
        $this->ruta["destino"] = htmlspecialchars(strval($_POST["destino"] ?? ""), ENT_QUOTES, 'UTF-8');
        $this->ruta["distancia_km"] = floatval($_POST["distancia_km"] ?? 0);
        $this->ruta["duracion_min"] = intval($_POST["duracion_min"] ?? 0);

        if ($this->mode !== "DEL") {
            if (Validators::IsEmpty($this->ruta["origen"])) {
                $errors["origen_error"] = "El origen es requerido";
            }

            if (Validators::IsEmpty($this->ruta["destino"])) {
                $errors["destino_error"] = "El destino es requerido";
            }

            if ($this->ruta["distancia_km"] <= 0) {
                $errors["distancia_km_error"] = "La distancia debe ser mayor a 0";
            }

            if ($this->ruta["duracion_min"] <= 0) {
                $errors["duracion_min_error"] = "La duración debe ser mayor a 0";
            }
        }

        if (count($errors) > 0) {
            foreach ($errors as $key => $value) {
                $this->ruta[$key] = $value;
            }
            return false;
        }

        return true;
    }

    private function handlePostAction()
    {
        switch ($this->mode) {
            case "INS":
                $this->handleInsert();
                break;
            case "UPD":
                $this->handleUpdate();
                break;
            case "DEL":
                $this->handleDelete();
                break;
            default:
                throw new \Exception("Modo inválido");
        }
    }

    private function handleInsert()
    {
        $result = RutasEntregaDao::insertRuta(
            $this->ruta["origen"],
            $this->ruta["destino"],
            $this->ruta["distancia_km"],
            $this->ruta["duracion_min"]
        );

        if ($result > 0) {
            Site::redirectToWithMsg(
                "index.php?page=RutasEntrega_RutasEntregas",
                "Ruta creada exitosamente"
            );
        }
    }

    private function handleUpdate()
    {
        $result = RutasEntregaDao::updateRuta(
            $this->ruta["id_ruta"],
            $this->ruta["origen"],
            $this->ruta["destino"],
            $this->ruta["distancia_km"],
            $this->ruta["duracion_min"]
        );

        if ($result > 0) {
            Site::redirectToWithMsg(
                "index.php?page=RutasEntrega_RutasEntregas",
                "Ruta actualizada exitosamente"
            );
        }
    }

    private function handleDelete()
    {
        $result = RutasEntregaDao::deleteRuta(
            $this->ruta["id_ruta"]
        );

        if ($result > 0) {
            Site::redirectToWithMsg(
                "index.php?page=RutasEntrega_RutasEntregas",
                "Ruta eliminada exitosamente"
            );
        }
    }

    private function setViewData(): void
    {
        $this->viewData["mode"] = $this->mode;
        $this->viewData["xss_token"] = $this->xss_token;
        $this->viewData["FormTitle"] = sprintf(
            $this->modeDescriptions[$this->mode],
            $this->ruta["id_ruta"]
        );
        $this->viewData["showCommitBtn"] = $this->showCommitBtn;
        $this->viewData["readonly"] = $this->readonly;
        $this->viewData["ruta"] = $this->ruta;
    }
}
?>