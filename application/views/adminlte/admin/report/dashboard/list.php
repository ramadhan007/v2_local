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
                    	<a class="btn btn-primary btn-sm" href="javascript:printPage();" title="Print to Printer/PDF" data-toggle="tooltip" ><i class="fa fa-print fa-lg"></i>&nbsp;Print</a>
                  	</div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-header -->
    
	<div class="box-body table-responsive">
    	<h3>Journey Wise - Comulative of Page's RT</h3>
    	<table class="table table-striped table-hover">
      	<thead>
        	<tr>
                <th rowspan="2" style="text-align:center; vertical-align:middle" width="1">&nbsp;</th>
                <th rowspan="2" style="text-align:center; vertical-align:middle" >Journey</th>
                <th rowspan="2" style="text-align:center; vertical-align:middle" width="1">No&nbsp;of Pages</th>
                <th rowspan="2" style="text-align:center; vertical-align:middle" width="1">Total Cycles</th>
                <th rowspan="2" style="text-align:center; vertical-align:middle" width="1">Journey Success</th>
                <th rowspan="2" style="text-align:center; vertical-align:middle" width="1">Journey Error</th>
                <th colspan="5" style="text-align:center; vertical-align:middle" width="1">Journey Duration (secs)</th>
                <th rowspan="2" style="text-align:center; vertical-align:middle" width="1">IT Availability</th>
                <th rowspan="2" style="text-align:center; vertical-align:middle" width="1">End User Availability</th>
                <!-- <th colspan="3" style="text-align:center;" width="1">NVT (%)</th> -->
            </tr>
            <tr>
            	<th rowspan="1" style="text-align:center; vertical-align:middle" width="1">Min</th>
            	<th rowspan="1" style="text-align:center; vertical-align:middle" width="1">Median</th>
                <th rowspan="1" style="text-align:center; vertical-align:middle" width="1">Average</th>
                <th rowspan="1" style="text-align:center; vertical-align:middle" width="1">90th Percentile</th>
                <th rowspan="1" style="text-align:center; vertical-align:middle" width="1">Max</th>
            </tr>
            <!-- <tr>
                <th width="1">Sig&gt;= -85dBm</th>
                <th width="1">API RT<200&nbsp;ms</th>
                <th width="1">Ping Success</th>
            </tr> -->
        </thead>
        <tbody id="list_tbody">
			<?php include('list_tbody.php'); ?>
        </tbody>
      	</table>
        
        <h3>Page-wise KPI Details</h3>
    	<table class="table table-striped table-hover">
      	<thead>
        	<tr>
            	<th rowspan="2" width="1">No</th>
                <th rowspan="2">Page Name</th>
                <th rowspan="2" style="text-align:center; vertical-align:middle" width="1">Total Cycles</th>
                <th rowspan="2" style="text-align:center; vertical-align:middle" width="1">Page Success</th>
                <th rowspan="2" style="text-align:center; vertical-align:middle" width="1">Page Error</th>
                <th colspan="5" style="text-align:center;">Response Time (secs)</th>
                <th rowspan="2" style="text-align:center; vertical-align:middle" width="1">Applications Index</th>
                <th rowspan="2" style="text-align:center; vertical-align:middle" width="1">IT Availability</th>
                <th rowspan="2" style="text-align:center; vertical-align:middle" width="1">End User Availability</th>
                <th colspan="3" style="text-align:center;">NVT (%)</th>
            </tr>
            <tr>
            	<th rowspan="1" style="text-align:center; vertical-align:middle" width="1">Min</th>
            	<th rowspan="1" style="text-align:center; vertical-align:middle" width="1">Median</th>
                <th rowspan="1" style="text-align:center; vertical-align:middle" width="1">Average</th>
                <th rowspan="1" style="text-align:center; vertical-align:middle" width="1">90th Percentile</th>
                <th rowspan="1" style="text-align:center; vertical-align:middle" width="1">Max</th>
                <th rowspan="1" style="text-align:center; vertical-align:middle" width="1">Sig&gt;= -85dBm</th>
                <th rowspan="1" style="text-align:center; vertical-align:middle" width="1">Ping Success</th>
                <th rowspan="1" style="text-align:center; vertical-align:middle" width="1">API RT<200&nbsp;ms</th>
            </tr>
        </thead>
        <tbody id="list_tbody_detail">
        </tbody>
      	</table>
        
    </div>
    <!-- /.box-body -->
    
    <div class="box-footer clearfix">
      	<div class="row">
        	<div class="col-sm-12">
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
			'filter_limit' : $('#filter_limit').val(),
			'filter_monitor_date_start' : filter_monitor_date_start,
			'filter_monitor_date_end' : filter_monitor_date_end,
			'filter_location_id' : $('#filter_location_id').val(),
			'filter_operator_id' : $('#filter_operator_id').val(),
		});
}

function CallbackFreshContent(){
	$('.status_tooltip').each(function(index, element) {
        $(this).attr('title',$(this).attr('title')).tooltip('fixTitle');
    });
	// loadClass();
	
	// popover
	var options = {
		html: true,
		trigger:'hover',
		placement: "left",
		content: function(){return '<img class="img-responsive" src="'+$(this).data('img') + '" />';}
	};
	
	$('a[rel=popover]').popover(options);
	
	$('#list_tbody_detail').html('');
}

var filter_monitor_date_start = '<?=$this->session->userdata('filter_monitor_date_start') ? $this->session->userdata('filter_monitor_date_start') : date('d-m-Y 00:00');?>';
var filter_monitor_date_end = '<?=$this->session->userdata('filter_monitor_date_end') ? $this->session->userdata('filter_monitor_date_end') : date('d-m-Y 23:59');?>';

$(document).ready(function(e) {
    $('#filter_location_id').change(function(e) {
        CustomFreshContent();
    });
	
	$('#filter_operator_id').change(function(e) {
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
	
	// loadClass();
	
	// popover
	var options = {
		html: true,
		trigger:'hover',
		placement: "left",
		content: function(){return '<img class="img-responsive" src="'+$(this).data('img') + '" />';}
	};
	
	$('a[rel=popover]').popover(options);
	
	CustomFreshContent();
	
});

function loadClass(){
	var index_start = $('#list_tbody').children('tr:first-child').find('input[name="cid[]"]').attr('data-index');
	var index_end = $('#list_tbody').children('tr:last-child').find('input[name="cid[]"]').attr('data-index');
	
	var ids = "";
	
	var i = index_start;
	var j = 0;
	
	var id = 0;
	while(i<=index_end){
		j++;
		ids = ids + (ids!="" ? "," : "") + $('input[name="cid[]"][data-index="' + i + '"]').val();
		if(j==20 || i==index_end){
			subLoadClass(ids);
			ids = "";
			j=0;
		}
		i++;
	}
}

function subLoadClass(ids){
	$.post(controller_url + '/getclass', {'ids' : ids}, function(data){
		var i;
		for(i=0; i<data.length; i++){
			$('.signal_level[data-id="' + data[i].id + '"]').removeClass('label-default').addClass('label-' + data[i].signal_level_class);
			$('.response_time[data-id="' + data[i].id + '"]').removeClass('label-default').addClass('label-' + data[i].response_time_class);
		}
	},'json');
}

function loadDetail(obj){
	var journey_id = $(obj).attr('data-id');
	$("#list_tbody").find("#journey_loading_" + journey_id).css('display','inline');
	$("#list_tbody_detail").LoadingOverlay("show");
	$.post(controller_url + '/listcontentdetail/' + journey_id, {}, function(data){
		$("#list_tbody_detail").html(data);
		$("#list_tbody").find("#journey_loading_" + journey_id).css('display','none');
		$("#list_tbody_detail").LoadingOverlay("hide");
	},'text');
}

function printPage(){
	window.print();
    window.close();
}

</script>