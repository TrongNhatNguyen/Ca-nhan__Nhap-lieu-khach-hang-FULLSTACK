<?php

/**
 *  Controller xử lý page Khách hàng
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\KhachhangModel;
use CodeIgniter\Config\Services;

class KhachhangControllerOld extends BaseController
{
    protected $mRequest;
    protected $currentTime;
    protected $services;
    protected $pager;
    protected $khachhangModel;

    function __construct()
    {
        // Chuyển hướng về view (extend, section,..)
        $this->services = new Services();

        // Hỗ trợ nhận request từ form truyền vào:
        $this->request = \Config\Services::request();

        // phân trang paginate:
        $this->pager = \Config\Services::pager();

        // Nạp dl vào database cần tạo Model:
        $this->khachhangModel = new KhachhangModel();
    }


    public function index()
    {
        $activeKhachangs = $this->khachhangModel->getKhachangs(['status' => 'active'], 'created_at DESC');

        // khai báo 2 dòng này để dùng được: <?= $this->extend,section,.. ?.> ở Views
        $view = $this->services->renderer(APPPATH . 'views/admin/pages/', null, false);
        return $view->setData($data)->render('khachhang_page');
    }


    public function searchCustomer()
    {
        $data = [];

        // Kiểm tra từ khoá:
        $keywords = $this->request->getVar('search');
        $fieldSearch = null;

        if (empty($keywords)) {
            // Nạp Phân trang và pager_link giao diện:
            $data = $this->custom_paginate_link();
            return $this->response->setJSON($data, 200);
        }

        // Xác định field tìm kiếm dựa trên định dạng của từ khóa
        if (preg_match('/^(0|(\+84))[1-9][0-9]{0,10}$/', $keywords)) {
            $fieldSearch = 'phone';
        } elseif (preg_match('/^\d+$/', $keywords)) {
            $fieldSearch = 'points';
        } elseif (preg_match('/^[\p{L}\p{N}\s]+$/u', $keywords)) {
            $fieldSearch = 'name';
        }

        // Thực hiện tìm kiếm và paginate nếu xác định được field tìm kiếm
        if ($fieldSearch !== null) {
            $currentPage = 1;
            $numRecords  = 20;

            $results = $this->khachhangModel->search($keywords, $fieldSearch, limit: $numRecords, page: $currentPage);
            $totalCount = $results['totalCount'] ?? 0;

            // Nạp bản ghi & paginate_link (app/config/pager.php):
            $data['pager_links'] = $this->pager->makeLinks($currentPage, $numRecords, $totalCount, 'my_custom_pager');
            $data['khachhangs']  = $results['khachhangs'];
        }

        return $this->response->setJSON($data, 200);
    }



    public function addNewCustomer()
    {
        $name       = $this->request->getVar('name');
        $phone      = $this->request->getVar('phone');
        $points     = $this->request->getVar('points');
        $status     = 'active';

        $result = $this->khachhangModel->addNew($name, $phone, $points, $status);

        if ($result['status'] = 'success') {
            // Nạp Phân trang và pager_link cho giao diện:
            $data = $this->custom_paginate_link();

            return $this->response->setJSON($data, 200);
        }

        return $this->response->setJSON($data, 400);
    }


    public function showUpdateCustomer()
    {
        $id = $this->request->getGet('idKh');
        $khachhang = $this->khachhangModel->getByID($id);

        if (!empty($khachhang)) {
            return $this->response->setJSON($khachhang, 200);
        }

        return $this->response->setJSON('khong co ban ghi nao!', 400);
    }


    public function updateCustomer()
    {
        $idKh       = $this->request->getVar('id_kh');
        $name       = $this->request->getVar('name');
        $phone      = $this->request->getVar('phone');
        $points     = (int) $this->request->getVar('points');

        $result = $this->khachhangModel->updateCus($idKh, $name, $phone, $points);

        if ($result['status'] = 'success') {
            // Nạp Phân trang và pager_link cho giao diện:
            $data = $this->custom_paginate_link();

            return $this->response->setJSON($data, 200);
        }

        return $this->response->setJSON($result, 400);
    }


    public function deleteCustomer()
    {
        $idKh    = $this->request->getGet('idKh');
        $removed = $this->khachhangModel->deleteCus($idKh);

        if ($removed['status'] === 'success') {
            // Nạp Phân trang và pager_link cho giao diện:
            $data = $this->custom_paginate_link();

            return $this->response->setJSON($data, 200);
        } else {
            return $this->response->setJSON($removed, 400);
        }
    }



    public function custom_paginate_link($numRecords = 5)
    {
        $data = [];

        // Lấy giá trị page từ giao diện
        $currentPage = intval($this->request->getGet('page', FILTER_VALIDATE_INT)) ?: intval($this->request->getPost('page', FILTER_VALIDATE_INT));
        $currentPage = max(1, $currentPage);

        $results     = $this->khachhangModel->getAll(limit: $numRecords, page: $currentPage);
        $totalCount  = $results['totalCount'] ?? 0;

        // Kiểm tra nếu không có bản ghi nào
        if (empty($results['khachhangs'])) {
            $currentPage--;
            $results = $this->khachhangModel->getAll(limit: $numRecords, page: $currentPage);
        }

        // Nạp bản ghi & paginate_link (app/config/pager.php):
        $data['pager_links'] = $this->pager->makeLinks($currentPage, $numRecords, $totalCount, 'my_custom_pager');
        $data['khachhangs']  = $results['khachhangs'];

        return $data;
    }
}
