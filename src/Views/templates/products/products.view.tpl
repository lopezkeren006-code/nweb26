<section style="padding:30px; background:#f2f2f2; min-height:100vh;">
    <h1 style="margin-bottom:25px; color:#1f4788;">Productos</h1>

    {{if products}}
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;">

        {{foreach products}}
        <div style="background:white; border:1px solid #dcdcdc; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.08);">

            <div style="width:100%; height:220px; background:#f8f8f8; display:flex; align-items:center; justify-content:center;">
                <img src="{{productImgUrl}}" alt="{{productName}}" style="max-width:100%; max-height:100%; object-fit:cover;">
            </div>

            <div style="padding:18px;">
                <h3 style="margin:0 0 10px 0; font-size:20px; color:#1f1f1f;">
                    {{productName}}
                </h3>

                <p style="margin:0 0 8px 0; font-size:18px; font-weight:bold; color:#1f4788;">
                    L {{productPrice}}
                </p>

                <p style="margin:0 0 15px 0; font-size:14px; color:#666;">
                    Código: {{productId}}
                </p>

                <form action="index.php?page=Carretilla-Carretilla" method="post" style="margin:0;">
                    <input type="hidden" name="productId" value="{{productId}}">
                    <input type="hidden" name="price" value="{{productPrice}}">
                    <input type="hidden" name="quantity" value="1">

                    <button type="submit" style="width:100%; background:#1f4788; color:white; border:none; padding:12px; border-radius:6px; font-weight:bold; cursor:pointer;">
                        Agregar al carrito
                    </button>
                </form>
            </div>
        </div>
        {{endfor products}}

    </div>
    {{endif products}}

    {{ifnot products}}
    <p>No hay productos para mostrar.</p>
    {{endifnot products}}
</section>