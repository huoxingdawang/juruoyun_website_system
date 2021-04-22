function jry_wb_test_phone_number( phone)
{ 
    if(!(/^[1](([3][0-9])|([4][5-9])|([5][0-3,5-9])|([6][5,6])|([7][0-8])|([8][0-9])|([9][1,8,9]))[0-9]{8}$/.test(phone)))
        return false; 
	return true;
}
