<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('signup_model');
	}

	public function index()
	{
		$viewData= new stdClass();
		$viewData->title = "Facebook - Giriş Yap veya Kaydol";
		$this->load->view('signin/index', $viewData);
	}

	public function signup_form_valid(){
		//modelde insert yazmakla başlayalım(yapıldı ve üzerinden saatler geçti)
		//çok yorum yazmadım çünkü bana soru sorulduğunda yoruma baktı denmesinden hoşlanmıyorum..
		$this->form_validation->set_rules('loginad', 'İsim', 'required');
		$this->form_validation->set_rules('loginpwd', 'Şifre', 'callback_sifre_kontrol');
		$this->form_validation->set_rules('logincinsiyet', 'Cinsiyet', 'required');
		$this->form_validation->set_rules('loginemail', 'Email', 'callback_email_check');
		$this->form_validation->set_message('required', "Lütfen geçerli bir {field} giriniz...");

		$signform = new stdClass();
		$signform->ad = $this->input->post('loginad');
		$signform->soyad = $this->input->post('loginsoyad');
		$signform->email = $this->input->post('loginemail');
		$signform->sifre = $this->input->post('loginpwd');
		$signform->cins = $this->input->post('logincinsiyet');
		$signform->dogum = $this->input->post('loginyıl')."/".$this->input->post('loginay')."/".$this->input->post('logingun');//datetime formatı, bunu daha sonra yapacağım(yaptım)

		$validate = $this->form_validation->run();
		if($validate){
			$insert = array(
				'ad' 		=> $signform->ad,
				'soyad' 	=> $signform->soyad,
				'email' 	=> $signform->email,
				'sifre' 	=> $signform->sifre,
				'cinsiyet' 	=> $signform->cins,
				'dogum'		=> $signform->dogum,
			);
			//TODO index routing
			print_r($insert);
			$query = $this->signup_model->insert($insert);
		}else{
			$viewData = new stdClass();
			$viewData->form_error = true;
			$this->load->view('signin/index', $viewData);
		}

	}
	public function signin(){
		//önce kontrol edelim
		$this->form_validation->set_rules('email', 'E-mail', 'callback_giris_kontrol[email]');
		$this->form_validation->set_rules('password', 'Şifre', 'callback_giris_kontrol[sifre]');
		//dont repeat yourself
		$validate = $this->form_validation->run();
		if($validate){
			echo "Başarıyla giriş yaptın";
		}else{
			$viewData = new stdClass();
			$viewData->form_error =  true;
			$this->load->view('signin/index', $viewData);
		}


	}

	//checkleri en sona alacağım
	public function email_check($str)
	{
		$where = array(
			'email' => $str
		);
		$query = $this->signup_model->get($where);
		//databasede verileri başka yerde kontrol ederek sql injectionu neredeyse imkansız hale getiriyoruz
		if ($query->num_rows() == 0 ){
			return true;
		}else{
			$this->form_validation->set_message('email_check', 'Lütfen kayıtlı olmayan bir e mail giriniz...');
			return false;
		}

	}
	public function sifre_kontrol($str){
		$karakterSayisi = strlen($str);
		if($karakterSayisi < 18){
			return true;
		}else{
			$this->form_validation->set_message('sifre_kontrol', 'Şifre 17 karakterden uzun olmamalıdır');
			return false;
		}
	}
	public function giris_kontrol($str, $inf){
		$bilgi = array(
			"{$inf}" => $str,
		);
		$query = $this->signup_model->get($bilgi);
		if($query->num_rows() == 0){
			$this->form_validation->set_message("{$inf}", 'giris_kontrol', 'Yanlış kullanıcı adı veya şifre');
			return false;
		}else{
			return true;
		}
	}

}
