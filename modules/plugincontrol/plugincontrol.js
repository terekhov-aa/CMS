;(function ($) {
	"use strict";
	$.fn.plugincontrol = function (arg) {
		var target = this;
		// console.log(this);
		// console.log(arg);
		// console.log('$.fn.plugincontrol');	

        $(target).empty();
        var output = "";
		output += '<table class="table"><thead><tr><th></th><th>Плагины</th><th>Описание</th></tr></thead><tbody>'; 

		$.each(arg,function(index, value){			
			// console.log(Mustache);
			// console.log(value);
			output += Mustache.render('<tr {{#inclusion}} class="success" {{/inclusion}}><td><input type="checkbox" id="plugincontrol_{{id}}" onclick="change_inclusion({{id}})" value="{{inclusion}}" {{#inclusion}} checked {{/inclusion}}/></td><td>{{pluginame}}</td><td></td><td><a class="fa fa-trash" href="#"></a></td></tr>', value);
		});
		output += '</tbody></table>'; 
		$(target).append(output);
	}

})(jQuery);

function change_inclusion(data){
	console.log($('#plugincontrol_'+data).prop('checked'));

	$.post(
	"",
	{
		module: "plugincontrol",
		action: "run_update",
		inclusion: $('#plugincontrol_'+data).prop('checked'),
		id: data
	},
		update_succes
	);
	function update_succes(data)
	{
		console.log(data);
		OnAdmMenuLoad();
	}
}