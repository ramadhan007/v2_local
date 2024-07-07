<form class="form-inline" method="post" action="<?php echo current_url(); ?>" role="form">
<div class="box box-primary">
    <div class="box-header with-border">
    	<div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                   <div class="input-group">
                        <input name="filter_cari" id="filter_cari" class="form-control input-sm" type="text" value="<?php echo $this->session->userdata($this->controller.'_filter_cari'); ?>" title="Tag, Title" />
                        <div class="input-group-btn">
                            <a class="btn btn-success btn-sm" href="javascript:javascript:submitTask('');" title="Search"><i class="fa fa-search fa-lg"></i></a>
                            <a class="btn btn-primary btn-sm" href="javascript:$('#filter_cari').val(''); javascript:submitTask('');" title="Reset"><i class="fa fa-refresh fa-lg"></i></a>
                      	</div>
                  	</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group" style="float:right">
                	<div class="btn-group">
                        <a class="btn btn-success btn-sm" href="javascript:submitTask('add');" title="Add"><i class="fa fa-file-o fa-lg"></i></a>
                        <a class="btn btn-primary btn-sm" href="javascript:submitTask('edit');" title="Edit"><i class="fa fa-edit fa-lg"></i></a>
                        <a class="btn btn-danger btn-sm" href="javascript:submitTask('delete');" title="Delete"><i class="fa fa-trash-o fa-lg"></i></a>
                        <?php if(get_main_config('dbsmr_enable')): ?>
                        <a id="btn_sync_smr" class="btn btn-warning btn-sm" href="javascript:SyncSMR();" title="Sync data SMR"><i class="fa fa-refresh fa-lg"></i> Sync</a>
                        <?php endif; ?>
                	</div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-header -->
						
    <div class="box-body table-responsive no-padding">
        <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th width="1"><input id="toggle" name="toggle" value="" onclick="checkAll();" type="checkbox"></th>
                <th width="1">No</th>
                <th>Nama</th>
                <?php if($this->login_by=='username'): ?>
                <th>Username</th>
                <?php endif; ?>
                <th>Email</th>
                <th>HP/WA</th>
                <th>User&nbsp;Type</th>
                <th>User&nbsp;Role</th>
                <th width="1">Status</th>
                <th width="1">Last&nbsp;Login</th>
            </tr>
        </thead>
        <tbody id="list_tbody">
            <?php include('list_tbody.php'); ?>
        </tbody>
        </table>
	</div>
	<!-- /.box-body -->
    
	<div class="box-footer clearfix">
      	<div class="row">
            <div id="div_paginfo" class="col-sm-8" style="display:block">
            	<div id="div_paginfo_div" style="margin-top:5px;">
					<?php if($numrows): ?>
                    Menampilkan <?php echo $offset+1; ?> sampai <?php echo $offset+$numrows; ?> dari <?php echo $total_rows; ?> entri
                    <?php else: ?>
                    Tidak ada entri
                    <?php endif; ?>
                </div>
            </div>
            <div id="div_progress" class="col-sm-8" style="display:none; margin-bottom:10px;">
            	<div style="margin-top:5px;">
                    <span id="span_progress" style="float:left; padding-right:3px;">
                    Mengimport 0%
                    </span>
                    <div class="progress" style="overflow:hidden; margin-bottom:unset;">
                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        </div>
                    </div>
				</div>
            </div>
            <div class="col-sm-4">
            	<span class="pull-right" style="padding-left:4px;">
                	<?php echo $html['filter_limit']; ?>
                </span>
            	<span id="div_pagin" class="pull-right">
               		<?php echo $pagination; ?>
               	</span>
            </div>
     	</div>
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
<input type="hidden" id="task" name="task" value="" />
<input type="hidden" id="numrows" name="numrows" value="<?php echo $numrows; ?>" />
</form>

<script>

var controller_url = '<?=site_url($this->controller);?>';

function CustomFreshContent(offset='', with_loading = true){
	is_with_loading = with_loading;
	FreshContent(offset,{
			'filter_cari' : $('#filter_cari').val(),
			'filter_limit' : $('#filter_limit').val()
		});
}

var timer = null;
var valuemin = 0;
var valuemax = 0;

function SyncSMR(){
	if(confirm('Anda yakin akan singkronisasi dengan data user SMR?')){
		$('#btn_sync_smr').addClass('disabled');
		$.post(controller_url + '/sync',{},
		function(data){
			clearInterval(timer);
			$('.progress-bar').attr('aria-valuenow',$('.progress-bar').attr('aria-valuemax'))
			$('.progress-bar').css('width', '100%');
			$('#span_progress').html("Mengimport 100%");
			$('#btn_sync_smr').removeClass('disabled');
			$('.progress').removeClass('active');
			setTimeout(function(){
				$('#div_progress').fadeOut(2000, function(){
					$(this).css('display','none');
					$('#div_paginfo').css('display','block');
					$('.progress-bar').css('width', '0%');
					CustomFreshContent('0', true);
				});
			}, 1000);
		},'text');
		$('#div_paginfo').css('display','none');
		$('#div_progress').css('display','block');
		timer = setInterval("SyncProgress()", 250);
	}
}

function SyncProgress(){
	$.post('<?=site_url('php/progress.php');?>',{
			'controller':'<?=$this->controller;?>',
			'process':'sync',
		},
	function(data){
		if(data.type=='init'){
			valuemin = data.valuemin;
			valuemax = data.valuemax;
			$('.progress-bar').attr('aria-valuemin',valuemin);
			$('.progress-bar').attr('aria-valuemax',valuemax);
			$('.progress-bar').attr('aria-valuenow',0);
			$('.progress-bar').css('width','0%');
			$('.progress').addClass('active');
			$('#span_progress').html("Mengimport 0%");
		}
		else{
			$('.progress-bar').attr('aria-valuenow',data.valuenow);
			$('.progress-bar').css('width', Math.round((data.valuenow/valuemax)*100) + '%');
			$('#span_progress').html("Mengimport " + Math.round((data.valuenow/valuemax)*100) + "%");
		}
	},'json');
}

</script>