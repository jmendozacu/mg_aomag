/**
 * Addonline_SoColissimo
 * 
 * @category    Addonline
 * @package     Addonline_SoColissimo
 * @copyright   Copyright (c) 2011 Addonline
 * @author 	    Addonline (http://www.addonline.fr)
 */

/**
 * Fonction startWith sur l'objet String de javascript
 */
String.prototype.startWith = function(t, i) { if (i==false) { return
(t == this.substring(0, t.length)); } else { return (t.toLowerCase()
== this.substring(0, t.length).toLowerCase()); } } 

/**
 * Variables globales
 */

var glsMyPosition;
var glsListRelais=new Array();
var glsRelayMap;
var glsOpenedInfowindow;
var glsRelaisChoisi;

/* Liste des markers */
var glsMarkersArray = [];

/*
 * Suppression des markers
 */
function clearMarkers() {
  for (var i = 0; i < glsMarkersArray.length; i++ ) {
	  glsMarkersArray[i].setMap(null);
  }
  glsMarkersArray.length = 0;
}

/**
 * Initialisation au chargement de la page
 */
jQuery(function($) {	
	
	
	//Cas du onestep checkout, si on change l'adresse de livraison après avoir choisi gls
	/* jQuery('.onestepcheckout-index-index .address-select').live("change", function() {
		if(jQuery('#gls-location').size() <= 0 ){	
			$("#attentionSoColissimo").remove();
			$("label[for=\"billing-address-select\"]").parent().before('<p id="attentionSoColissimo" style="font-weight:bold;color:red;text-align:justify; padding-right:5px;">Suite à la modification de votre adresse et si votre mode de livraison est So Colissimo, veuillez séléctionner votre point de retrait en cliquant sur le mode de livraison.</p>');
		}
	}); */	
	
	/** 
	 * Sur l'événement change des radios boutons de choix de mode de livraison
	 */
	$("input[id^=\"s_method_gls\"]").live("click", function() {		
		shippingGLSRadioCheck(this);
	});			
	
	/*
	 * Evenement sur la modification du point relais
	 */
	$(".modifier_relay").live("click", function() {		
		$("input[id^=\"s_method_gls\"]").click();
	});	
	
	/*
	 * Sur l'évènement de choix de relay
	 */
	$('.choose-relay-point').live("click",function(){
		choisirRelaisGLS($(this).data('relayindex'));
	});
	
	/* Seules les saisies numériques sont autorisées dans les champs textes */
	$("#layer_gls_wrapper #cp_recherche").keypress(function(e) {
		var charCode = (e.which) ? e.which : e.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	});

	initGlsLogos();
	
});

/**
 * Initialise les logos, descriptions, style sur le radio bouttons gls
 * ceci est fait en javascript pour ne pas surcharger le template available.phtml et ainsi éviter des conflits avec d'autres modules.
 * (appelé au chargement du DOM mais aussi au rechargement ajax (voir Checkout.prototype.setStepResponse dans  gls\additional.phtml)
 */
function initGlsLogos() {
	
	jQuery("input[id^=\"s_method_gls\"]").each(function(index, element){
		
		if(!jQuery("body").hasClass("onestepcheckout-index-index")) {
			jQuery(element).parents("dd").addClass("s_method_gls");
		} else {
			jQuery("input[id^=\"s_method_gls\"]").parents("dt").addClass("s_method_gls");
			var dd = jQuery("input[id^=\"s_method_gls\"]").eq(0).parents("dt").prev().addClass("s_method_gls-title");
		}
		
		jQuery(element).prop("checked", "");
		var typeSocolissimo =  getTypeGlsFromRadio(jQuery(element), false);
		if (typeSocolissimo) {
			var radioParent = jQuery(element).parent();
			radioParent.prepend('<img src="/skin/frontend/base/default/images/gls/picto_'+typeSocolissimo+'.png" >');
			var typeSocolissimoDesc =  getTypeGlsFromRadio(jQuery(element), true);
			jQuery("#gls_description_"+typeSocolissimoDesc).clone().appendTo(radioParent).attr("style","display:block;");
			if (typeSocolissimo=='rdv' || (typeSocolissimo=='domicile' && jQuery("input[id^=\"s_method_gls_rdv\"]").length==0)) {
				radioParent.addClass("first");
			}
		}
	});
	/*/if(!jQuery("body").hasClass("onestepcheckout-index-index")) {
		jQuery(".s_method_gls").prev().addClass("s_method_gls-title").append('<img src="/skin/frontend/base/default/images/gls/gls.png" >');
	} else {
		jQuery(".s_method_gls-title").append('<img src="/skin/frontend/base/default/images/gls/gls.png" >');
	}*/
}

function getTypeGlsFromRadio(radio, forDescription) {
	var shippingMethod = radio.attr("value");
	var typeGls = shippingMethod.replace("gls_","");
	if (typeGls.startWith("tohome")) {		
		return 'tohome';
	} else if (typeGls.startWith("toyou")) {
		return 'toyou';
	} else if (typeGls.startWith("relay")){ 
		return 'relay';
	} else {
		//Sinon c'est un type de livraison inconnu
		alert("Mauvaise configuration du module GLS : dans le champ configuration le code doit commencer par tohome, toyou ou relay");
		return false;
	}
}

function shippingGLSRadioCheck(element) {	
	var glsRadio = jQuery(element);	
	var typeGls =  getTypeGlsFromRadio(glsRadio, false);				
	if(typeGls == "relay"){
		//on affiche le picto de chargement étape suivante du opc
		jQuery("#shipping-method-please-wait").show();		
		url = "/gls/ajax/selector/"			
			jQuery.ajax({
				url: url,
				success: function(data){					
					jQuery("#layer_gls").html(data);
					resetGLSShippingMethod();
					geocoder = new google.maps.Geocoder();
					geocodeGLSAdresse();					
				}
			});					
	}
}

function resetGLSShippingMethod() {
	jQuery("input[name='shipping_method']:checked").prop("checked","");
}

function geocodeGLSAdresse() {
		
	var searchAdress = jQuery('#cp_recherche').val();		
	if ((typeof google) != "undefined") {
		var geocoder = new google.maps.Geocoder();		
		geocoder.geocode({'address': searchAdress}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				glsMyPosition = results[0].geometry.location;
	 			//on met à jour la carte avec cette position				
				loadMap();				
				loadListePointRelais();
			} else {
				alert('Adresse invalide '+searchAdress);
			}
	    });
	} else {
		alert("Géolocalisation de l'adresse impossible, vérifiez votre connexion internet (Google inaccessible).");
	}
}

function changeMap() {
	if (glsMyPosition!=undefined) {		
		loadListePointRelais();
	}
}

function loadListePointRelais() {	
	if(jQuery("#cp_recherche").val()){
		url = "/gls/ajax/listPointsRelais"
		url = url + "/zipcode/" + jQuery("#cp_recherche").val() + "/country/" + "FR";
		jQuery.ajax({
			url: url,
			success: function(data){				
				jQuery("#col_droite_gls").html(data);	
				showGLSMap();
			}
		});		
	}
}

function loadMap(){
	
	mapOptions = {
	    /*zoom: 10,*/
	    /*center: glsMyPosition,*/
	    mapTypeId: google.maps.MapTypeId.ROADMAP,
	    disableDefaultUI: true,
		zoomControlOptions: {
			position: google.maps.ControlPosition.RIGHT_TOP
	    }
	}
	
	if( jQuery("#layer_gls").data("overlay") == null ) {
		jQuery("#layer_gls").overlay({
			mask: {
				color: '#000', 
				loadSpeed: 200, 
				opacity: 0.5 
			}, 
			load: true,
			onLoad: function(){ glsRelayMap = new google.maps.Map(document.getElementById('map_gls'), mapOptions); },
			closeOnClick: false,
			top: "center",	
			fixed: false,
			onClose: function(){ jQuery("#layer_gls").html(''); jQuery("#layer_gls").data("overlay",null);}
		});	
	} else {
		//glsRelayMap.setCenter(glsMyPosition);
	}
	
	jQuery("#shipping-method-please-wait").hide();

}

function showGLSMap() {	
	if ((typeof google)!="undefined") {
		var init = false;		
		//google.maps.event.addListener(glsRelayMap, 'tilesloaded', function () {

			// création de bornes vides...
			var bounds = new google.maps.LatLngBounds();

			if (!init){
				
				clearMarkers();
				
				jQuery('.gls_point_relay').each(function(index,element){	
					
					var relayPosition =  new google.maps.LatLng(jQuery(this).find('.GLS_relay_latitude').text(), jQuery(this).find('.GLS_relay_longitude').text());
					markerGLS = new google.maps.Marker({
					    map: glsRelayMap,
					    position: relayPosition,
					    title : jQuery(this).find('.GLS_relay_name').text(),
					    icon : '/skin/frontend/base/default/images/gls/marker.png'
					});					
					infowindowGLS=infoGLSBulleGenerator(jQuery(this));
					
					// Ajout à la liste des markers
					glsMarkersArray.push(markerGLS);
					
					attachGLSClick(markerGLS,infowindowGLS, index);

					// ...étendues à chaque point...
					bounds.extend(relayPosition);

				});
				
				// ...pour voir tous les points
				glsRelayMap.fitBounds(bounds);
				
			}
			init=true;
		//});
	}
}

//générateur d'infobulle
function infoGLSBulleGenerator(relay) {	

	contentString = '<div class="info-window">'

	contentString += '<span class="store-name">' + relay.find('.GLS_relay_name').text() + '</span>';

	contentString += '' +
					relay.find('.GLS_relay_address').text() + '<br/>' +
    				relay.find('.GLS_relay_zipcode').text() + ' ' + relay.find('.GLS_relay_city').text();

	
	contentString += "<a href='#' class='choose-relay-point' data-relayindex="+relay.find('.GLS_relay_index').text()+" data-relayid="+relay.find('.GLS_relay_id').text()+">Choisir ce lieu</a>";
	
	contentString += relay.find('.GLS_relay_hours').html();	
	


	
	contentString += "</div>";

	infowindow = new google.maps.InfoWindow({
		content: contentString
	});

	return infowindow;
}


function attachGLSClick(markerGLS,infowindowGLS, index){	
	//Clic sur le relais dans la colonne de gauche
	jQuery("#gls_point_relay_"+index).live("click",function() {		
			clickHandler(markerGLS,infowindowGLS, index);		   
		});
		
	//Clic sur le marqueur du relais dans la carte
	google.maps.event.addListener(markerGLS, 'click', function() {		
			clickHandler(markerGLS,infowindowGLS, index);
		});

}

function clickHandler(markerGLS,infowindowGLS, index){
	//fermer la derniere infobulle ouverte
	if(glsOpenedInfowindow) {
		glsOpenedInfowindow.close();
		jQuery("#layer_gls .gls_point_relay").removeClass("current");
    }			
	//ouvrir l'infobulle
	infowindowGLS.open(glsRelayMap,markerGLS);
    glsOpenedInfowindow=infowindowGLS;
    
    // Mise en évidence du relais dans la liste
	jQuery("#layer_gls .gls_point_relay").removeClass("current").eq(index).addClass("current");
    
}

function choisirRelaisGLS(index) {
	
	//resetShippingMethod();	
	jQuery("select[name='shipping_address_id']").prop('selectedIndex',0);
	jQuery("select[name='shipping_address_id'] option[value='']").prop('selectedIndex',0);	
			
	// On cache le layer
	if(jQuery("#sms_checkbox").is(":checked") && jQuery("#num_telephone").val() == "") {
		alert( Translator.translate("Please provide a valide phone number.") );
		return;
	}	
	jQuery("#layer_gls").data("overlay").close();
	jQuery("input[id^=\"s_method_gls_relay_").prop("checked","checked");
	var contenu_html = "<div id='gls_relais_choisi'><span>"+jQuery('#gls_point_relay_'+index).find('.GLS_relay_name').text()+"</span>"      +" <span class='modifier_relay'>" + Translator.translate("Update my relay point") + "</span>"   +  "<br/>"+jQuery('#gls_point_relay_'+index).find('.GLS_relay_address').text()+"<br/>"+jQuery('#gls_point_relay_'+index).find('.GLS_relay_zipcode').text()+" "+jQuery('#gls_point_relay_'+index).find('.GLS_relay_city').text() + "</div>";
	jQuery('#gls_relais_choisi').remove();
	jQuery("input[id^=\"s_method_gls_relay_").parent().append(contenu_html);
	/* On stock en session les informations du relais */
	
	if(jQuery("#sms_checkbox").is(":checked") && jQuery("#num_telephone").val() != ""){
		var warnbyphone = 1;		
	}else{
		var warnbyphone = 0;
	}
	
	url = "/gls/ajax/saveInSessionRelayInformations/"		
	jQuery.ajax({
		url: url,
		data: {                             // <-- just pass an object
	          name: jQuery('#gls_point_relay_'+index).find('.GLS_relay_name').text(),
	          address: jQuery('#gls_point_relay_'+index).find('.GLS_relay_address').text(),
	          city : jQuery('#gls_point_relay_'+index).find('.GLS_relay_city').text(),
	          zipcode : jQuery('#gls_point_relay_'+index).find('.GLS_relay_zipcode').text(),	
	          relayId : jQuery('#gls_point_relay_'+index).find('.GLS_relay_id').text(),
	          phone : jQuery("#num_telephone").val(),
	          warnbyphone : warnbyphone,
	    },
	    dataType: 'json', 
		success: function(){
			// On fait la sauvegarde de la méthode de livraison
			shippingMethod.save();	
		}
	});		
}

function validerTelephone() {
	
	 if(glsTelephoneForm.validator && glsTelephoneForm.validator.validate()){
    	var telephone = jQuery("#gls-telephone input[name='tel_gls']").val();
    	jQuery("#gls-hook").append('<input type="hidden" name="tel_gls" value="'+telephone+'" />');
    	jQuery("#layer_gls").data("overlay").close();
    }
	return false;
}

/** ajout de la fonction de validation numéro de téléphone portable */
Validation.add('valid-telephone-portable', 'Veuillez saisir un numéro de téléphone portable correct', function(v) {
    return (/^0(6|7)\d{8}$/.test(v) && !(/^0(6|7)(0{8}|1{8}|2{8}|3{8}|4{8}|5{8}|6{8}|7{8}|8{8}|9{8}|12345678)$/.test(v)));
});

Validation.add('valid-telephone-portable-belgique', 'Veuillez saisir un numéro de téléphone portable correct', function(v) {
	//Pour les destinataires belges, le numéro de téléphone portable doit commencer par le caractère + suivi de 324, suivi de 8 chiffres
	return (/^\+324\d{8}$/.test(v) && !(/^\+324(0{8}|1{8}|2{8}|3{8}|4{8}|5{8}|6{8}|7{8}|8{8}|9{8}|12345678)$/.test(v)));
});
