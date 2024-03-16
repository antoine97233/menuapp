<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BO - App Restaurant</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

  <meta name="robots" content="noindex">

</head>

<body>


  <?php
  $loggedIn = isset($_SESSION['adminEmail']);
  ?>

  <?php if ($loggedIn) : ?>

    <nav class="navbar navbar-expand-lg " style='background-color: #e3f2fd;'>
      <div class="container-fluid">
        <a class='navbar-brand text-dark' href='#'>Restaurant App</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class='nav-item'>
              <a class='nav-link text-dark' role='button' href='index.php?controller=group'>
                Produits
              </a>
            </li>
            <li class='nav-item'>
              <a class='nav-link text-dark' role='button' href='index.php?controller=slider'>
                Sliders
              </a>
            </li>
            <li class='nav-item'>
              <a class='nav-link text-dark' role='button' href='index.php?controller=menu'>
                Menu PDF
              </a>
            </li>
            <li class='nav-item'>
              <a class='nav-link text-dark' role='button' href='index.php?controller=api'>
                API
              </a>
            </li>
          </ul>
          <div class='dropdown'>
            <button class='btn dropdown-toggle text-secondary' type='button' id='dropdownMenuButton1' data-bs-toggle='dropdown' aria-expanded='false'>
              <i class='fa-solid fa-user'></i><span class="p-2"><?php echo $_SESSION['adminEmail']; ?></span>
            </button>

            <ul id="dropDown" class='dropdown-menu shadow-sm' aria-labelledby='dropdownMenuButton1'>
              <li class='p-2'><a class='p-2 text-decoration-none text-secondary' href='index.php?controller=user'>Users</a></li>
              <li class='p-2'><a class='p-2 text-decoration-none text-secondary' href='index.php?controller=auth&action=logout'>Logout</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>


  <?php endif; ?>

  <div id="messageContainer"></div>


  <div class="container-fluid min-vh-100 d-flex flex-column ">
    <div class="row flex-grow-1">
      <?= $content ?>
    </div>
  </div>



  <footer class="footer fixed-bottom" style="background-color: #e3f2fd;">
    <div class="container">
      <span class="text-muted">©Antoine Jolivet</span>
    </div>
  </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/ab053e21c8.js" crossorigin="anonymous"></script>
<script type="module" src="../admin/js/main.js"></script>

</html>