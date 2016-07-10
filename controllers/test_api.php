<?php

class test_api_post
{
    public $post_var;
    function __construct($post) {
        $this->post_var=$post;
        $this->test_api($post);
    }

    function test_api($request)
    {
        try {
            $test = $request['test'];
            $message = array('message' => "api up and running", 'test' => $test, 'success' => true);
            print_r(json_encode($message));
        }
        catch (Exception $e)
        {
            print_r(json_encode(array("message" => 'Server Error!!', 'success' => false)));
        }

    }
}
