<?php

class Kontaktcontroller extends Controller
{
	public function zpracuj($parametry)
	{
		$this->hlavicka = array(
			'titulek' => 'Kontaktní formulář',
			'klicova_slova' => 'kontakt, email, formulář',
			'popis' => 'Kontaktní formulář našeho webu.'
		);

		if ($_POST)
		{
			try
			{
				$odesilacEmailu = new OdesilacEmailu();

				$to = "m.semrad9@seznam.cz";
				$subject = "Email z webu";
				$message = $_POST['message'];
				$from = $_POST['email'];

				$odesilacEmailu->odesli($to, $subject, $message, $from);
				$this->pridejZpravu('Email byl úspěšně odeslán.');
				$this->presmeruj('kontakt');
			}
			catch (ChybaUzivatele $chyba)
			{
				$this->pridejZpravu($chyba->getMessage());
			}
		}

		$this->pohled = 'kontakt';
    }
}