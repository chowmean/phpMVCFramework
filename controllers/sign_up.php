<?php

class sign_up
{
    private $post_var;
    function __construct($request)
    {
        $this->post_var=$request;
        $this->register($this->post_var);
    }
    function register($request)
    {
        if (empty($request)) {
            header("HTTP/1.0 404 Not Found");
            print_r(json_encode(array("message" => 'RESOURCE_NOT_FOUND', 'success' => false)));
        } else {
            try {
                $mob = isset($request['mobile_no'])?$request['mobile_no']:null;
                $role = isset( $request['role']) ? $request['role']: null;
                require_once("utils.php");
                $utility = new utils();
                $validity=$utility->check_mobileno($mob);
                $valid=$utility->check_valid_parameters($role,$mob);
                if($valid and $validity) {
                    if ($role == 'customer') $role = 'c';
                    if ($role == 'truck_driver') $role = 't';
                    $message = array("message" => "", "token" => "", "success" => false);
                    require_once("connection.php");
                    $con = new DB_CONNECT();
                    $conn = $con->connect();

                    $statement = $conn->prepare("select mobile_no from login where mobile_no = :name and role= :role");
                    $statement->execute(array(':name' => $mob,
                        ':role' => $role));

		$row = $statement->fetch();

                    if ($statement->rowCount() == 0) {

                        $result = json_decode($utility->generate_otp($mob));
		   if($result->status=="success") {
                            $message['message'] = "OTP Send Successfully!!";
                            $message['success'] = true;
                            $message['result'] = $result;
                            print_r(json_encode($message));
                        }
                        else
                        {
                            $message['message'] = $result->response->code;
                            $message['success'] = false;
                            $message['result'] = $result;
                            print_r(json_encode($message));
                        }
                    } else {
                        $message['message'] = "User Already Registered !! Please Try again";
                        $message['token'] = null;
                        $message['success'] = false;
                        print_r(json_encode($message));

                    }
                }
                else
                {
                    header("HTTP/1.0 400: Bad Request");
                    print_r(json_encode(array("message" => 'INVALID_REQUEST_BODY', 'success' => false)));
                }
                $con->close();
            } catch (Exception $e) {
                print_r(json_encode(array("message" => 'Server Error!!', 'success' => false)));
            } finally {
                $con->close();
            }
        }
    }
}
