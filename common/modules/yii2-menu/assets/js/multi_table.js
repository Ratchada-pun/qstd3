// **** **** **** **** **** *** *** *****
//	plugin: นี้ใช้สำหรับการเพิ่มและลบบรรทัด
//	version: 1.0.0
//	jquery: jquery-1.3.2.min
//	การเรียกใช้:
//		var inputChk='input#project_name';
//		var cont='<tr><td align="center"></td><td><input type="hidden" name="id[]" id="id"><input type="text" name=			"project_name[]" id="project_name"></td><td ><input type="text" name="responsible_agencies[]" id="responsible_agencies"></td><td><textarea name="operations[]" id="operations"></textarea></td><td align="center"></td></tr>';
//	$(function(){
//				$.Multi_tb({
//					Tb:$("table#muti_section9_1 tbody"),
//					InputChk:inputChk,
//					Contents:cont
//				});
//	});
//		</script>

//Defaults
//		RowClass:Array("rowA","rowB"),
//		A_plus:'<a id="plus" class="a_button">+</a>',
//		A_del:'<a id="del" class="a_button">-</a>',
//		InputChk:null,
//		Contents:null,
//		Tb:null,
//		Ln:'on',
//		FirstNo:null
// **** **** **** **** **** *** *** *****
(function($){
	$.Multi_tb = function(options){
		var opts = $.extend({}, $.Multi_tb.defaults, options);
		$.A_plus=opts.A_plus;
		$.A_del=opts.A_del;
	    $.addRows(options);
		if(typeof opts.CallFunc == 'function')opts.CallFunc.call();
	};
	
	$.Multi_tb.defaults = {
       	RowClass:Array("rowA","rowB"),
        A_plus:'<a id="plus" class="a_button">เพิ่ม</a>',
		A_del:'<a id="del" class="a_button">ลบ</a>',
		InputChk:null,
		Contents:null,
		Tb:null,
		Ln:'on',
		FirstNo:null,
		CallFunc:null
    };
	
	$.A_plus=null;
	$.A_del=null;
	$.rowClass=Array("rowA","rowB");
	
	$.addRows=function(options){
			var opts = $.extend({}, $.Multi_tb.defaults, options);
			$(opts.Tb).append(opts.Contents);
			$.bindEventButton(options);
	}
	
	$.bindEventButton=function(options){
		var opts = $.extend({}, $.Multi_tb.defaults, options);
		$(opts.Tb).find("tr").each(function(index){
			$(opts.Tb).find("tr:eq("+index+") td:last-child").html($.A_del);
			$(opts.Tb).find("tr:eq("+index+") a#del").click(function(){
				$.deleteRow(index,options);
			});
			
			if(opts.Ln=='on')
			$(opts.Tb).find("tr:eq("+index+") td:first").html(opts.FirstNo+(index+1));
			
			$(opts.Tb).find("tr:eq("+index+")").removeClass();
			$(opts.Tb).find("tr:eq("+index+")").addClass($.rowClass[(index%2)]);
		});
		
		/*เช็คว่าตัวสุดท้ายไหม*/
		if($(opts.Tb).find("tr:last-child").index()==0){
			$(opts.Tb).find("tr:last-child td:last-child").html($.A_plus);
		}else{
			$(opts.Tb).find("tr:last-child td:last-child").html($.A_plus+$.A_del);
		}
		
		$(opts.Tb).find("a#plus").click(function(){
				$.addRows(options);
				if(typeof opts.CallFunc == 'function')opts.CallFunc.call();
		});
		
		$(opts.Tb).find("tr:last-child td:last-child").find("a#del").click(function(){
				var lastItem=$(opts.Tb).find("tr:last-child").index();
				$.deleteRow(lastItem,options);
				//alert($(opts.Tb).find("tr:last-child").selector);
				if(typeof opts.CallFunc == 'function')opts.CallFunc.call();
		});
				
	}
	
	$.deleteRow=function(indexs_of_row,options){
		//alert(indexs_of_row);
		var opts = $.extend({}, $.Multi_tb.defaults, options);
		
		$(opts.Tb).find("tr:eq("+indexs_of_row+")").fadeOut("500",function(){
				$(this).remove();
				$.clearEventButton(options);
				$.bindEventButton(options);
		});
	}
	
	$.clearEventButton=function(options){
		var opts = $.extend({}, $.Multi_tb.defaults, options);
		$(opts.Tb).find("tr a#del").each(function(){
			$(this).unbind("click");
		});
	}
	
	


})(jQuery);


