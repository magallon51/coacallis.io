<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Cliente']);

        Permission::create(['name' => 'welcome'])->syncRoles([$role1, $role2]);

        Permission::create(['name' => 'users.index'])->syncRoles([$role1]);
        Permission::create(['name' => 'users.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'users.update'])->syncRoles([$role1]);

        //Permisos
        Permission::create(['name' => 'ubicaciones.index'])->syncRoles($role1);
        Permission::create(['name' => 'ubicaciones.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'ubicaciones.edit'])->assignRole($role1);
        Permission::create(['name' => 'ubicaciones.destroy'])->assignRole($role1);

        Permission::create(['name' => 'hoteles.index'])->assignRole($role1);
        Permission::create(['name' => 'hoteles.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'hoteles.edit'])->assignRole($role1);
        Permission::create(['name' => 'hoteles.destroy'])->assignRole($role1);

        Permission::create(['name' => 'habitaciones.index'])->assignRole($role1);
        Permission::create(['name' => 'habitaciones.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'habitaciones.edit'])->assignRole($role1);
        Permission::create(['name' => 'habitaciones.destroy'])->assignRole($role1);

        Permission::create(['name' => 'asignahabitaciones.index'])->assignRole($role1);
        Permission::create(['name' => 'asignahabitaciones.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'asignahabitaciones.edit'])->assignRole($role1);
        Permission::create(['name' => 'asignahabitaciones.destroy'])->assignRole($role1);

        Permission::create(['name' => 'tarjetas.index'])->assignRole($role1);
        Permission::create(['name' => 'tarjetas.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'tarjetas.edit'])->assignRole($role1);
        Permission::create(['name' => 'tarjetas.destroy'])->assignRole($role1);

        Permission::create(['name' => 'personas.index'])->assignRole($role1);
        Permission::create(['name' => 'personas.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'personas.edit'])->assignRole($role1);
        Permission::create(['name' => 'personas.destroy'])->assignRole($role1);

        Permission::create(['name' => 'reservaciones.index'])->assignRole($role1);
        Permission::create(['name' => 'reservaciones.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'reservaciones.edit'])->assignRole($role1);
        Permission::create(['name' => 'reservaciones.destroy'])->assignRole($role1);

        Permission::create(['name' => 'tickets.index'])->assignRole($role1);
        Permission::create(['name' => 'tickets.create'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'tickets.edit'])->assignRole($role1);
        Permission::create(['name' => 'tickets.destroy'])->assignRole($role1);
    }
}
