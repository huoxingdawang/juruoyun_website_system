function jry_wb_test_china_id_card(idcard)
{
	idcard.toUpperCase();
    if (idcard.length==15)
        return jry_wb_test_china_id_card_15(idcard);   
	else if (idcard.length==18)
        return (jry_wb_test_china_id_card_18(idcard));
	else
        return false;
}   
function jry_wb_test_china_id_card_18(idcardd)
{   
	idcardd=idcardd.toUpperCase();
	var wi = [7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2,1];
	var valideCode = [1,0,10,9,8,7,6,5,4,3,2];
	var sum = 0;
	var idcard=[];
	for(var i=0;i<idcardd.length;i++)
		if (idcardd[i].toUpperCase()=='X')
			idcard[i] = 10;
		else
			idcard[i]=parseInt(idcardd[i]);
    for ( var i = 0; i < 17; i++)
        sum += wi[i] * idcard[i]; 
    valCodePosition = sum % 11;  
    if (idcard[17] == valideCode[valCodePosition])
	{
		var year =  idcardd.substring(6,10);   
		var month = idcardd.substring(10,12);   
		var day = idcardd.substring(12,14);   
		var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));   
		if(temp_date.getFullYear()!=parseFloat(year)||temp_date.getMonth()!=parseFloat(month)-1||temp_date.getDate()!=parseFloat(day)) 
			return false;   
		else
			return true;
	}
	else
        return false;   
}    
function jry_wb_get_sex_by_china_id_card(idcard)
{
	idcard.toUpperCase();
	if(!jry_wb_test_china_id_card(idcard))
		return false;
	if(idcard.length==15)
	{   
		if(idcard.substring(14,15)%2==0)
			return 0;   
		else
			return 1;   
	}
	else if(idcard.length ==18)
	{   
		if(idcard.substring(14,17)%2==0)
			return 0;   
		else
			return 1;   
	}
	else
		return false;   
} 
function jry_wb_test_china_id_card_15(idcard15)
{
	idcard15.toUpperCase();
	var year=idcard15.substring(6,8);   
	var month=idcard15.substring(8,10);   
	var day=idcard15.substring(10,12);   
	var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));
	if(temp_date.getYear()!=parseFloat(year)||temp_date.getMonth()!=parseFloat(month)-1||temp_date.getDate()!=parseFloat(day))
		return false;
	else
		return true;   
}   