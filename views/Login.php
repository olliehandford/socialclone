<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Header.php';
?>
<section class="section">
<div class="container social-container is-max-desktop">

<h1 class="title">Login</h1>
<h2 class="subtitle">Login if you've already got an account</h2>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {    
        AuthController::login();
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
                    <label class="label">Password</label>
                    <div class="control">
                       <input class="input" type="password" id="password" name="password" placeholder="Password">
                    </div>
                </div>
            <button type="submit" class="button is-primary">Login </button>
        </form>
    </div>
</div>
</div>



<a href="/forgotpassword">Forgotten your password?</a>
</div>
</section>

<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Footer.php';
?>