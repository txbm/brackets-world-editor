/**
 * Master JavaScript File for Brackets Worldbuilder V0.1
 */

$(document).ready(
	function() {
		var menu = null
		$("td").click(function(event) {
			if($("td").removeClass("selected")) {
				$(this).addClass("selected");
				buildMenu($(this));
			}
		});
		
		$("#boardname").change(function (event) {
			$.post('cellselect.php', {number : $("#number").val(), name : $(event.target).val()}, function(data){
				if(data == "true") {
					$(".savestatus > p").text("Saved");
				}
			});
		});
		
		function buildMenu(cell) {
			$("form[name=celledit] > fieldset").empty();
			$("form[name=celledit]").unbind();
			$("#savestat").attr("class", "unsaved");
			$("#savestat").text("Unsaved");
			var board = $("#number").val();
			var r = cell.attr("id").match(/r[0-9]+/)[0].substr(1);
			var c = cell.attr("id").match(/c[0-9]+/)[0].substr(1);
			var id = cell.attr("id").match(/n[0-9]+/)[0].substr(1);;
			$("form[name=celledit] > fieldset").append("<legend>Editing Cell #: " + id + "</legend>");
			$.post('cellselect.php', {
				number : board,
				rowNumber : r,
				columnNumber : c
			}, function(data) {
				$("form[name=celledit] > fieldset").append(data);
				$("form[name=celledit] select").change(function (event) {
					$.post('cellselect.php', {number : board, rowNumber : r, columnNumber : c, cellops : $("form[name=celledit]").serializeArray() }, function(data) {
						if(data == "true") {
							$("#savestat").attr("class", "saved");
							$("#savestat").text("Saved");
						} else {
							$("#savestat").attr("class", "error");
							$("#savestat").text("ERROR!");
						}
					}, 'text');
				});
			}, 'html');
			$("form[name=celledit]").change(function(event){
				
cell.children(".inserts").children("[name=" + 
$(event.target).attr('name') + "]").attr("class", $(event.target).val());
			});
			$("form[name=celledit]").submit(function () {
				return false;
			});
		}
	});
