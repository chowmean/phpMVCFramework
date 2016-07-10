<?php

class home
{
    function __constructor()
    {
        $this->home();
    }
    function home()
    {
        try {
            $message = array('message' => 'get up and running', 'success' => true);
            print_r(json_encode($message));
        }
        catch (Exception $e)
        {
            print_r(json_encode(array("message" => 'Server Error!!', 'suceess' => false)));
        }

    }
}
