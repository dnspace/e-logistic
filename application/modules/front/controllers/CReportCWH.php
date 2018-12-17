<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : CParts (CPartsController)
 * CParts Class to control Data Parts.
 * @author : Sigit Prayitno
 * @version : 1.0
 * @since : Mei 2017
 */
class CReportCWH extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
    }
    
    //View List Index
    public function index(){ 
        $this->global['pageTitle'] = 'Report Ticket - '.APP_NAME;
        $this->global['pageMenu'] = 'Report Ticket';
        $this->global['contentHeader'] = 'Report Ticket';
        $this->global['contentTitle'] = 'Report Ticket';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $data['url_list'] = base_url('front/creportcwh/get_list_view_datatable');
        $data['list_warehouse'] = $this->get_list_warehouse();

        $this->loadViews('front/report-cwh/index', $this->global, $data);
    }

    //Get list for datatable
    public function get_list_view_datatable(){
        $fdate1 = $this->input->post('fdate1', TRUE);
        $fdate2 = $this->input->post('fdate2', TRUE);
        $coverage = !empty($_POST['fcoverage']) ? implode(';',$_POST['fcoverage']) : "";


        if (strpos($coverage, 'C000') !== false) {
            $fcoverage = array();
        }else{
            if (strpos($coverage, ',') !== false) {
                $fcoverage = str_replace(',', ';', $coverage);
            }else{
                $fcoverage = $coverage;
            }
        }
        
        if(empty($fcoverage)){
            $e_coverage = array();
        }else{
            $e_coverage = explode(';', $fcoverage);
        }


        $par = array(
            'fdate1'=>$fdate1,
            'fdate2'=>$fdate2,            
        );
        $rs_data = send_curl($par, $this->config->item('api_list_report_cwh'), 'POST', FALSE);
        //var_dump($rs_data);
        $rs = $rs_data->status ? $rs_data->result : array();
        $data = array();
        foreach ($rs as $r) {
            $row = array(); 
            $row['ticket']          = filter_var($r->outgoing_ticket, FILTER_SANITIZE_STRING);
            $row['fe_report']       = filter_var($r->fe_report, FILTER_SANITIZE_STRING);
            $row['fsl_name']        = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $row['engineer_name']   = filter_var($r->engineer_name, FILTER_SANITIZE_STRING);
            $row['partner_name']    = filter_var($r->partner_name, FILTER_SANITIZE_STRING);
            $row['part_number']     = filter_var($r->part_number, FILTER_SANITIZE_STRING);
            $row['serial_number']   = filter_var($r->serial_number, FILTER_SANITIZE_STRING);
//            $transdate = filter_var($r->outgoing_date, FILTER_SANITIZE_STRING);
            if(!empty($e_coverage)){
                if(in_array($r->fsl_name, $e_coverage)){
                    $data[] = $row;
                }
            }else{
                $data[] = $row;
            }
            
        }
        return $this->output
        ->set_content_type('application/json')
        ->set_output(
            json_encode(array('data'=>$data))
        );    
    }

    private function get_list_warehouse(){
        $rs = array();
        $arrWhere = array();
        
        $fcoverage = $this->session->userdata ( 'ovCoverage' );
        if(empty($fcoverage)){
            $e_coverage = array();
        }else{
            $e_coverage = explode(';', $fcoverage);
        }
        
        $arrWhere = array('fdeleted'=>0, 'flimit'=>0);
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
            $data[] = $row;
           
        }
        
        return $data;
    }

}