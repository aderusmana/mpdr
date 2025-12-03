<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Master\Department;
use App\Models\Master\Level;
use App\Models\Master\Position as MasterPosition;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
         // Create Permissions
         Permission::firstOrCreate(['name' => 'view role']);
         Permission::firstOrCreate(['name' => 'create role']);
         Permission::firstOrCreate(['name' => 'update role']);
         Permission::firstOrCreate(['name' => 'delete role']);

         Permission::firstOrCreate(['name' => 'view permission']);
         Permission::firstOrCreate(['name' => 'create permission']);
         Permission::firstOrCreate(['name' => 'update permission']);
         Permission::firstOrCreate(['name' => 'delete permission']);

         Permission::firstOrCreate(['name' => 'view user']);
         Permission::firstOrCreate(['name' => 'create user']);
         Permission::firstOrCreate(['name' => 'update user']);
         Permission::firstOrCreate(['name' => 'delete user']);

         Permission::firstOrCreate(['name' => 'view product']);
         Permission::firstOrCreate(['name' => 'create product']);
         Permission::firstOrCreate(['name' => 'update product']);
         Permission::firstOrCreate(['name' => 'delete product']);

         Permission::firstOrCreate(['name' => 'view finance']);
         Permission::firstOrCreate(['name' => 'create finance']);
         Permission::firstOrCreate(['name' => 'update finance']);
         Permission::firstOrCreate(['name' => 'delete finance']);

         Permission::firstOrCreate(['name' => 'view department']);
         Permission::firstOrCreate(['name' => 'create department']);
         Permission::firstOrCreate(['name' => 'update department']);
         Permission::firstOrCreate(['name' => 'delete department']);

         Permission::firstOrCreate(['name' => 'view position']);
         Permission::firstOrCreate(['name' => 'create position']);
         Permission::firstOrCreate(['name' => 'update position']);
         Permission::firstOrCreate(['name' => 'delete position']);

         Permission::firstOrCreate(['name' => 'view level']);
         Permission::firstOrCreate(['name' => 'create level']);
         Permission::firstOrCreate(['name' => 'update level']);
         Permission::firstOrCreate(['name' => 'delete level']);

         Permission::firstOrCreate(['name' => 'view dashboard Finance']);
         Permission::firstOrCreate(['name' => 'view dashboard Sales & Marketing']);
         Permission::firstOrCreate(['name' => 'view dashboard Supply Chain']);
         Permission::firstOrCreate(['name' => 'view dashboard HCD']);
         Permission::firstOrCreate(['name' => 'view dashboard Engineering & Maintainance']);
         Permission::firstOrCreate(['name' => 'view dashboard Manufacturing']);
         Permission::firstOrCreate(['name' => 'view dashboard QM & HSE']);
         Permission::firstOrCreate(['name' => 'view dashboard R&D']);

         //create departements

        Department::firstOrCreate(['department_name' => 'Engineering & Maintainance']);
        Department::firstOrCreate(['department_name' => 'Finance Admin']);
        Department::firstOrCreate(['department_name' => 'HCD']);
        Department::firstOrCreate(['department_name' => 'Manufacturing']);
        Department::firstOrCreate(['department_name' => 'QM & HSE']);
        Department::firstOrCreate(['department_name' => 'R&D']);
        Department::firstOrCreate(['department_name' => 'Sales & Marketing']);
        Department::firstOrCreate(['department_name' => 'Supply Chain']);
        Department::firstOrCreate(['department_name' => 'Secret']);

         //create Level
         Level::firstOrCreate(['level_name' => 'I']);
         Level::firstOrCreate(['level_name' => 'II']);
         Level::firstOrCreate(['level_name' => 'III']);
         Level::firstOrCreate(['level_name' => 'IV']);
         Level::firstOrCreate(['level_name' => 'V']);
         Level::firstOrCreate(['level_name' => 'VI']);
         Level::firstOrCreate(['level_name' => 'VII']);
         Level::firstOrCreate(['level_name' => 'Developer']);

          // department finance and admin
        MasterPosition::firstOrCreate(['position_name' => 'Developer','level_id' => 8,'department_id' => 9 ]);
        MasterPosition::firstOrCreate(['position_name' => 'General Manager','level_id' => 7,'department_id' => 1 ]);
        MasterPosition::firstOrCreate(['position_name' => 'Department Head Finance','level_id' => 6,'department_id' =>2 ]);
        MasterPosition::firstOrCreate(['position_name' => 'Assistant Manager MIS','level_id' => 5,'department_id' =>2 ]);
        MasterPosition::firstOrCreate(['position_name' => 'Manager Accounting & Tax','level_id' => 5,'department_id' =>2 ]);
        MasterPosition::firstOrCreate(['position_name' => 'Manager Bussiness Opr. Control','level_id' => 5,'department_id' => 2]);
        MasterPosition::firstOrCreate(['position_name' => 'IT Support','level_id' => 3,'department_id' =>2 ]);
        MasterPosition::firstOrCreate(['position_name' => 'Web Developer','level_id' => 3,'department_id' =>2 ]);
        MasterPosition::firstOrCreate(['position_name' => 'Supervisor - MIS ','level_id' => 4,'department_id' =>2 ]);

        // department suppply chain
        MasterPosition::firstOrCreate(['position_name' => 'Department Head Supply Chain','level_id' => 6,'department_id' =>8 ]);
        MasterPosition::firstOrCreate(['position_name' => 'Manager - Logistic','level_id' => 6,'department_id' =>8]);
        MasterPosition::firstOrCreate(['position_name' => 'Manager - PPIC','level_id' => 6,'department_id' =>8 ]);
        MasterPosition::firstOrCreate(['position_name' => 'Manager - Purchasing','level_id' => 6,'department_id' =>8 ]);
        MasterPosition::firstOrCreate(['position_name' => 'Supervisor - Export','level_id' => 5,'department_id' =>8 ]);


        // Create Roles
        $superAdminRole = Role::create(['name' => 'super-admin']); //as super-admin
        $adminRole = Role::create(['name' => 'admin']);
        $staffRole = Role::create(['name' => 'staff']);
        $userRole = Role::create(['name' => 'user']);

        // Lets give all permission to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);

        // Let's give few permissions to admin role.
        $adminRole->givePermissionTo(['create role', 'view role', 'update role']);
        $adminRole->givePermissionTo(['create permission', 'view permission']);
        $adminRole->givePermissionTo(['create user', 'view user', 'update user']);
        $adminRole->givePermissionTo(['create product', 'view product', 'update product']);

        $staffRole->givePermissionTo(['view dashboard Finance']);
        $userRole->givePermissionTo(['view dashboard Sales & Marketing']);


        // Let's Create User and assign Role to it.

        $superAdminUser = User::firstOrCreate([
                    'email' => 'superadmin@gmail.com',
                ], [
                    'name' => 'Super Admin',
                    'nik' => 'AG11111',
                    'email' => 'superadmin@gmail.com',
                    'password' => Hash::make ('password'),
                    'email_verified_at' => now(),
                    'position_id' => 1
                ]);

        $superAdminUser->assignRole($superAdminRole);


        $adminUser = User::firstOrCreate([
                            'email' => 'admin@gmail.com'
                        ], [
                            'name' => 'Admin',
                            'nik' => 'AG22222',
                            'email' => 'admin@gmail.com',
                            'password' => Hash::make ('password'),
                            'email_verified_at' => now(),
                            'position_id' => 3
                        ]);

        $adminUser->assignRole($adminRole);


        $staffUser = User::firstOrCreate([
                            'email' => 'staff@gmail.com',
                        ], [
                            'name' => 'Staff',
                            'nik' => 'AG33333',
                            'email' => 'staff@gmail.com',
                            'password' => Hash::make('password'),
                            'email_verified_at' => now(),
                            'position_id' => 3
                        ]);

        $staffUser->assignRole($staffRole);
    }
}
