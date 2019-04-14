<?php



// Controller pro zpracování článku

class RegistraceController extends Controller
{
    public function zpracuj($parametry)
    {
		// Hlavička stránky
		$this->hlavicka['title'] = 'Registrace';
		if ($_POST)
		{
			try
			{
				$spravceUzivatelu = new UsersManager();
				$spravceUzivatelu->register($_POST['name'], $_POST['password'], $_POST['confirmPassword'], $_POST['year']);
				$spravceUzivatelu->login($_POST['name'], $_POST['password']);
				$this->pridejZpravu('Byl jste úspěšně zaregistrován.');
				$this->presmeruj('administrace');
			}
			catch (ChybaUzivatele $chyba)
			{
				$this->pridejZpravu($chyba->getMessage());
			}
		}
		// Nastavení šablony
		$this->pohled = 'registrace';
    }
}