<?php

/**
 *  File helper độc lập, nhỏ gọn có thể gọi ở bất kì đâu (Controller, View,..)
 *  admin_helper này dùng để lấy thông tin tài khoản admin đang đăng nhập
 *  và hiển thị ở header, footer,..
 * 
 * note: phải khai báo: helper('admin_helper'); trong file `BaseController` dòng 58/
 */

use App\Models\Admin\AdminModel;

if (!function_exists('get_admin_info')) {

    /**---------------------------------------
     * Hàm lấy thông tin Admin đang đăng nhập
     */
    function get_admin_info()
    {
        $session = \Config\Services::session();
        if ($userID = $session->get('userID')) {
            $adminModel = new AdminModel();
            return $adminModel->getByID($userID);
        } else {
            throw new \InvalidArgumentException('không có thông tin tài khoản đăng nhập.');
        }
    }
}
