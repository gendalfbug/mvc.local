<?php

class IndexController implements IController
{
    public function indexAction()
    {
        $fc = FrontController::getInstance();

        //Добавляем
        $params = $fc->getParams();
        $view = new View(); //  название модели  (не представление)

        $view->name = 'Guest';
        $result = $view->render('../views/index.php');

        $fc->setBody($result);
    }
}