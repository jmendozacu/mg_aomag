<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Addonline
 * @package    Addonline_SprintSecure
 * @copyright  Copyright (c) 2004-2007 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Addonline_SprintSecure_Model_Source_Capturemode extends Addonline_SprintSecure_Model_Abstract
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'normal', 
			      'label' => Mage::helper('sprintsecure')->__('Normal')),
				  
            array('value' => 'author', 
			      'label' => Mage::helper('sprintsecure')->__('Author Capture')),
				  
			array('value' => 'validation', 
			      'label' => Mage::helper('sprintsecure')->__('Validation'))
        );
    }
}