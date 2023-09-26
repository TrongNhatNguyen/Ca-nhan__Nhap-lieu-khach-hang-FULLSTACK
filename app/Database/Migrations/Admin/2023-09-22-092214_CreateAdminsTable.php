<?php

/**
 * Chạy lệnh để tạo file Migration này:
 * C:\wamp\bin\php\php8.0.26\php.exe spark migrate:create Admin/CreateAdminsTable
 * 
 *  chạy lệnh để nạp duy nhất file này:
 *  C:\wamp\bin\php\php8.0.26\php.exe spark migrate --path=App/Database/Migrations/Admin --only=CreateAdminsTable
 *  chạy lệnh: php spark migrate:rollback nếu muốn chạy lại lệnh trên
 * 
 *  Vào `app/Config/Database.php` để kết nối với CSDL localhost
 */

namespace App\Database\Migrations\Admin;

use CodeIgniter\Database\Migration;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username'    => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'password'    => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['superadmin', 'admin', 'moderator'],
                'default'    => 'admin',
            ],
            'created_at'  => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
            'updated_at'  => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('admins');
    }

    public function down()
    {
        $this->forge->dropTable('admins');
    }
}
