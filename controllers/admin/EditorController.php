<?php



// Controller for articles

class Editorcontroller extends Controller
{
    public function zpracuj($parametry)
    {
		// Editor smí používat jen administrátoři
		$this->isAdmin(true);
		// Hlavička stránky
		$this->hlavicka['title'] = 'Editor článků';
		// Vytvoření instance modelu
		$articlesManager = new ArticlesManager();
		// Příprava prázdného článku
		$article = array(
			'id_article' => '',
			'title' => '',
			'content' => '',
			'url' => '',
			'description' => '',
			'key_words' => '',
            'id_article_category' => '',
		);
		// Je odeslán formulář
		if ($_POST)
		{
            log_as_json($_POST);

			// Získání článku z $_POST
			$klice = array('title', 'content', 'url', 'description', 'key_words', 'id_article_category');
			log_as_json($_POST);
            $article = array_intersect_key($_POST, array_flip($klice));
			// Uložení článku do DB
			$articlesManager->saveArticle($_POST['id_article'], $article);
            $type = "success";
			$this->pridejZpravu('Článek byl úspěšně uložen.', $type);
			$this->presmeruj('admin/editor/' . $article['url']);
		}
		// Je zadané URL článku k editaci
		else if (!empty($parametry[0]))
		{
			$nactenyarticle = $articlesManager->getArticle($parametry[0]);
			if ($nactenyarticle)
				$article = $nactenyarticle;
			else
				$this->pridejZpravu('Článek nebyl nalezen');
		}

        $articleCategoryManager = new ArticlesCategoryManager();
        $categories = $articleCategoryManager->getAllArticleCategories();

        $this->data['categories'] = $categories;
		$this->data['article'] = $article;
		$this->pohled = 'admin/edit-article';
    }
}