<form id="frm_cari" class="form-inline" method="post" action="<?php echo current_url(); ?>" role="form">
<div class="box box-primary">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="input-group">
                        <input name="filter_cari" id="filter_cari" class="form-control input-sm" type="text" value="<?php echo $this->session->userdata($this->controller.'_filter_cari'); ?>" title="Tag, Title" on />
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
                        <a class="btn btn-primary btn-sm" href="javascript:submitTask('editchild');"><i class="fa fa-file-text-o fa-lg"></i></a>
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
                <th width="1">Icon</th>
                <th width="1">Content</th>
                <th width="1">Published</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
            $i = 0 + $offset;
            $j = 0;
            $numrows = count($rows);
            foreach($rows as $row):
                $cb = '<input id="cb'.$j++.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" type="checkbox">';
                $link = anchor($this->controller.'/edit/'.$row->id,$row->title,array('class'=>'link'));
                
                $row->content = $this->categoryModel->count_content($row->id);
                $content = '<a href="'.site_url('admin/article/setcategory/'.$row->id).'" class="btn btn-info btn-xs" role="button" style="width:50px;">'.$row->content.'</a>';
                $text_published = '<i class="fa fa-'.($row->published ? 'check' : 'close').' fa-lg"></i> '.($row->published=='1' ? 'YES' : 'NO');
                $class_published = ($row->published ? 'success' : 'danger');
                $published = "<span class=\"label label-$class_published\">$text_published</span>";
            ?>
            <tr>
                <td align="center"><?php echo $cb; ?></td>
                <td><?php echo ++$i; ?></td>
                <td><?php echo $link; ?></td>
                <td><?php echo $row->alias; ?></td>
                <td align="center"><li class="fa <?php echo $row->icon; ?>"></li></td>
                <td align="center"><?php echo $content; ?></td>
                <td align="center"><?php echo $published; ?></td>
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