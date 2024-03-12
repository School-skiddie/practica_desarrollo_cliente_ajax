<!DOCTYPE html>
<html lang="es">
<?php
define('TITULO', "Dar de baja");

include("../head.php");
include("../side.php");

if (!isset($_SESSION["usuario"])) header("Refresh:0; url=../login.php");
?>

<body id="body-pd">
    <div class="container h-100 d-flex justify-content-center align-items-center">
        <div class="col-lg-12 col-xlg-6 col-md-12">
            <?php if (!isset($_GET["modificar"])) { ?>
                <div class="card" style="margin-top: 30px; margin-bottom: 10px;">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                    <th scope="col">Numero de cuenta</th>
                                    <th scope="col">Saldo</th>
                                    <th scope="col">Usuario</th>
                                </tr>
                            </thead>
                            <tbody id="usuarios_body">
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="button-64" id="boton" name="baja" role="button" disabled>
                            <span class="text"><i class="bi bi-calendar-x"></i> Dar de baja</span>
                        </button>
                    </div>
                </div>
            <?php
            } else {
            ?>
                <div class="card" style="margin-top: 30px; margin-bottom: 10px;">
                    <div class="card-body">
                        <div class="card-body">
                            <div class="form__group field">
                                <input type="text" class="form__field" placeholder="Número de cuenta" name="numero_cuenta" value="" id='numero_cuenta' required />
                                <label for="numero_cuenta" class="form__label"><i class="bi bi-bank2"></i> Numero de cuenta</label>
                            </div>
                            <div class="form__group field">
                                <input type="number" class="form__field" placeholder="Saldo" name="saldo" value="" id='saldo' required />
                                <label for="saldo" class="form__label"><i class="bi bi-currency-euro"></i> Saldo</label>
                            </div>
                            <div class="form__group field">
                                <input type="text" class="form__field" placeholder="Usuario" name="usuario" value="" id='usuario' required />
                                <label for="usuario" class="form__label"><i class="bi bi-person-circle"></i> Usuario</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="button-64" id="boton" name="actualizar" role="button">
                            <span class="text"><i class="bi bi-arrow-clockwise"></i> Actualizar</span>
                        </button>
                    </div>
                <?php
            }
                ?>
                </div>
        </div>
        <script>
            var checkboxes_seleccionados = [];
            var xhr = new XMLHttpRequest();
            var tabla_body = $("#usuarios_body");

            if (tabla_body.length) {
                var url = "/api/api.php?tipo=dados_de_alta";

                xhr.open("GET", url, true);

                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var usuarios = JSON.parse(xhr.responseText);
                        usuarios.forEach(function(usuario) {
                            var row = document.createElement("tr");

                            var checkbox_celda = document.createElement("td");
                            var checkbox_wrapper = document.createElement("div");
                            checkbox_wrapper.classList.add("checkbox-wrapper-33");

                            var checkbox_label = document.createElement("label");
                            checkbox_label.classList.add("checkbox");

                            var checkbox_input = document.createElement("input");
                            checkbox_input.classList.add("checkbox__trigger", "visuallyhidden");
                            checkbox_input.setAttribute("name", "seleccion[]");
                            checkbox_input.setAttribute("type", "checkbox");
                            checkbox_input.setAttribute("value", usuario.numero_cuenta);

                            var checkbox_span = document.createElement("span");
                            checkbox_span.classList.add("checkbox__symbol");

                            var checkbox_svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                            checkbox_svg.setAttribute("aria-hidden", "true");
                            checkbox_svg.setAttribute("class", "icon-checkbox");
                            checkbox_svg.setAttribute("width", "28px");
                            checkbox_svg.setAttribute("height", "28px");
                            checkbox_svg.setAttribute("viewBox", "0 0 28 28");

                            var checkbox_path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                            checkbox_path.setAttribute("d", "M4 14l8 7L24 7");

                            checkbox_svg.appendChild(checkbox_path);
                            checkbox_span.appendChild(checkbox_svg);
                            checkbox_label.appendChild(checkbox_input);
                            checkbox_label.appendChild(checkbox_span);
                            checkbox_wrapper.appendChild(checkbox_label);
                            checkbox_celda.appendChild(checkbox_wrapper);
                            row.appendChild(checkbox_celda);

                            var añadir_celda = document.createElement("td");
                            var modificar_link = document.createElement("a");
                            modificar_link.setAttribute("href", "dar_baja.php?modificar=" + usuario.usuario);
                            modificar_link.innerHTML = '<i class="bi bi-box-arrow-up-right"></i> Modificar cuenta';

                            añadir_celda.appendChild(modificar_link);
                            row.appendChild(añadir_celda);

                            var keys = ["numero_cuenta", "saldo", "usuario"];
                            keys.forEach(function(key) {
                                var cell = document.createElement("td");
                                cell.textContent = usuario[key];
                                row.appendChild(cell);
                            });

                            tabla_body.append(row);
                        });

                        $('input[type="checkbox"]').change(function() {
                            let seleccionado = false;
                            checkboxes_seleccionados = [];
                            $('input[type="checkbox"]').each(function() {
                                if ($(this).is(':checked')) {
                                    checkboxes_seleccionados.push($(this).val());
                                    seleccionado = true;
                                }
                            });
                            $('#boton').prop('disabled', !seleccionado);
                        });
                    }
                };

                xhr.send();

                $("#boton").click(function() {
                    console.log(checkboxes_seleccionados);
                    $.ajax({
                        url: "/api/api.php",
                        method: "POST",
                        data: {
                            "tipo": "dar_de_baja",
                            "seleccion": checkboxes_seleccionados
                        },
                        success: function(respuesta) {
                            Swal.fire({
                                icon: "success",
                                title: "Se ha dado de baja a los usuarios correctamente",
                                showConfirmButton: false,
                                timer: 2500
                            });
                            window.setTimeout(function() {
                                window.location.href = "dar_baja.php";
                            }, 2800);
                        },
                        error: function(xhr, status) {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Ha ocurrido un error con la API"
                            });
                        }
                    });
                });
            } else {
                var url = "/api/api.php?tipo=obtener_cuenta&modificar=<?php echo empty($_GET["modificar"]) ? "" : $_GET["modificar"]; ?>";

                xhr.open("GET", url, true);

                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var usuario = JSON.parse(xhr.responseText);
                        usuario.forEach(function(usuario) {

                            $("#numero_cuenta").val(usuario.numero_cuenta);
                            $("#saldo").val(usuario.saldo);
                            $("#usuario").val(usuario.usuario);
                        });
                    }
                };

                xhr.send();

                $("#boton").click(function() {
                    console.log(checkboxes_seleccionados);
                    $.ajax({
                        url: "/api/api.php",
                        method: "POST",
                        data: {
                            "tipo": "modificar_cuenta",
                            "numero_cuenta": $("#numero_cuenta").val(),
                            "saldo": $("#saldo").val(),
                            "usuario": $("#usuario").val()
                        },
                        success: function(respuesta) {
                            Swal.fire({
                                icon: "success",
                                title: "Se ha actualizado correctamente el usuario",
                                showConfirmButton: false,
                                timer: 2500
                            });
                        },
                        error: function(xhr, status) {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Ha ocurrido un error con la API"
                            });
                        }
                    });
                });
            }
        </script>
</body>

</html>