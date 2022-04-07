<?php
/*
Plugin Name: QPC Delivery
Plugin URI: https://quartopianocomunicazione.it
Description: Gestione Menù con API Delivery
Version: 1.0.0
Author: Quartopianocomunicazione
Author URI: https://quartopianocomunicazione.it
*/

include("api/DeliveryAPI.php");
include("api/MailchimpAPI.php");

/**
 * Currently plugin version.
 */
define('QPC_DELIVERY_VERSION', '1.0.0');

define('QPC_DELIVERY_BASEURL', 'https://ristorantescudiero.finedelivery.it/storage/app/public/');
define('QPC_DELIVERY_URL', 'https://ristorantescudiero.finedelivery.it');
//define('QPC_DELIVERY_BASEURL', 'https://qpc.qpcdev.it/storage/app/public/');
//define('QPC_DELIVERY_URL', 'https://qpc.qpcdev.it/');
//
class Qpc_Delivery
{
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * @since   1.0.0
	 * @access  private
	 * @var     DeliveryAPI
	 */
	private static $_delivery;
	/**
	 * Static property to hold our singleton instance
	 *
	 * @since   1.0.0
	 * @access  static
	 * @var     bool
	 */
	static $instance = false;
	/**
	 * Static property for multilanguage
	 */
	private static $lang = 'it';
	private static $fd_version = 'id'; //oppure 'slug'

	public $cart;


	public function __construct()
	{

		if (defined('QPC_DELIVERY_VERSION')) {
			$this->version = QPC_DELIVERY_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->plugin_name = 'qpc-delivery';
		$this->_mailchimp = new MailchimpAPI();
	}

	public static function register()
    {
		if (!self::$instance){
			self::$instance = true;
			$plugin = new self();
			self::detectLanguage();

			self::$_delivery   = new DeliveryAPI();

	//if (self::instanceOfMapTranslation()){ /* it's an Ajax call */
			//	self::$mapTranslations = new mapTranslations();
		//	}

			add_action('init', array($plugin, 'product_api_rewrite_tag'));
			add_action('init', array($plugin, 'category_api_rewrite_tag'));

			add_filter( 'generate_rewrite_rules', array($plugin, 'finedelivery_api_rewrite_rules' ));
			add_filter( 'query_vars', array($plugin, 'finedelivery_api_rewrite_filter' ));

			flush_rewrite_rules();

			//	self::mapFDtranslations();

			add_action('wp_ajax_is_logged', array($plugin, 'is_logged'));
			add_action('wp_ajax_nopriv_is_logged', array($plugin, 'is_logged'));
			add_action('wp_ajax_login', array($plugin, 'login'));
			add_action('wp_ajax_nopriv_login', array($plugin, 'login'));
			add_action('wp_ajax_recover_password', array($plugin, 'recover_password'));
			add_action('wp_ajax_nopriv_recover_password', array($plugin, 'recover_password'));
			add_action('wp_ajax_change_password', array($plugin, 'change_password'));
			add_action('wp_ajax_nopriv_change_password', array($plugin, 'change_password'));
			add_action('wp_ajax_register_user', array($plugin, 'register_user'));
			add_action('wp_ajax_nopriv_register_user', array($plugin, 'register_user'));
			add_action('wp_ajax_checkout', array($plugin, 'checkout'));
			add_action('wp_ajax_nopriv_get_order', array($plugin, 'get_order'));
			add_action('wp_ajax_get_order', array($plugin, 'get_order'));
			add_action('wp_ajax_nopriv_checkout', array($plugin, 'checkout'));
			add_action('wp_ajax_get_product', array($plugin, 'get_product'));
			add_action('wp_ajax_nopriv_get_product', array($plugin, 'get_product'));
			add_action('wp_ajax_add_to_cart', array($plugin, 'add_to_cart'));
			add_action('wp_ajax_nopriv_add_to_cart', array($plugin, 'add_to_cart'));
			add_action('wp_ajax_remove_from_cart', array($plugin, 'remove_from_cart'));
			add_action('wp_ajax_nopriv_remove_from_cart', array($plugin, 'remove_from_cart'));
			add_action('wp_ajax_getCart', array($plugin, 'getCart'));
			add_action('wp_ajax_nopriv_getCart', array($plugin, 'getCart'));
			add_action('wp_ajax_forceRightPermalinkInWPMLSwitcher', array($plugin, 'forceRightPermalinkInWPMLSwitcher'));
			add_action('wp_ajax_nopriv_forceRightPermalinkInWPMLSwitcher', array($plugin, 'forceRightPermalinkInWPMLSwitcher'));
			add_action('wp_enqueue_scripts', array($plugin, 'ja_global_enqueues'));
		}

	}

	public static function ja_global_enqueues()
	{
		wp_enqueue_script('delivery', plugin_dir_url(__FILE__) . 'js/delivery.js', array('global'), '1.0.0', true);
		wp_enqueue_style('delivery', plugin_dir_url(__FILE__) . 'css/bootstrap-grid.min.css', array(), rand(111, 9999));

		wp_localize_script(
			'delivery',
			'delivery',
			array(
				'ajax' => admin_url('admin-ajax.php'),
				'gift_label' => __('Regalo', 'mos-theme'),
				'persons_label' => __('Voucher', 'mos-theme'),
				'total_label' => __('Totale', 'mos-theme'),
				'subtotal_label' => __('Subtotale', 'mos-theme'),
				'error_status' => __("Qualcosa è andato storto. Riprova.", 'mos-theme'),
				'paypal_status' => __("Stai per essere reindirizzato sul sito di PayPal...", 'mos-theme'),
				'register_ok_status' => __("Grazie per esserti registrato!", 'mos-theme'),
				'login_ok_status' => __("Accesso effettuato correttamente", 'mos-theme'),
				'recover_ok_status' => __("Controlla la tua e-mail e clicca sul pulsante per reimpostare la password.", 'mos-theme')
			)
		);
	}


	
	/**
	 * Get all Delivery products
	 *
	 * @return mixed|string
	 */
	public static function getProducts()
	{
		//print_r(self::$_delivery->getProducts());

		return (self::$_delivery->getProducts());
	}

	/**
	 * Get all Delivery $cat_slug's products
	 *
	 * @param $cat_slug
	 *
	 * @return array
	 */
	public static function getCategoryProducts($cat_slug)
	{
		$return   = [];
		$products = self::getProducts();
		$cat_slug = urlencode($cat_slug);
		foreach ($products['products'] as $product) {
			foreach ($product['categories'] as $category) {
				$category_slug = json_decode($category['slug'], true);

				if (( /*$category['slug'] == $cat_slug || */ self::getTranslatedValue($category, 'slug') == $cat_slug) && $product['status']) {
					$return[] = $product;
				}
			}
		}

		return $return;
	}

	/**
	 * Get Delivery single product by slug
	 *
	 *
	 * @param $product_slug
	 *
	 * @return array
	 */
	public static function getProduct($product_slug)
	{
	//	var_dump(self::$_delivery->getProduct(self::$lang, rawurlencode($product_slug)));
	
		return self::$_delivery->getProduct(self::$lang, rawurlencode($product_slug))['product'];

		//
	}

	/**
	 * Get all Delivery categories
	 *
	 * @return array
	 */
	public static function getCategories($addDegustazioni = true)
	{
		$return     = [];
		$categories = self::$_delivery->getCategories();


		//escludo Root e Degustazioni
		foreach ($categories as $category) {
			if ($category['parent_id'] == 1 && $category['menu'] && $category['featured'] == true) {
				$category_slug = json_decode($category['slug'], true);
				if ($addDegustazioni) {
					if ( $category_slug['it'] !== 'degustazioni' ){

						$return[] = $category;
					}
				//	if ($category['slug'] != 'degustazioni' && $category['slug'] != 'business-lunch') {
				/*	if ($category['slug'] != 'degustazioni') {
						$return[] = $category;
					}*/	
				} else {
					$return[] = $category;
				}
			}
		}
		if ($addDegustazioni) //Aggiungo le degustazioni singole
		{
			return array_merge($return, self::getCategoryProducts('degustazioni'));
			//return array_merge($return, self::getCategoryProducts('degustazioni'), self::getCategoryProducts('business-lunch'));
		}

		return $return;
		//return ( self::$_delivery->getCategories() );
	}

	public static function getCategory($cat_slug)
	{
	/*	if (self::$fd_version == 'id'){
			$cat_slug_ = (self::$mapTranslations->getCategorySlugTranslation(ICL_LANGUAGE_CODE, $cat_slug));
			$returnCategory = (self::$_delivery->getCategory($cat_slug_));
			if ($returnCategory) return $returnCategory;
		}*/
		return self::$_delivery->getCategory(self::$lang, $cat_slug);
}

	public static function getVouchers()
	{
		$return = [];

		$data     = self::getProducts();

		$products = $data['products'];

		foreach ($products as $i => $product) {

			if ($product['voucher']) {
				$order = ($product['order']=='' ? $i : (int) $product['order']);
				$return[$order] = $product;
			}
		}

		ksort($return);
		return $return;
	}

	/**
	 * Get Delivery cart
	 *
	 * @return mixed|string
	 */
	public static function getCart()
	{
		global $wpdb;

		if (isset($_POST['deliverySession']))
			echo json_encode(self::$_delivery->getCart($_POST['deliverySession']));
		wp_die();
	}

	/**
	 * Delivery Register (AJAX)
	 *
	 */
	public  function register_user()
	{
		global $wpdb;
		$register_response = self::$_delivery->register($_POST['deliverySession'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'], $_POST['address'], $_POST['city'], $_POST['country'], $_POST['post_code'], $_POST['phone_number']);

		if ($register_response) {

			$login_response = self::$_delivery->login($_POST['deliverySession'], $_POST['email'], $_POST['password']);
			$this->_mailchimp->addSubscriber($_POST['first_name'], $_POST['last_name'], $_POST['email']);
			echo $login_response['api_token'];

		} else {

			echo 0;

		}

		wp_die();
	}

	/**
	 * Delivery Login (AJAX)
	 *
	 */
	public  function login()
	{
		global $wpdb;
		echo json_encode(self::$_delivery->login($_POST['deliverySession'], $_POST['email'], $_POST['password']));
		//	echo json_encode(self::$_delivery->user($api_token));
		wp_die();
	}
	/**
	 * Delivery Recover Password (AJAX)
	 *
	 */
	public function recover_password()
	{
		global $wpdb;
		echo json_encode(self::$_delivery->recoverPassword($_POST['email'], $_POST['deliverySession']));
		wp_die();
	}
	/**
	 * Delivery Change Password (AJAX)
	 *
	 */
	public function change_password()
	{
		global $wpdb;
		echo json_encode(self::$_delivery->changePassword($_POST['email'], $_POST['password'], $_POST['password_confirmation'], $_POST['token']));
		wp_die();
	}
	/**
	 * Check if is logged in Delivery (AJAX)
	 *
	 */
	public function is_logged()
	{
		global $wpdb;
		echo json_encode(self::$_delivery->user($_POST['apiToken']));
		wp_die();
	}

	/**
	 * Delivery Checkout (AJAX)
	 *
	 */
	public function checkout()
	{
		global $wpdb;
		$checkout_response = self::$_delivery->checkout($_POST['deliverySession'], $_POST['first_name'], $_POST['last_name'], $_POST['address'], $_POST['city'], $_POST['country'], $_POST['post_code'], $_POST['phone_number'], $_POST['notes'], $_POST['api_token'], $_POST['voucher_name'], $_POST['voucher_email']);
		echo json_encode($checkout_response);

		wp_die();
	}

	/**
	 * Get Delivery Product (AJAX)
	 *
	 */
	public function get_product()
	{
		global $wpdb;
		//	echo json_encode( $this->_delivery->getProductById( $_POST['productId'] ) );
		echo json_encode(self::$_delivery->getProduct($_POST['productId'], self::$fd_version));
		wp_die();
	}

	/**
	 * Get Delivery Order by ID (AJAX)
	 */
	public function get_order()
	{
		global $wpdb;
		echo json_encode(self::$_delivery->getOrderById($_POST['orderId'], $_POST['deliverySession']));
		wp_die();
	}
	/**
	 * Aggiungi prodotto al carrello Delivery (AJAX)
	 */
	public function add_to_cart()
	{
		global $wpdb;

		$this->detectLanguage();
		error_log(json_encode($_POST, true));
		self::$_delivery->addToCart($_POST['deliverySession'], $_POST['price'], $_POST['productId'], $_POST['calice'], $_POST['attributePrice'], $_POST['attributeId'], $_POST['attributeId'], self::$lang, $_POST['qty']);
		echo self::$_delivery->getDeliverySession();
		wp_die();
	}

	/**
	 * Rimuovi prodotto dal carrello Delivery (AJAX)
	 */
	public function remove_from_cart()
	{
		global $wpdb;
		$response = self::$_delivery->removeFromCart($_POST['deliverySession'], $_POST['productId']);
	//	echo $response;
		echo self::$_delivery->getDeliverySession();
		wp_die();
	}

	/**
	 * Returns string
	 *
	 * @param $attributes
	 * @param string $sep
	 *
	 * @return string
	 */
	public static function getAttributeValuesHTML($attributes, $sep = ' | ')
	{
		$return = [];
		if (count($attributes)>0)
			foreach ($attributes as $attribute) :
				$return[] = self::getAttributeTitle($attribute) . " " . $attribute['quantity'] . " " . $attribute['value'] . " <strong>" . number_format($attribute["price"], 0, ",", "") . " € </strong>";
			//	$return[] = $attribute['title'] . " " . $attribute['quantity'] . " " . $attribute['value'] . " <strong>" . number_format($attribute["price"], 0, ",", "") . " € </strong>";
			endforeach;

		return implode($sep, $return);
	}

	/**
	 *
	 * Get URL for Delivery category or product
	 *
	 * @param $data
	 *
	 * @return string
	 */
	public static function getURL($data)
	{
		/*
		$url[] = site_url();
		$url[] = 'menu';
*/
		$url[] = substr(get_permalink(self::get_pages_by_template_filename()->ID), 0,  -1);
		if (isset($data['categories'])) {
			$url[] = self::getTranslatedValue($data['categories'][0], 'slug'); //$data['categories'][0]['slug'];
		}
		if (isset($data['category'])) {
			$url[] = self::getTranslatedValue($data['category'], 'slug'); //$data['category']['slug'];
		} else {
			$url[] = self::getTranslatedValue($data, 'slug');
		}

		return implode("/", $url);
	}

	/**
	 * Add tag for Delivery products
	 */
	public static function product_api_rewrite_tag()
	{
		add_rewrite_tag('%product_slug%', '([^&]+)');
	}

	/**
	 * Add tag for Delivery products
	 */
	public static function category_api_rewrite_tag()
	{
		add_rewrite_tag('%category_slug%', '([^&]+)');
	}

	private static function get_pages_by_template_filename($page_template_filename = "template-menu.php")
	{
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $page_template_filename
		));

		if ($pages)
			return $pages[0]; //solo la prima, altrimenti è un casino!

		return false;
	}

	/**
	 * Add rewrite rule for Delivery product page
	 */
	public static function finedelivery_api_rewrite_rules( $wp_rewrite ) {


		if ($page_template_menu = self::get_pages_by_template_filename()) {

			$languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');

			if (!empty($languages)) {
				foreach ($languages as $l) {
					if ($l['active']) {
						$translatedPageID =  apply_filters('wpml_object_id',  $page_template_menu->ID, 'page', FALSE, $l['language_code']);
						$translatedPage = get_post($translatedPageID);
						$lang_prefix = (($l['code'] == ICL_LANGUAGE_CODE) ? '' : $l['code'].'/' );
						$fd_rules = array(
							 $lang_prefix.$translatedPage->post_name . '/(.+)/(.+)/?' => 'index.php??pagename=' . $translatedPage->post_name . '&category_slug=$matches[1]&product_slug=$matches[2]',
							 $lang_prefix.$translatedPage->post_name . '/(.+)/?' => 'index.php??pagename=' . $translatedPage->post_name . '&category_slug=$matches[1]'
						);
					}
				}
			}
		}

		$wp_rewrite->rules = $fd_rules + $wp_rewrite->rules;
	}
	public static function finedelivery_api_rewrite_filter( $query_vars ){
		$query_vars[] = 'category_slug';
		$query_vars[] = 'product_slug';
		return $query_vars;
	}
	/**
	 * Prepare an array for category gallery, with images taken for all its single products
	 *
	 * @param $products
	 *
	 * @return array
	 */
	public static function prepareCategoryGalleryData($products, $category)
	{
		$return = [];
		foreach ($products as $product) :
			if (isset($product['images']) && $product['status']) {
				foreach ($product['images'] as $image) {
					if ($image['resize_1']) {
						$return[] = $image["resize_1"];
					}
				}
			}
		endforeach;
		if (count($category['category']['images']) > 0) {
			foreach ($category['category']['images'] as $image) {
				if ($image['resize_1']) {
					$return[] = $image["resize_1"];
				}
			}
		}

		return $return;
	}

	public static function getImageForMenuListing($product)
	{
		if (count($product['images']) > 0) {
			return QPC_DELIVERY_URL . $product['images'][0]['resize_1'];
		}

		return null;
	}

	/**
	 * Elenco categorie correlate
	 */
	public static function getRelatedCategories($category_slug)
	{
		$categories = self::getCategories(false);
		$return     = [];
		foreach ($categories as $i => $category) {
			if ($category['slug'] != $category_slug) {
				$return[] = [
					"url"  => self::getURL($category),
					"name" => $category['name']
				];
			}
		}

		return $return;
	}

	/**
	 * Elenco prodotti correlati
	 */
	public static function getRelatedProducts($category_slug, $product_slug)
	{
		$products = self::getCategoryProducts($category_slug);
		$return   = [];
		foreach ($products as $i => $product) {
			if ($product['slug'] != $product_slug && $product['status']) {
				$return[] = [
					"url"  => self::getURL($product),
					"name" => $product['name']
				];
			}
		}

		return $return;
	}

	//Breadcrumb
	public static function getBreadcrumb($category, $product, $sep = "&raquo;")
	{
		global $wp_query;
		$return = [];
		//base
		$urls[0] = ["url" => site_url(), "name" => "Home"];

		//pagina menù
		$menu_page_id = $wp_query->queried_object_id;
		if (defined('ICL_LANGUAGE_CODE')){
			$menu_url = apply_filters('wpml_permalink', get_permalink($menu_page_id), ICL_LANGUAGE_CODE );
		}else{
			$menu_url = get_permalink();
		}
		$urls[1] = ["url" => $menu_url, "name" => get_the_title($menu_page_id)];
		// categoria
		if ($category) {
			if ($category!==null && $category['category']['featured'])
				$urls[2]  = ["url" => self::getURL($category), "name" => self::getCategoryName($category['category'])];
		}
		if ($product) {
			$urls[3] = ["url" => self::getURL($product), "name" => self::getProductName($product)];
		}

		foreach ($urls as $url) {
			$return[] = "<li><a href='{$url["url"]}'>{$url["name"]}</a></li>";
		}

		return '<div class="delivery-breadcrumbs"><ul>' . implode($sep, $return) . '</ul></div>';
	}


	/**
	 * Sceglie l'immagine da mostrare nel blocco "gallery" (v. template-parts/blocchi/blocco-gallery.php)
	 * Restituisce la prima immagine della gallery della categoria o del prodotto, se c'è.
	 * Altrimenti prende la prima immagine dalle gallery dei prodotti
	 *
	 * @param $data
	 */
	public static function getImageForBloccoGallery($data)
	{
		if (count($data['images']) > 0) {
			return QPC_DELIVERY_URL . $data["images"][0]["resize_1"];
		}
	//	var_dump(self::getTranslatedValue($data, 'slug'));
		$products = self::getCategoryProducts( self::getTranslatedValue($data, 'slug') );
		if (count($products) > 0) {
			$gallery = self::prepareCategoryGalleryData( $products, $data );
			if (count($gallery) > 0) {
				return QPC_DELIVERY_URL . $gallery[0];
			}
		}

		return null;
	}

	//UTILITY MULTILINGUA

	private static function getTranslatedValue($variable, $field)
	{
		$jsonDecode = json_decode($variable[$field], true);
		if ($jsonDecode !== null)
			return ($jsonDecode[self::$lang]);

		return $variable[$field];
	}

	private static function detectLanguage()
	{
		if (defined('ICL_LANGUAGE_CODE')) {
			self::$lang = ICL_LANGUAGE_CODE;
		} else {
			self::$lang = 'it';
		}
	}
	public static function getProductSlug($product)
	{
		return self::getTranslatedValue($product, 'slug');
	}
	public static function getProductName($product)
	{
		return self::getTranslatedValue($product, 'name');
	}
	public static function getProductDescription($product)
	{
		return self::getTranslatedValue($product, 'description');
	}

	public static function getAttributeTitle($attribute)
	{
		return self::getTranslatedValue($attribute, 'title');
	}

	public static function getCategoryName($category)
	{
		return self::getTranslatedValue($category, 'name');
	}


	// CAMBIA GLI URL DELLO SWITCHER DELLA LINGUA DI WPML (AJAX)
	public function forceRightPermalinkInWPMLSwitcher(){
		global $wpdb;

		global $sitepress;

		$return['wpml-ls-item-'.ICL_LANGUAGE_CODE] = $_POST['url'];
		if (defined('ICL_LANGUAGE_CODE')){
			//se la pagina è template-menu.php
			$page_template_menu = self::get_pages_by_template_filename();
			$url_page_menu = apply_filters('wpml_permalink', get_permalink($page_template_menu), ICL_LANGUAGE_CODE );
			if ($page_template_menu && strpos($_POST['url'], $url_page_menu)!==false){
		//		if (self::$mapTranslations === null) self::$mapTranslations = new mapTranslations();
				//tolgo l'url della pagina menu dal $_post['url'] per estrarre gli slug della categoria e del prodotto
				$slugs = explode("/", str_replace($url_page_menu, "", $_POST['url']));
				$languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
				//
				if (!empty($languages)) {
					foreach ($languages as $l) {
						if ( $l['code'] !== ICL_LANGUAGE_CODE ){
							$translatedPageID = apply_filters('wpml_object_id',  $page_template_menu->ID, 'page', FALSE, $l['code']);
							$translatedUrl = apply_filters('wpml_permalink', get_permalink($translatedPageID) , $l['code'] );

						//	$return['wpml-ls-item-'.$l['code']] = $translatedUrl . self::$mapTranslations->getCategoryTranslationFromTo(ICL_LANGUAGE_CODE, $l['code'], $slugs[0]) .'/'. self::$mapTranslations->getProductTranslationFromTo(ICL_LANGUAGE_CODE, $l['code'], $slugs[1]);
						}
					}
				}
			}
			echo json_encode($return);
			die();
		}
	}

}

QPC_Delivery::register();
