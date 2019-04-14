<?php


// Controller pro výpis článků
class Articlescontroller extends Controller
{
    public function zpracuj($parametry)
    {

        // Vytvoření instance modelu, který nám umožní pracovat s články
		$articlesManager = new ArticlesManager();
		$spravceUzivatelu = new UsersManager();
		$user = $spravceUzivatelu->getSessionOfCurrentUser();
		$this->data['admin'] = $user && $user['admin'];

        $url = parse_url($_SERVER['REQUEST_URI']);
        parse_str($url["query"], $queryParameters);

        if (empty($parametry[0]))
        {
            var_dump($queryParameters);

            if(empty($queryParameters)){

                // Parameters not found in URL, so query to all articles
                $articles = $articlesManager->getArticles();
            }
            else
            {
                // Parameters found in URL, so query to articles according parameteres
                try
                {
                    $articles = $articlesManager->getFilteredArticles($queryParameters);
                }
                catch (Exception $e)
                {
                    $message = $e->getMessage();
                }
            }
                if($message)
                {
                    $this->pridejZpravu($message);
                }
                $categoriesManager = new ArticlesCategoryManager();
                $categories = $categoriesManager->getAllArticleCategories();
                $this->data['categories'] = $categories;
                $this->data['articles'] = $articles;
                $this->pohled = 'articles';


        }
        else
        {
            // Je zadáno URL článku ke smazání
		    if (!empty($parametry[1]) && $parametry[1] == 'odstranit')
		    {
			    $this->isAdmin(true);
			    $articlesManager->deleteArticle($parametry[0]);
			    $this->pridejZpravu('Článek byl úspěšně odstraněn');
			    $this->presmeruj('article');
		    }
            // Detail of article
            else
            {
                // Získání článku podle URL

                $article = $articlesManager->getArticle($parametry[0]);
			    // Pokud nebyl článek s danou URL nalezen, přesměrujeme na Chybacontroller
			    if (!$article)
				    $this->presmeruj('chyba');

			    // Hlavička stránky
			    $this->hlavicka = array(
				    'title' => $article['title'],
				    'key_words' => $article['key_words'],
				    'description' => $article['description'],
			    );

			    // Naplnění proměnných pro šablonu
			    $this->data['title'] = $article['title'];
			    $this->data['content'] = $article['content'];

			    // Nastavení šablony
			    $this->pohled = 'article';
            }
        }
    }
}