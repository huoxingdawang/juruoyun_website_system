const jry_wb_keycode_backspace	 = 8;
const jry_wb_keycode_tab		 = 9;
const jry_wb_keycode_clear		 = 12;
const jry_wb_keycode_enter		 = 13;
const jry_wb_keycode_shift		 = 16;
const jry_wb_keycode_control	 = 17;
const jry_wb_keycode_alt		 = 18;
const jry_wb_keycode_pause		 = 19;
const jry_wb_keycode_capslock	 = 20;
const jry_wb_keycode_escape		 = 27;
const jry_wb_keycode_space		 = 32;
const jry_wb_keycode_prior		 = 33;
const jry_wb_keycode_next		 = 34;
const jry_wb_keycode_end		 = 35;
const jry_wb_keycode_home		 = 36;
const jry_wb_keycode_left		 = 37;
const jry_wb_keycode_up			 = 38;
const jry_wb_keycode_right		 = 39;
const jry_wb_keycode_down		 = 40;
const jry_wb_keycode_select		 = 41;
const jry_wb_keycode_print		 = 42;
const jry_wb_keycode_execute	 = 43;
const jry_wb_keycode_insert		 = 45;
const jry_wb_keycode_delete		 = 46;
const jry_wb_keycode_help		 = 47;
const jry_wb_keycode_0			 = 48;
const jry_wb_keycode_1			 = 49;
const jry_wb_keycode_2			 = 50;
const jry_wb_keycode_3			 = 51;
const jry_wb_keycode_4			 = 52;
const jry_wb_keycode_5			 = 53;
const jry_wb_keycode_6			 = 54;
const jry_wb_keycode_7			 = 55;
const jry_wb_keycode_8			 = 56;
const jry_wb_keycode_9			 = 57;
const jry_wb_keycode_a			 = 65;
const jry_wb_keycode_b			 = 66;
const jry_wb_keycode_c			 = 67;
const jry_wb_keycode_d			 = 68;
const jry_wb_keycode_e			 = 69;
const jry_wb_keycode_f			 = 70;
const jry_wb_keycode_g			 = 71;
const jry_wb_keycode_h			 = 72;
const jry_wb_keycode_i			 = 73;
const jry_wb_keycode_j			 = 74;
const jry_wb_keycode_k			 = 75;
const jry_wb_keycode_l			 = 76;
const jry_wb_keycode_m			 = 77;
const jry_wb_keycode_n			 = 78;
const jry_wb_keycode_o			 = 79;
const jry_wb_keycode_p			 = 80;
const jry_wb_keycode_q			 = 81;
const jry_wb_keycode_r			 = 82;
const jry_wb_keycode_s			 = 83;
const jry_wb_keycode_t			 = 84;
const jry_wb_keycode_u			 = 85;
const jry_wb_keycode_v			 = 86;
const jry_wb_keycode_w			 = 87;
const jry_wb_keycode_x			 = 88;
const jry_wb_keycode_y			 = 89;
const jry_wb_keycode_z			 = 90;
const jry_wb_keycode_win		 = 91;
const jry_wb_keycode_0_			 = 96;
const jry_wb_keycode_1_			 = 97;
const jry_wb_keycode_2_			 = 98;
const jry_wb_keycode_3_			 = 99;
const jry_wb_keycode_4_			 = 100;
const jry_wb_keycode_5_			 = 101;
const jry_wb_keycode_6_			 = 102;
const jry_wb_keycode_7_			 = 103;
const jry_wb_keycode_8_			 = 104;
const jry_wb_keycode_9_			 = 105;
const jry_wb_keycode_f1			 = 112;
const jry_wb_keycode_f2			 = 113;
const jry_wb_keycode_f3			 = 114;
const jry_wb_keycode_f4			 = 115;
const jry_wb_keycode_f5			 = 116;
const jry_wb_keycode_f6			 = 117;
const jry_wb_keycode_f7			 = 118;
const jry_wb_keycode_f8			 = 119;
const jry_wb_keycode_f9			 = 120;
const jry_wb_keycode_f10		 = 121;
const jry_wb_keycode_f11		 = 122;
const jry_wb_keycode_f12		 = 123;
var jry_wb_shortcut_tree={'func':function(){},'next': new Array()};
var jry_wb_shortcut_keycode_buf  =  new Array();
var jry_wb_shortcut_keycode_buf_count = 0;
var jry_wb_shortcut_debug = false;
var jry_wb_shortcut_set_flag = false;
document.onkeydown  =  function (e) 
{
	if (!e) 
		e  =  window.event;
	if((e.target.tagName=="TEXTAREA"||e.target.tagName=="INPUT"))
		return;
	var keycode=(e.keyCode||e.which);
	if(keycode>=jry_wb_keycode_0_&&keycode<=jry_wb_keycode_9_)
		keycode = keycode-jry_wb_keycode_0_+jry_wb_keycode_0;
	jry_wb_shortcut_keycode_buf[jry_wb_shortcut_keycode_buf_count++]=keycode;
	if(!jry_wb_shortcut_set_flag)
	{
		jry_wb_shortcut_set_flag = true;
		setTimeout(function(){
			var now = jry_wb_shortcut_tree;
			var n = jry_wb_shortcut_keycode_buf_count;
			jry_wb_shortcut_set_flag = false;
			jry_wb_shortcut_keycode_buf_count = 0;
			for( var i = 0;i<n;i++)
			{
				if(now==null)
					return true;
				now = now.next[jry_wb_shortcut_keycode_buf[i]];
			}
			if(now==null||now.func==null)
				return ;
			if( typeof now.func=="function")
				now.func(e);
		},500);
	}
	return ((e.target.tagName=="TEXTAREA"||e.target.tagName=="INPUT")&&(!e.ctrlKey))||(keycode==jry_wb_keycode_f12||keycode==jry_wb_keycode_f5||keycode==jry_wb_keycode_f11||(keycode==jry_wb_keycode_c&&e.ctrlKey));
};
function jry_wb_set_shortcut(code,func)
{
	var now = jry_wb_shortcut_tree.next;
	for( var i = 0,n = code.length;i<n;i++)
	{
		if(now[code[i]]==null)
			now[code[i]]={'func':function(){},'next': new Array()};
		if(i!=(n-1))
			now = now[code[i]].next;
	}
	now[code[code.length-1]].func = func;
}
