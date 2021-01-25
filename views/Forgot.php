<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Header.php';
?>
<section class="section">
<div class="container social-container is-max-desktop">

<h1 class="title">Forgot password?</h1>
<h2 class="subtitle">Please enter your email.</h2>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {    
        AuthController::forgot();
    }
?>

<div class="box">
  <div class="media">
    <div class="media-content">
        <form method="post">
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                        <input type="email" name="email" id="email" placeholder="Email" class="input">
                    </div>
                </div>

            <button type="submit" class="button is-primary">Reset password</button>
        </form>
    </div>
</div>
</div>

</div>
</section>
<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Footer.php';
?>