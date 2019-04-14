<?php


// Výchozí controller
abstract class Controller
{

	// Pole, jehož indexy jsou poté viditelné v šabloně jako běžné proměnné
    protected $data = array();
	// Název šablony bez přípony
    protected $pohled = "";
	// Hlavička HTML stránky
	protected $hlavicka = array('titulek' => '', 'klicova_slova' => '', 'popis' => '');

	// Ošetří proměnnou pro výpis do HTML stránky
	private function osetri($x = null)
	{
		if (!isset($x))
			return null;
		elseif (is_string($x))
			return htmlspecialchars($x, ENT_QUOTES);
		elseif (is_array($x))
		{
			foreach($x as $k => $v)
			{
				$x[$k] = $this->osetri($v);
			}
			return $x;
		}
		else
			return $x;
	}

	// Vyrenderuje pohled
    public function vypisPohled()
    {
        if ($this->pohled)
        {
            extract($this->osetri($this->data));
			extract($this->data, EXTR_PREFIX_ALL, "");
            require("pohledy/" . $this->pohled . ".phtml");
        }
    }

	// Přidá zprávu pro uživatele
	public function pridejZpravu($zprava, $type = "primary")
	{
		// is the $type is NULL
		if(is_null($type)){

			$header = '<div class="alert alert-primary" role="alert">';
			$footer = '</div>';
			$zprava = $header . "".$zprava. "" . $footer;

		} else {
			
			// set type of message (alert/success etc)
			switch($type){
				case "primary":
					$header = '<div class="alert alert-primary" role="alert">';
					$footer = '</div>';
					$zprava = $header . "".$zprava. "" . $footer;
					break;
				case "error":
					$header = '<div class="alert alert-danger" role="alert">';
					$footer = '</div>';
					$zprava = $header . "".$zprava. "" . $footer;
					break;
				case "success":
					$header = '<div class="alert alert-success" role="alert">';
					$footer = '</div>';
					$zprava = $header . "".$zprava. "" . $footer;
					break;
			}
		}

        

        if (isset($_SESSION['zpravy']))
			$_SESSION['zpravy'][] = $zprava;
		else
			$_SESSION['zpravy'] = array($zprava);




	}

	// Vrátí zprávy pro uživatele
	public static function vratZpravy()
	{
		if (isset($_SESSION['zpravy']))
		{
			$zpravy = $_SESSION['zpravy'];
			unset($_SESSION['zpravy']);
			return $zpravy;
		}
		else
			return array();
	}

	// Přesměruje na dané URL
	public function presmeruj($url)
	{
		header("Location: /$url");
		header("Connection: close");
        exit;
	}

	// Ověří, zda je přihlášený uživatel, případně přesměruje na login
	public function isAdmin($admin = false)
	{
		$spravceUzivatelu = new UsersManager();
		$user = $spravceUzivatelu->getSessionOfCurrentUser();
		if (!$user || ($admin && !$user['admin']))
		{
			$this->pridejZpravu('Nedostatečná oprávnění.');
			$this->presmeruj('login');
		}
	}

	// Hlavní metoda controlleru
    abstract function zpracuj($parametry);

}