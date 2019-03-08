function jry_nd_get_area_by_area_id(area_id)
{
	for(var i=0;i<jry_nd_area.length;i++)
		if(jry_nd_area[i].area_id==area_id)
			return jry_nd_area[i];
}