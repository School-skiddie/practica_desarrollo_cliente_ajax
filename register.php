<!DOCTYPE html>
<html lang="es">
<?php
define('TITULO', "Registrar");

include("head.php");
include("side.php");

if (isset($_SESSION["usuario"])) header("Refresh:0; url=index.php");
?>

<body id="body-pd">
    <div class="container h-100 d-flex justify-content-center align-items-center">
        <div class="col-lg-6 col-xlg-6 col-md-6">
            <div class="card" style="margin-top: 30px; margin-bottom: 10px;">
                <div class="card-header">Formulario de <b>registro</b></div>
                <div class="card-body">
                    <div class="form__group field">
                        <input type="text" class="form__field" placeholder="Usuario" name="usuario" id='usuario' required />
                        <label for="usuario" class="form__label"><i class="bi bi-person-circle"></i> Usuario</label>
                    </div>
                    <div class="form__group field">
                        <input type="password" class="form__field" placeholder="Clave" name="clave" id='clave' required />
                        <label for="clave" class="form__label"><i class="bi bi-key-fill"></i> Contraseña</label>
                    </div>
                    <div class="form__group field">
                        <input type="nombre" class="form__field" placeholder="Nombre" name="nombre" id='nombre' required />
                        <label for="nombre" class="form__label"><i class="bi bi-person-badge-fill"></i> Nombre Completo</label>
                    </div>
                    <div class="form__group field">
                        <input type="email" class="form__field" placeholder="Email" name="email" id='email' required />
                        <label for="email" class="form__label"><i class="bi bi-envelope-at-fill"></i> Email</label>
                    </div>
                    <div class="form__group field">
                        <input type="telefono" class="form__field" placeholder="Teléfono" name="telefono" id='telefono' required />
                        <label for="telefono" class="form__label"><i class="bi bi-telephone-fill"></i> Telefono</label>
                    </div>
                    <div class="form__group field">
                        <input type="number" class="form__field" placeholder="Edad" name="edad" id='edad' required />
                        <label for="Edad" class="form__label"><i class="bi bi-backpack-fill"></i> Edad</label>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="button-64" id="boton" name="registrar" role="button"><span class="text"><i class="bi bi-box-arrow-right"></i> Registrarse</span></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $("#boton").click(function() {
            $.ajax({
                url: "/api/api.php",
                method: "POST",
                data: {
                    "tipo": "register",
                    "usuario": $("#usuario").val(),
                    "clave": $("#clave").val(),
                    "nombre": $("#nombre").val(),
                    "email": $("#email").val(),
                    "telefono": $("#telefono").val(),
                    "edad": $("#edad").val()
                },
                success: function(respuesta) {

                    switch (respuesta) {
                        case "OK":
                            Swal.fire({
                                icon: "success",
                                title: "Te has registrado correctamente",
                                showConfirmButton: false,
                                timer: 2500
                            });

                            window.setTimeout(function() {
                                window.location.href = "login.php";
                            }, 3000);
                            return;
                        case "ERROR":
                            Swal.fire({
                                icon: "error",
                                title: "El usuario ya existe..",
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