<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Seeder;

/**
 * Seeder pour créer les 24 zones (un par gouvernorat tunisien).
 */
class ZoneSeeder extends Seeder
{
    /**
     * Les 24 gouvernorats de Tunisie avec ville principale et coordonnées.
     */
    private array $zones = [
        ['nom' => 'Zone Tunis Centre',      'ville' => 'Tunis',           'gouvernorat' => 'Tunis',       'code_postal' => '1000', 'latitude' => 36.8190,  'longitude' => 10.1658,  'description' => 'Capitale de la Tunisie, centre administratif et économique.'],
        ['nom' => 'Zone Ariana',            'ville' => 'Ariana',          'gouvernorat' => 'Ariana',      'code_postal' => '2080', 'latitude' => 36.8665,  'longitude' => 10.1647,  'description' => 'Gouvernorat de la banlieue nord de Tunis.'],
        ['nom' => 'Zone Ben Arous',         'ville' => 'Ben Arous',       'gouvernorat' => 'Ben Arous',   'code_postal' => '2013', 'latitude' => 36.7503,  'longitude' => 10.2282,  'description' => 'Gouvernorat industriel au sud-est de Tunis.'],
        ['nom' => 'Zone Manouba',           'ville' => 'Manouba',         'gouvernorat' => 'Manouba',     'code_postal' => '2010', 'latitude' => 36.8092,  'longitude' => 10.0989,  'description' => 'Gouvernorat à l\'ouest de Tunis.'],
        ['nom' => 'Zone Nabeul',            'ville' => 'Nabeul',          'gouvernorat' => 'Nabeul',      'code_postal' => '8000', 'latitude' => 36.4561,  'longitude' => 10.7376,  'description' => 'Cap Bon, connue pour son artisanat et ses plages.'],
        ['nom' => 'Zone Zaghouan',          'ville' => 'Zaghouan',        'gouvernorat' => 'Zaghouan',    'code_postal' => '1100', 'latitude' => 36.4029,  'longitude' => 10.1434,  'description' => 'Zone montagneuse, source d\'eau potable historique.'],
        ['nom' => 'Zone Bizerte',           'ville' => 'Bizerte',         'gouvernorat' => 'Bizerte',     'code_postal' => '7000', 'latitude' => 37.2744,  'longitude' => 9.8739,   'description' => 'Port méditerranéen au nord de la Tunisie.'],
        ['nom' => 'Zone Béja',              'ville' => 'Béja',            'gouvernorat' => 'Béja',        'code_postal' => '9000', 'latitude' => 36.7256,  'longitude' => 9.1817,   'description' => 'Région agricole fertile, grenier à blé de la Tunisie.'],
        ['nom' => 'Zone Jendouba',          'ville' => 'Jendouba',        'gouvernorat' => 'Jendouba',    'code_postal' => '8100', 'latitude' => 36.5011,  'longitude' => 8.7803,   'description' => 'Gouvernorat frontalier avec l\'Algérie, région forestière.'],
        ['nom' => 'Zone Le Kef',            'ville' => 'Le Kef',          'gouvernorat' => 'Le Kef',      'code_postal' => '7100', 'latitude' => 36.1826,  'longitude' => 8.7149,   'description' => 'Région haute du nord-ouest tunisien.'],
        ['nom' => 'Zone Siliana',           'ville' => 'Siliana',         'gouvernorat' => 'Siliana',     'code_postal' => '6100', 'latitude' => 36.0844,  'longitude' => 9.3709,   'description' => 'Gouvernorat agricole du centre-nord.'],
        ['nom' => 'Zone Sousse',            'ville' => 'Sousse',          'gouvernorat' => 'Sousse',      'code_postal' => '4000', 'latitude' => 35.8245,  'longitude' => 10.6346,  'description' => 'Ville côtière touristique, troisième ville de Tunisie.'],
        ['nom' => 'Zone Monastir',          'ville' => 'Monastir',        'gouvernorat' => 'Monastir',    'code_postal' => '5000', 'latitude' => 35.7643,  'longitude' => 10.8113,  'description' => 'Zone côtière avec aéroport international.'],
        ['nom' => 'Zone Mahdia',            'ville' => 'Mahdia',          'gouvernorat' => 'Mahdia',      'code_postal' => '5100', 'latitude' => 35.5047,  'longitude' => 11.0622,  'description' => 'Ville côtière historique, ancienne capitale de la Tunisie.'],
        ['nom' => 'Zone Sfax',              'ville' => 'Sfax',            'gouvernorat' => 'Sfax',        'code_postal' => '3000', 'latitude' => 34.7400,  'longitude' => 10.7600,  'description' => 'Deuxième ville de Tunisie, capitale économique du sud.'],
        ['nom' => 'Zone Kairouan',          'ville' => 'Kairouan',        'gouvernorat' => 'Kairouan',    'code_postal' => '3100', 'latitude' => 35.6781,  'longitude' => 10.0963,  'description' => 'Quatrième ville sainte de l\'Islam, patrimoine UNESCO.'],
        ['nom' => 'Zone Kasserine',         'ville' => 'Kasserine',       'gouvernorat' => 'Kasserine',   'code_postal' => '1200', 'latitude' => 35.1676,  'longitude' => 8.8306,   'description' => 'Zone montagneuse frontalière avec l\'Algérie.'],
        ['nom' => 'Zone Sidi Bouzid',       'ville' => 'Sidi Bouzid',     'gouvernorat' => 'Sidi Bouzid', 'code_postal' => '9100', 'latitude' => 35.0382,  'longitude' => 9.4849,   'description' => 'Région agricole du centre tunisien.'],
        ['nom' => 'Zone Gabès',             'ville' => 'Gabès',           'gouvernorat' => 'Gabès',       'code_postal' => '6000', 'latitude' => 33.8881,  'longitude' => 10.0975,  'description' => 'Gouvernorat côtier au bord du golfe de Gabès.'],
        ['nom' => 'Zone Medenine',          'ville' => 'Medenine',        'gouvernorat' => 'Medenine',    'code_postal' => '4100', 'latitude' => 33.3549,  'longitude' => 10.5055,  'description' => 'Gouvernorat du sud-est incluant Djerba et Zarzis.'],
        ['nom' => 'Zone Tataouine',         'ville' => 'Tataouine',       'gouvernorat' => 'Tataouine',   'code_postal' => '3200', 'latitude' => 32.9297,  'longitude' => 10.4519,  'description' => 'Gouvernorat du grand sud, paysages désertiques.'],
        ['nom' => 'Zone Gafsa',             'ville' => 'Gafsa',           'gouvernorat' => 'Gafsa',       'code_postal' => '2100', 'latitude' => 34.4250,  'longitude' => 8.7842,   'description' => 'Région minière phosphatière du sud-ouest.'],
        ['nom' => 'Zone Tozeur',            'ville' => 'Tozeur',          'gouvernorat' => 'Tozeur',      'code_postal' => '2200', 'latitude' => 33.9197,  'longitude' => 8.1335,   'description' => 'Oasis du Sahara, tourisme désertique.'],
        ['nom' => 'Zone Kébili',            'ville' => 'Kébili',          'gouvernorat' => 'Kébili',      'code_postal' => '4200', 'latitude' => 33.7074,  'longitude' => 8.9689,   'description' => 'Zone désertique avec le grand erg oriental et Douz.'],
    ];

    public function run(): void
    {
        foreach ($this->zones as $zone) {
            Zone::updateOrCreate(
                ['ville' => $zone['ville'], 'gouvernorat' => $zone['gouvernorat']],
                array_merge($zone, ['actif' => true])
            );
        }

        $this->command->info('✅ 24 zones tunisiennes créées.');
    }
}
