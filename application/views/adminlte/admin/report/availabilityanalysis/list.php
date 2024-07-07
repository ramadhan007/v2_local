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
        	<div id="div_content_page" class="col-md-6">
            	<div class="row">
                	<div class="col-md-12">
                		<h3>Availability Analysis - Page Wise</h3>
                   	</div>
                </div>
                <div class="row">
                	<div class="col-md-12">
                    	<div id="piechart_page"></div>
                    </div>
                </div>
                <div class="row">
                	<div class="col-md-12 form-group" style="text-align:right">
                    	<label>Error Type</label>
                    	<?=$html['filter_schedule_page'];?>
                    </div>
                </div>
                <div class="row">
                	<div class="col-md-12">
                        <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Page Name</th>
                                <th width="1">Duration&nbsp;(mins)</th>
                            </tr>
                        </thead>
                        <tbody id="list_tbody_page">
                            <?php include('list_tbody_page.php'); ?>
                        </tbody>
                        </table>
					</div>
                </div>
            </div>
            
            <div id="div_content_error" class="col-md-6">
            	<div class="row">
                	<div class="col-md-12">
                		<h3>Availability Analysis - Error Wise</h3>
                   	</div>
                </div>
                <div class="row">
                	<div class="col-md-12">
                    	<div id="piechart_error"></div>
                    </div>
                </div>
                <div class="row">
                	<div class="col-md-12 form-group" style="text-align:right">
                    	<label>Error Type</label>
                    	<?=$html['filter_schedule_error'];?>
                    </div>
                </div>
                <div class="row">
                	<div class="col-md-12">
                        <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Error Description</th>
                                <th width="1">Duration&nbsp;(mins)</th>
                            </tr>
                        </thead>
                        <tbody id="list_tbody_error">
                            <?php include('list_tbody_error.php'); ?>
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
<input type="hidden" id="numrows" name="numrows" value="<?php echo $numrows; ?>" />
</form>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>

var controller_url = '<?=site_url($this->controller);?>';

function CustomFreshContent(param){
	
	if(param=='page' || param==''){
		$("#div_content_page").LoadingOverlay("show");
		$.post(controller_url + '/listcontent/page' ,
			{
				'filter_monitor_date_start' : filter_monitor_date_start,
				'filter_monitor_date_end' : filter_monitor_date_end,
				'filter_location_id' : $('#filter_location_id').val(),
				'filter_operator_id' : $('#filter_operator_id').val(),
				'filter_schedule_page' : $('#filter_schedule_page').val(),
				'filter_schedule_error' : $('#filter_schedule_error').val(),
			},
			function(data){
				$("#list_tbody_page").html(data);
				drawChartPage();
				$("#div_content_page").LoadingOverlay("hide");
			},'text');
	}
	
	if(param=='error' || param==''){
		$("#div_content_error").LoadingOverlay("show");
		$.post(controller_url + '/listcontent/error',
			{
				'filter_monitor_date_start' : filter_monitor_date_start,
				'filter_monitor_date_end' : filter_monitor_date_end,
				'filter_location_id' : $('#filter_location_id').val(),
				'filter_operator_id' : $('#filter_operator_id').val(),
				'filter_schedule_page' : $('#filter_schedule_page').val(),
				'filter_schedule_error' : $('#filter_schedule_error').val(),
			},
			function(data){
				$("#list_tbody_error").html(data);
				drawChartError();
				$("#div_content_error").LoadingOverlay("hide");
			},'text');
	}
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
	
	$('#filter_schedule_page').change(function(e) {
        CustomFreshContent('page');
    });
	
	$('#filter_schedule_error').change(function(e) {
        CustomFreshContent('error');
    });
	
});

//Chart

//Page

// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(CustomFreshContent1);

function CustomFreshContent1(){
	CustomFreshContent('');
}

// Draw the chart and set the chart values
function drawChart() {
    drawChartPage();
	drawChartError();
}

function drawChartPage(){
	
	var arSrc = [];
	var data1 = ["Page Name", "Total"];
	arSrc.push(data1);
	$('#list_tbody_page').children().each(function(index, element) {
		var data1 = [$(element).find('td[class="name"]').html(), parseInt($(element).find('td[class="value"]').html())];
		arSrc.push(data1);
    });
	
	var data = google.visualization.arrayToDataTable(arSrc);
    // Optional; add a title and set the width and height of the chart
    var options = {
        legend: {
            position: 'labeled'
        },
        is3D: true,
        'width': 'auto',
        'height': 'auto',
        pieSliceText: 'none',
        slices: {
            0: {offset: 0.1},
            1: {offset: 0.2},
            2: {offset: 0.2},
            3: {offset: 0.2},
            4: {offset: 0.1},
        },
    };
    // Display the chart inside the <div> element with id="piechart"
    var chart = new google.visualization.PieChart(document.getElementById('piechart_page'));
    chart.draw(data, options);
}

function drawChartError(){
	
	var arSrc = [];
	var data1 = ["Error Description", "Total"];
	arSrc.push(data1);
	$('#list_tbody_error').children().each(function(index, element) {
		var data1 = [$(element).find('td[class="name"]').html(), parseInt($(element).find('td[class="value"]').html())];
		arSrc.push(data1);
    });
	
	var data = google.visualization.arrayToDataTable(arSrc);
    // Optional; add a title and set the width and height of the chart
    var options = {
        legend: {
            position: 'labeled'
        },
        is3D: true,
        'width': 'auto',
        'height': 'auto',
        pieSliceText: 'none',
        slices: {
            0: {offset: 0.1},
            1: {offset: 0.2},
            2: {offset: 0.2},
            3: {offset: 0.2},
            4: {offset: 0.1},
        },
    };
    // Display the chart inside the <div> element with id="piechart"
    var chart = new google.visualization.PieChart(document.getElementById('piechart_error'));
    chart.draw(data, options);
}

function printPage(){
	window.print();
    window.close();
}

</script>