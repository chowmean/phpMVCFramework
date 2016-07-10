<?php

class logout
{
    protected $post_var;
    function __construct($post) {
        $this->post_var=$post;
        $this->logout($this->post_var);
    }

    function logout($request)
    {//further secured by checking the token
        if (empty($request)) {
            header("HTTP/1.0 404 Not Found");
            print_r(json_encode(array("message" => 'RESOURCE_NOT_FOUND', 'success' => false)));
        } else {
            try {
                $token = isset($request['token'])?$request['token']:null;
                require_once("connection.php");
                require_once("utils.php");
                $utility=new utils();
                $valid=$utility->check_valid_parameters($token);
                if($valid) {
                    $con = new DB_CONNECT();
                    $conn = $con->connect();
                    $query_token = $conn->prepare("delete from islogin where token=:token;");
                    $query_token->execute(array(':token' => $token));
                    $message = array('message' => 'Logging out!!', 'success' => true);
                    print_r(json_encode($message));
                }
                else
                {
                    header("HTTP/1.0 400: Bad Request");
                    print_r(json_encode(array("message" => 'INVALID_REQUEST_BODY', 'success' => false)));

                }

            } catch (Exception $e) {
                print_r(json_encode(array("message" => 'Server Error!!', 'success' => false)));
            } finally {
                $con->close();
            }
        }
    }
}
