<form id="frm_cari" class="form-inline" method="post" action="<?php echo current_url(); ?>" role="form">
<div class="box box-primary">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <?php echo $html['filter_category_id']; ?>
                    <input name="filter_cari" id="filter_cari" class="form-control input-sm" type="text" value="<?php echo $this->session->userdata($this->controller.'_filter_cari'); ?>" title="Title, Alias, Keywords" on />
                    <a class="btn btn-primary btn-sm" href="javascript:submitTask('');"><i class="fa fa-search fa-lg"></i> Search</a>
                    <a class="btn btn-primary btn-sm" href="javascript:$('#filter_cari').val(''); submitTask('');"><i class="fa fa-refresh fa-lg"></i> Reset</a>
                </div>
            </div>
            <!-- <div class="col-sm-6">
                <div class="form-group" style="float:right">
                    <label>Category</label>
                    
                </div>
            </div> -->
        </div>
	</div>
    <!-- /.box-header -->
	
    <div class="box-body table-responsive">
        <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th width="1"><input id="toggle" name="toggle" value="" onclick="checkAll();" type="checkbox"></th>
                <th width="1">No</th>
                <th>Title</th>
                <th>Alias</th>
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
            ?>
            <tr>
                <td><?php echo $cb; ?></td>
                <td><?php echo ++$i; ?></td>
                <td><a href="javascript:ReturnID('<?php echo $row->alias; ?>');"><?php echo $row->title; ?></a></td>
                <td><?php echo $row->alias; ?></td>
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

<script type="application/javascript">

$(document).ready(function(e) {
	$('#filter_category_id').change(function(e) {
		$('#frm_cari').submit();
	}); 
});

function ReturnID(article_id)
{
	var el = parent.document.getElementById('<?php echo $field_id ?>');
	el.value = article_id;
	parent.hideArticleModal();
}

</script>