<?php

class MailchimpAPI {

	const MC_APIKEY = '73750b9f835a8dfc047c06e8a903293a-us13';
	const MC_APISERVER = 'us13';
	const MC_LISTID = '0233bcf01d';

	/**
	 *
	 * ADD SUBSCRIBER
	 *
	 * From MAILCHIMP DOC
	 * #!/bin/bash
	 * set -euo pipefail
	 *
	 *
	 * list_id="YOUR_LIST_ID"
	 * user_email="prudence.mcvankab@example.com"
	 * user_fname="Prudence"
	 * user_lname="McVankab"
	 *
	 * curl -sS --request POST \
	 * --url "https://$API_SERVER.api.mailchimp.com/3.0/lists/$list_id/members" \
	 * --user "key:$API_KEY" \
	 * --header 'content-type: application/json' \
	 * --data @- \
	 * <<EOF | jq '.id'
	 * {
	 * "email_address": "$user_email",
	 * "status": "subscribed",
	 * "merge_fields": {
	 * "FNAME": "$user_fname",
	 * "LNAME": "$user_lname"
	 * }
	 * }
	 * EOF
	 *
	 * @see https://mailchimp.com/developer/guides/create-your-first-audience/#add-a-contact-to-an-audience
	 *
	 * @param $fname
	 * @param $lname
	 * @param $email
	 */
	public function addSubscriber( $fname, $lname, $email ) {
		$curl     = curl_init();
		$postdata = [
			'email_address'   => $email,
			'status'       => "subscribed",
			"merge_fields" => [
				'FNAME' => $fname,
				'LNAME' => $lname
			]
		];
		$data     = array(
			CURLOPT_URL            => "https://" . self::MC_APISERVER . ".api.mailchimp.com/3.0/lists/" . self::MC_LISTID . "/members",
			CURLOPT_USERPWD         => "key:".self::MC_APIKEY,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 10,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_CUSTOMREQUEST  => "POST",
			CURLOPT_POSTFIELDS     => json_encode( $postdata ),
			CURLOPT_HTTPHEADER     => array(
				"Content-Type: application/json",
			),
		);
		curl_setopt_array( $curl, $data );
		$response = curl_exec( $curl );
		$err      = curl_error( $curl );
		curl_close( $curl );
		if ( $err ) {
			return "cURL Error #:" . $err;
		} else {
			return json_decode( $response, true );
		}
	}
}