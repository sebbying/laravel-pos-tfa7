<?php
namespace Database\Seeders;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['full_name' => 'Maria Santos', 'email' => 'maria@example.com', 'phone' => '09171234567', 'address' => 'Quezon City'],
            ['full_name' => 'Juan Dela Cruz', 'email' => 'juan@example.com', 'phone' => '09181234567', 'address' => 'Manila'],
        ] as $customer) {
            Customer::updateOrCreate(['email' => $customer['email']], $customer);
        }
        foreach ([
            ['username' => 'cashier01', 'full_name' => 'Ana Reyes', 'email' => 'ana@example.com'],
            ['username' => 'manager01', 'full_name' => 'Carlo Garcia', 'email' => 'carlo@example.com'],
        ] as $user) {
            User::updateOrCreate(['username' => $user['username']], $user);
        }
    }
}
