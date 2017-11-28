<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Contact;
use App\StructureType;
use App\Structure;
use App\Roof;
use App\Roof\PurchaseCategory;
use App\Roof\Type;
use App\Roof\Tilt;
use App\Roof\SouthOrientation;
use App\Department;
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
        $this->info('start parse');
        $rows = $this->parseCsv();

        $this->info('parse finish start insert');

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

            Roof::create([
                'name' => $row['street'] . ', ' . $row['city'],
                'probability' => $row['probability'],
                'structure_id' => $struct->id,
                'square_area' => $row['potential_m2'],
                'power_max' => $row['estimate_hight'],
                'power_min' => $row['estimate_low'],
                'purchase_category_id' => $purchaseCategory->id,
                'type_id' => $typeId,
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
                // 'department_id' => Department::first([
                    // 'name' => $row['department']
                // ])->id,
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
                'owner_id' => Structure::firstOrCreate([
                    'name' => $row['structure_name'],
                    'type_id' => StructureType::firstOrCreate([
                        'name' => $row['structure_type']
                    ])->id,
                    'contact_id' => Contact::firstOrCreate([
                        'first_name' => $row['contact_firstname'],
                        'last_name' => $row['contact_lastname'],
                        'email' => $row['contact_email'],
                        'phone' => $row['contact_phone'],
                    ])->id
                ])->id,
            ]);
        }

        $this->info('created users from structure initiatrice');
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
            // 'id',
            'probability',
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
            $row = explode(';', $row);
            $tmp = array();
            foreach ($row as $k => $v) {
                if (!empty($keys[$k])) {
                    $tmp[$keys[$k]] = trim($v);
                }
            }

            if (empty($tmp['link'])) {
                print_r($tmp);
                echo 'no link here' . PHP_EOL;
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
