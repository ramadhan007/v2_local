<form id="frm_cari" class="form-inline" method="post" action="<?php echo current_url(); ?>" role="form">
<div class="box box-primary">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <?php echo $html['filter_category_id']; ?>
                    <div class="input-group">
                        <input name="filter_cari" id="filter_cari" class="form-control input-sm" type="text" value="<?php echo $this->session->userdata($this->controller.'_filter_cari'); ?>" title="Title, Alias, Keywords" on />
                        <div class="input-group-btn">
                        	<a class="btn btn-success btn-sm" href="javascript:submitTask('');"><i class="fa fa-search fa-lg"></i></a>
                        	<a class="btn btn-primary btn-sm" href="javascript:$('#filter_cari').val(''); submitTask('');"><i class="fa fa-refresh fa-lg"></i></a>
                      	</div>
                	</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group" style="float:right">
                	<div class="input-group-btn">
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
        <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th width="1"><input id="toggle" name="toggle" value="" onclick="checkAll();" type="checkbox"></th>
                <th width="1">No</th>
                <th>Title</th>
                <th>Alias</th>
                <th width="1"><i class="fa fa-globe fa-fw"></i></th>
                <th width="1">Images</th>
                <th width="1">Widget</th>
                <th width="1">Tampil</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0 + $offset;
            $j = 0;
            $numrows = count($rows);
            foreach($rows as $row):
                $text_published = '<i class="fa fa-check fa-lg"></i> '.($row->published=='1' ? 'YES' : 'NO');
                $class_published = ($row->published=='1' ? 'success' : 'danger');
                $published = "<span class=\"label label-$class_published\">$text_published</span>";
                
                $cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
                $link = anchor($this->controller.'/edit/'.$row->id,$row->title,array('class'=>'link'));
                
				$image = '<a id="span_image_count_'.$row->id.'" href="javascript:openImageModal('.$row->id.');" class="btn btn-info btn-xs" role="button" style="width:50px;">'.$row->image.'</a>';
                
				$widget = '<a id="span_widget_count_'.$row->id.'" href="javascript:openWidgetModal('.$row->id.')" class="btn btn-info btn-xs" role="button" style="width:50px;">'.$row->widget.'</a>';
            ?>
            <tr>
                <td><?php echo $cb; ?></td>
                <td><?php echo ++$i; ?></td>
                <td><?php echo $link; ?></td>
                <td><?php echo $row->alias; ?></td>
                <td><a href="<?php echo site_url($row->alias).".html"; ?>" target="_blank" title="Lihat link"><i class="fa fa-globe fa-fw"></i></a></td>
                <td><?php echo $image; ?></td>
                <td><?php echo $widget; ?></td>
                <td><?php echo $published; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
	</div>
	<!-- /.box-body -->
	<div class="box-footer clearfix">
      	<div class="row">
            <div class="col-sm-6">
				<?php if($numrows): ?>
                Menampilkan <?php echo $offset+1; ?> sampai <?php echo $offset+$numrows; ?> dari <?php echo $total_rows; ?> entri
                <?php else: ?>
                Tidak ada entri
                <?php endif; ?>
            </div>
            <div class="col-sm-6">
               	<?php echo $pagination; ?>
            </div>
		</div>
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
<input type="hidden" id="task" name="task" value="" />
<input type="hidden" id="numrows" name="numrows" value="<?php echo $numrows; ?>" />
</form>

<!-- Image Modal -->
<div class="modal large autoheight fade" id="imageModal" style="width:auto; height:auto;">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type=
                "button">&times;</button>
                <h4 id="imageModalTitle" class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe width="100%" src="" frameborder="0" style="overflow-x: hidden; overflow-y: scroll; "></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Widget Modal -->
<div class="modal large autoheight fade" id="widgetModal" style="width:auto; height:auto;">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type=
                "button">&times;</button>
                <h4 id="widgetModalTitle" class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe width="100%" src="" frameborder="0" style="overflow-x: hidden; overflow-y: scroll; "></iframe>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function(){
	
	$('#filter_category_id').change(function(e) {
		$('#frm_cari').submit();
	});
	
	$('input:radio[name=meta_enable]').change(function(e) {
		showElement('meta_enable','div_meta');
    });
	
	showElement('meta_enable','div_meta');
	
});

function showElement(check_element, display_element){
	if($('input:radio[name=' + check_element + ']:checked').val()=='1'){
		$('#' + display_element).css('display','inline').animate({
        	opacity: 1,
        	width: "100%"
		}, 1000);
	}
	else{
		$('#' + display_element).animate({
			opacity: 0,
			width: 0
		}, 1000, function() {
			$(this).css('display','none');
		});
	}
}

function openImageModal(article_id)
{
	$('#imageModal iframe').attr('src','<?php echo site_url('admin/articleimage/index/0'); ?>/' + article_id);
	$('#imageModal').modal("show");
}

function openWidgetModal(article_id)
{
	$('#widgetModal iframe').attr('src','<?php echo site_url('admin/articlewidget/index/0'); ?>/' + article_id);
	$('#widgetModal').modal("show");
}

function closeModal(modal_id)
{
	$('#' + modal_id).modal("hide");
}

</script>