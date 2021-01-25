<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Header.php';
?>

<section class="section">
<div class="container social-container is-max-desktop">

<h1 class="title">Update your profile</h1>
<h2 class="subtitle">Change information about your profile.</h2>


<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {    
        AuthController::updateProfile();
    }
?>

<div class="box">
  <div class="media">
    <div class="media-content">
        <form method="post">
                <div class="field">
                    <label class="label">Username</label>
                    <div class="control">
                        <input type="text" name="username" id="username" class="input" placeholder="Username" value="<?php echo $_SESSION['user']['username']; ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                       <input type="email" name="email" id="email" class="input" placeholder="Email" value="<?php echo $_SESSION['user']['email']; ?>" required>
                    </div>
                </div>
            <button type="submit" class="button is-primary">Update</button><hr>
            To update your password please use the <a href="/forgotpassword">Forgot Password</a> page.<hr>
            Here at Social Network, we use <a href="https://en.gravatar.com/" target="_blank">Gravtar</a> for our avatars. Please change yours on their website.
        </form>
    </div>
</div>
</div>

</div>
</section>

<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Footer.php';
?>