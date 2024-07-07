// JavaScript Document

var is_with_loading = false;
var ar_loading = new Array(false, false, false);
var sel_tasks = ["copy", "edit", "delete", "editchild", "cancel1", "pay"];
var ex_tasks = ["add", "edit", "edit1", "save", "new", "back", "copy", "orderup", "orderdown"];
var ex_forms = [];

$(document).ready(function(e) {
	$('#div_pagin').on('click', 'ul.pagination li a', function(e){
		var href = $(this).attr('href');
		offset = href.replace(controller_url + '/index/','');
		offset = offset=='' ? '0' : offset;
		if(typeof CustomFreshContent == 'function') CustomFreshContent(offset, true);
		e.preventDefault();
	});
	$('form').submit(function(e) {
		var id = $(this).attr('id');
		if(typeof id == 'undefined') id = "";
		var task = $('form #task').val();
		if(ex_forms.indexOf(id)==-1){
			if(ex_tasks.indexOf(task)==-1){
				e.preventDefault();
				if(typeof CustomFreshContent == 'function') CustomFreshContent();
			}
		}
    });
	$('#filter_cari').change(function(e) {
        if(typeof CustomFreshContent == 'function') CustomFreshContent();
    });
});

function FreshContent(offset, postdata){
	if(is_with_loading) start_loading();
	// refresh tbody
	$.post(controller_url + '/listcontent/tbody/' + offset,postdata,function(data){
			$('#list_tbody').html(data);
			stop_loading(0);
		},'text');
	
	// refresh paginfo
	$.post(controller_url + '/listcontent/paginfo/' + offset,postdata,function(data){
			$('#div_paginfo_div').html(data);
			stop_loading(1);
		},'text');
	
	// refresh paginfo
	$.post(controller_url + '/listcontent/pagin/' + offset,postdata,function(data){
			$('#div_pagin').html(data);
			stop_loading(2);
		},'text');
}

function start_loading(){
	var i;
	for(i=0;i<ar_loading.length;i++){
		ar_loading[i] = false;
	}
	$("section.content").LoadingOverlay("show");
}

function stop_loading(idx){
	ar_loading[idx] = true;
	var complete = true;
	var i;
	for(i=0;i<ar_loading.length;i++){
		complete = complete && ar_loading[i];
	}
	if(complete) {
		$("section.content").LoadingOverlay("hide");
		is_with_loading = false;
		if(typeof CallbackFreshContent == 'function') CallbackFreshContent();
	}
}

function toggleCheck(n)
{
	var chk = document.getElementById("cb"+n);
	if(chk.checked){
		chk.checked = false;
	}
	else{
		chk.checked = true;
	}
}

function checkAll()
{
	var toggle = $('#toggle').prop('checked');
	$('input[type="checkbox"][name="cid[]"]').each(function(index, element) {
		$(this).prop('checked',toggle);
	});
}

function submitTask(task)
{
	var objtask = document.getElementById('task');
	if(objtask) $('#task').val(task);
	if(sel_tasks.indexOf(task)>-1){
		// check any checked
		var j=0;
		$('input[type="checkbox"][name="cid[]"]').each(function(index, element) {
			if($(this).is(':checked')){
				j++;
			}
		});
		if(j>0){
			//delete
			if(task=='delete'){
				var rec = (j > 1) ? 'data-data' : 'data';
				getConfirm('Konfirmasi Hapus', 'Anda yakin akan menghapus ' + rec + ' ini', "doDeleteRecord();");
			}
			else if(task=='cancel1'){
				var rec = (j > 1) ? 'data-data' : 'data';
				var resp = confirm('Anda yakin akan membatalkan ' + rec + ' ini');
				if(resp==true){
					$('#task').closest('form').submit();
				}
			}
			else{
				$('#task').closest('form').submit();
			}
		}
		else{
			showInfo("Belum Dipilih", "Tidak ada data yang terpilih!");
		}
	}
	else{
		$('#task').closest('form').submit();
		// var id = $('#task').closest('form').attr('id');
		// document.getElementById(id).submit();
	}
}

function doDeleteRecord(){
	var cid = [];
	$('input[type="checkbox"][name="cid[]"]').each(function(index, element) {
		if($(this).is(':checked')){
			cid.push($(this).val());
		}
	});
	$.post(controller_url + '/index',{ 'task':'delete', 'cid':cid },function(data){
			if(typeof CustomFreshContent == 'function') CustomFreshContent();
			$('#toggle').prop('checked',false);
		},'text');
}

function FreshContent1(id, with_loading){
	with_loading = with_loading || false;
	
	var offset = $('tr[data-id="' + id + '"]').attr('data-offset');
	var index = $('tr[data-id="' + id + '"]').attr('data-index');
	
	if(with_loading) $("section.content").LoadingOverlay("show");
	$.post(controller_url + '/listcontent1/' + id,
		{
			'offset' : offset,
			'index' : index
		},
	function(data){
		$('tr[data-id="' + id + '"]').empty();
		$('tr[data-id="' + id + '"]').append(data);
		if(with_loading) $("section.content").LoadingOverlay("hide");
	}, 'text');
}