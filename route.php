<?php

class route
{
    private $_uri=array();
    private $_method=array();
    public function add($uri,$method=null)
    {
        $this->_uri[]='/'.trim($uri,'/');
        if($method!=null)
        {
            $this->_method[]=$method;
        }

    }
    public function submit()
    {
       $flag=0;
       $urigetparam= isset($_GET['id'])?$_GET['id']:'/';
        foreach ($this->_uri as $key=>$value) {
            if (preg_match("#^/$urigetparam$#", $value)) {
                if (isset($_POST)) {
                    $post = $_POST;
                    new $this->_method[$key]($post);
                } else
                    new $this->_method[$key]();
                $flag = 1;
                break;
                //call_user_func(new $this->_method[$key]());
            }
        }
        if ($flag==0)
        {
            header("HTTP/1.0 404 Not Found");
            print_r(json_encode(array("message" => 'RESOURCE_NOT_FOUND', 'success' => false)));
        }


    }

}
