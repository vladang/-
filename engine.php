<?php

error_reporting(E_ALL);

ini_set('log_errors', 'On');
ini_set('error_log', 'error.log');

header('Content-Type: text/html; charset=utf-8');

require_once('view.php');

class Engine {	private $img_format = array('jpg', 'jpeg', 'gif', 'png');

	function __construct() {
		session_start();

		$this->sql = new mysqli('localhost', 'root', '', 'testovoe');
		$this->sql->set_charset('utf8');

		$this->view = new View;

		if (empty($_SESSION['lang'])) $_SESSION['lang'] = 1;

	}

	private function translation($key) {		$result = $this->sql->query("SELECT `translation` FROM `translation` WHERE `id_language`='" . $this->sql->escape_string($_SESSION['lang']) . "' AND `key`='" . $this->sql->escape_string($key) . "'")->fetch_assoc();
	    return $result['translation'];
	}

	private function languages() {
		$query = $this->sql->query("SELECT * FROM `language`");
        $l = '';
        while ($data = $query->fetch_assoc()) {        	$sel = '';        	if ($data['id'] == $_SESSION['lang']) $sel = 'selected';        	$l .= '<option value="'.$data['id'].'" '.$sel.'>'.$data['name'].'</option>';
        }
	    return $l;
	}

	private function month() {
        $month = array();
		for ($i = 0; $i <= 12; $i++) $month[] = $this->translation('month_' . $i);
		return $month;
	}

	private function typeFile($image) {
		$name = basename($image);
		$name = explode('.', $name);
		return end($name);
	}

	private function checkAuth() {		if (!empty($_SESSION['id']) && !empty($_SESSION['password'])) {			$result = $this->sql->query("SELECT `password` FROM `users` WHERE `id`='".$this->sql->escape_string($_SESSION['id'])."'")->fetch_assoc();
			if ($result['password'] == $_SESSION['password']) return true; else return false;
		} else {			return false;
		}
	}

	public function exits() {		unset($_SESSION['id'], $_SESSION['password']);
		return $this->registration_form();
	}

	public function lang() {
	    $result = $this->sql->query("SELECT `id` FROM `language` WHERE `id`='".$this->sql->escape_string($_GET['id'])."'")->fetch_assoc();
	    if (!empty($result['id'])) $_SESSION['lang'] = $result['id'];

		return $this->index();
	}

	public function registration_form() {
		if ($this->checkAuth()) exit($this->translation('registerd'));
		$data = array();
		$data['languages']     = $this->languages();
		$data['auth']          = $this->checkAuth();
		$data['month']         = $this->month();
        $data['reg']           = $this->translation('reg');
        $data['authorize']     = $this->translation('authorize');
        $data['login']         = $this->translation('login');
        $data['pass']          = $this->translation('pass');
		$data['login_err_1']   = $this->translation('login_err_1');
		$data['login_err_2']   = $this->translation('login_err_2');
		$data['login_info']    = $this->translation('login_info');
		$data['y_name']        = $this->translation('y_name');
		$data['this_row']      = $this->translation('this_row');
		$data['day_date']      = $this->translation('day_date');
		$data['number']        = $this->translation('number');
		$data['year']          = $this->translation('year');
		$data['day_error']     = $this->translation('day_error');
		$data['month_error']   = $this->translation('month_error');
		$data['year_error']    = $this->translation('year_error');
		$data['pol']           = $this->translation('pol');
		$data['m']             = $this->translation('m');
		$data['z']             = $this->translation('z');
		$data['pol_err']       = $this->translation('pol_err');
		$data['photo']         = $this->translation('photo');
		$data['select_file']   = $this->translation('select_file');
		$data['file_info']     = $this->translation('file_info');
		$data['about']         = $this->translation('about');
		$data['mail_err']      = $this->translation('mail_err');
		$data['mail_info']     = $this->translation('mail_info');
		$data['pass_err1']     = $this->translation('pass_err1');
		$data['pass_inf']      = $this->translation('pass_inf');
		$data['conf_pass']     = $this->translation('conf_pass');
		$data['conf_pass_err'] = $this->translation('conf_pass_err');
		$data['registr_info']  = $this->translation('registr_info');
		$data['success']       = $this->translation('success');

		$html = $this->view->registration($data);

        return $html;
	}

	public function authorization_form() {
	    if ($this->checkAuth()) exit($this->translation('authorized'));
        $data = array();

		$data['languages'] = $this->languages();
		$data['auth']      = $this->checkAuth();
        $data['success']   = $this->translation('success');
		$data['reg']       = $this->translation('reg');
        $data['authorize'] = $this->translation('authorize');
        $data['login']     = $this->translation('login');
        $data['pass']      = $this->translation('pass');
        $data['pass_err']  = $this->translation('pass_err');

		$html = $this->view->authorization($data);

        return $html;
	}

	public function authorization_post() {
        if ($this->checkAuth()) exit($this->translation('authorized'));

    	$result = $this->sql->query("SELECT `id`,`password` FROM `users` WHERE `login`='".$this->sql->escape_string($_POST['login'])."'")->fetch_assoc();
    	if (!empty($result['id']) && $result['password'] == md5($_POST['password'])) {    		$_SESSION['id'] = $result['id'];
    		$_SESSION['password'] = md5($_POST['password']);
    		return '{"status":"success"}';
    	} else {    		return '{"status":"error"}';
    	}
	}

	public function info() {

        if ($this->checkAuth()) {
            $result = $this->sql->query("SELECT * FROM `users` WHERE `id`='".$this->sql->escape_string($_SESSION['id'])."'")->fetch_assoc();

            $data = array();

            $data['languages'] = $this->languages();
            $data['auth']      = $this->checkAuth();
        	$data['personal']  = $this->translation('personal');
        	$data['logout']    = $this->translation('logout');
        	$data['login']     = $this->translation('login');
        	$data['y_name']    = $this->translation('y_name');
        	$data['day_date']  = $this->translation('day_date');
        	$data['pol']       = $this->translation('pol');
        	$data['photo']     = $this->translation('photo');
        	$data['about']     = $this->translation('about');

        	$html = $this->view->info($data, $result);

        } else {
        	$html = 'Вы не авторизованы!';
        }
        return $html;
	}

	public function registration_post() {
		if ($this->checkAuth()) exit($this->translation('registerd'));
		$json = array();
        if (!preg_match("#^[a-zA-Z0-9]+$#", $_POST['login']) || strlen($_POST['login']) < 6) {        	$json['login'] = 'login_error';
        } else {        	$result = $this->sql->query("SELECT `id` FROM `users` WHERE `login`='" . $this->sql->escape_string($_POST['login']) . "'")->fetch_assoc();
            if (!empty($result['id'])) $json['login'] = 'login_zanyat_error';
        }
        if (!empty($_POST['email']) && !preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $_POST['email'])) $json['email'] = 'email_error';
        if (!preg_match("#^[a-zA-Z0-9]+$#", $_POST['password']) || strlen($_POST['password']) < 6) $json['password'] = 'password_error';
        if ($_POST['password'] != $_POST['password1']) $json['password1'] = 'password1_error';

        $photo = $this->typeFile($_FILES['photo']['name']);
        if (!in_array($photo, $this->img_format)) $json['photo'] = 'photo_error';

        if (empty($json)) {
        	$date = $_POST['day'] .'.'. $_POST['month'] .'.'. $_POST['year'];        	$this->sql->query("INSERT INTO `users` (`login`,`password`,`name`,`date`,`pol`,`photo`,`info`,`email`,`reg_date`) VALUES (
        			'".$this->sql->escape_string($_POST['login'])."',
        			'".md5($_POST['password'])."',
        			'".$this->sql->escape_string($_POST['name'])."',
        			'".$this->sql->escape_string($date)."',
        			'".$this->sql->escape_string($_POST['pol'])."',
        			'".$this->sql->escape_string($photo)."',
        			'".$this->sql->escape_string($_POST['info'])."',
        			'".$this->sql->escape_string($_POST['email'])."',
        			NOW())");

        	$fileName = $this->sql->insert_id . '.' . $photo;

        	move_uploaded_file($_FILES['photo']['tmp_name'], 'upload/'.$fileName);

        	$json['status'] = 'success';

        } else {        	$json['status'] = 'error';
        }

        return json_encode($json);
	}

	public function index() {		if ($this->checkAuth()) return $this->info(); else return $this->registration_form();
	}
}

$Engine = new Engine();

switch (@$_GET['mod']) {

	case 'registration':
		echo $Engine->registration_form();
	break;

	case 'registration_post':
		echo $Engine->registration_post();
	break;

	case 'authorization':
		echo $Engine->authorization_form();
	break;

	case 'authorization_post':
		echo $Engine->authorization_post();
	break;

	case 'info':
		echo $Engine->info();
	break;

	case 'lang':
		echo $Engine->lang();
	break;

	case 'exit':
		echo $Engine->exits();
	break;

	default:
    	echo $Engine->index();
	break;

}