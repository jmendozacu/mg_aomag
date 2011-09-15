<?php
/**
 * Addonline_SoColissimoLiberte
 * 
 * @category    Addonline
 * @package     Addonline_SoColissimoLiberte
 * @copyright   Copyright (c) 2011 Addonline
 * @author 	    Addonline (http://www.addonline.fr)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;

$installer->startSetup();

if (!$installer->tableExists($installer->getTable('socolissimoliberte_relais'))) {

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('socolissimoliberte_relais')};
CREATE TABLE {$this->getTable('socolissimoliberte_relais')} (
  id_relais int(11) NOT NULL auto_increment,
  identifiant varchar(6) NOT NULL UNIQUE,
  libelle varchar(50) NOT NULL,
  adresse varchar(38) NOT NULL,
  complement_adr varchar(38) default NULL,
  lieu_dit varchar(38) default NULL,
  indice_localisation varchar(70) default NULL,
  code_postal varchar(5) NOT NULL,
  commune varchar(32) NOT NULL,
  latitude double(10,8) NOT NULL,
  longitude double(10,8) NOT NULL,
  indicateur_acces int(11) default NULL,
  type_relais varchar(3) NOT NULL,
  point_max double(2,0) default NULL,
  lot_acheminement varchar(10) default NULL,
  distribution_sort varchar(10) default NULL,
  version varchar(2) default NULL,
  PRIMARY KEY  (id_relais)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

}

if (!$installer->tableExists($installer->getTable('socolissimoliberte_horaire_ouverture'))) {

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('socolissimoliberte_horaire_ouverture')};
CREATE TABLE {$this->getTable('socolissimoliberte_horaire_ouverture')} (
  id_horaire_ouverture int(11) NOT NULL auto_increment,
  id_relais_ho int(11) NOT NULL,
  deb_periode_horaire varchar(5) NOT NULL,
  fin_periode_horaire varchar(5) NOT NULL,
  horaire_lundi varchar(23) default NULL,
  horaire_mardi varchar(23) default NULL,
  horaire_mercredi varchar(23) default NULL,
  horaire_jeudi varchar(23) default NULL,
  horaire_vendredi varchar(23) default NULL,
  horaire_samedi varchar(23) default NULL,
  horaire_dimanche varchar(23) default NULL,
  PRIMARY KEY  (id_horaire_ouverture),
  KEY fk_socolissimo_relais (id_relais_ho),
  CONSTRAINT fk_socolissimo_relais FOREIGN KEY (id_relais_ho) REFERENCES {$this->getTable('socolissimoliberte_relais')} (id_relais)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

}

if (!$installer->tableExists($installer->getTable('socolissimoliberte_periode_fermeture'))) {

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('socolissimoliberte_periode_fermeture')};
CREATE TABLE {$this->getTable('socolissimoliberte_periode_fermeture')} (
  id_periode_fermeture int(11) NOT NULL auto_increment,
  id_relais_fe int(11) NOT NULL,
  deb_periode_fermeture date default NULL,
  fin_periode_fermeture date default NULL,
  PRIMARY KEY  (id_periode_fermeture),
  KEY fk_socilissimo_relais (id_relais_fe),
  CONSTRAINT fk_socilissimo_relais FOREIGN KEY (id_relais_fe) REFERENCES {$this->getTable('socolissimoliberte_relais')} (id_relais)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

}


$installer->endSetup();