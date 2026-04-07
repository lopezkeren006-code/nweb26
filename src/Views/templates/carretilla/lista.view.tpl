<section style="width:100%; min-height:100vh; background:#f2f2f2; display:flex; justify-content:flex-end;">

    <div style="width:420px; min-height:100vh; background:white; box-shadow:-4px 0 12px rgba(0,0,0,0.15); display:flex; flex-direction:column;">

        <div style="background:#12007a; padding:22px; font-size:30px; font-weight:bold; color:white;">
            Carrito
        </div>

        {{if carretilla}}
        <div style="padding:20px; flex:1;">

            {{foreach carretilla}}
            <div style="display:flex; gap:14px; border-bottom:1px solid #ddd; padding-bottom:18px; margin-bottom:18px;">
                
                <div>
                    <img src="{{productImgUrl}}" alt="{{productName}}" style="width:90px; height:90px; object-fit:cover; border:1px solid #ccc;">
                </div>

                <div style="flex:1;">
                    <div style="font-size:18px; font-weight:bold; margin-bottom:8px;">
                        {{productName}}
                    </div>

                    <div style="font-size:14px;">
                        Precio: L {{precio}}
                    </div>

                    <div style="font-size:14px;">
                        Cantidad: {{cantidad}}
                    </div>

                    <div style="font-size:14px; font-weight:bold; margin-bottom:8px;">
                        Subtotal: L {{subtotal}}
                    </div>

                    <a href="index.php?page=Carretilla-Carretilla&remove={{productId}}" style="color:#ff4d4f; font-weight:bold; text-decoration:none;">
                        Eliminar
                    </a>
                </div>
            </div>
            {{endfor carretilla}}

        </div>
        {{endif carretilla}}

        {{ifnot carretilla}}
        <div style="padding:30px; text-align:center; flex:1; display:flex; flex-direction:column; justify-content:center;">
            <p style="font-size:20px; color:#555;">Tu carrito está vacío</p>
        </div>
        {{endifnot carretilla}}

        <div style="border-top:1px solid #ddd; padding:20px; background:#fafafa;">
            <div style="display:flex; justify-content:space-between; font-size:22px; font-weight:bold; margin-bottom:18px;">
                <span>Total</span>
                <span>L {{total}}</span>
            </div>

            <a href="index.php?page=Carretilla-Carretilla&clear=1" style="display:block; width:100%; background:black; color:white; text-align:center; padding:14px; text-decoration:none; margin-bottom:10px; font-weight:bold;">
                Vaciar carrito
            </a>

            <a href="#" style="display:block; width:100%; background:#f39b5f; color:white; text-align:center; padding:14px; text-decoration:none; font-weight:bold;">
                Realizar compra
            </a>
        </div>

    </div>
</section>