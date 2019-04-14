<?php


class LoginController extends Controller
{
    public function zpracuj($parametry)
    {
		$spravceUzivatelu = new UsersManager();
		if ($spravceUzivatelu->getSessionOfCurrentUser())
			$this->presmeruj('administrace');
		// Hlavička stránky
		$this->hlavicka['title'] = 'Přihlášení';
		if ($_POST)
		{
			try
			{
				$spravceUzivatelu->login($_POST['login'], $_POST['password']);
                $type = "success";
				$this->pridejZpravu('Byl jste úspěšně přihlášen.', $type);
				$this->presmeruj('my-account');
			}
			catch (ChybaUzivatele $chyba)
			{
				$type = "error";
				$this->pridejZpravu($chyba->getMessage(), $type);
			}
		}
		// Nastavení šablony
		$this->pohled = 'login';
    }
}