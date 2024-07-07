<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common extends CI_Controller {
	
	function __construct(){
		parent::__construct();
	}
	
	function index(){
		echo "";
	}
	
	function loadcontact(){
		/* $sql = "
				SELECT	b.`phone` AS sekolah_phone, IFNULL(c.`id`,'') AS id, IFNULL(d.`name`,'') AS name,
						IFNULL(d.`phone`,'') AS phone
				FROM 	tb_umroh_paket AS a
						INNER JOIN tb_sekolah AS b ON a.`sekolah_id` = b.`id`
						LEFT JOIN tb_contact AS c ON c.`sekolah_id` = b.`id`
						LEFT JOIN tb_user AS d ON c.`user_id` = d.`id`
				WHERE	a.`id` = '$paket_umroh_id'
		"; */
		$sekolah_id = get_sekolah_id();
		$sql = "
				SELECT	b.`phone` AS sekolah_phone, IFNULL(c.`id`,'') AS id, IFNULL(d.`name`,'') AS name,
						IFNULL(d.`phone`,'') AS phone
				FROM 	tb_sekolah AS b
						LEFT JOIN tb_contact AS c ON (c.`sekolah_id` = b.`id` AND NOT c.deleted)
						LEFT JOIN tb_user AS d ON c.`user_id` = d.`id`
				WHERE	b.`id` = '$sekolah_id'";
		$rows = get_rows($sql);
		echo json_encode($rows);
	}
	
	function contactdetail($contact_id){
		$sql = "
				select	ifnull(b.name,'') as name, ifnull(b.email,'') as email, 
						ifnull(b.phone,'') as phone, ifnull(b.picture,'') as picture
				from 	tb_contact as a
						inner join tb_user as b on a.user_id = b.id
				where	a.id = '$contact_id'
		";
		$row = get_row($sql);
		echo json_encode(fix_base_url($row));
	}
	
	public function downloadpdf()
  	{
		$this->load->helper('senofile');
		
		$filename = $this->input->post('filename');
		
		$content = $this->input->post('content');
		$content = str_replace('<tbody>','',$content);
		$content = str_replace('</tbody>','',$content);
		
		$content = str_replace('<p> </p>','<p><br></p>',$content);
		while(strpos($content,'  ')!==false)
		{
			$content = str_replace('  ',' ',$content);
		}
		$content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
		
		$this->load->library('dom_pdf');
		
		$pdf = $this->dom_pdf->load();
		
		$style = "<style>@page { margin: 0.5in 1in 0.5in 1in;}</style>";
		
		$head = "<head>".$style."</head>";
		
		$html = "<html>".$head."<body>".$content."</body></html>";
		if ( get_magic_quotes_gpc() ) $html = stripslashes($html);
		
		$pdf->load_html($content);
		$pdf->set_paper('a4', 'portrait');
		$pdf->render();	
		$pdf->stream($filename);
	}
}

?>