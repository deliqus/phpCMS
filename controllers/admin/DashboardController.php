<?php



// Controller for user account

class DashboardController extends Controller
{
    public function zpracuj($parametry)
    {
		// Do administrace mají přístup jen přihlášení uživatelé
		$this->isAdmin();
		// Hlavička stránky
		$this->hlavicka['title'] = 'Přihlášení';
		// Získání dat o přihlášeném uživateli
		$spravceUzivatelu = new UsersManager();

		if (!empty($parametry[0]) && $parametry[0] == 'logout')
		{
			$spravceUzivatelu->logout();
			$this->presmeruj('login');
		}

		$user = $spravceUzivatelu->getSessionOfCurrentUser();
        $this->data['name'] = $user['name'];
		$this->data['admin'] = $user['admin'];



        // Nastavení šablony
		$this->pohled = 'dashboard';
    }

}

echo "Dashboard";
