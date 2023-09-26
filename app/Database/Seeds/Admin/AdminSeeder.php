<?php

/**
 * Chạy lệnh để tạo file Seeder này:
 * C:\wamp\bin\php\php8.0.26\php.exe spark make:seeder Admin/AdminSeeder
 * 
 *  chạy lệnh để nạp duy nhất file này:
 *  C:\wamp\bin\php\php8.0.26\php.exe spark db:seed App\Database\Seeds\Admin\AdminSeeder
 * 
 *  Vào `app/Config/Database.php` để kết nối với CSDL localhost
 */

namespace App\Database\Seeds\Admin;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    protected $db;
    protected $table = 'admins';

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function run()
    {
        for ($i = 1; $i <= 3; $i++) {
            // mật khẩu:
            $password       = 'admin' . $i;
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);  // mật khẩu đã được mã hóa

            // vai trò:
            $roles      = ['superadmin', 'admin', 'moderator'];
            $randomRole = $roles[array_rand($roles)];

            $data = [
                'username'   => 'admin' . $i,
                'password'   => $hashedPassword,
                'email'      => 'tkquantri' . $i . '@gmail.com',
                'full_name'  => 'Người Điều Hành ' . $i,
                'role'       => $randomRole,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->table($this->table)->insert($data);
        }
    }
}
