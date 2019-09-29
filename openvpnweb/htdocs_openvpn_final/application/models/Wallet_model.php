<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet_model extends CI_Model {
	
	private $user;
	private $password;
	private $login_type;
	
	private $api_signin 				= "https://mobile-api-gateway.truemoney.com/mobile-api-gateway/api/v1/signin";
	private $api_logout 				= "https://mobile-api-gateway.truemoney.com/mobile-api-gateway/api/v1/signout/";
	private $api_profile 				= "https://mobile-api-gateway.truemoney.com/mobile-api-gateway/api/v1/profile/";
	private $api_topup 					= "https://mobile-api-gateway.truemoney.com/mobile-api-gateway/api/v1/topup/mobile/";
	private $api_gettran 				= "https://mobile-api-gateway.truemoney.com/mobile-api-gateway/api/v1/profile/transactions/history/";
	private $api_checktran 				= "https://mobile-api-gateway.truemoney.com/mobile-api-gateway/api/v1/profile/activities/";
	private $api_transaction_draft		= "https://mobile-api-gateway.truemoney.com/mobile-api-gateway/api/v1/transfer/draft-transaction/";
	private $api_transaction_send_otp	= "https://mobile-api-gateway.truemoney.com/mobile-api-gateway/api/v1/transfer/draft-transaction/";
	private $api_transaction_transfer	= "https://mobile-api-gateway.truemoney.com/mobile-api-gateway/api/v1/transfer/transaction/";
	private $api_FetchActivities		= "https://mobile-api-gateway.truemoney.com/mobile-api-gateway/user-profile-composite/v1/users/transactions/history?start_date=";
	private $api_FetchDetail			= "https://mobile-api-gateway.truemoney.com/mobile-api-gateway/user-profile-composite/v1/users/transactions/history/detail/";
	private $api_CashcardBuyRequest		= "https://mobile-api-gateway.truemoney.com/mobile-api-gateway/api/v1/buy/e-pin/draft/verifyAndCreate/";
	private $api_CashcardBuyComfirm		= "https://mobile-api-gateway.truemoney.com/mobile-api-gateway/api/v1/buy/e-pin/confirm/";
	
	public function __construct() {
		parent::__construct();
		$this->user 		= "";
		$this->password 	= "";
		$this->login_type 	= "";
		$this->load->database();
	}
	
	/*public function __construct($user, $pass, $type) {
		parent::__construct();
		$this->user 		= $user;
		$this->password 	= $pass;
		$this->login_type 	= $type;
	}*/
	
	public function Curl($method, $url, $header, $data){
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'okhttp/3.8.0');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if($data){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
        return curl_exec($ch);
	}
	
	public function hash_password(){
		return sha1($this->user.$this->password);
	}
	
	public function setLogin($user, $pass, $type){
		$this->user 		= $user;
		$this->password 	= $pass;
		$this->login_type 	= $type;
	}
	
	public function Login(){
		$method 		= 'POST';
		$url 			= $this->api_signin;
		$header 		= array(
							"Host: mobile-api-gateway.truemoney.com",
							"Content-Type: application/json"
						);
		$postfield = array(
			"username"	=>	$this->user,
			"password"	=>	$this->hash_password(),
			"type"		=>	$this->login_type
		);
		$data_string = json_encode($postfield);
		$login = json_decode($this->Curl($method, $url, $header, $data_string), true);
		if($login['code']==20000){
			return $login['data']['accessToken'];
		}else{
			return false;
		}
	}
	
	public function Profile($token){
		$method 		= 'GET';
		$url 			= $this->api_profile.$token;
		$header 		= array("
							Host: mobile-api-gateway.truemoney.com"
						);
		return json_decode($this->Curl($method, $url, $header, false), true);
	}
	
	public function Info($token){
		$row = $this->Profile($token);
		$data = array();
		$data['Name'] = $row['data']['fullname'];
		$data['Balance']		= number_format($row['data']['currentBalance'], 2);
		$data['thaiId']			= $row['data']['thaiId'];
		$data['email']			= $row['data']['email'];
		$data['occupation']		= $row['data']['occupation'];
		$data['mobileNumber']	= $row['data']['mobileNumber'];
		$data['profileImg']		= $row['data']['imageURL'];
		
		return $data;
	}
	
	public function Topup($cashcard, $token){
		$method 		= 'POST';
		$url 			= $this->api_topup.time()."/".$token."/cashcard/".$cashcard;
		$header 		= array("Host: mobile-api-gateway.truemoney.com");
		return json_decode($this->Curl($method, $url, $header, true), true);
	}
	
	public function getTran($token, $start, $end){
		$method 		= 'GET';
		$url 			= $this->api_gettran.$token.'/?startDate='.$start.'&endDate='.$end.'&limit=20&page=1&type=&action=';
		$header 		= array("Host: mobile-api-gateway.truemoney.com");
		return json_decode($this->Curl($method, $url, $header, false), true);
	}
	
	public function CheckTran($token,$id){
		$method 		= 'GET';
		$url 			= $this->api_checktran.$id.'/detail/'.$token;
		$header 		= array("Host: mobile-api-gateway.truemoney.com");
		return json_decode($this->Curl($method, $url, $header, false), true);
	}
	
	public function FetchActivities($token, $start, $end, $limit = 25) {
		$method 		= 'GET';
        $url 			= $this->api_FetchActivities.$start."&end_date=".$end."&limit=".$limit;
        $header 		= array("Host: mobile-api-gateway.truemoney.com", "Authorization: ".$token);
		return json_decode($this->Curl($method, $url, $header, false), true)['data']['activities'];
    }

    public function FetchDetail($token, $id) {
		$method 		= 'GET';
        $url 			= $this->api_FetchDetail.$id;
        $header 		= array("Host: mobile-api-gateway.truemoney.com", "Authorization: ".$token);
		return json_decode($this->Curl($method, $url, $header, false), true)['data'];
    }
	public function transaction_draft($token, $mobile, $amount){
		$method 		= 'POST';
        $url 			= $this->api_transaction_draft.$token;
        $data 			= array("amount"=>$amount, "mobileNumber"=>$mobile);
        $data 			= json_encode($data);
        $header 		= array("Host: mobile-api-gateway.truemoney.com", "Content-Type: application/json");
		return json_decode($this->Curl($method, $url, $header, $data), true)['data']['draftTransactionID'];
    }
    
    public function transaction_send_otp($token, $transaction_id, $personalMessage=""){
		$method 		= 'PUT';
        $url 			= $this->api_transaction_send_otp.$transaction_id."/send-otp/".$token;
        $data 			= array("personalMessage"=>$personalMessage);
        $data 			= json_encode($data);
        $header 		= array(
							"Host: mobile-api-gateway.truemoney.com",
							"Content-Type: application/json"
						);
		return json_decode($this->Curl($method, $url, $header, $data), true);
    }
	
	public function transaction_transfer($token, $mobile, $transaction_id, $otpString, $otpRefCode){
		$method 		= 'POST';
        $url 			= $this->api_transaction_transfer.$transaction_id."/".$token;
        $data 			= array("mobileNumber"=>$mobile, "otpString"=>$otpString, "otpRefCode"=>$otpRefCode);
        $data 			= json_encode($data);
        $header 		= array(
							"Host: mobile-api-gateway.truemoney.com",
							"Content-Type: application/json"
						);
		$tmp			= json_decode($this->Curl($method, $url, $header, $data), true);
		if($tmp['code']==20000){
			return true;
		}else{
			return false;
		}
    }
	
	public function CashcardBuyRequest($token, $mobile, $amount) {
		$method = 'POST';
        $url 	= $this->api_CashcardBuyRequest.$token;
        $data 	= array(
					"recipientMobileNumber"=>$mobile,
					"amount"=>$amount
				);
        $header = array(
					"Host: mobile-api-gateway.truemoney.com",
					"Content-Type: application/json"
				);
        return json_decode($this->Curl($method, $url, $header, json_encode($data)), true);
    }
    
    public function CashcardBuyComfirm($token, $draft, $mobile, $otpString, $otpRefCode) {
		$method = 'PUT';
        $url 	= $this->api_CashcardBuyComfirm.$draft."/".$token;
		$data 	= array(
					"mobileNumber"=>$mobile,
					"otpString"=>$otpString,
					"otpRefCode"=>$otpRefCode,
					"timestamp"=>time()
				);
        $header = array(
				"Host: mobile-api-gateway.truemoney.com",
				"Content-Type: application/json"
				);
        return json_decode($this->Curl($method, $url, $header, json_encode($data)), true);
    }
	
	public function Logout($token) {
		$method 		= 'POST';
        $url 			= $this->api_logout.$token;
        $header 		= array(
							"Host: mobile-api-gateway.truemoney.com"
						);
		return $this->Curl($method, $url, $header, true);
    }
	
	public function update_ref(){
		$d = $this->get_wallet();
		$this->setLogin($d->mobile, $d->pin, 'mobile');
		$token = $this->Login();
		$start = date('Y-m-d', strtotime('now' . "-" . 30 . " day"));
		$end = date('Y-m-d', strtotime('now'   . "+" . 1 . " day"));
		$transaction = $this->FetchActivities($token, $start, $end);
		$report=array();
		for($i=0;$i<count($transaction);$i++){
			$type=$transaction[$i]['original_type'];
			$action=$transaction[$i]['original_action'];
			if($type=="transfer"&&$action=="creditor"){
				$report[] = $this->FetchDetail($token, $transaction[$i]['report_id']);
			}
		}
		$row = array();
		for($i=0;$i<count($report);$i++){
			$row[$i]['ref_n'] = $report[$i]['section4']['column2']['cell1']['value'];
			$row[$i]['point'] = $report[$i]['section3']['column1']['cell1']['value'];
			$row[$i]['mobile'] = $report[$i]['ref1'];
			$row[$i]['date'] = $report[$i]['section4']['column1']['cell1']['value'];
			$row[$i]['msg'] = $report[$i]['personal_message']['value'];
			$row[$i]['name'] = $report[$i]['section2']['column1']['cell2']['value'];
		}
		
		$this->wallet_model->Logout($token);
		
		for($i=0;$i<count($row);$i++){
			if($this->check_ref_no($row[$i]['ref_n'])){
				$data = array(
					'id' 				=> NULL,
					'ref_no' 			=> $row[$i]['ref_n'],
					'point' 			=> $row[$i]['point'],
					'phone_no' 			=> $row[$i]['mobile'],
					'transaction_date' 	=> $row[$i]['date'],
					'name' 				=> $row[$i]['name'],
					'status' 			=> 1
				);
				$this->db->insert('ref_no', $data);
			}
		}
	}
	
	public function check_ref_no($ref_no){
		$this->db->select('ref_no');
		$this->db->from('ref_no');
		$this->db->where('ref_no', $ref_no);
		
		if(empty($this->db->get()->row('ref_no'))){
			return true;
		}else{
			return false;
		}
	}
	
	public function check_status($ref_no){
		$this->db->select('status');
		$this->db->from('ref_no');
		$this->db->where('ref_no', $ref_no);
		
		if($this->db->get()->row('status')==1){
			return true;
		}else{
			return false;
		}
	}
	
	public function change_status($ref_no){
		$row = array(
			'status' => 0
		);
		$this->db->where('ref_no', $ref_no);
		$this->db->update('ref_no', $row);
	}
	
	public function get_info_by_ref_no($ref_no){
		$this->db->from('ref_no');
		$this->db->where('ref_no', $ref_no);
		return $this->db->get()->row();
	}
	
	public function get_wallet(){
		$this->db->from('wallet_user');
		$this->db->where('id', '1');
		return $this->db->get()->row();
	}
	
	public function add_wallet($data){
		$row = array(
			'id' 		=> 1,
			'mobile' 	=> $data->mobile,
			'pin' 		=> $data->pin,
			'type' 		=> 'mobile'
			
		);
		$status = false;
		
		if($this->db->insert('wallet_user', $row)){
			$status = true;
		}
		
		return $status;
	}
	
	public function update_wallet($data){
		$row = array(
			'mobile' => $data->mobile,
			'pin'	 => $data->pin
		);
		$this->db->where('id', 1);
		return $this->db->update('wallet_user', $row);
	}
}
?>