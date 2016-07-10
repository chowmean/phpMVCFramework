<?php

class login_test
{
    private $post_var;
    function __construct($post) {
        $this->post_var=$post;
        $this->login_ver($this->post_var);
    }
    function login_ver($request)
    {
        if (empty($request)) {
            header("HTTP/1.0 404 Not Found");
            print_r(json_encode(array("message" => 'RESOURCE_NOT_FOUND', 'success' => false)));
        } else {
            try {
                $mob = isset($request['username'])?$request['username']:null;
                $password = isset($request['password']) ? $request['password']:null;
                $role = isset($request['role']) ? $request['role'] :null;
                require_once("connection.php");
                require_once('utils.php');
                $utility = new utils();
                $valid=$utility->check_valid_parameters($mob,$password,$role);
                if($valid) {
                    if ($role == "customer") $role = 'c';
                    if ($role == "truck_driver") $role = 't';

                    $message = array("message" => "", "token" => "", "success" => false);
                    $con = new DB_CONNECT();
                    $conn = $con->connect();
                    if ($role != -1) {
                        $statement = $conn->prepare("select user_id,salt,password from login where mobile_no = :name and role=:role");
                        $statement->execute(array(':name' => $mob,
                            ':role' => $role));
                        $row = $statement->fetch();
                        if ($statement->rowCount() > 0) {
                            $hash = $row['password'];
                            $salt = $row['salt'];
                            $user_id = $row['user_id'];
                            if (hash_hmac('ripemd160', $password, $salt) == $hash) {
                                $token = $utility->generate_token($mob);
                                $query_token = $conn->prepare("insert into islogin(user_id,token) VALUES (:userid,:token);");
                                try {
                                    $query_token->execute(array(':userid' => $user_id, ':token' => $token));
                                } catch (PDOException $e) {
                                    if ($e->errorInfo[1] == 1062) {
                                    }
                                }
                                $message['message'] = "Entered user is correct";
                                $message['token'] = $token;
                                $role = $utility->encrypt_role($role);
                                $message['role'] = $role;
                                $message['success'] = true;
                                print_r(json_encode($message));

                            } else {
                                $message['message'] = "Password is incorrect";
                                $token = null;
                                $message['token'] = $token;
                                $message['success'] = false;
                                print_r(json_encode($message));
                            }
                        } else {
                            $message = array("message" => "", "token" => "", "success" => false);
                            $message['message'] = 'Username does not exist';
                            $message['token'] = null;
                            $message['success'] = false;
                            print_r(json_encode($message));
                        }
                    } else {
                        print_r(json_encode(array("message" => 'Access Denied', 'success' => false)));
                    }

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
