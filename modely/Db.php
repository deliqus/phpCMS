<?php



// Wrapper pro snadnější práci s databází s použitím PDO a automatickýmail
// zabezpečením parametrů (proměnných) v dotazech.

class Db {

	// Databázové spojení
    private static $spojeni;

	// Výchozí nastavení ovladače
    private static $nastaveni = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		PDO::ATTR_EMULATE_PREPARES => false,
	);

	// Připojí se k databázi pomocí daných údajů
    public static function pripoj() {
		if (!isset(self::$spojeni)) {

		    self::$spojeni = @new PDO('sqlite:phpCMS.db');

//			self::$spojeni = @new PDO(
//				"mysql:host=$host;dbname=$databaze",
//				$user,
//				$password,
//				self::$nastaveni
//			);
		}
	}

	// Spustí dotaz a vrátí z něj první řádek
    public static function queryOne($dotaz, $parametry = array()) {
		$navrat = self::$spojeni->prepare($dotaz);
		$navrat->execute($parametry);
		return $navrat->fetch();
	}

	// Spustí dotaz a vrátí všechny jeho řádky jako pole asociativních polí
    public static function queryAll($dotaz, $parametry = array()) {
		$navrat = self::$spojeni->prepare($dotaz);
		$navrat->execute($parametry);
		return $navrat->fetchAll();
	}

	// Spustí dotaz a vrátí z něj první sloupec prvního řádku
    public static function dotazSamotny($dotaz, $parametry = array()) {
		$vysledek = self::queryOne($dotaz, $parametry);
		return $vysledek[0];
	}

	// Spustí dotaz a vrátí počet ovlivněných řádků
	public static function query($dotaz, $parametry = array()) {
		$navrat = self::$spojeni->prepare($dotaz);
		$navrat->execute($parametry);
		return $navrat->rowCount();
	}

	// Vloží do tabulky nový řádek jako data z asociativního pole
	public static function insert($tabulka, $parametry = array()) {
		return self::query("INSERT INTO `$tabulka` (`".
		implode('`, `', array_keys($parametry)).
		"`) VALUES (".str_repeat('?,', sizeOf($parametry)-1)."?)",
			array_values($parametry));
	}

	// Změní řádek v tabulce tak, aby obsahoval data z asociativního pole
	public static function update($tabulka, $hodnoty = array(), $podminka, $parametry = array()) {
		return self::query("UPDATE `$tabulka` SET `".
		implode('` = ?, `', array_keys($hodnoty)).
		"` = ? " . $podminka,
		array_merge(array_values($hodnoty), $parametry));
	}

	// Vrací ID posledně vloženého záznamu
	public static function getLastId()
	{
		return self::$spojeni->lastInsertId();
	}
}