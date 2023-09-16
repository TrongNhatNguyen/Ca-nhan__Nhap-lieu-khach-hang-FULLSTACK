<?php

/**
 *  Tự viết các Quy Tắc hợp lý cho $validationRules ở Models,
 *  Vào `app/Config/Validation.php` khai báo:
 *   - CustomRules::class, (để đăng kí quy tắc mới chạy đc hàm validation->run)
 */

namespace App\CustomValidators;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Validation\Rules;

class CustomRules extends Rules
{
    public function rule_name_unicode(string $str, string &$error = null): bool
    {
        $unicodePattern = '/^[\p{L}\p{N}\s]+$/u';

        if (preg_match($unicodePattern, $str)) {
            return true;
        }

        // $error có thể bỏ:
        // $error = 'Sorry. That field is not valid.';
        return false;
    }
}
