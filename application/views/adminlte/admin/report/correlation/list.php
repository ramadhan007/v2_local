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

.dot {
  height: 15px;
  width: 15px;
  background-color: #bbb;
  border-radius: 50%;
  display: inline-block;
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
        	<div class="col-sm-12">
            	<div class="row">
                	<div class="col-md-6">
                    	<div class="row">
                        	<div class="col-md-12">
                        		<h3 align="center">Poor Network Contribution</h3>
                                <h4 align="center">to Overall Result</h4>
                           	</div>
                        </div>
                        <div id="div_content_chart_poor" class="row" style="min-height:100px;">
                            <div class="col-md-12">
                                <div id="chart_poor" align="center"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    	<div class="row">
                        	<div class="col-md-12">
                        		<h3 align="center">Potentially Degraded Performance Event</h3>
                           	</div>
                        </div>
                        <div id="div_content_chart_event" class="row" style="min-height:100px;">
                            <div class="col-md-12">
                                <div id="chart_event" align="center" style="min-height:300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                	<div class="col-md-8">
                    	<div class="row">
                        	<div class="col-md-12">
                        		<h3 align="center">Event Transaction Performance</h3>
                                <h4 align="center">on Poor Network Condition</h4>
                           	</div>
                        </div>
                        <div id="div_content_chart_error" class="row" style="min-height:100px;">
                            <div class="col-md-12">
                                <div id="chart_error" align="center"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                    	<div class="row">
                        	<div class="col-md-12">
                        		<h3 align="center">Network Contribution to The Services</h3>
                           	</div>
                        </div>
                        <div id="div_content_chart_network" class="row" style="min-height:100px;">
                            <div class="col-md-12">
                                <div id="chart_network" align="center"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="div_content" class="row" style="min-height:100px;">
                	<div class="col-md-12">
                        <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Response Time</th>
                                <th>NVT Results</th>
                                <th>Signal State</th>
                                <th>Network Condition</th>
                                <th>Description of Network Condition</th>
                                <th width="1">#&nbsp;Samples</th>
                            </tr>
                        </thead>
                        <tbody id="list_tbody">
                            
                        </tbody>
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
	
	$("#div_content").LoadingOverlay("show");
	$("#div_content_chart_poor").LoadingOverlay("show");
	$("#div_content_chart_network").LoadingOverlay("show");
	$.post(controller_url + '/listcontent' ,
		{
			'filter_monitor_date_start' : filter_monitor_date_start,
			'filter_monitor_date_end' : filter_monitor_date_end,
			'filter_location_id' : $('#filter_location_id').val(),
			'filter_operator_id' : $('#filter_operator_id').val(),
			'filter_journey_id' : $('#filter_journey_id').val(),
		},
		function(data){
			$("#list_tbody").html(data);
			drawChart();
			drawChartNetwork();
			$("#div_content").LoadingOverlay("hide");
			$("#div_content_chart_poor").LoadingOverlay("hide");
			$("#div_content_chart_network").LoadingOverlay("hide");
		},'text');
		
	$("#div_content_chart_event").LoadingOverlay("show");
	$.post(controller_url + '/listcontent/event' ,
		{
			'filter_monitor_date_start' : filter_monitor_date_start,
			'filter_monitor_date_end' : filter_monitor_date_end,
			'filter_location_id' : $('#filter_location_id').val(),
			'filter_operator_id' : $('#filter_operator_id').val(),
			'filter_journey_id' : $('#filter_journey_id').val(),
		},
		function(data){
			drawChartEvent(data);
			$("#div_content_chart_event").LoadingOverlay("hide");
		},'json');
		
	$("#div_content_chart_error").LoadingOverlay("show");
	$.post(controller_url + '/listcontent/error' ,
		{
			'filter_monitor_date_start' : filter_monitor_date_start,
			'filter_monitor_date_end' : filter_monitor_date_end,
			'filter_location_id' : $('#filter_location_id').val(),
			'filter_operator_id' : $('#filter_operator_id').val(),
			'filter_journey_id' : $('#filter_journey_id').val(),
		},
		function(data){
			drawChartError(data);
			$("#div_content_chart_error").LoadingOverlay("hide");
		},'json');
}

var filter_monitor_date_start = '<?=$this->session->userdata('filter_monitor_date_start') ? $this->session->userdata('filter_monitor_date_start') : date('d-m-Y 00:00');?>';
var filter_monitor_date_end = '<?=$this->session->userdata('filter_monitor_date_end') ? $this->session->userdata('filter_monitor_date_end') : date('d-m-Y 23:59');?>';

$(document).ready(function(e) {
    $('#filter_location_id').change(function(e) {
        CustomFreshContent('');
    });
	
	$('#filter_operator_id').change(function(e) {
        CustomFreshContent('');
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
		CustomFreshContent('');
	});
	
	$('#filter_monitor_date').on('cancel.daterangepicker hide.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD-MM-YYYY HH:mm') + ' to ' + picker.endDate.format('DD-MM-YYYY HH:mm'));
	});
	
	$('#filter_journey_id').change(function(e) {
        CustomFreshContent();
    });
	
	// CustomFreshContent();
	
});

//Chart

// Load google charts
google.charts.load('current', {'packages':['gauge', 'corechart']});
google.charts.setOnLoadCallback(CustomFreshContent);

// Draw the chart and set the chart values
function drawChart(){
	
	var tot = 0;
	$('.num').each(function(index, element) {
        tot = tot + parseInt($(this).val());
    });
	
	var tot_poor = 0;
	$('.num_poor').each(function(index, element) {
        tot_poor = tot_poor + parseInt($(this).val());
    });
	
	var chart_value = Math.round((tot_poor/tot)*100*100)/100;
	
	var data = google.visualization.arrayToDataTable([
		['Label', 'Value'],
		['%', 0],
	]);
	var options = {
		height: 300,
		greenFrom: 0, greenTo: 27.5,
		yellowFrom: 27.5, yellowTo: 72.5,
		redFrom: 72.5, redTo: 100,
		minorTicks: 10,
		majorTicks: ['0','10','20','30','40', '50', '60', '70', '80', '90', '100']
	};
	var chart = new google.visualization.Gauge(document.getElementById('chart_poor'));
	chart.draw(data, options);
	
	setInterval(function() {
          data.setValue(0, 1, chart_value);
          chart.draw(data, options);
        }, 1000);

	
}

function drawChartEvent(arSrc){
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
			title: 'No of Event',
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
    var chart = new google.visualization.ColumnChart(document.getElementById('chart_event'));
    chart.draw(view, options);
}

function drawChartError(arSrc){
	var data = google.visualization.arrayToDataTable(arSrc);
	
	var options = {
		chartArea: {
			left: 0,
			height: 250,
			width: 600
		},
		height: 300,
		width: 600,
		pieHole: 0.6,
		colors: <?=json_encode(get_array_color());?>,
	};

	var chart = new google.visualization.PieChart(document.getElementById('chart_error'));
	chart.draw(data, options);
}

function drawChartNetwork(){
	var tot = 0;
	$('.num').each(function(index, element) {
        tot = tot + parseInt($(this).val());
    });
	
	var tot_poor = 0;
	$('.num_poor').each(function(index, element) {
        tot_poor = tot_poor + parseInt($(this).val());
    });
	
	var value_poor = tot_poor;
	var value_good = tot-tot_poor;
	
	var data = google.visualization.arrayToDataTable([
		['Label', 'Value'],
		['Unlikely Cell-Networks Issue', value_good],
		['Potentially degrade Performance', value_poor],
	]);
	
	var options = {
		chartArea: {
			left: 0,
			height: 250,
			width: 300
		},
		height: 300,
		width: 600,
		pieHole: 0.6,
		colors: ["#109618", "#dc3912"],
	};

	var chart = new google.visualization.PieChart(document.getElementById('chart_network'));
	chart.draw(data, options);
}

function printPage(){
	window.print();
    window.close();
}

</script>