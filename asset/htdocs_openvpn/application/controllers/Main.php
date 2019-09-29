<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('wallet_model');
		$this->load->model('server_model');
		$this->load->helper('url_helper');
		$this->load->library(array('session'));
		 $this->load->library('form_validation');
	}
	private function _set_view($file, $init) {
		$this->load->view('base/header', $init);
		$this->load->view($file, $init);
        $this->load->view('base/footer');
	}
	
	public function index(){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			$data = new stdClass();
			$this->_set_view('index', $data);
		}
	}
	
	public function login(){
		$this->load->view('login');
	}
	
	public function server(){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			$this->server_model->del_expire_user();
			$data = new stdClass();
			$data->row = $this->server_model->get_all_server();
			for($i=0;$i<count($data->row);$i++){
				$data->row[$i]['s_account'] = $this->server_model->get_limit_user($data->row[$i]['s_id']);
			}
			$this->_set_view('panel/server', $data);
		}
	}
	
	public function register($sid){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			$data = new stdClass();
			$data->sid = $sid;
			$data->row = $this->server_model->get_server_by_id($sid);
			$this->_set_view('panel/register', $data);
		}
	}
	public function setting(){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			$data = new stdClass();
			$this->_set_view('setting', $data);
		}
	}
	
	public function addpoint(){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			if($this->wallet_model->get_wallet()){
				$data = new stdClass();
				$data->mobile = $this->wallet_model->get_wallet();
				$this->_set_view('panel/addpoint', $data);
			}else{
				$data = new stdClass();
				$this->_set_view('panel/addpoint', $data);
			}
		}
	}
	
	public function confirmaddpoint(){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/login'));
		}else{
			$data = new stdClass();
			$this->wallet_model->update_ref();
			$ref_no = $this->input->post('ref_no');
			if($this->wallet_model->check_ref_no($ref_no)==false){
				if($this->wallet_model->check_status($ref_no)){
					$point = $this->wallet_model->get_info_by_ref_no($ref_no);
					if($this->user_model->increase_balance($point->point)){
						$this->wallet_model->change_status($ref_no);
						$this->user_model->set_session_balance();
						$data->message = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>สำเร็จ!</strong> เติมเงินแล้ว</div>';
						$data->mobile = $this->wallet_model->get_wallet();
						$this->_set_view('panel/addpoint', $data);
					}else{
						redirect(base_url('/main/addpoint'));
					}
				}else{
					$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> เลขอ้างอิงนี้ถูกใช้ไปแล้ว</div>';
					$data->mobile = $this->wallet_model->get_wallet();
					$this->_set_view('panel/addpoint', $data);
				}
			}else{
				$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> ไม่มีเลขอ้างอิงนี้ในระบบ</div>';
				$data->mobile = $this->wallet_model->get_wallet();
				$this->_set_view('panel/addpoint', $data);
			}
		}
	}
	
	public function test(){
		echo $this->user_model->test_db();
	}
}
