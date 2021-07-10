<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ///ADMIN

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@email.co.id',
            'password' => bcrypt('qwerty'),
        ]);

        $role = Role::create(['name' => 'Admin']);
     
        $permissions = Permission::whereIn('id', [1,2,3,4,5,6])->pluck('id','id');

        $role->syncPermissions($permissions);
     
        $admin->assignRole([$role->id]);

        ///AGENT

        $agent = User::create([
            'name' => 'Agent',
            'email' => 'agent@email.co.id',
            'password' => bcrypt('12345678'),
        ]);

        $role = Role::create(['name' => 'Agent']);
     
        $permissions = Permission::whereIn('id', [2,7])->pluck('id','id');
   
        $role->syncPermissions($permissions);
     
        $agent->assignRole([$role->id]);
    }
}
