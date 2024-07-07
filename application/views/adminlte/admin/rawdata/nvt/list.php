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
            <div class="col-sm-10">
                <div class="form-group">
                	<div class="input-group">
                    	<?=$html['filter_location_id'];?>
                    </div>
                	<div class="input-group">
                        <?=$html['filter_operator_id'];?>
                  	</div>
                    <div class="input-group">
                        <?=$html['filter_journey_id'];?>
                  	</div>
                    <!-- <div class="input-group">
                        <?=$html['filter_journey_detail_id'];?>
                  	</div> -->
                    <div class="input-group">
                        <?=$html['filter_status'];?>
                  	</div>
                    <div class="input-group">
                    	<div class="input-group-addon">
                        	<i class="fa fa-calendar"></i>
                      	</div>
                      	<input type="text" class="form-control input-sm pull-right" id="filter_monitor_date" style="min-width:210px;">
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group" style="float:right">
                	<div class="btn-group">
                    	<a class="btn btn-success btn-sm" href="javascript:exportData();" title="Download Raw Data" data-toggle="tooltip" ><i class="fa fa-download fa-lg"></i>&nbsp;Download</a>
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
                <th width="1">No</th>
                <th width="1">Loc</th>
                <th width="1">Oper</th>
                <th>Journey</th>
                <th width="1">Cell</th>
                <th width="1">Lat</th>
                <th width="1">Lng</th>
                <th width="1">RT</th>
                <th width="1">SS(dBm)</th>
                <th width="1">SQ(dB)</th>
                <th width="1">BER</th>
                <th width="1">Resp(s)</th>
                <th width="1">Ping(ms)</th>
                <th width="1">Loss</th>
                <th width="1">Status</th>
                <th width="1">Date&nbsp;Time</th>
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
        	<div id="div_progress" class="col-sm-12" style="display:none; margin-bottom:10px;">
            	<div style="margin-top:5px;">
                    <span id="span_progress" style="float:left; padding-right:3px;">
                    Preparing download 0%
                    </span>
                    <div class="progress" style="overflow:hidden; margin-bottom:unset;">
                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        </div>
                    </div>
				</div>
            </div>
            <div id="div_paginfo" class="col-sm-12" style="display:block">
            	<div id="div_paginfo_div" style="margin-top:5px;">
					<?php if($numrows): ?>
                    Showing <?php echo $offset+1; ?> to <?php echo $offset+$numrows; ?> of <?php echo $total_rows; ?> entries
                    <?php else: ?>
                    No record
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-12">
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
<div class="modal large autoheight fade" id="detailModal" style="width:auto; height:auto;">
    <div class="modal-dialog" style="width:60%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type=
                "button">&times;</button>
                <h4 id="detailModalTitle" class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe width="100%" src="" frameborder="0" style="overflow-x: hidden; overflow-y: scroll; "></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" style="width:auto; height:auto;">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 id="exportModalTitle" class="modal-title">Download Raw Data</h4>
            </div>
            <div class="modal-body">
            	<!-- Location -->
            	<div class="form-group">
					<label>Location:</label>
                    <?=$html['export_location_id'];?>
                    <div class="checkbox" style="margin-top:0px">
                    	<label><input type="checkbox" id="select_all_location">Select All Location</label>
                    </div>
                    <!-- /.input group -->
				</div>
                <!-- Operarot -->
            	<div class="form-group">
					<label>Operator:</label>
                    <?=$html['export_operator_id'];?>
                    <div class="checkbox" style="margin-top:0px">
                    	<label><input type="checkbox" id="select_all_operator">Select All Operator</label>
                    </div>
                    <!-- /.input group -->
				</div>
            	<!-- Date range -->
				<div class="form-group">
					<label>Date Range:</label>
                    <div class="input-group">
                    	<div class="input-group-addon">
                        	<i class="fa fa-calendar"></i>
                      	</div>
                      	<input type="text" class="form-control pull-right" id="export_date">
                    </div>
                    <!-- /.input group -->
				</div>
                <a id="btn_download" class="btn btn-success btn-sm" href="javascript:doExportData();" title="Download Raw Data"><i class="fa fa-download fa-lg"></i>&nbsp;Download</a>
            </div>
        </div>
    </div>
</div>

<script>

var controller_url = '<?=site_url($this->controller);?>';

function CustomFreshContent(offset='', with_loading = true){
	$("section.content").LoadingOverlay("show");
	var postdata = {
			'filter_cari' : $('#filter_cari').val(),
			'filter_limit' : $('#filter_limit').val(),
			'filter_monitor_date_start' : filter_monitor_date_start,
			'filter_monitor_date_end' : filter_monitor_date_end,
			'filter_location_id' : $('#filter_location_id').val(),
			'filter_operator_id' : $('#filter_operator_id').val(),
			'filter_journey_id' : $('#filter_journey_id').val(),
			'filter_status' : $('#filter_status').val(),
		};
	$.post(controller_url + '/listcontent/all/' + offset,postdata,function(data){
		$('#list_tbody').html(data.tbody);
		$('#div_paginfo_div').html(data.paginfo);
		$('#div_pagin').html(data.pagin);
		CallbackFreshContent();
		$("section.content").LoadingOverlay("hide");
	},'json');
}

function CallbackFreshContent(){
	$('.status_tooltip').each(function(index, element) {
        $(this).attr('title',$(this).attr('title')).tooltip('fixTitle');
    });
	
	// popover
	var options = {
		html: true,
		trigger:'hover',
		placement: "left",
		content: function(){return '<img class="img-responsive" src="'+$(this).data('img') + '" />';}
	};
	
	$('a[rel=popover]').popover(options);
}

var filter_monitor_date_start = '<?=$this->session->userdata('filter_monitor_date_start') ? $this->session->userdata('filter_monitor_date_start') : date('d-m-Y 00:00');?>';
var filter_monitor_date_end = '<?=$this->session->userdata('filter_monitor_date_end') ? $this->session->userdata('filter_monitor_date_end') : date('d-m-Y 23:59');?>';

var export_date_start = '<?php echo date('d-m-Y'); ?>';
var export_date_end = '<?php echo date('d-m-Y'); ?>';

function exportData(){
	$('#exportModal').modal({
  		backdrop: 'static',
  		keyboard: false
	});
	$('#exportModal').modal("show");
}

var timer = null;
var valuemin = 0;
var valuemax = 0;

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

function doExportData(){
	$('#exportModal').modal("hide");
	
	var export_location_id = encodeURI($('#export_location_id').val().toString().replaceAll(","," "));
	var export_operator_id = encodeURI($('#export_operator_id').val().toString().replaceAll(","," "));
	
	$.post('<?=site_url($this->controller);?>/checkdownloadxls/' + export_location_id + '/' + export_operator_id + '/' + export_date_start + '/' + export_date_end, {},
	function(data){
		if(parseInt(data)>0){
			document.location = '<?=site_url($this->controller);?>/downloadxls/' + export_location_id + '/' + export_operator_id + '/' + export_date_start + '/' + export_date_end;
			$('#span_progress').html("Preparing download 0%");
			$('.progress-bar').css('width', '0%');
			$('#div_progress').css('display','inline-block');
			timer = setInterval("DownloadProgress()", 250);
		}else{
			alert('No record found!');
		}
	},
	'text');
}

function DownloadProgress(){
	$.post('<?=site_url('php/progress.php');?>',{
			'controller':'<?=$this->controller;?>',
			'process':'download',
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
			$('#span_progress').html("Preparing download 0%");
		}
		else{
			if(data.valuenow==valuemax){
				clearInterval(timer);
				$('.progress-bar').attr('aria-valuenow',$('.progress-bar').attr('aria-valuemax'))
				$('.progress-bar').css('width', '100%');
				$('#span_progress').html("Preparing download 0%");
				$('#btn_sync_smr').removeClass('disabled');
				$('.progress').removeClass('active');
				setTimeout(function(){
					$('#div_progress').fadeOut(2000, function(){
						$(this).css('display','none');
						$('#div_paginfo').css('display','block');
						$('.progress-bar').css('width', '100%');
					});
				}, 1000);
			}else{
				$('.progress-bar').attr('aria-valuenow',data.valuenow);
				$('.progress-bar').css('width', Math.round((data.valuenow/valuemax)*100) + '%');
				$('#span_progress').html("Preparing download " + Math.round((data.valuenow/valuemax)*100) + "%");
			}
		}
	},'json');
}

$(document).ready(function(e) {
    $('#filter_location_id').change(function(e) {
        CustomFreshContent();
    });
	
	$('#filter_operator_id').change(function(e) {
        CustomFreshContent();
    });
	
	$('#filter_journey_id').change(function(e) {
		 CustomFreshContent();
    });
	
	/* $('#filter_journey_detail_id').change(function(e) {
        CustomFreshContent();
    }); */
	
	$('#filter_status').change(function(e) {
        CustomFreshContent();
    });
	
	$('#filter_monitor_date').daterangepicker({
		timePicker: true,
		locale: {
      		format: 'DD-MM-YYYY HH:mm'
		},
		startDate: filter_monitor_date_start,
        endDate: filter_monitor_date_end,
	},
	function(start, end){
		filter_monitor_date_start = start.format('DD-MM-YYYY HH:mm');
		filter_monitor_date_end = end.format('DD-MM-YYYY HH:mm');
	},
	);
	
	$('#filter_monitor_date').val(filter_monitor_date_start + ' to ' + filter_monitor_date_end);
	
	$('#filter_monitor_date').on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD-MM-YYYY HH:mm') + ' to ' + picker.endDate.format('DD-MM-YYYY HH:mm'));
		CustomFreshContent();
	});
	
	$('#filter_monitor_date').on('cancel.daterangepicker hide.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD-MM-YYYY HH:mm') + ' to ' + picker.endDate.format('DD-MM-YYYY HH:mm'));
	});
	
	setInterval(function(){
		$.post(controller_url + '/checknewdata', {}, function(data){
			if(data=='1') CustomFreshContent();
		}, 'text');
	}, 10000);
	
	// popover
	var options = {
		html: true,
		trigger:'hover',
		placement: "left",
		content: function(){return '<img class="img-responsive" src="'+$(this).data('img') + '" />';}
	};
	
	$('a[rel=popover]').popover(options);
	
	//EXPORT MODAL
	$('.select2').width("74%");
	
	$("#select_all_location").click(function(){
		var values = [];
		if($("#select_all_location").is(':checked') ){
			$("#export_location_id > option").each(function() {
				values.push(this.value);
			});
		}
		$("#export_location_id").val(values).trigger('change');
	});
	
	$("#select_all_operator").click(function(){
		var values = [];
		if($("#select_all_operator").is(':checked') ){
			$("#export_operator_id > option").each(function() {
				values.push(this.value);
			});
		}
		$("#export_operator_id").val(values).trigger('change');
	});
	
	$('#export_date').daterangepicker({
		locale: {
      		format: 'DD-MM-YYYY'
		},
		startDate: export_date_start,
        endDate: export_date_end,
		drops: "up",
	},
	function(start, end){
		export_date_start = start.format('DD-MM-YYYY');
		export_date_end = end.format('DD-MM-YYYY');
	},
	);
	
	$('#export_date').val(export_date_start + ' to ' + export_date_end);
	
	$('#export_date').on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD-MM-YYYY') + ' to ' + picker.endDate.format('DD-MM-YYYY'));
	});
	
	$('#export_date').on('cancel.daterangepicker hide.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD-MM-YYYY') + ' to ' + picker.endDate.format('DD-MM-YYYY'));
	});
	
	CustomFreshContent();
	
});

function openDetailModal(journey_id)
{
	$('#detailModal iframe').attr('src','<?php echo site_url('admin/journeydetail/setjourneyid'); ?>/' + journey_id);
	$('#detailModal').modal("show");
}

function closeModal(modal_id)
{
	$('#' + modal_id).modal("hide");
}

</script>