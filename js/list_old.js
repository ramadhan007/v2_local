// JavaScript Document

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
	var i;
	var toggle = document.getElementById("toggle").checked;
	var jml = document.getElementById('numrows').value;
	for(i=0; i<jml; i++)
	{
		var chk = document.getElementById("cb"+i);
		if(!chk.disabled)
		{
			if(toggle){
				chk.checked = true;
			}
			else{
				chk.checked = false;
			}
		}
	}
}

function submitTask(task)
{
	var objtask = document.getElementById('task');
	if(objtask) $('#task').val(task);
	if(task=='copy' || task=='edit' || task=='view' || task=='delete' || task=='editchild' || task=='cancel1' || task=='pay'){
		var jml = $('#numrows').val();
		var i;
		var j=0;
		for(i=0; i<jml; i++){
			var chk = document.getElementById("cb"+i);
			if(chk.checked==true){
				j++;
			}
		}
		if(j>0){
			if(task=='delete'){
				var rec = (j > 1) ? 'data-data' : 'data';
				var resp = confirm('Anda yakin akan menghapus ' + rec + ' ini');
				if(resp==true){
					$('#task').closest('form').submit();
				}
				else{
					if(objtask) $('#task').val(task);
				}
			}
			else if(task=='cancel1'){
				var rec = (j > 1) ? 'data-data' : 'data';
				var resp = confirm('Anda yakin akan membatalkan ' + rec + ' ini');
				if(resp==true){
					$('#task').closest('form').submit();
				}
				else{
					if(objtask) $('#task').val(task);
				}
			}
			else{
				$('#task').closest('form').submit();
			}
		}
		else{
			if(objtask) $('#task').val(task);
			alert("Tidak ada data yang terpilih!");
		}
	}
	else{
		$('#task').closest('form').submit();
	}
}