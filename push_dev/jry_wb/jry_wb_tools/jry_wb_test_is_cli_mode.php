<?php
function jry_wb_test_is_cli_mode()
{
	return preg_match("/cli/i", php_sapi_name()) ? true : false;
}