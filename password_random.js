function random_password()
{
    var password='';
    var ch = 32+Math.floor(Math.random()*(126-32));
    var n = Math.floor(Math.random()*80);

    while (password.length < n) {
	switch(Math.floor(Math.random()*6)) {
	case 0: ch = 65+Math.floor(Math.random()*26); break;
	case 1: ch = 97+Math.floor(Math.random()*26); break;
	case 2: ch = 48+Math.floor(Math.random()*10); break;
	case 3: ch = 32+Math.floor(Math.random()*(126-32)); break;
	case 4: ch = 32+((ch-32+1) % (126 - 32)); break;
	case 5: break;
	}
	password += String.fromCharCode(ch);
    }
    return password;
}
