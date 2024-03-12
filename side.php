<header class="header" id="header">
  <div class="header_toggle">
    <i class="bx bx-menu" id="header-toggle"></i>
  </div>
  <?php if (isset($_SESSION["usuario"])) { ?>
    <div class="header_img">
      <img src="https://i.imgur.com/hczKIze.jpg" alt="">
    </div>
  <?php
  }
  ?>
</header>
<div class="l-navbar" id="nav-bar">
  <nav class="nav">
    <div>
      <a href="../index.php" class="nav_logo">
        <i class="bx bxs-bank nav_logo-icon"></i>
        <span class="nav_logo-name">Banco</span>
      </a>
      <div class="nav_list">
        <?php
        if (!isset($_SESSION["usuario"])) {
        ?>
          <a href="login.php" class="nav_link">
            <i class="bx bx-user nav_icon"></i>
            <span class="nav_name">Iniciar sesión</span>
          </a>
          <a href="register.php" class="nav_link">
            <i class="bi bi-box-arrow-in-right"></i>
            <span class="nav_name">Registrarse</span>
          </a>
        <?php
        } else {
        ?>
          <a href="../admin/transferencias.php" class="nav_link">
            <i class="bi bi-piggy-bank-fill"></i>
            <span class="nav_name">Transferencias</span>
          </a>
          <a href="../admin/usuarios.php" class="nav_link">
            <i class="bi bi-people-fill"></i>
            <span class="nav_name">Modificar usuarios</span>
          </a>
          <a href="../admin/dar_alta.php" class="nav_link">
            <i class="bi bi-plus-circle-fill"></i>
            <span class="nav_name">Dar de alta</span>
          </a>
          <a href="../admin/dar_baja.php" class="nav_link">
            <i class="bi bi-plus-circle-fill"></i>
            <span class="nav_name">Dar de baja</span>
          </a>
        <?php
        }
        ?>
      </div>
    </div>
    <?php
    if (isset($_SESSION["usuario"])) {
    ?>
      <a href="../logout.php" class="nav_link">
        <i class="bi bi-box-arrow-left"></i>
        <span class="nav_name">Cerrar sesión</span>
      </a>
    <?php
    }
    ?>
  </nav>
</div>
<div class="title_header">
  <h4><?php echo TITULO; ?></h4>
</div>