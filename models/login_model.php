<?php

class Login_Model extends Model
{

    public function __construct()
    {
        parent::__construct();
    }


    function run()
    {
    	
    	//`u_id`, `u_email`, `u_username`, `u_password`, `u_name`, `u_surname`, `u_tel`, `u_active`, `u_own`, `Address`
        $login = $_POST['login'];
        $pass = hash('sha512', $_POST['password']);
        
        
        $sth = $this->fetch_command(array(
            'select' => 'u_id, u_own',
            'from' => 'user',
            'where' => "`u_email` = '$login' AND `u_password` = '$pass' limit 1",
        ));
        
        $check = $sth->rowCount();
        if ($check == 1) {
            //login
            Session::set(sessionname, true);
            $fetch = $sth->fetch();
            Session::set('owner', $fetch['u_own']);
            Session::set('userid', $fetch['u_id']);


        } else {
            //error
            Jyll::get_error(5);
        }


    }

    function create()
    {
        $email = $_POST['email'];
        $pass = hash('sha512', $_POST['pass']);
        $u_username = $_POST['u_username'];
        $u_name = $_POST['u_name'];
        $u_surname = $_POST['u_surname'];
        $u_tel = $_POST['u_tel'];
        $address = $_POST['address'];

        
        $data = $this->fetch_command(array(
            'select'=>'u_email',
            'from'=>'user',
            'where'=>"u_email = '$email' limit 1",
        ),0,1);
        
      if($data->rowCount() != 0){
          Jyll::get_error(11);
          exit;
      }
        
        $data = $this->fetch_command(array(
            'select'=>'u_username',
            'from'=>'user',
            'where'=>"u_username = $u_username limit 1",
        ),0,1);

      if($data->rowCount() != 0){
          Jyll::get_error(12);
          exit;
      }
        
        $this->create_command(
            array(
                'table' => 'user',
                'into' => array(
                    'u_id' => 'NULL',
                    'u_email' => $email,
                    'u_username' => $u_username,
                    'u_password' => $pass,
                    'u_name' => $u_name,
                    'u_surname' => $u_surname,
                    'u_tel' => $u_tel,
                    'Address' => $address,
                ),
            ));
        


    }
    function sprawdz_uzytkownik(){
    	
    	$id = Session::get('userid');
    	if($id != null){
    	$data = $this->fetch_command(array(
    			'select' => '*',
    			'from' => 'user',
    			'where' => 'u_id=' . $id . ' limit 1',
    	));
    	


    	if($data->rowCount() == 0){
    		$ret['val'] = '0';
    		$ret['text'] = 'Nie zalogowano !';
    	}else{
    		
    		$data = $data->fetch();
    		
    		$ret['val'] = '1';
    		$ret['text'] = 'Zalogowany jako:'.$data['u_username'];
    	}
    	
    	}else{

    		$ret['val'] = '0';
    		$ret['text'] = 'Nie zalogowano !';
    	}
    	
    	return $ret;
    	
    }
    
    
    function login_menu(){
                     echo Jyll_menu::make('Dashboard', 'login/index2','user');
                     echo Jyll_menu::make('Dashboard', 'login/index2','moderator');
                     echo Jyll_menu::make('Dashboard', 'login/index2','admin');
        
                     echo Jyll_menu::make('Logout', 'login/logout','user');
                     echo Jyll_menu::make('Logout', 'login/logout','moderator');
                     echo Jyll_menu::make('Logout', 'login/logout','admin');
    }
}
