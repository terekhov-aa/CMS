;(function ($) {
	"use strict";
	$.fn.pagecreator = function (arg) {
		var target = this;
		// console.log(this);
		// console.log(arg);
		// console.log('$.fn.plugincontrol');	
        $(target).empty();               
		// 	var output = Mustache.render('<div>{{qwer}} is a  {{qwer}}</div>', arg); 
		// $.each(arg,function(index, value){			
		// 	// console.log(Mustache);
		// 	// console.log(value);
		// });
		// 	$(target).append(output);
		var text_window = "<div class='col-md-3'><input type='button' onclick='send_content()' class='btn btn-success btn-block' value='Cохранить'><input type='button' onclick='page_edit()' class='btn btn-success btn-block' value='Страницы'> </div><div class='col-md-9'>Введите ссылку для страницы <input type='text' id='page_header'/><textarea name='text' id='text_data'></textarea></div>";
				$(target).append(text_window);
				tinymce.init({
				  selector: 'textarea',
				  height: 500,
				  theme: 'modern',
				  plugins: [
				    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
				    'searchreplace wordcount visualblocks visualchars code fullscreen',
				    'insertdatetime media nonbreaking save table contextmenu directionality',
				    'emoticons template paste textcolor colorpicker textpattern imagetools'
				  ],
				  toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
				  toolbar2: 'print preview media | forecolor backcolor emoticons',
				  image_advtab: true,
				  templates: [
				    { title: 'Test template 1', content: 'Test 1' },
				    { title: 'Test template 2', content: 'Test 2' }
				  ],
				  content_css: [
				    '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
				    '//www.tinymce.com/css/codepen.min.css'
				  ]
				 });
	}

})(jQuery);

function page_edit(){
	$.post(
	"",
	{
	module: "pagecreator",
	action: "run_page_control"
	},
	page_edit_succes
	);
	function page_edit_succes(data)
	{
	console.log(data);
	$("#page_names").empty(); 
	var jdata = data != "" ? $.parseJSON(data) : {};
	var output = '';
	$.each(jdata,function(index, value){			
	// console.log(Mustache);
	// console.log(value);
	output += Mustache.render('<li>{{#inclusion}} class="success" {{/inclusion}}<input type="checkbox" id="plugincontrol_{{id}}" onclick="change_inclusion({{id}})" value="{{inclusion}}" {{#inclusion}} checked {{/inclusion}}/>{{pagename}}<a class="fa fa-trash" href="#"></a><ul>', value);
	});
	$("#page_names").append(output);
	}
}

function change_inclusion(data){
	console.log($('#plugincontrol_'+data).prop('checked'));

	$.post(
	"",
	{
		module: "pagecreator",
		action: "run_update",
		inclusion: $('#plugincontrol_'+data).prop('checked'),
		id: data
	},
		update_succes
	);
	function update_succes(data)
	{
		console.log(data);
	}
}

function send_content(){
	var text_data = tinyMCE.activeEditor.getContent();
	console.log(text_data);
	var page_header = $("#page_header").val();
	console.log(page_header);
	$.post(
	"",
	{
	module: "pagecreator",
	action: "run_send_content",
	page_name: page_header,
	page_content: text_data
	},
	send_content_succes
	);
	function send_content_succes(data)
		{
		  console.log(data);
		}
}