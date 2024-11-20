    <?php include '../estructura/cabeceraSegura.php' ?>

    <div class="container-sm min-vh-100">  

        <div id='mensajeOperacion'></div>

        <h1 class="deposito-title pt-4">Menu</h1>
        
        <div class="deposito-menu" id="menuDinamico">
            <!--viene el codigo de jquery-->
        </div>
        <div class="grid"></div>
        
    </div>


    <script>
    $(document).ready(function() {
        function mostrarMenues() {
            $.ajax({
                url: 'actionMenu.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('.deposito-menu').html('');
                    
                    if (response.error) {
                        $('.deposito-menu').html('Error al cargar los datos.');
                    } else {
                        var i = 1;
                        let menuHtml = "<div class='d-flex flex-column'>"; 
                        
                        response.forEach(menu => {
                            switch(menu) {
                                case 'verpaquete':
                                    menu = "<span>Ver Paquete(s).</span>";
                                    break;
                                case 'agregarproducto':
                                    menu = "<span>Agregar un Producto.</span>";
                                    break;
                                case 'modificarproducto':
                                    menu = "<span>Modificar un Producto.</span>";
                                    break;
                                case 'altausuario':
                                    menu = "<span>Agregar un Usuario.</span>";
                                    break;
                                case 'bajausuario':
                                    menu = "<span>Borrar Usuario.</span>";
                                    break;
                                case 'modificarusuario':
                                    menu = "<span>Modificar un Usuario.</span>";
                                    break;
                                case 'configuracionusuario': 
                                    menu = "<span>Configuracion de Usuario.</span>";
                                    break;
                            }
                            menuHtml += `<button type="button" class="deposito-btn-subir-producto mb-2" onclick="obtenerMenu(${i})">${menu}</button>`;
                            i++;
                        });
                        menuHtml += "</div>";
                        $('.deposito-menu').append(menuHtml);
                        $('.deposito-menu').addClass('align-items-stretch');

                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error al cargar los datos.');
                }
            });
        }
        mostrarMenues();
    });

    window.obtenerMenu = function(indice) {
        var url;
        switch(indice) {
            case 1:
                //COFIG USUARIO ADMIN 
                url = './actionlistarDatosUsuario.php';
    
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(result) {
                        $('.deposito-menu').html('');
                        $('.deposito-title').html('Configuracion Usuario');
                        $('.grid').html('');
    
                        let userInfo = `
                            <div class="d-flex flex-row w-77">

                            <div class="d-flex flex-row w-100">
                                <form class="formularioMenuConfig" id="formularioUs" novalidate method="post" action="./actionConfigurarPerfil.php">
                                    <div class="form-group mb-4">
                                        <label for="usnombre" class="form-label text-light">Nombre del usuario</label>
                                        <input type="text" name="usnombre" id="usnombre" class="form-input" value="${result.usnombre}" required>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="usmail" class="form-label text-light">Correo del usuario</label>
                                        <input type="email" name="usmail" id="usmail" class="form-input" value="${result.usmail}" required>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="uspass" class="form-label text-light">Contraseña del usuario</label>
                                        <input type="password" name="uspass" id="uspass" class="form-input mb-2" value="${result.uspass}" required>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <input type="submit" value="Actualizar Perfil" name="submit" class="btn-actualizarPerfil mt-4">
                                    </div>
                                </form>
                            </div>
                            
                                <div class="d-flex flex-row w-100 justify-content-center bg-ee9f40">
                                    <div class="w-70 h-100 rounded-end">
                                        <img src="../Assets/imgs/imagen-formulario.jpg" alt="" class="w-100 h-100 rounded-end">
                                    </div>    
                                </div>

                            </div>
                        `;
                        $('.deposito-menu').append(userInfo);
    
                            $('#formularioUs').on('submit', function(e) {
                                e.preventDefault();
                                $.ajax({
                                    url: './actionConfigurarPerfil.php',
                                    type: 'POST',
                                    data: new FormData(this),
                                    processData: false,
                                    contentType: false,
                                    success: function(texto) {
                                        $('#mensajeOperacion').addClass('alert alert-success alert-dismissible fade show text-center').html('Perfil editado exitosamente.');
                                    },
                                    error: function(xhr, status, error) {
                                        console.log('Error: ' + error);
                                    }
                                });
                            });
                    },
                    error: function(xhr, status, error) {
                        console.log('Error al cargar los datos del menú dinámico.');
                        console.log('Error: ' + error);
                    }
                });
                break;
            case 2:
                //VER PAQUETES DEPOSITO
                url = "./actionVerPaquetes.php";

                $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json', 
                        success: function(result) {
                            if (Array.isArray(result)) {

                                let deposito = $('.deposito-menu').html('');
                                let grid = $('.grid').html('');
                                let row;

                                if ($('.deposito-menu').is(':empty')) {
                                    $('.deposito-menu').html('<div class="alert alert-dark alert-dismissible fade show text-center w-100">No hay nada en el depósito.</div>');
                                    $('.deposito-menu').css('min-height', '0px');
                                }


                                result.forEach(function(datos, index) {
                                   
                                    if (index % 4 === 0) {
                                        row = $('<div class="row mt-3 mb-3 justify-content-between"></div>'); 
                                        $('.grid').append(row); 
                                        $('.grid').addClass('w-100')
                                        $('.deposito-menu').html(grid);
                                    }
                                    let pedido = `<div class="col-md-3 col-sm-6 mb-3 evalua w-25">  
                                                    <div class="d-flex flex-column">
                                                    <div class="text-center">Pedido numero: ${datos.idcompraitem}</div>
                                                    <div class="text-center">Id de la Compra: ${datos.idcompra}</div>
                                                    <div class="text-center">Id del Producto: ${datos.idproducto}</div>
                                                        <div class="text-center"><strong>Cantidad solicitada: ${datos.cicantidad}</strong></div>
                                                        <div class="text-center"><strong>Stock: ${datos.cicantstock}</strong></div>
                                                        <div class="d-flex flex-row justify-content-center">
                                                            <button class="btn btn-success" onclick="evaluar(this, ${datos.idcompra}, 1)">Aceptar</button>
                                                            <button class="btn btn-danger" onclick="evaluar(this, ${datos.idcompra}, 0)">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>`;

                                   
                                    row.append(pedido);
                                });
                            } else {
                                console.error('La respuesta no es un array:', result);
                        }
                    },
                        error: function(xhr, status, error) {
                            console.log('Error al cargar los datos del menú dinámico.');
                            console.log('Error: ' + error);
                        }
                    });
                break;
            case 3:
                //AGREGAR PRODUCTO ADMIN 
                $('.deposito-title').html('Agregar Producto');

                url = './agregarAction.php';

                $('.deposito-menu').html(`
                    <form class="formAgregarProducto" id="fm" novalidate method="post">
                        <div class="form-group inputForm">
                            <label for="pronombre" class="form-label">Nombre del producto</label>
                            <input type="text" name="pronombre" id="pronombre" class="form-input inputForm" required>
                        </div>
                        <div class="form-group">
                            <label for="proprecio" class="form-label">Precio del producto</label>
                            <input type="number" name="proprecio" id="proprecio" class="form-input inputForm" required>
                        </div>
                        <div class="form-group">
                            <label for="promarca" class="form-label">Marca del producto</label>
                            <select name="promarca" id="promarca" class="form-select" required>
                                <option value="nike">Nike</option>
                                <option value="adidas">Adidas</option>
                                <option value="vans">Vans</option>
                                <option value="topper">Topper</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="prodetalle" class="form-label">Detalle del producto</label>
                            <input type="text" name="prodetalle" id="prodetalle" class="form-input inputForm" required>
                        </div>
                        <div class="form-group">
                            <label for="procantstock" class="form-label">Cantidad de stock</label>
                            <input type="number" name="procantstock" id="procantstock" class="form-input inputForm" required>
                        </div>
                        <div class="form-group">
                            <label for="image" class="form-label">Choose image(s) to upload:</label>
                            <input type="file" name="image[]" id="image" class="form-file" multiple required>
                        </div>
                        <div class="text-center">
                            <input type="submit" value="Subir imagen" name="submit" class="btn-verde">
                        </div>
                    </form>`);

                $('#fm').on('submit', function(e) {
                    
                    e.preventDefault(); 
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function(result) {
                            console.log(result);
                            try {
                                if (result) {
                                    $('#mensajeOperacion').addClass('alert alert-success alert-dismissible fade show text-center').html('Producto agregado exitosamente.');    
                                    } else {console.log('Error: ' + result.errorMsg);
                                }
                            } catch (e) {
                                console.log('Error al parsear la respuesta del servidor.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Error al cargar los datos del menú dinámico.');
                            console.log('Error: ' + error);
                        }
                    });
                });
                
                break;
            case 4:
                //MODIFICAR PRODUCTO
                url = './listarDeposito.php';

                $('.deposito-title').html('Modificar Producto');

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(result) {

                    $('.deposito-menu').html(''); 
                    let grid = $('.grid').html(''); 

                    let row;
                        result.forEach(function(producto ,index) {
                            if (index % 4 === 0) {
                                    row = $('<div class="row mt-4 mb-4"></div>');
                                    $('.grid').append(row);
                                    $('.deposito-menu').html(grid); 
                                }
                                
                            let productoStr = JSON.stringify(producto).replace(/"/g, '&quot;');
                            let zapatilla = `
                                    <div class="col-3">
                                        <div class="card d-flex w-100 h-100 p-3 shadow-sm">
                                            <div class="card-img w-100">
                                                <img src="${producto.proimagen1}" alt="" class="w-100 h-100 img-card">
                                            </div>
                                            <div class="card-marca">${producto.promarca}</div>
                                            <div class="card-infoZapatillas data-nombre">${producto.pronombre}</div>
                                            <div class="card-precioMasDescuento">
                                            <strong>$</strong><span class="data-precio">${producto.proprecio}</span><strong>USD</strong>
                                            </div>
                                            <div class="hidden">
                                                <span class="data-idproducto">${producto.idproducto}</span>
                                            </div>
                                            <div class="card-button text-center pt-3">
                                                <button class="btn btn-dark p-2 agregarCarrito" id="myButton" onclick="modificarProducto(${productoStr})">Modificar Producto</button>
                                            </div>
                                        </div>
                                    </div>
                            `;
                            row.append(zapatilla);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('Error al cargar los datos del menú dinámico.');
                        console.log('Error: ' + error);
                    }
                  
                })

                break;
            case 5:
                //ALTA USUARIO ADMIN 
                url = './actionAltaUsuario.php';
                $('.deposito-title').html('Agregar un usuario nuevo');

                $('.deposito-menu').html(`
                        <form class="formNuevoUsuario" id="formulario" novalidate method="post">
                            <h2>Formulario de Registro</h2>
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" name="usnombre" class="form-input" id="nombre" placeholder="Ingresa tu nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="contraseña" class="form-label">Contraseña</label>
                                <input type="password" name="uspass" class="form-input" id="contraseña" placeholder="Ingresa tu contraseña" required>
                            </div>
                            <div class="form-group">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" name="usmail" class="form-input" id="correo" placeholder="Ingresa tu correo electrónico" required>
                            </div>
                            <div class="text-center mt-5">
                                <button type="submit" class="btn-enviar-formularioRegistroUsuario">Enviar</button>
                            </div>
                        </form>`);

                $('#formulario').on('submit', function(e) {
                    e.preventDefault(); 
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(result) {
                            try {
                                if (result) {
                                    $('#mensajeOperacion').addClass('alert alert-success alert-dismissible fade show text-center').html('Se agrego un usuario correctamente.');    
                                    } else {console.log('Error: ' + result.errorMsg);
                                }
                            } catch (e) {
                                console.log('Error al parsear la respuesta del servidor.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Error al cargar los datos del menú dinámico.');
                            console.log('Error: ' + error);
                        }
                    });
                });

                break;
                case 6:
                //BAJA USUARIO ADMIN 

                url = './listarUsuarios.php';
                $('.deposito-title').html('Borrar un Usuario');

                $.ajax({ 
                    url: url, 
                    type: 'GET', 
                    dataType: 'json', 
                    success: function(result) { 

                        $('.deposito-menu').html(''); 
                        let grid = $('.grid').html('');
                        let row;
                        $('.deposito-menu').append(grid);

                        result.forEach(function(usuario, index) {
                            let usuarioStr = JSON.stringify(usuario).replace(/"/g, '&quot;');
                            
                            if (index % 4 === 0) {
                                row = $('<div class="row mt-4 mb-4"></div>');
                                $('.grid').append(row);
                            }

                            let usuarioHtml = `
                                    <div class="col-3 text-center">
                                        <div class="card m-2 p-2 shadow-sm"> 
                                            <div class="card-body"> 
                                            <form class="" id="formularioBorrado" novalidate method="post">
                                                <p class="card-title"><strong>Nombre : </strong>${usuario.usnombre}</p> 
                                                <p class="card-text"><strong>Correo:</strong> ${usuario.usmail}</p> 
                                                <p class="card-text font-bold">${usuario.usdeshabilitado == '0000-00-00 00:00:00' ? 'Habilitado' : 'Deshabilitado'}</p> 
                                                <button class="btn btn-danger"><a onclick="borradoLogicaUsuario(${usuarioStr})">Dar de Baja</a></button> 
                                            </form>    
                                            </div> 
                                        </div> 
                                    </div>
                            `;
                            row.append(usuarioHtml);
                        });
                    }, 
                    error: function(xhr, status, error) { 
                        console.log('Error al cargar los datos del menú dinámico.'); 
                        console.log('Error: ' + error); 
                    } 
                });      

                            
                    break;   
                     case 7:
                    //MODIFICAR USUARIO ADMIN 
                    $('.deposito-title').html('Modificar un Usuario');
                    url = './listarUsuarios.php'; 
                    
                    $.ajax({
                        url: './listarUsuarios.php',
                        type: 'GET',
                        dataType: 'json',
                        success: function(result) {

                            $('.deposito-menu').html('');
                            let grid = $('.grid').html('');
                            $('.deposito-menu').append(grid);

                            let row;
                            result.forEach(function(usuario, index ) {
                                    let usuarioStr = JSON.stringify(usuario).replace(/"/g, '&quot;');

                                    if (index % 4 === 0) {
                                        row = $('<div class="row mt-4 mb-4"></div>');
                                        $('.grid').append(row);
                                    }
                                    
                                    let usuarioHtml = `
                                        <div class="col-3">
                                            <div class="card m-2 p-2 shadow-sm">
                                                <div class="card-body">
                                                    <form>
                                                        <p class="card-title">Nombre : ${usuario.usnombre}</p>
                                                        <p class="card-text">Correo: ${usuario.usmail}</p>
                                                        <p class="card-text"><strong>Rol: ${usuario.rodescripcion}</strong></p>
                                                        <div class="text-center">
                                                            <button class="btn btn-warning boton-formuModificar"><a onclick="modificarUsuario(${usuarioStr})">Modificar</a></button>
                                                        <div>
-
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    row.append(usuarioHtml);
                            });
                        },
                        error: function(xhr, status, error) {
                            console.log('Error al cargar los datos del menú dinámico.');
                            console.log('Error: ' + error);
                        }
                    });
            break;
            default:
                console.log('Índice fuera de rango: ' + indice);
                return;
        }
    }

    function modificarProducto(producto) {
        $.ajax({
            url: './modificarAction.php',
            type: 'get',
            success: function(response) {
                $('.grid').html('');
                $('.deposito-menu').html(`
                    <form class="upload-form" id="form" novalidate method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="pronombre" class="form-label">Nombre del producto</label>
                            <input type="text" name="pronombre" id="pronombre" class="form-input" value="${producto.pronombre}" required>
                        </div>
                        <div class="form-group">
                            <label for="proprecio" class="form-label">Precio del producto</label>
                            <input type="number" name="proprecio" id="proprecio" class="form-input" value="${parseInt(producto.proprecio, 10).toFixed(2)}" required>
                        </div>
                        <div class="form-group">
                            <label for="promarca" class="form-label">Marca del producto</label>
                            <select name="promarca" id="promarca" class="form-select" required>
                                <option value="nike" ${producto.promarca === 'nike' ? 'selected' : ''}>Nike</option>
                                <option value="adidas" ${producto.promarca === 'adidas' ? 'selected' : ''}>Adidas</option>
                                <option value="vans" ${producto.promarca === 'vans' ? 'selected' : ''}>Vans</option>
                                <option value="topper" ${producto.promarca === 'topper' ? 'selected' : ''}>Topper</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="prodetalle" class="form-label">Detalle del producto</label>
                            <input type="text" name="prodetalle" id="prodetalle" class="form-input" value="${producto.prodetalle}" required>
                        </div>
                        <div class="form-group">
                            <label for="procantstock" class="form-label">Cantidad de stock</label>
                            <input type="number" name="procantstock" id="procantstock" class="form-input" value="${parseInt(producto.procantstock, 10)}" required>
                        </div>
                        <div class="form-group">
                            <label for="proimagen1" class="form-label">Seleccione la imagen para cambiar:</label>
                            <div class="form-group w-50">
                                <img src="${producto.proimagen1}" class="w-100 h-100" id="proimagen1-preview">
                            </div>
                            <input type="file" name="proimagen1" id="proimagen1" class="form-file">
                        </div>
                        <input type="submit" value="Subir imagen" name="submit" class="form-submit" required>
                    </form>
                `);

                $('#form').on('submit', function(e) {
                    e.preventDefault();
                    var formData = new FormData();
                    formData.append('idproducto', producto.idproducto);
                    formData.append('pronombre', $('#pronombre').val());
                    formData.append('proprecio', $('#proprecio').val());
                    formData.append('promarca', $('#promarca').val());
                    formData.append('promarca', $('#promarca option:selected').val());
                    formData.append('prodetalle', $('#prodetalle').val());
                    formData.append('procantstock', $('#procantstock').val());

                    var fileInput = $('#proimagen1')[0];
                    if (fileInput.files && fileInput.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            formData.append('proimagen1', e.target.result);
                            enviarFormulario(formData);
                        };
                        reader.readAsDataURL(fileInput.files[0]);
                    } else {
                        formData.append('proimagen1', $('#proimagen1-preview').attr('src'));
                        enviarFormulario(formData);
                    }
                });

                function enviarFormulario(formData) {
                    $.ajax({
                        url: './modificarAction.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(texto) {
                            console.log(texto);
                        },
                        error: function(xhr, status, error) {
                            console.log('Error: ' + error);
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log('Error: ' + error);
            }
        });
    }

    function modificarUsuario(objUsuario){
        $('.grid').html('');

        $('.deposito-menu').html(`

            <form class="upload-form" id="formularioUsuario" novalidate>
                <div class="form-group">
                    <label for="usnombre" class="form-label">Nombre del usuario</label>
                    <input type="text" name="usnombre" id="usnombre" class="form-input" value="${objUsuario.usnombre}" required>
                </div>

                <div class="form-group hidden">
                    <label for="usid" class="form-label">ID del usuario</label>
                    <input type="text" name="idusuario" id="idusuario" class="form-input" value="${objUsuario.idusuario}" required>
                </div>

                <div class="form-group">
                    <label for="usmail" class="form-label">Correo del usuario</label>
                    <input type="text" name="usmail" id="usmail" class="form-input" value="${objUsuario.usmail}" required>
                </div> 

                <div class="form-group">
                    <label for="rodescripcion" class="form-label">Rol</label>
                    <select class="form-select form-select-lg" aria-label="Large select example" name="rodescripcion" id="">
                        <option value="Cliente" ${objUsuario.rodescripcion === 'Cliente' ? 'selected' : ''}>Cliente</option>
                        <option value="Deposito" ${objUsuario.rodescripcion === 'Deposito' ? 'selected' : ''}>Deposito</option>
                        <option value="Administrador" ${objUsuario.rodescripcion === 'Administrador' ? 'selected' : ''}>Administrador</option>
                    </select>
                </div> 
                    <input type="submit" value="Modificar usuario" name="submit" class="form-submit" required>
            </form>`);

        url='./actionModificarUsuario.php';

        $('#formularioUsuario').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: url,
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(texto) {
                    console.log(texto);
                    $('#mensajeOperacion').addClass('alert alert-success alert-dismissible fade show text-center').html('Usuario modificado exitosamente.');
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error);
                }
            });
        });
    }
        
    function evaluar(boton, idcompra, estado) {
        $.ajax({
            url: './actionEvaluar.php',
            type: 'POST',
            data: { idcompra: idcompra, estado: estado },
            success: function(response) {
                $('#mensajeOperacion').html(response);
                $(boton).closest('.evalua').remove();
                $.ajax({
                    url: '../buy/actionMandarCorreo.php',
                    type: 'post',
                    data: {estado: estado == 1 ? 'aceptado' : 'rechazado', compra: idcompra},
                    // beforeSend: function(){
                    //     console.log('enviando correo');
                    // },
                    success: function(response){
                        console.log(response);
                        $.ajax({
                            url: '../buy/actionMandarCorreo.php',
                            type: 'post',
                            data: {estado: response, compra: idcompra},
                            // beforeSend: function(){
                            //     console.log('enviando correo');
                            // },
                            success: function(response){
                                console.log(response);
                            },
                            error: function(xhr, status, error) {
                                console.log('Error: ' + error);
                            }
                        })
                    },
                    error: function(xhr, status, error) {
                        console.log('Error: ' + error);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.log('Error: ' + error);
            }
        });
        
    }
    
    function borradoLogicaUsuario(objUsuario){
        $('.grid').html('');
        $('.deposito-menu').html(`
            <form class="upload-form" id="formularioUsuario" novalidate method="post">
                <div class="form-group">
                    <label for="usnombre" class="form-label">Nombre del usuario</label>
                    <input type="text" name="usnombre" id="usnombre" class="form-input" value="${objUsuario.usnombre}" readonly required>
                </div>
                <div class="form-group hidden">
                    <label for="usid" class="form-label">ID del usuario</label>
                    <input type="text" name="idusuario" id="idusuario" class="form-input" value="${objUsuario.idusuario}" readonly required>
                </div>
                <div class="form-group">
                    <label for="usmail" class="form-label">Correo del usuario</label>
                    <input type="text" name="usmail" id="usmail" class="form-input" value="${objUsuario.usmail}" readonly required>
                </div>
                <div class="form-group"> 
                        <label for="usmail" class="form-label">Estado</label>
                    <select class="form-select form-select-lg" aria-label="Large select example" name="usdeshabilitado" id="usdeshabilitado">
                        <option value="0000-00-00 00:00:00" ${objUsuario.usdeshabilitado == '0000-00-00 00:00:00' ? 'selected' : ''}>Habilitado</option>
                        <option value="2021-09-01 00:00:00" ${objUsuario.usdeshabilitado == '2021-09-01 00:00:00' ? 'selected' : ''}>Deshabilitado</option>
                    </select>
                    <p>¿Estas seguro de que quieres eliminar ? 😲😒</p>
                    <input type="submit" value="Confirmar Baja" name="submit" class="form-submit" required>
                </div>                 
            </form>`);
        
        url = './actionBajaUsuario.php';
        
        $('#formularioUsuario').on('submit', function(e) {
            e.preventDefault(); 
            $.ajax({
                url: url,
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                
                success: function(texto) {
                    $('#deposito-menu').addClass('alert alert-success alert-dismissible fade show text-center').html('Usuario deshabilitado exitosamente.');
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error);
                }
            });
        });
    }
            
</script>