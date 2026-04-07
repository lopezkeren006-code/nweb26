<section class="container-m row px-4 py-4">
  <div class="col-12">
    <h1>Rutas de Entrega</h1>
  </div>
</section>

<section class="container-m row px-4 py-4">
  <div class="col-12 right">
    <a href="index.php?page=RutasEntrega_RutaEntrega&mode=INS" class="button primary">
      Nueva Ruta
    </a>
  </div>
</section>

<section class="container-m row px-4 py-4">
  <table class="col-12">
    <thead>
      <tr>
        <th>ID</th>
        <th>Origen</th>
        <th>Destino</th>
        <th>Distancia Km</th>
        <th>Duración Min</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>

      {{foreach rutas}}
      <tr>
        <td>{{id_ruta}}</td>
        <td>{{origen}}</td>
        <td>{{destino}}</td>
        <td>{{distancia_km}}</td>
        <td>{{duracion_min}}</td>
        <td>
          <a href="index.php?page=RutasEntrega_RutaEntrega&mode=DSP&id_ruta={{id_ruta}}">
            Ver
          </a>
          &nbsp;
          <a href="index.php?page=RutasEntrega_RutaEntrega&mode=UPD&id_ruta={{id_ruta}}">
            Editar
          </a>
          &nbsp;
          <a href="index.php?page=RutasEntrega_RutaEntrega&mode=DEL&id_ruta={{id_ruta}}">
            Eliminar
          </a>
        </td>
      </tr>
      {{endfor rutas}}

    </tbody>
  </table>
</section>