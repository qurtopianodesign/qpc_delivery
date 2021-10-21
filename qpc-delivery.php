<?php
/*
Plugin Name: QPC Delivery
Plugin URI: https://quartopianocomunicazione.it
Description: Gestione Menù con API Delivery
Version: 1.0.0
Author: Quartopianocomunicazione
Author URI: https://quartopianocomunicazione.it
*/

include( "api/DeliveryAPI.php" );
include( "api/MailchimpAPI.php" );

/**
 * Currently plugin version.
 */
define( 'QPC_DELIVERY_VERSION', '1.0.0' );
define( 'QPC_DELIVERY_BASEURL', 'https://qpc.qpcdev.it/storage/app/public/' );
define( 'QPC_DELIVERY_URL', 'https://qpc.qpcdev.it/' );

class Qpc_Delivery {
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
	private $_delivery;
	/**
	 * Static property to hold our singleton instance
	 *
	 * @since   1.0.0
	 * @access  static
	 * @var     bool
	 */
	static $instance = false;

	public $cart;

	public function __construct() {

		if ( defined( 'QPC_DELIVERY_VERSION' ) ) {
			$this->version = QPC_DELIVERY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'qpc-delivery';
		$this->_delivery   = new DeliveryAPI();
		$this->_mailchimp = new MailchimpAPI();

		add_action( 'init', array( $this, 'product_api_rewrite_tag' ) );
		add_action( 'init', array( $this, 'product_api_rewrite_rule' ) );
		add_action( 'init', array( $this, 'category_api_rewrite_tag' ) );
		add_action( 'init', array( $this, 'category_api_rewrite_rule' ) );

		add_action( 'wp_ajax_is_logged', array( $this, 'is_logged' ) );
		add_action( 'wp_ajax_nopriv_is_logged', array( $this, 'is_logged' ) );
		add_action( 'wp_ajax_login', array( $this, 'login' ) );
		add_action( 'wp_ajax_nopriv_login', array( $this, 'login' ) );
		add_action( 'wp_ajax_recover_password', array( $this, 'recover_password' ) );
		add_action( 'wp_ajax_nopriv_recover_password', array( $this, 'recover_password' ) );
		add_action( 'wp_ajax_change_password', array( $this, 'change_password' ) );
		add_action( 'wp_ajax_nopriv_change_password', array( $this, 'change_password' ) );
		add_action( 'wp_ajax_register', array( $this, 'register' ) );
		add_action( 'wp_ajax_nopriv_register', array( $this, 'register' ) );
		add_action( 'wp_ajax_checkout', array( $this, 'checkout' ) );
		add_action( 'wp_ajax_nopriv_get_order', array( $this, 'get_order' ) );
		add_action( 'wp_ajax_get_order', array( $this, 'get_order' ) );
		add_action( 'wp_ajax_nopriv_checkout', array( $this, 'checkout' ) );
		add_action( 'wp_ajax_get_product', array( $this, 'get_product' ) );
		add_action( 'wp_ajax_nopriv_get_product', array( $this, 'get_product' ) );
		add_action( 'wp_ajax_add_to_cart', array( $this, 'add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_add_to_cart', array( $this, 'add_to_cart' ) );
		add_action( 'wp_ajax_remove_from_cart', array( $this, 'remove_from_cart' ) );
		add_action( 'wp_ajax_nopriv_remove_from_cart', array( $this, 'remove_from_cart' ) );
		add_action( 'wp_ajax_getCart', array( $this, 'getCart' ) );
		add_action( 'wp_ajax_nopriv_getCart', array( $this, 'getCart' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'ja_global_enqueues' ) );
	}

	public function ja_global_enqueues() {
		wp_enqueue_script( 'delivery', plugin_dir_url( __FILE__ ) . 'js/delivery.js', array( 'global' ), '1.0.0', true );
		wp_enqueue_style( 'delivery', plugin_dir_url( __FILE__ ) . 'css/bootstrap-grid.min.css', array(  ), rand(111, 9999));

		wp_localize_script( 'delivery', 'delivery',
			array(
				'ajax' => admin_url( 'admin-ajax.php' ),
				'gift_label' => __('Regalo', 'mos-theme'),
				'persons_label' => __('Voucher', 'mos-theme'),
				'total_label' => __('Totale', 'mos-theme'),
				'subtotal_label' => __('Subtotale', 'mos-theme'),
				'error_status' => __("Qualcosa è andato storto. Riprova.", 'mos-theme'),
				'paypal_status' => __("Stai per essere reindirizzato sul sito di PayPal...", 'mos-theme'),
				'register_ok_status' => __("Grazie per esserti registrato!", 'mos-theme'),
				'login_ok_status' => __("Accesso effettuato correttamente", 'mos-theme'),
				'recover_ok_status' => __("Controlla la tua e-mail e clicca sul pulsante per reimpostare la password.", 'mos-theme'),
				) );
	}


	/**
	 * Get all Delivery products
	 *
	 * @return mixed|string
	 */
	public function getProducts() {
		//print_r($this->_delivery->getProducts());

		return ( $this->_delivery->getProducts() );

	}

	/**
	 * Get all Delivery $cat_slug's products
	 *
	 * @param $cat_slug
	 *
	 * @return array
	 */
	public function getCategoryProducts( $cat_slug ) {
		$return   = [];
		$products = $this->getProducts();
		foreach ( $products['products'] as $product ) {
			foreach ( $product['categories'] as $category ) {
				if ( $category['slug'] == $cat_slug && $product['status'] ) {
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
	public function getProduct( $product_slug ) {
		/*
		$return   = [];
		$products = $this->getProducts();
		foreach ( $products['data'] as $product ) {
			if ( $product['slug'] == $product_slug ) {
				foreach ( $product['categories'] as $category ) {
					if ( $category['slug'] == $cat_slug ) {
						return $product;
					}

				}
			}
		}
		*/
		return $this->_delivery->getProduct( $product_slug )['product'];

		//
	}

	/**
	 * Get all Delivery categories
	 *
	 * @return array
	 */
	public function getCategories( $addDegustazioni = true ) {
		$return     = [];
		$categories = $this->_delivery->getCategories();


		//escludo Root e Degustazioni
		foreach ( $categories as $category ) {
			if ( $category['parent_id'] == 1 && $category['menu'] && $category['featured'] == true ) {
				if ( $addDegustazioni ) {
					if ( $category['slug'] != 'degustazioni' && $category['slug'] != 'business-lunch' ) {
						$return[] = $category;
					}
				} else {
					$return[] = $category;

				}
			}
		}
		if ( $addDegustazioni ) //Aggiungo le degustazioni singole
		{
			return array_merge( $return, $this->getCategoryProducts( 'degustazioni' ), $this->getCategoryProducts( 'business-lunch' ) );
		}

		return $return;
		//return ( $this->_delivery->getCategories() );
	}

	public function getCategory( $cat_slug ) {
		$category = $this->_delivery->getCategory( $cat_slug );

		//	var_dump($category);
		return ( $category );
	}

	public function getVouchers() {
		$return = [];

		$data     = $this->getProducts();
		
		$products = $data['products'];

		foreach ( $products as $product ) {
			
			if ( $product['voucher'] ) {
				$return[] = $product;
			}
		}

		return $return;
	}

	/**
	 * Get Delivery cart
	 *
	 * @return mixed|string
	 */
	public function getCart() {
		global $wpdb;
		$this->cart = $this->_delivery->getCart( $_POST['deliverySession'] );
		echo json_encode( $this->_delivery->getCart( $_POST['deliverySession'] ) );
		wp_die();
	}

	/**
	 * Delivery Register (AJAX)
	 *
	 */
	public function register() {
		global $wpdb;
		$register_response = $this->_delivery->register( $_POST['deliverySession'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'], $_POST['address'], $_POST['city'], $_POST['country'], $_POST['post_code'], $_POST['phone_number'] ) ;

		if ($register_response){
			$login_response = $this->_delivery->login( $_POST['deliverySession'], $_POST['email'], $_POST['password'] );

			$this->_mailchimp->addSubscriber($_POST['first_name'], $_POST['last_name'], $_POST['email']);

			echo $login_response['api_token'];
		}
		else{
			echo 0;
		}

		wp_die();

	}

	/**
	 * Delivery Login (AJAX)
	 *
	 */
	public function login() {
		global $wpdb;
		echo json_encode($this->_delivery->login( $_POST['deliverySession'], $_POST['email'], $_POST['password'] ) );
	//	echo json_encode($this->_delivery->user($api_token));
		wp_die();
	}
	/**
	 * Delivery Recover Password (AJAX)
	 *
	 */
	public function recover_password() {
		global $wpdb;
		echo json_encode($this->_delivery->recoverPassword( $_POST['email'], $_POST['deliverySession'] ) );
		wp_die();
	}
	/**
	 * Delivery Change Password (AJAX)
	 *
	 */
	public function change_password() {
		global $wpdb;
		echo json_encode($this->_delivery->changePassword( $_POST['email'], $_POST['password'], $_POST['password_confirmation'], $_POST['token'] ) );
		wp_die();
	}
	/**
	 * Check if is logged in Delivery (AJAX)
	 *
	 */
	public function is_logged() {
		global $wpdb;
		echo json_encode($this->_delivery->user($_POST['apiToken']));
		wp_die();
	}

	/**
	 * Delivery Checkout (AJAX)
	 *
	 */
	public function checkout() {
		global $wpdb;
		$checkout_response = $this->_delivery->checkout( $_POST['deliverySession'], $_POST['first_name'], $_POST['last_name'], $_POST['address'], $_POST['city'], $_POST['country'], $_POST['post_code'], $_POST['phone_number'], $_POST['notes'], $_POST['api_token'], $_POST['voucher_name'], $_POST['voucher_email'] );
		echo json_encode($checkout_response);
		/*
		if ($checkout_response === null)
			echo 0;
		else
			echo $checkout_response;
		*/
		wp_die();
	}

	/**
	 * Get Delivery Product (AJAX)
	 *
	 */
	public function get_product() {
		global $wpdb;
	//	echo json_encode( $this->_delivery->getProductById( $_POST['productId'] ) );
		echo json_encode( $this->_delivery->getProduct( $_POST['productId'] ) );
		wp_die();
	}

	/**
	 * Get Delivery Order by ID (AJAX)
	 */
	public function get_order(){
		global $wpdb;
		echo json_encode($this->_delivery->getOrderById($_POST['orderId'], $_POST['deliverySession']));
		wp_die();
	}
	/**
	 * Aggiungi prodotto al carrello Delivery (AJAX)
	 */
	public function add_to_cart() {
		global $wpdb;
		
		$this->_delivery->addToCart( $_POST['deliverySession'], $_POST['price'], $_POST['productId'], $_POST['calice'], $_POST['attributePrice'], $_POST['attributeId'], $_POST['qty'] );
		echo $this->_delivery->getDeliverySession();
		wp_die();
	}

	/**
	 * Rimuovi prodotto dal carrello Delivery
	 */
	public function remove_from_cart() {
		global $wpdb;
		$response = $this->_delivery->removeFromCart( $_POST['deliverySession'], $_POST['productId'] );
		echo $response;
		echo $this->_delivery->getDeliverySession();
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
	public function getAttributeValuesHTML( $attributes, $sep = ' | ' ) {
		$return = [];
		foreach ( $attributes as $attribute ) :
			$return[] = $attribute['title'] . " " .$attribute['quantity'] . " " . $attribute['value'] . " <strong>" . number_format( $attribute["price"], 0, ",", "" ) . " € </strong>";
		endforeach;

		return implode( $sep, $return );
	}

	/**
	 *
	 * Get URL for Delivery category or product
	 *
	 * @param $data
	 *
	 * @return string
	 */
	public function getURL( $data ) {

		$url[] = site_url();
		$url[] = 'menu';
		if ( isset( $data['categories'] ) ) {
			$url[] = $data['categories'][0]['slug'];
		}
		if ( isset ( $data['category'] ) ) {
			$url[] = $data['category']['slug'];
		} else {
			$url[] = $data['slug'];
		}

		return implode( "/", $url );
	}

	/**
	 * Add tag for Delivery products
	 */
	public function product_api_rewrite_tag() {
		add_rewrite_tag( '%product_slug%', '([^&]+)' );
	}

	/**
	 * Add tag for Delivery products
	 */
	public function category_api_rewrite_tag() {
		add_rewrite_tag( '%category_slug%', '([^&]+)' );
	}


	/**
	 * Add rewrite rule for Delivery product page
	 */
	public function product_api_rewrite_rule() {
		add_rewrite_rule( '^menu/([^/]*)/([^/]*)/?', 'index.php??page_id=2697&category_slug=$matches[1]&product_slug=$matches[2]', 'top' );
	}

	/**
	 * Add rewrite rule for Delivery category page
	 */
	public function category_api_rewrite_rule() {
		add_rewrite_rule( '^menu/([^/]*)/?', 'index.php??page_id=2697&category_slug=$matches[1]', 'top' );
	}

	/**
	 * Prepare an array for category gallery, with images taken for all its single products
	 *
	 * @param $products
	 *
	 * @return array
	 */
	public function prepareCategoryGalleryData( $products, $category ) {
		$return = [];
		foreach ( $products as $product ) :
			if ( isset( $product['images'] ) && $product['status'] ) {
				foreach ( $product['images'] as $image ) {
					if ( $image['resize_1'] ) {
						$return[] = $image["resize_1"];
					}

				}
			}
		endforeach;
		if ( count( $return ) <= 0 ) {
			foreach ( $category['category']['images'] as $image ) {
				if ( $image['resize_1'] ) {
					$return[] = $image["resize_1"];
				}

			}
		}

		return $return;
	}

	public function getImageForMenuListing( $product ) {
		if ( count( $product['images'] ) > 0 ) {
			return QPC_DELIVERY_URL . $product['images'][0]['resize_1'];
		}

		return null;
	}

	/**
	 * Elenco categorie correlate
	 */
	public function getRelatedCategories( $category_slug ) {
		$categories = $this->getCategories( false );
		$return     = [];
		foreach ( $categories as $i => $category ) {
			if ( $category['slug'] != $category_slug ) {
				$return[] = [
					"url"  => $this->getURL( $category ),
					"name" => $category['name']
				];
			}
		}

		return $return;
	}

	/**
	 * Elenco prodotti correlati
	 */
	public function getRelatedProducts( $category_slug, $product_slug ) {
		$products = $this->getCategoryProducts( $category_slug );
		$return   = [];
		foreach ( $products as $i => $product ) {
			if ( $product['slug'] != $product_slug && $product['status'] ) {
				$return[] = [
					"url"  => $this->getURL( $product ),
					"name" => $product['name']
				];
			}
		}

		return $return;
	}

	//Breadcrumb
	public function getBreadcrumb( $sep = "&raquo;" ) {
		global $wp_query;
		$return = [];
		//base
		$urls[0] = [ "url" => site_url(), "name" => "Home" ];
		$urls[1] = [ "url" => get_permalink( 2697 ), "name" => get_the_title( 2697 ) ];

		if ( isset( $wp_query->query_vars['category_slug'] ) ) {
			$category = $this->getCategory( $wp_query->query_vars['category_slug'] );
			$urls[2]  = [ "url" => $this->getURL( $category ), "name" => $category["category"]['name'] ];
		}
		if ( isset( $wp_query->query_vars['product_slug'] ) ) {

			$product = $this->getProduct( $wp_query->query_vars['product_slug'] );
			$urls[3] = [ "url" => $this->getURL( $product ), "name" => $product['name'] ];
		}

		foreach ( $urls as $url ) {
			$return[] = "<li><a href='{$url["url"]}'>{$url["name"]}</a></li>";
		}

		return '<div class="delivery-breadcrumbs"><ul>' . implode( $sep, $return ) . '</ul></div>';
	}


	/**
	 * Sceglie l'immagine da mostrare nel blocco "gallery" (v. template-parts/blocchi/blocco-gallery.php)
	 * Restituisce la prima immagine della gallery della categoria o del prodotto, se c'è.
	 * Altrimenti prende la prima immagine dalle gallery dei prodotti
	 *
	 * @param $data
	 */
	public function getImageForBloccoGallery( $data ) {
		if ( count( $data['images'] ) > 0 ) {
			return QPC_DELIVERY_URL . $data["images"][0]["resize_1"];
		}

		$products = $this->getCategoryProducts( $data['slug'] );
		if ( count( $products ) > 0 ) {
			$gallery = $this->prepareCategoryGalleryData( $products, $data );
			if ( count( $gallery ) > 0 ) {
				return QPC_DELIVERY_URL . $gallery[0];
			}
		}

		return null;
	}
}

$QPC_Delivery = new QPC_Delivery();
