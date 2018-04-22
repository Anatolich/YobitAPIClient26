<?php

/**
 * The basic abstract representation of the Yobit API object for further extends.
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

use GuzzleHttp\Client as HttpClient;

abstract class YobitAPIBase
{
	/**
	 * Store HTTP client wrapper.
	 *
	 * @var object|null
	 *
 	 */

	protected $http_client = null;

	/**
	 * Basic primary options for the HTTP client request's.
	 *
	 * @var array
	 *
 	 */

	protected $http_client_options = array(

		'timeout' => 14, # time in milliseconds, to wait end of the request, before catch an error

		'verify' => false, # disable ssl verification for the request's

		'headers' => array(

			'User-Agent' => 'YobitAPIClient26/1.0' # unique client sign
		)
	);

	/**
	 * Basic options config parameters.
	 *
	 * @var array
	 *
 	 */

	protected $config = array();

	/**
	 * Keys param for basic config integrity
	 *
	 * @var array
	 *
 	 */

	protected $config_basic_keys = array(

		'api_auth' => array(

			'key',

			'secret'
		),

		'api_services_urls' => array(

			'public_v2',

			'public_v3',

			'trade'
		)
	);

	/**
 	 * Initializes and prepare need default properties.
 	 * 
 	 * @param array $specific_client_option	specific input option's for http client initialization
	 *
 	 * @return void
 	 *
 	 */

	public function __construct($specific_client_option = array())
	{
		if(!empty($specific_client_option)) $this->http_client_options += $specific_client_option; # to do check

		$this->http_client = new HttpClient($this->http_client_options);
	}

	/**
 	 * Read work config file.
	 *
 	 * @return array|false
 	 *
 	 */

	protected function readConfigFile()
	{
		return parse_ini_file(__DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'boot_production.ini', true);
	}

	/**
 	 * Check config integrity by a right type syntax of entity.
	 *
 	 * @return bool
 	 *
 	 */

	protected function checkConfigIntegrity()
	{
		# TO DO
	}

	/**
 	 * Preparing given trade pair's param to work condition, if is it have a right syntax.
 	 *
 	 * @throws RuntimeException if getting wrong syntax trade pair's parameter
 	 *
 	 * @param mixed $trade_pairs	given trade pair's
	 *
 	 * @return string
 	 *
 	 */

	protected function prepareTradePairs($trade_pairs = null)
	{
		$trade_pairs_ref = (is_string($trade_pairs)) ? array($trade_pairs) : $trade_pairs;

		if(!$this->checkTradePairs($trade_pairs_ref)) $this->wrongtradePairsParam();

		$trade_pairs_ref = (count($trade_pairs_ref) === 1) ? $trade_pairs_ref[0] : implode('-', $trade_pairs_ref);

		return $trade_pairs_ref;
	}

	/**
 	 * Check given trade pair's on right syntax.
 	 * 
 	 * @param array $trade_pairs	given trade pair's in array type
	 *
 	 * @return bool
 	 *
 	 */

	protected function checkTradePairs($trade_pairs = array())
	{
		$result = false;

		$regex_trade_pair_signature = '^([a-z]+_[a-z]+(?:(-(?!$))|$))+$';

		if(is_array($trade_pairs))
		{
			foreach($trade_pairs as $trade_pair)
			{
				if(is_string($trade_pair) && preg_match_all('/'.$regex_trade_pair_signature.'/', $trade_pair))
				{
					$result = true;
				}

				else
				{
					$result = false;

					break;
				}
			}
		}

		return $result;
	}

	/**
 	 * Throw message for wrong http status code in the response thread.
	 *
 	 * @return void
 	 *
 	 */

	protected function unresponsibleStatusCode()
	{
		throw new \RuntimeException('Yobit API service unavilable.');
	}

	/**
 	 * Throw message for wrong syntax trade pair's parameter of the API methods.
	 *
 	 * @return void
 	 *
 	 */

	protected function wrongtradePairsParam()
	{
		throw new \RuntimeException('Wrong trade pair\'s param given for the request.');
	}
}

?>