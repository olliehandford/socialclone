<?php

class APIController extends Controller 
{
    public static function postThread()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        
        if(!isset($input['message'])) {
            $response = [
                'error' => true,
                'message' => 'No message set'
            ];
            http_response_code(400); // send the response code so that Vue can easily deal with errors.
            header('Content-Type: application/json'); // set response type as json
            echo json_encode($response);
        }
        else {
            $user = DB::fetch("SELECT * FROM users WHERE apikey = ?", [$input['apiKey']]);

            if($user != null)
            {
                $clean = strip_tags($input['message']);
                DB::insert("INSERT INTO posts (owner, content, timestamp) VALUES (?, ?, ?)", [
                    $user['id'],
                    $clean,
                    time(),
                ]);
                $response = [
                    'error' => false,
                    'message' => 'Successfully posted'
                ];
                http_response_code(200); // send the response code so that Vue can easily deal with errors.
                header('Content-Type: application/json'); // set response type as json
                echo json_encode($response);
            }
            else {
                $response = [
                    'error' => true,
                    'message' => 'Invalid API key'
                ];
                http_response_code(401); // send the response code so that Vue can easily deal with errors.
                header('Content-Type: application/json'); // set response type as json
                echo json_encode($response);
            }
        }
    }

    public static function postImage()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        
        if(!isset($input['message'])) {
            $response = [
                'error' => true,
                'message' => 'No message set'
            ];
            http_response_code(400); // send the response code so that Vue can easily deal with errors.
            header('Content-Type: application/json'); // set response type as json
            echo json_encode($response);
        }
        else if (!isset($input['image']))
        {
            $response = [
                'error' => true,
                'message' => 'No image set'
            ];
            http_response_code(400); // send the response code so that Vue can easily deal with errors.
            header('Content-Type: application/json'); // set response type as json
            echo json_encode($response); 
        }
        else if (!isset($input['tags']))
        {
            $response = [
                'error' => true,
                'message' => 'No tags set'
            ];
            http_response_code(400); // send the response code so that Vue can easily deal with errors.
            header('Content-Type: application/json'); // set response type as json
            echo json_encode($response); 
        }
        else {
            $user = DB::fetch("SELECT * FROM users WHERE apikey = ?", [$input['apiKey']]);

            if($user != null)
            {
                $clean = strip_tags($input['message']);
                $clean_tags = strip_tags($input['tags']);

                DB::insert("INSERT INTO posts (owner, content, timestamp, isPicture, pictureData, pictureTags) VALUES (?, ?, ?, ?, ?, ?)", [
                    $user['id'],
                    $clean,
                    time(),
                    1,
                    $input['image'],
                    $clean_tags
                ]);
                $response = [
                    'error' => false,
                    'message' => 'Successfully posted'
                ];
                http_response_code(200); // send the response code so that Vue can easily deal with errors.
                header('Content-Type: application/json'); // set response type as json
                echo json_encode($response);
            }
            else {
                $response = [
                    'error' => true,
                    'message' => 'Invalid API key'
                ];
                http_response_code(401); // send the response code so that Vue can easily deal with errors.
                header('Content-Type: application/json'); // set response type as json
                echo json_encode($response);
            }
        }
    }

    public static function getPosts()
    {
        if(isset($_GET['currentPosts']))
        {
            $posts = DB::fetchAll("SELECT * FROM posts WHERE deleted = 0 AND isReply = 0 ORDER BY timestamp DESC LIMIT 5 OFFSET " . $_GET['currentPosts']);
        }
        else {
            $posts = DB::fetchAll("SELECT * FROM posts WHERE deleted = 0 AND isReply = 0 ORDER BY timestamp DESC LIMIT 5");
        }
       

        foreach($posts as &$post)
        {
            // fetch each post owners details
            $user = DB::fetch("SELECT * FROM users WHERE id = ?", [$post['owner']]);
            unset($user['password']);
            unset($user['apikey']);
            $user['avatar'] = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $user['email'] ) ) ) . "&s=64";
            unset($user['email']);
            $post['user'] = $user;

            // fetch post likes
            $likes = DB::count("SELECT * FROM user_has_liked WHERE postid = ?", [$post['id']]);
            $post['likes'] = $likes;

            //fetch replies
            $replies = DB::fetchAll("SELECT * FROM posts WHERE parentid = ? AND isReply = 1 AND deleted = 0 ORDER BY timestamp DESC", [$post['id']]);

                foreach($replies as &$reply)
                {
                    // fetch each post owners details
                    $user = DB::fetch("SELECT * FROM users WHERE id = ?", [$reply['owner']]);
                    unset($user['password']);
                    unset($user['apikey']);
                    $user['avatar'] = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $user['email'] ) ) ) . "&s=64";
                    unset($user['email']);
                    $reply['user'] = $user;  

                    // fetch reply likes
                    $likes = DB::count("SELECT * FROM user_has_liked WHERE postid = ?", [$reply['id']]);
                    $reply['likes'] = $likes;

                }
                $post['replies'] = $replies;
    
        }

        http_response_code(200); // send the response code so that Vue can easily deal with errors.
        header('Content-Type: application/json'); // set response type as json
        echo json_encode($posts);
    }

    public static function likePost()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        
        if(!isset($input['postid'])) {
            $response = [
                'error' => true,
                'message' => 'No postid set'
            ];
            http_response_code(400); // send the response code so that Vue can easily deal with errors.
            header('Content-Type: application/json'); // set response type as json
            echo json_encode($response);
        }
        else {
            $user = DB::fetch("SELECT * FROM users WHERE apikey = ?", [$input['apiKey']]);

            if($user != null)
            {
                
                DB::insert("INSERT IGNORE INTO user_has_liked (userid, postid) VALUES (?, ?)", [
                    $user['id'],
                    $input['postid']
                ]);
                $response = [
                    'error' => false,
                    'message' => 'Successfully liked'
                ];
                http_response_code(200); // send the response code so that Vue can easily deal with errors.
                header('Content-Type: application/json'); // set response type as json
                echo json_encode($response);
            }
            else {
                $response = [
                    'error' => true,
                    'message' => 'Invalid API key'
                ];
                http_response_code(401); // send the response code so that Vue can easily deal with errors.
                header('Content-Type: application/json'); // set response type as json
                echo json_encode($response);
            }
        }
    }

    public static function deletePost()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        
        if(!isset($input['postid'])) {
            $response = [
                'error' => true,
                'message' => 'No postid set'
            ];
            http_response_code(400); // send the response code so that Vue can easily deal with errors.
            header('Content-Type: application/json'); // set response type as json
            echo json_encode($response);
        }
        else {
            $user = DB::fetch("SELECT * FROM users WHERE apikey = ?", [$input['apiKey']]);

            if($user != null)
            {
                $post = DB::count("SELECT * FROM posts WHERE owner = ? AND id = ?", [$user['id'], $input['postid']]);
                if($post != 0)
                {
                    $post = DB::update("UPDATE posts SET deleted = 1 WHERE id = ?", [$input['postid']]);
                    $response = [
                        'error' => false,
                        'message' => 'Successfully deleted'
                    ];
                    http_response_code(200); // send the response code so that Vue can easily deal with errors.
                    header('Content-Type: application/json'); // set response type as json
                    echo json_encode($response);
                }
                else {
                    $response = [
                        'error' => true,
                        'message' => 'You cannot delete a post that isnt yours.'
                    ];
                    http_response_code(401); // send the response code so that Vue can easily deal with errors.
                    header('Content-Type: application/json'); // set response type as json
                    echo json_encode($response);
                }
            }
            else {
                $response = [
                    'error' => true,
                    'message' => 'Invalid API key'
                ];
                http_response_code(401); // send the response code so that Vue can easily deal with errors.
                header('Content-Type: application/json'); // set response type as json
                echo json_encode($response);
            }
        }
    }


    public static function replyToPost()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        
        if(!isset($input['message'])) {
            $response = [
                'error' => true,
                'message' => 'No message set'
            ];
            http_response_code(400); // send the response code so that Vue can easily deal with errors.
            header('Content-Type: application/json'); // set response type as json
            echo json_encode($response);
        }
        else {
            $user = DB::fetch("SELECT * FROM users WHERE apikey = ?", [$input['apiKey']]);

            if($user != null)
            {
                $clean = strip_tags($input['message']);
                DB::insert("INSERT INTO posts (owner, content, timestamp, isReply, parentid) VALUES (?, ?, ?, ?, ?)", [
                    $user['id'],
                    $clean,
                    time(),
                    1,
                    $input['postid']
                ]);
                $response = [
                    'error' => false,
                    'message' => 'Successfully posted'
                ];
                http_response_code(200); // send the response code so that Vue can easily deal with errors.
                header('Content-Type: application/json'); // set response type as json
                echo json_encode($response);
            }
            else {
                $response = [
                    'error' => true,
                    'message' => 'Invalid API key'
                ];
                http_response_code(401); // send the response code so that Vue can easily deal with errors.
                header('Content-Type: application/json'); // set response type as json
                echo json_encode($response);
            }
        }
    }
}