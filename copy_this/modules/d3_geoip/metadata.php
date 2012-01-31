<?php
/**
 * Module information
 */
$aModule = array(
    'id'           => 'd3ordermanager',
    'title'        => 'D³ Auftragsmanager Pro / Order Manager Pro',
    'description'  => 'Führt ausgeführte Aufträge nach definierten Regeln weiter.<br>Aktiviere Sie die Moduleinträge bitte immer und steuern Sie die Modulaktivität ausschließlich im Adminbereich des Moduls.',
    'thumbnail'    => 'picture.png',
    'version'      => '1.0.0',
    'author'       => 'D³ Data Development',
    'extend'      => array(
        'oxemail' => 'd3ordermanager/core/d3_oxemail_ordermanager'
    )
);