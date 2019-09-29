<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Server_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('user_model');
		date_default_timezone_set("Asia/Bangkok");
		$this->load->library('ssh_lib');
	}
	
	public function get_all_server($order='asc'){
		$this->db->order_by('s_id', $order);
		$query = $this->db->get('server');
		return $query->result_array();
	}
	
	public function get_all_ssh_user($order='asc'){
		$this->db->order_by('ssh_u_id', $order);
		$query = $this->db->get('ssh_user');
		return $query->result_array();
	}
	
	public function get_server_by_id($sid){
		$this->db->from('server');
		$this->db->where('s_id', $sid);
		return $this->db->get()->row();
	}
	
	public function get_server_key(){
		$this->db->select('s_id');
		$this->db->from('server');
		$this->db->order_by('s_id', 'desc');
		$query = $this->db->get(); 
		return $query->result_array();
	}
	
	public function get_ssh_id(){
		$this->db->select('ssh_u_id');
		$this->db->from('ssh_user');
		$this->db->order_by('ssh_u_id', 'desc');
		$query = $this->db->get(); 
		return $query->result_array();
	}
	
	public function get_limit_user($sid){
		return $this->db->where(['s_id'=>$sid])->from("ssh_user")->count_all_results();
	} 
	
	public function create_sv_key(){
		$row = $this->get_server_key();
		
		if(empty($row)){
			return "SV001";
		}else{
			$sid = $row[0]['s_id'];
			
			$tmp = explode("SV",$sid);
			$tmp[1] += 1;
			$tmp[1] = str_pad($tmp[1], 3, '0', STR_PAD_LEFT);
			$sid = "SV".$tmp[1];
			
			return $sid;
		}
	}
	
	public function check_ssh_user($user){
		$this->db->select('ssh_user');
		$this->db->from('ssh_user');
		$this->db->where('ssh_user', $user);
		$row = $this->db->get()->row();
		
		if(empty($row)){
			return true;
		}else{
			return false;
		}
	}
	
	public function check_status_server($sid){
		$this->db->select('s_status');
		$this->db->from('server');
		$this->db->where('s_id', $sid);
		$s = $this->db->get()->row()->s_status;
		
		if($s==0){
			return false;
		}else{
			return true;
		}
	}
	
	public function create_ssh_id(){
		$row = $this->get_ssh_id();
		
		if(empty($row)){
			return "SSH0001";
		}else{
			$sid = $row[0]['ssh_u_id'];
			
			$tmp = explode("SSH",$sid);
			$tmp[1] += 1;
			$tmp[1] = str_pad($tmp[1], 4, '0', STR_PAD_LEFT);
			$sid = "SSH".$tmp[1];
			
			return $sid;
		}
	}
	
	public function add_server($data){
		$s_id = $this->create_sv_key();
		$row = array(
			's_id' 		=> $s_id,
			's_name' 	=> $data->s_name,
			's_ip' 		=> $data->s_ip,
			's_pass' 	=> $data->s_pass,
			's_price' 	=> $data->s_price,
			's_expire' 	=> $data->s_expire,
			's_limit' 	=> $data->s_limit,
			's_status' 	=> 1
			
		);
		$status = false;
		
		if($this->db->insert('server', $row)){
			$status = true;
		}
		
		return $status;
	}
	
	public function create_user_ssh($data){
		$s_id = $this->create_ssh_id();
		$r = $this->get_server_by_id($data->ssh_id);
		$row = array(
			'ssh_u_id' 			=> $s_id,
			'ssh_user' 			=> $data->ssh_user,
			'ssh_pass' 			=> $data->ssh_pass,
			's_id' 				=> $data->ssh_id,
			'create_by' 		=> $_SESSION['username'],
			'create_at' 		=> $this->dateToTime($this->get_time_now()),
			'expire_at' 		=> $this->dateToTime($this->get_time_expire($r->s_expire)),
			'expire_day' 		=> $r->s_expire
		);
		if($this->db->insert('ssh_user', $row)){
			if($this->user_model->set_session_balance()){
				$d = array(
					'hostname'	=> $r->s_ip,
					'rootpass'	=> $r->s_pass,
					'username'	=> $data->ssh_user,
					'password'	=> $data->ssh_pass,
					'expired'	=> $r->s_expire
				);
				$this->update_status_server($data->ssh_id);
				return $this->ssh_lib->addAccount($d);
			}
		}
	}
	
	public function checkConnect($sid){
		$row = $this->get_server_by_id($sid);
		$data = array();
		$data['hostname'] = $row->s_ip;
		$data['rootpass'] = $row->s_pass;
		
		return $this->ssh_lib->checkConnect($data);
	}
	
	public function del_expire_user(){
		$ssh_user = $this->get_all_ssh_user();
		$date_now = $this->get_time_now();
		
		$date_now = $this->dateToTime($date_now);
		
		for($i=0;$i<count($ssh_user);$i++){
			if($date_now >= $ssh_user[$i]['expire_at']){
				$this->db->where('ssh_u_id', $ssh_user[$i]['ssh_u_id']);
				$this->db->delete('ssh_user');
				
				$row = $this->get_server_by_id($ssh_user[$i]['s_id']);
				
				$data = array(
					'hostname'	=> $row->s_ip,
					'rootpass'	=> $row->s_pass,
					'username'	=> $ssh_user[$i]['ssh_user']
				);
				$this->ssh_lib->deletAccount($data);
			}
		}
	}
	
	public function update_status_server($sid=false){
		$user = $this->get_limit_user($sid);
		$server = $this->get_server_by_id($sid);
		if($user >= $server->s_limit){
			$row = array(
				's_status' => 0
			);
			$this->db->where('s_id', $sid);
			$this->db->update('server', $row);
		}
		
		/*else{
			$row = array(
				's_status' => 1
			);
			$this->db->where('s_id', $sid);
			$this->db->update('server', $row);
		}*/
	}
	
	public function update_server($data){
		$row = array(
			's_name' 	=>	$data->s_name, 
			's_ip' 		=>	$data->s_ip,
			's_price' 	=>  $data->s_price,
			's_expire' 	=>  $data->s_expire,
			's_limit' 	=>  $data->s_limit,
			's_status' 	=>  1,
			's_pass' 	=>  $data->s_pass
		);
		$this->db->where('s_id', $data->s_id);
		return $this->db->update('server', $row);
	}
	
	public function open_server($data){
		$row = array(
			's_status' => $data->value
		);
		$this->db->where('s_id', $data->s_id);
		return $this->db->update('server', $row);
	}
	
	public function delserver($sid){
		$this->db->where('s_id', $sid);
		$this->db->delete('server');
	}
	
	public function timeToDate($timestamp){ 
		$timestamp = date("Y-m-d H:i:s", $timestamp);
		return $timestamp;
	}
	public function dateToTime($datetime){
		$exp = explode(" ",$datetime);
		$t = explode(":",$exp[1]);
		$d = explode("-",$exp[0]);
		$timestamp = mktime($t[0], $t[1], $t[2], $d[1], $d[2], $d[0]);
		return $timestamp;
	}
	
	public function get_time_now(){
		return date('Y-m-d H:i:s', strtotime('now'));
	}
	
	public function get_time_expire($time, $type = 'days'){
		return date('Y-m-d H:i:s', strtotime('now' . "+" . $time . " $type"));
	}
	
	public function add_more_expire($date, $more, $type='days'){
		return date('Y-m-d', strtotime($date . "+" . $more . " $type"));
	}
}
