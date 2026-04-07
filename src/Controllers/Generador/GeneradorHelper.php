<?php
namespace Controllers\Generator;

class GeneratorHelper
{
  private $columns = [];
  private $tableName = "";

  
  private $entity = "";
  private $entityLc = "";
  private $module = "";
  private $moduleLc = "";

  public function __construct(array $columns, string $tableName)
  {
    $this->columns = $columns;
    $this->tableName = $tableName;

   
    $this->entity = $this->pascal($tableName);
    $this->entityLc = lcfirst($this->entity);

   
    $this->module = $this->entity;
    $this->moduleLc = strtolower($this->module);
  }

  private function pascal(string $s): string
  {
    $s = preg_replace('/[^a-zA-Z0-9_]/', '_', $s);
    $parts = preg_split('/_+/', strtolower($s));
    $out = "";
    foreach ($parts as $p) { $out .= ucfirst($p); }
    return $out ?: "Entity";
  }

  private function getPkField(): string
  {
    foreach ($this->columns as $c) {
      if (($c["Key"] ?? "") === "PRI") return $c["Field"];
    }
    return $this->columns[0]["Field"] ?? "id";
  }

  private function escapeHtmlChars(string $s): string
  {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
  }

  
  public function getDaoPhpCode(): string
  {
    $pk = $this->getPkField();
    $table = $this->tableName;

    $cols = array_map(fn($c) => "`{$c["Field"]}`", $this->columns);
    $colList = implode(", ", $cols);

    $params = array_map(fn($c) => ":{$c["Field"]}", $this->columns);
    $paramList = implode(", ", $params);

    $updCols = array_filter($this->columns, fn($c)=>$c["Field"] !== $pk);
    $updSet = implode(", ", array_map(fn($c)=>"`{$c["Field"]}` = :{$c["Field"]}", $updCols));

    $class = $this->entity;  
    $module = $this->module;

    $code = <<<PHP
<?php
namespace Dao\\$module;

use Dao\\Table;

class $class extends Table
{
  public static function getAll(int \$page = 0, int \$itemsPerPage = 10): array
  {
    \$sql = "SELECT $colList FROM `$table` LIMIT :offset, :limit;";
    return self::obtenerRegistros(\$sql, [
      "offset" => \$page * \$itemsPerPage,
      "limit"  => \$itemsPerPage
    ]);
  }

  public static function getById(\$id): array
  {
    \$sql = "SELECT $colList FROM `$table` WHERE `$pk` = :id;";
    return self::obtenerUnRegistro(\$sql, ["id" => \$id]);
  }

  public static function insert(array \$data): int
  {
    \$sql = "INSERT INTO `$table` ($colList) VALUES ($paramList);";
    return self::executeNonQuery(\$sql, \$data);
  }

  public static function update(array \$data): int
  {
    \$sql = "UPDATE `$table` SET $updSet WHERE `$pk` = :$pk;";
    return self::executeNonQuery(\$sql, \$data);
  }

  public static function delete(\$id): int
  {
    \$sql = "DELETE FROM `$table` WHERE `$pk` = :id;";
    return self::executeNonQuery(\$sql, ["id" => \$id]);
  }
}
PHP;

    return $this->escapeHtmlChars($code);
  }

 
  public function getControllerPhpCode(): string
  {
    $class = $this->entity . "List";
    $daoClass = $this->entity;
    $module = $this->module;
    $moduleLc = $this->moduleLc;
    $entityLc = $this->entityLc;

    $code = <<<PHP
<?php
namespace Controllers\\$module;

use Controllers\\PublicController;
use Views\\Renderer;
use Dao\\$module\\$daoClass;

class $class extends PublicController
{
  private \$pageNumber = 1;
  private \$itemsPerPage = 10;
  private \$viewData = [];

  public function run(): void
  {
    \$this->pageNumber = intval(\$_GET["page"] ?? 1);
    if (\$this->pageNumber < 1) \$this->pageNumber = 1;

    \$rows = $daoClass::getAll(\$this->pageNumber - 1, \$this->itemsPerPage);

    \$this->viewData["rows"] = \$rows;
    \$this->viewData["page"] = \$this->pageNumber;

    Renderer::render("$moduleLc/$entityLc_list", \$this->viewData);
  }
}
PHP;

    return $this->escapeHtmlChars($code);
  }

  
  public function getSimpleControllerPhpCode(): string
  {
    $pk = $this->getPkField();

    $class = $this->entity . "Form";
    $daoClass = $this->entity;
    $module = $this->module;
    $moduleLc = $this->moduleLc;
    $entityLc = $this->entityLc;

    $assign = "";
    foreach ($this->columns as $c) {
      $f = $c["Field"];
      $assign .= "    \$data[\"$f\"] = \$_POST[\"$f\"] ?? null;\n";
    }

    $code = <<<PHP
<?php
namespace Controllers\\$module;

use Controllers\\PublicController;
use Views\\Renderer;
use Dao\\$module\\$daoClass;

class $class extends PublicController
{
  private \$viewData = [
    "mode" => "INS",
    "row" => []
  ];

  public function run(): void
  {
    \$id = \$_GET["id"] ?? null;
    \$mode = \$_GET["mode"] ?? "INS"; // INS|UPD|DEL
    \$this->viewData["mode"] = \$mode;

    if (!\$this->isPostBack()) {
      if (\$mode !== "INS" && \$id !== null) {
        \$this->viewData["row"] = $daoClass::getById(\$id);
      }
      Renderer::render("$moduleLc/$entityLc_form", \$this->viewData);
      return;
    }

    \$data = [];
$assign

    if (\$mode === "INS") {
      $daoClass::insert(\$data);
    } elseif (\$mode === "UPD") {
      $daoClass::update(\$data);
    } elseif (\$mode === "DEL") {
      $daoClass::delete(\$data["$pk"] ?? \$id);
    }

    header("Location: index.php?page=$moduleLc-$entityLc-list");
    exit();
  }
}
PHP;

    return $this->escapeHtmlChars($code);
  }

 
  public function getFormTemplateCode(): string
  {
    $pk = $this->getPkField();
    $moduleLc = $this->moduleLc;
    $entityLc = $this->entityLc;

    $inputs = "";
    foreach ($this->columns as $c) {
      $f = $c["Field"];
      $inputs .= <<<TPL

  <div style="margin-bottom:10px;">
    <label for="$f">$f</label><br/>
    <input id="$f" name="$f" value="{{row.$f}}" />
  </div>
TPL;
    }

    $tpl = <<<TPL
<h1>Formulario: {$this->tableName}</h1>

<form method="post" action="index.php?page=$moduleLc-$entityLc-form&mode={{mode}}&id={{row.$pk}}">
$inputs
  <button type="submit">Guardar</button>
  <a href="index.php?page=$moduleLc-$entityLc-list">Cancelar</a>
</form>
TPL;

    return $this->escapeHtmlChars($tpl);
  }

  
  public function getListTemplateCode(): string
  {
    $pk = $this->getPkField();
    $moduleLc = $this->moduleLc;
    $entityLc = $this->entityLc;

    $ths = "";
    $tds = "";
    foreach ($this->columns as $c) {
      $f = $c["Field"];
      $ths .= "      <th>$f</th>\n";
      $tds .= "      <td>{{{$f}}}</td>\n";
    }

    $tpl = <<<TPL
<h1>Listado: {$this->tableName}</h1>

<table border="1" cellpadding="6" cellspacing="0">
  <thead>
    <tr>
$ths
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    {{foreach rows}}
    <tr>
$tds
      <td>
        <a href="index.php?page=$moduleLc-$entityLc-form&mode=UPD&id={{{$pk}}}">Editar</a>
        |
        <a href="index.php?page=$moduleLc-$entityLc-form&mode=DEL&id={{{$pk}}}">Eliminar</a>
      </td>
    </tr>
    {{endfor rows}}
  </tbody>
</table>

<p><a href="index.php?page=$moduleLc-$entityLc-form&mode=INS">Nuevo</a></p>
TPL;

    return $this->escapeHtmlChars($tpl);
  }
}