<?php

use Carbon\Carbon as Carbon;
use database\DisablesForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class RoleTableSeeder.
 */
class RoleTableSeeder extends Seeder
{
    use DisablesForeignKeys;

    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        if (DB::connection()->getDriverName() == 'mysql') {
            DB::table(config('access.roles_table'))->truncate();
        } elseif (DB::connection()->getDriverName() == 'sqlite') {
            DB::statement('DELETE FROM '.config('access.roles_table'));
            DB::statement('UPDATE sqlite_sequence SET seq = 0 where name = '."'".config('access.roles_table')."'");
        } else {
            //For PostgreSQL or anything else
            DB::statement('TRUNCATE TABLE '.config('access.roles_table').' CASCADE');
        }

        $roles = [
            [
                'name'       => 'Administrator',
                'all'        => true,
                'sort'       => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'Executive',
                'all'        => false,
                'sort'       => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name'       => 'User',
                'all'        => false,
                'sort'       => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table(config('access.roles_table'))->insert($roles);

        $this->enableForeignKeys();
    }
}
