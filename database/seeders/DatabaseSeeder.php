<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RolesAndPermissionsSeeder::class);

        // Seed email configuration
        $this->call(EmailConfigurationSeeder::class);

        // Seed core site content
        $this->call([
            ServiceSeeder::class,
            SiteSettingSeeder::class,
            ProcessStepSeeder::class,
            InvestmentTierSeeder::class,
            TestimonialSeeder::class,
        ]);

        // Seed test data
        $this->call(TestDataSeeder::class);
    }
}
