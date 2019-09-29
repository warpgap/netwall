<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	public function get_test($username) {
		
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('username', $username);

		return $this->db->get()->row('id');
	}
	public function get_user() {
		$query = $this->db->get('users');
		return $query->result_array();
	}
	public function check_login($user, $pass){
		$query = $this->db->get('users');
		$status = false;
		foreach ($query->result() as $row){
			if($user == $row->user && $pass == $row->password){
				$status = true;
			}
		}
		return $status;
	}
	
	public function get_info_user($user){
		$this->db->from('users');
		$this->db->where('user', $user);
		return $this->db->get()->row();
	}
	
	public function check_admin($user){
		$this->db->select('admin_');
		$this->db->from('users');
		$this->db->where('user', $user);

		return $this->db->get()->row('admin_');
	}
	public function check_password($user, $pass){
		$this->db->select('password');
		$this->db->from('users');
		$this->db->where('user', $user);
		
		if($pass==$this->db->get()->row('password')){
			return true;
		}else{
			return false;
		}
	}
	public function check_user($user){
		$this->db->select('user');
		$this->db->from('users');
		$this->db->where('user', $user);
		
		if(empty($this->db->get()->row('user'))){
			return true;
		}else{
			return false;
		}
	}
	
	public function check_email($email){
		$this->db->select('email');
		$this->db->from('users');
		$this->db->where('email', $email);
		
		if(empty($this->db->get()->row('email'))){
			return true;
		}else{
			return false;
		}
	}
	
	public function update_password($user, $pass){
		$data = array('password' => $pass);
		$this->db->where('user', $user);
		return $this->db->update('users', $data);
	}
	
	public function decrease_balance($b){
		$row = $this->get_info_user($_SESSION['username']);
		$balance = $row->balance;
		
		if($balance > 0 && $balance >= $b){
			$balance = $balance - $b;
			$data = array('balance' => $balance);
			$this->db->where('user', $_SESSION['username']);
			return $this->db->update('users', $data);
		}
	}
	
	public function increase_balance($b){
		$row = $this->get_info_user($_SESSION['username']);
		$balance = $row->balance;
		
		$balance = $balance + $b;
		$data = array('balance' => $balance);
		$this->db->where('user', $_SESSION['username']);
		return $this->db->update('users', $data);
	}
	
	public function set_session_balance(){
		$row = $this->get_info_user($_SESSION['username']);
		$_SESSION['balance'] = $row->balance;
		return true;
	}
	
	public function regis_account($data){
		$row = array(
			'id' => NULL,
			'user' => $data['user'],
			'password' => $data['password'],
			'email' => $data['email'],
			'balance' => 0,
			'admin_' => 0,
			'active' => 1
		);
		return $this->db->insert('users', $row);
	}
}
