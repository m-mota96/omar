<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $role = [
            [
               'role'=>'admin',
            ],
            [
                'role'=>'customer',
            ],
        ];
        foreach ($role as $key => $value) {
            Role::create($value);
        }
    }
}
