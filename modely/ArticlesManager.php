<?php

// Třída poskytuje metody pro správu článků v redakčním systému
class ArticlesManager
{

	// Vrátí článek z databáze podle jeho URL
	public function getArticle($url)
	{

        $article = Db::queryOne('
			SELECT *
			FROM `articles`
			WHERE `url` = ?
		', array($url));


		return $article;
	}

	// Uloží článek do systému. Pokud je ID false, vloží nový, jinak provede editaci.
	public function saveArticle($id, $article)
	{
		if (!$id)
			Db::insert('articles', $article);
		else
			Db::update('articles', $article, 'WHERE id_article = ?', array($id));
	}

	// Vrátí seznam článků v databázi
	public function getArticles()
	{
		return Db::queryAll('
			SELECT `id_article`, `title`, `url`, `description`
			FROM `articles`
			ORDER BY `id_article` DESC
		');
	}



    // Vrátí seznam článků v databázi
	public function getFilteredArticles($parameters)
	{
        $count = 0;
        $string = "";
        foreach($parameters as $key => $parameter)
        {
            $count++;
            $string .= ' ' . $key . '=' . $parameter;
            if($count < count($parameters))
            {
                $string .= ' AND ';
            }
        }
        $condition = $string;

		$articles = Db::queryAll('
			SELECT *
			FROM `articles`
            WHERE '.$condition.'
			ORDER BY `id_article` DESC
		');

        if(!$articles){
            throw new Exception("No articles found.");
        }

        return $articles;
	}

	// Delete article
	public function deleteArticle($url)
	{
		Db::query('
			DELETE FROM articles
			WHERE url = ?
		', array($url));
	}

}