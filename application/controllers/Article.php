<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller {

	//controller main properties
	var $controller = "article";
	var	$title = "artikel";
	
	function __construct(){
		parent::__construct();
		
		//fill template parameters
		$this->view['title'] = 'Artikel';
		$this->view['sidebar-right'] = $this->config->item('template_user').'/assets/sidebar-category';
	}
	
	function index($id='')
	{
		$data = array();
		if($id){
			$sql = "SELECT a.*, b.name as user_name, b.picture as user_picture
				FROM tb_article as a
					inner join tb_user as b on a.user_insert = b.id
				WHERE a.id = '$id'";
			$row = get_row($sql);
			if($row){
				$row->image = "";
				$row_image = load_article_image($row->id, 'main');
				if($row_image) $row->image = $row_image->path;
				
				$row = fix_base_url($row);
				$row->body = fix_read_more($row->body);
				$data['row'] = $row;
				$this->view['title'] = $row->title;
				if($row->meta_enable){
					$this->view['keywords'] = $row->meta_keywords;
					$this->view['description'] = $row->meta_description;
				}
				$this->view['image'] = $row->image;
				$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/index';
			}
			else{
				$this->error404();
			}
		}
		else{
			$this->error404();
		}
		$this->load->view($this->config->item('template_user').'/index',$data);
	}
	
	function category($id='')
	{
		$data = array();
		if($id){
			$rowc = get_row("SELECT title, meta_enable, meta_keywords, meta_description FROM tb_category WHERE id = '$id'");
			$this->view['title'] = $rowc->title;
			$rows = get_rows("SELECT * FROM tb_article WHERE concat(',',category_id,',') like '%,$id,%' and published");
			if($this->view['title']){
				$data['rows'] = $rows;
				if($rowc->meta_enable){
					$this->view['keywords'] = $rowc->meta_keywords;
					$this->view['description'] = $rowc->meta_description;
				}
				$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/category';
			}
			else{
				$this->error404();
			}
		}
		else{
			$this->error404();
		}
		$this->load->view($this->config->item('template_user').'/index',$data);
	}
	
	function error404()
	{
		$this->view['title'] = 'HTTP Error 404';
		$this->view['keywords'] = "";
		$this->view['description'] = "";
		$this->view['content'] = $this->config->item('template_user').'/'.$this->controller.'/error';
	}
	
	function commentSave()
	{
		$this->load->helper('crypt');
		$param = Decrypt($this->input->post('param'));
		$ar_param = explode("|",$param);
		if($ar_param[0]){	//user comment
			$this->load->model('commentModel');
			$ordering = $this->commentModel->get_max_ordering($ar_param[1], $this->input->post('parent_id'))+1;
			$comment_date = get_server_time();
			$row = array(
				'article_id' => $ar_param[1],
				'parent_id' => $this->input->post('parent_id'),
				'user_id' => $ar_param[2],
				'comment' => $this->input->post('comment'),
				'comment_date' => $comment_date,
				'ordering' => $ordering,
				'published' => 1,
			);
			$id = $this->commentModel->save($row);
			echo json_encode(array(
					'success' => 1,
					'id' => $id,
					'message' => '',
				));
		}
		else{	//guest comment
			$this->load->helper('http');
			$params = array(
					'secret' => '6LeuNloUAAAAAE7WFEKMB8s0wteElvn95c5OcC-T',
					'response' => $this->input->post('g_recaptcha_response'),
					'remoteip' => '',
				);
			$response = http_load('https://www.google.com/recaptcha/api/siteverify',0,$params);
			$ar_response = json_decode($response);
			if($ar_response->success){
				$this->load->model('commentModel');
				$ordering = $this->commentModel->get_max_ordering($ar_param[1], $this->input->post('parent_id'))+1;
				$comment_date = get_server_time();
				$row = array(
					'article_id' => $ar_param[1],
					'parent_id' => $this->input->post('parent_id'),
					'name' => $this->input->post('name'),
					'email' => $this->input->post('email'),
					'comment' => $this->input->post('comment'),
					'comment_date' => $comment_date,
					'ordering' => $ordering,
					'published' => 1,
				);
				$id = $this->commentModel->save($row);
				echo json_encode(array(
						'success' => 1,
						'id' => $id,
						'message' => '',
					));
			}else{
				echo json_encode(array(
						'success' => 0,
						'id' => 0,
						'message' => 'Buktikan bahwa Anda bukan robot :)',
					));
			}
		}
	}
	
	function commentLoad()
	{
		$id = $this->input->post('id');
		$sql = "select a.id, a.article_id, a.parent_id, a.comment, a.comment_date,
			DATE_FORMAT(NOW(),'%d %b %Y %T') as comment_date_str, ifnull(b.name, a.name) as name, ifnull(b.picture, '') as picture
			from tb_article_comment as a left join tb_user as b on a.user_id = b.id
			where a.id = '$id'";
		$row = get_row($sql);
		if($row){
			if(!$row->picture) $row->picture = base_url('images/no-user.png');
			$row->time_ago = timeAgoId($row->comment_date);
			$ar_return = array('success' => 1, 'row' => $row);
		}
		else{
			$ar_return = array('success' => 0, 'row' => '');
		}
		echo json_encode($ar_return);
	}
	
	function getNextComment()
	{
		$limit = 5;
		$article_id = $this->input->post('article_id');
		$offset = $this->input->post('offset');
		$sql = "SELECT 	id
			FROM 	tb_article_comment
			WHERE 	article_id = '$article_id'
			ORDER 	BY fc_article_comment_ordering(id)
			LIMIT	$offset,$limit";
		$rows = get_rows($sql);
		if($rows){
			$ar_return = array('success' => 1, 'row_count' => count($rows), 'rows' => $rows);
		}
		else{
			$ar_return = array('success' => 0, 'row_count' => 0, 'rows' => '');
		}
		echo json_encode($ar_return);
	}
}

?>