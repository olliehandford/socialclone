<?php 

Route::add('index.php', function () {
    HomeController::view('Home');
});

Route::add('home', function () {
    HomeController::view('Home');
});

Route::add('login', function () {
    AuthController::view('Login');
});

Route::add('forgotpassword', function () {
    AuthController::view('Forgot');
});

Route::add('confirmreset', function () {
    AuthController::view('ConfirmReset');
});

Route::add('logout', function () {
    AuthController::logout();
});

Route::add('register', function () {
    AuthController::view('Register');
});

Route::add('update', function () {
    AuthController::view('UpdateProfile');
});

Route::add('post', function () {
    APIController::postThread();
});

Route::add('postImage', function () {
    APIController::postImage();
});

Route::add('getPosts', function () {
    APIController::getPosts();
});

Route::add('likePost', function () {
    APIController::likePost();
});

Route::add('replyToPost', function () {
    APIController::replyToPost();
});

Route::add('deletePost', function () {
    APIController::deletePost();
});

Route::add('profile', function () {
    ProfileController::getProfile();
});