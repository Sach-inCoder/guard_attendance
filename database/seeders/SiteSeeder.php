<?php
namespace Database\Seeders;

use App\Models\Company;
use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['code' => 'DEMO001'],
            [
                'name' => 'Demo Client Company',
                'is_active' => true,
            ]
        );

        $sites = [
            [
                'name' => 'Head Office',
                'address' => 'Connaught Place, New Delhi, Delhi',
                'latitude' => 28.6315,
                'longitude' => 77.2167,
                'is_active' => true,
            ],
            [
                'name' => 'Warehouse Site',
                'address' => 'Okhla Industrial Area, New Delhi, Delhi',
                'latitude' => 28.5355,
                'longitude' => 77.2732,
                'is_active' => true,
            ],
            [
                'name' => 'Branch Office',
                'address' => 'Netaji Subhash Place, Pitampura, Delhi',
                'latitude' => 28.6942,
                'longitude' => 77.1526,
                'is_active' => true,
            ],
        ];

        foreach ($sites as $site) {
            Site::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'name' => $site['name'],
                ],
                $site
            );
        }
    }
}