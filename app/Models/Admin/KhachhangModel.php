<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class KhachhangModel extends Model
{
    protected $table      = 'khachhangs';  // Tên table
    protected $primaryKey = 'id';         // Khóa chính

    protected $returnType = 'array';     // Kiểu dữ liệu trả về, bạn cũng có thể sử dụng 'object'

    protected $useSoftDeletes = false;    // Bật/tắt soft deletes

    protected $allowedFields = ['name', 'phone', 'points', 'status', 'created_at', 'updated_at'];  // Các trường bạn muốn cho phép thao tác

    protected $useTimestamps = true;      // Bật timestamps
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [];   // Các quy tắc xác thực cho việc thêm/sửa
    protected $validationMessages = [];   // Thông báo tùy chỉnh cho xác thực
    protected $skipValidation     = false; // Có bỏ qua quá trình xác thực hay không

    // =================================================================

    /**
     * Lấy danh sách khách hàng
     */
    public function getKhachhangs($where = [], $fields = '*', $orderBy = 'id DESC', $limit = 10, $offset = 0)
    {
        $this->select($fields)->where($where)->orderBy($orderBy);
        return $this->findAll($limit, $offset);
    }

    /**
     * Đếm tất cả bản ghi
     */
    public function countTotal($where = [], $search = [])
    {
        $this->select('id')->where($where);

        if (!empty($search) && is_array($search) && count($search) === 2) {
            $this->like($search[0], $search[1]);
        }

        return $this->countAllResults();
    }

    // Bạn cũng có thể thêm các phương thức tùy chỉnh khác cho các truy vấn đặc biệt
}
