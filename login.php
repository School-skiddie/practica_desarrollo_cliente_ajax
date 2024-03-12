<!DOCTYPE html>
<html lang="es">
<?php
define('TITULO', "Iniciar sesión");

include("head.php");
include("side.php");

if (isset($_SESSION["usuario"])) header("Refresh:0; url=index.php");
?>

<body id="body-pd">
    <div class="container h-100 d-flex justify-content-center align-items-center">
        <div class="col-lg-6 col-xlg-6 col-md-6">
            <div class="card" style="margin-top: 30px; margin-bottom: 10px;">
                <div class="card-header">Formulario de <b>inicio de sesión</b></div>
                <div class="card-body">
                    <div class="form__group field">
                        <input type="text" class="form__field" placeholder="Usuario" name="usuario" id='usuario' required />
                        <label for="usuario" class="form__label"><i class="bi bi-person-circle"></i> Usuario</label>
                    </div>
                    <div class="form__group field">
                        <input type="password" class="form__field" placeholder="Clave" name="clave" id='clave' required />
                        <label for="clave" class="form__label"><i class="bi bi-key-fill"></i> Contraseña</label>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="button-64" id="boton" name="boton" role="button"><span class="text"><i class="bi bi-box-arrow-in-right"></i> Iniciar sesión</span></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $("#boton").click(function() {
            $.ajax({
                url: "/api/api.php",
                data: {
                    "tipo": "login",
                    "usuario": $("#usuario").val(),
                    "clave": $("#clave").val()
                },
                success: function(respuesta) {

                    switch (respuesta) {
                        case "OK":
                            Swal.fire({
                                icon: "success",
                                title: "Has iniciado sesión correctamente",
                                showConfirmButton: false,
                                timer: 2500
                            });

                            window.setTimeout(function() {
                                window.location.href = "index.php";
                            }, 3000);
                            return;
                        case "ERROR":
                            Swal.fire({
                                icon: "error",
                                title: "El usuario insertado no existe..",
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