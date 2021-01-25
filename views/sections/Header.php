<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Network</title>
    <link rel="stylesheet" href="/public/global.css">
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://kit.fontawesome.com/45d1a04ced.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="bar"></div>
    <div class="container is-fluid ">
<nav class="navbar" role="navigation" aria-label="main navigation">
<input type="checkbox" id="toggler" class="toggler" />
  <div class="navbar-brand">
    <a class="navbar-item" href="#">
      Social Network
    </a>

      <label for="toggler" role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </label>
  </div>

  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">
      <a class="navbar-item" href="/">
        Home
      </a>
    </div>
    <?php if(isset($_SESSION['authenticated'])) { ?>
    <div class="navbar-end">
      <div class="navbar-item">
        <figure class="image is-32x32 is-hidden-mobile is-hidden-tablet-only">
            <img src="<?php echo $_SESSION['avatar']; ?>" style="max-height: none !important;">
        </figure>
      </div>
      <div class="navbar-item has-dropdown is-hoverable">
 
       <input type="checkbox" id="dropdown1">
        <label for="dropdown1" class="navbar-link"> <a href="/profile?user=<?php echo $_SESSION['username']; ?>">
         <?php echo $_SESSION['username']; ?>
        </a>  </label>

        <div id="dropdown-content1" class="navbar-dropdown dropdown-right">
          <a class="navbar-item " href="/update">
            Settings
          </a>
          <a class="navbar-item" href="/logout">
            Logout
          </a>
    </div>
    </div>


      </div>
    </div>
    <?php }else{ ?>
    <div class="navbar-end">
      <a class="navbar-item is-hidden-desktop" href="/">
        Sign up
      </a>
      <a class="navbar-item is-hidden-desktop" href="/">
        Log in
      </a>

      <div class="navbar-item is-hidden-mobile is-hidden-tablet-only">
        <div class="buttons">
          <a class="button is-primary" href="/register">
            <strong>Sign up</strong>
          </a>
          <a class="button is-light" href="/login">
            Log in
          </a>
        </div>
      </div>
    </div>
    <?php } ?>

  </div>
</nav>