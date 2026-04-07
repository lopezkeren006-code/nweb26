<section class="container-m px-4 py-4">
    <h2>Carrito de Compras</h2>

    {{if cart}}
    <table class="table">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            {{foreach cart}}
            <tr>
                <td>
                    <img src="{{imagen}}" alt="{{nombre}}" width="80">
                </td>
                <td>{{nombre}}</td>
                <td>L {{precio}}</td>
                <td>{{cantidad}}</td>
                <td>L {{subtotal}}</td>
                <td>
                    <a href="index.php?page=Carrit&action=remove&id={{id}}" class="btn btn-danger">
                        Eliminar
                    </a>
                </td>
            </tr>
            {{endfor}}
        </tbody>
    </table>

    <h3>Total: L {{total}}</h3>

    <div class="mt-3">
        <a href="index.php?page=Carrit&action=clear" class="btn btn-warning">Vaciar carrito</a>
    </div>
    {{endif cart}}

    {{ifnot cart}}
    <p>No hay productos en el carrito.</p>
    {{endifnot cart}}
</section>