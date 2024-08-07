<form class="form-inline" method="post" action="<?php echo current_url(); ?>" role="form">
<div class="box box-primary">
    <div class="box-header with-border">
    	<div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                	<div class="input-group">
                        <input name="filter_cari" id="filter_cari" class="form-control input-sm" type="text" value="<?php echo $this->session->userdata($this->controller.'_filter_cari'); ?>" title="Tag, Title, Name" />
                        <div class="input-group-btn">
                            <a class="btn btn-success btn-sm" href="javascript:CustomFreshContent();" title="Search"><i class="fa fa-search fa-lg"></i></a>
                            <a class="btn btn-primary btn-sm" href="javascript:$('#filter_cari').val(''); CustomFreshContent();" title="Reset"><i class="fa fa-refresh fa-lg"></i></a>
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
                	</div> 
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-header -->
    
    <div class="box-body table-responsive">
      <table class="table table-striped table-hover">
      	<thead>
            <tr>
                <th width="1"><input id="toggle" name="toggle" value="" onclick="checkAll();" type="checkbox"></th>
                <th width="1">No</th>
                <th>Nama</th>
                <!-- <th width="1">Devices</th> -->
                <th width="1">Published</th>
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
                    Showing <?php echo $offset+1; ?> to <?php echo $offset+$numrows; ?> of <?php echo $total_rows; ?> entries
                    <?php else: ?>
                    No record
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

<!-- List Item Modal -->
<div class="modal large autoheight fade" id="deviceModal" style="width:auto; height:auto;">
    <div class="modal-dialog" style="width:60%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type=
                "button">&times;</button>
                <h4 id="deviceModalTitle" class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe width="100%" src="" frameborder="0" style="overflow-x: hidden; overflow-y: scroll; "></iframe>
            </div>
        </div>
    </div>
</div>

<script>

var controller_url = '<?=site_url($this->controller);?>';

function CustomFreshContent(offset='', with_loading = true){
	is_with_loading = with_loading;
	FreshContent(offset,{
			'filter_cari' : $('#filter_cari').val(),
			'filter_limit' : $('#filter_limit').val()
		});
}

function openDeviceModal(location_id)
{
	$('#deviceModal iframe').attr('src','<?php echo site_url('admin/locationdevice/setlocid'); ?>/' + location_id);
	$('#deviceModal').modal("show");
}

function closeModal(modal_id)
{
	$('#' + modal_id).modal("hide");
}

</script>