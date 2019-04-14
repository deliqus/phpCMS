<?php



// Controller for articles

class ArticlesCategoryEditorcontroller extends Controller
{
    public function zpracuj($parametry)
    {
		// Editor smí používat jen administrátoři
		$this->isAdmin(true);
		// Hlavička stránky
		$this->hlavicka['title'] = 'Editor kategorií';
		// Vytvoření instance modelu
		$categoryManager = new ArticlesCategoryManager();
		// Příprava prázdného článku
		$category = array(
			'id_article_category' => '',
			'name' => '',
		);
		// Je odeslán formulář
		if ($_POST)
		{
			// Získání článku z $_POST
			$klice = array('name');
			$category = array_intersect_key($_POST, array_flip($klice));
			// Save category into DB
			$categoryManager->saveArticleCategory($_POST['id_article_category'], $category);
            $type = "success";
			$this->pridejZpravu('Category has been successfully saved.', $type);
			$this->presmeruj('articlescategoryeditor/' . $_POST['id_article_category']);
		}
		// When app knows ID of category
		else if (!empty($parametry[0]))
		{
			$loadedCategory = $categoryManager->getArticleCategory($parametry[0]);

			if ($loadedCategory){
                $category = $loadedCategory;
            }
			else{
                $this->pridejZpravu('Článek nebyl nalezen');
            }

            $this->data['category'] = $category;
            $this->pohled = 'admin/edit-category';
		}
        else if (!empty($parametry[0]) && $parametry[0]="new")
		{
            $this->pohled = 'admin/new-category';
        }

        else if (empty($parametry[0]))
		{
            $loadedCategories = $categoryManager->getAllArticleCategories();
            print_r($loadedCategories);
            $this->data['categories'] = $loadedCategories;
            $this->pohled = 'admin/categories';

        }

    }
}