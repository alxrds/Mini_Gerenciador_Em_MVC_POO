<?php

    namespace App\Controller;

    use App\View\View;

    class PageController 
    {
        public function index()
        { 
            $view = new View('site/index.phtml');
            return $view->render();
        }
    }
