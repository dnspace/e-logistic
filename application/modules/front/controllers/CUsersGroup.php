<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CUsersGroup (CUsersGroupController)
 * CUsersGroup Class to control Data User Group.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CUsersGroup extends BaseController
{
    private $cname = 'user-groups';
    private $view_dir = 'front/accounts-group/';
    private $readonly = TRUE;
    
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        if($this->isSuperAdmin()){
            //load page
        }else{
            redirect('cl');
        }
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'List User Group - '.APP_NAME;
        $this->global['pageMenu'] = 'List User Group';
        $this->global['contentHeader'] = 'List User Group';
        $this->global['contentTitle'] = 'List User Group';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['classname'] = $this->cname;
        $data['url_list'] = base_url($this->cname.'/list/json');
        $this->loadViews($this->view_dir.'index', $this->global, $data);
    }
    
    /**
     * This function is used to get list for datatables
     */
    public function get_list($type){
        $rs = array();
        $arrWhere = array();
        $data = array();
        $output = null;
        $isParam = FALSE;
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_user_group'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        switch($type) {
            case "json":
                foreach ($rs as $r) {
                    $id = filter_var($r->group_id, FILTER_SANITIZE_NUMBER_INT);
                    $row['name'] = filter_var($r->group_display, FILTER_SANITIZE_STRING);

                    $row['button'] = '<div class="btn-group dropdown">';
                    $row['button'] .= '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></a>';
                    $row['button'] .= '<div class="dropdown-menu dropdown-menu-right">';
                    $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/edit/").$id.'"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>';
                    $row['button'] .= '<a class="dropdown-item" href="'.base_url($this->cname."/remove/").$id.'"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>';
                    $row['button'] .= '</div>';
                    $row['button'] .= '</div>';

                    $data[] = $row;
                }
                $output = $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('data'=>$data)));
            break;
            case "array":
                foreach ($rs as $r) {
                    $id = filter_var($r->group_id, FILTER_SANITIZE_NUMBER_INT);
                    $row['name'] = filter_var($r->group_display, FILTER_SANITIZE_STRING);
                    
                    $data[] = $row;
                }
                $output = $data;
            break;
        }
        return $output;
    }
    
    /**
     * This function is used to get list information described by function name
     */
    public function get_list_wh(){
        $rs = array();
        $arrWhere = array();
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
            $row['nearby'] = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
            $row['pic'] = stripslashes($r->fsl_pic) ? filter_var($r->fsl_pic, FILTER_SANITIZE_STRING) : "-";
            $row['phone'] = stripslashes($r->fsl_phone) ? filter_var($r->fsl_phone, FILTER_SANITIZE_STRING) : "-";
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get list information described by function name
     */
    public function get_list_info_wh($fcode){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fcode'=>$fcode);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $row['location'] = filter_var($r->fsl_location, FILTER_SANITIZE_STRING);
            $row['nearby'] = filter_var($r->fsl_nearby, FILTER_SANITIZE_STRING);
            $row['pic'] = stripslashes($r->fsl_pic) ? filter_var($r->fsl_pic, FILTER_SANITIZE_STRING) : "-";
            $row['phone'] = stripslashes($r->fsl_phone) ? filter_var($r->fsl_phone, FILTER_SANITIZE_STRING) : "-";
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to get list information described by function name
     */
    public function get_list_info($fkey){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fid'=>$fkey);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_user_group'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['id'] = filter_var($r->group_id, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->group_name, FILTER_SANITIZE_STRING);
            $row['display'] = filter_var($r->group_display, FILTER_SANITIZE_STRING);
            $row['enc'] = filter_var($r->group_enc, FILTER_SANITIZE_STRING);
 
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * This function is used to load the add new form
     */
    function add()
    {
        $this->global['pageTitle'] = "Add New Group - ".APP_NAME;
        $this->global['pageMenu'] = 'Add New Group';
        $this->global['contentHeader'] = 'Add New Group';
        $this->global['contentTitle'] = 'Add New Group';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['classname'] = $this->cname;
        $this->loadViews($this->view_dir.'create', $this->global, $data);
    }
    
    /**
     * This function is used to add new data to the system
     */
    function create()
    {
        $fname = $this->input->post('fname', TRUE);
        $fdisplay = $this->input->post('fdisplay', TRUE);

        $dataInfo = array('fname'=>$fname, 'fdisplay'=>$fdisplay);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_add_user_group'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect($this->cname.'/view');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect($this->cname.'/add');
        }
    }
    
    /**
     * This function is used load edit information
     * @param $fkey : Optional : This is data unique key
     */
    function edit($fkey = NULL)
    {
        if($fkey == NULL)
        {
            redirect($this->cname.'/view');
        }
        
        $this->global['pageTitle'] = "Edit Data Group - ".APP_NAME;
        $this->global['pageMenu'] = 'Edit Data Group';
        $this->global['contentHeader'] = 'Edit Data Group';
        $this->global['contentTitle'] = 'Edit Data Group';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;
        
        $data['classname'] = $this->cname;
        $data['records'] = $this->get_list_info($fkey);
        $this->loadViews($this->view_dir.'edit', $this->global, $data);
    }
    
    /**
     * This function is used to edit the data information
     */
    function update()
    {
        $fid = $this->input->post('fid', TRUE);
        $fname = $this->input->post('fname', TRUE);
        $fdisplay = $this->input->post('fdisplay', TRUE);

        $dataInfo = array('fid'=>$fid, 'fname'=>$fname, 'fdisplay'=>$fdisplay);
        
        $rs_data = send_curl($this->security->xss_clean($dataInfo), $this->config->item('api_edit_user_group'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
            redirect($this->cname.'/view');
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
            redirect($this->cname.'/edit/'.$fkey);
        }
    }
    
    /**
     * This function is used to delete the data
     * @return boolean $result : TRUE / FALSE
     */
    function delete($fid = NULL)
    {
        $arrWhere = array();
        $arrWhere = array('fid'=>$fid);

        $rs_data = send_curl($this->security->xss_clean($arrWhere), $this->config->item('api_remove_user_group'), 'POST', FALSE);

        if($rs_data->status)
        {
            $this->session->set_flashdata('success', $rs_data->message);
        }
        else
        {
            $this->session->set_flashdata('error', $rs_data->message);
        }

        redirect($this->cname.'/view');
    }
}