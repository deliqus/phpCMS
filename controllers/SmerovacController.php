<?php

// Router je speciální typ controlleru, který podle URL adresy zavolá
// správný controller a jím vytvořený pohled vloží do šablony stránky

class Smerovaccontroller extends Controller
{
	// Instance controlleru
	protected $controller;

	// Metoda převede pomlčkovou variantu controlleru na název třídy
	private function pomlckyDoVelbloudiNotace($text)
	{
		$veta = str_replace('-', ' ', $text);
		$veta = ucwords($veta);
		$veta = str_replace(' ', '', $veta);
		return $veta;
	}

	// Naparsuje URL adresu podle lomítek a vrátí pole parametrů
	private function parseURL($url)
	{

        // Naparsuje jednotlivé části URL adresy do asociativního pole
        $naparsovanaURL = parse_url($url);
		// Odstranění počátečního lomítka
		$naparsovanaURL["path"] = ltrim($naparsovanaURL["path"], "/");
		// Odstranění bílých znaků kolem adresy
		$naparsovanaURL["path"] = trim($naparsovanaURL["path"]);
		// Rozbití řetězce podle lomítek
		$rozdelenaCesta = explode("/", $naparsovanaURL["path"]);

		return $rozdelenaCesta;
	}

    // Naparsování URL adresy a vytvoření příslušného controlleru
    public function zpracuj($parametry)
    {
		$naparsovanaURL = $this->parseURL($parametry[0]);

        if (empty($naparsovanaURL[0]))
        {
           $this->presmeruj('article/uvod');

        }


		// controller je 1. parametr URL
        // delete index 0 from the array ($naparsovanaURL)
		//$tridaControlleru = $this->pomlckyDoVelbloudiNotace(array_shift($naparsovanaURL)) . 'Controller';

        if(preg_match("/^\/admin\//", $_SERVER['REQUEST_URI']))
        {
            if (empty($naparsovanaURL[1]) or $naparsovanaURL[1] == "")
            {
                echo "Administace";
                $this->presmeruj('admin/dashboard');

            }
            else
            {
                $naparsovanaURL = array_splice($naparsovanaURL, 1);
                $tridaControlleru = $this->pomlckyDoVelbloudiNotace(array_shift($naparsovanaURL)) . 'Controller';

                if (file_exists('controllers/admin/' . $tridaControlleru . '.php'))
                {
                    $this->controller = new $tridaControlleru;
                }
            }

        }
        else
        {
            $tridaControlleru = $this->pomlckyDoVelbloudiNotace(array_shift($naparsovanaURL)) . 'Controller';

            if (file_exists('controllers/' . $tridaControlleru . '.php'))
            {
                $this->controller = new $tridaControlleru;
            }
            else {

                $this->presmeruj('chyba');
            }
        }




        // call controller and pass cropped parameters from URL
        $this->controller->zpracuj($naparsovanaURL);


		// Nastavení proměnných pro šablonu
		$this->data['title'] = $this->controller->hlavicka['title'];
		$this->data['popis'] = $this->controller->hlavicka['popis'];
		$this->data['klicova_slova'] = $this->controller->hlavicka['klicova_slova'];
		$this->data['zpravy'] = $this->vratZpravy();
		// Nastavení hlavní šablony
		$this->pohled = 'rozlozeni';
    }

}