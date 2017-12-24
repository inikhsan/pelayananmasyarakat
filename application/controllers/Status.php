<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Status extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Status_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'status/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'status/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'status/index.html';
            $config['first_url'] = base_url() . 'status/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Status_model->total_rows($q);
        $status = $this->Status_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'status_data' => $status,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('status/status_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Status_model->get_by_id($id);
        if ($row) {
            $data = array(
		'idstatus' => $row->idstatus,
		'deskripsi' => $row->deskripsi,
	    );
            $this->load->view('status/status_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('status'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('status/create_action'),
	    'idstatus' => set_value('idstatus'),
	    'deskripsi' => set_value('deskripsi'),
	);
        $this->load->view('status/status_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'deskripsi' => $this->input->post('deskripsi',TRUE),
	    );

            $this->Status_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('status'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Status_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('status/update_action'),
		'idstatus' => set_value('idstatus', $row->idstatus),
		'deskripsi' => set_value('deskripsi', $row->deskripsi),
	    );
            $this->load->view('status/status_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('status'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('idstatus', TRUE));
        } else {
            $data = array(
		'deskripsi' => $this->input->post('deskripsi',TRUE),
	    );

            $this->Status_model->update($this->input->post('idstatus', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('status'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Status_model->get_by_id($id);

        if ($row) {
            $this->Status_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('status'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('status'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('deskripsi', 'deskripsi', 'trim|required');

	$this->form_validation->set_rules('idstatus', 'idstatus', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Status.php */
/* Location: ./application/controllers/Status.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-01-08 09:24:29 */
/* http://harviacode.com */