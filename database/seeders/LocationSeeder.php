<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Create South Africa
        $sa = Country::firstOrCreate([
            'name' => 'South Africa',
            'code' => 'ZA'
        ]);

        // Provinces of South Africa
        $provinces = [
            'Eastern Cape' => ['Port Elizabeth', 'East London', 'Mthatha'],
            'Free State' => ['Bloemfontein', 'Welkom'],
            'Gauteng' => ['Johannesburg', 'Pretoria', 'Soweto'],
            'KwaZulu-Natal' => ['Durban', 'Pietermaritzburg', 'Richards Bay'],
            'Limpopo' => ['Polokwane', 'Thohoyandou'],
            'Mpumalanga' => ['Nelspruit', 'Secunda'],
            'Northern Cape' => ['Kimberley', 'Upington'],
            'North West' => ['Mahikeng', 'Potchefstroom'],
            'Western Cape' => ['Cape Town', 'Stellenbosch', 'George'],
        ];

        foreach ($provinces as $provinceName => $cities) {
            $province = Province::firstOrCreate([
                'name' => $provinceName,
                'country_id' => $sa->id
            ]);

            foreach ($cities as $cityName) {
                City::firstOrCreate([
                    'name' => $cityName,
                    'province_id' => $province->id
                ]);
            }
        }
    }
}
