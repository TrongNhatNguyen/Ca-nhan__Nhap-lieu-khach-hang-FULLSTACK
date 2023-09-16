<?php

/**
 * Chạy lệnh để tạo file Migration này:
 * C:\wamp\bin\php\php8.0.26\php.exe spark migrate:create Admin/CreateKhachhangsTable
 * 
 *  chạy lệnh để nạp duy nhất file này:
 *  C:\wamp\bin\php\php8.0.26\php.exe spark migrate --path=App/Database/Migrations/Admin --only=CreateKhachhangsTable
 *  chạy lệnh: php spark migrate:rollback nếu muốn chạy lại lệnh trên
 * 
 *  Vào `app/Config/Database.php` để kết nối với CSDL localhost
 */

namespace App\Database\Migrations\Admin;

use CodeIgniter\Database\Migration;

class CreateKhachhangsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'points' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('khachhangs');
    }

    public function down()
    {
        $this->forge->dropTable('khachhangs');
    }
}
