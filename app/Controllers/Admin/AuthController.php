<?php

/**
 * Đây là Controller phụ trách xử lý giao diện phần đăng nhập, đăng ký (nếu có), quên mật khẩu (nếu có).
 * 
 * lưu ý: sau khi hoàn thành đăng nhập, viết file: `App\Filters\AdminAuthFilter.php`
 *        để tạo xác thực cho phép truy cập vào các Controller nằm trong phần ADMIN.
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\AdminModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $adminModel;
    protected $session;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->session = \Config\Services::session();
    }


    /**----------------------------------
     *  Hàm đăng nhập:
     */
    public function login()
    {
        // kiểm tra đã login trước đó chưa:
        if ($this->session->get('isLoggedIn')) {
            return redirect()->route('admin.khachhang');
        }

        // xử lí form đăng nhập nếu có bấm submit:
        if (strtoupper($this->request->getMethod()) === 'POST') {
            return $this->handleLoginPost();
        }

        return view('admin/pages/login_page');
    }

    /**-------------------------------------------------
     *  Hàm kiểm tra đăng nhập username, pass (chỉ gọi):
     */
    protected function handleLoginPost(): ResponseInterface
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if ($userID = $this->adminModel->checkLogin($username, $password)) {
            $this->session->set('isLoggedIn', true);
            $this->session->set('userID', $userID);
            $this->session->setFlashdata('mess', 'Bạn đã đăng nhập thành công!');

            return $this->response->setJSON([
                'status' => 'success',
                'url'    => base_url(route_to('admin.khachhang')),
            ]);
        }

        // nếu sai username, pass thì trả về cảnh báo:
        return $this->response->setJSON([
            'status' => 'errors',
            'mess'   => 'Thông tin đăng nhập không chính xác!',
        ]);
    }


    /**----------------------------------
     *  Hàm đăng xuất:
     */
    public function logout()
    {
        $this->session->remove('isLoggedIn');
        $this->session->remove('userID');
        $this->session->setFlashdata('mess', 'Bạn đã đăng xuất thành công!');

        return redirect()->route('admin.dangnhap');
    }
}
