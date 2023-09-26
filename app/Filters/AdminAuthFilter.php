<?php

/**
 * Đây là file để kiểm tra xem đã có đăng nhập trước đó hay chưa
 * để cho phép truy cập vào các Controller nằm trong phần ADMIN.
 * 
 * lưu ý: phải đăng kí trong file: `app/Config/Filters.php` để loại trừ 2 router login, logout.
 * 
 * chỉnh thời gian phiên đăng nhập: `App\Config\Session.php` -> $expiration = 300; (giây)
 * hết thời gian này sẽ tự động đăng xuất
 */

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->route('admin.dangnhap');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}
