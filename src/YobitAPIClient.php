<?php

/**
 * Createional object generator.
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

final class YobitAPIClient
{
	/**
	 * Mark, determines what exactly need from YoBit API functional.
	 *
	 * @var string
	 *
 	 */

	private $current_api_privileges = 'info';

	/**
	 * Сonserve the constructor from direct implementation.
	 *
	 * @return void
	 *
 	 */

	private function __construct() {}

	/**
 	 * "Factory Method", depending on the input parameters, it will provide the necessary interface in the form of a ready implementation.
 	 * 
 	 * Not nesasary for Trade part of api.
	 *
 	 * @return object
 	 *
 	 */

	public static function get($api_privileges = 'info', $using_api_version = null)
	{
		if(is_string($api_privileges))
		{
			switch($api_privileges)
			{
				case 'trade':

					$realisation_class = new YobitTradeAPI();

				break;

				case 'tapi':

					$realisation_class = new YobitTradeAPI();

				break;

				case 'full':

					# TODO booth obj, tapi and info properties

				break;

				case 'fapi':

					# alias to full

				break;

				default:

					$realisation_class = new YobitPublicAPI($using_api_version);

				break;
			}
		}

		return $realisation_class;
	}

	/**
	 * Toggle API function mode.
	 *
	 * @return void
	 *
 	 */

	public function toggleAPIMode($api_privileges = 'info')
	{
		
	}
}

?>