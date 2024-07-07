<div class="box box-primary">
    <div class="box-body table-responsive">
        
        <input type="text" class="form-control" id="tags" name="tags" placeholder="Add some tags" data-role="tagsinput">
    	<input type="hidden" class="form-control" id="freeTexts" name="freeTexts">
        
        <script>
    
		$(document).ready(function(e) {
            $('#tags').tagsinput('add', 'some tag');
        });
		
		</script>
        
	</div>
	<!-- /.box-body -->
</div>
<!-- /.box -->