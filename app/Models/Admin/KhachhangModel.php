<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class KhachhangModel extends Model
{
    protected $table      = 'khachhangs';  // `app\Database\Migrations\Admin\2023-08-27-175056_CreateKhachhangsTable.php`
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
    protected $validation;
    function __construct()
    {
        parent::__construct(); // hàm này cho phép viết câu truy vấn ở controller

        // nạp thư viện kiểm tra Quy tắc:
        $this->validation = \Config\Services::validation();
    }


    /**------------------------------
     * Lấy danh sách khách hàng
     */
    public function getKhachhangs($where = [], $fields = '*', $orderBy = 'id DESC', $limit = 10, $offset = 0)
    {
        $this->select($fields)->where($where)->orderBy($orderBy);
        return $this->findAll($limit, $offset);
    }


    /**------------------------------
     * Tìm khách hàng bằng ID
     */
    public function getByID($id)
    {
        return $this->find($id);
    }


    /**------------------------------
     * Đếm tổng số bản ghi
     */
    public function countTotal($where = [], $search = [])
    {
        $this->select('id')->where($where);

        if (!empty($search) && is_array($search) && count($search) === 2) {
            $this->like($search[0], $search[1]);
        }

        return $this->countAllResults();
    }


    /**------------------------------
     * Thêm mới khách hàng
     */
    public function themMoiKhachHang($data)
    {
        return $this->insert($data);
    }

    /**------------------------------
     * Cập nhật khách hàng
     */
    public function capNhatKhachHang($id, $data)
    {
        return $this->update($id, $data);
    }

    /**------------------------------
     * Xoá bỏ khách hàng
     */
    public function xoaKhachHang($id)
    {
        return $this->delete($id);
    }
}
