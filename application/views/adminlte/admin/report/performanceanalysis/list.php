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
                    <div class="input-group">
                    	<?=$html['filter_journey_id'];?>
                    </div>
                	<div class="input-group">
                        <?=$html['filter_journey_detail_id'];?>
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
        	<div id="div_content_daily" class="col-md-12">
            	<div class="row">
                	<div class="col-md-12" style="text-align:center">
                		<h4>Response Times - Daily</h4>
                   	</div>
                </div>
                <div class="row">
                	<div class="col-md-12" style="text-align:center">
                    	<div id="chart_daily"></div>
                    </div>
                </div>
            </div>
            
            <div id="div_content_frequency" class="col-md-12">
            	<div class="row">
                	<div class="col-md-12" style="text-align:center">
                		<h4>Frequency Distribution - Response Times</h4>
                   	</div>
                </div>
                <div class="row">
                	<div class="col-md-12" style="text-align:center">
                    	<div id="chart_frequency"></div>
                    </div>
                </div>
            </div>
            
            <div id="div_content_hourly" class="col-md-12">
            	<div class="row">
                	<div class="col-md-12" style="text-align:center">
                		<h4>Average Response Time - Hourly</h4>
                   	</div>
                </div>
                <div class="row">
                	<div class="col-md-12" style="text-align:center">
                    	<div id="chart_hourly"></div>
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
	
	$("#div_content_daily").LoadingOverlay("show");
	$.post(controller_url + '/listcontent/daily',
		{
			'filter_monitor_date_start' : filter_monitor_date_start,
			'filter_monitor_date_end' : filter_monitor_date_end,
			'filter_location_id' : $('#filter_location_id').val(),
			'filter_operator_id' : $('#filter_operator_id').val(),
			'filter_journey_id' : $('#filter_journey_id').val(),
			'filter_journey_detail_id' : $('#filter_journey_detail_id').val(),
		},
		function(data){
			drawChartDaily(data);
			$("#div_content_daily").LoadingOverlay("hide");
		},'json');
		
	$("#div_content_frequency").LoadingOverlay("show");
	$.post(controller_url + '/listcontent/frequency',
		{
			'filter_monitor_date_start' : filter_monitor_date_start,
			'filter_monitor_date_end' : filter_monitor_date_end,
			'filter_location_id' : $('#filter_location_id').val(),
			'filter_operator_id' : $('#filter_operator_id').val(),
			'filter_journey_id' : $('#filter_journey_id').val(),
			'filter_journey_detail_id' : $('#filter_journey_detail_id').val(),
		},
		function(data){
			drawChartFrequency(data);
			$("#div_content_frequency").LoadingOverlay("hide");
		},'json');
		
	$("#div_content_hourly").LoadingOverlay("show");
	$.post(controller_url + '/listcontent/hourly',
		{
			'filter_monitor_date_start' : filter_monitor_date_start,
			'filter_monitor_date_end' : filter_monitor_date_end,
			'filter_location_id' : $('#filter_location_id').val(),
			'filter_operator_id' : $('#filter_operator_id').val(),
			'filter_journey_id' : $('#filter_journey_id').val(),
			'filter_journey_detail_id' : $('#filter_journey_detail_id').val(),
		},
		function(data){
			drawChartHourly(data);
			$("#div_content_hourly").LoadingOverlay("hide");
		},'json');
		
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
	
	$('#filter_journey_id').change(function(e) {
		$.post(controller_url + '/getjourneydetaillist/' + $(this).val(),{},
			function(data){
				$('#filter_journey_detail_id').find('option').remove();
				var i;
				for(i=0; i<data.length; i++){
					$('#filter_journey_detail_id').append('<option value="' + data[i].value + '">'+  data[i].text +'</option>');
				}
				$("#filter_journey_detail_id").val($("#filter_journey_detail_id option:first").val());
			},'json');
        CustomFreshContent();
    });
	
	$('#filter_journey_detail_id').change(function(e) {
        CustomFreshContent();
    });
	
});

//Chart

//Page

// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(CustomFreshContent);

// Draw the chart and set the chart values
function drawChart() {
    drawChartDaily(<?=json_encode($array_daily);?>);
	drawChartFrequency(<?=json_encode($array_frequency);?>);
	drawChartHourly(<?=json_encode($array_hourly);?>);
}

function drawChartDaily(arSrc){
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
		legend: { position: 'none' },
		vAxis: {
			title: 'Secs',
			textStyle: {
				fontSize: 11,
				bold: true,
			}
		},
		hAxis: {
			// title: 'Date',
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
    var chart = new google.visualization.LineChart(document.getElementById('chart_daily'));
    chart.draw(view, options);
}

function drawChartFrequency(arSrc){
	var i;
	var total = 0;
	for(i=1; i<arSrc.length; i++){
		total = total + arSrc[i][1];
	}
	// console.log("total: " + total);
	
	arSrc1 = arSrc;
	for(i=1; i<arSrc1.length; i++){
		arSrc1[i][1] = Math.round((arSrc[i][1]/total)*100);
	}
	
	var data = google.visualization.arrayToDataTable(arSrc1);
	
	var view = new google.visualization.DataView(data);
	view.setColumns([0, 1,
					{ calc: "stringify",
						 sourceColumn: 1,
						 type: "string",
						 role: "annotation"
					}]);
	
	// Optional; add a title and set the width and height of the chart
    
	var options = {
		legend: { position: 'none' },
		vAxis: {
			title: '%',
			textStyle: {
				fontSize: 11,
			}
		},
		hAxis: {
			title: 'Response Time',
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
    var chart = new google.visualization.LineChart(document.getElementById('chart_frequency'));
    chart.draw(view, options);
}

function drawChartHourly(arSrc){
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
		legend: { position: 'none' },
		vAxis: {
			title: 'Secs',
			textStyle: {
				fontSize: 11,
			}
		},
		hAxis: {
			title: 'Hour',
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
    var chart = new google.visualization.LineChart(document.getElementById('chart_hourly'));
    chart.draw(view, options);
}

function printPage(){
	window.print();
    window.close();
}

</script>