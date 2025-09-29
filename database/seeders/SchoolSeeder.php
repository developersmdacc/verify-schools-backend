<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Country;
use App\Models\Province;
use App\Models\City;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // Get South Africa country
        $country = Country::where('name', 'South Africa')->first();

        if (!$country) {
            $this->command->info('South Africa not found. Make sure LocationSeeder ran first.');
            return;
        }

        $schoolsData = [
            'Gauteng' => [
                'Johannesburg' => [
                    ['name' => 'Parktown High School', 'school_type' => 'High'],
                    ['name' => 'St Stithians College', 'school_type' => 'High']
                ],
                'Pretoria' => [
                    ['name' => 'Pretoria Boys High School', 'school_type' => 'High'],
                    ['name' => 'Afrikaanse Hoër Seunskool', 'school_type' => 'High']
                ]
            ],
            'Western Cape' => [
                'Cape Town' => [
                    ['name' => 'Rondebosch Boys High School', 'school_type' => 'High'],
                    ['name' => 'Wynberg Boys\' High School', 'school_type' => 'High']
                ],
                'Stellenbosch' => [
                    ['name' => 'Paul Roos Gymnasium', 'school_type' => 'High']
                ]
            ],
            'KwaZulu-Natal' => [
                'Durban' => [
                    ['name' => 'Westville Boys High School', 'school_type' => 'High']
                ]
            ]
        ];

        foreach ($schoolsData as $provinceName => $cities) {
            $province = Province::where('name', $provinceName)->where('country_id', $country->id)->first();
            if (!$province) continue;

            foreach ($cities as $cityName => $schools) {
                $city = City::where('name', $cityName)->where('province_id', $province->id)->first();
                if (!$city) continue;

                foreach ($schools as $schoolData) {
                    School::firstOrCreate([
                        'name' => $schoolData['name'],
                        'province_id' => $province->id,
                        'city_id' => $city->id,
                        'country_id' => $country->id
                    ], [
                        'school_type' => $schoolData['school_type'],
                        'is_verified' => true
                    ]);
                }
            }
        }
    }
}
