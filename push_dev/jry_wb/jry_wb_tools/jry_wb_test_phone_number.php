<?php
function jry_wb_test_phone_number($mobile) 
{
    if (!is_numeric($mobile)) 
        return false;
    return preg_match('#^1[3,7][\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
}
?>
