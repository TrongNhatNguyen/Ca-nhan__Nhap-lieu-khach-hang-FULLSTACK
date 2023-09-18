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


    public function index()
    {
        $page = intval($this->request->getGet('page', FILTER_VALIDATE_INT))
            ?: intval($this->request->getPost('page', FILTER_VALIDATE_INT));
        $page = max(1, $page);

        $data  = [];
        $limit = 5;
        $dieukien = []; // $dieukien = ['status' => 'active'];

        $total        = $this->khachhangModel->countTotal($dieukien);
        $dsKhachhangs = $this->khachhangModel->where($dieukien)->paginate($limit);

        $data = [
            'dsKhachhangs' => $this->napSttDateTime($dsKhachhangs, $total, $page),
            'pager'        => $this->khachhangModel->pager->links(template: 'khachhang_pager')
        ];

        // khai báo 2 dòng này để dùng được: <?= $this->extend,section,.. ?.> ở Views
        $view = $this->services->renderer(APPPATH . 'views/admin/pages/', null, false);
        return $view->setData($data)->render('khachhang_page');
    }


    /**-------------------------------
     * Hàm Tìm kiếm khách hàng
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



    /**------------------------------
     * Hàm nạp thêm STT, Date, Time
     */
    public function napSttDateTime($dsKhachhangs, $total, $page = 1, $limit = 5)
    {
        if (!empty($dsKhachhangs)) {
            // vị trí bắt đầu lấy bản ghi theo paginate:
            $offset = max(0, ($page - 1) * $limit);

            // Đếm số thứ tự các bản ghi:
            $remainingRecords = $total - $offset;
            $stt = $remainingRecords;

            foreach ($dsKhachhangs as &$item) {
                $createdAt = $item['created_at'] ?? null;

                if ($createdAt) {
                    // Gán giá trị stt cho mỗi khách hàng
                    $item['stt'] = $stt--;

                    // Phân tách ngày, giờ trong `created_at`
                    $item['created_date'] = $this->time::parse($createdAt)->format('d-m-Y');
                    $item['created_time'] = $this->time::parse($createdAt)->toTimeString();
                }
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
