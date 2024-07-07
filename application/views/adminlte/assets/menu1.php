<?php
$admindir = $this->config->item('admin');
$login_data = $this->session->userdata('login_data_admin');
$rows = load_menu('admin-menu', $login_data['user']->usertype);
if(count($rows)):
?>
<ul class="sidebar-menu" data-widget="tree">
    <li class="header">MAIN NAVIGATION</li>
    <?php
	$i=0;
	foreach($rows as $row):
	
	$row->link = str_replace('{[admin]}',$admindir,$row->link);
	$row->base_controller = str_replace('{[admin]}',$admindir,$row->base_controller);
	
	if(strpos($row->link,'://')===false && substr($row->link,0,1)!=='#' && substr($row->link,0,11)!=='javascript:')
		$row->link = site_url($row->link);
	if(strpos($row->base_controller,'://')===false && substr($row->base_controller,0,1)!=='#' && substr($row->base_controller,0,11)!=='javascript:')
		$row->base_controller = site_url($row->base_controller);
	?>
    <li<?=$row->child ? ' class="treeview"' : '';?>>
        <a href="<?=$row->link;?>" data-base_controller="<?=$row->base_controller;?>">
        	<i class="fa <?=$row->icon;?>"></i> <span><?=$row->title;?></span>
            <?php if($row->child): ?>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            <?php endif; ?>
		</a>
        <?php show_sub_menu($row, $login_data['user']->usertype, $admindir); ?>
    </li>
    <?php endforeach; ?>
</ul>

<script type="text/javascript">

var ar_folders = ['umroh', 'sipatuhi', 'inventory'];

var url = window.location;
var ar_url = String(url).split("/");
var ar_comp = String(url).split("/");
var comp_length = <?php echo count(explode("/",base_url())); ?>;

for(var i=comp_length;i<ar_url.length;i++){
	if(ar_folders.indexOf(ar_url[i])>-1){
		comp_length = comp_length + 1;
	}
}

ar_comp.length = comp_length+1;
var comp = ar_comp.join("/");

//get current element
var element = $('ul.sidebar-menu a').filter(function(){
	return $(this).attr('data-base_controller') == comp;
}).parent();
check_open_parent(element);

function check_open_parent(element){
	$(element).addClass('active');
	
	var element_ul = $(element).parent();
	if($(element_ul).hasClass('treeview-menu')){
		$(element_ul).css('display','block');
	}
	
	var element_parent = $(element_ul).parent('li');
	if($(element_parent).length){
		$(element_parent).addClass('active');
		$(element_parent).addClass('menu-open');
		check_open_parent(element_parent);
	}
}

</script>

<?php
endif;

function show_sub_menu($row_menuitem, $usertype, $admindir){
if($row_menuitem->child): ?>
<ul class="treeview-menu">
<?php
	$rows = load_sub_menu($row_menuitem->id, $usertype);
	foreach($rows as $row):
	
	$row->link = str_replace('{[admin]}',$admindir,$row->link);
	$row->base_controller = str_replace('{[admin]}',$admindir,$row->base_controller);
	
	if(strpos($row->link,'://')===false && substr($row->link,0,1)!=='#')
		$row->link = site_url($row->link);
	if(strpos($row->base_controller,'://')===false && substr($row->base_controller,0,1)!=='#')
		$row->base_controller = site_url($row->base_controller);
?>
	<li<?=$row->child ? ' class="treeview"' : '';?>>
        <a href="<?=$row->link;?>" data-base_controller="<?=$row->base_controller;?>">
        	<i class="fa <?=$row->icon;?>"></i> <span><?=$row->title;?></span>
            <?php if($row->child): ?>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            <?php endif; ?>
		</a>
        <?php show_sub_menu($row, $usertype, $admindir); ?>
	</li>
	<?php
	endforeach;
	?>
</ul>
<?php
endif;
}

?>