<style>

.box-header select.form-control {
	padding: 4px 12px;
	height: 30px;
	font-size:12px;
}

.tooltip-inner {
    white-space:pre-wrap;
	text-align:left;
}

</style>

<form class="form-inline" method="post" action="<?php echo current_url(); ?>" role="form">
<div class="box box-primary">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-xs-9">
                <div class="form-group">
                	<div class="input-group">
						<?=$html['filter_published'];?>
                    </div>
                    <div class="input-group">
                        <?=$html['filter_location_id'];?>
                    </div>
                    <div class="input-group">
                        <?=$html['filter_operator_id'];?>
                    </div>
                	<div class="input-group">
                        <input name="filter_cari" id="filter_cari" class="form-control input-sm" type="text" value="<?php echo $this->session->userdata($this->controller.'_filter_cari'); ?>" title="Name, Title" on />
                        <div class="input-group-btn">
                            <a class="btn btn-success btn-sm" href="javascript:CustomFreshContent();" title="Search"><i class="fa fa-search fa-lg"></i></a>
                            <a class="btn btn-primary btn-sm" href="javascript:$('#filter_cari').val(''); CustomFreshContent();" title="Reset"><i class="fa fa-refresh fa-lg"></i></a>
                      	</div>
                 	</div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="form-group" style="float:right">
                	<div class="btn-group">
                        <a class="btn btn-success btn-sm" href="javascript:submitTask('add');"><i class="fa fa-file-o fa-lg"></i></a>
                        <a class="btn btn-primary btn-sm" href="javascript:submitTask('edit');"><i class="fa fa-edit fa-lg"></i></a>
                        <a class="btn btn-danger btn-sm" href="javascript:submitTask('delete');"><i class="fa fa-trash-o fa-lg"></i></a>
                 	</div>
                </div>
            </div>
        </div>
	</div>
    <!-- /.box-header -->
						
    <div class="box-body table-responsive">
        <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th width="1"><input id="toggle" name="toggle" value="" onclick="checkAll();" type="checkbox"></th>
                <th width="1">No</th>
                <th width="1">id</th>
                <th width="1">phone_number</th>
                <th width="1">device_name</th>
                <th width="1">udid</th>
                <th width="1">platform_name</th>
                <th width="1">platform_version</th>
                <th width="1">application</th>
                <th width="1">location_name</th>
                <th width="1">operator_name</th>
                <th width="1">Status</th>
                <th width="1">Age</th>
                <th width="1">Version</th>
                <th width="1">Published</th>
                <th>&nbsp;</th>
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

<script type="text/javascript">

var controller_url = '<?=site_url($this->controller);?>';

function CustomFreshContent(offset='', with_loading = true){
	is_with_loading = with_loading;
	FreshContent(offset,{
			'filter_published' : $('#filter_published').val(),
			'filter_location_id' : $('#filter_location_id').val(),
			'filter_operator_id' : $('#filter_operator_id').val(),
			'filter_cari' : $('#filter_cari').val(),
			'filter_limit' : $('#filter_limit').val()
		});
}

$(document).ready(function(e) {
	$('#filter_published').change(function(e) {
        CustomFreshContent();
    });
	
    $('#filter_location_id').change(function(e) {
        CustomFreshContent();
    });
	
	$('#filter_operator_id').change(function(e) {
        CustomFreshContent();
    });
});

</script>