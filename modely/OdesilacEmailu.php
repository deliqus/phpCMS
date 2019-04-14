<?php

// Pomocná třída, poskytující metody pro odeslání emailu
class OdesilacEmailu
{

	// Odešle email jako HTML, lze tedy používat základní HTML tagy a nové
	// řádky je třeba psát jako <br /> nebo používat odstavce. Kódování je
	// odladěno pro UTF-8.
	public function odesli($to, $subject, $message, $from)
	{
		$hlavicka = "From: " . $from;
		$hlavicka .= "\nMIME-Version: 1.0\n";
		$hlavicka .= "Content-Type: text/html; charset=\"utf-8\"\n";
		if (!mb_send_mail($to, $subject, $message, $hlavicka))
			throw new ChybaUzivatele('Email se nepodařilo odeslat.');
	}


}