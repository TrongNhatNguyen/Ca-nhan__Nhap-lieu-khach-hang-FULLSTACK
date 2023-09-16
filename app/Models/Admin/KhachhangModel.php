<?php

/**
 *  Cấu hình Model khách hàng để nạp dl vào DB,
 *  Vào `app/Config/Database.php` để kết nối với CSDL localhost
 * 
 *  Quy tắc tự viết trong: `App\CustomValidators\CustomRules.php`
 */

namespace App\Models\Admin;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class KhachhangModel extends Model
{
    protected $table      = 'khachhangs'; // tên table đã tạo trong file `app\Database\Migrations\Admin\2023-08-27-175056_CreateKhachhangsTable.php`
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType    = 'array';
    protected $allowedFields = ['name', 'phone', 'points', 'status', 'created_at', 'updated_at'];

    // Ngày giờ:
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Khai báo các Quy Tắc & thông báo: alpha_numeric_space
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
    protected $validationRules      = [
        'name'     => 'required|rule_name_unicode|min_length[3]|max_length[20]',
        'phone'    => 'required|numeric|min_length[9]|max_length[12]',
        'points'   => 'required|numeric',
    ];
    protected $validationMessages = [
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
    protected $db;
    protected $validation;
    protected $time;
    function __construct()
    {
        $this->db = \Config\Database::connect();

        // nạp thư viện kiểm tra Quy tắc:
        $this->validation = \Config\Services::validation();

        // Nạp hàm lấy hoặc format thời gian:
        $this->time = new Time();
    }


    /// XÂY DỰNG HÀM LẤY TẤT CẢ DANH SÁCH:
    public function getAll($sortField = 'created_at', $sortDirection = 'desc', $fields = '*', $limit = 10, $page = 1)
    {
        $results = [];

        // vị trí bắt đầu lấy bản ghi theo paginate:
        $offset = ($page >= 1) ? ($page - 1) * $limit : 0;

        $query = $this->db->table($this->table)
            ->select($fields)
            ->orderBy($sortField, $sortDirection); //'desc'(tăng dần) | 'asc'

        $totalCount = $query->countAllResults(false);
        $khachhangs = $query->limit($limit, $offset)->get()->getResult('array');

        if ($totalCount !== null) {
            // Đếm số thứ tự các bản ghi:
            $sttStart = $totalCount - $offset; // Đếm giảm dần | $sttStart = $offset + 1; (đếm tăng dần)
            $stt = $sttStart;
            foreach ($khachhangs as &$khachhang) {
                // Gán giá trị stt cho mỗi khách hàng
                $khachhang['stt'] = $stt--;

                // Chỉ hiển thị ngày hoặc giờ trong `created_at`
                $khachhang['created_date'] = $this->time::parse($khachhang['created_at'])->format('d-m-Y');
                $khachhang['created_time'] = $this->time::parse($khachhang['created_at'])->toTimeString();
            }

            $results = ['khachhangs' => $khachhangs, 'totalCount' => $totalCount];
        }

        return $results;
    }


    /// XÂY DỰNG HÀM LẤY DANH SÁCH THEO `id`:
    public function getByID($idKh, $fields = '*')
    {
        $query = $this->db->table($this->table)
            ->select($fields)
            ->where('id', $idKh)
            ->get();
        $khachhang = $query->getRow(0, 'array');

        // Chỉ hiển thị ngày hoặc giờ trong `created_at`
        $khachhang['created_date'] = $this->time::parse($khachhang['created_at'])->format('d-m-Y');
        $khachhang['created_time'] = $this->time::parse($khachhang['created_at'])->toTimeString();

        return $khachhang;
    }


    /// XÂY DỰNG HÀM LẤY DANH SÁCH THEO `status`:
    public function getByStatus($status, $sortField = null, $sortDirection = null, $fields = '*')
    {
        if ($sortField === null) {
            $sortField = 'created_at';
        }
        if ($sortDirection === null) {
            $sortDirection = 'desc';
        }

        $query = $this->db->select($fields)
            ->from('khachhangs')
            ->where('status', $status)
            ->orderBy($sortField, $sortDirection) //'desc'(tăng dần) | 'asc'
            ->get();

        return $query->getResult();
    }


    /// ĐẾM TỔNG SỐ BẢN GHI HIỆN CÓ:
    public function getTotalCount()
    {
        return $this->db->table($this->table)->countAllResults();
    }


    /// SEARCH BẢN GHI THEO `name`, `phone`, `points`:
    public function search($keywords = '', $fieldSearch = '', $sortField = 'created_at', $sortDirection = 'desc', $fields = '*', $limit = 10, $page = 1)
    {
        $results = [];

        // Vị trí bắt đầu lấy bản ghi theo paginate:
        $offset = ($page >= 1) ? ($page - 1) * $limit : 0;

        // Khởi tạo truy vấn cơ bản
        $query = $this->db->table($this->table)
            ->select($fields)
            ->orderBy($sortField, $sortDirection);

        // Áp dụng điều kiện tìm kiếm nếu có
        if (!empty($keywords) && !empty($fieldSearch)) {
            $query = $query->like($fieldSearch, $keywords);
        }

        // Đếm tổng số bản ghi
        $totalCount = $query->countAllResults(false); // Sử dụng tham số `false` để không reset lại truy vấn

        // Lấy dữ liệu với giới hạn số lượng
        $khachhangs = $query->limit($limit, $offset)->get()->getResult('array');

        // Đếm số thứ tự các bản ghi:
        if ($totalCount !== null) {
            $sttStart = $totalCount - $offset;
            $stt = $sttStart;
            foreach ($khachhangs as &$khachhang) {
                $khachhang['stt'] = $stt--;
                $khachhang['created_date'] = $this->time::parse($khachhang['created_at'])->format('d-m-Y');
                $khachhang['created_time'] = $this->time::parse($khachhang['created_at'])->toTimeString();
            }

            $results = ['khachhangs' => $khachhangs, 'totalCount' => $totalCount];
        }

        return $results;
    }


    /// -------------------------------------------------------------

    /// XÂY DỰNG HÀM CREATE:
    public function addNew($name, $phone, $points, $status)
    {
        // Nạp các Quy Tắc & thông báo đã được khai báo ở bên trên:
        $this->validation->setRules($this->validationRules, $this->validationMessages);

        // Kiểm tra các dữ liệu truyền vào có đúng Quy Tắc:
        $isValid = $this->validation->run([
            'name' => $name,
            'phone' => $phone,
            'points' => $points,
        ]);

        // Nếu dữ liệu vi phạm, trả về thông báo lỗi:
        if (!$isValid) {
            $errors = $this->validation->getErrors();
            return [
                'status' => 'error',
                'message' => $errors
            ];
        }

        // Nếu dữ liệu phù hợp thì tiếp tục nạp vào Database:
        $data = [
            'name'       => $name,
            'phone'      => $phone,
            'points'     => $points,
            'status'     => $status,
            'created_at' => $this->time->toLocalizedString(),
        ];

        $this->db->table($this->table)->insert($data);

        if ($this->db->affectedRows() > 0) {
            $id = $this->getInsertID();

            return [
                'status' => 'success',
                'message' => 'Khach hang added successfully!',
                'id' => $id
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'co loi tu server!'
            ];
        }
    }


    /// XÂY DỰNG HÀM UPDATE:
    public function updateCus($idKh, $name = null, $phone = null, $points = null, $status = null)
    {
        // Lấy thông tin khách hàng từ cơ sở dữ liệu
        $khachhang = $this->getByID($idKh);

        $name = $name !== null ? $name : $khachhang['name'];
        $phone = $phone !== null ? $phone : $khachhang['phone'];
        $points = $points !== null ? $points : $khachhang['points'];
        $status = $status !== null ? $status : $khachhang['status'];
        $updated_at = $this->time->toLocalizedString();

        // Cập nhật thông tin khách hàng
        $result = $this->db->table($this->table)->where('id', $idKh)->update([
            'name' => $name,
            'phone' => $phone,
            'points' => $points,
            'status' => $status,
            'updated_at' => $updated_at
        ]);

        return $result ? [
            'status' => 'success',
            'message' => 'Khach hang updated successfully!',
            'id' => $idKh
        ] : [
            'status' => 'error',
            'message' => 'co loi tu server!'
        ];
    }


    /// XÂY DỰNG HÀM DELETE:
    public function deleteCus($idKh)
    {
        $result = $this->db->table($this->table)->where('id', $idKh)->delete();

        return $result ? [
            'status' => 'success',
            'message' => 'Khach hang removed successfully!'
        ] : [
            'status' => 'error',
            'message' => 'co loi tu server!'
        ];
    }
}
