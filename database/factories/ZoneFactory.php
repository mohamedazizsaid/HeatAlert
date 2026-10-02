<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory pour les zones tunisiennes.
 */
class ZoneFactory extends Factory
{
    /**
     * Les 24 gouvernorats de Tunisie avec leurs données géographiques.
     */
    private static array $gouvernorats = [
        ['gouvernorat' => 'Tunis',        'villes' => ['Tunis', 'La Goulette', 'Le Bardo', 'La Marsa'],       'lat_range' => [36.7, 36.9], 'lng_range' => [10.1, 10.3]],
        ['gouvernorat' => 'Ariana',       'villes' => ['Ariana', 'Raoued', 'Ettadhamen', 'Kalaat el-Andalous'], 'lat_range' => [36.8, 36.9], 'lng_range' => [10.1, 10.2]],
        ['gouvernorat' => 'Ben Arous',    'villes' => ['Ben Arous', 'Hammam-Lif', 'Ezzahra', 'Rades'],        'lat_range' => [36.6, 36.8], 'lng_range' => [10.2, 10.4]],
        ['gouvernorat' => 'Manouba',      'villes' => ['Manouba', 'Douar Hicher', 'Oued Ellil', 'Tebourba'], 'lat_range' => [36.7, 36.9], 'lng_range' => [9.9, 10.1]],
        ['gouvernorat' => 'Nabeul',       'villes' => ['Nabeul', 'Hammamet', 'Kélibia', 'Menzel Temime'],    'lat_range' => [36.3, 36.8], 'lng_range' => [10.6, 11.1]],
        ['gouvernorat' => 'Zaghouan',     'villes' => ['Zaghouan', 'Zriba', 'El Fahs', 'Nadhour'],           'lat_range' => [36.2, 36.7], 'lng_range' => [9.9, 10.3]],
        ['gouvernorat' => 'Bizerte',      'villes' => ['Bizerte', 'Menzel Bourguiba', 'Mateur', 'Ras Jebel'], 'lat_range' => [36.9, 37.3], 'lng_range' => [9.5, 10.1]],
        ['gouvernorat' => 'Béja',         'villes' => ['Béja', 'Medjez el-Bab', 'Testour', 'Nefza'],         'lat_range' => [36.6, 37.0], 'lng_range' => [8.9, 9.5]],
        ['gouvernorat' => 'Jendouba',     'villes' => ['Jendouba', 'Tabarka', 'Ghardimaou', 'Bou Salem'],    'lat_range' => [36.5, 37.0], 'lng_range' => [8.4, 9.1]],
        ['gouvernorat' => 'Le Kef',       'villes' => ['Le Kef', 'Dahmani', 'Sers', 'Kalaat Senan'],         'lat_range' => [35.8, 36.5], 'lng_range' => [8.5, 9.0]],
        ['gouvernorat' => 'Siliana',      'villes' => ['Siliana', 'Makthar', 'Rouhia', 'Bou Arada'],         'lat_range' => [35.9, 36.5], 'lng_range' => [9.1, 9.7]],
        ['gouvernorat' => 'Sousse',       'villes' => ['Sousse', 'Monastir', 'Msaken', 'Akouda'],            'lat_range' => [35.7, 36.0], 'lng_range' => [10.5, 10.8]],
        ['gouvernorat' => 'Monastir',     'villes' => ['Monastir', 'Ksar Hellal', 'Moknine', 'Jemmal'],      'lat_range' => [35.6, 35.9], 'lng_range' => [10.6, 11.0]],
        ['gouvernorat' => 'Mahdia',       'villes' => ['Mahdia', 'El Jem', 'Chebba', 'Ksour Essef'],         'lat_range' => [35.1, 35.7], 'lng_range' => [10.5, 11.1]],
        ['gouvernorat' => 'Sfax',         'villes' => ['Sfax', 'Sakiet Ezzit', 'Thyna', 'Agareb'],           'lat_range' => [34.5, 35.0], 'lng_range' => [10.5, 10.9]],
        ['gouvernorat' => 'Kairouan',     'villes' => ['Kairouan', 'Sbikha', 'El Alaa', 'Hajeb El Ayoun'],   'lat_range' => [35.3, 36.0], 'lng_range' => [9.5, 10.0]],
        ['gouvernorat' => 'Kasserine',    'villes' => ['Kasserine', 'Sbeitla', 'Feriana', 'Thala'],          'lat_range' => [35.0, 35.8], 'lng_range' => [8.4, 9.1]],
        ['gouvernorat' => 'Sidi Bouzid',  'villes' => ['Sidi Bouzid', 'Jelma', 'Meknassy', 'Bir El Hafey'],  'lat_range' => [34.7, 35.4], 'lng_range' => [9.3, 10.0]],
        ['gouvernorat' => 'Gabès',        'villes' => ['Gabès', 'El Hamma', 'Mareth', 'Ghannouch'],          'lat_range' => [33.6, 34.1], 'lng_range' => [9.9, 10.3]],
        ['gouvernorat' => 'Medenine',     'villes' => ['Medenine', 'Zarzis', 'Ben Gardane', 'Djerba'],       'lat_range' => [32.9, 33.6], 'lng_range' => [10.3, 11.2]],
        ['gouvernorat' => 'Tataouine',    'villes' => ['Tataouine', 'Ghomrassen', 'Bir Lahmar', 'Dehiba'],   'lat_range' => [31.5, 32.9], 'lng_range' => [9.5, 10.4]],
        ['gouvernorat' => 'Gafsa',        'villes' => ['Gafsa', 'El Ksar', 'Moularès', 'Redeyef'],           'lat_range' => [34.2, 34.6], 'lng_range' => [8.3, 9.2]],
        ['gouvernorat' => 'Tozeur',       'villes' => ['Tozeur', 'Degache', 'Nefta', 'Hazoua'],              'lat_range' => [33.5, 34.2], 'lng_range' => [7.9, 8.5]],
        ['gouvernorat' => 'Kébili',       'villes' => ['Kébili', 'Douz', 'Souk Lahad', 'El Faouar'],         'lat_range' => [32.5, 33.8], 'lng_range' => [8.7, 9.5]],
    ];

    public function definition(): array
    {
        $gov = $this->faker->randomElement(self::$gouvernorats);
        $ville = $this->faker->randomElement($gov['villes']);

        return [
            'nom'          => 'Zone ' . $ville,
            'ville'        => $ville,
            'gouvernorat'  => $gov['gouvernorat'],
            'code_postal'  => $this->faker->numerify('####'),
            'latitude'     => $this->faker->randomFloat(7, $gov['lat_range'][0], $gov['lat_range'][1]),
            'longitude'    => $this->faker->randomFloat(7, $gov['lng_range'][0], $gov['lng_range'][1]),
            'description'  => 'Zone météorologique de ' . $ville . ', gouvernorat de ' . $gov['gouvernorat'],
            'actif'        => $this->faker->boolean(90),
        ];
    }
}
