<?php

$content = file_get_contents('./data.csv');
$rows = explode(PHP_EOL, $content);
$title = explode(';', array_shift($rows));

$realTitle = array(
    'Probabilité de réalisation' => 'probility',
    'Département' => 'department',
    'Nom structure' => 'structure',
    'Nom responsable' => 'responsable_lastname',
    'Prénom responsable' => 'responsable_firstname',
    'm2 potentiels pour le PV' => 'potential_m2',
    'estimation basse' => 'estimate_low',
    'estimation haute' => 'estimate_hight',
    'catégorie tarif achat' => 'sold_cat',
    'type de toiture' => 'roof_type',
    'inclinaison' => 'inclinaison',
    'orientation par rapport au Sud' => 'south_orientation',
    'ERP ?' => 'erp',
    'Hauteur du bâtiment' => 'building_hight',
    'Périmètre ABF' => 'ABF_primeter',
    'Commentaire sur la toiture' => 'description',
    'Emplacement Onduleurs' => 'inverter_position',
    'Distance onduleur / centrale' => 'inverter_distance',
    'adresse : n° et rue' => 'street',
    'code postal' => 'zip',
    'commune' => 'city',
    'lien recherche google maps' => 'link',
    'coordonnées GPS latitude' => 'latitude',
    'coordonnées GPS longitute' => 'longitude',
    'Type' => 'structure_type',
    'Nom structure' => 'structure_name',
    'Nom contact' => 'contact_lastname',
    'Prénom contact' => 'contact_firstname',
    'mail contact' => 'contact_email',
    'tél contact' => 'contact_phone',
);

$data = array();
foreach ($rows as $row) {
    $row = explode(';', $row);
    $tmp = array();
    foreach ($row as $k => $v) {
        $tmp[$realTitle[$title[$k]]] = $v;
    }
    if (preg_match('/google.fr.*@(?P<latitude>[0-9.]+),(?P<longitude>[0-9.]+)/i', $tmp['latitude'], $results)) {
        $tmp['google_link'] = $tmp['latitude'];
        $tmp['latitude'] = $results['latitude'];
        $tmp['longitude'] = $results['longitude'];
    }
    $data[] = $tmp;
}

file_put_contents('./data.json', json_encode($data));
