<script>

$(document).ready(function(e) {
    $(document).keyup(function(e){
		if(e.keyCode == 27) {
			history.back(-1);
		}
	});
});

parent.document.getElementById('deviceModalTitle').innerHTML='<?php echo str_replace("'","\'",$this->view['toptitle']); ?>';

</script>

<?php $id = $this->session->userdata($this->controller.'_id'); ?>
<div class="box box-primary">
	<div class="box-header with-border">
		<a class="btn btn-success" href="javascript:submitTask('save');"><i class="fa fa-save"></i> Save</a>
        <a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
	<div class="box-body table-responsive">
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="<?php echo $action; ?>">
                    <input type="hidden" id="task" name="task" value="" />
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <div class="form-group<?=form_group_error('phone_number');?>">
                        <label>phone_number</label>
                        <input class="form-control" type="text" name="phone_number" value="<?php echo set_value('phone_number',(isset($row) ? $row->phone_number : '')); ?>"/>
                        <?php echo form_error('phone_number'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('device_name');?>">
                        <label>device_name</label>
                        <input class="form-control" type="text" name="device_name" value="<?php echo set_value('device_name',(isset($row) ? $row->device_name : '')); ?>"/>
                        <?php echo form_error('device_name'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('udid');?>">
                        <label>udid</label>
                        <input class="form-control" type="text" name="udid" value="<?php echo set_value('udid',(isset($row) ? $row->udid : '')); ?>"/>
                        <?php echo form_error('udid'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('platform_name');?>">
                        <label>platform_name</label>
                        <?=$html['platform_name'];?>
                        <?=form_error('platform_name');?>
                    </div>
                    <div class="form-group<?=form_group_error('platform_version');?>">
                        <label>platform_version</label>
                        <input class="form-control" type="text" name="platform_version" value="<?php echo set_value('platform_version',(isset($row) ? $row->platform_version : '')); ?>"/>
                        <?php echo form_error('platform_version'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('location_id');?>">
                        <label>location_name</label>
                        <?=$html['location_id'];?>
                        <?=form_error('location_id');?>
                    </div>
                    <div class="form-group<?=form_group_error('operator_id');?>">
                        <label>operator_name</label>
                        <?=$html['operator_id'];?>
                        <?=form_error('operator_id');?>
                    </div>
                    <div class="form-group<?=form_group_error('application');?>">
                        <label>application</label>
                        <?=$html['application'];?>
                        <?=form_error('application');?>
                    </div>
                    <div class="form-group<?=form_group_error('remarks');?>">
                        <label>remarks</label>
                        <textarea name="remarks" id="remarks" style="width:100%;" class="form-control" ><?php echo set_value('remarks',(isset($row) ? $row->remarks : '')); ?></textarea>
                        <?php echo form_error('remarks'); ?>
                    </div>
                    <div id="div_params" class="form-group<?=form_group_error('params[]');?>">
                    	<label>params</label>
                        <?php
						
						if(isset($_POST['params'])){
							$str_params = json_encode($this->input->post('params'));
						}else{
							$str_params = (isset($row) ? $row->params : '[{"name":"","value":""}]');
							$str_params = ($str_params ? $str_params : '[{"name":"","value":""}]');
						}
						
						$ar_params = json_decode($str_params);
						
						$i=0;
						foreach($ar_params as $params):
						if(!$i):
                        ?>
                        <div id="div_params_item_<?=$i;?>" class="form-inline div_params_item" data-id="<?=$i;?>">
                            <div class="form-group" style="padding-bottom:3px;">
                                <input class="form-control" name="params[<?=$i;?>][name]" placeholder="Name" type="text" value="<?=$params->name;?>">
                                <div class="input-group">
                                	<input class="form-control" name="params[<?=$i;?>][value]" placeholder="Value" type="text" value="<?=$params->value;?>">
                                    <div class="input-group-btn">
                                    	<button id="btn_params_item_add" type="button" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                  	</div>
                            	</div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div id="div_params_item_<?=$i;?>" class="form-inline div_params_item" data-id="<?=$i;?>">
                            <div class="form-group" style="padding-bottom:3px;">
                                <input class="form-control" name="params[<?=$i;?>][name]" placeholder="Name" type="text" value="<?=$params->name;?>">
                                <div class="input-group">
                                	<input class="form-control" name="params[<?=$i;?>][value]" placeholder="Value" type="text" value="<?=$params->value;?>">
                                    <div class="input-group-btn">
                                    	<button type="button" class="btn btn-danger btn_params_item_remove" data-params_item_id="<?=$i;?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                 	</div>
                              	</div>
                            </div>
                        </div>
                        <?php
						endif;
						$i++;
						endforeach;
                        echo form_error('params[]'); ?>
                    </div>
                    <div class="form-group<?=form_group_error('published');?>">
                        <label>Published</label>
                        <?php echo $html['published']; ?>
                        <?php echo form_error('published'); ?>
                    </div>
                </form>
            </div>
        </div>
	</div>
    <!-- /.box-body -->
    <div class="box-footer with-border" style="margin-top:0px">
		<a class="btn btn-success" href="javascript:submitTask('save');"><i class="fa fa-save"></i> Save</a>
        <a class="btn btn-warning" href="javascript:window.location='<?php echo $link_back; ?>';"><i class="fa fa-undo"></i> Cancel</a>
    </div>
    <!-- /.box-header -->
</div>
<!-- /.box -->

<!-- Icon Modal -->
<div class="modal autoheight fade" id="iconModal" style="width:auto; height:auto;">
    <div class="modal-dialog" style="width:95%; height:95%">
    
      <!-- Modal content-->
      <div class="modal-content" style="height:100%">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Select Icon</h4>
        </div>
        <div class="modal-body" style="height:100%">
            <iframe width="100%" height="400px" src="<?php echo site_url('admin/icon/search/0/icon'); ?>" frameborder="0" style="overflow: hidden;"></iframe>
        </div>
      </div>
      
    </div>
</div>

<script type="text/javascript">

$(document).ready(function(){
	
	function reposition() {
		var modal = $(this),
			dialog = modal.find('.modal-dialog');
		modal.css('display', 'block');
		
		// Dividing by two centers the modal exactly, but dividing by three 
		// or four works better for larger screens.
		dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
		//dialog.css("margin-left", Math.max(0, ($(window).width() - dialog.width()) / 2));
	}
	
	// Reposition when a modal is shown
    $('.modal').on('show.bs.modal', reposition);
	
	// Reposition when the window is resized
    $(window).on('resize', function() {
        $('.modal:visible').each(reposition);
    });
	
	$('#class').change(function(e) {
        $('#span_sample').removeClass();
		$('#span_sample').addClass('label');
		$('#span_sample').addClass('label-' + $(this).val());
    });
	
	$('#class').trigger('change');
	
	$('#btn_params_item_add').click(function(e) {
		var params_item_count = 0;
		$('.div_params_item').each(function(index, element) {
            params_item_count = $(this).attr('data-id')>params_item_count ? $(this).attr('data-id') : params_item_count;
        });
		params_item_count++;
		var params_item = '<div id="div_params_item_' + params_item_count + '" class="form-inline div_params_item" data-id="' + params_item_count + '"><div class="form-group" style="padding-bottom:3px;"><input name="params[' + params_item_count + '][name]" type="text" class="form-control" placeholder="Name"> <div class="input-group"><input name="params[' + params_item_count + '][value]" type="text" class="form-control" placeholder="Value"><div class="input-group-btn"><button type="button" class="btn btn-danger btn_params_item_remove" data-params_item_id="' + params_item_count +'"><i class="fa fa-trash" aria-hidden="true"></i></button></div></div></div></div>';
        $('#div_params').append(params_item);
    });
	
	$('#div_params').on('click','.btn_params_item_remove',function(e){
		var params_item_id = $(this).attr('data-params_item_id');
		document.getElementById('div_params').removeChild(document.getElementById('div_params_item_' + params_item_id));
	});
	
});

function deleteIcon()
{
	$('#icon').val('');
	$('#li_icon').attr('class','fa');
}

function hideIconModal(icon_value)
{
	$('#icon').val(icon_value);
	$('#li_icon').attr('class','fa ' + icon_value);
	$("#iconModal").modal("hide");
}

</script>