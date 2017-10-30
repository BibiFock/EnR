<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Contact;
use App\StructureType;
use App\Structure;

class ImportCsv extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import data from shared csv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('start parse');
        $rows = $this->parseCsv();
        $this->info('parse finish start insert');
        foreach ($rows as $row) {
            $contact = null;
            if (!empty($row['responsable_lastname'])) {
                $contact = Contact::firstOrCreate([
                    'first_name' => $row['responsable_firstname'],
                    'last_name' => $row['responsable_lastname'],
                ]);
            }
            $structType = StructureType::firstOrCreate([
                'name' => 'structure initiatrice'
            ]);
            $struct = Structure::firstOrCreate([
                'name' => $row['structure'],
                'contact_id' => (!empty($contact) ? $contact->id : null),
                'type_id' => (!empty($structType) ? $structType->id : null)
            ]);

            //TODO finish it

            Roof::firstOrCreate([
                'probability' => $row['probability'],
                'structure_id' => $struct->id,
                'square_area' => $row['potential_m2'],
                'power_max' => $row['estimate_hight'],
                'power_min' => $row['estimate_low'],
                'purchase_category_id' => $row[''],
                'type_id' => Type::firstOrCreate([
                    'name' => $row['roof_type'],
                ])->id,
                'tilt_id' => Tilt::firstOrCreate([
                    'name' => $row['inclinaison'],
                ])->id,
                'south_orientation_id' => SouthOrientation::firstOrCreate([
                    'name' => $row['south_orientation'],
                ])->id,
                'erp' => $row['erp'],
                'building_size' => $row['building_hight'],
                'perimeter_abf' => $row['ABF_primeter'],
                'remarks' => $row['description'],
                'inverter_location' => $row['inverter_position'],
                'inverter_distance' => $row['inverter_distance'],
                'street' => $row['street'],
                'zip' => $row['zip'],
                'city' => $row['city'],
                'department_id' => Department::firstOrCreate([
                    'name' => 'department'
                ])->id,
                'latitude' => $row['latitude'],
                'longitude' => $row['longitute'],
                'owner_id' => Contact::firstOrCreate([
                    'name' => $row['structure_name']
                ])->id,
            ]);

        }
        $this->info('is finished');
    }

    protected function parseCsv()
    {
        $filePath = resource_path('data/data.csv');

        $content = file_get_contents($filePath);
        $rows = explode(PHP_EOL, $content);
        $title = explode(';', array_shift($rows));

        $realTitle = array(
            'id' => 'id',
            'Probabilité de réalisation' => 'probability',
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
            // 'Nom structure' => 'structure_name',
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
                if (empty($title[$k])
                    || empty($realTitle[$title[$k]])
                ) {
                    continue;
                }
                $tmp[$realTitle[$title[$k]]] = $v;
            }
            if (empty($tmp['link'])) {
                continue;
            }
            if (preg_match('/google.fr.*@(?P<latitude>[0-9.]+),(?P<longitude>[0-9.]+)/i', $tmp['link'], $results)) {
                // $tmp['google_link'] = $tmp['latitude'];
                $tmp['latitude'] = $results['latitude'];
                $tmp['longitude'] = $results['longitude'];
            }
            $data[] = $tmp;
        }

        return $data;
    }

}
