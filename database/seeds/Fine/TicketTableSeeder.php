<?php

use Database\TruncateTable;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Database\DisableForeignKeys;
use Illuminate\Support\Facades\DB;

/**
 * Class TicketTableSeeder.
 */
class TicketTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        $this->truncate(config('fine.tickets_table'));

        $tickets = [
            [
                'motorist_id'       => 1,
                'officer_id'        => 1,
                'station_id'        => 1,
                'vehicle_no'        => 'CAB-6578',
                'lat'               => 6.841878,
                'lng'               => 79.963467,
                'location'          => 'Kottawa town',
                'created_at'        => Carbon::now(),
                'total_amount'      => 500.00,
                'paid'              => true,
            ],
        ];

        DB::table(config('fine.tickets_table'))->insert($tickets);

        $this->enableForeignKeys();
    }
}
