<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CReportsStock extends BaseController{

    private $cname = 'creportsstock';
    private $view_dir = 'front/reportsstock/';
    private $readonly = TRUE;
    private $hasCoverage = FALSE;
    private $hasHub = FALSE;


    public function __construct(){
        parent::__construct();
        $this->isLoggedIn();
        if($this->isWebAdmin() || $this->isSpv() || $this->isStaff() || $this->isCtower()){
            if($this->isStaff()){
                $this->readonly = FALSE;
            }elseif($this->isSpv()|| $this->isCtower()){
                $this->readonly = TRUE;
                $this->hasHub = TRUE;
                $this->hasCoverage = TRUE;
            }else{
                $this->readonly = FALSE;
                $this->hasHub = TRUE;
            }
        }else{
            redirect('cl');
        }
    }

    public function index(){
        redirect('cl');
    }

    public function report_all_stock(){
        $this->global['pageTitle'] = 'List Stock - '.APP_NAME;
        $this->global['pageMenu'] = 'List Stock';
        $this->global['contentHeader'] = 'List Stock';
        $this->global['contentTitle'] = 'List Stock';
        $this->global ['role'] = $this->role;
        $this->global ['name'] = $this->name;
        $this->global ['repo'] = $this->repo;

        $data['readonly'] = $this->readonly;
        $data['hashub'] = $this->hasHub;
        $data['classname'] = $this->cname;
        if($this->hasHub){
            $data['list_warehouse'] = $this->get_list_warehouse('array');
        }
        $data['link_print'] = base_url('front/'.$this->cname.'/export_stock');
        $data['url_list'] = base_url($this->cname.'/onhand-list/json');
        $data['url_list_detail'] = base_url($this->cname.'/detail-list');
        $this->loadViews($this->view_dir.'index', $this->global, $data);
    }


    
    public function get_data(){
        
    }

    /**
     * This function is used to get detail information
     */
    private function get_warehouse_name($fcode){
        $rs = array();
        $arrWhere = array();        
        $arrWhere = array('fcode'=>$fcode);
        
        //Parse Data for cURL
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $wh_name = "";
        foreach ($rs as $r) {
            $wh_name = filter_var($r->fsl_name, FILTER_SANITIZE_STRING);
        }
        
        return $wh_name;
    }

    /**
     * This function is used to get lists fsl
     */
    public function get_list_warehouse($output = "json"){
        $rs = array();
        $arrWhere = array();
        
        $arrWhere = array('fdeleted'=>0, 'flimit'=>0);
        //Parse Data for cURL
        $arrWhere = array('fdeleted'=>0, 'flimit'=>0);
        $rs_data = send_curl($arrWhere, $this->config->item('api_list_warehouse'), 'POST', FALSE);
        $rs = $rs_data->status ? $rs_data->result : array();
        
        $data = array();
        foreach ($rs as $r) {
            $row['code'] = filter_var($r->fsl_code, FILTER_SANITIZE_STRING);
            $row['name'] = $this->common->nohtml($r->fsl_name);
 
            $data[] = $row;
        }
        
        switch ($output){
            case "json":
                return $this->output
                ->set_content_type('application/json')
                ->set_output(
                    json_encode($data)
                );
            break;
            case "array":
                return $data;
            break;
            default:
                return $this->output
                ->set_content_type('application/json')
                ->set_output(
                    json_encode($data)
                );
            break;
        }
    }

    public function export_stock(){
        $rs = array();
        $arrWhere = array();
        
        if($this->isStaff()){
            $code = array($this->repo);
        }else{
//            $code = $this->input->post('fcode', TRUE);
            $code = isset($_POST['fcode']) ? $_POST['fcode'] : array();
        }

        (int)$c_arr_code = count($code);

        $createdBy = $this->name;
        
        if(empty($code)){
            redirect('cl');
        }else{
            $curdateID = tgl_indo(date('Y-m-d'));
            $curdate = date('dmY');
            
            $title = 'Parts Stock '.$curdateID;
            
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            // Set document properties
            $spreadsheet->getProperties()->setCreator('E-Logistic')
            ->setLastModifiedBy($createdBy)
            ->setTitle($title)
            ->setSubject('Parts Stock')
            ->setDescription($title.' generated by '.$createdBy)
            ->setKeywords('parts stock')
            ->setCategory('Report');
            
//            $activeSheet = $spreadsheet->getActiveSheet();
            
            $styleHeaderArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E5E4E2' ),
                ),
            );
            
            //Start adding next sheets
            $x=0;
            foreach($code as $fcode) {
                $fslname = $this->get_warehouse_name($fcode);
            
                $spreadsheet->createSheet($x);
                $spreadsheet->setActiveSheetIndex($x);
                $activeSheet = $spreadsheet->getActiveSheet();
                
                $activeSheet->mergeCells('A1:K1');
                $activeSheet->mergeCells('A2:K2');
                $activeSheet
                ->setCellValue('A1', $fslname)
                ->setCellValue('A2', 'Report Date: '.$curdateID);
                $activeSheet->getStyle('A1')->getFont()->setBold(true);

                // Header data
                $activeSheet
                ->setCellValue('A4', 'FSL')
                ->setCellValue('B4', 'PART NUMBER')
                ->setCellValue('C4', 'PART NAME')
                ->setCellValue('D4', 'MIN STOCK')
                ->setCellValue('E4', 'ON HAND FSE')
                ->setCellValue('F4', 'LAST STOCK')
                ;
                $activeSheet->getStyle('A4:F4')->applyFromArray($styleHeaderArray);

                //Parameters for cURL
                //$arrWhere = array('fcode'=> strtoupper($fcode), 'fdate1'=> $fdate1.' 00:00:00', 'fdate2'=> $fdate2.' 23:59:59');
                //Parse Data for cURL
                //$rs_data = send_curl($arrWhere, $this->config->item('api_used_reports'), 'POST', FALSE);

                $arrWhere = array('fcode' => $fcode);
                $rs_data = send_curl($arrWhere, $this->config->item('api_list_parts_report'), 'POST', FALSE);
                $rs_data_on_hand  = send_curl($arrWhere, $this->config->item('api_list_on_hand'), 'POST', FALSE);
                $rs = $rs_data->status ? $rs_data->result : array();
                $rs_on_hand = $rs_data_on_hand->status ? $rs_data_on_hand->result : array();
                $data_on_hand = array();

                if($rs_on_hand > 0){
                    foreach( $rs_on_hand as $row_on_hand){
                        $data_on_hand[$row_on_hand->part_number] = $row_on_hand->dt_outgoing_qty;
                    }
                }

                $i=5; //initial row for displaying data output
                foreach( $rs as $row )
                {

                    $fsl        = filter_var($row->stock_fsl_code, FILTER_SANITIZE_STRING);
                    $partnum    = filter_var($row->stock_part_number, FILTER_SANITIZE_STRING);
                    $partname   = filter_var($row->part_name, FILTER_SANITIZE_STRING);
                    $minstock   = filter_var($row->stock_min_value, FILTER_SANITIZE_STRING);
                    $onhand     = (array_key_exists($partnum,$data_on_hand))?$data_on_hand[$partnum]:0;
                    $lastvalue  = filter_var($row->stock_last_value, FILTER_SANITIZE_STRING);
                    
                    //set column width auto size
                    $activeSheet->getColumnDimension('A')->setAutoSize(true);
                    $activeSheet->getColumnDimension('B')->setAutoSize(true);
                    $activeSheet->getColumnDimension('C')->setAutoSize(true);
                    $activeSheet->getColumnDimension('D')->setAutoSize(true);
                    $activeSheet->getColumnDimension('E')->setAutoSize(true);
                    $activeSheet->getColumnDimension('F')->setAutoSize(true);
                   
                    $activeSheet->getStyle('B')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                    $activeSheet->getStyle('C')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                    //$activeSheet->getStyle('H')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                    $activeSheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $activeSheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $activeSheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    //$activeSheet->getStyle('I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    //fill data row
                    $activeSheet
                    ->setCellValue('A'.$i, $fsl)
                    ->setCellValue('B'.$i, $partnum)
                    ->setCellValue('C'.$i, $partname)
                    ->setCellValue('D'.$i, $minstock)
                    ->setCellValue('E'.$i, $onhand)
                    ->setCellValue('F'.$i, $lastvalue)
                    ;

                    $i++;
                }
    //            $activeSheet->getHeaderFooter()
    //                    ->setOddHeader('&L&B' . 'Report Date: '.$reportdateID.' to '.$reportdateID2);
                $activeSheet->getHeaderFooter()
                        ->setOddFooter('&L&B' . 'Generated by '.$createdBy . '&RPage &P of &N');
                // Rename worksheet
                $activeSheet->setTitle($fcode);
                $x++;
            }

            ob_start();
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->setOffice2003Compatibility(true);
            // $writer->save('php://output');

            $this->load->library('zip');

//            $path = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']); 
            $path = str_replace('\\', '/', FCPATH);
            $path .= '/tmp/'; // destination dir
            $file_name = $title.'.xlsx'; // destination file
            if (file_exists($path.$file_name)) {
                unlink($path.$file_name);
            } else {
               echo "The file filename does not exist";
            }
            $writer->save($path.$file_name);
			
            $this->zip->read_file($path.$file_name,FALSE);
            ob_end_clean();

            // prompt user to download the zip file
            $this->zip->download($title.'.zip');
            exit;
        }
    }
}