<?php

class AuthController extends Controller 
{
    public static function login()
    {
        //TODO: Validation

        $user = DB::fetch("SELECT * FROM users WHERE username = ?", [$_POST['username']]);
        
        if($user != null)
        {
            if(password_verify($_POST['password'], $user['password']))
            {
                $_SESSION['authenticated'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['id'] = $user['id'];
                $_SESSION['apiKey'] = $user['apikey'];

                $_SESSION['avatar'] = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $user['email'] ) ) ) . "&s=32";

                // take out the password for security purposes
                unset($user['password']);

                $_SESSION['user'] = $user;

                header('Location: /home');
            }
            else {
                // password wrong but send same message for security
                echo <<<ERROR
                <div class="notification is-danger">
                Sorry, we can't find you. Check your details and try again.
                </div>  
                ERROR;
            }
        }
        else {
           // cant find user, error message
                echo <<<ERROR
                <div class="notification is-danger">
                Sorry, we can't find you. Check your details and try again.
                </div>  
                ERROR;
        }
    }

    public static function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: /home');
    }

    public static function register()
    {

        if(!isset($_POST['username']) || $_POST['username'] == null)
        {
                echo <<<ERROR
                <div class="notification is-danger">
                You have not set a username.
                </div>  
                ERROR;
        }
        elseif(!isset($_POST['email']) || $_POST['email'] == null)
        {
                echo <<<ERROR
                <div class="notification is-danger">
                You have not set a email.
                </div>  
                ERROR;
        }
        elseif(!isset($_POST['password']) || !isset($_POST['password2']) || $_POST['password'] == null || $_POST['password2'] == null)
        {
                echo <<<ERROR
                <div class="notification is-danger">
                You have not set a password.
                </div>  
                ERROR;
        }
        elseif($_POST['password'] != $_POST['password2'])
        {
                echo <<<ERROR
                <div class="notification is-danger">
                Your passwords do not match.
                </div>  
                ERROR;
        }
        else 
        {
            $user = DB::fetch("SELECT * FROM users WHERE username = ?", [$_POST['username']]);

            if($user != null)
            {
                echo <<<ERROR
                <div class="notification is-danger">
                Sorry, that username is already in use. If that is you, <a href="/login">Login here</a>.
                </div>  
                ERROR;
            }
            else {
                DB::insert("INSERT INTO users (username, email, password, apikey) VALUES (?, ?, ?, ?)", [
                    $_POST['username'],
                    $_POST['email'],
                    password_hash($_POST['password'], PASSWORD_BCRYPT),
                    bin2hex(random_bytes(20))
                ]);

                $user = DB::fetch("SELECT * FROM users WHERE username = ?", [$_POST['username']]);

                $_SESSION['authenticated'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['id'] = $user['id'];
                $_SESSION['apiKey'] = $user['apikey'];

                $_SESSION['avatar'] = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $user['email'] ) ) ) . "&s=32";

                header('Location: /home');
            }
        }
    }

    public static function forgot()
    {
        if(!isset($_POST['email']) || $_POST['email'] == null)
        {
                echo <<<ERROR
                <div class="notification is-danger">
                    You have not set an email.
                </div>  
                ERROR;
        }
        else {
            $user = DB::fetch("SELECT * FROM users WHERE email = ?", [$_POST['email']]);

            if($user == null)
            {
                echo <<<ERROR
                <div class="notification is-danger">
                    Sorry, we can't find you. Please check the email address provided.
                </div>  
                ERROR;
            }
            else {
                // generate reset code
                $code = bin2hex(random_bytes(20));
                DB::insert("INSERT INTO resettoken (userid, code, timestamp) VALUES (?, ?, ?)", [
                    $user['id'],
                    $code,
                    time(),
                ]);
                //email user
                $mail = new Email('smtp.mailtrap.io', 2525);
                $mail->setProtocol(Email::TLS);
                $mail->setLogin(SMTP_PRIMARY_EMAIL, SMTP_PRIMARY_PASSWORD);
                $mail->addTo($user['email'], $user['username']);
                $mail->setFrom('noreply@socialnetwork.com', 'Social Network');
                $mail->setSubject('Reset your password');
                $mail->setHtmlMessage('The code to reset your password for Social Network is <b>' . $code . '</b>. This code will expire in 30 mins. Or click <a href="localhost:8000/confirmreset?code=' . $code . '">here</a>.');

                if($mail->send()){
                    echo <<<ERROR
                    <div class="notification is-success">
                        Success, please check your email!
                    </div>  
                    ERROR;
                } else {
                    echo <<<ERROR
                    <div class="notification is-danger">
                       An error occured, please contact help@socialnetwork.com
                    </div>  
                    ERROR;
                }

                //redirect to confirm page
                header('Location: /confirmreset');
            }
        }
        
    }

    public static function confirmReset()
    {
        if(!isset($_POST['code']) || $_POST['code'] == null)
        {
                    echo <<<ERROR
                    <div class="notification is-danger">
                       You do not have a reset code.
                    </div>  
                    ERROR;
        }
        elseif(!isset($_POST['password']) || !isset($_POST['password2']) || $_POST['password'] == null || $_POST['password2'] == null)
        {
                    echo <<<ERROR
                    <div class="notification is-danger">
                      You have not set a password.
                    </div>  
                    ERROR;
        }
        elseif($_POST['password'] != $_POST['password2'])
        {
                    echo <<<ERROR
                    <div class="notification is-danger">
                      Your passwords do not match.
                    </div>  
                    ERROR;
        }
        else {
            $code = DB::fetch("SELECT * FROM resettoken WHERE code = ?", [$_POST['code']]);

            if($code == null)
            {
                echo <<<ERROR
                    <div class="notification is-danger">
                      This code does not exist. Try resetting again <a href="/forgotpassword">here</a>
                    </div>  
                    ERROR;
            }
            else
            {
                // if its still within half an hour of creation
                if($code['timestamp'] > strtotime("-30 minutes")) 
                {
                    DB::update("UPDATE users SET password = ? WHERE id = ?", [
                        password_hash($_POST['password'], PASSWORD_BCRYPT),
                        $code['userid']
                    ]);

                    $user = DB::fetch("SELECT * FROM users WHERE id = ?", [$code['userid']]);

                    $mail = new Email('smtp.mailtrap.io', 2525);
                    $mail->setProtocol(Email::TLS);
                    $mail->setLogin(SMTP_PRIMARY_EMAIL, SMTP_PRIMARY_PASSWORD);
                    $mail->addTo($user['email'], $user['username']);
                    $mail->setFrom('noreply@socialnetwork.com', 'Social Network');
                    $mail->setSubject('Your password has been reset');
                    $mail->setHtmlMessage('Your password has now been reset. If this wasn\'t you please contact <b>help@socialnetwork.com</b>.');

                    if($mail->send()){
                        echo <<<ERROR
                        <div class="notification is-success">
                            Success!
                        ERROR;
                    } else {
                        echo <<<ERROR
                        <div class="notification is-danger">
                        An error occured, please contact help@socialnetwork.com
                        </div>  
                        ERROR;
                    }

                    header('Location: /login');
                }
                else {
                        echo <<<ERROR
                        <div class="notification is-danger">
                        This code is too old. Try resetting again <a href="/forgotpassword">here</a>.
                        </div>  
                        ERROR;
                }
            }
        }
    }

    public static function updateProfile()
    {
        if(!isset($_POST['username']) || $_POST['username'] == null)
        {
                        echo <<<ERROR
                        <div class="notification is-danger">
                        You have not set an username.
                        </div>  
                        ERROR;
        }
        else if(!isset($_POST['email']) || $_POST['email'] == null)
        {
                                   echo <<<ERROR
                        <div class="notification is-danger">
                        You have not set an email.
                        </div>  
                        ERROR;
        }
        else {
            DB::update("UPDATE users SET username = ?, email = ? WHERE id = ?", [
                    $_POST['username'],
                    $_POST['email'],
                    $_SESSION['user']['id']
            ]);
            
            $user = DB::fetch("SELECT * FROM users WHERE id = ?", [$_SESSION['user']['id']]);
            $_SESSION['user'] = $user;

                                    echo <<<ERROR
                        <div class="notification is-success">
                        Successfully updated.
                        </div>  
                        ERROR;
        }
    }
}