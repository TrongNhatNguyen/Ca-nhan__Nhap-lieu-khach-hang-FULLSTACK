<?php

/**
 *  Cấu hình Model Admin users để nạp dl vào DB,
 *  Vào `app/Config/Database.php` để kết nối với CSDL localhost
 * 
 *  Quy tắc tự viết trong: `App\CustomValidators\Models\CustomRules.php`
 *  và đăng kí nó ở: `App\Config\Validation.php` -> biến $ruleSets
 */

namespace App\Models\Admin;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admins';  // `app\Database\Migrations\Admin\2023-09-22-092214_CreateAdminsTable`
    protected $primaryKey = 'id'; // Khóa chính

    protected $returnType = 'array';   // Kiểu dữ liệu trả về, bạn cũng có thể sử dụng 'object'

    protected $useSoftDeletes = false; // Bật/tắt soft deletes
    protected $allowedFields = ['username', 'password', 'email', 'full_name', 'role', 'created_at', 'updated_at'];  // Các trường bạn muốn cho phép thao tác

    // Ngày giờ:
    protected $useTimestamps = false; // true thì tự động nạp giá trị vào db
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // =================================================================

    /**------------------------------
     * Hàm kiểm tra đăng nhập
     */
    public function checkLogin($username, $password)
    {
        $user = $this->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user['id'];
        }

        return false;
    }


    /**------------------------------
     * Tìm admin account bằng ID
     */
    public function getByID($id)
    {
        return $this->find($id);
    }
}
