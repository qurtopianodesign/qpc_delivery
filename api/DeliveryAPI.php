<?php


class DeliveryAPI {
	const API_BASEURL = "https://qpc.qpcdev.it/api/";
	private $deliverySession;


	public function getProducts() {
		$curl = curl_init();
		curl_setopt_array( $curl, array(
			CURLOPT_URL            => self::API_BASEURL . 'products',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "GET",
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTPHEADER     => array(
				//	"Authorization: Bearer {$this->token}",
				"Content-Type: application/json",
				"cache-control: no-cache"
			),
		) );

		return $this->executeGETcurl( $curl );
	}

	/**
	 *
	 * @param $slug
	 *
	 * @return mixed|string
	 */
	public function getCategory( $slug ) {
		$curl = curl_init();
		curl_setopt_array( $curl, array(
			CURLOPT_URL            => self::API_BASEURL . "category/$slug",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "GET",
			CURLOPT_HTTPHEADER     => array(
				//	"Authorization: Bearer {$this->token}",
				"Content-Type: application/json",
				"cache-control: no-cache"
			),
		) );

		return $this->executeGETcurl( $curl );
	}

	public function getCategories() {
		$curl = curl_init();
		curl_setopt_array( $curl, array(
			CURLOPT_URL            => self::API_BASEURL . 'categories?colum=order&sort=asc',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "GET",
			CURLOPT_HTTPHEADER     => array(
				//	"Authorization: Bearer {$this->token}",
				"Content-Type: application/json",
				"cache-control: no-cache"
			),
		) );

		return $this->executeGETcurl( $curl );
	}

	/**
	 *
	 * @param $slug
	 *
	 * @return mixed|string
	 */
	public function getProduct( $slug ) {
		$curl = curl_init();
		curl_setopt_array( $curl, array(
			CURLOPT_URL            => self::API_BASEURL . "product/$slug",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "GET",
			CURLOPT_HTTPHEADER     => array(
				//	"Authorization: Bearer {$this->token}",
				"Content-Type: application/json",
				"cache-control: no-cache"
			),
		) );

		return $this->executeGETcurl( $curl );
	}

	public function getProductById( $id ) {
		$curl = curl_init();
		curl_setopt_array( $curl, array(
			CURLOPT_URL            => self::API_BASEURL . "product/$id/item",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "GET",
			CURLOPT_HTTPHEADER     => array(
				//	"Authorization: Bearer {$this->token}",
				"Content-Type: application/json",
				"cache-control: no-cache"
			),
		) );

		return $this->executeGETcurl( $curl );
	}

	public function getProductsCategory( $id ) {
		$curl = curl_init();
		curl_setopt_array( $curl, array(
			CURLOPT_URL            => self::API_BASEURL . "productscategories/id=$id",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "GET",
			CURLOPT_HTTPHEADER     => array(
				//	"Authorization: Bearer {$this->token}",
				"Content-Type: application/json",
				"cache-control: no-cache"
			),
		) );

		return $this->executeGETcurl( $curl );
	}


	public function register( $deliverySession, $first_name, $last_name, $email, $password, $address, $city, $country, $post_code, $phone_number ) {
		$curl     = curl_init();
		$postdata = [
			'first_name'   => $first_name,
			'last_name'    => $last_name,
			'email'        => $email,
			'password'     => $password,
			'address'      => $address,
			'city'         => $city,
			'country'      => $country,
			'post_code'    => $post_code,
			'phone_number' => $phone_number

		];

		$data = array(
			CURLOPT_URL            => self::API_BASEURL . 'register',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_POSTFIELDS     => http_build_query( $postdata, '', '&' )
		);
		curl_setopt_array( $curl, $data );

		$response = $this->executeGETcurl( $curl );
		if ( $response === "utente creato" ) {
			return true;
		}

		return false;
	}

	public function login( $deliverySession, $email, $password ) {
		$curl     = curl_init();
		$postdata = [
			'email'    => $email,
			'password' => $password
		];
		$data     = array(
			CURLOPT_URL            => self::API_BASEURL . "login",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_POSTFIELDS     => http_build_query( $postdata, '', '&' ),
		);
		curl_setopt_array( $curl, $data );

		return $this->executeGETcurl( $curl );

	}

	public function recoverPassword( $email, $deliverySession ) {
		$curl     = curl_init();
		$postdata = [
			'email' => $email
		];
		$data     = array(
			CURLOPT_URL            => self::API_BASEURL . "password/email",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_POSTFIELDS     => http_build_query( $postdata, '', '&' ),
			CURLOPT_HTTPHEADER     => array(
				//	"Authorization: Bearer {$this->token}",
				"Accept: application/json",
				"Cookie: delivery_session=$deliverySession"
			),
		);
		curl_setopt_array( $curl, $data );

		return $this->executeGETcurl( $curl );

	}

	public function changePassword( $email, $password, $password_confirmation, $token ) {
		$curl     = curl_init();
		$postdata = [
			'email'                 => $email,
			'password'              => $password,
			'password_confirmation' => $password_confirmation,
			'token'                 => $token
		];
		$data     = array(
			CURLOPT_URL            => self::API_BASEURL . "password/reset",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_POSTFIELDS     => http_build_query( $postdata, '', '&' ),
			CURLOPT_HTTPHEADER     => array(
				//	"Authorization: Bearer {$this->token}",
				"Accept: application/json"
			),
		);
		curl_setopt_array( $curl, $data );

		return $this->executeGETcurl( $curl );

	}


	public function user( $api_token ) {
		$curl = curl_init();
		curl_setopt_array( $curl, array(
			CURLOPT_URL            => self::API_BASEURL . "user?api_token=$api_token",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "GET",
			CURLOPT_HTTPHEADER     => array(
				//	"Authorization: Bearer {$this->token}",
				"Content-Type: application/json",
				"cache-control: no-cache"
			),
		) );

		return $this->executeGETcurl( $curl );

	}

	public function addToCart( $deliverySession, $price, $productId, $attributeValue, $attributePrice, $attributeTheId ,$attributeId, $qty = 1 ) {
		$curl      = curl_init();
		$price     += $attributePrice;
		$attribute = '';
		if ( $attributeValue != '' ) {
			$attribute = "&label=$attributeValue";
		}
		if ( $attributeId != '' ) {
			$attribute .= "&attributeId=$attributeId";
		}
		if ( $attributeTheId != '' ) {
			$attribute .= "&attributeTheId=$attributeTheId";
		}

		$data = array(
			CURLOPT_URL            => self::API_BASEURL . 'product/add/cart',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_HEADER         => 1,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_POSTFIELDS     => "price=$price&productId=$productId&qty=$qty" . $attribute,
			CURLOPT_HTTPHEADER     => array(
				"Content-Type: application/x-www-form-urlencoded",
				"Cookie: delivery_session=$deliverySession"
			),
		);
		curl_setopt_array( $curl, $data );

		$response = curl_exec( $curl );
		$err      = curl_error( $curl );


		if ( $err ) {
			return "cURL Error #:" . $err;
		} else {
			$this->setDeliverySession( $response );

			return json_decode( $response, true );
		}
	}

	public function removeFromCart( $deliverySession, $productId ) {
		/**
		 *
		 * CURLOPT_URL            => self::API_BASEURL . 'product/add/cart',
		 * CURLOPT_RETURNTRANSFER => true,
		 * CURLOPT_ENCODING       => "",
		 * CURLOPT_MAXREDIRS      => 10,
		 * CURLOPT_TIMEOUT        => 0,
		 * CURLOPT_HEADER         => 1,
		 * CURLOPT_FOLLOWLOCATION => true,
		 * CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
		 * CURLOPT_CUSTOMREQUEST  => "POST",
		 * CURLOPT_POSTFIELDS     => "price=$price&productId=$productId&qty=$qty".$attribute,
		 * CURLOPT_HTTPHEADER     => array(
		 * "Content-Type: application/x-www-form-urlencoded",
		 * "Cookie: delivery_session=$deliverySession"
		 * ),
		 */
		$curl = curl_init();
		$data = array(
      CURLOPT_URL            => self::API_BASEURL . 'cart/item/'.$productId.'/remove',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HEADER         => 1,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_HTTPHEADER     => array(
				"Content-Type: application/x-www-form-urlencoded",
				"Cookie: delivery_session=$deliverySession"
			),
		);

		curl_setopt_array( $curl, $data );
		$response = curl_exec( $curl );
		$err      = curl_error( $curl );
		if ( $err ) {
			return "cURL Error #:" . $err;
		} else {
			$return['response']                       = $response;
			$return['data']['CURLOPT_URL']            = "https://osteriadelviandante.finedelivery.it/api/cart/item/$productId/remove";
			$return['data']['CURLOPT_RETURNTRANSFER'] = "true";
			$return['data']['CURLOPT_ENCODING']       = '';
			$return['data']['CURLOPT_MAXREDIRS']      = "10";
			$return['data']['CURLOPT_TIMEOUT']        = "0";
			$return['data']['CURLOPT_HEADER']         = "1";
			$return['data']['CURLOPT_SSL_VERIFYPEER'] = "0";
			$return['data']['CURLOPT_FOLLOWLOCATION'] = "true";
			$return['data']['CURLOPT_HTTP_VERSION']   = "CURL_HTTP_VERSION_1_1";
			$return['data']['CURLOPT_CUSTOMREQUEST']  = "POST";
			$return['data']['CURLOPT_HTTPHEADER']     = "array(
				'Content-Type: application/x-www-form-urlencoded',
				'Cookie: delivery_session=$deliverySession')";

			//	$this->deliverySession = $deliverySession;
			return json_encode( $return );
			//	return  $data;
		}
		//return $this->executeCURL( $curl );
	}


	/**
	 * @param $deliverySession
	 * @param $first_name
	 * @param $last_name
	 * @param $address
	 * @param $city
	 * @param $country
	 * @param $post_code
	 * @param $phone_number
	 * @param $notes
	 * @param string $api_token
	 *
	 * @param $voucher_name
	 * @param $voucher_email
	 *
	 * @return array|bool|string
	 */
 public function checkout( $deliverySession, $first_name, $last_name, $address, $city, $country, $post_code, $phone_number, $notes, $api_token, $voucher_name, $voucher_email /*$id*/ ) {
		$curl     = curl_init();
		$postdata = [
		//	'id'            => $id,
			'first_name'    => $first_name,
			'last_name'     => $last_name,
			'address'       => $address,
			'city'          => $city,
			'country'       => $country,
			'post_code'     => $post_code,
			'phone_number'  => $phone_number,
			'notes'         => $notes,
			'api_token'     => $api_token,
			'voucher_name'  => $voucher_name,
			'voucher_email' => $voucher_email
		];
		$data     = array(
			CURLOPT_URL            => self::API_BASEURL . "checkout/order",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 0,
			CURLOPT_HEADER         => 1,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_POSTFIELDS     => http_build_query( $postdata, '', '&' ),
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_HTTPHEADER     => array(
				"Content-Type: application/x-www-form-urlencoded",
				"Cookie: delivery_session=$deliverySession"
			)
		);
		curl_setopt_array( $curl, $data );

		$response = curl_exec( $curl );

		$redirectURL = curl_getinfo( $curl, CURLINFO_EFFECTIVE_URL );
		curl_close( $curl );
		if ( $redirectURL == self::API_BASEURL . "checkout/order" ) {
			$return['response'] = $response;
		} else {
			$return['redirect'] = $redirectURL;
		}

		return $return;
		//return $this->executeGETcurl( $curl );
	}

	/**
	 * @param $deliverySession
	 *
	 * @return mixed|string|null
	 */
	public function getCart( $deliverySession ) {
		if ( $deliverySession != '' ) {
			$curl = curl_init();
			curl_setopt_array( $curl, array(
				CURLOPT_URL            => self::API_BASEURL . 'cart',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => "",
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => "GET",
				CURLOPT_HTTPHEADER     => array(
					//	"Authorization: Bearer {$this->token}",
					"Content-Type: application/json",
					"cache-control: no-cache",
					"Cookie: delivery_session=$deliverySession"
				),
			) );

			return $this->executeGETcurl( $curl );
		}

		return null;
	}

	public function getOrderById( $order_id, $deliverySession ) {

		if ( $order_id != '' ) {
			$curl = curl_init();
			curl_setopt_array( $curl, array(
				CURLOPT_URL            => self::API_BASEURL . 'order/' . $order_id,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => "",
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => "GET",
				CURLOPT_HTTPHEADER     => array(
					//	"Authorization: Bearer {$this->token}",
					"Content-Type: application/json",
					"cache-control: no-cache",
					"Cookie: delivery_session=$deliverySession"
				),
			) );

			return $this->executeGETcurl( $curl );
		}

		return null;
	}

	private function executeGETcurl( $curl ) {
		$response = curl_exec( $curl );
		$err      = curl_error( $curl );
		curl_close( $curl );
		if ( $err ) {
			return "cURL Error #:" . $err;
		} else {
			return json_decode( $response, true );
		}
	}

	public function setDeliverySession( $response ) {

		preg_match_all( '/^Set-Cookie:\s*([^;]*)/mi', $response, $matches );
		$cookies = array();
		foreach ( $matches[1] as $item ) {
			parse_str( $item, $cookie );
			$cookies = array_merge( $cookies, $cookie );
		}
		$this->deliverySession = $cookies['delivery_session'];

	}


	public function getDeliverySession() {
		return $this->deliverySession;
	}

}
