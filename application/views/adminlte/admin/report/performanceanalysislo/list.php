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
    	<div class="row">
        	<div id="div_content_location" class="col-md-12">
                <div class="row">
                	<div class="col-md-12" style="text-align:center">
                    	<div id="chart_location" style="height:300px"></div>
                    </div>
                	<div class="col-md-12" style="text-align:center">
                    	<table id="tbl_legend_location" align="center">
                        	<?php include('tbl_legend_location.php'); ?>
                        </table>
                    </div>
                </div>
            </div>
            
            <div id="div_content_operator" class="col-md-12">
                <div class="row">
                	<div class="col-md-12" style="text-align:center">
                    	<div id="chart_operator" style="height:300px"></div>
                    </div>
                	<div class="col-md-12" style="text-align:center">
                    	<table id="tbl_legend_operator" align="center">
                        	<?php include('tbl_legend_operator.php'); ?>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>       
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
</form>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>

var controller_url = '<?=site_url($this->controller);?>';

function CustomFreshContent(){
	
	$("#div_content_location").LoadingOverlay("show");
	$.post(controller_url + '/listcontent/location',
		{
			'filter_monitor_date_start' : filter_monitor_date_start,
			'filter_monitor_date_end' : filter_monitor_date_end,
			'filter_location_id' : $('#filter_location_id').val(),
			'filter_operator_id' : $('#filter_operator_id').val(),
		},
		function(data){
			$('#tbl_legend_location').html(data);
			drawChartLocation();
			$("#div_content_location").LoadingOverlay("hide");
		},'text');
		
	$("#div_content_operator").LoadingOverlay("show");
	$.post(controller_url + '/listcontent/operator',
		{
			'filter_monitor_date_start' : filter_monitor_date_start,
			'filter_monitor_date_end' : filter_monitor_date_end,
			'filter_location_id' : $('#filter_location_id').val(),
			'filter_operator_id' : $('#filter_operator_id').val(),
		},
		function(data){
			$('#tbl_legend_operator').html(data);
			drawChartOperator();
			$("#div_content_operator").LoadingOverlay("hide");
		},'text');
		
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
	
});

//Chart

//Page

// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(CustomFreshContent);

// Draw the chart and set the chart values
function drawChart() {
    drawChartLocation();
	drawChartOperator();
}

function drawChartLocation(){
	
	var arSrc = $('#tbody_legend_location').data('array');
	var arColor = $('#tbody_legend_location').data('color');
	
	var data = google.visualization.arrayToDataTable(arSrc);
	
	var view = new google.visualization.DataView(data);
	view.setColumns([0, 1,
					{ calc: "stringify",
						 sourceColumn: 1,
						 type: "string",
						 role: "annotation"
					}]);
	
	// Optional; add a title and set the width and height of the chart
    var options = {
		title: 'Response Times - Location Wise',
		colors: arColor,
		legend: { position: 'none' },
		vAxis: {
			title: 'Secs',
			textStyle: {
				fontSize: 11,
				bold: true,
			}
		},
		hAxis: {
			slantedText:true, 
			slantedTextAngle:45,
			textStyle: {
				fontSize: 11,
			}
		},
		annotations: {
			alwaysOutside: true,
			textStyle: {
				fontSize: 11,
				bold: true,
			}
		},
	};
	
    // Display the chart inside the <div> element with id="piechart"
    var chart = new google.visualization.LineChart(document.getElementById('chart_location'));
    chart.draw(data, options);
}

function drawChartOperator(){
	
	var arSrc = $('#tbody_legend_operator').data('array');
	
	var data = google.visualization.arrayToDataTable(arSrc);
	
	var view = new google.visualization.DataView(data);
	view.setColumns([0, 1,
					{ calc: "stringify",
						 sourceColumn: 1,
						 type: "string",
						 role: "annotation"
					}]);
	
	// Optional; add a title and set the width and height of the chart
    
	var options = {
		title : 'Response Times - Operator Wise',
		colors: <?=json_encode(get_array_color());?>,
		legend: { position: 'none' },
		vAxis: {
			title: 'Secs',
			textStyle: {
				fontSize: 11,
			}
		},
		hAxis: {
			slantedText:true, 
			slantedTextAngle:45,
			textStyle: {
				fontSize: 11,
			}
		},
		annotations: {
			alwaysOutside: true,
			textStyle: {
				fontSize: 11,
				bold: true,
			}
		},
	};
	
    // Display the chart inside the <div> element with id="piechart"
    var chart = new google.visualization.LineChart(document.getElementById('chart_operator'));
    chart.draw(data, options);
}

function printPage(){
	window.print();
    window.close();
}

</script>