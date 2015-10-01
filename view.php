<?php

class View {
	public function navigate($auth, $languages, $translate) {
        $naw = '<div class="naw"><select id="lang" onChange="return get_lang();">' . $languages . '</select>';

	    if (empty($auth)) {	    	$naw .= '<a href="#" onClick="return ajax(\'engine.php?mod=registration\');">'.$translate['reg'].'</a>
	    			 <a href="#" onClick="return ajax(\'engine.php?mod=authorization\');">'.$translate['authorize'].'</a>';

	    } else {
	    	$naw .= '<a href="#" onClick="return ajax(\'engine.php?mod=info\');">'.$translate['personal'].'</a>
	    			 <a href="#" onClick="return ajax(\'engine.php?mod=exit\');">'.$translate['logout'].'</a>';

	    }

	    $naw .= '</div>';

        return $naw;

	}

	public function authorization($data) {
        $success = '';

        if (isset($_GET['reg'])) $success = '<div class="success">'.$data['success'].'</div>';

		$html = $this->navigate($data['auth'], $data['languages'], $data).

		'<h1>'.$data['authorize'].'</h1>
		'.$success.'
        <form action="#" method="post" id="authorization">
		<div class="row">
        	<label for="login">* '.$data['login'].'</label>
        	<input type="text" id="login">
      	</div>
		<div class="row">
        	<label for="password">* '.$data['pass'].'</label>
        	<input id="password" type="password">
        	<div id="password_error" class="error">'.$data['pass_err'].'</div>
      	</div>
		<div class="row">
        	<input type="submit" value="Войти" onClick="return authorization();">
      	</div>
        </form>';

      return $html;

	}
	public function registration($data) {

		$html =  $this->navigate($data['auth'], $data['languages'], $data).

		'<h1>'.$data['reg'].'</h1>

        <form action="#" method="post" id="upload_form" enctype="multipart/form-data">

		<div class="row">
        	<label for="login">* '.$data['login'].'</label>
        	<input type="text" id="login">
        	<div id="login_error" class="error">'.$data['login_err_1'].'</div>
        	<div id="login_zanyat_error" class="error">'.$data['login_err_2'].'</div>
        	<div class="info"><i></i> '.$data['login_info'].'</div>
      	</div>

		<div class="row">
        	<label for="name">* '.$data['y_name'].'</label>
        	<input type="text" id="name" name="name">
        	<div id="name_error" class="error">'.$data['this_row'].'</div>
      	</div>

		<div class="row">
        	<label for="day">* '.$data['day_date'].'</label>
        	<input type="text" id="day" style="width:50px" placeholder="'.$data['number'].'">

        		<select id="month">';

        		foreach ($data['month'] as $key => $val) $html .= '<option value="'.$key.'">'.$val.'</option>';

				$html .= '</select>

 				<input type="text" id="year" style="width:50px" placeholder="'.$data['year'].'">

        	<div id="day_error" class="error">'.$data['day_error'].'</div>
        	<div id="month_error" class="error">'.$data['month_error'].'</div>
        	<div id="year_error" class="error">'.$data['year_error'].'</div>
      	</div>

		<div class="row">
        	<label for="pol">* '.$data['pol'].'</label>
        	<input name="pol" id="man" type="radio" value="man"> '.$data['m'].'
        	<input name="pol" id="woman" type="radio" value="woman"> '.$data['z'].'
        	<div id="pol_error" class="error">'.$data['pol_err'].'</div>
      	</div>

		<div class="row">
        	<label for="photo">* '.$data['photo'].'</label>
        	<input type="file" name="photo" id="photo">
        	<div id="photo_error" class="error">'.$data['select_file'].'</div>
        	<div class="info"><i></i> '.$data['file_info'].'</div>
      	</div>

		<div class="row">
        	<label for="info">'.$data['about'].'</label>
        	<textarea id="info"></textarea>
      	</div>

		<div class="row">
        	<label for="email">E-Mail</label>
        	<input type="text" id="email">
        	<div id="email_error" class="error">'.$data['mail_err'].'</div>
        	<div class="info"><i></i> '.$data['mail_info'].'</div>
      	</div>

		<div class="row">
        	<label for="password">* '.$data['pass'].'</label>
        	<input id="password" type="password">
        	<div id="password_error" class="error">'.$data['pass_err1'].'</div>
            <div class="info"><i></i> '.$data['pass_inf'].'</div>
      	</div>

		<div class="row">
        	<label for="password1">* '.$data['conf_pass'].'</label>
        	<input id="password1" type="password">
        	<div id="password1_error" class="error">'.$data['conf_pass_err'].'</div>
      	</div>

		<div class="row">
        	<input type="submit" value="'.$data['reg'].'" onClick="return registration();">
            <br />
        	<br />
        	<div class="info">'.$data['registr_info'].'</div>
      	</div>

        </form>';

      return $html;

	}


	public function info($data, $info) {

		$html = $this->navigate($data['auth'], $data['languages'], $data).


		'<h1>'.$data['personal'].'</h1>
		<div class="row">
        	<label for="login">'.$data['login'].': '.$info['login'].'</label>
      	</div>

		<div class="row">
        	<label for="name">'.$data['y_name'].': '.$info['name'].'</label>
      	</div>

		<div class="row">
        	<label for="day">'.$data['day_date'].': '.$info['date'].'</label>
      	</div>

		<div class="row">
        	<label for="pol">'.$data['pol'].': '.$info['pol'].'</label>
      	</div>

		<div class="row">
        	<label for="photo">'.$data['photo'].':</label>
        	<img src="upload/'.$info['id'].'.'.$info['photo'].'" width="200">
      	</div>

		<div class="row">
        	<label for="info">'.$data['about'].': '.$info['info'].'</label>
      	</div>

		<div class="row">
        	<label for="email">E-Mail: '.$info['email'].'</label>
      	</div>';

      return $html;

	}



}