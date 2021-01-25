<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Header.php';
?>
<section class="section">
<div class="container social-container is-max-desktop">

<h1 class="title">Register</h1>
<h2 class="subtitle">Register an account with Social Network</h2>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {    
        AuthController::register();
    }
?>

<div class="box">
  <div class="media">
    <div class="media-content">
        <form method="post">
                <div class="field">
                    <label class="label">Username</label>
                    <div class="control">
                        <input type="text" name="username" id="username" class="input" placeholder="Username">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                        <input type="email" name="email" id="email" class="input" placeholder="Email">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <div class="control">
                       <input class="input" type="password" id="password" name="password" placeholder="Password">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Confirm Password</label>
                    <div class="control">
                       <input class="input" type="password" id="password2" name="password2" placeholder="Confirm Password">
                    </div>
                </div>
            <button type="submit" class="button is-primary">Register</button>
        </form>
    </div>
</div>
</div>
</div>
</section>

<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Footer.php';
?>