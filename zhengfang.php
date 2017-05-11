
<?php
	header("Content-type: text/html; charset=gb2312");




class zhengfang{
	private $stunum = '';


	function cURL($url,$headers,$posts,$cookie_jar,$cookie_file,$referer){
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);//保存cookie

		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_REFERER, $referer);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);//设置http头
		curl_setopt($ch, CURLOPT_POSTFIELDS, $posts);//设置post内容
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);//设置cookie

		//curl_setopt($ch, CURLOPT_REFERER, $referer);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//不自动输出exec后得到的数据
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	function getImg(){
		$url = "http://202.115.80.211/CheckCode.aspx";
	    $cookie_file = dirname(__FILE__)."/cookie.txt";
	    $img = $this->cURL($url,array(),'',$cookie_file,'','');
	    $fp = fopen("./veri.jpg", "w");
		fwrite($fp, $img);
		fclose($fp);
	    $this->getHidden();
	}

	function getHidden(){
		$cookie_file = dirname(__FILE__)."/cookie.txt";
		$url = "http://202.115.80.211/default2.aspx";
		$headers = array(
			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
			"Accept-Encoding: gzip, deflate",
			"Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3",
			"Connection: keep-alive",
			"Host: 202.115.80.211",
			"Upgrade-Insecure-Requests: 1",
			"User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0"
			);
		
		$data = $this->cURL($url,$headers,'','',$cookie_file,'');

		$hidden = array();
		preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $data, $hidden);
		$fp = fopen("./hidden.txt", "w");
		fwrite($fp, $hidden[1][0]);
		fclose($fp);
	}



	function login(){
		$cookie_file = dirname(__FILE__)."/cookie.txt";
		$url = "http://202.115.80.211/default2.aspx";
		

		$fp = fopen("./hidden.txt", "r");
		$hidden = fread($fp, filesize("./hidden.txt"));

		echo $hidden;
		echo $_POST['txtUserName'].'<br/>';
		echo $_POST['txtSecret'].'<br/>';

		$this->stunum = $_POST['txtUserName'];


		$headers = array(
			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
			"Accept-Encoding: gzip, deflate",
			"Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3",
			"Connection: keep-alive",
			"Host: 202.115.80.211",
			"Referer: http://202.115.80.211/default2.aspx",
			"Upgrade-Insecure-Requests: 1",
			"User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0",
			"Content-Type: application/x-www-form-urlencoded"
			);

		$fields_string = "__VIEWSTATE=".urlencode($hidden)."&txtUserName=".$_POST['txtUserName']."&TextBox2=".$_POST['TextBox2']."&txtSecretCode=".$_POST['txtSecret']."&RadioButtonList1=%D1%A7%C9%FA&Button1=&lbLanguage=&hidPdrs=&hidsc=";
		
		$this->cURL($url,$headers,$fields_string,'',$cookie_file,'');

		$this->getScore();
	}


	function getScore(){
		$xm = urlencode('朱宇');
		$xh = $this->stunum;
		$url = "http://202.115.80.211/xscj_gc.aspx?xh=$xh&xm=$xm&gnmkdm=N121605";
		$cookie_file = dirname(__FILE__)."/cookie.txt";
		$referer = 'http://202.115.80.211/xs_main.aspx?xh='.$xh;

		$data = $this->cURL($url,'','','',$cookie_file,$referer);

		$hidden2 = array();

		preg_match_all('/<input type="hidden" name="__VIEWSTATE" value="([^<>]+)" \/>/', $data, $hidden2);


		print_r($hidden2);
		$hid = $hidden2[1][0];
		echo $hid;
		$this->getScorem($hid);
	}

	function getScorem($hid){
		$xm = urlencode('朱宇');
		$xh = $this->stunum;
		$url = "http://202.115.80.211/xscj_gc.aspx?xh=$xh&xm=$xm&gnmkdm=N121605";
		$cookie_file = dirname(__FILE__)."/cookie.txt";
		$ch = curl_init($url);

		echo $hid;

		$headers = array(
			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
			"Accept-Encoding: gzip, deflate",
			"Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3",
			"Connection: keep-alive",
			"Host: 202.115.80.211",
			"Referer: http://202.115.80.211/xscj_gc.aspx?xh=201410420127&xm=%D6%EC%D3%EE&gnmkdm=N121605",
			"Upgrade-Insecure-Requests: 1",
			"User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0",
			"Content-Type: application/x-www-form-urlencoded"
			);

		$post = array(
			"__VIEWSTATE"=>$hid,
			"ddlXN"=>"2016-2017",
			"ddlXQ"=>"1",
			"Button1"=>"%B0%B4%D1%A7%C6%DA%B2%E9%D1%AF"
			);

		$fields_string = http_build_query($post);
		echo $fields_string;
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);//需要echo
		echo curl_exec($ch);

		curl_close($ch);
	}
}
	


	$zf = new zhengfang();
	if(empty($_POST)){
		$zf->getImg();
		include("./post.html");
	}else{
		$zf->login();
		//getScore();
	}


?>