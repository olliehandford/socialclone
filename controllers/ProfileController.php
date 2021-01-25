<?php 

class ProfileController extends Controller 
{
    public static function getProfile()
    {
        $_GET['userdata'] = DB::fetch("SELECT id,username, email,timestamp FROM users WHERE username = ?", [$_GET['user']]);
        if($_GET['userdata'] == null)
        {
            return Controller::view('404');
        }
        $_GET['posts'] = DB::count("SELECT * FROM posts WHERE owner = ?", [$_GET['userdata']['id']]);
        $_GET['avatar'] = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $_GET['userdata']['email'] ) ) ) . "&s=96";
        return Controller::view('Profile');
    }
}