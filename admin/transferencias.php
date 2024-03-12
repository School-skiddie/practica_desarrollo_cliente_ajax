<!DOCTYPE html>
<html lang="es">
<?php
define('TITULO', "Transferir dinero");

include("../head.php");
include("../side.php");

if (!isset($_SESSION["usuario"])) header("Refresh:0; url=../login.php");
?>

<body id="body-pd">
    <div class="container h-100 d-flex justify-content-center align-items-center">
        <div class="col-lg-12 col-xlg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    Todas las transferencias
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Emisor</th>
                                    <th scope="col">Receptor</th>
                                    <th scope="col">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody id="transferencias_body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card" style="margin-top: 30px; margin-bottom: 10px;">
                <div class="card-header">
                    <?php
                    $cuenta = new cuenta($_SESSION["usuario"]);
                    echo "Actualmente tienes un saldo de: <b id='saldo'>" . $cuenta->obtener_saldo() . "</b>€";
                    ?>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class="form__group field">
                            <label for="cuenta"><i class="bi bi-people-fill"></i> Usuario</label>
                            <select name="cuenta" id="cuenta" required>
                                <option value="selecciona" disabled selected>Selecciona un usuario</option>
                            </select>
                        </div>
                        <div class="form__group field">
                            <input type="number" class="form__field" placeholder="Cantidad" name="cantidad" min="1" max="<?php echo $cuenta->obtener_saldo(); ?>" value="1" id='cantidad' required />
                            <label for="cantidad" class="form__label"><i class="bi bi-currency-euro"></i> Cantidad</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="button-64" id="boton" name="transferir" role="button">
                        <span class="text"><i class="bi bi-send"></i> Enviar dinero</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script>

        function cargar_xml() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    insertar_tablas(this);
                }
            };
            xhr.open("GET", "http://localhost/transferencias.xml", true);
            xhr.send();
        }

        function insertar_tablas(xml) {
            var docXML = xml.responseXML;
            var tabla = "";
            var transferencias = docXML.getElementsByTagName("transferencia");
            for (var i = 0; i < transferencias.length; i++) {
                tabla += "<tr><td>";
                tabla += transferencias[i].getElementsByTagName("emisor")[0].textContent;
                tabla += "</td><td>";
                tabla += transferencias[i].getElementsByTagName("receptor")[0].textContent;
                tabla += "</td><td>";
                tabla += transferencias[i].getElementsByTagName("cantidad")[0].textContent;
                tabla += "</td></tr>"; 
            }
            document.getElementById("transferencias_body").innerHTML = tabla;
        }


        function obtener_dados_de_alta() {
             // METODO XMLHttp
            var xhr = new XMLHttpRequest();
            var url = "/api/api.php?";

            var parametros = "tipo=" + encodeURIComponent("dados_de_alta");

            xhr.open("GET", url + parametros, true);

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);

                    for (var i = 0; i < response.length; i++) {
                        if (response[i].usuario != "<?php echo $_SESSION["usuario"]; ?>") {
                            var option = document.createElement("option");
                            option.value = response[i].usuario;
                            option.textContent = "Usuario: " + response[i].usuario + " | Cuenta: " + response[i].numero_cuenta + " | Saldo: " + response[i].saldo + " €";
                            $("#cuenta").append(option);
                        }
                    }
                }
            }

            xhr.send();
        }

        cargar_xml();
        obtener_dados_de_alta();

        $("#boton").click(function() {
            $.ajax({
                url: "/api/api.php",
                method: "POST",
                data: {
                    "tipo": "insertar_xml",
                    "emisor": "<?php echo $_SESSION["usuario"]; ?>",
                    "receptor": $("#cuenta").val(),
                    "cantidad": $("#cantidad").val()
                },
                error: function(xhr, status) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Ha ocurrido un error con la API al insertar el XML"
                    });
                }
            });

            $.ajax({
                url: "/api/api.php",
                method: "POST",
                data: {
                    "tipo": "transferencia",
                    "cuenta": $("#cuenta").val(),
                    "cantidad": $("#cantidad").val()
                },
                success: function(respuesta) {
                    switch (respuesta) {
                        case "OK":
                            const select = document.getElementById('cuenta');

                            if (document.getElementById("cuenta").value == "selecciona") {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Selecciona un usuario"
                                });
                            } else if (parseInt($("#cantidad").val()) > parseInt($("#saldo").text())) {
                                Swal.fire({
                                    icon: "error",
                                    title: "No tienes esa cantidad de dinero",
                                    showConfirmButton: false,
                                    timer: 2500
                                });
                            } else {
                                Swal.fire({
                                    icon: "success",
                                    title: "Se ha enviado el dinero correctamente",
                                    showConfirmButton: false,
                                    timer: 2500
                                });

                                $("#saldo").text($("#saldo").text() - $("#cantidad").val());
                            }
                            return;
                        case "ERROR":
                            Swal.fire({
                                icon: "error",
                                title: "No se ha podido enviar el dinero..",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            return;
                    }
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
    </script>
</body>

</html>