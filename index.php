<?php

session_start();

// Nastavení interního kódování pro funkce pro práci s řetězci
mb_internal_encoding("UTF-8");

require("controllers/Controller.php");
require("controllers/SmerovacController.php");
require("help/helper.php");

// Callback pro automatické načítání tříd controllerů a modelů
function autoloadFunkce($trida)
{

    if(preg_match("/^\/admin\//", $_SERVER['REQUEST_URI']))
    {

        // Končí název třídy řetězcem "Controller" ?
        if (preg_match('/Controller$/', $trida)){
            require("controllers/admin/" . $trida . ".php");
        }
        else
            require("modely/" . $trida . ".php");
    }
    else{
        // Končí název třídy řetězcem "Controller" ?
        if (preg_match('/Controller$/', $trida))
            require("controllers/" . $trida . ".php");
        else
            require("modely/" . $trida . ".php");
    }
}

// Registrace callbacku (Pod starým PHP 5.2 je nutné nahradit fcí __autoload())
spl_autoload_register("autoloadFunkce");

// Připojení k databázi
//Db::pripoj("localhost", "root", "", "cms");
Db::pripoj();

//Db::pripoj("wm40.wedos.net", "a49641_phpcms", "a49641_trashPASS", "d49641_phpcms");


//Uživatel pro správu databáze (má plná přístupová práva):
//Jméno: a49641_phpcms
//Heslo: rQBeTesg

//Uživatel s omezenými právy pro použití ve vašich skriptech:
//Jméno: w49641_phpcms
//Heslo: u9dTsEEs

// a49641_trashPASS






// Vytvoření routeru a zpracování parametrů od uživatele z URL

$smerovac = new SmerovacController();
$smerovac->zpracuj(array($_SERVER['REQUEST_URI']));
// Vyrenderování šablony
$smerovac->vypisPohled();

