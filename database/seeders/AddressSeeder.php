<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Municipality;
use App\Models\Barangay;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CALABARZON (Region IV-A) sample data
        $data = [
            'Cavite' => [
                'Tagaytay City' => ['Silang Junction North', 'Silang Junction South', 'San Jose', 'Maitim 2nd East'],
                'Dasmariñas' => ['Burol 1', 'Burol 2', 'Emiliano Tria Tirona', 'Paliparan 1', 'Paliparan 2'],
                'Trece Martires City' => ['Aguado', 'Cabuco', 'Cabezas', 'De Ocampo', 'Dela Paz', 'Don Jose'],
            ],
            'Laguna' => [
                'San Pablo City' => ['Barangay I-A', 'Barangay II-B', 'Barangay III-C', 'Barangay IV-D'],
                'Calamba' => ['Canlubang', 'Real', 'Parian', 'Batino', 'Palo Alto'],
            ],
            'Batangas' => [
                'Lipa City' => ['Antipolo del Norte', 'Antipolo del Sur', 'Balintawak', 'Sabang'],
                'Batangas City' => ['Alangilan', 'Balagtas', 'Bolbok', 'Pallocan West'],
            ],
            'Rizal' => [
                'Antipolo' => ['Cupang', 'Dalig', 'Inarawan', 'San Isidro'],
                'Taytay' => ['Dolores', 'San Juan', 'Santa Ana', 'San Isidro'],
            ],
            'Quezon' => [
                'Lucena City' => ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4'],
                'Tayabas' => ['Anos', 'Baguio', 'Bukal', 'Ipil'],
            ],
        ];

        foreach ($data as $provinceName => $municipalities) {
            $province = Province::firstOrCreate(['name' => $provinceName]);

            foreach ($municipalities as $municipalityName => $barangays) {
                $municipality = Municipality::firstOrCreate([
                    'name' => $municipalityName,
                    'province_id' => $province->id
                ]);

                foreach ($barangays as $barangayName) {
                    Barangay::firstOrCreate([
                        'name' => $barangayName,
                        'municipality_id' => $municipality->id
                    ]);
                }
            }
        }
    }
}
