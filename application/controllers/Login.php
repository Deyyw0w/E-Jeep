<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function index(){
		$this->autlogin();
	}

	public function autlogin(){
		$this->load->view('frontend/login');
	}
	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url('login'));
	}
	
	public function cekuser(){
		$username = strtolower($this->input->post('username'));
		$password = $this->input->post('password');
		$sqlCheck = $this->db->query('select * from tbl_pelanggan where username_pelanggan = "'.$username.'" OR email_pelanggan = "'.$username.'" ')->row();
		// die(print_r($sqlCheck));
		if ($sqlCheck) {
			if ($sqlCheck->status_pelanggan == 1) { 
				if (password_verify($password,$sqlCheck->password_pelanggan)) {
						$sess = [
							'kd_pelanggan' => $sqlCheck->kd_pelanggan,
							'username' => $sqlCheck->username_pelanggan,
							'password' => $sqlCheck->password_pelanggan,
							'ktp'     => $sqlCheck->no_ktp_pelanggan,
							'nama_lengkap'     => $sqlCheck->nama_pelanggan,
							'img_pelanggan'	=> $sqlCheck->img_pelanggan,
							'email'   => $sqlCheck->email_pelanggan,
							'telpon'   => $sqlCheck->telpon_pelanggan,
							'alamat'	=> $sqlCheck->alamat_pelanggan
						];
						$this->session->set_userdata($sess);
						if ($this->session->userdata('jadwal') == NULL) {
							redirect('tiket');
						}else{
							redirect('tiket/beforebeli/'.$this->session->userdata('jadwal').'/'.$this->session->userdata('asal').'/'.$this->session->userdata('tanggal'));
						}
					}else{
						$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
						Wrong Password
					</div>');
					redirect('login');
				}
			}else{
				$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
				Username has not been verified, please check your email
			</div>');
			
				redirect('login');
			}
		}else{
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
			Username is not registered
		</div>');
			redirect('login');
		}
	}

	public function daftar(){
		$this->form_validation->set_rules('nomor', 'Nomor', 'trim|required|is_unique[tbl_pelanggan.telpon_pelanggan]', array(
			'required' => 'Phone number must be filled.',
			'is_unique' => 'Phone number has already been used.'
		));
		
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required', array(
			'required' => 'First name must be filled.'
		));
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required', array(
			'required' => 'Last name must be filled.'
		));
		
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|is_unique[tbl_pelanggan.username_pelanggan]', array(
			'required' => 'Username must be filled.',
			'is_unique' => 'Username has already been used.'
		));
		
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[tbl_pelanggan.email_pelanggan]', array(
			'required' => 'Email must be filled.',
			'valid_email' => 'Please enter a valid email address.',
			'is_unique' => 'Email has already been used.'
		));
		
		$this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[8]|matches[password2]', array(
			'matches' => 'Passwords do not match.',
			'min_length' => 'Password must be at least 8 characters long.'
		));
		
		$this->form_validation->set_rules('password2', 'Password', 'trim|required|matches[password1]');
		if ($this->form_validation->run() == false) {
			$this->load->view('frontend/daftar');
		} else {
			// die(print_r($_POST));
			$this->load->model('getkod_model');

			$firstName = $this->input->post('first_name');
			$middleName = $this->input->post('middle_name');
			$lastName = $this->input->post('last_name');
			$fullName="";
			if (!empty($middleName)) {
				$fullName = $firstName . ' ' . $middleName . ' ' . $lastName;
			} else {
				$fullName = $firstName . ' ' . $lastName;
			}			

			$data = array(
			'kd_pelanggan'	=> $this->getkod_model->get_kodpel(),
			'nama_pelanggan'  => $fullName,
			'email_pelanggan'	    	=> $this->input->post('email'),
			'img_pelanggan'		=> 'assets/frontend/img/default.png',
			'alamat_pelanggan'		=> $this->input->post('alamat'),
			'telpon_pelanggan'		=> $this->input->post('nomor'),
			'username_pelanggan'		=> $this->input->post('username'),
			'status_pelanggan' => 0,
			'date_create_pelanggan' => time(),
			'password_pelanggan'		=> password_hash($this->input->post('password1'),PASSWORD_DEFAULT)
			);
			$token = md5($this->input->post('email').date("d-m-Y H:i:s"));
			$data1 = array(
				'nama_token' => $token,
				'email_token' => $this->input->post('email'),
				'date_create_token' => time()
				 );
			$this->db->insert('tbl_pelanggan', $data);
			$this->db->insert('tbl_token_pelanggan', $data1);
			$this->_sendmail($token,'verify');
			$this->session->set_flashdata('message', 'swal("Succeeded", "Succeeded Register Please Check Your Email", "success");');
    		redirect('login');
		}

	}
	Private function _sendmail($token='',$type=''){
		$config = [
               'mailtype'  => 'html',
               'charset'   => 'utf-8',
               'protocol'  => getenv('MAIL_DRIVER'),
               'smtp_host' => getenv('MAIL_HOST'),
               'smtp_user' => getenv('MAIL_USERNAME'), // Ganti dengan email gmail kamu
               'smtp_pass' => getenv('MAIL_PASSWORD'),    // Password gmail kamu
               'smtp_port' => getenv('MAIL_PORT'),
               'crlf'      => "rn",
               'newline'   => "rn"
           ];
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from('HATID MOKO');
        $this->email->to($this->input->post('email'));
        // $this->email->attach('https://masrud.com/content/images/20181215150137-codeigniter-smtp-gmail.png');
		if ($type == 'verify') {
			$this->email->subject('Account Verification HATID MOKO Reservation');
			$this->email->message('Click the link below to verify your account: <a href="' . base_url('login/verify?email=' . $this->input->post('email') . '&token=' . $token) . '">Verify</a>');
		} elseif ($type == 'forgot') {
			$this->email->subject('Reset Account HATID MOKO Reservation');
			$this->email->message('Click the link below to reset your account: <a href="' . base_url('login/forgot?email=' . $this->input->post('email') . '&token=' . $token) . '">Reset Password</a>');
		}
		if ($this->email->send()) {
			return true;
		} else {
			echo 'Error! Email cannot be sent.';
		}
		
	}
	public function verify($value=''){
		$email = $this->input->get('email');
		$token = $this->input->get('token');
		$sqlcek = $this->db->get_where('tbl_pelanggan',['email_pelanggan' => $email])->row_array();
		if ($sqlcek) {
			$sqlcek_token = $this->db->get_where('tbl_token_pelanggan',['nama_token' => $token])->row_array();
			if ($sqlcek_token) {
				if(time() - $sqlcek_token['date_create_token'] < (60 * 60 * 24)){
					$update = array('status_pelanggan' => 1, );
					$where = array('email_pelanggan' => $email );
					$this->db->update('tbl_pelanggan', $update,$where);
					$this->db->delete('tbl_token_pelanggan',['email_token' => $email]);
					$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">
					Succeeded Login Verification Back Your Account
					</div>');
					redirect('login');
				}else{
					$this->db->delete('tbl_pelanggan',['email_pelanggan' => $email]);
					$this->db->delete('tbl_token_pelanggan',['email_token' => $email]);
					$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
					Token Expired Please register your account again
					</div>');
	    			redirect('login');
				}
			}else{
				$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
				Wrong Token Verification Failed
 
						</div>');
	    		redirect('login');
			}
		}else{
		$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
		Email Verification Failed

						</div>');
	    redirect('login');
		}
	}
	public function lupapassword($value=''){
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', array(
			'required' => 'Email must be filled.',
			'valid_email' => 'Please enter a valid email address.',
		));
		
		if ($this->form_validation->run() == false) {
			$this->load->view('frontend/lupapassword');
		} else {
			$email = $this->input->post('email');
			$sqlcek = $this->db->get_where('tbl_pelanggan', ['email_pelanggan' => $email, 'status_pelanggan' => 1])->row_array();
			if ($sqlcek) {
				$token = md5($email . date("d-m-Y H:i:s"));
				$data = array(
					'nama_token' => $token,
					'email_token' => $email,
					'date_create_token' => time()
				);
				$this->db->insert('tbl_token_pelanggan', $data);
				$this->_sendmail($token, 'forgot');
				$this->session->set_flashdata('message', 'swal("Succeeded", "Succeeded Reset Password Please Check Your Email", "success");');
				redirect('login');
			} else {
				$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
					Email not found or the account is not active.
				</div>');
				redirect('login/lupapassword');
			}
		}
	}
	public function forgot($value=''){
		$email = $this->input->get('email');
		$token = $this->input->get('token');
		$sqlcek = $this->db->get_where('tbl_pelanggan',['email_pelanggan' => $email])->row_array();
		if ($sqlcek) {
			$sqlcek_token = $this->db->get_where('tbl_token_pelanggan',['nama_token' => $token])->row_array();
			if ($sqlcek_token) {
				$this->session->set_userdata('resetemail' ,$email);
				$this->changepassword();
			}else{
				$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
						  Failed to Reset Wrong Email Token
 
						</div>');
	    		redirect('login');
			}
		}else{
		$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
		Failed to Reset Wrong Email

						</div>');
	    redirect('login');
		}
	}
	public function changepassword($value=''){
		if ($this->session->userdata('resetemail') == NULL) {
			redirect('login/daftar');
		}
		$this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[8]|matches[password2]',array(
			'matches' => 'The password is not the same.',
			'min_length' => 'Password Minimum 8 Characters.'
			 ));
		$this->form_validation->set_rules('password2', 'Password', 'trim|required|matches[password1]');
		if ($this->form_validation->run() == false) {
			$this->load->view('frontend/resetpassword');
		}else{
			$email = $this->session->userdata('resetemail');
			$update = array(
				'status_pelanggan' => 1,
				'password_pelanggan' => password_hash($this->input->post('password1'),PASSWORD_DEFAULT)
			);
			$where = array('email_pelanggan' => $email );
			$this->db->update('tbl_pelanggan', $update,$where);
			$this->session->unset_userdata('resetemail');
			$this->db->delete('tbl_token_pelanggan',['email_token' => $email]);
			$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">
			Succeeded Reset, Login Back to Your Account
			</div>');
			redirect('login');
		}
	}
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */