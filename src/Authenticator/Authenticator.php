<?php

    namespace App\Authenticator;

    use App\Session\Session;
    use App\Entity\User;

    class Authenticator
    {
        private $user;
        public function __construct(User $user = null)
        {
            $this->user = $user;
        }
        public function login(array $credentials)
        {
            $user = current($this->user->where([
                'email' => $credentials['email'],
            ]));
            if(!$user){
                return false;
            }
            if($user['password'] == md5($credentials['password'])){
                Session::add('user', $user);
                return true;
            }
            unset($user['password']);
        }

        public function logout()
        {
            if(Session::has('user')){
                Session::remove('user');
            }
            Session::clear();
        }
    }