<?php



// Správce uživatelů redakčního systému
class UsersManager
{

	// Vrátí otisk hesla
	public function getHash($password)
	{
		//$sul = 'fd16sdfd2ew#$%';
		//return hash('sha256', $password . $sul);

        return $password;
	}

	// Registruje nového uživatele do systému
	public function register($name, $password, $confirmPassword, $year)
	{
		if ($year != date('Y'))
			throw new ChybaUzivatele('Chybně vyplněný antispam.');
		if ($password != $confirmPassword)
			throw new ChybaUzivatele('Hesla nesouhlasí.');
		$user = array(
			'login' => $name,
			'password' => $this->getHash($password),
		);
		try
		{
			Db::insert('users', $user);
		}
		catch (PDOException $chyba)
		{
			throw new ChybaUzivatele('Uživatel s tímto jménem je již zaregistrovaný.');
		}
	}

	// Přihlásí uživatele do systému
	public function login($login, $password)
	{
		$user = Db::queryOne('
			SELECT user_id, login, admin
			FROM users
			WHERE login = ? AND password = ?
		', array($login, $password));
		if (!$user)
			throw new ChybaUzivatele('Neplatné jméno nebo password.');
		$_SESSION['user'] = $user;
	}

	// Odhlásí uživatele
	public function logout()
	{
		unset($_SESSION['user']);
	}

	// Zjistí, zda je přihlášený uživatel administrátor
	public function getSessionOfCurrentUser()
	{
		if (isset($_SESSION['user']))

			return $_SESSION['user'];
		return null;
	}

	public function getUserById($id){
		$user = Db::queryOne('
			SELECT *
			FROM users
			WHERE user_id = ?
		', array($id));
		if (!$user)
			throw new ChybaUzivatele('User with this ID doesnt exist.');
		return $user;
	}

	public function updateUserById($id, $user){
		if (!$user)
			throw new ChybaUzivatele('User has NOT been updated');
		Db::update('users', $user, 'WHERE user_id = ?', array($id));

	}
	public function uploadImage($image){

		$uploaddir = 'files/';
		$uploadfile = $uploaddir . basename($image['name']);

		if (move_uploaded_file($image['tmp_name'], $uploadfile)) {
			echo "File is valid, and was successfully uploaded.\n";
		} else {
			echo "Possible file upload attack!\n";
		}

		return $uploadfile;

	}

    public function getAllUsers(){
        $users = Db::queryAll('SELECT * FROM users');
        return $users;
    }

}