<?php

class Login extends Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function _own()
    {
        $menu['nologged'] = array(
            'index', 'register','register_error','create','check_login'
        );
        $menu['user'] = array(
            'check_login','logout','index2'
        );
        $menu['moderator'] = array(
            'check_login','logout','index2'
        );
        $menu['admin'] = array(

            'check_login','logout','index2'
        );
        return $menu;
    }
    public function index()
    {
    	Jyll_owner::must_ajax_render('login/index','login/index');
    }

    public function index2()
    {
        Controller::FrameworkExt("system_info");
    	Jyll_owner::must_ajax_render('login/index2','login/index2');
    }

    public function register()
    {

    	Jyll_owner::must_ajax_render('login/register_ajax','login/register');
    }


    public function register_error()
    {
    
    	$this->view->render('login/register');
    }
    
    public function create()
    {
    	Jyll_form::required_form('login/register_error',array(
                'email'=>array('name'=>'Email','system'=>'email'),
                'pass'=>array(
                'name'=>'Haslo',
                'od'=>'3',
                'do'=>'50',
    	),
                'u_username'=>array(
                'name'=>'Login',
                'od'=>'3',
                'do'=>'50',
    	),
                'regulamin'=>'Regulamin',
        ));

        $this->model->create();
        $this->redirect('login/index');
    }

    public function check_login()
    {
    	Jyll_form::required_form('login/index',array(
                'login'=>array('name'=>'Email(login)','system'=>'email'),
    	));
    	
        $this->model->run();
        if (Session::get('userid') != null) {
            $this->redirect('login/index2');
        }
        //$this->redirect('dashboard');
    }

    public function logout()
    {

        Session::destroy();
        $this->redirect('homepage/index');



    }
}
