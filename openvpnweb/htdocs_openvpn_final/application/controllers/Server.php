<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Server extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('wallet_model');
		$this->load->model('server_model');
		$this->load->helper('url_helper');
		$this->load->library(array('session'));
	}
	private function _set_view($file, $init) {
		$this->load->view('base/header', $init);
		$this->load->view($file, $init);
        $this->load->view('base/footer');
	}
	
	public function confirm($sid){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			$data = new stdClass();
			$data->row = $this->server_model->get_server_by_id($sid);
			$data->sid = $sid;
			$user = strtolower($this->input->post('user_ssh'));
			$password = $this->input->post('pwd_ssh');
			if($user=="root"){
				$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> ห้ามใช้ชื่อ root</div>';
				$this->_set_view('panel/register', $data);
			}else{
				if($this->server_model->check_status_server($sid)){
					if($this->server_model->check_ssh_user($user)){
						$row = $this->server_model->get_server_by_id($sid);
						$u_info = $this->user_model->get_info_user($_SESSION['username']);
						$limit = $this->server_model->get_limit_user($sid);
						if($limit < $row->s_limit){
							if($u_info->balance >= $row->s_price){
								$d = new stdClass();
								$d->ssh_user = $user;
								$d->ssh_pass = $password;
								$d->ssh_id = $sid;
								if($this->server_model->checkConnect($sid)){
									if($this->server_model->create_user_ssh($d)){
										
											if($this->user_model->decrease_balance($row->s_price)){
												$this->user_model->set_session_balance();
												$data->message = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>สำเร็จ!</strong> สมัครบัญชี Openvpn แล้ว<br>User: '.$user.'<br>Password: '.$password.'<br>อายุ: '.$row->s_expire.' วัน</div>';
												$this->_set_view('panel/register', $data);
											}else{
												$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> หักเงินไม่สำเร็จ</div>';
												$this->_set_view('panel/register', $data);
											}
										
									}else{
										$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> สมัครไม่สำเร็จ</div>';
										$this->_set_view('panel/register', $data);
									}
								}else{
										$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> ไม่สามารถเชื่อมเซิร์ฟเวอร์ได้</div>';
										$this->_set_view('panel/register', $data);
								}
							}else{
								$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> ยอดเงินไม่พอ</div>';
								$this->_set_view('panel/register', $data);
							}
						}else{
							$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> เซิร์ฟเต็มแล้ว</div>';
							$this->_set_view('panel/register', $data);
						}
					}else{
						$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> กรุณาใช้ชื่ออื่น</div>';
						$this->_set_view('panel/register', $data);
					}
				}else{
					$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> เซิร์ฟปิดอยู่</div>';
					$this->_set_view('panel/register', $data);
				}
			}
		}
	}
	
	public function switchserver($sid, $value){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			if($_SESSION['admin']){
				$data = new stdClass();
				$data->value = $value;
				$data->s_id = $sid;
				if($this->server_model->open_server($data)){
					redirect(base_url('/main/server'));
				}else{
					redirect(base_url('/main/server'));
				}
			}else{
				redirect(base_url('/main/server'));
			}
		}
	}
	
	public function addserver(){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			if($_SESSION['admin']){
				$data = new stdClass();
				$this->_set_view('panel/addserver', $data);
			}else{
				redirect(base_url('/main/server'));
			}
		}
	}
	
	public function confirmadd(){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			if($_SESSION['admin']){
				$data = new stdClass();
				$data->s_name		= $this->input->post('s_name');
				$data->s_ip			= $this->input->post('s_ip');
				$data->s_pass		= $this->input->post('s_pass');
				$data->s_price		= $this->input->post('s_price');
				$data->s_expire		= $this->input->post('s_expire');
				$data->s_limit		= $this->input->post('s_limit');
				
				if($this->server_model->add_server($data)){
					$data->message = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>สำเร็จ!</strong> เพิ่มเซิร์ฟเวอร์แล้ว</div>';
					$this->_set_view('panel/addserver', $data);
				}else{
					redirect(base_url('/server/addserver'));
				}
			}else{
				redirect(base_url('/main/server'));
			}
		}
	}
	
	public function edit($sid){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			if($_SESSION['admin']){
				$data = new stdClass();
				$data->row = $this->server_model->get_server_by_id($sid);
				$this->_set_view('panel/editserver', $data);
			}else{
				redirect(base_url('/main/server'));
			}
		}
	}
	
	public function confirmedit(){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			if($_SESSION['admin']){
				$data = new stdClass();
				$data->s_id			= $this->input->post('s_id');
				$data->s_name		= $this->input->post('s_name');
				$data->s_ip			= $this->input->post('s_ip');
				$data->s_pass		= $this->input->post('s_pass');
				$data->s_price		= $this->input->post('s_price');
				$data->s_expire		= $this->input->post('s_expire');
				$data->s_limit		= $this->input->post('s_limit');
				
				if($this->server_model->update_server($data)){
					$data->row = $this->server_model->get_server_by_id($data->s_id);
					$data->message = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>สำเร็จ!</strong> แก้ไขเซิร์ฟเวอร์แล้ว</div>';
					$this->_set_view('panel/editserver', $data);
				}else{
					redirect(base_url('/server/editserver'));
				}
			}else{
				redirect(base_url('/main/server'));
			}
		}
	}
	
	public function del($sid){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			if($_SESSION['admin']){
				$this->server_model->delserver($sid);
				redirect(base_url('/main/server'));
			}else{
				redirect(base_url('/main/server'));
			}
		}
	}
	
	public function addwallet(){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			if($_SESSION['admin']){
				if($this->wallet_model->get_wallet()){
					$data = new stdClass();
					$data->edit = $this->wallet_model->get_wallet();
					$this->_set_view('panel/addwallet', $data);
				}else{
					$data = new stdClass();
					$this->_set_view('panel/addwallet', $data);
				}
			}else{
				redirect(base_url('/main/addpoint'));
			}
		}
	}
	
	public function confirmaddwallet(){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			if($_SESSION['admin']){
				$data = new stdClass();
				$data->mobile		= $this->input->post('mobile');
				$data->pin			= $this->input->post('pin');
				
				if($this->wallet_model->get_wallet()){
					$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> มีเบอร์วอเล็ทอยู่แล้ว</div>';
					$data->edit = $this->wallet_model->get_wallet();
					$this->_set_view('panel/addwallet', $data);
				}else{
					if($this->wallet_model->add_wallet($data)){
						$data->message = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>สำเร็จ!</strong> เพิ่มวอเล็ทแล้ว</div>';
						$data->edit = $this->wallet_model->get_wallet();
						$this->_set_view('panel/addwallet', $data);
					}
				}
			}else{
				redirect(base_url('/main/addpoint'));
			}
		}
	}
	
	public function confirmeditwallet(){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			if($_SESSION['admin']){
				$data = new stdClass();
				$data->mobile		= $this->input->post('mobile');
				$data->pin			= $this->input->post('pin');
				if($this->wallet_model->update_wallet($data)){
					$data->message = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>สำเร็จ!</strong> แก้ไขวอเล็ทแล้ว</div>';
					$data->edit = $this->wallet_model->get_wallet();
					$this->_set_view('panel/addwallet', $data);
				}else{
					redirect(base_url('/main/addpoint'));
				}
			}else{
				redirect(base_url('/main/addpoint'));
			}
		}
	}
}
