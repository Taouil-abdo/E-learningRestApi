<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // $randArray = [null ,1,2,3,4,5,10,15,30,35,40,45,50];
        // Category::factory(50)->create()->each(function ($category) use ($randArray){
        //     $category->parent_id = $randArray[rand(0,count($randArray)-1)];
        //     $category->save;
        // });

        

$admin = Role::create(['name' => 'admin']);
$teacher = Role::create(['name' => 'teacher']);
$student = Role::create(['name' => 'student']);

Permission::create(['name' => 'manage users']);
Permission::create(['name' => 'manage courses']);
Permission::create(['name' => 'enroll in courses']);


$admin->givePermissionTo(['manage users', 'manage courses']);
$teacher->givePermissionTo('manage courses');
$student->givePermissionTo('enroll in courses');

$user = User::find(1);
$user->assignRole('admin');

    }
}
