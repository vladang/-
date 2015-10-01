function ajax(url) {

	xhr = new XMLHttpRequest(),
	xhr.open('GET', url, false);
	xhr.send(null);

	var statusElem = document.getElementById('inner');

	statusElem.innerHTML = xhr.responseText;

	return false;
}

function v(key) {
	return document.getElementById(key).value;
}

function er(key) {
	document.getElementById(key).classList.add('error_form');
	document.getElementById(key + '_error').style.display = 'block';
}

function clearError() {

	var els = document.getElementsByClassName('error');
	Array.prototype.forEach.call(els, function(el) {
    	el.style.display = 'none';
	});

	var els = document.getElementsByTagName('input');
	Array.prototype.forEach.call(els, function(el) {
		if (el.getAttribute('class') == 'error_form') el.classList.remove('error_form');
	});

	var els = document.getElementsByTagName('select');
	Array.prototype.forEach.call(els, function(el) {
		if (el.getAttribute('class') == 'error_form') el.classList.remove('error_form');
	});

}

function registration() {

	clearError();

	var e = document.getElementById('month');
	var month = e.options[e.selectedIndex].value;

    var err = 0;

	if (!(/^[a-zA-Z0-9]+$/.test(v('login'))) || v('login').length < 6) { er('login'); err = 1; }
    if (v('name').length < 2) { er('name'); err = 1; }

    var day = parseInt(v('day'));
    if ((/[^[0-9]/.test(day)) || day > 31 || day < 1) { er('day'); err = 1; }

    if (month == 0) er('month');

    var year = parseInt(v('year'));
    if ((/[^[0-9]/.test(year)) || year > 2015 || year < 1900) { er('year'); err = 1; }

    if (document.getElementById('man').checked) var pol = 'man';
    if (document.getElementById('woman').checked) var pol = 'woman';
    if (!pol) { document.getElementById('pol_error').style.display = 'block';  err = 1; }

    if (!v('photo')) { er('photo'); err = 1; }

    if (v('email') && !(/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/.test(v('email')))) { er('email'); err = 1; }

    if (v('password').length < 6 || !(/^[a-zA-Z0-9]+$/.test(v('password')))) { er('password'); err = 1; }
    if (v('password') != v('password1')) { er('password1'); err = 1; }

    if (err == 0) {

		var file = document.getElementById("photo"),
		xhr = new XMLHttpRequest(),
		form = new FormData();

    	var upload_file = photo.files[0];
    	form.append('photo', upload_file);
    	form.append('login', v('login'));
    	form.append('name', v('name'));
    	form.append('day', day);
    	form.append('month', month);
    	form.append('year', year);
    	form.append('pol', pol);
    	form.append('info', v('info'));
    	form.append('email', v('email'));
    	form.append('password', v('password'));
    	form.append('password1', v('password1'));

    	xhr.open('POST', 'engine.php?mod=registration_post', false);
    	xhr.send(form);

		if (xhr.status != 200) {

  			alert( xhr.status + ': ' + xhr.statusText );

		} else {

  			var jsonObj = JSON.parse(xhr.responseText);

            if (jsonObj.status == 'error') {

            	if (jsonObj.login == 'login_zanyat_error') {
					document.getElementById('login').classList.add('error_form');
					document.getElementById('login_zanyat_error').style.display = 'block';
            	}
            	if (jsonObj.login == 'login_error') er('login');
            	if (jsonObj.email == 'email_error') er('email');
            	if (jsonObj.password == 'password_error') er('password');
            	if (jsonObj.password1 == 'password1_error') er('password1');
            	if (jsonObj.photo == 'photo_error') er('photo');

            } else {
            	ajax('engine.php?mod=authorization&reg=1');
            }
		}
    }
	return false;
}

function authorization() {

    var err = 0;
    if (v('password').length < 6 || v('login').length < 6) { er('password'); err = 1; }

    if (err == 0) {

		xhr = new XMLHttpRequest(),
		form = new FormData();

    	form.append('login', v('login'));
    	form.append('password', v('password'));

    	xhr.open('POST', 'engine.php?mod=authorization_post', false);
    	xhr.send(form);

		if (xhr.status != 200) {

  			alert( xhr.status + ': ' + xhr.statusText );

		} else {

  			var jsonObj = JSON.parse(xhr.responseText);

            if (jsonObj.status == 'error') {
            	er('password');
            } else {
            	ajax('engine.php?mod=info');
            }
		}
    }
	return false;
}

function get_lang() {

	var e = document.getElementById('lang');
	var lang = e.options[e.selectedIndex].value;

	ajax('engine.php?mod=lang&id=' + lang);

	return false;
}

ajax('engine.php');
