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
    <!-- <div class="box-header with-border">
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                	<div class="input-group">
                        <?=$html['filter_location_id'];?>
                    </div>
                    <div class="input-group">
                        <?=$html['filter_operator_id'];?>
                    </div>
                </div>
            </div>
        </div>
	</div> -->
    <!-- /.box-header -->
						
    <div class="box-body table-responsive">
        <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th width="1">No</th>
                <th width="1">ID</th>
                <!-- <th>Phone&nbsp;Number</th> -->
                <th width="1">Appl</th>
                <th>Location</th>
                <th>Operator</th>
                <th width="1">Status</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody id="list_tbody">
            <?php include('list_tbody.php'); ?>
        </tbody>
        </table>
	</div>
	<!-- /.box-body -->
	
    <!-- <div class="box-footer clearfix">
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
    </div> -->
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
			'filter_location_id' : $('#filter_location_id').val(),
			'filter_operator_id' : $('#filter_operator_id').val(),
			'filter_cari' : $('#filter_cari').val(),
			'filter_limit' : $('#filter_limit').val()
		});
}

var tmrSecond;
var tmrStatus;

$(document).ready(function(e) {
	$('#filter_location_id').change(function(e) {
        CustomFreshContent();
    });
	
	$('#filter_operator_id').change(function(e) {
        CustomFreshContent();
    });
	
	tmrSecondTick();
	
	tmrSecond = setInterval(tmrSecondTick, 1000);
	tmrStatus = setInterval(tmrStatusTick, 10000);
	
});

function tmrSecondTick() {
	$('tr.datarow').each(function(index, element) {
		if($(this).attr("data-status")=='0'){
			var t = $(this).attr("data-status_time").split(/[- :]/);
			var d_status = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5])- (7*60*60*1000));
			
			var d_now = new Date();
			
			var diff = d_now.getTime() - d_status.getTime();
	
			var msec = diff;
			var dd = Math.floor(msec / 1000 / 24 / 60 / 60);
			msec -= dd * 1000 * 24 * 60 * 60;
			var hh = Math.floor(msec / 1000 / 60 / 60);
			msec -= hh * 1000 * 60 * 60;
			var mm = Math.floor(msec / 1000 / 60);
			msec -= mm * 1000 * 60;
			var ss = Math.floor(msec / 1000);
			msec -= ss * 1000;
			
			var status_age = (dd>0 ? dd + 'd ' : '') + pad(hh,2) + ":" + pad(mm,2) + ":" + pad(ss,2);
			
			$(this).children('td.status_age').html(status_age);
		}else{
			$(this).children('td.status_age').html("");
		}
    });
}

function tmrStatusTick() {
	var ids = '';
	$('tr.datarow').each(function(index, element) {
		ids = ids + (ids!='' ? ',' : '') + $(this).attr("data-id");
    });
	$.post(controller_url + '/getdevicestatus',
		{ 'ids' : ids },
	function(data){
		for(var i=0; i<data.length; i++){
			if($('tr[data-id="' + data[i].id + '"]').attr('data-status')!=data[i].status){
				var add_class = (data[i].status=='1' ? 'btn-success' : 'btn-default');
				var rem_class = (data[i].status=='1' ? 'btn-default' : 'btn-success');
				var status_text = (data[i].status=='1' ? 'ON' : 'OFF');
				$('tr[data-id="' + data[i].id + '"]').attr('data-status',data[i].status);
				$('tr[data-id="' + data[i].id + '"]').attr('data-status_time',data[i].status_time);
				$('tr[data-id="' + data[i].id + '"]').find('a.btn_status').removeClass(rem_class).addClass(add_class);
				$('tr[data-id="' + data[i].id + '"]').find('span.status_text').html(status_text);
			}
		}
	}, 'json');
}

function pad(num, size) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
}

</script>