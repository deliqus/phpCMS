<?php

// Třída poskytuje metody pro správu článků v redakčním systému
class ArticlesCategoryManager
{

	// Vrátí článek z databáze podle jeho URL
	public function getArticleCategory($id)
	{
		return Db::queryOne('
			SELECT `id_article_category`, `name`
			FROM `articles_category`
			WHERE `id_article_category` = ?
		', array($id));
	}

	// Uloží článek do systému. Pokud je ID false, vloží nový, jinak provede editaci.
	public function saveArticleCategory($id, $category)
	{
		if (!$id)
			Db::insert('articles_category', $category);
		else
			Db::update('articles_category', $category, 'WHERE id_article_category = ?', array($id));
	}

	// Vrátí seznam článků v databázi
	public function getAllArticleCategories()
	{
		return Db::queryAll('
			SELECT `id_article_category`, `name`
			FROM `articles_category`
			ORDER BY `id_article_category` DESC
		');
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