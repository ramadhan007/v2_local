<script>

$(document).ready(function(e) {
    $(document).keyup(function(e){
		if(e.keyCode == 27) {
			parent.closeModal('detailModal');
		}
	});
});

parent.document.getElementById('detailModalTitle').innerHTML='<?php echo str_replace("'","\'",$this->view['toptitle']); ?>';
parent.document.getElementById('span_list_count_<?php echo $this->session->userdata($this->controller.'_parent_id'); ?>').innerHTML = '<?php echo $total_rows; ?>';

</script>

<style>

.tooltip-inner {
    white-space:pre-wrap;
	text-align:left;
}

</style>

<form class="form-inline" method="post" action="<?php echo current_url(); ?>" role="form">
<div class="box box-primary">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                	<div class="input-group">
                        <input name="filter_cari" id="filter_cari" class="form-control input-sm" type="text" value="<?php echo $this->session->userdata($this->controller.'_filter_cari'); ?>" title="Name, Title" on />
                        <div class="input-group-btn">
                            <a class="btn btn-success btn-sm" href="javascript:CustomFreshContent();" title="Search"><i class="fa fa-search fa-lg"></i></a>
                            <a class="btn btn-primary btn-sm" href="javascript:$('#filter_cari').val(''); CustomFreshContent();" title="Reset"><i class="fa fa-refresh fa-lg"></i></a>
                      	</div>
                 	</div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group" style="float:right">
                	<div class="btn-group">
                        <a class="btn btn-success btn-sm" href="javascript:submitTask('add');"><i class="fa fa-file-o fa-lg"></i></a>
                        <a class="btn btn-primary btn-sm" href="javascript:submitTask('edit');"><i class="fa fa-edit fa-lg"></i></a>
                        <a class="btn btn-primary btn-sm" href="javascript:submitTask('copy');" title="Duplicate"><i class="fa fa-copy fa-lg"></i></a>
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
                <th width="1" rowspan="2" style="vertical-align:middle"><input id="toggle" name="toggle" value="" onclick="checkAll();" type="checkbox"></th>
                <th width="1" rowspan="2" style="vertical-align:middle">No</th>
                <th rowspan="2" style="vertical-align:middle">Name</th>
                <th width="1" rowspan="2" style="vertical-align:middle">Type</th>
                <th width="1" rowspan="2" style="vertical-align:middle">Find</th>
                <th width="1" rowspan="2" style="vertical-align:middle">Content</th>
                <th width="1" rowspan="2" style="vertical-align:middle">Content&nbsp;iOS</th>
                <th width="1" rowspan="2" style="vertical-align:middle">Hdlr</th>
                <th width="1" rowspan="2" style="vertical-align:middle">TO(s)</th>
                <th width="1" rowspan="2" style="vertical-align:middle">Action</th>
                <th width="1" rowspan="2" style="vertical-align:middle">Wait</th>
                <th width="1" rowspan="2" style="vertical-align:middle">Input</th>
                <th colspan="2" style="text-align:center">Timer</th>
                <th width="1" rowspan="2" style="vertical-align:middle">Param</th>
                <th width="1" rowspan="2" style="vertical-align:middle">Upload</th>
                <th width="1" rowspan="2" style="vertical-align:middle">Platform</th>
                <th width="1" rowspan="2" style="vertical-align:middle">Publish</th>
                <th width="1" rowspan="2" style="vertical-align:middle">Ordering</th>
            </tr>
            <tr>
                <th width="1">Start</th>
                <th width="1">End</th>
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
			'filter_cari' : $('#filter_cari').val(),
			'filter_limit' : $('#filter_limit').val()
		});
}

function CallbackFreshContent(){
	$('.my_tooltip').each(function(index, element) {
        $(this).attr('title',$(this).attr('title')).tooltip('fixTitle');
    });
	loadClass();
}

</script>