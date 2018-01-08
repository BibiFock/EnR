<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Roof;

class ExportController extends Controller
{

    public function index()
    {
        $roofs = Roof::all();

        $content = [[
            'id' => 'id',
            'name' => 'nom',
            'probability' => 'probabilité',
            'square_area' => 'surface',
            'power_max' => 'puissance max',
            'power_min' => 'puissance min',
            'erp' => 'ERP',
            'building_size' => 'taille du batiment',
            'perimeter_abf' => 'périmètre ABF',
            'remarks' => 'remarques',
            'inverter_location' => 'position de l\'onduleur',
            'inverter_distance' => 'distance de l\'onduleur',
            'street' => 'rue',
            'zip' => 'code postal',
            'city' => 'ville',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'slope' => 'pente de toit',
            'ground_square_area' => 'surface au sol du toit',
            'occupancy_rate' => 'taux d\'occupation',
            'south_orientation' => 'orientation sud',
            // relations
            'structure.name' => 'structure.nom',
            'structure.type.name' => 'structure.type',
            'structure.contact.first_name' => 'structure.contact.prénom',
            'structure.contact.last_name' => 'structure.contact.nom de famille',
            'structure.contact.phone' => 'structure.contact.téléphone',
            'structure.contact.email' => 'structure.contact.email',

            'purchaseCategory.name' => 'catégorie de tarif',
            'type.name' => 'type',
            'owner.name' => 'owner.nom',
            'owner.type.name' => 'owner.type',
            'owner.contact.first_name' => 'owner.contact.prénom',
            'owner.contact.last_name' => 'owner.contact.nom de famille',
            'owner.contact.phone' => 'owner.contact.téléphone',
            'owner.contact.email' => 'owner.contact.email',
        ]];

        foreach ($roofs as $roof) {
            $content[] = [
                $roof->id,
                $roof->name,
                $roof->probability,
                $roof->square_area,
                $roof->power_max,
                $roof->power_min,
                $roof->erp,
                $roof->building_size,
                $roof->perimeter_abf,
                $roof->remarks,
                $roof->inverter_location,
                $roof->inverter_distance,
                $roof->street,
                $roof->zip,
                $roof->city,
                $roof->latitude,
                $roof->longitude,
                $roof->slope,
                $roof->ground_square_area,
                $roof->occupancy_rate,
                $roof->south_orientation,
                // relations
                ( $roof->structure_id ? $roof->structure->name : '-' ),
                ( $roof->structure_id ? $roof->structure->type->name : '-' ),
                ( $roof->structure_id && $roof->structure->contact_id ? $roof->structure->contact->first_name : '-' ),
                ( $roof->structure_id && $roof->structure->contact_id ? $roof->structure->contact->last_name : '-' ),
                ( $roof->structure_id && $roof->structure->contact_id ? $roof->structure->contact->phone : '-' ),
                ( $roof->structure_id && $roof->structure->contact_id ? $roof->structure->contact->email : '-' ),

                ($roof->purchase_category_id ? $roof->purchaseCategory->name : '-' ),
                ($roof->type_id ? $roof->type->name : '-' ),
                ($roof->owner_id ? $roof->owner->name : '-' ),
                ($roof->owner_id ? $roof->owner->type->name : '-' ),
                ($roof->owner_id && $roof->owner->contact_id ? $roof->owner->contact->first_name : '-' ),
                ($roof->owner_id && $roof->owner->contact_id ? $roof->owner->contact->last_name : '-' ),
                ($roof->owner_id && $roof->owner->contact_id ? $roof->owner->contact->phone : '-' ),
                ($roof->owner_id && $roof->owner->contact_id ? $roof->owner->contact->email : '-' ),
            ];

        }

        $csvContent = implode( PHP_EOL, array_map(
            function ($row) {
                return '"' . implode('";"', $row) . '"';
            },
            $content
        ));

        return response($csvContent)
            ->header('Content-type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename=export-photovolt.csv')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
