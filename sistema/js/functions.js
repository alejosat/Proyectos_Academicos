$(document).ready(function(){

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
    	var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png')
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
              }else{
              	alert("No selecciono foto");
                $("#img").remove();
              }              
    });

    $('.delPhoto').click(function(){
    	$('#foto').val('');
    	$(".delPhoto").addClass('notBlock');
    	$("#img").remove();

        if($("#foto_actual") && $("#foto_remove"))
        {
            $("foto_remove").val('img_producto.png');
        }

    });

    // Modal form add product
    $('.add_product').click(function(e) {

        e.preventDefault();
        var producto = $(this).attr('product');
        var action = 'infoProducto';
        
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,producto:producto},
        
            success: function(response){
                if(response != 'error')
                {
                    var info = JSON.parse(response);
                    // $('#producto_id').val(info.idproducto);
                    // $('.nameProducto').html(info.descripcion);

                    $('.bodyModal').html('<form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">'+
                    '<h1>Agregar Producto</h1>'+
                    '<br>'+
                    '<h2 class="nameProducto">'+info.descripcion+'</h2><br>'+
                    '<input type="number" name="cantidad" id="txtcantidad" placeholder="Cantidad del producto" required><br>'+
                    '<input type="text" name="precio" id="txtprecio" placeholder="Precio del producto" required>'+
                    '<input type="hidden" name="producto_id" id="producto_id" value="'+info.idproducto+'" required>'+
                    '<input type="hidden" name="action" value="addProduct" required>'+
                    '<div class="alert alertAddProduct"></div>'+
                    '<button type="submit" class="btn_new">Agregar</button>'+
                    '<a href="#" class="btn_ok closeModal" onclick="coloseModal();" style="background: #e65656; color: #fff;">Cerrar</a>'+
                '</form>');
                }
            },

            error: function(error){
                console.log(error);
            },

        });

        
        $('.modal').fadeIn();
    });

    // Modal form delete product
    $('.del_product').click(function(e) {

        e.preventDefault();
        var producto = $(this).attr('product');
        var action = 'infoProducto';
        
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,producto:producto},
        
            success: function(response){
                if(response != 'error')
                {
                    var info = JSON.parse(response);
                    // $('#producto_id').val(info.idproducto);
                    // $('.nameProducto').html(info.descripcion);

                    $('.bodyModal').html('<form action="" method="post" name="form_del_product" id="form_del_product" onsubmit="event.preventDefault(); delProduct();">'+
                    '<h1>Eliminar Producto</h1>'+
                    '<p>¿Está seguro de eliminar el siguiente registro?</p>'+
                    '<br>'+
                    '<h2 class="nameProducto">'+info.descripcion+'</h2><br>'+
                    '<input type="hidden" name="producto_id" id="producto_id" value="'+info.idproducto+'" required>'+
                    '<input type="hidden" name="action" value="delProduct" required>'+
                    '<div class="alert alertAddProduct"></div>'+
                    '<a href="#" class="btn_cancel" onclick="coloseModal();">Cerrar</a>'+
                    '<button type="submit" class="btn_ok" style="background: #e65656; color: #fff;">Eliminar</button>'+
                '</form>');
                }
            },

            error: function(error){
                console.log(error);
            },

        });

        
        $('.modal').fadeIn();
    });
});

function sendDataProduct(){

    $('.alertAddProduct').html('');

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: $('#form_add_product').serialize(),
    
        success: function(response){
            if(response == 'error')
            {
                $('.alertAddProduct').html('<p style = "color: red;">Error al agregar el producto</p>')
            }
            else
            {
                var info = JSON.parse(response);
                $('.row'+info.producto_id+'.celPrecio').html(info.nuevo_precio);
                $('.row'+info.producto_id+'.celExistencia').html(info.nueva_existencia);
                $('#txtcantidad').val('');
                $('#txtprecio').val('');
                $('.alertAddProduct').html('<p>Producto Guardado Correctamente</p>')
            }
        },

        error: function(error){
            console.log(error);
        },

    });
}

//eliminar producto
function delProduct(){

    var pr = $('#producto_id').val();
    $('.alertAddProduct').html('');

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: $('#form_del_product').serialize(),
    
        success: function(response){
            if(response == 'error')
            {
                $('.alertAddProduct').html('<p style = "color: red;">Error al eliminar el producto</p>')
            }
            else
            {
                $('.row'+pr).remove();
                $('#form_del_product .btn_ok').remove();
                $('.alertAddProduct').html('<p>Producto Eliminado Correctamente</p>')
            }
        },

        error: function(error){
            console.log(error);
        },

    });
}


function coloseModal(){

    $('.alertAddProduct').html('');
    $('#txtcantidad').val('');
    $('#txtprecio').val('');
    $('.modal').fadeOut();
}
