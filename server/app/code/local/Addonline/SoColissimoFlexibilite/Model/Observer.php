<?php
/**
 * Addonline_SoColissimoFlexibilite
 * 
 * @category    Addonline
 * @package     Addonline_SoColissimoFlexibilite
 * @copyright   Copyright (c) 2011 Addonline
 * @author 	    Addonline (http://www.addonline.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Addonline_SoColissimoFlexibilite_Model_Observer extends Varien_Object
{

	public function _9cd4777ae76310fd6977a5c559c51820(){
		eval(base64_decode("JGtleSA9ICdlOTgzY2ZjNTRmODhjNzExNGU5OWRhOTVmNTc1N2RmNic7CgkJaWYoKG1kNShNYWdlOjpnZXRTdG9yZUNvbmZpZygnd2ViL3Vuc2VjdXJlL2Jhc2VfdXJsJykuJGtleS4nU29Db2xpc3NpbW9GbGV4aWJpbGl0ZScpIT1NYWdlOjpnZXRTdG9yZUNvbmZpZygnc29jb2xpc3NpbW9mbGV4aWJpbGl0ZS9saWNlbmNlL3NlcmlhbCcpKSAmJiAoTWFnZTo6Z2V0U3RvcmVDb25maWcoJ3NvY29saXNzaW1vZmxleGliaWxpdGUvbGljZW5jZS9hY3RpdmUnKSkgKXsKCQkJJHNldmVyaXR5PU1hZ2VfQWRtaW5Ob3RpZmljYXRpb25fTW9kZWxfSW5ib3g6OlNFVkVSSVRZX01BSk9SOyR0aXRsZT0gIlZvdXMgZGV2ZXogcmVuc2VpZ25lciB1bmUgY2zDqSBsaWNlbmNlIHZhbGlkZSBwb3VyIGxlIG1vZHVsZSBTbyBjb2xpc3NpbW8gRmxleGliaWxpdGUuIExlIG1vZHVsZSBhIMOpdMOpIGTDqXNhY3RpdsOpIjskZGVzY3JpcHRpb249ICJMZSBtb2R1bGUgU28gY29saXNzaW1vIEZsZXhpYmlsaXTDqSBuJ2EgcGFzIHVuZSBjbMOpIGxpY2VuY2UgdmFsaWRlIjsJJGRhdGUgPSBkYXRlKCdZLW0tZCBIOmk6cycpOyBNYWdlOjpnZXRNb2RlbCgnYWRtaW5ub3RpZmljYXRpb24vaW5ib3gnKS0+cGFyc2UoYXJyYXkoYXJyYXkoJ3NldmVyaXR5JyA9PiAkc2V2ZXJpdHksJ2RhdGVfYWRkZWQnPT4gJGRhdGUsJ3RpdGxlJz0+ICR0aXRsZSwnZGVzY3JpcHRpb24nICAgPT4gJGRlc2NyaXB0aW9uLCd1cmwnPT4gJycsJ2ludGVybmFsJyAgICAgID0+IHRydWUpKSk7CU1hZ2U6OmdldE1vZGVsKCdjb3JlL2NvbmZpZycpLT5zYXZlQ29uZmlnKCdzb2NvbGlzc2ltb2ZsZXhpYmlsaXRlL2xpY2VuY2UvYWN0aXZlJywwKTtyZXR1cm4gZmFsc2U7CgkJfWVsc2V7CgkJCSRfdW5yZWFkTm90aWNlcyA9IE1hZ2U6OmdldE1vZGVsKCdhZG1pbm5vdGlmaWNhdGlvbi9pbmJveCcpLT5nZXRDb2xsZWN0aW9uKCktPmdldEl0ZW1zQnlDb2x1bW5WYWx1ZSgnaXNfcmVhZCcsIDApOwoJCQlmb3JlYWNoKCRfdW5yZWFkTm90aWNlcyBhcyAkbm90aWNlKToKCQkJaWYoc3RycG9zKCRub3RpY2UtPmdldERhdGEoJ2Rlc2NyaXB0aW9uJyksJ1NvIGNvbGlzc2ltbyBGbGV4aWJpbGl0JykpOgoJCQkkbm90aWNlLT5zZXRJc1JlYWQoMSktPnNhdmUoKTsKCQkJZW5kaWY7CgkJCWVuZGZvcmVhY2g7CgkJCXJldHVybiB0cnVlOwoJCX0="));		
	}

    public function checkoutEventSocodata($observer)
    {

    	$quote = $observer->getEvent()->getQuote();
    	$request = Mage::app()->getRequest();
    	
    	$typeSocolissimo = $request->getParam('type_socolissimo_choisi');
    	
    	if ($typeSocolissimo) {
    	$adresseRelais = null;
    	$socoShippingData = Mage::getSingleton('checkout/session')->getData('socolissimoflexibilite_shipping_data');
    	//on positionne l'identitifant relais précédent si il existe
    	if (is_array($socoShippingData) && isset($socoShippingData['PRID'])) {
    		$adresseRelais = $socoShippingData['PRID'];
    	}
 		//on réinitilaise les données en session
    	$socoShippingData = array();
		Mage::getSingleton('checkout/session')->setData('socolissimoflexibilite_shipping_data', $socoShippingData);
	    if ($shippingAddress = $quote->getShippingAddress()) {
	    	
			if (strpos($shippingAddress->getShippingMethod(),'socolissimoflexibilite_')===0) {
	        	
	        	$arrayData = array();
		        $street = array();
		        $customerNotesArray = array();
	
		        $socoShippingData['CEDELIVERYINFORMATION'] = '';
		        $socoShippingData['CEDOORCODE1'] = '';
		        $socoShippingData['CEDOORCODE2'] = '';
		        $socoShippingData['CEENTRYPHONE'] = '';
		        $socoShippingData['CECIVILITY'] = '';
		        $socoShippingData['CEEMAIL'] = $quote->getCustomer()->getData('email');
		        
		        $telephone = $request->getParam('tel_socolissimo');
		        if ($telephone) { 
		            $arrayData['telephone'] = $telephone;
		        	$socoShippingData['CEPHONENUMBER'] = $telephone;
	        	}   	

	        	$socoShippingData['DELIVERYMODE'] = $this->_getSocoProductCode($typeSocolissimo);
	        	 // marquer le mode RDV au niveau de l'adresse (calcul dépend du mode)
	        	$arrayData['soco_product_code'] = $this->_getSocoProductCode($typeSocolissimo);
	        	if ($typeSocolissimo == 'rdv') {
	        		$customerNotesArray['0']='Livraison sur rendez-vous : '.$telephone;
	            	$socoShippingData['CEDELIVERYINFORMATION'] = 'Prise de rendez-vous : '.$telephone;
	            }
	    		$idRelais = $request->getParam('relais_socolissimo');
	    		$relais = Mage::getModel('socolissimoflexibilite/service')->findPointRetraitAcheminementByID($idRelais);

	    		if ($relais instanceof pointRetraitAcheminement) {
	
	        		$arrayData['customer_address_id'] = null;
		            
	        		$billingAddress = $quote->getBillingAddress();
		            $arrayData['lastname'] = '('.$billingAddress->getFirstname().' '.$billingAddress->getLastname().')';
		            $arrayData['firstname'] = $relais->nom;
		            $arrayData['city'] = $relais->localite;
		            $arrayData['postcode'] =$relais->codePostal;
		            $arrayData['telephone'] = $telephone;
		            	        		
		            $street['0'] = $relais->adresse1;
		            $street['1'] = $relais->adresse2;
		            $street['2'] = $relais->adresse3;
		            $street['3'] = $relais->indiceDeLocalisation;
		            
	            	$shippingAddress->setStreet($street);
		            
		            $customerNotesArray['0']='Livraison relais colis : '.$relais->identifiant;
			        
		            $socoShippingData['PRID'] = $relais->identifiant;
			        
	            	$arrayData['save_in_address_book'] = 0;
	            
	        	} else {
		            $socoShippingData['PRID'] = '';
	        	}
	        	//on initialise les données socolissimo en session
	        	Mage::getSingleton('checkout/session')->setData('socolissimoflexibilite_shipping_data', $socoShippingData);
	        	
	        	if (! empty($customerNotesArray)) {
		        	$arrayData['customer_notes'] = implode("\n",$customerNotesArray);
		        }

				// sauvegarder l'adresse
	            $shippingAddress->addData($arrayData);
	        	
	            // relancer le calcul du prix (modification du shipping amount via socolissimo)
	            // voir getCalculatedPrice() de ShippingMethod
	            $shippingAddress->setCollectShippingRates(true);
	            $shippingAddress->collectShippingRates();
	            

	        }        
		}
		
		if ($adresseRelais && $adresseRelais != '') {
			if (!isset($socoShippingData['PRID']) || $socoShippingData['PRID'] == '') {
				//si l'adresse de livraison était un relais et que maintenant ça ne l'est plus il faut remettre l'adresse de facturation :
				$billingAddress = $quote->getBillingAddress();
				$shippingAdress = $quote->getShippingAddress();
				$shippingAdress->setData('customer_id', $billingAddress->getData('customer_id'));
               	$shippingAdress->setData('customer_address_id', $billingAddress->getData('customer_address_id'));
               	$shippingAdress->setData('email', $billingAddress->getData('email'));
               	$shippingAdress->setData('prefix', $billingAddress->getData('prefix'));
               	$shippingAdress->setData('firstname', $billingAddress->getData('firstname'));
               	$shippingAdress->setData('middlename', $billingAddress->getData('middlename'));
               	$shippingAdress->setData('lastname', $billingAddress->getData('lastname'));
               	$shippingAdress->setData('suffix', $billingAddress->getData('suffix'));
               	$shippingAdress->setData('company', $billingAddress->getData('company'));
               	$shippingAdress->setData('street', $billingAddress->getData('street'));
               	$shippingAdress->setData('city', $billingAddress->getData('city'));
               	$shippingAdress->setData('region', $billingAddress->getData('region'));
               	$shippingAdress->setData('region_id', $billingAddress->getData('region_id'));
               	$shippingAdress->setData('postcode', $billingAddress->getData('postcode'));
               	$shippingAdress->setData('country_id', $billingAddress->getData('country_id'));
               	if (is_array($socoShippingData) && isset($socoShippingData['CEPHONENUMBER'])) {
	               	$shippingAdress->setData('telephone', $socoShippingData['CEPHONENUMBER']);
               	} else {
	               	$shippingAdress->setData('telephone', $billingAddress->getData('telephone'));
               	}
                $shippingAdress->setData('save_in_address_book', 0);
			}
		}
    	}
        return $this;
    }

    /**
     * 
     * Sauvegarde les donnees de la commande propre a So Colissimo
     * @param $observer
     */
    
    public function addSocoAttributesToOrder($observer)
    {
    	try {
		    	$checkoutSession = Mage::getSingleton('checkout/session');
		    	$shippingData = $checkoutSession->getData('socolissimoflexibilite_shipping_data');
		    	
    			//on ne fait le traitement que si le mode d'expedition est socolissimo (et donc qu'on a recupere les donnees de socolissimo)
		    	if (isset($shippingData) && count($shippingData) > 0) {
			    	if (isset($shippingData['DELIVERYMODE'])) {
				    	$observer->getEvent()->getOrder()->setSocoProductCode($shippingData['DELIVERYMODE']);
			    	}
			
			    	if (isset($shippingData['CEDELIVERYINFORMATION'])) {
				    	$observer->getEvent()->getOrder()->setSocoShippingInstruction($shippingData['CEDELIVERYINFORMATION']);
			    	}
			
				    if (isset($shippingData['CEDOORCODE1'])) {	
				    	$observer->getEvent()->getOrder()->setSocoDoorCode1($shippingData['CEDOORCODE1']);
				    }
			    	
				    if (isset($shippingData['CEDOORCODE2'])) {
				    	$observer->getEvent()->getOrder()->setSocoDoorCode2($shippingData['CEDOORCODE2']);
				    }   	
			    	
				    if (isset($shippingData['CEENTRYPHONE'])) {
				    	$observer->getEvent()->getOrder()->setSocoInterphone($shippingData['CEENTRYPHONE']);
				    }
				    
				    if (isset($shippingData['PRID'])) {
			    		$observer->getEvent()->getOrder()->setSocoRelayPointCode($shippingData['PRID']);
				    }    	
				    
				    if (isset($shippingData['CECIVILITY'])) {
			    		$observer->getEvent()->getOrder()->setSocoCivility($shippingData['CECIVILITY']);
				    }
			
				    if (isset($shippingData['CEPHONENUMBER'])) {
			    		$observer->getEvent()->getOrder()->setSocoPhoneNumber($shippingData['CEPHONENUMBER']);
				    }
			
				    if (isset($shippingData['CEEMAIL'])) {
			    		$observer->getEvent()->getOrder()->setSocoEmail($shippingData['CEEMAIL']);
				    }
				    
		    	}
    	    } catch (Exception $e) {
    	    	Mage::Log('Failed to save so-colissimo data : '.print_r($shippingData, true));
    	}	    
    }
  
    public function resetSession($observer)
    {
		$checkoutSession = Mage::getSingleton('checkout/session');
    	$checkoutSession->setData('socolissimoliberte_shipping_data', array());
    }

    protected function _getSocoProductCode($type) {
    	if ($type=='poste') {
    		return 'BPR';
    	} elseif ($type=='cityssimo') {
    		return 'CIT';
    	} elseif ($type=='commercant') {
    		return 'A2P';
    	} elseif ($type=='rdv') {
    		return 'RDV';
    	} elseif ($type=='livraison') {
    		return Mage::getStoreConfig('carriers/socolissimoflexibilite/domicile_signature')?'DOS':'DOM';
    	} else {
    		return false;
    	}
    }
}
