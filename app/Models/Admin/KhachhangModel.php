<?php

/**
 *  Cấu hình Model khách hàng để nạp dl vào DB,
 *  Vào `app/Config/Database.php` để kết nối với CSDL localhost
 * 
 *  Quy tắc tự viết trong: `App\CustomValidators\Models\CustomRules.php`
 *  và đăng kí nó ở: `App\Config\Validation.php` -> biến $ruleSets
 */

namespace App\Models\Admin;

use CodeIgniter\Model;

class KhachhangModel extends Model
{
    protected $table      = 'khachhangs';  // `app\Database\Migrations\Admin\2023-08-27-175056_CreateKhachhangsTable.php`
    protected $primaryKey = 'id';         // Khóa chính

    protected $returnType = 'array';     // Kiểu dữ liệu trả về, bạn cũng có thể sử dụng 'object'

    protected $useSoftDeletes = false;    // Bật/tắt soft deletes

    protected $allowedFields = ['name', 'phone', 'points', 'status', 'created_at', 'updated_at'];  // Các trường bạn muốn cho phép thao tác

    // Ngày giờ:
    protected $useTimestamps = false; // true thì tự động nạp giá trị vào db
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $skipValidation     = false; // Có bỏ qua quá trình xác thực hay không
    protected $validationRules    = [      // Các quy tắc xác thực cho việc thêm/sửa
        'name'     => 'required|rule_name_unicode|min_length[3]|max_length[20]',
        'phone'    => 'required|numeric|min_length[9]|max_length[12]',
        'points'   => 'required|numeric',
    ];
    protected $validationMessages = [      // Thông báo tùy chỉnh cho xác thực
        'name' => [
            'required'            => 'Sorry. That field is required.',
            'rule_name_unicode'   => 'Sorry. That field is not valid.',
            'min_length'          => 'Sorry. That field is not valid.',
        ],
        'phone' => [
            'required'            => 'Sorry. That field is required.',
            'numeric'             => 'Sorry. That field is not valid.',
            'min_length'          => 'Sorry. That field is not valid.',
        ],
        'points' => [
            'required'            => 'Sorry. That field is required.',
            'numeric'             => 'Sorry. That field is not valid.',
        ],
    ];

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

        if (isset($search[0], $search[1])) {
            $this->like($search[0], $search[1]);
        } elseif ($search) {
            throw new \InvalidArgumentException('chưa đảm bảo đủ 2 giá trị trong Search.');
        }

        return $this->countAllResults();
    }



    /**------------------------------
     * Thêm mới khách hàng
     */
    public function themMoiKhachHang($data)
    {
        // Nạp các Quy Tắc & thông báo đã được khai báo ở bên trên:
        $this->validation->setRules($this->validationRules, $this->validationMessages);

        // Kiểm tra các dữ liệu truyền vào có đúng Quy Tắc:
        $isValid = $this->validation->run([
            'name'   => $data['name'],
            'phone'  => $data['phone'],
            'points' => $data['points'],
        ]);

        // Nếu dữ liệu vi phạm, trả về thông báo lỗi:
        if (!$isValid) {
            $errors = $this->validation->getErrors();
            throw new \InvalidArgumentException($errors[1]);
        }

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
