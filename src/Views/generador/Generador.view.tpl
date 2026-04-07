<h2>Generador de WW para tablas</h2>

<ul>
{{foreach tables}}
  <li>
    <form action="index.php?page=Generator_Generator" method="post">
      <input type="hidden" name="table" value="{{this}}">
      <input type="submit" value="Generar WW para {{this}}">
    </form>
  </li>
{{endfor tables}}
</ul>

{{if columns}}
  <h3>Columnas de la tabla: {{table}}</h3>
  <ul>
    {{foreach columns}}
      <li>{{Field}} {{Type}} {{Null}} {{Key}}</li>
    {{endfor columns}}
  </ul>

  <hr/>

  <h3>Modelo (DAO)</h3>
  <pre>{{genResult}}</pre>

  <h3>Controlador Lista</h3>
  <pre>{{genController}}</pre>

  <h3>Controlador Formulario</h3>
  <pre>{{genSimpleController}}</pre>

  <h3>Plantilla Formulario</h3>
  <pre>{{genForm}}</pre>

  <h3>Plantilla Lista</h3>
  <pre>{{genList}}</pre>
{{endif columns}}