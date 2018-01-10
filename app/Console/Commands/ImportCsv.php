<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Contact;
use App\StructureType;
use App\Structure;
use App\Roof;
use App\Roof\PurchaseCategory;
use App\Roof\Tilt;
use App\Roof\Tilt\Type;
use App\User;

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('start parse');

        $rows = $this->parseCsv();
        $this->info('parse finish start insert for ' . count($rows) . ' roofs');

        $structType = StructureType::firstOrCreate([
            'name' => 'structure initiatrice'
        ]);

        foreach ($rows as $row) {
            $contact = null;
            if (!empty($row['responsable_lastname'])) {
                $contact = Contact::firstOrCreate([
                    'first_name' => $row['responsable_firstname'],
                    'last_name' => $row['responsable_lastname'],
                ]);
            }
            $struct = Structure::firstOrCreate([
                'name' => $row['structure'],
            ], [
                'contact_id' => (!empty($contact) ? $contact->id : null),
                'type_id' => (!empty($structType) ? $structType->id : null)
            ]);

            $purchaseCategory = PurchaseCategory::firstOrCreate([
                'name' => $row['sold_cat']
            ]);

            try {
                $typeId = Type::findOrFail(['name' => $row['roof_type']])->id;
            } catch (\Exception $e) {
                $typeId = 2; // other one
                $row['description'] .= ' | type de toit:' . $row['roof_type'];
            }

            $slope = null;
            switch ($row['inclinaison']) {
                case '35° optimale' :
                    $slope = 35;
                    break;
                case '> 25°/40°<' :
                    $slope = 32;
                    break;
                case '0° (à plat) à 10°' :
                    $slope = 5;
                    break;
                case '90° verticale (déconseillée)' :
                    $slope = 90;
                    break;
                case '> 10°/25°<' :
                    $slope = 17;
                    break;
            }

            $southOrientation = null;
            switch ($row['south_orientation']) {
                case '180° Sud':
                    $southOrientation = 0;
                    break;
                case '+/-5°':
                    $southOrientation = 5;
                    break;
                case '+/- 10°':
                    $southOrientation = 10;
                    break;
                case '+/- 15°':
                    $southOrientation = 15;
                    break;
                case '> 20°':
                    $southOrientation = 45;
                    break;
            }

            if (empty($row['name'])) {
                $row['name'] = $row['street'] . ', ' . $row['city'];
            }

            $cleanInts = [
                'potential_m2', 'estimate_hight', 'estimate_low',
                'building_hight', 'inverter_distance'
            ];
            foreach ($cleanInts as $field) {
                $row[$field] = preg_replace('/[\s ]+/', '', $row[$field]);
            }

            $roof = Roof::create([
                'name' => $row['name'],
                'probability' => $row['probability'],
                'structure_id' => $struct->id,
                'square_area' => $row['potential_m2'],
                'power_max' => $row['estimate_hight'],
                'power_min' => $row['estimate_low'],
                'purchase_category_id' => $purchaseCategory->id,
                'erp' => ($row['erp'] == 'Oui'),
                'building_size' => $row['building_hight'],
                'perimeter_abf' => ($row['ABF_primeter'] == 'Oui'),
                'remarks' => $row['description'],
                'street' => $row['street'],
                'zip' => $row['zip'],
                'city' => $row['city'],
                'owner_id' => $this->getOwnerId($row),
            ]);
            // on met le userId à 0 pour la console
            $roof->saveState(0);

            $tilt = Tilt::create([
                'roof_id' => $roof->id,
                'name' => 'default',
                'type_id' => $typeId,
                'slope' => $slope,
                'occupancy_rate' => 0,
                'ground_square_area' => 0,
                'south_orientation' => $southOrientation,
                'inverter_location' => $row['inverter_position'],
                'inverter_distance' => $row['inverter_distance'],
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
            ]);
        }

        $this->line('--- created users from structure initiatrice');
        $rows = Structure::where('type_id', $structType->id)->get();
        foreach ($rows as $row) {
            User::firstOrCreate(
                ['name' => $row->name],
                ['password' => app('hash')->make('123photo')]
            );
        }

        $this->info('is finished');
    }

    protected function parseCsv()
    {
        $filePath = resource_path('data/data.csv');
        $content = file_get_contents($filePath);
        $rows = explode(PHP_EOL, $content);
        $title = explode(';', array_shift($rows));

        $keys = array(
            'id',
            'name',
            'probability',
            'year_real',
            'department',
            'structure',
            'responsable_lastname',
            'responsable_firstname',
            'potential_m2',
            'estimate_low',
            'estimate_hight',
            'sold_cat',
            'roof_type',
            'inclinaison',
            'south_orientation',
            'erp',
            'building_hight',
            'ABF_primeter',
            'description',
            'inverter_position',
            'inverter_distance',
            'street',
            'zip',
            'city',
            'link',
            'latitude',
            'longitude',
            'structure_type',
            'structure_name',
            'contact_lastname',
            'contact_firstname',
            'contact_email',
            'contact_phone',
            'quote_total_off',
            'quote_roi_off',
            'quote_total',
            'quote_roi',
            'quote_panel_type',
            'quote_inverter_type',
            'quote_guarantee',
            'quote_certifications',
            'quote_remarks',
        );

        $data = array();
        $i= 0;
        foreach ($rows as $row) {
            if (empty($row)) {
                continue;
            }
            $row = explode(';', $row);
            $tmp = array();
            foreach ($row as $k => $v) {
                if (!empty($keys[$k])) {
                    $tmp[$keys[$k]] = trim($v);
                }
            }

            if (empty($tmp['link'])) {
                $this->line('no link here');
                if (!($gps = $this->getGPSCoord($tmp))) {
                    $this->error(
                        'GPS not found for ' . $tmp['street'] . ' ' . $tmp['city'] . ' ' . $tmp['zip']
                    );
                    continue;
                }
                $tmp['latitude'] = $gps['lat'];
                $tmp['longitude'] = $gps['lon'];
            } elseif (
                preg_match('/google.fr.*@(?P<latitude>[0-9.]+),(?P<longitude>[0-9.]+)/i', $tmp['link'], $results)
            ) {
                // $tmp['google_link'] = $tmp['latitude'];
                $tmp['latitude'] = $results['latitude'];
                $tmp['longitude'] = $results['longitude'];
            }
            $data[] = $tmp;
        }

        return $data;
    }

    protected function getGPSCoord($row)
    {
        $query = (!empty($row['street']) ? $row['street'] . ' ' : '')
            . (!empty($row['zip']) ? $row['zip'] . ' ' : '')
            . (!empty($row['city']) ? $row['city'] . ' ' : '')
            . ' France';

        $url = 'http://nominatim.openstreetmap.org/search?' . http_build_query(array(
            'format' => 'json',
            'q' => $query
        ));

        $ctx = stream_context_create(array('http'=>
            array(
                'timeout' => 5,  //100 Seconds is 20 Minutes
            )
        ));
        try {
            $searchContent = file_get_contents($url, false, $ctx);
        } catch (\Exception $e) {
            return false;
        }
        $json = json_decode($searchContent, true);
        if (empty($json)) {
            return false;
        }

        return $json[0];
    }

    protected function getOwnerId($row)
    {
        $canCreateContact = (
            !empty($row['contact_firstname']) ||
            !empty($row['contact_lastname'])
        );

        if (empty($row['structure_name'])) {
            if (!$canCreateContact) {
                // on se casse car on ne peut pas créer la structure
                return null;
            }
            $row['structure_name'] = $row['contact_firstname'] . ' ' .
                $row['contact_lastname'];
        }
        $structId = 2; // autre
        if (!empty($row['structure_type'])) {
            $structId = StructureType::firstOrCreate([
                'name' => $row['structure_type']
            ])->id;
        }

        $contactId = null;
        if ($canCreateContact) {
            $contactId = Contact::firstOrCreate([
                'first_name' => $row['contact_firstname'],
                'last_name' => $row['contact_lastname'],
                'email' => $row['contact_email'],
                'phone' => $row['contact_phone'],
            ])->id;
        }

        return Structure::firstOrCreate([
            'name' => $row['structure_name'],
            'type_id' => $structId,
            'contact_id' => $contactId
        ])->id;
    }
}
