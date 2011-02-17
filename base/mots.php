<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2011                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

/**
 * Interfaces des tables mots et groupes de mots pour le compilateur
 *
 * @param array $interfaces
 * @return array
 */
function mots_declarer_tables_interfaces($interfaces){

	$interfaces['table_des_tables']['mots']='mots';
	$interfaces['table_des_tables']['groupes_mots']='groupes_mots';


	#$interfaces['table_date']['groupes_mots'] = 'date';
	#$interfaces['table_date']['mots'] = 'date';

	#$interfaces['table_titre']['mots'] = "titre, '' AS lang";

	$interfaces['tables_jointures']['spip_articles'][]= 'mots_liens';
	$interfaces['tables_jointures']['spip_articles'][]= 'mots';
	
	$interfaces['tables_jointures']['spip_breves'][]= 'mots_liens';
	$interfaces['tables_jointures']['spip_breves'][]= 'mots';
	
	$interfaces['tables_jointures']['spip_documents'][]= 'mots_liens';
	$interfaces['tables_jointures']['spip_documents'][]= 'mots';
	
	$interfaces['tables_jointures']['spip_rubriques'][]= 'mots_liens';
	$interfaces['tables_jointures']['spip_rubriques'][]= 'mots';
	
	$interfaces['tables_jointures']['spip_syndic'][]= 'mots_liens';
	$interfaces['tables_jointures']['spip_syndic'][]= 'mots';
	
	$interfaces['tables_jointures']['spip_syndic_articles'][]= 'mots_liens';
	$interfaces['tables_jointures']['spip_syndic_articles'][]= 'mots';
	
	$interfaces['tables_jointures']['spip_mots'][]= 'mots_liens';
	
	$interfaces['tables_jointures']['spip_groupes_mots'][]= 'mots';

	$interfaces['exceptions_des_jointures']['type_mot'] = array('spip_mots', 'type');
	$interfaces['exceptions_des_jointures']['id_mot_syndic'] = array('spip_mots_liens', 'id_mot');
	$interfaces['exceptions_des_jointures']['titre_mot_syndic'] = array('spip_mots', 'titre');
	$interfaces['exceptions_des_jointures']['type_mot_syndic'] = array('spip_mots', 'type');

	return $interfaces;
}


/**
 * Table auxilaire spip_mots_xx
 *
 * @param array $tables_principales
 * @return array
 */
function mots_declarer_tables_auxiliaires($tables_auxiliaires){

	$spip_mots_liens = array(
			"id_mot"	=> "bigint(21) DEFAULT '0' NOT NULL",
			"id_objet"	=> "bigint(21) DEFAULT '0' NOT NULL",
			"objet"	=> "VARCHAR (25) DEFAULT '' NOT NULL");

	$spip_mots_liens_key = array(
			"PRIMARY KEY"		=> "id_mot,id_objet,objet",
			"KEY id_mot"	=> "id_mot");

	$tables_auxiliaires['spip_mots_liens'] =
		array('field' => &$spip_mots_liens, 'key' => &$spip_mots_liens_key);
		
	return $tables_auxiliaires;
}




/**
 * Declarer le surnom des groupes de mots
 *
 * @param array $table
 * @return array
 */
function mots_declarer_tables_objets_surnoms($table){
	#$table['mot'] = 'mots';
	return $table;
}

/**
 * Alias de type pour les groupes de mot
 * @param array $table
 * @return string
 */
function mots_declarer_type_surnoms($table) {
	$table['groupes_mot'] = 'groupe_mots';
	$table['groupemot'] = 'groupemots'; // pour les appels a autoriser('faire','groupemots')
	#$table['mot-cle'] = 'mot'; // pour les icones...
	return $table;
}


function mots_declarer_tables_objets_sql($tables){
	$tables['spip_mots'] = array(
		'type'=>'mot',
	  'type_surnoms' => array('mot-cle'), // pour les icones...

		'texte_retour' => 'icone_retour',
		'texte_objets' => 'titre_page_mots_tous',
		'texte_modifier' => 'icone_modifier_mot',
		'info_aucun_objet'=> 'info_aucun_mot',
		'info_1_objet' => 'info_1_mot',
		'info_nb_objets' => 'info_nb_mots',
		'titre' => "titre, '' AS lang",
		'date' => 'date',
		'principale' => 'oui',
		'field'=> array(
			"id_mot"	=> "bigint(21) NOT NULL",
			"titre"	=> "text DEFAULT '' NOT NULL",
			"descriptif"	=> "text DEFAULT '' NOT NULL",
			"texte"	=> "longtext DEFAULT '' NOT NULL",
			"id_groupe"	=> "bigint(21) DEFAULT 0 NOT NULL",
			"type"	=> "text DEFAULT '' NOT NULL",
			"maj"	=> "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"	=> "id_mot",
		),
		'rechercher_champs' => array(
	  'titre' => 8, 'texte' => 1, 'descriptif' => 5
		),
		'rechercher_jointures' => array(
			'article' => array(
				'mot' => array('titre' => 3),
			),
			'breve' => array(
				'mot' => array('titre' => 3),
			),
			'rubrique' => array(
				'mot' => array('titre' => 3),
			),
			'document' => array(
				'mot' => array('titre' => 3)
			)
		),
		'champs_versionnes' => array('titre', 'descriptif', 'texte','id_groupe'),
	);

	$tables['spip_groupes_mots'] = array(
		'table_objet_surnoms' => array('groupe_mots' /*hum*/,'groupe_mot' /* hum*/,'groupe' /*hum (EXPOSE)*/),

		'type'=>'groupe_mots',
	  'type_surnoms' => array('groupes_mot','groupemot'),

		'texte_retour' => 'icone_retour',
		'texte_objets' => 'mots:titre_groupes_mots',
		'texte_modifier' => 'icone_modif_groupe_mots',
		'info_aucun_objet'=> 'mots:info_aucun_groupemots',
		'info_1_objet' => 'mots:info_1_groupemots',
		'info_nb_objets' => 'mots:info_nb_groupemots',
		'titre' => "titre, '' AS lang",
		'date' => 'date',
		'principale' => 'oui',
		'field'=> array(
			"id_groupe"	=> "bigint(21) NOT NULL",
			"titre"	=> "text DEFAULT '' NOT NULL",
			"descriptif"	=> "text DEFAULT '' NOT NULL",
			"texte"	=> "longtext DEFAULT '' NOT NULL",
			"unseul"	=> "varchar(3) DEFAULT '' NOT NULL",
			"obligatoire"	=> "varchar(3) DEFAULT '' NOT NULL",
			"tables_liees" => "text DEFAULT '' NOT NULL",
			"minirezo"	=> "varchar(3) DEFAULT '' NOT NULL",
			"comite"	=> "varchar(3) DEFAULT '' NOT NULL",
			"forum"	=> "varchar(3) DEFAULT '' NOT NULL",
			"maj"	=> "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"	=> "id_groupe"
		),
		'rechercher_champs' => array(
	  'titre' => 8, 'texte' => 1, 'descriptif' => 5
		),
		'rechercher_jointures' => array(
			'article' => array(
				'mot' => array('titre' => 3),
			),
			'breve' => array(
				'mot' => array('titre' => 3),
			),
			'rubrique' => array(
				'mot' => array('titre' => 3),
			),
			'document' => array(
				'mot' => array('titre' => 3)
			)
		),
		'champs_versionnes' => array('titre', 'descriptif', 'texte','un_seul','obligatoire','tables_liees','minirezo','forum','comite'),
	);
	return $tables;
}
?>
