<?php

/**
 * @package         Google Structured Data
 * @version         4.6.0 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2019 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace GSD;

defined('_JEXEC') or die('Restricted access');

use GSD\PluginBase;
use GSD\MappingOptions;

/**
 *  Google Structured Data Product Plugin Base
 */
class PluginBaseEvent extends PluginBase
{
 	/**
	 * The MapOptions Backend Event. Triggered by the mappingoptions fields to help each integration add its own map options.
	 *  
	 * @param	string	$plugin
	 * @param	array	$options
	 *
	 * @return	void
	 */
    public function onMapOptions($plugin, &$options)
    {
		if ($plugin != $this->_name)
        {
			return;
		}
		
		$remove_options = [
			'modified',
			'created',
			'ratingValue',
			'reviewCount'
		];
		
		// Remove unsupported mapping options
		foreach ($remove_options as $key => $option)
		{
			unset($options['GSD_INTEGRATION']['gsd.item.' . $option]);
		}

		// Add Event based options
		$new_options = [
			'startdate'  	      => 'GSD_EVENT_START_DATE',
			'enddate'    	      => 'GSD_EVENT_END_DATE',
			'offerprice'          => 'GSD_EVENT_OFFER_PRICE',
			'locationname'        => 'GSD_EVENT_LOCATION_NAME',
			'locationaddress'     => 'GSD_EVENT_STREET_ADDRESS',
			'offercurrency'       => 'GSD_PRODUCT_OFFER_CURRENCY',
			'offerinventorylevel' => 'GSD_EVENT_INVENTORY_LEVEL',
			'offerstartdate'      => 'GSD_EVENT_AVAILABILITY_START_DATE'
		];

		MappingOptions::add($options, $new_options, 'GSD_INTEGRATION', 'gsd.item.');
	}
}

?>