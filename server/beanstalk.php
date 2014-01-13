<?php 
require 'app/Mage.php';
Varien_Profiler::enable();
Mage::setIsDeveloperMode(true);
ini_set('display_errors', 1);
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
Mage::app()->getStore()->setConfig('dev/log/active', true);

function _log($message, $echo=false) {
	Mage::log($message, null, 'beanstalk.log');
	if ($echo) {
		if (is_array($message)) {
			foreach ($message as $line) {
				echo $line."<br/>";
			}
		} else {
			echo $message."<br/>";
		} 
	}
}


/*
 *  Vérification de l'adresse IP appelante 
 */
$autorized_ips = array();
$autorized_ips[] = '127.0.0.1';
$autorized_ips[] = '::1';
$autorized_ips [] = '195.28.202.129'; // IP Addonline 
for ($i=48; $i<=79; $i++) {
	$autorized_ips [] = '50.31.156.'.$i; // IPs Beanstalk (1ère plage)
}
for ($i=108; $i<=122; $i++) {
	$autorized_ips [] = '50.31.189.'.$i; // IPs Beanstalk (2eme plage)
}
$remoteIp = @$_SERVER['REMOTE_ADDR'];
if (strpos ($remoteIp, '192.168.') ===0 || $remoteIp=='127.0.0.1') { //si on est derrière un proxy
	$remoteIp = @$_SERVER['HTTP_X_FORWARDED_FOR'];
}
if (!in_array($remoteIp, $autorized_ips))
{
	_log("Tentative non autorisée ".@$_SERVER['REQUEST_URI']." depuis ".$remoteIp);
	die("TOP SECRET");
}


/*
 *  Traitement web hook
 */	
if (isset ($_GET['hook'])) {
	$hook = $_GET['hook'];
	
	if ($hook =='pre' ) {
		
		/*
		 *  AVANT le déploiement
  		 */	
		_log("Pre-Deployment depuis ".$remoteIp);
		
		//On lève le flag maintenance
		touch (dirname(__FILE__).DS.'maintenance.flag');

		_log("Pre-Deployment OK", true);
			
	} else if ($hook =='post' ) {

		/*
		 *  APRES le déploiement
  		 */	
		_log("Post-Deployment depuis ".$remoteIp);

		//on vide le cache APC
		if(function_exists("apc_clear_cache")) {
			apc_clear_cache('user');
			apc_clear_cache('opcode');
		}
		//on vide le cache File
		$cacheDir = dirname(__FILE__).DS.'var'.DS.'cache';
		emptyDir($cacheDir, false);
		
		//TODO : remplacer cela par une méthode qui utilise le vrai backend utiliser magento pour vider le cache, tous les caches
		//  attention, sans lancer la mise à jour en cas de montée de version !!
		
		/*
		 *  Compilation 
		 *   - des sprites par Glue
		 *   - des css par Less  
  		 */	
		$output = array();
		$cmdeGlue = "glue skin/frontend/CLIENT/default/images/client/origin -l -f --img=skin/frontend/CLIENT/default/images/client/sprites --css=skin/frontend/CLIENT/default/less --sprite-namespace= --namespace= --cachebuster --optipng";
		$output[] = "exec >> ".$cmdeGlue;
		$resultGlue;
		exec($cmdeGlue, $output, $resultGlue);
		if ($resultGlue!=0) {
			$output[]="<h2>ERREUR lors de la génération du sprite par GLUE !! : $resultGlue</h2>";
			_log($output, true);
			die();
		} 
		
		$cmdeLess = "lessc -x skin/frontend/CLIENT/default/less/styles.less > skin/frontend/CLIENT/default/css/styles.css";
		$output[] = "exec >> ".$cmdeLess;
		$resultLess;
		exec($cmdeLess, $output, $resultLess);
		if ($resultLess!=0) {
			$output[]="<h2>ERREUR lors de la compilation LESS  !! : $resultLess</h2>";
			_log($output, true);
			die();
		}
			
		_log($output, true);
		
		//On baisse le flag maintenance
		unlink (dirname(__FILE__).DS.'maintenance.flag');
		
		_log("Post-Deployment OK", true);
				
	} else {
		_log("Tentative non conforme ".@$_SERVER['REQUEST_URI']." depuis ".$remoteIp);
		die("TOP SECRET");
	}	
} else {
	_log("Tentative non conforme ".@$_SERVER['REQUEST_URI']." depuis ".$remoteIp);
	die("TOP SECRET");
	
}



function emptyDir($cacheDir, $deleteMe) {
	
	if(!$dh = @opendir($cacheDir)) return;
	while (false !== ($obj = readdir($dh))) {
		if($obj=='.' || $obj=='..') continue;
		if($obj=='.gitignore' || $obj=='readme.txt') continue;
		if (!@unlink($cacheDir.'/'.$obj)) emptyDir($cacheDir.'/'.$obj, true); 
	}
	closedir($dh);
	if ($deleteMe){
		@rmdir($cacheDir);
	}
}