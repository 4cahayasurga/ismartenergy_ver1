<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_flow extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Pipeline_flow_model','flow');
        if (!$this->session->userdata('pipeline_form_token')) {
            $this->session->set_userdata('pipeline_form_token',bin2hex(random_bytes(24)));
        }
    }

    private function render($view,$data)
    {
        $data += array('judul'=>'Pesanan Pipanisasi','pipeline_token'=>$this->session->userdata('pipeline_form_token'));
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar');
        $this->load->view('pipeline/'.$view,$data);
        $this->load->view('templates/footer');
    }

    private function guard()
    {
        if ($this->input->method()!=='post') show_error('Gunakan form yang tersedia.',405);
        $token = $this->input->post('pipeline_token');
        if (!is_string($token) || !hash_equals($this->session->userdata('pipeline_form_token'),$token)) show_error('Form kedaluwarsa.',403);
    }

    private function request($id)
    {
        $row = $this->flow->find((int)$id);
        if (!$row) show_404();
        return $row;
    }

    public function index()
    {
        $this->pipeline_access->require_menu('customer_order');
        $this->render('flow_customer',array('rows'=>$this->flow->listing(),'statuses'=>$this->flow->statuses(),'company'=>$this->flow->company()));
    }

    public function submissions()
    {
        $this->pipeline_access->require_menu('customer_order');
        $rows=array_values(array_filter($this->flow->listing(),function($row){
            return $row['Status']!=='DRAFT' || $this->flow->supervisor_state($row)==='PENDING';
        }));
        $this->render('flow_customer',array('rows'=>$rows,'statuses'=>$this->flow->statuses(),
            'approval_only'=>true,'submission_list'=>true));
    }

    public function create()
    {
        $this->pipeline_access->require_menu('customer_order');
        $company = $this->flow->company();
        $profile = $this->flow->profile($company);
        $areas = $this->flow->operations->billing->branches();
        $vessels = $this->Spbb_model->customer_vessels($this->flow->actor());
        $members = $this->flow->members($company);
        $supervisors = $this->flow->supervisors($company,$this->flow->actor());
        $error = '';
        $input = array();
        if ($this->input->method()==='post') {
            $this->guard();
            $input = $this->input->post();
            try {
                $id = $this->flow->create($input);
                redirect('Pipeline_flow/detail/'.$id);
                return;
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        $this->render('flow_create',compact('company','profile','areas','vessels','members','supervisors','error','input'));
    }

    public function edit($id)
    {
        $row=$this->request($id);
        if (!$this->flow->may_manage_order($row)) show_error('Pesanan tidak dapat diedit pada tahap ini.',403);
        $this->render('flow_edit',array('row'=>$row,'letter'=>json_decode($row['Letter'],true),'version'=>$this->flow->order_version($row)));
    }

    public function profile()
    {
        $this->pipeline_access->require_menu('customer_logo');
        $company = $this->flow->company();
        if (!$company) show_error('Minta administrator menghubungkan akun ke badan usaha.',403);
        $error = '';
        if ($this->input->method()==='post') {
            $this->guard();
            try {
                $this->flow->save_profile($company,$this->input->post(),$_FILES['Logo']??null);
                $this->session->set_flashdata('Berhasil','Profil perusahaan disimpan.');
                redirect('Pipeline_flow/profile');
                return;
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        $profile = $this->flow->profile($company);
        $this->render('flow_profile',compact('company','profile','error'));
    }

    public function settings()
    {
        $this->pipeline_access->require_menu('flow_settings');
        if ($this->input->method()==='post') {
            $this->guard();
            $this->flow->save_settings($this->input->post());
            $this->session->set_flashdata('Berhasil','Persyaratan untuk pesanan baru disimpan.');
            redirect('Pipeline_flow/settings');
            return;
        }
        $this->load->model('Pipeline_customer_vessel_model','customer_vessel_settings');
        $this->render('flow_settings',array('vesselSettingsReady'=>$this->customer_vessel_settings->settings_ready(),
            'vesselSettings'=>$this->customer_vessel_settings->settings(),'vesselFields'=>$this->customer_vessel_settings->fields(),'rules'=>$this->flow->settings(),'showTransportAssignment'=>$this->flow->show_transport_assignment(),'labels'=>$this->flow->labels()));
    }

    public function detail($id)
    {
        $row = $this->request($id);
        $attachmentError='';
        if(in_array($row['Status'],array('DRAFT','REVISION','SUBMITTED'),true) && $this->flow->approved_bunker($row)) {
            try{$this->load->library('Pipeline_bunker_pdf');$this->pipeline_bunker_pdf->ensure($row);}catch(Throwable $e){$attachmentError=$e->getMessage();}
        }
        $letter = json_decode($row['Letter'],true);
        $currentCompany=$this->db->select('CompanyName')->where('BusinessEntityID',$row['BusinessEntityID'])->get('JbtBusinessEntity')->row_array();
        $rules = $this->flow->document_requirements($row);
        $files = $this->flow->files($id);
        $approvals = $this->flow->approvals($id);
        $blockers = in_array($row['Status'],array('FILLING','FILLED','BA_APPROVAL','BASTB_APPROVAL','COMPLETED'),true)?array():$this->flow->start_blockers($row,$files);
        $pending = $this->flow->pending($row);
        $supervisors = $this->flow->is_customer_editor($row)?$this->flow->supervisors($this->flow->company(),$row['CreatedBy']):array();
        $staff = $this->flow->staff();
        $editor = $this->flow->is_customer_editor($row) && in_array($row['Status'],array('DRAFT','REVISION'),true);
        $captures = $staff && $this->flow->awaiting_source($row)?$this->flow->captures($row):array();
        $verifiedCapture=$staff && $row['CaptureID']?$this->flow->aggregate_source($row):null;
        $prefilled = array_column($captures,null,'CaptureID')[(int)$this->input->get('spp')]??null;
        $sourceMismatches=$prefilled?$this->flow->source_mismatches($row,$prefilled):array();
        $sourceConflicts=$prefilled?$this->flow->source_conflicts($prefilled):array();
        $resolveKey=$sourceConflicts && !array_filter(array_column($sourceConflicts,'Billed')) && $this->pipeline_access->allowed('transport_assign')
            ?$this->flow->source_conflict_key($prefilled,$sourceConflicts):null;
        $sourceKind=$this->input->get('source');
        $vesselIdentity=$staff?$this->flow->vessel_identity($row):null;
        $this->render('flow_detail',compact('row','letter','rules','files','blockers','approvals','pending','supervisors','staff','editor','captures','prefilled','attachmentError','verifiedCapture','sourceConflicts','sourceMismatches','resolveKey','sourceKind','vesselIdentity','currentCompany')+
            array('labels'=>$this->flow->labels(),'statuses'=>$this->flow->statuses(),'slots'=>$this->flow->slots()));
    }

    public function action($id)
    {
        $this->guard();
        $row = $this->request($id);
        $action = $this->input->post('action');
        $ajax=$this->input->is_ajax_request() && in_array($action,array('upload','recheck'),true);
        try {
            if ($action==='upload') {
                $kind = $this->input->post('Kind');
                if (!is_string($kind)) throw new RuntimeException('Jenis dokumen tidak valid.');
                $this->flow->put_file((int)$id,$kind,$_FILES['Document']??array());
            } elseif ($action==='recheck') {
                $kind=$this->input->post('Kind');
                if(!is_string($kind))throw new RuntimeException('Jenis dokumen tidak valid.');
                $this->flow->recheck_file((int)$id,$kind);
            } elseif ($action==='make_ba') {
                $this->flow->make_ba((int)$id,$this->input->post());
            } elseif ($action==='approve') {
                $slot = $this->input->post('Slot');
                if (!is_string($slot)) throw new RuntimeException('Jabatan tidak valid.');
                if ($this->input->post('Confirm')!=='1') throw new RuntimeException('Baca dokumen dan centang konfirmasi.');
                $this->flow->approve((int)$id,$slot,$this->input->post('DocumentHash'),$this->input->post());
            } else {
                $this->flow->change((int)$id,$action,$this->input->post());
            }
            if($ajax) {
                $fresh=$this->request($id);$file=$this->flow->files($id)[$kind]??null;
                $html=$this->load->view('pipeline/flow_file',array('row'=>$fresh,'file'=>$file,'kind'=>$kind,'label'=>$this->flow->labels()[$kind],
                    'rules'=>json_decode($fresh['Requirements'],true),'editor'=>$this->flow->is_customer_editor($fresh) && in_array($fresh['Status'],array('DRAFT','REVISION'),true),
                    'staff'=>$this->flow->staff(),'actionUrl'=>site_url('Pipeline_flow/action/'.$id),'pipeline_token'=>$this->session->userdata('pipeline_form_token')),true);
                $this->output->set_header('Cache-Control: no-store')->set_content_type('application/json')->set_output(json_encode(array('ok'=>true,'kind'=>$kind,'html'=>$html,
                    'message'=>'File tersimpan. Dokumen lain tetap tersedia.','csrf'=>array('name'=>$this->security->get_csrf_token_name(),'hash'=>$this->security->get_csrf_hash()))));return;
            }
            if (in_array($action,array('edit_order','cancel_order'),true)) {
                $this->session->set_flashdata('Berhasil',$action==='cancel_order'?'Pesanan dibatalkan.':'Rencana pengisian diperbarui.');
                redirect('Pipeline_flow');
                return;
            }
            $this->session->set_flashdata('Berhasil','Perubahan pesanan tersimpan.');
            if ($action==='approve' && $this->input->post('Slot')==='BUNKER_SUPERVISOR'
                && !$this->flow->find($id)) {
                redirect('Pipeline_flow/supervision');
                return;
            }
            if ($this->flow->staff() && in_array($action, array('revise', 'reject_file'), true)) {
                redirect('Pipanisasi/orders');
                return;
            }
        } catch (Exception $e) {
            if($ajax){$this->output->set_status_header(422)->set_content_type('application/json')->set_output(json_encode(array('ok'=>false,'message'=>$e->getMessage(),
                'csrf'=>array('name'=>$this->security->get_csrf_token_name(),'hash'=>$this->security->get_csrf_hash()))));return;}
            $this->session->set_flashdata('Gagal',$e->getMessage());
            if (in_array($action,array('edit_order','cancel_order'),true)) {
                redirect('Pipeline_flow');
                return;
            }
            if($action==='verify_source'){
                $captureId=$this->input->post('CaptureID');
                $kind=$this->input->post('SourceKind');
                if(is_string($captureId) && ctype_digit($captureId)){
                    redirect('Pipeline_flow/detail/'.$id.'?spp='.(int)$captureId.'&source='.($kind==='DO'?'DO':'SPP').'#verifikasi-sumber');
                    return;
                }
            }
        }
        redirect('Pipeline_flow/detail/'.$id);
    }

    public function link_delivery($id)
    {
        $this->guard();
        if(!$this->flow->staff())show_error('Aksi khusus Petugas Bunkering / Pipanisasi.',403);
        $delivery=$this->flow->operations->billing->delivery((int)$id);
        if(!$delivery)show_404();
        try{
            $request=$this->input->post('RequestID');
            if(!is_string($request)||!ctype_digit($request)||$this->input->post('ConfirmSource')!=='1')throw new RuntimeException('Pilih permohonan dan konfirmasi kecocokan dokumen.');
            $docs=$this->flow->operations->billing->current_documents($id);
            $spp=$docs['SPP']??null;
            $capture=$spp?$this->db->where('SpbbID',$delivery['SpbbID'])->where('FileHash',$spp['FileHash'])->where('Status','CONFIRMED')->get('PipelineSppCapture')->row_array():null;
            if(!$capture)throw new RuntimeException('Konfirmasi arsip OCR SPP yang dilampirkan pada DO terlebih dahulu.');
            $this->flow->change((int)$request,'verify_source',array('CaptureID'=>(string)$capture['CaptureID'],'SourceKind'=>'SPP','ConfirmSource'=>'1','LinkedDeliveryID'=>(string)$id));
            $this->session->set_flashdata('Berhasil','DO terverifikasi. Tekan Mulai Pengisian pada detail pesanan.');
            redirect('Pipeline_flow/detail/'.(int)$request.'#proses-pengisian');
            return;
        }catch(RuntimeException $e){$this->session->set_flashdata('Gagal',$e->getMessage());}
        redirect('Pipanisasi/delivery?start='.substr($delivery['DeliveryDate'],0,10).'&end='.substr($delivery['DeliveryDate'],0,10).'&do='.(int)$id.'#do-'.(int)$id);
    }

    public function supervision()
    {
        $this->pipeline_access->require_menu('customer_supervision');
        $rows=array();
        $revisions=array();
        foreach ($this->flow->listing() as $row) {
            if (isset($this->flow->pending($row)['BUNKER_SUPERVISOR'])) $rows[]=$row;
            elseif ($this->flow->revision_assigned_to_supervisor($row)) $revisions[]=$row;
        }
        $creatorIds=array_unique(array_column(array_merge($rows,$revisions),'CreatedBy'));
        $creators=$creatorIds?array_column($this->db->select('UserID,UserName')->where_in('UserID',$creatorIds)->get('Login')->result_array(),'UserName','UserID'):array();
        $this->render('flow_customer',array('rows'=>$rows,'statuses'=>$this->flow->statuses(),
            'approval_only'=>true,'supervision_only'=>true,'creators'=>$creators,'revisions'=>$revisions));
    }

    public function approval()
    {
        $this->pipeline_access->require_menu('flow_approval');
        $rows=array();
        $completedKkm=array();
        foreach ($this->flow->listing() as $row) {
            if ($this->flow->pending($row) || $this->flow->kkm_task($row)) $rows[]=$row;
            elseif ($this->flow->kkm_completed($row)) $completedKkm[]=$row;
        }
        $this->render('flow_customer',array('rows'=>$rows,'statuses'=>$this->flow->statuses(),
            'approval_only'=>true,'kkm_list'=>true,'completedKkm'=>$completedKkm));
    }

    private function send_file($path,$mime,$filename)
    {
        if (!is_file($path)) show_404();
        $this->output->set_header('Cache-Control: private, no-store')->set_header('X-Content-Type-Options: nosniff')
            ->set_header('Content-Disposition: inline; filename="'.$filename.'"')->set_content_type($mime)->set_output(file_get_contents($path));
    }

    public function file($id,$kind)
    {
        $row = $this->request($id);
        if ($kind==='SPP' && $row['CaptureID']) {
            $file = $this->db->where('CaptureID',$row['CaptureID'])->get('PipelineSppCapture')->row_array();
            $dir = 'pipeline';
            $mime = $file['MimeType']??'';
        } else {
            $file = $this->flow->files($id)[$kind]??null;
            $dir = 'customer_flow';
            $mime = $file['Mime']??'';
        }
        if (!$file || !preg_match('/^[a-f0-9]{48}\.(jpg|png|pdf)$/D',$file['StoredName'])) show_404();
        $this->send_file(APPPATH.'storage/'.$dir.'/'.$file['StoredName'],$mime,$kind.'.'.pathinfo($file['StoredName'],PATHINFO_EXTENSION));
    }

    public function logo()
    {
        $this->pipeline_access->require_menu('customer_logo');
        $profile = $this->flow->profile($this->flow->company());
        if (!$profile) show_404();
        $this->send_file(APPPATH.'storage/customer_flow/'.$profile['Logo'],$profile['LogoMime'],'logo.'.pathinfo($profile['Logo'],PATHINFO_EXTENSION));
    }

    public function document($id,$kind='Bunker')
    {
        require_once APPPATH.'libraries/Pipeline_approval_qr.php';
        $row = $this->request($id);
        $url = site_url('Pipeline_flow/document/'.$id.'/'.$kind);
        $pdf = $this->input->get('pdf')==='1';
        if ($kind==='Bunker') {
            $this->load->library('Pipeline_bunker_pdf');
            if($pdf && $this->flow->approved_bunker($row)) {
                $cached=$this->pipeline_bunker_pdf->ensure($row);
                $this->output->set_header('Cache-Control: private, no-store')->set_header('Content-Disposition: attachment; filename="Permohonan-Disetujui-'.(int)$id.'.pdf"')->set_content_type('application/pdf')->set_output(file_get_contents($cached));return;
            }
            $html=$this->pipeline_bunker_pdf->html($row,$pdf);
        } else {
            if (!in_array($kind,array('BA','BASTB'),true) || !$row[$kind] ||
                ($kind==='BASTB' && $row['Status']==='BA_APPROVAL')) show_404();
            if ($kind==='BA' && $this->flow->filling_progress($row)) show_404();
            $s = json_decode($row[$kind],true);
            $approval = $this->flow->approvals($id);
            $proof = array();
            require_once APPPATH.'third_party/pipeline_qr/qrlib.php';
            foreach ($this->flow->slots() as $slot=>$meta) {
                if ($meta[0]!==$kind || !isset($approval[$slot])) continue;
                $a = $approval[$slot];
                $verify = site_url('Pipeline_verify/index/'.$a['Token']);
                $payload = Pipeline_approval_qr::payload($a);
                $tempQr = tempnam(sys_get_temp_dir(),'flow-qr-');
                if (!$tempQr) show_error('QR tidak dapat dibuat.',500);
                try {
                    QRcode::png($payload,$tempQr,QR_ECLEVEL_M,4,4);
                    $png = file_get_contents($tempQr);
                } finally {
                    unlink($tempQr);
                }
                $proof[$meta[1]] = array('png'=>'data:image/png;base64,'.base64_encode($png),'url'=>$verify,'name'=>$a['Approver']);
            }
            $final = $kind==='BA' ? in_array($row['Status'],array('BASTB_APPROVAL','COMPLETED'),true) : $row['Status']==='COMPLETED';
            $printRow = array('Kind'=>$kind==='BA'?'RDT':'BASTB','DocumentNo'=>$s['DocumentNo'],'DocumentDate'=>$s['DocumentDate']);
            $html = $this->load->view('pipeline/operation_print',array('row'=>$printRow,'s'=>$s,'pdf_mode'=>$pdf,'workflow_url'=>$url,
                'workflow_draft'=>!$final,'proof'=>$proof,'workflow_back'=>site_url('Pipeline_flow/detail/'.$id)),true);
        }
        if (!$pdf) {
            $this->output->set_header('Cache-Control: private, no-store')->set_output($html);
            return;
        }
        require_once FCPATH.'vendor/autoload.php';
        $temp = sys_get_temp_dir().'/ismartenergy_ver1_pdf';
        if (!is_dir($temp)) mkdir($temp,0700,true);
        $pdfFile = new \Mpdf\Mpdf(array('mode'=>'c','format'=>'A4','default_font'=>$kind==='Bunker'?'times':'helvetica',
            'margin_top'=>9,'margin_bottom'=>10,'margin_left'=>$kind==='Bunker'?26:15,'margin_right'=>15,'tempDir'=>$temp));
        $pdfFile->WriteHTML($html);
        $pdfFile->Output($kind.'-'.$id.'.pdf','D');
    }
}
