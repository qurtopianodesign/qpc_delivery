<?php

class mapTranslations{

	private static $categoriesMap;
	private static $productsMap;
	private static $categoriesMapByID;
	private static $productsMapByID;

    private static $_delivery;
 
    private static $alreadyMapped = 0; 

    public function __construct()
    {
        
//		$dbts = debug_backtrace();
  //      foreach ($dbts as $i => $dbt)	error_log("mt $i :: ".$dbt["function"]." ".$dbt["file"].":".$dbt["line"]);
        
        self::mapFDTranslations();
    }

    private static function mapFDTranslations(){

        self::$_delivery = new DeliveryAPI();
 //       error_log("exec mapFDTranslations ".self::$alreadyMapped);
        if (self::$alreadyMapped<1){
            $categories =  self::$_delivery->getCategories();
            $products = self::$_delivery->getProducts();
			foreach ($categories as $category){
				$categoryData = json_decode($category['slug']);
				if ($categoryData!==null){ //se lo slug della categoria è in json - multilingua 
					foreach ($categoryData as $lang => $categoryTranslation){
						self::$categoriesMap[$lang][$categoryTranslation] = $category['id'];
						self::$categoriesMapByID[$category['id']][$lang] = $categoryTranslation;
					}
				} else { //lo slug è una stringa "semplice", come nella vecchia versione - no multilingua

					self::$categoriesMap['it'][$category['slug']] = $category['id'];
					self::$categoriesMapByID[$category['id']]['it'] = $category['slug'];
				}
			}
			foreach ($products['products'] as $product){
				$productData = json_decode($product['slug']);
				if ($productData!==null){ //se lo slug della categoria è in json - multilingua 
					foreach ($productData as $lang => $productTranslation){
						self::$productsMap[$lang][$productTranslation] = $product['id'];
						self::$productsMapByID[$product['id']][$lang] = $productTranslation;
					}
				} else { //lo slug è una stringa "semplice", come nella vecchia versione - no multilingua
					self::$productsMap['it'][$product['slug']] = $product['id'];
					self::$productsMapByID[$product['id']]['it'] = $product['slug'];
				}
			}
            self::$alreadyMapped = 1;}

    }

    public function getCategorySlugTranslation($lang, $category_slug){
		return self::$categoriesMap[$lang][$category_slug];
	}
	public function getProductSlugTranslation($lang, $product_slug){
		return self::$productsMap[$lang][$product_slug];
	}

	public function getCategoryTranslationFromTo($langFrom, $langTo, $category_slug){
		$cat_id = $this->getCategorySlugTranslation($langFrom, $category_slug);
		return self::$categoriesMapByID[$cat_id][$langTo];
	}
	public function getProductTranslationFromTo($langFrom, $langTo, $product_slug){
		$product_id = $this->getProductSlugTranslation($langFrom, $product_slug);
		return self::$productsMapByID[$product_id][$langTo];
	}
}