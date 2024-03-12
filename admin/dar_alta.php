<!DOCTYPE html>
<html lang="es">
<?php
define('TITULO', "Dar de alta");

include("../head.php");
include("../side.php");

if (!isset($_SESSION["usuario"])) header("Refresh:0; url=../login.php");
?>

<body id="body-pd">
    <div class="container h-100 d-flex justify-content-center align-items-center">
        <div class="col-lg-12 col-xlg-6 col-md-12">
            <div class="card" style="margin-top: 30px; margin-bottom: 10px;">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Clave</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Email</th>
                                <th scope="col">Teléfono</th>
                                <th scope="col">Edad</th>
                            </tr>
                        </thead>
                        <tbody id="usuarios_body">
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button type="submit" class="button-64" id="boton" name="alta" role="button" disabled>
                        <span class="text"><i class="bi bi-sign-intersection"></i> Dar de alta</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var checkboxes_seleccionados = [];
        var xhr = new XMLHttpRequest();
        var tabla_body = $("#usuarios_body");

        if (tabla_body.length) {
            var url = "/api/api.php?tipo=dados_de_baja";

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
                        checkbox_input.setAttribute("value", usuario.usuario);

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

                        var keys = ["usuario", "clave", "nombre", "email", "telefono", "edad"];
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
                        "tipo": "dar_de_alta",
                        "seleccion": checkboxes_seleccionados
                    },
                    success: function(respuesta) {
                        Swal.fire({
                            icon: "success",
                            title: "Se han dado de alta los usuarios correctamente",
                            showConfirmButton: false,
                            timer: 2500
                        });
                        window.setTimeout(function() {
                            window.location.href = "dar_alta.php";
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
        }
    </script>
</body>

</html>