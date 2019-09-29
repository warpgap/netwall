<?php
class ssh_lib {
	
	public function __construct(){
	    set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
	    include('Net/SSH2.php');
    }
	
	public function addAccount($data){
	   
		$host = $data['hostname'];
		$root = $data['rootpass'];
		$user = $data['username'];
		$pass = $data['password'];
		$exp = $data['expired'];
		if($user == 'root'){
			exit("Root pass salah");
		}
		$ssh= new Net_SSH2($host);
	    if (!$ssh->login('root', $root)){
			exit;
		}
	    
	    $ssh->exec("useradd -G users -e \"$exp days\" -s /bin/false -M $user ");
        $ssh->enablePTY();
        $ssh->exec("passwd $user");
        $ssh->read("Enter new UNIX password: ");
        $ssh->write("$pass\n");
        $ssh->read("Retype new UNIX password: ");
        $ssh->write("$pass\n");
        $ssh->read('password updated successfully');
    
		return true;
		
    }
	
    public function deletAccount($data){
		$host = $data['hostname'];
		$root = $data['rootpass'];
		$user = $data['username'];

		if (empty($user)){
			exit;
		}
		if($user==='root'){
			exit;
		}
		$ssh = new Net_SSH2($host);
		if(!$ssh->login('root', $root)){
			exit;
		}
		$ssh->exec("userdel -f $user ");
		return true;
	}
	
	public function addMoreExpire($data){
		$host = $data['hostname'];
		$root = $data['rootpass'];
		$user = $data['username'];
		
		$date = $data['new_date'];
		
		if (empty($user)){
			exit;
		}
		if($user==='root'){
			exit;
		}
		$ssh = new Net_SSH2($host);
		if(!$ssh->login('root', $root)){
			exit;
		}
		$ssh->exec("chage -E $date $user");
		return true;
	}
	
	public function checkConnect($data){
		$host = $data['hostname'];
		$root = $data['rootpass'];
		
		$ssh = new Net_SSH2($host);
		if(!$ssh->login('root', $root)){
			return false;
		}else{
			return true;
		}
	}

}
