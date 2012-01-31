<?php
/**
 * Module information
 */
$aModule = array(
    'id'           => 'd3ordermanager',
    'title'        => 'D� Auftragsmanager Pro / Order Manager Pro',
    'description'  => 'F�hrt ausgef�hrte Auftr�ge nach definierten Regeln weiter.<br>Aktiviere Sie die Moduleintr�ge bitte immer und steuern Sie die Modulaktivit�t ausschlie�lich im Adminbereich des Moduls.',
    'thumbnail'    => 'picture.png',
    'version'      => '1.0.0',
    'author'       => 'D� Data Development',
    'extend'      => array(
        'oxemail' => 'd3ordermanager/core/d3_oxemail_ordermanager'
    )
);