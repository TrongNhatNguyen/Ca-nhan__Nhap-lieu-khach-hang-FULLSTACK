<?php

/**
 * Chạy lệnh để tạo file Seeder này:
 * C:\wamp\bin\php\php8.0.26\php.exe spark make:seeder Admin/KhachhangSeeder
 * 
 *  chạy lệnh để nạp duy nhất file này:
 *  C:\wamp\bin\php\php8.0.26\php.exe spark db:seed App\Database\Seeds\Admin\KhachhangSeeder
 * 
 *  Vào `app/Config/Database.php` để kết nối với CSDL localhost
 */

namespace App\Database\Seeds\Admin;

use CodeIgniter\Database\Seeder;

class KhachhangSeeder extends Seeder
{
    protected $db;
    protected $table = 'khachhangs';

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function run()
    {
        for ($i = 0; $i < 20; $i++) {
            $data = [
                'name'       => 'Khách Hàng ' . $i,  // bạn có thể sử dụng thư viện Faker để tạo dữ liệu mẫu tự nhiên hơn
                'phone'      => '09' . random_int(10000000, 99999999),  // tạo số điện thoại mẫu
                'points'     => random_int(0, 1000),  // tạo điểm mẫu từ 0 đến 1000
                'status'     => (rand(0, 1) == 0) ? 'active' : 'inactive', // ngẫu nhiên chọn trạng thái
                'created_at' => date('Y-m-d H:i:s'),  // thời gian hiện tại
            ];

            $this->db->table($this->table)->insert($data);
        }
    }
}
