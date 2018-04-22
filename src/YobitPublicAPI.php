<?php

/**
 * Object realizing access for functional of Yobit Public API part.
 * 
 * API v2 realize as is compatible functional.
 * 
 * @license https://github.com/Anatolich/YobitAPIClient26/blob/master/LICENSE
 *
 * @package YobitAPIClient26
 *
 * @copyright 2018
 *
 * @version Id rev V 0.0.4
 *
 */

namespace Anatolich\YobitAPIClient26;

class YobitPublicAPI extends YobitAPIBase
{
	/**
	 * Using API version, characteristically for a Yobit Public API.
	 *
	 * @var string
	 *
 	 */

	protected $current_api_version = 'v3';

	/**
	 * Syntax signature for request methods call for given API version.
	 *
	 * @var string
	 *
 	 */

	protected $signature_methods_request = ':$method_name/:$request_param';

	/**
 	 * Prepare inheritance object, with characteristic features of functionality.
 	 * 
 	 * @param string|null $using_api_version	an api version which client realisation want to use
	 *
 	 * @return void
 	 *
 	 */

	public function __construct($using_api_version = null)
	{
		if(is_string($using_api_version) && $using_api_version === 'v2')
		{
			$this->current_api_version = $using_api_version;

			$this->signature_methods_request = ':$request_param/:$method_name';	# compute param by version
		}

		$this->config = $this->readConfigFile();

		$this->checkConfigIntegrity();

		$client_base_uri = (!empty($this->config)) ? $this->config['api_services_urls']['public_'.$this->current_api_version]: null;

		parent::__construct(array('base_uri' => $client_base_uri));
	}

	/**
	 * Provides list of the current actual trade pair's with common excahnge info, at the request moment.
	 * 
	 * @throws RuntimeException if getting unresponsible http status code for the request
	 * 
	 * @return array
 	 *
 	 */

	public function info()
	{
		$method_name = 'info';

		$out_stream = $this->http_client->request(

			'GET',

			$method_name
		);

		if($out_stream->getStatusCode() !== 200) $this->unresponsibleStatusCode();

		$json_data = $out_stream->getBody();

		$currency_pairs = json_decode($json_data, true);

		return $currency_pairs;
	}

	/**
	 * Provides statistic data for given trade pair's in the last 24 hours.
	 * 
	 * @throws RuntimeException if getting unresponsible http status code for the request
	 *
     * @param mixed $trade_pairs	given trade pair's
     *
     * @return array
 	 *
 	 */

	public function ticker($trade_pairs = null)
	{
		$method_name = 'ticker';

		$request_param = $this->prepareTradePairs($trade_pairs);

		$out_stream = $this->http_client->request(

			'GET',

			strtr($this->signature_methods_request, array(

				':$method_name' => $method_name,

				':$request_param' => $request_param
			))
		);

		if($out_stream->getStatusCode() !== 200) $this->unresponsibleStatusCode();

		$json_data = $out_stream->getBody();

		$currency_pairs_info = json_decode($json_data, true);

		return $currency_pairs_info;
	}

	/**
	 * Provides information about lists of active orders for selected trade pair's, restricted by limit.
	 * 
	 * @throws RuntimeException if getting unresponsible http status code for the request
	 *
     * @param mixed $trade_pairs	given trade pair's
     * 
     * @param integer|null $orders_limit	limit result
     * 
     * @return array
 	 *
 	 */

	public function depth($trade_pairs = null, $orders_limit = null) 
	{
		$method_name = 'depth';

		$request_param = $this->prepareTradePairs($trade_pairs);

		$limit_param = (is_null($orders_limit) || $orders_limit > 2000 || !is_integer($orders_limit)) ? 150 : $orders_limit;

		$out_stream = $this->http_client->request(

			'GET',

			strtr($this->signature_methods_request, array(

				':$method_name' => $method_name,

				':$request_param' => $request_param
			)),

			array(

				'query' => array(

					'limit' => $limit_param
				)
			)
		);

		if($out_stream->getStatusCode() !== 200) $this->unresponsibleStatusCode();

		$json_data = $out_stream->getBody();

		$active_orders = json_decode($json_data, true);

		return $active_orders;
	}

	/**
	 * Provides information about the last transactions of selected trade pair's restricted by limit.
	 * 
	 * @throws RuntimeException if getting unresponsible http status code for the request
	 *
 	 * @param mixed $trade_pairs	given trade pair's
 	 *
 	 * @param integer|null $trades_limit	limit result
 	 *
 	 * @return array
 	 * 
 	 */

	public function trades($trade_pairs = null, $trades_limit = null)
	{
		$method_name = 'trades';
		//calleble
		$request_param = $this->prepareTradePairs($trade_pairs);

		$limit_param = (is_null($trades_limit) || $trades_limit > 2000 || !is_integer($trades_limit)) ? 150 : $trades_limit;

		$out_stream = $this->http_client->request(

			'GET',

			strtr($this->signature_methods_request, array(

				':$method_name' => $method_name,

				':$request_param' => $request_param
			)),

			array(

				'query' => array(

					'limit' => $limit_param
				)
			)
		);

		if($out_stream->getStatusCode() !== 200) $this->unresponsibleStatusCode();

		$json_data = $out_stream->getBody();

		$last_deals = json_decode($json_data, true);

		return $last_deals;
	}
}

?>