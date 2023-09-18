<?php

/**
 *  Controller xử lý page Khách hàng
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\KhachhangModel;
use CodeIgniter\Config\Services;
use CodeIgniter\I18n\Time;

class KhachhangController extends BaseController
{
    protected $services;
    protected $time;
    protected $khachhangModel;

    public function __construct()
    {
        // Chuyển hướng về view (extend, section,..)
        $this->services = new Services();

        // Nạp hàm lấy hoặc format thời gian:
        $this->time = new Time();

        // Nạp dl vào database cần tạo Model:
        $this->khachhangModel = new KhachhangModel();
    }


    /**-------------------------------
     *  Hàm Trang chủ (index)
     */
    public function index()
    {
        $page = intval($this->request->getGet('page', FILTER_VALIDATE_INT))
            ?: intval($this->request->getPost('page', FILTER_VALIDATE_INT));

        $data = $this->dsPhanTrang(page: $page);

        // khai báo 2 dòng này để dùng được: <?= $this->extend,section,.. ?.> ở Views
        $view = $this->services->renderer(APPPATH . 'views/admin/pages/', null, false);
        return $view->setData($data)->render('khachhang_page');
    }


    /**-------------------------------
     *  Hàm Tìm kiếm khách hàng
     */
    public function timkiemKhachhang()
    {
        $keywords    = $this->request->getVar('search');
        $fieldSearch = $this->determineSearchField($keywords);

        $page = intval($this->request->getGet('page_search', FILTER_VALIDATE_INT))
            ?: intval($this->request->getPost('page_search', FILTER_VALIDATE_INT));
        $page = max(1, $page);

        $data  = [];
        $limit = 5;
        $dieukien = []; // $dieukien = ['status' => 'active'];
        $search = $fieldSearch ? [$fieldSearch, $keywords] : [];

        $query = $this->khachhangModel->where($dieukien);
        if ($fieldSearch) {
            $query = $query->like($search[0], $search[1]);
        }

        $dsKhachhangs = $query->paginate($limit);
        $total        = $this->khachhangModel->countTotal($dieukien, $search);

        $data = [
            'dsKhachhangs' => $this->napSttDateTime($dsKhachhangs, $total, $page),
            'pager'        => $query->pager->links(template: 'khachhang_pager')
        ];

        return $this->response->setJSON($data, 200);
    }


    /**-------------------------------
     *  Thêm Khách hàng mới
     */
    public function themMoi()
    {
        // Dữ liệu từ form
        $data = [
            'name'   => $this->request->getPost('name'),
            'phone'  => $this->request->getPost('phone'),
            'points' => $this->request->getPost('points'),
            'status' => 'active',
        ];

        $result = $this->khachhangModel->themMoiKhachHang($data);

        if ($result) {
            $resData = ['status' => 'success', 'mess' => 'Thêm mới thành công!'];
        } else {
            $resData = ['status' => 'errors', 'mess' => $this->khachhangModel->errors()];
        }

        $resData += $this->dsPhanTrang(page: 1);

        return $this->response->setJSON($resData, 200);
    }

    /**-------------------------------
     *  Hiển thị khách hàng muốn Sửa
     */
    public function showCapNhat()
    {
        $id        = $this->request->getGet('idKh');
        $khachhang = $this->khachhangModel->getByID($id);

        if ($khachhang) {
            $resData = ['status' => 'success', 'khachhang' => $khachhang];
        } else {
            $resData = ['status' => 'errors', 'mess' => 'không tồn tại.'];
        }

        return $this->response->setJSON($resData, 200);
    }

    /**-----------------------------------
     * Xử lý cập nhật khách hàng muốn sửa
     */
    public function xulyCapNhat()
    {
        // Dữ liệu từ form
        $id   = $this->request->getPost('id_kh');
        $data = [
            'name'   => $this->request->getPost('name'),
            'phone'  => $this->request->getPost('phone'),
            'points' => $this->request->getPost('points'),
        ];

        $result = $this->khachhangModel->capNhatKhachHang($id, $data);

        if ($result) {
            $resData = ['status' => 'success', 'mess' => 'Cập nhật thành công!'];
        } else {
            $resData = ['status' => 'errors', 'mess' => $this->khachhangModel->errors()];
        }

        $page = intval($this->request->getGet('page', FILTER_VALIDATE_INT))
            ?: intval($this->request->getPost('page', FILTER_VALIDATE_INT));

        $resData += $this->dsPhanTrang(page: $page);

        return $this->response->setJSON($resData, 200);
    }

    /**-----------------------------------
     *  Xoá bỏ khách hàng
     */
    public function xoa()
    {
        $id     = $this->request->getGet('idKh');
        $result = $this->khachhangModel->xoaKhachHang($id);

        if ($result) {
            $resData = ['status' => 'success', 'mess' => 'Xoá thành công!'];
        } else {
            $resData = ['status' => 'errors', 'mess' => $this->khachhangModel->errors()];
        }

        $page = intval($this->request->getGet('page', FILTER_VALIDATE_INT))
            ?: intval($this->request->getPost('page', FILTER_VALIDATE_INT));

        $resData += $this->dsPhanTrang(page: $page);

        return $this->response->setJSON($resData, 200);
    }


    /**------------------------------
     *  Hàm Phân trang ds khách hàng
     */
    public function dsPhanTrang($page, $limit = 5, $fields = '*', $dieukien = [], $orderBy = 'id DESC')
    {
        $page = max(1, $page);
        $data  = [];
        // $dieukien += ['status' => 'active'];

        $total        = $this->khachhangModel->countTotal($dieukien);
        $dsKhachhangs = $this->khachhangModel->select($fields)
            ->where($dieukien)->orderBy($orderBy)->paginate(perPage: $limit, page: $page);

        $data = [
            'dsKhachhangs' => $this->napSttDateTime($dsKhachhangs, $total, $page),
            'pager'        => $this->khachhangModel->pager->links(template: 'khachhang_pager')
        ];

        return $data;
    }


    /**------------------------------
     *  Hàm nạp thêm STT, Date, Time
     */
    public function napSttDateTime(array $dsKhachhangs, int $total, int $page = 1, int $limit = 5): array
    {
        // nếu không có bản ghi nào hoặc tổng số bản ghi là 0, trả về danh sách rỗng
        if (!$dsKhachhangs || $total <= 0) {
            return [];
        }

        // kiểm tra nếu xoá hết bản ghi của pager cuối cùng thì tự động lùi về pager kế cuối
        while (($page - 1) * $limit >= $total && $page > 1) {
            $page--;
        }

        // vị trí bắt đầu lấy bản ghi theo paginate:
        $offset = ($page - 1) * $limit;

        // Đếm số thứ tự các bản ghi:
        $stt = $total - $offset;

        foreach ($dsKhachhangs as &$item) {
            // Gán giá trị stt cho mỗi khách hàng
            $item['stt'] = $stt--;

            // Phân tách ngày, giờ từ `created_at` nếu có
            if (isset($item['created_at'])) {
                $createdAt = $this->time::parse($item['created_at']);
                $item['created_date'] = $createdAt->format('d-m-Y');
                $item['created_time'] = $createdAt->toTimeString();
            }
        }

        return $dsKhachhangs;
    }




    /**---------------------------------------------
     * Hàm kiểm tra từ khoá phù hợp với field nào.
     */
    private function determineSearchField($keywords)
    {
        if (empty($keywords)) {
            return null;
        }

        if (preg_match('/^(0|(\+84))[1-9][0-9]{0,10}$/', $keywords)) {
            return 'phone';
        }
        if (preg_match('/^\d+$/', $keywords)) {
            return 'points';
        }
        if (preg_match('/^[\p{L}\p{N}\s]+$/u', $keywords)) {
            return 'name';
        }

        return null;
    }
}
