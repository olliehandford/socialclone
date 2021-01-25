<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Header.php';
?>
<section class="section">
<div class="container social-container is-max-desktop">
<h1 class="title">Confim password reset</h1>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {    
        AuthController::confirmReset();
    }
?>

<div class="box">
  <div class="media">
    <div class="media-content">
        <form method="post">
                <div class="field">
                    <label class="label">Reset code</label>
                    <div class="control">
                        <input type="text" name="code" id="code" placeholder="Reset code" value="<?php if(isset($_GET['code'])){ echo $_GET['code']; } ?>" class="input">
                    </div>
                </div>
                <div class="field">
                    <label class="label">New Password</label>
                    <div class="control">
                        <input type="password" name="password" id="password" placeholder="New password" class="input">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Confirm new code</label>
                    <div class="control">
                         <input type="password" name="password2" id="password2" placeholder="Confirm new password" class="input">
                    </div>
                </div>
            <button type="submit" class="button is-primary">Reset password</button>
        </form>
    </div>
</div>
</div>
<a href="/forgotpassword">Don't have a reset code?</a>
</div>
</section>
<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Footer.php';
?>


