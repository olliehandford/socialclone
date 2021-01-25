<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Header.php';
?>
<section class="section">
<div class="container social-container is-max-desktop">
<h1 class="title"><?php  echo $_GET['userdata']['username'] ?></h1>


<div class="card">
  <div class="card-content">
    <div class="media">
      <div class="media-left">
        <figure class="image is-48x48">
          <img src="<?php  echo $_GET['avatar'] ?>" alt="Placeholder image">
        </figure>
      </div>
      <div class="media-content">
        <p class="title is-4"><?php  echo $_GET['userdata']['username'] ?></p>
        <p class="subtitle is-6">@<?php echo $_GET['userdata']['username'] ?></p>
      </div>
    </div>

    <div class="content">
      This is the profile of user <?php  echo $_GET['userdata']['username'] ?>. They first registered on <?php echo date('m/d/Y H:i:s', $_GET['userdata']['timestamp']); ?>.
      <br><br>They have posted <?php echo $_GET['posts']; ?> times.
    </div>
  </div>
</div>

</div>

</div>
</section>
<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/views/sections/Footer.php';
?>