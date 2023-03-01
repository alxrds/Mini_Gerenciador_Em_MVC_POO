<?php

    namespace App\Controller;

    use App\Authenticator\CheckUserLogged;
    use App\DB\Connection;
    use App\Entity\Expense;
    use App\Entity\Category;
    use App\Entity\User;
    use App\View\View;
    use App\Session\Session;

    class ExpenseController 
    {
        use CheckUserLogged;

        public function __construct()
        {
            if(!$this->check()){
                header('Location: ' .HOME. '/auth/login');
            }
        }
        
        public function index()
        {
            $userId = Session::get('user')['id'];
            $view = new View('site/expense/index.phtml');
            $view->expenses = (new Expense(Connection::getInstance()))->where(['users_id'=>$userId]);
            return $view->render();
        }

        public function new()
        {   
            $userId = Session::get('user')['id'];
            $method = $_SERVER['REQUEST_METHOD'];
            $connection = Connection::getInstance();

            if($method == 'POST'){
                $data = $_POST;
                $data['users_id'] = $userId;
                $expense = new Expense($connection);
                $expense->insert($data);
                return header('Location: ' .HOME. '/expense');
            }

            $view = new View('site/expense/new.phtml');

            $view->categories = (new Category($connection))->findAll();
            $view->users = (new User($connection))->findAll();

            return $view->render();
        }

        public function edit($id)
        {
            $view = new View('site/expense/edit.phtml');
            $method = $_SERVER['REQUEST_METHOD'];
            $connection = Connection::getInstance();

            if($method == 'POST'){
                $data = $_POST;
                $data['id'] = $id;
                $expense = new Expense($connection);
                $expense->update($data);
                return header('Location: ' .HOME. '/expense');
            }

            $view->categories = (new Category($connection))->findAll();
            $view->users = (new User($connection))->findAll();
            $view->expense = (new Expense($connection))->find($id);

            return $view->render();
        }

        public function remove($id)
        {   
            $expense = new Expense(Connection::getInstance());
            $expense->delete($id);
            return header('Location: ' .HOME. '/expense');
        }
    }