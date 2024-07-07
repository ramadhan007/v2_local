<div class="row">
    <div class="col-lg-12">
    	<?=$greeting;?>
    	<?php if(isset($messages)): ?>
        <ul>
		<?php
        foreach($messages as $message):
			echo '<li style="margin-left: -10px">'.$message.'</li>';
		endforeach;
		?>
        </ul>
		<?php endif ?>
    </div>
</div>