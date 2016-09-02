<?php

class FrontController
{
    protected $_controller, $_action, $_body;
    protected $_params = array();
    static $_instance;

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $request = $_SERVER['REQUEST_URI'];
        $splits = explode('/', trim($request, '/'));
        //Controller
        $this->_controller = !empty($splits[0]) ? ucfirst($splits[0]) . 'Controller' : 'IndexController';
        //Action
        $this->_action = !empty($splits[1]) ? ucfirst($splits[1]) . 'Action' : 'indexAction';
        //Есть ли параметры и их значения
        $key = $value = null;
        if(!empty($splits[2])){

            for($i=2,$cnt = count($splits); $i<$cnt;$i++){
                if($i % 2  == 0){
                    // чётное = ключ (параметр)
                    $key = $splits[$i];
                }else{
                    // значение параметра
                    $value = $splits[$i];
                }

                $this->_params[$key] = !empty($value)? $value : '';

            }

        }
    }

    private function __clone(){}

    public function route(){
        if(class_exists($this->getController())){
            $rc = new ReflectionClass($this->getController());
            if($rc->implementsInterface('IController')){
                if($rc -> hasMethod($this->getAction())){
                    $controller = $rc->newInstance();
                    $method = $rc->getMethod($this->getAction());
                    $method->invoke($controller);
                }else{
                    throw new Exception ('Action');
                }

            }else{
              throw new Exception('Interface');
            }
        }else{
            throw new Exception('Controller');
        }
    }

    public function getParams(){
        return $this->_params;
    }

    public function getController(){
        return $this->_controller;
    }

    public function getAction(){
        return $this->_action;
    }

    public function getBody(){
        return $this->_body;
    }

    public function setBody($body){
        $this->_body = $body;
    }

}