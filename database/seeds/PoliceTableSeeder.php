<?php

use Illuminate\Database\Seeder;
use Database\DisableForeignKeys;

/**
 * Class PoliceTableSeeder.
 */
class PoliceTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        $this->call(RangeTableSeeder::class);
        $this->call(DivisionTableSeeder::class);
        $this->call(DistrictTableSeeder::class);
        $this->call(CourtTableSeeder::class);
        $this->call(StationTableSeeder::class);
        $this->call(EmployablesTableSeeder::class);

        $this->enableForeignKeys();
    }
}
