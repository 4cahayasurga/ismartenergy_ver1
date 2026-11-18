<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	var $Company;

	function __construct()
	{
		parent::__construct();
		$this->load->model('Users');
		$this->Company = $this->config->item('Company');
		

	}

	function index($Lakukan = '')
	{
		// $token = $this->input->get('token');
		// if($token == NULL){
		// 	// redirect('http://localhost/Real-Point/dashboard');die;
		// 	redirect('http://147.139.178.49/Intranet/');die;
		// }
		// $pisahToken = explode('_', $token);
		// $get = $this->db->query("SELECT * FROM Login WHERE UserID='$pisahToken[1]'")->row_array();
		// if ($pisahToken[0] != $get['TokenLogin']) {
		// 	$this->session->set_flashdata('error_login', 'Your token does not match, please try again');
		// 	// redirect('http://localhost/Real-Point/dashboard');die;
		// 	redirect('http://147.139.178.49/Intranet/');die;
		// }else
		
		// {
		// 	$getToken2 = $pisahToken[1];
		// 	$username = $getToken2;
		// 	$user = $this->Users->getUserByUsername($getToken2);
		// 	$NIK_tidak_ada_di_login = 0;
		// 	if ($user) {
		// 		$NIK_tidak_ada_di_login = 1;
		// 	} else {
		// 		$NIK_tidak_ada_di_login = 0;
		// 	}
		// 	if ($NIK_tidak_ada_di_login == 0) {
		// 		$dapat_nama_karyawan = $this->db->query("SELECT Nama, Password_dari_CI, NIK, Password_dari_CI FROM Karyawan WHERE NIK='$username' AND Active='1' ");
		// 		if ($dapat_nama_karyawan->num_rows() == 0) {
		// 			is_pesan_saya('error', '<u>' . $username . '</u> sudah tidak aktif ');
		// 			redirect('Auth');
		// 			exit;
		// 		}
		// 		$Password_dari_CI = '$1$VgN0LLc6$k.2knAm0n5I6tJdhtJIh2.';
		// 		$this->db->query("UPDATE Karyawan SET Password_dari_CI='$Password_dari_CI' WHERE NIK='$username'   ");
		// 		$Company = $this->config->item('Company');
		// 		$dapat_nama_karyawan = $dapat_nama_karyawan->row_array();
		// 		$UserID_buatan = $dapat_nama_karyawan['Nama'];
		// 		$UserID_buatan = explode(' ', $UserID_buatan);
		// 		$UserID_buatan = strtolower($UserID_buatan[0]);
		// 		$mydata = array(
		// 			'UserID' => $UserID_buatan,
		// 			'UserName' => $dapat_nama_karyawan['Nama'],
		// 			'AksesCompany' => $Company,
		// 			'Password_dari_CI' => $Password_dari_CI,
		// 			'NIK' => $dapat_nama_karyawan['NIK'],
		// 			'PasswordCI' => $Password_dari_CI,
		// 			'Active' => 1
		// 		);
		// 		$cek_UserID_sudah_ada = $this->db->where('UserID', $UserID_buatan)->get('Login')->num_rows();
		// 		if ($cek_UserID_sudah_ada > 0) {
		// 			$mydata['UserID'] = $UserID_buatan . '1';
		// 		}
		// 		$this->db->trans_begin();
		// 		$this->db->insert('Login', $mydata);
		// 		//ubah login gak aktif kalau ada nama yang sama
		// 		$NamaDiLogin = $mydata['UserName'];
		// 		$NIKDiLogin = $mydata['NIK'];
		// 		$this->db->query("UPDATE Login SET Active='0' WHERE UserName='$NamaDiLogin' AND NIK !='$NIKDiLogin' ");
		// 		if ($this->db->trans_status() === FALSE) {
		// 			$this->db->trans_rollback();
		// 			$this->index('0');
		// 		} else {
		// 			$this->db->trans_commit();
		// 			$this->index('1');
		// 		}
		// 	}
		// 	if ($user->Active != '1') {
		// 		is_pesan_saya('error_login', 'Access Denied, User is inactive');
		// 		// redirect('http://localhost/Real-Point/dashboard');die;
		// 		redirect('http://147.139.178.49/Intranet/');die;
		// 	}
		// 	$myquery = $this->db->query("SELECT item FROM dbo.SplitString((SELECT AksesCompany FROM Login WHERE (UserID = '$username' OR NIK='$username' )),',')");
		// 	$AksesCompany = '';
		// 	foreach ($myquery->result() as $data_company) :
		// 		if ($data_company->item == $this->Company) {
		// 			$AksesCompany = $this->Company;
		// 		}
		// 	endforeach;
		// 	if ($AksesCompany == '' or $AksesCompany == NULL) {
		// 		is_pesan_saya('error', 'Access Denied, you do not have access to this menu');
		// 		// redirect('http://localhost/Real-Point/dashboard');die;
		// 		redirect('http://147.139.178.49/Intranet/');die;
		// 	}
		// 	if ($user) {
		// 		$this->session->set_userdata([
		// 				'MIS_LOGGED_ID'   => $user->UserID,
		// 				'MIS_LOGGED_CORP'   => $this->input->post('corp'),
		// 				'MyCompany' => $this->Company,
		// 				'MIS_LOGGED_NAME'   => $user->UserName,
		// 				'MIS_LOGGED_TOKEN'  => json_encode($user),
		// 				'MIS_LOGGED_NIK'    => $user->NIK,
		// 				'PecahToken'    => json_decode(json_encode($user))
		// 			]);
		// 		redirect($this->pipeline_access->landing());
		// 	} else {
		// 		// redirect('http://localhost/Real-Point');die;
		// 		redirect('http://147.139.178.49/Intranet/');die;
		// 	}
		// }

		if ($this->input->post() ||  $Lakukan != '') {
			$username = trim((string) $this->input->post('username', TRUE));
			$attempt_key = $this->loginAttemptKey($username);
			if ($this->input->post() && $this->isLoginLocked($attempt_key)) {
				is_pesan_saya('error', 'Terlalu banyak percobaan login. Silakan tunggu 15 menit.');
				redirect('Auth');
				return;
			}
			$user = $this->Users->getUserByUsername($username);

			$NIK_tidak_ada_di_login = 0;
			if ($user) {
				$NIK_tidak_ada_di_login = 1;
			} else {
				$NIK_tidak_ada_di_login = 0;
			}

			if ($NIK_tidak_ada_di_login == 0) {
				$this->recordLoginAttempt($attempt_key, $username, false);
				is_pesan_saya('error', 'Username dan password tidak sesuai');
				redirect('Auth');
				return;

				$dapat_nama_karyawan = $this->db->query("SELECT Nama, Password_dari_CI, NIK, Password_dari_CI FROM Karyawan WHERE NIK=? AND Active='1' ", array($username));

				if ($dapat_nama_karyawan->num_rows() == 0) {
					is_pesan_saya('error', '<u>' . $username . '</u> sudah tidak aktif ');
					redirect('Auth');
					exit;
				}

				$Password_dari_CI = '$1$VgN0LLc6$k.2knAm0n5I6tJdhtJIh2.';
				$this->db->query("UPDATE Karyawan SET Password_dari_CI='$Password_dari_CI' WHERE NIK='$username'   ");
				$Company = $this->config->item('Company');

				$dapat_nama_karyawan = $dapat_nama_karyawan->row_array();

				$UserID_buatan = $dapat_nama_karyawan['Nama'];
				$UserID_buatan = explode(' ', $UserID_buatan);
				$UserID_buatan = strtolower($UserID_buatan[0]);


				$mydata = array(

					'UserID' => $UserID_buatan,
					'UserName' => $dapat_nama_karyawan['Nama'],
					'AksesCompany' => $Company,

					'Password_dari_CI' => $Password_dari_CI,
					'NIK' => $dapat_nama_karyawan['NIK'],
					'PasswordCI' => $Password_dari_CI,
					'Active' => 1

				);

				$cek_UserID_sudah_ada = $this->db->where('UserID', $UserID_buatan)->get('Login')->num_rows();
				if ($cek_UserID_sudah_ada > 0) {
					$mydata['UserID'] = $UserID_buatan . '1';
				}

				$this->db->trans_begin();

				$this->db->insert('Login', $mydata);

				//ubah login gak aktif kalau ada nama yang sama
				$NamaDiLogin = $mydata['UserName'];
				$NIKDiLogin = $mydata['NIK'];

				$this->db->query("UPDATE Login SET Active='0' WHERE UserName='$NamaDiLogin' AND NIK !='$NIKDiLogin' ");

				if ($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					$this->index('0');
				} else {
					$this->db->trans_commit();
					$this->index('1');
				}
			}

			if ($user->Active != '1') {
				is_pesan_saya('error', 'Akses Ditolak, User tidak aktif');
				redirect('Auth', 'refresh');
			}

			$myquery = $this->db->query(
				"
				SELECT item FROM
				dbo.SplitString( 
					(SELECT AksesCompany FROM Login WHERE (UserID = ? OR NIK=? ) ), 
					',')

				", array($username, $username)
			);

			$AksesCompany = '';

			foreach ($myquery->result() as $data_company) :

				if ($data_company->item == $this->Company) {
					$AksesCompany = $this->Company;
				}
			endforeach;


			if ($AksesCompany == '' or $AksesCompany == NULL) {
				is_pesan_saya('error', 'Akses ditolak');
				redirect('Auth', 'refresh');
			}

			if ($user) {

				if (password_verify($this->input->post('password'), $user->PasswordCI)) {
					$this->recordLoginAttempt($attempt_key, $username, true);
					$this->session->sess_regenerate(TRUE);
                    $this->session->unset_userdata('pipeline_access_version');
                    $this->load->library('Pipeline_access');

					$this->session->set_userdata([
						'MIS_LOGGED_ID'   => $user->UserID,
						'MIS_LOGGED_CORP'   => $this->input->post('corp'),
						'MyCompany' => $this->Company,
						'MIS_LOGGED_NAME'   => $user->UserName,
						'MIS_LOGGED_TOKEN'  => json_encode($user),
						'MIS_LOGGED_NIK'    => $user->NIK,
						'PecahToken'    => json_decode(json_encode($user)),
						'USER_ROLE' => isset($user->UserRole) ? $user->UserRole : 'CUSTOMER',
						'SPBB_ID' => isset($user->SpbbID) ? $user->SpbbID : NULL,

						'IS_LOGIN'          => TRUE,
                		'LOGIN_TIME'        => date('Y-m-d H:i:s')
					]);

                    $this->load->library('Pipeline_identity');
                    if (!$this->pipeline_identity->valid(true)) { $this->session->sess_destroy(); redirect('Auth'); return; }

					// print_rr($this->session->userdata());	
					// exit;

					if (isset($user->UserRole) && $user->UserRole == 'CUSTOMER') {
						redirect($this->pipeline_access->landing());
					} elseif (isset($user->UserRole) && $user->UserRole == 'PEMILIK_SPBB') {
						redirect($this->pipeline_access->landing());
					} elseif (isset($user->UserRole) && $user->UserRole == 'OPERATOR') {
						redirect($this->pipeline_access->landing());
					} else {
						redirect($this->pipeline_access->landing());
					}
				} else {
					$this->recordLoginAttempt($attempt_key, $username, false);
					is_pesan_saya('error', 'Username dan password tidak sesuai');
					redirect('Auth');
				}
			} else {
				is_pesan_saya('error', 'Username dan password tidak sesuai');
				redirect('Auth');
			}
		} else {

			$this->load->view('auth');
		}
	}

	public function updatePassword()
	{
		is_login();
		if ($this->input->post()) {
			$user_id = $this->session->userdata('MIS_LOGGED_ID');
			$user = $this->Users->getUserByUsername($user_id);
			$current = (string) $this->input->post('CurrentPassword');
			$password = (string) $this->input->post('Password');
			$confirm = (string) $this->input->post('PasswordConfirmation');

			if (!$user || !password_verify($current, $user->PasswordCI)) {
				$this->session->set_flashdata('Gagal', 'Password saat ini tidak sesuai');
			} elseif (strlen($password) < 10 || !preg_match('/[A-Z]/', $password) ||
				!preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
				$this->session->set_flashdata('Gagal', 'Password baru minimal 10 karakter dan harus memuat huruf besar, huruf kecil, serta angka');
			} elseif ($password !== $confirm) {
				$this->session->set_flashdata('Gagal', 'Konfirmasi password baru tidak sesuai');
			} else {
				$this->Users->updateUser(array(
					'PasswordCI' => password_hash($password, PASSWORD_DEFAULT)
				), $user_id);
				$this->session->sess_regenerate(TRUE);
                    $this->session->unset_userdata('pipeline_access_version');
                    $this->load->library('Pipeline_access');
				$this->session->set_flashdata('Berhasil', 'Password berhasil diubah');
			}
		}
		redirect($this->pipeline_access->landing());
	}

	function logout()
	{
        if ($this->input->method() !== 'post') {
            $this->load->view('auth_logout');
            return;
        }
		if ($this->config->item('Company') == $this->Company) {
			$date = array('last_login' => date('Y-m-d H:i:s'));
			$UserID = $this->session->userdata('MIS_LOGGED_ID');
			$this->db->set($date)->where('UserID', $UserID)->update('Login');
			$this->session->sess_destroy();
			redirect('Auth');
			// redirect('http://147.139.178.49/Intranet/');
		}
	}

	private function loginAttemptKey($username)
	{
		return hash('sha256', strtolower(trim($username)) . '|' . $this->input->ip_address());
	}

	private function isLoginLocked($attempt_key)
	{
		if (!$this->db->table_exists('SecurityLoginAttempt')) {
			return false;
		}
		return $this->db->where('AttemptKey', $attempt_key)
			->where('IsSuccess', 0)
			->where('AttemptedAt >=', date('Y-m-d H:i:s', time() - 900))
			->count_all_results('SecurityLoginAttempt') >= 5;
	}

	private function recordLoginAttempt($attempt_key, $username, $success)
	{
		if (!$this->db->table_exists('SecurityLoginAttempt')) {
			return;
		}
		if ($success) {
			$this->db->where('AttemptKey', $attempt_key)->delete('SecurityLoginAttempt');
			return;
		}
		$this->db->insert('SecurityLoginAttempt', array(
			'AttemptKey' => $attempt_key,
			'Username' => substr($username, 0, 100),
			'IpAddress' => substr($this->input->ip_address(), 0, 45),
			'IsSuccess' => 0,
			'AttemptedAt' => date('Y-m-d H:i:s'),
		));
		$this->db->where('AttemptedAt <', date('Y-m-d H:i:s', time() - 2592000))
			->delete('SecurityLoginAttempt');
	}
}
