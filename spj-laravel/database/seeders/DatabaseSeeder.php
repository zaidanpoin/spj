<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call all seeders in order
        $this->call([
            UnorSeeder::class,
            UnitKerjaSeeder::class,
            SBMKonsumsiSeeder::class,
            WaktuKonsumsiSeeder::class,
            MAKSeeder::class,
            PPKSeeder::class,
            SBMHonorariumSeeder::class,
            // Ensure roles & permissions exist before creating users
            \Database\Seeders\RolePermissionSeeder::class,
            UserSeeder::class,
        ]);

        $this->command->info('✅ Database seeded successfully!');
    }
}
