<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
		$this->load->helper('url_helper');
		$this->load->library(array('session'));
		$this->load->library('form_validation');
	}
	private function _set_view($file, $init) {
		$this->load->view('base/header', $init);
		$this->load->view($file, $init);
        $this->load->view('base/footer');
	}
	
	public function confirm(){
		if(empty($_SESSION['logged_in'])){
			$user = $this->input->post('user');
			$pass = $this->input->post('pwd');
			
			if($this->user_model->check_login($user, $pass)){
				
				$row = $this->user_model->get_info_user($user);
				$_SESSION['logged_in'] = true;
				$_SESSION['username'] = $user;
				$_SESSION['admin'] = false;
				$_SESSION['balance'] = $row->balance;
				if($this->user_model->check_admin($user)==true){
					$_SESSION['admin'] = true;
				}
				redirect(base_url('/'));
			}else{
				$data = new stdClass();
				$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> ชื่อผู้ใช้/รหัสผ่านไม่ถูกต้อง</div>';
				$this->load->view('login', $data);
			}
		}else{
			redirect(base_url('/'));
		}
	}
	public function logout() {
		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
			session_destroy();
		}
		redirect(base_url('/'));
	}
	
	public function confirm_setting(){
		if(empty($_SESSION['logged_in'])){
			redirect(base_url('/'));
		}else{
			$user = $_SESSION['username'];
			$pass_old = $this->input->post('pwd_old');
			$pass_new = $this->input->post('pwd_new');
			$pass_new2 = $this->input->post('pwd_new2');
			
			if($this->user_model->check_password($user, $pass_old)){
				if($pass_new==$pass_new2){
					if(strlen($pass_new) >= 6){
						if($this->user_model->update_password($user, $pass_new)){
							$data = new stdClass();
							$data->message = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>สำเร็จ!</strong> เปลี่ยนรหัสผ่านแล้ว</div>';
							$this->_set_view('setting', $data);
						}else{
							redirect(base_url('/setting'));
						}
					}else{
						$data = new stdClass();
						$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> รหัสผ่านต้องไม่ต่ำกว่า 6 ตัว</div>';
						$this->_set_view('setting', $data);
					}
				}else{
					$data = new stdClass();
					$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> รหัสผ่านไม่ตรงกัน</div>';
					$this->_set_view('setting', $data);
				}
			}else{
				$data = new stdClass();
				$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> รหัสผ่านเดิมไม่ถูกต้อง</div>';
				$this->_set_view('setting', $data);
			}
		}
	}
	
	public function register(){
		if(!empty($_SESSION['logged_in'])){
			redirect(base_url('/'));
		}else{
			$this->load->view('register');
		}
	}
	public function confirm_register(){
		if(!empty($_SESSION['logged_in'])){
			redirect(base_url('/'));
		}else{
			$user = $this->input->post('user');
			$pass = $this->input->post('pwd');
			$pass_re = $this->input->post('pwd_re');
			$email = $this->input->post('email');
			
			$valid = false;
			if($user==""||$pass==""||$email==""){
				$data = new stdClass();
				$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> กรอกให้ครบทุกช่อง</div>';
				$this->load->view('register', $data);
			}else{
				if($this->user_model->check_user($user)){
					if($pass==$pass_re){
						if($this->user_model->check_email($email)){
							$data = array(
								'user' => $user,
								'password' => $pass,
								'email' => $email
							);
							if($this->user_model->regis_account($data)){
								$data = new stdClass();
								$data->message = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>สำเร็จ!</strong> สมัครบัญชีแล้ว<br> Username ของคุณคือ '.$user.'</div>';
								$this->load->view('login', $data);
							}else{
								redirect(base_url('/register'));
							}
						}else{
							$data = new stdClass();
							$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> กรุณาใช้อีเมลล์อื่น</div>';
							$this->load->view('register', $data);
						}
					}else{
						$data = new stdClass();
						$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> รหัสผ่านไม่ตรงกัน</div>';
						$this->load->view('register', $data);
					}
				}else{
					$data = new stdClass();
					$data->message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>ผิดพลาด!</strong> กรุณาใช้ชื่ออื่น</div>';	
					$this->load->view('register', $data);
				}
			}
		}
	}
}
