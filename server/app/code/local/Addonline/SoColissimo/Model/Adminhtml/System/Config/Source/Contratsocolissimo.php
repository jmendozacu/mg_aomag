<?php
/**
 * Addonline_SoColissimo
 * 
 * @category    Addonline
 * @package     Addonline_SoColissimo
 * @copyright   Copyright (c) 2013 Addonline
 * @author 	    Addonline (http://www.addonline.fr)
 */

/**
 * Used in creating options for Socolissimo Contract selection
*
*/
class Addonline_SoColissimo_Model_Adminhtml_System_Config_Source_Contratsocolissimo
{

	/**
	 * Options getter
	 *
	 * @return array
	 */
	public function toOptionArray()
	{

		$options = array();
		$observer = Mage::getSingleton('socolissimo/observer');
		$storeId = Mage::app()->getStore()->getId();
		if ($observer->_9cd4777ae76310fd6977a5c559c51820($storeId, Addonline_SoColissimo_Model_Observer::CONTRAT_FLEXIBILITE)) {
			$options[] = array('value' => 'flexibilite', 'label'=>Mage::helper('adminhtml')->__('Flexibilité'));
		}
		if ($observer->_9cd4777ae76310fd6977a5c559c51820($storeId, Addonline_SoColissimo_Model_Observer::CONTRAT_LIBERTE)) {
			$options[] = array('value' => 'liberte', 'label'=>Mage::helper('adminhtml')->__('Liberté'));
		}
		return $options;
		
	}


}
