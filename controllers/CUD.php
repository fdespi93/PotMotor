<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 class CUD extends MY_Controller {
//   function __construct(){
//       parent::__construct();
//       //load our second db and put in $db2
//       $this->db_bio = $this->load->database('bio', TRUE);
//   }

	//public $layout_view = 'layout/default';

	function __construct() {
    parent::__construct();
    // date_default_timezone_set('Asia/Manila');
    // if ( ! $this->session->userdata('store_logged_in'))
    //  { 
    //  	$this->session->set_flashdata('failed', 'Oops! You need to login.');
    //      redirect('login');
    //  }
   }


  //list
	public function index()
	{
    if(!$this->isadmin())
    $useridloggedin = $this->session->userdata['store_logged_in']['employeeno'];
    else
    $useridloggedin = '';
    //   $this->data['jobdetail'] = $this->cud_model->getAllJobDetail();
		  // $this->layout->view('content/home', $this->data);	
	}
  
  public function isadmin()
  {
    if($this->session->userdata['store_logged_in']['role'] == 'SUPERADMIN')
      return true;
    else
      return false;
  }

  public function saveProduct()
  {  
      $data = $this->input->post('productdata');

      $datatrans = array('Descr' => $data['Descr'], 
                         'Descr2' => $data['Descr2'],
                         'Type' => $data['Type'],
                         'Category' => $data['Category'],
                         'Brand' => $data['Brand'],
                         'Model' => $data['Model'],
                         'SellingPrice' => $data['SellingPrice'],
                         'LastPrice' => $data['LastPrice'],
                         'LessPercentage' => $data['LessPercentage'],
                         'Sold' => $data['Sold'],
                         'Quantity' => $data['Quantity'],
                         'Unit' => $data['Unit'],
                         'ReorderQtyPoint' => $data['ReorderQtyPoint'],
                         'StandardSalesQty' => $data['StandardSalesQty'],
                         'Remarks' => $data['Remarks'],
                         'modified_by' => $this->session->userdata['store_logged_in']['username'],
                         'modified_date' => date("Y-m-d H:i:s"));    

      if($data['ProductID']){ //means update
          $cond = "ProductID = '".$data['ProductID']."'";
          if($this->cud_model->updateDB('Product',$datatrans,$cond)){ 
            echo json_encode(true); 
          }
          else{
            echo json_encode(false); 
          }
      }
      else{
          $datatrans['created_by'] = $this->session->userdata['store_logged_in']['username'];
          $lastid = $this->cud_model->insertDB('Product',$datatrans,true);

          if($this->cud_model->insertToProductReceived($lastid)){ 
            echo json_encode(true); 
          }
          else{
            echo json_encode(false); 
          }
      }
  }

  public function saveReceivedProduct()
  {  
      $code = $this->input->post('id');

      $datatrans = array('ProductID' => $this->input->post('prodid'), 
                         'CurrentPrice' => $this->input->post('curprice'),
                         'OrderPrice' => $this->input->post('orderprice'),
                         'NewPrice' => $this->input->post('newprice'),
                         'AveragePrice' => $this->input->post('aveprice'),
                         'AverageLastPrice' => $this->input->post('avelastprice'),
                         'Quantity' => $this->input->post('qty'),
                         'Supplier' => $this->input->post('supplier'),
                         'DeliveredBy' => $this->input->post('delby'),
                         'DeliveryDate' => $this->input->post('deldate'),
                         'Remarks' => $this->input->post('remarks'),
                         'modified_by' => $this->session->userdata['store_logged_in']['username'],
                         'modified_date' => date("Y-m-d H:i:s"));    

      if($code){ //means update
          $cond = "SeriesNo = '".$code."'";
          if($this->cud_model->updateDB('ProductReceived',$datatrans,$cond)){ 
            echo json_encode(true); 
          }
          else{
            echo json_encode(false); 
          }
      }
      else{
          $datatrans['created_by'] = $this->session->userdata['store_logged_in']['username'];
          $lastid = $this->cud_model->insertDB('ProductReceived',$datatrans,true);

          if($this->cud_model->updateProductQty($lastid)){ 
            echo json_encode(true); 
          }
          else{
            echo json_encode(false); 
          }
      }
  }

  public function saveCodeReference()
  {  
      $code = $this->input->post('id');
      $crd = array('Descr' => $this->input->post('desc'),
                   'modified_by' => $this->session->userdata['store_logged_in']['username'],
                   'modified_date' => date("Y-m-d H:i:s"),
                   'IsDeleted' => 0);
      
      if($code){
        $cond = array('ParentCode' => $this->input->post('parentcode'), 'Code' => $code);
        if($this->cud_model->updateDB('CodeReferenceDetail',$crd, $cond)){         
          echo json_encode(true);  
        }
        else{
          echo json_encode(false);
        }
      }
      else{
        $crd['created_by'] = $this->session->userdata['store_logged_in']['username'];
        if($this->cud_model->insertDB_CodeReference($crd,$this->input->post('parentcode'),false)){ 
          echo json_encode(true); 
        }
        else{
          echo json_encode(false);
        }
      }

  }

  public function saveOrder()
  {  
      $code = $this->input->post('id');
      $itemdata = $this->input->post('items');

      $datatrans = array('TotalAmount' => $this->input->post('totalamount'), 
                         'TotalLessAmount' => $this->input->post('totalesslamount'),
                         'TotalLastAmount' => $this->input->post('totalastlamount'),
                         'AmountTendered' => $this->input->post('tendered'),
                         'ServicesAmount' => $this->input->post('serviceamount'),
                         'CustomerType' => $this->input->post('custtype'),
                         'CustomerID' => $this->input->post('custid'),
                         'Discount' => $this->input->post('discount'),
                         'Remarks' => $this->input->post('remarks'),
                         'modified_by' => $this->session->userdata['store_logged_in']['username'],
                         'modified_date' => date("Y-m-d H:i:s"));    

      if($code){ //means update
        // var_dump('update');
          $cond = "SeriesNo = '".$code."'";
          if($this->cud_model->updateDB('SalesTrasaction',$datatrans,$cond)){ 
            echo json_encode(true); 
          }
          else{
            echo json_encode(false); 
          }
      }
      else{
        // var_dump('insert');
          $datatrans['created_by'] = $this->session->userdata['store_logged_in']['username'];
          $lastid = $this->cud_model->insertDB('SalesTrasaction',$datatrans,true);

          if($lastid){ 
            //// ordered items ////
            for($i=0;$i<count($itemdata);$i++){
                $vall = $itemdata[$i];
                $datatrans = array('TransID' => $lastid,
                                   'ProductID' => $vall['ProductID'],
                                   'OrderPrice' => $vall['LastOrderPrice'],
                                   'SellingPrice' => $vall['SellingPrice'],
                                   'LastPrice' => $vall['LastPrice'],
                                   'SalePrice' => $vall['SalePrice'],
                                   'Quantity' => $vall['StandardSalesQty'],
                                   'TotalAmount' => $vall['TotalSalePrice'],
                                   'Discount' => $vall['LessPercentage'],
                                   'modified_by' => $this->session->userdata['store_logged_in']['username'],
                                   'modified_date' => date("Y-m-d H:i:s"));

                  $datatrans['created_by'] = $this->session->userdata['store_logged_in']['username'];
                  $this->cud_model->insertDB('SalesTransactionDetail', $datatrans); 
            }   
            $this->cud_model->updateProduct_SoldQty($lastid);  
            //// end ordered items //// 
            echo json_encode(true); 
          }
          else{
          echo json_encode(false); 
          }
      }
        
  }

  public function saveUserAccess()
  {  
      $userid = $this->input->post('id');
      $selctdmodule = $this->input->post('module');

      $userdet = array('role' => $this->input->post('role')
                       ,'EmployeeNo' => $this->input->post('empno')
                       ,'modified_by' => $this->session->userdata['logged_in']['username']
                       ,'modified_date' => date("Y-m-d H:i:s"));

      if($userid){ 
        if($this->cud_model->updateDB('UserMaster',$userdet, "user_name = '".$userid."'")){  
          if($this->cud_model->deleteModuleAccess($userid)){  
            for($i=0;$i<count($selctdmodule);$i++){
                $vall = $selctdmodule[$i];
                $datatrans = array('UserID' => $userid, 
                                   'ModuleID' => $vall['Code']);
                $this->cud_model->insertDB('StoreModuleRights', $datatrans); 
            }   
            echo json_encode(true); 
          }
          else{
            echo json_encode(false); 
          }
        }        
      }
      else{
        $datatrans['created_by'] = $this->session->userdata['store_logged_in']['username'];
        $lastid = $this->cud_model->insertDB('UserMaster',$userdet,true);

        if($this->cud_model->updateProductQty($lastid)){ 
          echo json_encode(true); 
        }
        else{
          echo json_encode(false); 
        }
      }
        
  }


  public function deleteDB_CodeReference()
  {
    $cond = array('ParentCode' => $this->input->post('parentcode'),'Code' => $this->input->post('id'));
    if($this->cud_model->deleteDB('CodeReferenceDetail',$cond))
    {
      echo true;
    }
    else
      echo false;
  }












//   public function uploadDTR()
//   {
//     $query = "Execute usp_exportDTR_range '".$this->input->post('datefrom')."','".$this->input->post('dateto')."'";
    
//     if($this->cud_model->ExecuteQuery_BIO($query))
//       echo json_encode(true);
//     else
//       echo json_encode(false);
//   }


//   public function uploadDTRecord()
//   {  
//       $data = array('EmployeeNo' => $this->input->post('empno')
//                    ,'TransType' => $this->input->post('transtype')
//                    ,'TransDate' => $this->input->post('transdate')
//                    ,'TransTime' => $this->input->post('transtime')
//                    ,'TransType02' => $this->input->post('transtype'));

//       if($this->cud_model->insertDB_GP('DailyTrans', $data)){ 
//         echo json_encode(true); 
//       }
//       else{
//         echo json_encode(false);
//       }
//   }

//   public function uploadDTRecords()
//   {  

//       $notexists = $this->input->post('list');
//       $errorcount = 0;

//       for($i=0;$i<count($notexists);$i++){
//         $vall = $notexists[$i];
//         // var_dump($vall);
//         $transtype = '';
//         $thisdate = date_create($vall['checktime']);

//         if($vall['checktype']=='O'){
//           $transtype = '1';
//         }
//         else{
//           $transtype = '0';
//         }

//         $datatrans = array('EmployeeNo' => $vall['emplono']
//                            ,'TransType' => $transtype
//                            ,'TransDate' => date_format($thisdate,"Y-m-d")
//                            ,'TransTime' => $vall['checktime']
//                            ,'TransType02' => $transtype);

//         if(!$this->cud_model->insertDB_GP('DailyTrans', $datatrans)){
//           $errorcount++;
//         }
//       } 
//       echo json_encode($errorcount); 
//   }


//   public function saveLeaveApplication()
//   {  
//       $code = $this->input->post('id');

//       $flagxmon = 0;      if($this->input->post('flagxmon')=='true'){$flagxmon=1;}
//       $flagxtue = 0;      if($this->input->post('flagxtue')=='true'){$flagxtue=1;}
//       $flagxwed = 0;      if($this->input->post('flagxwed')=='true'){$flagxwed=1;}
//       $flagxthu = 0;      if($this->input->post('flagxthu')=='true'){$flagxthu=1;}
//       $flagxfri = 0;      if($this->input->post('flagxfri')=='true'){$flagxfri=1;}
//       $flagxsat = 0;      if($this->input->post('flagxsat')=='true'){$flagxsat=1;}
//       $flagxsun = 0;      if($this->input->post('flagxsun')=='true'){$flagxsun=1;}
//       $flagxhol = 0;      if($this->input->post('flagxhol')=='true'){$flagxhol=1;}
//       $flagxhalf = 0;     if($this->input->post('flagxhalf')=='true'){$flagxhalf=1;}
//       $flagxapplied = 0;  if($this->input->post('flagxapplied')=='true'){$flagxapplied=1;}
//       $flagantidate = 0;  if($this->input->post('flagantidate')=='true'){$flagantidate=1;}

//       $data = array('EmployeeNo' => $this->input->post('emplono')
//                    ,'agency' => $this->input->post('agency')
//                    ,'salary' => $this->input->post('salasary')
//                    ,'position' => $this->input->post('position')
//                    ,'date_filed' => $this->input->post('datefiled')
//                    ,'leave_type' => $this->input->post('leavetype')
//                    ,'applied' => $this->input->post('applied')
//                    ,'withpay_value' => $this->input->post('wpvalue')
//                    ,'withoutpay_value' => $this->input->post('wopvalue')
//                    ,'sl_balance' => $this->input->post('slbal')
//                    ,'vl_balance' => $this->input->post('vlbal')
//                    ,'new_sl_balance' => $this->input->post('newslbal')
//                    ,'new_vl_balance' => $this->input->post('newvlbal')
//                    ,'balance_as_of' => $this->input->post('balasof')
//                    ,'sick_leave' => $this->input->post('rbsl')
//                    ,'vacation_leave' => $this->input->post('rbvl')
//                    ,'dest' => $this->input->post('rbdest')
//                    ,'commutation' => $this->input->post('rbcomm')
//                    ,'sick_remarks' => $this->input->post('remarksl')
//                    ,'vacation_remarks' => $this->input->post('remarkvl')
//                    ,'dest_remarks' => $this->input->post('remarkdest')
//                    ,'leave_status' => $this->input->post('leavestat')
//                    ,'remarks' => $this->input->post('remarks')
//                    ,'date_period' => $this->input->post('period')
//                    ,'ExcludeMon' => $flagxmon
//                    ,'ExcludeTue' => $flagxtue
//                    ,'ExcludeWed' => $flagxwed
//                    ,'ExcludeThu' => $flagxthu
//                    ,'ExcludeFri' => $flagxfri
//                    ,'ExcludeSat' => $flagxsat
//                    ,'ExcludeSun' => $flagxsun
//                    ,'ExcludeHol' => $flagxhol
//                    ,'ExcludeHalf' => $flagxhalf
//                    ,'ExcludeLeaveApp' => $flagxapplied
//                    ,'is_antidate' => $flagantidate
//                    ,'modified_by' => $this->session->userdata['logged_in']['username']
//                    ,'modified_date' => date("Y-m-d H:i:s"));

//       $typeslctd = $this->input->post('typeslctd');
//       $deductedto = $this->input->post('deductedto');
//       $adjustingentry = $this->input->post('aentry');
//       $leavedates = $this->input->post('incdates');  
//       $fileattach = $this->input->post('fileattach');     

//       if($code){
//           $cond = "leave_id = '".$code."'";
//           if($this->cud_model->updateDB_LMS('DETAIL_LEAVE1',$data, $cond)){ 
//               $sampecho=true;
//               if($this->input->post('allowedit')=='true'&&$this->input->post('leavestat')!='cancelled'&&$this->input->post('leavestat')!='disapproved'){
//                   if($this->input->post('leavetype')=='MLC-22'||$this->input->post('leavetype')=='MLC-23'){
//                       $monettrans = array('vl_applied' => $this->input->post('monet_vl_applied'),
//                                          'vl_balance' => $this->input->post('monet_vl_bal'),
//                                          'vl_max' => $this->input->post('monet_vl_max'),
//                                          'sl_applied' => $this->input->post('monet_sl_applied'),
//                                          'sl_balance' => $this->input->post('monet_sl_bal'),
//                                          'sl_max' => $this->input->post('monet_sl_max'),
//                                          'vlused' => $this->input->post('monet_vlused'));
//                       $this->cud_model->updateDB_LMS('Monetization_Detail',$monettrans, "leave_id = '".$code."'");
//                       $sampecho=true;
//                   }
//                   else{
//                     if($this->cud_model->LeaveDetail($code,$typeslctd,$adjustingentry,$leavedates,$this->input->post('emplono'),true)){
//                         $sampecho=true;
//                     }
//                     else{
//                         $sampecho=false;
//                     }
//                   }
//               }
//               // $this->cud_model->saveAttachment($code,$fileattach)
//               echo json_encode($sampecho); 
//           }
//           else{
//             echo json_encode(false); //echo json_encode(true); 
//           }
//       }
//       else
//       {
//           $data['prepared_by'] = $this->session->userdata['logged_in']['username'];
//           $data['created_by'] = $this->session->userdata['logged_in']['username'];
//           $data['IsDeleted'] = 0;
//           $lastid = $this->cud_model->insertLeave($data);
          
//           if($lastid!='false'){ 
//               if($this->input->post('leavetype')=='MLC-22'||$this->input->post('leavetype')=='MLC-23'){
//                   $monettrans = array('leave_id' => $lastid, 
//                                      'vl_applied' => $this->input->post('monet_vl_applied'),
//                                      'vl_balance' => $this->input->post('monet_vl_bal'),
//                                      'vl_max' => $this->input->post('monet_vl_max'),
//                                      'sl_applied' => $this->input->post('monet_sl_applied'),
//                                      'sl_balance' => $this->input->post('monet_sl_bal'),
//                                      'sl_max' => $this->input->post('monet_sl_max'));
//                   $this->cud_model->insertDB_LMS('Monetization_Detail', $monettrans); 
//                   $query = "Execute usp_ChangeLeaveStatus '" .  $lastid . "', 'Pending','Web Application','" . $this->session->userdata['logged_in']['username'] ."'";
//                   $this->cud_model->ExecuteQuery_LMS($query);
//                   echo json_encode($lastid);
//               }
//               else{
//                   if($this->cud_model->LeaveDetail($lastid,$typeslctd,$adjustingentry,$leavedates,$this->input->post('emplono'))){
//                       $query = "Execute usp_ChangeLeaveStatus '" .  $lastid . "', 'Pending','Web Application','" . $this->session->userdata['logged_in']['username'] ."'";
//                       $this->cud_model->ExecuteQuery_LMS($query);
//                       echo json_encode($lastid);
//                   }
//                   else{
//                       if($this->cud_model->ExecuteQuery_LMS("UPDATE DETAIL_LEAVE1 set leave_status = 'Cancelled' WHERE leave_id = '".$lastid."'")){
//                           echo json_encode($lastid);
//                       }
//                       else{
//                           echo json_encode(false); 
//                       }
//                   }
//               }
//           }
//           else{
//             echo json_encode(false); 
//           }
//       }

//   }

//   public function saveLeaveAttachment()
//   {  
//       $leaveid = $this->input->post('leaveid');
//       $fileattach = $this->input->post('fileattach');     

//       if($leaveid){
//           if($this->cud_model->saveAttachment($leaveid,$fileattach)){ 
//             echo json_encode(true); 
//           }
//           else{
//             echo json_encode(false); 
//           }
//       }
//       else{
//           echo json_encode(false); 
//       }

//   }

//   public function changeleavestatus()
//   {  
//     $id = $this->input->post('id');
//     $stat = $this->input->post('stat');
//     $reason = $this->input->post('reason');

//     $query = "Execute usp_ChangeLeaveStatus '".$id."','".$stat."','Web Application','".$this->session->userdata['logged_in']['username']."',1,'".$reason."'";
//     // $data;
//     // var_dump($this->cud_model->ExecuteQuery_LMS_RetList($query));
//     $data = $this->cud_model->ExecuteQuery_LMS_RetList($query);
//     // echo json_encode($this->cud_model->ExecuteQuery_LMS_RetList($query));
//     // if($data=$this->cud_model->ExecuteQuery_LMS_RetList($query)){
//       if(empty($data)){
//         $result['msg'] = 'Successfull';
//         $result['return'] = true;
//         echo json_encode($result);
//       }
//       else{
//         // var_dump($data);
//         $result['msg'] = $data[0]->result;
//         // var_dump($result['msg']);
//         $result['return'] = false;
//         echo json_encode($result);
//       }
//     // }
//     // else{
//     //     $result['msg'] = 'Error';
//     //     $result['return'] = false;
//     //     echo json_encode($result);
//     //   // echo json_encode(true);
//     // }
//   }

//   public function changeleaveAppliedValue()
//   {  
//     $row = $this->input->post('row');
//     $query = "Execute usp_ChangeLeaveAppliedValue '".$row['leavecode']."'
//                                                   ,".$this->input->post('wpval')."
//                                                   ,".$this->input->post('wopval')."
//                                                   ,'".$this->input->post('remarks')."'
//                                                   ,'".$this->session->userdata['logged_in']['username']."'";

//     $data = $this->cud_model->ExecuteQuery_LMS_RetList($query);

//       if(empty($data)){
//         $result['msg'] = 'Successfull';
//         $result['return'] = true;
//         echo json_encode($result);
//       }
//       else{
//         $result['msg'] = $row['leavecode'];
//         $result['return'] = false;
//         echo json_encode($result);
//       }
//   }

//   public function changeLeaveSignature()
//   {  
//     $row = $this->input->post('row');
//     $query = "Execute usp_ChangeLeaveSignatory '".$row['leavecode']."'
//                                                   ,".$this->input->post('recommsig')."
//                                                   ,".$this->input->post('appsig')."
//                                                   ,'".$this->session->userdata['logged_in']['username']."'";

//     $data = $this->cud_model->ExecuteQuery_LMS_RetList($query);

//       if(empty($data)){
//         $result['msg'] = 'Successfull';
//         $result['return'] = true;
//         echo json_encode($result);
//       }
//       else{
//         $result['msg'] = $row['leavecode'];
//         $result['return'] = false;
//         echo json_encode($result);
//       }
//   }

//   public function portaluseraccount()
//   {  
//       $isnew = $this->input->post('isnew');
//       $userid = $this->input->post('userid');

//       $genpas = $this->login_model->generate_pass($this->input->post('pass'));
//       $pass = $genpas['pass'];
//       $salt = $genpas['salt'];

//       $hrisacc = array('employeeno' => $this->input->post('empno')
//                        ,'salt' => $salt
//                        ,'password' => $pass
//                        ,'web_password' => $pass
//                        ,'role' => $this->input->post('role')
//                        ,'modified_by' => $this->session->userdata['logged_in']['username']
//                        ,'modified_date' => date("Y-m-d H:i:s"));

//       $lmsacc = array('employeeno' => $this->input->post('empno')
//                        ,'spec_group' => $this->input->post('lmsrole')
//                        ,'usersname' => $this->input->post('empname')
//                        ,'password' => $pass
//                        ,'user_group' => $this->input->post('lmsgroup')
//                        ,'modified_by' => $this->session->userdata['logged_in']['username']
//                        ,'modified_date' => date("Y-m-d H:i:s"));

//       $empacc = array('CellNo' => $this->input->post('mobile')
//                        ,'LMSsignature' => $this->input->post('empno').".png");

//       if($isnew=='0')
//       {
//         if($this->cud_model->updateDB('UserMaster',$hrisacc, "user_name='".$userid."'"))
//         {         
//           echo json_encode(true); 
//         }
//         else
//           echo json_encode(false);
//       }
//       else
//       {
//         $hrisacc['user_name'] = $userid;
//         $lmsacc['user_code'] = $userid;
//         $retdata = $this->cud_model->SaveUserAccount($userid,$hrisacc,$lmsacc,$empacc);

//         echo json_encode($retdata);
        
//       }

//   }

//   public function verifySignature()
//   {  
//       $empno = $this->input->post('empno');
//       $empdata = array('LMSsignature' => $empno.".png",
//                        'CellNo' => $this->input->post('mobile'));

//       if($this->cud_model->updateDB("Employees",$empdata,"EmployeeNo='".$empno."'")){
//         echo json_encode(true);
//       }
//       else{
//         echo json_encode(false);
//       }
        
//   }

//   public function saveUserAccess()
//   {  
//       $userid = $this->input->post('id');
//       $selctdview = $this->input->post('view');
//       $selctdchange = $this->input->post('change');
//       $selctdcancel = $this->input->post('cancel');
//       $selctdmodule = $this->input->post('module');
//       $selctdoffice = $this->input->post('underoffice');

//       $modifyAE = 0;         if($this->input->post('flag_modifyAE')=='true'){$modifyAE=1;}
//       $modifyfiling = 0;     if($this->input->post('flag_modifyfiling')=='true'){$modifyfiling=1;}
//       $canantidate = 0;      if($this->input->post('flag_canantidate')=='true'){$canantidate=1;}
//       $statchanger = 0;      if($this->input->post('flag_statchanger')=='true'){$statchanger=1;}
//       $pdsadmin = 0;      if($this->input->post('flag_pdsadmin')=='true'){$pdsadmin=1;}

//       $userdet = array('spec_group' => $this->input->post('group')
//                        ,'EmployeeNo' => $this->input->post('empno')
//                        ,'usersname' => $this->input->post('name')
//                        ,'user_group' => $this->input->post('office')
//                        ,'can_modify_AE' => $this->input->post('flag_modifyAE')
//                        ,'CanModifyFiling' => $this->input->post('flag_modifyfiling')
//                        ,'CanAntiDate' => $this->input->post('flag_canantidate')
//                        ,'CanModifyValue' => $this->input->post('flag_canmodifyvalue')
//                        ,'statchanger' => $this->input->post('flag_statchanger')
//                        ,'modified_by' => $this->session->userdata['logged_in']['username']
//                        ,'modified_date' => date("Y-m-d H:i:s"));

//       $hrisdata = array('pdsAdmin' => $this->input->post('flag_pdsadmin'));


//       if($userid){ 
//         if($this->cud_model->updateDB_LMS('UserMaster',$userdet, "user_code = '".$userid."'")){   
//           $this->cud_model->updateDB('UserMaster',$hrisdata, "user_name = '".$userid."'");
//           if($this->cud_model->deleteUserAccess($userid)){
//             for($i=0;$i<count($selctdview);$i++){
//                 $vall = $selctdview[$i];
//                 $datatrans = array('UserID' => $userid, 
//                                    'Module' => 'viewStat',
//                                    'AccessName' => $vall['Code'],
//                                    'created_by' => $this->session->userdata['logged_in']['username']);
//                 $this->cud_model->insertDB_LMS('UserAccessDetail', $datatrans); 
//             }   
//             for($i=0;$i<count($selctdchange);$i++){
//                 $vall = $selctdchange[$i];
//                 $datatrans = array('UserID' => $userid, 
//                                    'Module' => 'Stat',
//                                    'AccessName' => $vall['Code'],
//                                    'created_by' => $this->session->userdata['logged_in']['username']);
//                 $this->cud_model->insertDB_LMS('UserAccessDetail', $datatrans); 
//             }   
//             for($i=0;$i<count($selctdcancel);$i++){
//                 $vall = $selctdcancel[$i];
//                 $datatrans = array('UserID' => $userid, 
//                                    'Module' => 'cancelStat',
//                                    'AccessName' => $vall['Code'],
//                                    'created_by' => $this->session->userdata['logged_in']['username']);
//                 $this->cud_model->insertDB_LMS('UserAccessDetail', $datatrans); 
//             }   
//             for($i=0;$i<count($selctdoffice);$i++){
//                 $vall = $selctdoffice[$i];
//                 $datatrans = array('UserID' => $userid, 
//                                    'Module' => 'underOffice',
//                                    'AccessName' => $vall['Code'],
//                                    'created_by' => $this->session->userdata['logged_in']['username']);
//                 $this->cud_model->insertDB_LMS('UserAccessDetail', $datatrans); 
//             }     
//           }

//           if($this->cud_model->deleteModuleAccess($this->input->post('empno'))){  
//             for($i=0;$i<count($selctdmodule);$i++){
//                 $vall = $selctdmodule[$i];
//                 $datatrans = array('EmployeeNo' => $this->input->post('empno'), 
//                                    'ACCESSSytemID' => $vall['Code']);
//                 $this->cud_model->insertDB('ACCESSSystemsRights', $datatrans); 
//             }   
//           }
//           echo json_encode(true); 
//         }        
//       }
//       else{
//           echo json_encode(false); 
//       }
        
//   }

//   public function saveUserNotif()
//   {  
//       $userid = $this->input->post('id');
//       $selctdtxt = $this->input->post('txt');
//       $selctdnotif = $this->input->post('notif');

//       if($userid){  
//           if($this->cud_model->deleteUserNotif($userid)){
//             for($i=0;$i<count($selctdtxt);$i++){
//                 $vall = $selctdtxt[$i];
//                 $datatrans = array('UserID' => $userid, 
//                                    'Module' => 'txt',
//                                    'AccessName' => $vall['Code'],
//                                    'created_by' => $this->session->userdata['logged_in']['username']);
//                 $this->cud_model->insertDB_LMS('UserAccessDetail', $datatrans); 
//             }   
//             for($i=0;$i<count($selctdnotif);$i++){
//                 $vall = $selctdnotif[$i];
//                 $datatrans = array('UserID' => $userid, 
//                                    'Module' => 'notif',
//                                    'AccessName' => $vall['Code'],
//                                    'created_by' => $this->session->userdata['logged_in']['username']);
//                 $this->cud_model->insertDB_LMS('UserAccessDetail', $datatrans); 
//             }         
//           }
//           echo json_encode(true);      
//       }
//       else{
//           echo json_encode(false); 
//       }
        
//   }

//   public function test_upload()
//   {
//       $data['data_array'] = explode(',',$_POST['leave_code']);
//        foreach($data AS $key =>$values)
//        {  
//            $leave_code = $values[0];
        
           
//        }

//       $file        = $_FILES["file_to_upload"]["name"];
//       $size        = $_FILES["file_to_upload"]["size"];
//       $imgExt      = strtolower(pathinfo($file,PATHINFO_EXTENSION)); 
//       $target_file = $file;
//       $new_file    = strtolower(pathinfo($file, PATHINFO_FILENAME));
//       $structure   = 'D:/';
  
//       if(!file_exists($structure)){
//         mkdir($structure, 0777, true);
//       }

//       // var_dump($_FILES["file_to_upload"]["tmp_name"]);
//       // var_dump($structure.'/'.$new_file.'.'.$imgExt);
  
//       move_uploaded_file($_FILES["file_to_upload"]["tmp_name"], $structure.'/'.$new_file.'.'.$imgExt);

//   }

//   public function saveattach()
//   {
// //upload file 
//     $leaveid = $this->input->post('leaveid');
//     $fileattach = $this->input->post('fileattach');
//     $key = $this->input->post('key');

//     $structure = 'uploads/lms/'.$leaveid.'/';
//     if(!file_exists($structure)){
//         mkdir($structure, 0777, true);
//     }

//     $config['upload_path'] = $structure;
//     $config['allowed_types'] = 'xlsx|doc|docx|pdf|jpg|jpeg|png';
//     $config['max_filename'] = '255';

//     $path = $_FILES['file']['name'];
//     $ext = pathinfo($path, PATHINFO_EXTENSION);
//     $new_name = 'leaveattach'.time().$key.'.'.$ext;
//     $config['file_name'] = $new_name;
//     //$config['encrypt_name'] = FALSE;
//     $config['overwrite'] = FALSE;
//     $config['max_size'] = '101200000'; //5GB
//     // $config['remove_spaces'] = FALSE;

//     if (isset($_FILES['file']['name'])) {
//         if (0 < $_FILES['file']['error']) {
//             $response['msg'] = 'Error during file upload' . $_FILES['file']['error'];
//             $response['result'] = false;
//         } 
//         else {
//             $this->load->library('upload', $config);
//             if (!$this->upload->do_upload('file')) {
//                 $response['msg'] = $this->upload->display_errors();
//                 $response['result'] = false;
//             } else {
//                 $response['msg'] = $new_name;
//                 $response['result'] = true;
//             } 
//         }
//     } else {
//         $response['msg'] = 'Please choose a file';
//         $response['result'] = false;
//     }

//     echo json_encode($response);
//   }

//   public function saveLeaveType()
//   {  
//       $type = $this->input->post('type');
//       $ltype = $this->input->post('ltype'); 

//       $datatrans = array('leave_desc' => $ltype['leave_desc'], 
//                          'rules' => $ltype['rules'],
//                          'AttachRemarks' => $ltype['AttachRemarks'],
//                          'modified_by' => $this->session->userdata['logged_in']['username'],
//                          'modified_date' => date("Y-m-d H:i:s"));    

//       $cond = "leave_code = '".$ltype['leave_code']."'";

//       if($type=='1'){ //means update
//           if($this->cud_model->updateDB_LMS('Leave_Type',$datatrans,$cond)){ 
//             echo json_encode(true); 
//           }
//           else{
//             echo json_encode(false); 
//           }
//       }
//       else{
//           echo json_encode(false); 
//       }
//   }

//   public function saveSignatory()
//   {  
//       $datasig = $this->input->post('signatory');
//       $code = $datasig['id'];

//       $datatrans = array('Name' => $datasig['signame'], 
//                          'Designation' => $datasig['sigposition'],
//                          'Signature' => $datasig['signature'],
//                          'modified_by' => $this->session->userdata['logged_in']['username'],
//                          'modified_date' => date("Y-m-d H:i:s"));    

//       $cond = "SeriesNo = '".$datasig['id']."'";

//       if($code!='0'){ //means update
//           if($this->cud_model->updateDB_LMS('Signatory',$datatrans,$cond)){ 
//             echo json_encode(true); 
//           }
//           else{
//             echo json_encode(false); 
//           }
//       }
//       else{
//           $data['created_by'] = $this->session->userdata['logged_in']['username'];
//           if($this->cud_model->insertDB_LMS('Signatory',$datatrans)){ 
//             echo json_encode(true); 
//           }
//           else{
//           echo json_encode(false); 
//           }
//       }

//   }

//   public function savePDS()
//   {  
//       $empdata = $this->input->post('empdata');
//       // $educdata = $this->input->post('educ');
//       // $workxp = $this->input->post('workxp');
//       // $eligdata = $this->input->post('elig');
//       // $famdata = $this->input->post('fam');
//       // $refdata = $this->input->post('ref');
//       // $orgdata = $this->input->post('org');
//       // $traindata = $this->input->post('train');
//       // $skilldata = $this->input->post('skill');
//       // $recogdata = $this->input->post('recog');
//       $otherdata = $this->input->post('other');
      
//       $empdata['DateHired'] = ($empdata['DateHired']=='' ? Null : $empdata['DateHired']);
//       $empdata['DateOfAppointment'] = ($empdata['DateOfAppointment']=='' ? Null : $empdata['DateOfAppointment']);
//       $empdata['DateFinish'] = ($empdata['DateFinish']=='' ? Null : $empdata['DateFinish']);
//       $empdata['DateOfClearance'] = ($empdata['DateOfClearance']=='' ? Null : $empdata['DateOfClearance']);
//       $empdata['Birthday'] = ($empdata['Birthday']=='' ? Null : $empdata['Birthday']);
//       $empdata['Retirement'] = ($empdata['Retirement']=='' ? Null : $empdata['Retirement']);
//       $empdata['DateOfLastPromotion'] = ($empdata['DateOfLastPromotion']=='' ? Null : $empdata['DateOfLastPromotion']);

//       $data = array('LastName' => $empdata['LastName']
//                    ,'FirstName' => $empdata['FirstName']
//                    ,'MiddleName' => $empdata['MiddleName']
//                    ,'MiddleInitial' => $empdata['MiddleInitial']
//                    ,'EmploymentStatusCode' => $empdata['EmploymentStatusCode']
//                    ,'SalaryFundingCode' => $empdata['SalaryFundingCode']
//                    ,'AppointmentStatusCode' => $empdata['AppointmentStatusCode']
//                    ,'DateHired' => $empdata['DateHired']
//                    ,'DateOfAppointment' => $empdata['DateOfAppointment']
//                    ,'DateFinish' => $empdata['DateFinish']
//                    ,'DateOfClearance' => $empdata['DateOfClearance']
//                    ,'Category' => $empdata['Category']
//                    ,'PayrollTerms' => $empdata['PayrollTerms']
//                    ,'PayrollMode' => $empdata['PayrollMode']
//                    ,'ConfidentialityLevel' => $empdata['ConfidentialityLevel']
//                    ,'GroupCode' => $empdata['GroupCode']
//                    ,'Division' => $empdata['Division']
//                    ,'Department' => $empdata['Department']
//                    ,'Section' => $empdata['Section']
//                    ,'Position' => $empdata['Position']
//                    ,'TaxStatus' => $empdata['TaxStatus']
//                    ,'MonthlyGrade' => $empdata['MonthlyGrade']
//                    ,'MonthlyStep' => $empdata['MonthlyStep']
//                    ,'RATA' => $empdata['RATA']
//                    ,'LeaveTable' => $empdata['LeaveTable']
//                    ,'ADCOM' => $empdata['ADCOM']
//                    ,'PERA' => $empdata['PERA']
//                    ,'TransAllowance' => $empdata['TransAllowance']
//                    ,'CustomPYCode' => $empdata['CustomPYCode']
//                    ,'Birthday' => $empdata['Birthday']
//                    ,'Age' => $empdata['Age']
//                    ,'BirthPlace' => $empdata['BirthPlace']
//                    ,'Gender' => $empdata['Gender']
//                    ,'CivilStatus' => $empdata['CivilStatus']
//                    ,'Citizenship' => $empdata['Citizenship']
//                    ,'Height' => $empdata['Height']
//                    ,'Weight' => $empdata['Weight']
//                    ,'Email' => $empdata['Email']
//                    ,'CellNo' => $empdata['CellNo']
//                    ,'StreetNo' => $empdata['StreetNo']
//                    ,'StreetNo1' => $empdata['StreetNo1']
//                    ,'Barangay' => $empdata['Barangay']
//                    ,'Barangay1' => $empdata['Barangay1']
//                    ,'CityTown' => $empdata['CityTown']
//                    ,'CityTown1' => $empdata['CityTown1']
//                    ,'Province' => $empdata['Province']
//                    ,'Province1' => $empdata['Province1']
//                    ,'PhoneNo' => $empdata['PhoneNo']
//                    ,'PhoneNo1' => $empdata['PhoneNo1']
//                    ,'ZipCode' => $empdata['ZipCode']
//                    ,'ZipCode1' => $empdata['ZipCode1']
//                    ,'TimekeepingID' => $empdata['TimekeepingID']
//                    ,'CustomTMCode' => $empdata['CustomTMCode']
//                    ,'BankCode' => $empdata['BankCode']
//                    ,'BankAccountNo' => $empdata['BankAccountNo']
//                    ,'GSISNo' => $empdata['GSISNo']
//                    ,'PhilHealthNo' => $empdata['PhilHealthNo']
//                    ,'PagIbigNo' => $empdata['PagIbigNo']
//                    ,'TaxIDNo' => $empdata['TaxIDNo']
//                    ,'SSSNo' => $empdata['SSSNo']
//                    ,'PolicyNo' => $empdata['PolicyNo']
//                    ,'WithGSISNo' => $empdata['WithGSISNo']
//                    ,'WithPagIbigNo' => $empdata['WithPagIbigNo']
//                    ,'WithPhilHealthNo' => $empdata['WithPhilHealthNo']
//                    ,'WithTaxIDNo' => $empdata['WithTaxIDNo']
//                    ,'Photo' => $empdata['Photo']
//                    ,'CostCenter' => $empdata['CostCenter']
//                    ,'MonthlyRate' => $empdata['MonthlyRate']
//                    ,'DailyRate' => $empdata['DailyRate']
//                    ,'RateDivisior' => $empdata['RateDivisior']
//                    ,'Retirement' => $empdata['Retirement']
//                    ,'GovSector' => $empdata['GovSector']
//                    ,'Plantilla' => $empdata['Plantilla']
//                    ,'Eligibility' => $empdata['Eligibility']
//                    ,'Classification' => $empdata['Classification']
//                    ,'CareerLevel' => $empdata['CareerLevel']
//                    ,'CTC' => $empdata['CTC']
//                    ,'ProvidentNo' => $empdata['ProvidentNo']
//                    ,'WithProvidentNo' => $empdata['WithProvidentNo']
//                    ,'RACode' => $empdata['RACode']
//                    ,'TACode' => $empdata['TACode']
//                    ,'Ethnicity' => $empdata['Ethnicity']
//                    ,'Religion' => $empdata['Religion']
//                    ,'DateOfLastPromotion' => $empdata['DateOfLastPromotion']
//                    ,'Position2' => $empdata['Position2']
//                    ,'BloodType' => $empdata['BloodType']
//                    ,'AgencyEmpNo' => $empdata['AgencyEmpNo']
//                    ,'TelNo' => $empdata['TelNo']
//                    ,'DualType' => $empdata['DualType']
//                    ,'Country' => $empdata['Country']
//                    ,'Detail' => $empdata['Detail']
//                    ,'HouseNo' => $empdata['HouseNo']
//                    ,'Village' => $empdata['Village']
//                    ,'HouseNo1' => $empdata['HouseNo1']
//                    ,'Village1' => $empdata['Village1']
//                    ,'SameDeptCode' => $empdata['SameDeptCode']
//                    ,'SecondSameDeptCode' => $empdata['SecondSameDeptCode']
//                    ,'Signature' => $empdata['Signature']
//                    ,'OfficialPosition' => $empdata['OfficialPosition']);
//                    // ,'lockPDSflag' => 1
//       $reviewdata = array('ReviewFlag' => 0);

      
//       if($this->cud_model->updateDB('Employees',$data,"EmployeeNo='".$empdata['EmployeeNo']."'")){  

//         // //// education ////
//         // for($i=0;$i<count($educdata);$i++){
//         //     $vall = $educdata[$i];

//         //     $new_EducationFrom = $vall['YearFrom'];
//         //     $new_EducationTo = $vall['YearTo'];

//         //     if($vall['YearFrom']=='')
//         //       $new_EducationFrom = Null;
            
//         //     if($vall['YearTo']=='')
//         //       $new_EducationTo = Null;

//         //     $datatrans = array('EmployeeNo' => $vall['EmployeeNo'], 
//         //                        'EducationLevel' => $vall['EducationLevel'],
//         //                        'SchoolCode' => $vall['SchoolCode'],
//         //                        'DegreeCode' => $vall['DegreeCode'],
//         //                        'YearGraduated' => $vall['YearGraduated'],
//         //                        'Units' => $vall['Units'],
//         //                        'HonorRecieved' => $vall['HonorRecieved'],
//         //                        'YearFrom' => $new_EducationFrom,
//         //                        'YearTo' => $new_EducationTo,
//         //                        'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                        'modified_date' => date("Y-m-d H:i:s"),
//         //                        'IsDeleted' => $vall['IsDeleted']);

//         //     if($vall['EducNo']!=''){
//         //       $cond = "EducNo = '".$vall['EducNo']."'";
//         //       $this->cud_model->updateDB('EmployeeEducation', $datatrans, $cond); 
//         //     }
//         //     else{
//         //       $datatrans['created_by'] = $this->session->userdata['logged_in']['username'];
//         //       $datatrans['created_date'] = date("Y-m-d H:i:s");
//         //       $this->cud_model->insertDB('EmployeeEducation', $datatrans); 
//         //     }
//         // }    
//         // //// end education //// 
         
//         // //// Work XP ////
//         // for($i=0;$i<count($workxp);$i++){
//         //     $vall = $workxp[$i];
//         //     $new_Designation = $vall['Designation'];
//         //     $new_Station = $vall['Station'];
//         //     $new_Status = $vall['Status'];

//         //     $new_ServiceDateFrom = $vall['ServiceDateFrom'];
//         //     $new_ServiceDateTo = $vall['ServiceDateTo'];
//         //     $new_SeparationDate = $vall['SeparationDate'];

//         //     if($vall['ServiceDateFrom']=='')
//         //       $new_ServiceDateFrom = Null;

//         //     if($vall['ServiceDateTo']=='')
//         //       $new_ServiceDateTo = Null;

//         //     if($vall['SeparationDate']=='')
//         //       $new_SeparationDate = Null;


//         //     if($vall['Designation']==''){
//         //       $datainsert = array('Description' => $vall['posDesc'],
//         //                            'Status' => 'INACTIVE',
//         //                            'created_by' => $this->session->userdata['logged_in']['username'],
//         //                            'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                            'IsDeleted' => 0);
//         //       // $new_Designation = $this->cud_model->insertDB('PositionMaster',$datainsert,true);
//         //       $new_Designation = $this->cud_model->insertDB_returnExist('PositionMaster',$datainsert,true,$vall['posDesc'],'Code',"Description='".$vall['posDesc']."'");
//         //     }

//         //     if($vall['Station']==''){
//         //       $datainsert = array('DepartmentDesc' => $vall['officedesc'],
//         //                            'IsCPGOffice' => '0',
//         //                            'created_by' => $this->session->userdata['logged_in']['username'],
//         //                            'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                            'IsDeleted' => 0);
//         //       // $new_Station = $this->cud_model->insertDB('Department',$datainsert,true);
//         //       $new_Station = $this->cud_model->insertDB_returnExist('Department',$datainsert,true,$vall['officedesc'],'SeriesNo',"DepartmentDesc='".$vall['officedesc']."'");
//         //     }

//         //     if($vall['Status']==''){
//         //       $datainsert = array('StatusDesc' => $vall['statDesc'],
//         //                            'created_by' => $this->session->userdata['logged_in']['username'],
//         //                            'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                            'IsDeleted' => 0);
//         //       $new_Status = $this->cud_model->insertDB('EmploymentStatus',$datainsert,true);
//         //     }

//         //     $datatrans = array('EmployeeNo' => $vall['EmployeeNo'],
//         //                        'Designation' => $new_Designation,
//         //                        'Station' => $new_Station,
//         //                        'IsGovernment' => $vall['IsGovernment'],
//         //                        'Status' => $new_Status,
//         //                        'awop' => $vall['awop'],
//         //                        'SalaryGrade' => $vall['SalaryGrade'],
//         //                        'SalaryStep' => $vall['SalaryStep'],
//         //                        'TranchNo' => $vall['TranchNo'],
//         //                        'SalaryType' => $vall['SalaryType'],
//         //                        'Salary' => $vall['Salary'],
//         //                        'TA' => $vall['TA'],
//         //                        'ServiceDateFrom' => $new_ServiceDateFrom,
//         //                        'ServiceDateTo' => $new_ServiceDateTo,
//         //                        'SeparationDate' => $new_SeparationDate,
//         //                        'SeparationCause' => $vall['SeparationCause'],
//         //                        'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                        'modified_date' => date("Y-m-d H:i:s"),
//         //                        'IsDeleted' => $vall['IsDeleted']);
//         //     // var_dump($datatrans);
//         //     if($vall['workID']!=''){
//         //       $cond = "SeriesNo = '".$vall['workID']."'";
//         //       $this->cud_model->updateDB('EmployeeWorkXP', $datatrans, $cond); 
//         //     }
//         //     else{
//         //       $datatrans['created_by'] = $this->session->userdata['logged_in']['username'];
//         //       $datatrans['created_date'] = date("Y-m-d H:i:s");
//         //       $this->cud_model->insertDB('EmployeeWorkXP', $datatrans); 
//         //     }
//         // }    
//         // //// end Work XP ////   

//         // //// eligibility ////
//         // for($i=0;$i<count($eligdata);$i++){
//         //     $vall = $eligdata[$i];

//         //     $new_DateOfExam = $vall['DateOfExam'];
//         //     $new_ValidityDate = $vall['ValidityDate'];

//         //     if($vall['DateOfExam']=='')
//         //       $new_DateOfExam = Null;

//         //     if($vall['ValidityDate']=='')
//         //       $new_ValidityDate = Null;

//         //     $datatrans = array('EmployeeNo' => $vall['EmployeeNo'],
//         //                        'EligCode' => $vall['EligCode'],
//         //                        'DateOfExam' => $new_DateOfExam,
//         //                        'Rating' => $vall['Rating'],
//         //                        'PlaceOfExam' => $vall['PlaceOfExam'],
//         //                        'LicenseNo' => $vall['LicenseNo'],
//         //                        'ValidityDate' => $new_ValidityDate,
//         //                        'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                        'modified_date' => date("Y-m-d H:i:s"),
//         //                        'IsDeleted' => $vall['IsDeleted']);

//         //     if($vall['EligID']!=''){
//         //       $cond = "SeriesNo = '".$vall['EligID']."'";
//         //       $this->cud_model->updateDB('EmployeeEligibility', $datatrans, $cond); 
//         //     }
//         //     else{
//         //       $datatrans['created_by'] = $this->session->userdata['logged_in']['username'];
//         //       $datatrans['created_date'] = date("Y-m-d H:i:s");
//         //       $this->cud_model->insertDB('EmployeeEligibility', $datatrans); 
//         //     }
//         // }    
//         // //// end eligibility ////  
         
//         // //// family ////
//         // for($i=0;$i<count($famdata);$i++){
//         //     $vall = $famdata[$i];

//         //     $new_birthdate = $vall['DateOfBirth'];

//         //     if($vall['DateOfBirth']==''){$new_birthdate = Null;} 
//         //     // if($vall['FName']==''){$vall['FName'] = Null;}
//         //     // if($vall['MName']==''){$vall['MName'] = Null;}
//         //     // if($vall['LName']==''){$vall['LName'] = Null;}
//         //     // if($vall['XName']==''){$vall['XName'] = Null;}
//         //     // if($vall['PlaceOfBirth']==''){$vall['PlaceOfBirth'] = Null;}
//         //     // if($vall['Occupation']==''){$vall['Occupation'] = Null;}
//         //     // if($vall['Address']==''){$vall['Address'] = Null;}
//         //     // if($vall['ContactNo']==''){$vall['ContactNo'] = Null;}
//         //     // if($vall['Employer']==''){$vall['Employer'] = Null;}
//         //     // if($vall['EmployerAddress']==''){$vall['EmployerAddress'] = Null;}
//         //     // if($vall['Remarks']==''){$vall['Remarks'] = Null;}

//         //     $datatrans = array('EmployeeNo' => $vall['EmployeeNo'],
//         //                        'RelationCode' => $vall['RelationCode'],
//         //                        'FName' => $vall['FName'],
//         //                        'MName' => $vall['MName'],
//         //                        'LName' => $vall['LName'],
//         //                        'XName' => $vall['XName'],
//         //                        'DateOfBirth' => $new_birthdate,
//         //                        'PlaceOfBirth' => $vall['PlaceOfBirth'],
//         //                        'Occupation' => $vall['Occupation'],
//         //                        'Address' => $vall['Address'],
//         //                        'ContactNo' => $vall['ContactNo'],
//         //                        'Employer' => $vall['Employer'],
//         //                        'EmployerAddress' => $vall['EmployerAddress'],
//         //                        'Remarks' => $vall['Remarks'],
//         //                        'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                        'modified_date' => date("Y-m-d H:i:s"),
//         //                        'IsDeleted' => $vall['IsDeleted']);

//         //     if($vall['famID']!=''){
//         //       $cond = "SeriesNo = '".$vall['famID']."'";
//         //       $this->cud_model->updateDB('EmployeeRelative', $datatrans, $cond); 
//         //     }
//         //     else{
//         //       $datatrans['created_by'] = $this->session->userdata['logged_in']['username'];
//         //       $datatrans['created_date'] = date("Y-m-d H:i:s");
//         //       $this->cud_model->insertDB('EmployeeRelative', $datatrans); 
//         //     }
//         // }    
//         // //// end family ////  
         
//         // //// reference ////
//         // for($i=0;$i<count($refdata);$i++){
//         //     $vall = $refdata[$i];
//         //     $datatrans = array('EmployeeNo' => $vall['EmployeeNo'],
//         //                        'ReferenceName' => $vall['ReferenceName'],
//         //                        'Address' => $vall['Address'],
//         //                        'ContactNo' => $vall['ContactNo'],
//         //                        'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                        'modified_date' => date("Y-m-d H:i:s"),
//         //                        'IsDeleted' => $vall['IsDeleted']);

//         //     if($vall['refID']!=''){
//         //       $cond = "SeriesNo = '".$vall['refID']."'";
//         //       $this->cud_model->updateDB('EmployeeReferences', $datatrans, $cond); 
//         //     }
//         //     else{
//         //       $datatrans['created_by'] = $this->session->userdata['logged_in']['username'];
//         //       $datatrans['created_date'] = date("Y-m-d H:i:s");
//         //       $this->cud_model->insertDB('EmployeeReferences', $datatrans); 
//         //     }
//         // }    
//         // //// end reference ////  
         
//         // //// organization ////
//         // for($i=0;$i<count($orgdata);$i++){
//         //     $vall = $orgdata[$i];
//         //     $new_orgcode = $vall['OrgCode'];
//         //     $new_MemberFrom = $vall['MemberFrom'];
//         //     $new_MemberTo = $vall['MemberTo'];

//         //     if($vall['MemberFrom']=='')
//         //       $new_MemberFrom = Null;

//         //     if($vall['MemberTo']=='')
//         //       $new_MemberTo = Null;

//         //     if($vall['OrgCode']==''){
//         //       $crd = array('Descr' => $vall['OrgDesc'],
//         //                    'created_by' => $this->session->userdata['logged_in']['username'],
//         //                    'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                    'IsDeleted' => 0);
//         //       $new_orgcode = $this->cud_model->insertDB_CodeReference($crd,'Org',true);
//         //     }

//         //     $datatrans = array('EmployeeNo' => $vall['EmployeeNo'],
//         //                        'OrgCode' => $new_orgcode,
//         //                        'MembershipTitle' => $vall['MembershipTitle'],
//         //                        'MemberFrom' => $new_MemberFrom,
//         //                        'MemberTo' => $new_MemberTo,
//         //                        'IsCivic' => $vall['IsCivic'],
//         //                        'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                        'modified_date' => date("Y-m-d H:i:s"),
//         //                        'IsDeleted' => $vall['IsDeleted']);
//         //     // var_dump($datatrans);
//         //     if($vall['orgID']!=''){
//         //       $cond = "OrgNo = '".$vall['orgID']."'";
//         //       $this->cud_model->updateDB('EmployeeOrganization', $datatrans, $cond); 
//         //     }
//         //     else{
//         //       $datatrans['created_by'] = $this->session->userdata['logged_in']['username'];
//         //       $datatrans['created_date'] = date("Y-m-d H:i:s");
//         //       $this->cud_model->insertDB('EmployeeOrganization', $datatrans); 
//         //     }
//         // }    
//         // //// end organization //// 
         
//         // //// Training ////
//         // for($i=0;$i<count($traindata);$i++){
//         //     $vall = $traindata[$i];
//         //     $new_schedID = $vall['schedID'];
//         //     $new_trainingID = $vall['trainingID'];
//         //     $new_VenueID = $vall['VenueID'];
//         //     $new_ProviderID = $vall['ProviderID'];
//         //     $new_FacilitatorID = $vall['FacilitatorID'];

//         //     $new_DateStart = $vall['DateStart'];
//         //     $new_DateEnd = $vall['DateEnd'];

//         //     if($vall['DateStart']=='')
//         //       $new_DateStart = Null;

//         //     if($vall['DateEnd']=='')
//         //       $new_DateEnd = Null;

//         //     if($vall['trainingID']==''&&($vall['trainDesc']!=''&&$vall['trainDesc']!='null')){
//         //       $datainsert = array('TrainingDesc' => $vall['trainDesc'],
//         //                            'TrainingType' => '',
//         //                            'TypeOfLD' => '',
//         //                            'Remarks' => '',
//         //                            'created_by' => $this->session->userdata['logged_in']['username'],
//         //                            'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                            'IsDeleted' => 0);
//         //       $new_trainingID = $this->cud_model->insertDB('TrainingMaster',$datainsert,true);
//         //     }

//         //     if($vall['VenueID']==''&&($vall['venueDesc']!=''&&$vall['venueDesc']!='null')){
//         //       $datainsert = array('Venue' => $vall['venueDesc'],
//         //                            'LiveIn' => '0',
//         //                            'created_by' => $this->session->userdata['logged_in']['username'],
//         //                            'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                            'IsDeleted' => 0);
//         //       $new_VenueID = $this->cud_model->insertDB('TrainingVenue',$datainsert,true);
//         //     }

//         //     if($vall['ProviderID']==''&&($vall['providerDesc']!=''&&$vall['providerDesc']!='null')){
//         //       $datainsert = array('Abbrv' => '',
//         //                            'ProviderName' => $vall['providerDesc'],
//         //                            'Address' => '',
//         //                            'ContactPerson' => '',
//         //                            'ContactNo' => '',
//         //                            'Remarks' => '',
//         //                            'created_by' => $this->session->userdata['logged_in']['username'],
//         //                            'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                            'IsDeleted' => 0);
//         //       $new_ProviderID = $this->cud_model->insertDB('TrainingProvider',$datainsert,true);
//         //     }

//         //     if($vall['FacilitatorID']==''&&($vall['faciDesc']!=''&&$vall['faciDesc']!='null')){
//         //       $datainsert = array('FaciName' => $vall['faciDesc'],
//         //                            'Remarks' => '',
//         //                            'created_by' => $this->session->userdata['logged_in']['username'],
//         //                            'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                            'IsDeleted' => 0);
//         //       $new_FacilitatorID = $this->cud_model->insertDB('TrainingFacilitator',$datainsert,true);
//         //     }

//         //     $trainingdata = array('TrainingID' => $new_trainingID,
//         //                           'VenueID' => $new_VenueID,
//         //                           'ProviderID' => $new_ProviderID,
//         //                           'FacilitatorID' => $new_FacilitatorID,
//         //                           'TypeOfLD' => $vall['TypeOfLD'],
//         //                           'DateStart' => $new_DateStart,
//         //                           'DateEnd' => $new_DateEnd,
//         //                           'Hours' => $vall['Hours'],
//         //                           'Status' => '',
//         //                           'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                           'modified_date' => date("Y-m-d H:i:s"),
//         //                           'IsDeleted' => $vall['IsDeleted']);

//         //     if($vall['schedID']!=''){
//         //       $cond = "TrainingListID = '".$vall['schedID']."'";
//         //       $this->cud_model->updateDB('TrainingList', $trainingdata, $cond); 
//         //     }
//         //     else{
//         //       $trainingdata['created_by'] = $this->session->userdata['logged_in']['username'];
//         //       $trainingdata['created_date'] = date("Y-m-d H:i:s"); 
//         //       $new_schedID = $this->cud_model->insertDB('TrainingList',$trainingdata,true);
//         //     }

//         //     $attenddata = array('TrainingListID' => $new_schedID,
//         //                         'EmployeeNo' => $vall['EmployeeNo'],
//         //                         'Office' => $vall['Office'],
//         //                         'IsSubmitted' => '0',
//         //                         'IsPresent' => '1',
//         //                         'Remarks' => '',
//         //                         'ImplementRemarks' => '',
//         //                         'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                         'modified_date' => date("Y-m-d H:i:s"),
//         //                         'IsDeleted' => $vall['IsDeleted']);

//         //     if($vall['trainID']!=''){
//         //       $cond = "SeriesNo = '".$vall['trainID']."'";
//         //       $this->cud_model->updateDB('TrainingAttendance', $attenddata, $cond); 
//         //     }
//         //     else{
//         //       $attenddata['created_by'] = $this->session->userdata['logged_in']['username'];
//         //       $attenddata['created_date'] = date("Y-m-d H:i:s");
//         //       $this->cud_model->insertDB('TrainingAttendance', $attenddata); 
//         //     }
//         // }    
//         // //// end Training ////  
         
//         // //// skill ////
//         // for($i=0;$i<count($skilldata);$i++){
//         //     $vall = $skilldata[$i];
//         //     $new_skillcode = $vall['EmployeeSkill'];

//         //     if($vall['EmployeeSkill']==''){
//         //       $crd = array('Descr' => $vall['SkillDesc'],
//         //                    'created_by' => $this->session->userdata['logged_in']['username'],
//         //                    'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                    'IsDeleted' => 0);
//         //       $new_skillcode = $this->cud_model->insertDB_CodeReference($crd,'S',true);
//         //     }

//         //     $datatrans = array('EmployeeNo' => $vall['EmployeeNo'],
//         //                        'EmployeeSkill' => $new_skillcode,
//         //                        'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                        'modified_date' => date("Y-m-d H:i:s"),
//         //                        'IsDeleted' => $vall['IsDeleted']);

//         //     if($vall['skillID']!=''){
//         //       $cond = "SkillNo = '".$vall['skillID']."'";
//         //       $this->cud_model->updateDB('EmployeeSkills', $datatrans, $cond); 
//         //     }
//         //     else{
//         //       $datatrans['created_by'] = $this->session->userdata['logged_in']['username'];
//         //       $datatrans['created_date'] = date("Y-m-d H:i:s");
//         //       $this->cud_model->insertDB('EmployeeSkills', $datatrans); 
//         //     }
//         // }    
//         // //// end skill //// 
         
//         // //// recognition ////
//         // for($i=0;$i<count($recogdata);$i++){
//         //     $vall = $recogdata[$i];
//         //     $new_recogcode = $vall['RecogCode'];

//         //     if($vall['RecogCode']==''){
//         //       $crd = array('Descr' => $vall['RecogDesc'],
//         //                    'created_by' => $this->session->userdata['logged_in']['username'],
//         //                    'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                    'IsDeleted' => 0);
//         //       $new_recogcode = $this->cud_model->insertDB_CodeReference($crd,'recog',true);
//         //     }

//         //     $datatrans = array('EmployeeNo' => $vall['EmployeeNo'],
//         //                        'RecognitionID' => $new_recogcode,
//         //                        'modified_by' => $this->session->userdata['logged_in']['username'],
//         //                        'modified_date' => date("Y-m-d H:i:s"),
//         //                        'IsDeleted' => $vall['IsDeleted']);

//         //     if($vall['recogID']!=''){
//         //       $cond = "SeriesNo = '".$vall['recogID']."'";
//         //       $this->cud_model->updateDB('EmployeeRecognition', $datatrans, $cond); 
//         //     }
//         //     else{
//         //       $datatrans['created_by'] = $this->session->userdata['logged_in']['username'];
//         //       $datatrans['created_date'] = date("Y-m-d H:i:s");
//         //       $this->cud_model->insertDB('EmployeeRecognition', $datatrans); 
//         //     }
//         // }    
//         // //// end recognition //// 
         
//         //// other ////
//         if($otherdata){
//           $datatrans = array('a1' => $otherdata['a1']
//                              ,'b1' => $otherdata['b1']
//                              ,'b1Remark' => $otherdata['b1Remark']
//                              ,'a2' => $otherdata['a2']
//                              ,'a2Remark' => $otherdata['a2Remark']
//                              ,'b2' => $otherdata['b2']
//                              ,'b2datefiled' => $otherdata['b2datefiled']
//                              ,'b2Remark' => $otherdata['b2Remark']
//                              ,'a3' => $otherdata['a3']
//                              ,'a3Remark' => $otherdata['a3Remark']
//                              ,'a4' => $otherdata['a4']
//                              ,'a4Remark' => $otherdata['a4Remark']
//                              ,'a5' => $otherdata['a5']
//                              ,'a5Remark' => $otherdata['a5Remark']
//                              ,'b5' => $otherdata['b5']
//                              ,'b5Remark' => $otherdata['b5Remark']
//                              ,'a6' => $otherdata['a6']
//                              ,'a6Remark' => $otherdata['a6Remark']
//                              ,'a7' => $otherdata['a7']
//                              ,'a7Remark' => $otherdata['a7Remark']
//                              ,'b7' => $otherdata['b7']
//                              ,'b7Remark' => $otherdata['b7Remark']
//                              ,'c7' => $otherdata['c7']
//                              ,'c7Remark' => $otherdata['c7Remark']
//                              ,'modified_by' => $this->session->userdata['logged_in']['username']
//                              ,'modified_date' => date("Y-m-d H:i:s"));
//           if($otherdata['EmployeeNo']!=''){
//             $cond = "EmployeeNo = '".$otherdata['EmployeeNo']."'";
//             $this->cud_model->updateDB('EmployeeOtherDetail', $datatrans, $cond); 
//           }
//           else{
//             $datatrans['EmployeeNo'] = $empdata['EmployeeNo'];
//             $datatrans['created_by'] = $this->session->userdata['logged_in']['username'];
//             $datatrans['created_date'] = date("Y-m-d H:i:s");
//             $this->cud_model->insertDB('EmployeeOtherDetail', $datatrans); 
//           }
//         }
//         //// end other ////

//         //// this is to flag the PDS as not reviewed right after saving the pds ////
//         $reviewquery = "Execute usp_ReviewPDS '".$empdata['EmployeeNo']."','".$this->session->userdata['logged_in']['username']."','".$_SERVER['REMOTE_ADDR']."','ONLINE PDS','0'";
//         $this->cud_model->ExecuteQuery($reviewquery);

//         echo json_encode(true);
//       }
//       else{
//         echo json_encode(false);
//       }

//   }

//   public function saveEmployee_Education()
//   {  
//       $code = $this->input->post('EducNo');
//       $data = array('EmployeeNo' => $this->input->post('EmployeeNo')
//                    ,'EducationLevel' => $this->input->post('EducationLevel')
//                    ,'SchoolCode' => $this->input->post('SchoolCode')
//                    ,'DegreeCode' => $this->input->post('DegreeCode')
//                    ,'YearGraduated' => $this->input->post('YearGraduated')
//                    ,'Units' => $this->input->post('Units')
//                    ,'HonorRecieved' => $this->input->post('HonorRecieved')
//                    ,'YearFrom' => $this->input->post('YearFrom')
//                    ,'YearTo' => $this->input->post('YearTo')
//                    ,'IsDeleted' => $this->input->post('IsDeleted')
//                    ,'modified_by' => $this->session->userdata['logged_in']['username']
//                    ,'modified_date' => date("Y-m-d H:i:s"));

//       if($code){
//         $cond = array('EducNo' => $code);
//         if($this->cud_model->updateDB('EmployeeEducation',$data, $cond)){         
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB('EmployeeEducation',$data)){ 
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }
//   }

//   public function saveEmployee_WorkXP()
//   {  
//       $code = $this->input->post('workID');

//       $new_Designation = $this->input->post('Designation');
//       $new_Station = $this->input->post('Station');
//       $new_Status = $this->input->post('Status');

//       $new_ServiceDateFrom = $this->input->post('ServiceDateFrom');
//       $new_ServiceDateTo = $this->input->post('ServiceDateTo');
//       $new_SeparationDate = $this->input->post('SeparationDate');

//       if($new_ServiceDateFrom=='')
//         $new_ServiceDateFrom = Null;

//       if($new_ServiceDateTo=='')
//         $new_ServiceDateTo = Null;

//       if($new_SeparationDate=='')
//         $new_SeparationDate = Null;


//       if($new_Designation==''){
//         $datainsert = array('Description' => $this->input->post('posDesc'),
//                              'Status' => 'INACTIVE',
//                              'created_by' => $this->session->userdata['logged_in']['username'],
//                              'modified_by' => $this->session->userdata['logged_in']['username'],
//                              'IsDeleted' => 0);

//         $new_Designation = $this->cud_model->insertDB_returnExist('PositionMaster',$datainsert,true,$this->input->post('posDesc'),'Code',"Description='".$this->input->post('posDesc')."'");
//       }

//       if($new_Station==''){
//         $datainsert = array('DepartmentDesc' => $this->input->post('officedesc'),
//                              'IsCPGOffice' => '0',
//                              'created_by' => $this->session->userdata['logged_in']['username'],
//                              'modified_by' => $this->session->userdata['logged_in']['username'],
//                              'IsDeleted' => 0);

//         $new_Station = $this->cud_model->insertDB_returnExist('Department',$datainsert,true,$this->input->post('officedesc'),'SeriesNo',"DepartmentDesc='".$this->input->post('officedesc')."'");
//       }

//       if($new_Status==''){
//         $datainsert = array('StatusDesc' => $this->input->post('statDesc'),
//                              'created_by' => $this->session->userdata['logged_in']['username'],
//                              'modified_by' => $this->session->userdata['logged_in']['username'],
//                              'IsDeleted' => 0);

//         $new_Status = $this->cud_model->insertDB_returnExist('EmploymentStatus',$datainsert,true,$this->input->post('statDesc'),'SeriesNo',"StatusDesc='".$this->input->post('statDesc')."'");
//       }

//       $datatrans = array('EmployeeNo' => $this->input->post('EmployeeNo')
//                          ,'Designation' => $new_Designation
//                          ,'Station' => $new_Station
//                          ,'IsGovernment' => $this->input->post('IsGovernment')
//                          ,'Status' => $new_Status
//                          ,'awop' => $this->input->post('awop')
//                          ,'SalaryGrade' => $this->input->post('SalaryGrade')
//                          ,'SalaryStep' => $this->input->post('SalaryStep')
//                          ,'TranchNo' => $this->input->post('TranchNo')
//                          ,'SalaryType' => $this->input->post('SalaryType')
//                          ,'Salary' => $this->input->post('Salary')
//                          ,'TA' => $this->input->post('TA')
//                          ,'ServiceDateFrom' => $new_ServiceDateFrom
//                          ,'ServiceDateTo' => $new_ServiceDateTo
//                          ,'SeparationDate' => $new_SeparationDate
//                          ,'SeparationCause' => $this->input->post('SeparationCause')
//                          ,'modified_by' => $this->session->userdata['logged_in']['username']
//                          ,'modified_date' => date("Y-m-d H:i:s"));

//       if($code!=''){
//         $cond = "SeriesNo = '".$code."'";
//         if($this->cud_model->updateDB('EmployeeWorkXP',$datatrans, $cond)){         
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $datatrans['created_by'] = $this->session->userdata['logged_in']['username'];
//         $datatrans['created_date'] = date("Y-m-d H:i:s");
//         if($this->cud_model->insertDB('EmployeeWorkXP',$datatrans)){ 
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }
//   }

//   public function saveEmployee_Eligibility()
//   {  
//       $code = $this->input->post('EligID');
//       $new_DateOfExam = $this->input->post('DateOfExam');
//       $new_ValidityDate = $this->input->post('ValidityDate');
//       $eligdates = $this->input->post('eligdates');

//       if($new_DateOfExam=='')
//         $new_DateOfExam = Null;

//       if($new_ValidityDate=='')
//         $new_ValidityDate = Null;

//       $data = array('EmployeeNo' => $this->input->post('EmployeeNo')
//                    ,'EligCode' => $this->input->post('EligCode')
//                    ,'DateOfExam' => $new_DateOfExam
//                    ,'Rating' => $this->input->post('Rating')
//                    ,'PlaceOfExam' => $this->input->post('PlaceOfExam')
//                    ,'LicenseNo' => $this->input->post('LicenseNo')
//                    ,'ValidityDate' => $new_ValidityDate
//                    ,'Remarks' => $this->input->post('Remarks')
//                    ,'modified_by' => $this->session->userdata['logged_in']['username']
//                    ,'modified_date' => date("Y-m-d H:i:s"));

//       if($code!=''){
//         $cond = array('SeriesNo' => $code);
//         if($this->cud_model->updateDB('EmployeeEligibility',$data, $cond)){ 

//           //// saving eligdates ////
//           for($i=0;$i<count($eligdates);$i++){
//               $vall = $eligdates[$i];

//               $datatrans = array('EligID' => $code,
//                                  'DateOfElig' => $vall['EligDate'],
//                                  'modified_by' => $this->session->userdata['logged_in']['username'],
//                                  'modified_date' => date("Y-m-d H:i:s"),
//                                  'IsDeleted' => $vall['IsDeleted']);

//               if($vall['ID']!=''){
//                 $cond = "SeriesNo = '".$vall['ID']."'";
//                 $this->cud_model->updateDB('EmployeeEligibility_Dates', $datatrans, $cond); 
//               }
//               else{
//                 $datatrans['created_by'] = $this->session->userdata['logged_in']['username'];
//                 $datatrans['created_date'] = date("Y-m-d H:i:s");
//                 $this->cud_model->insertDB('EmployeeEligibility_Dates', $datatrans); 
//               } 
//           }    
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         $newEligID = $this->cud_model->insertDB('EmployeeEligibility',$data,true);
//         if($newEligID){ 
//           //// saving eligdates ////
//           for($i=0;$i<count($eligdates);$i++){
//               $vall = $eligdates[$i];

//               $datatrans = array('EligID' => $newEligID,
//                                  'DateOfElig' => $vall['EligDate'],
//                                  'modified_by' => $this->session->userdata['logged_in']['username'],
//                                  'modified_date' => date("Y-m-d H:i:s"),
//                                  'IsDeleted' => $vall['IsDeleted']);

//               if($vall['ID']!=''){
//                 $cond = "SeriesNo = '".$vall['ID']."'";
//                 $this->cud_model->updateDB('EmployeeEligibility_Dates', $datatrans, $cond); 
//               }
//               else{
//                 $datatrans['created_by'] = $this->session->userdata['logged_in']['username'];
//                 $datatrans['created_date'] = date("Y-m-d H:i:s");
//                 $this->cud_model->insertDB('EmployeeEligibility_Dates', $datatrans); 
//               } 
//           }
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }
//   }

//   public function saveEmployee_Family()
//   {  
//       $code = $this->input->post('famID');

//       $new_birthdate = $this->input->post('DateOfBirth');

//       if($new_birthdate==''){$new_birthdate = Null;} 

//       $data = array('EmployeeNo' => $this->input->post('EmployeeNo'),
//                          'RelationCode' => $this->input->post('RelationCode'),
//                          'FName' => $this->input->post('FName'),
//                          'MName' => $this->input->post('MName'),
//                          'LName' => $this->input->post('LName'),
//                          'XName' => $this->input->post('XName'),
//                          'DateOfBirth' => $new_birthdate,
//                          'PlaceOfBirth' => $this->input->post('PlaceOfBirth'),
//                          'Occupation' => $this->input->post('Occupation'),
//                          'Address' => $this->input->post('Address'),
//                          'ContactNo' => $this->input->post('ContactNo'),
//                          'Employer' => $this->input->post('Employer'),
//                          'EmployerAddress' => $this->input->post('EmployerAddress'),
//                          'Remarks' => $this->input->post('Remarks'),
//                          'modified_by' => $this->session->userdata['logged_in']['username'],
//                          'modified_date' => date("Y-m-d H:i:s"));

//       if($code!=''){
//         $cond = array('SeriesNo' => $code);
//         if($this->cud_model->updateDB('EmployeeRelative',$data, $cond)){         
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB('EmployeeRelative',$data)){ 
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }
//   }

//   public function saveEmployee_Reference()
//   {  
//       $code = $this->input->post('refID');

//       $data = array('EmployeeNo' => $this->input->post('EmployeeNo'),
//                     'ReferenceName' => $this->input->post('ReferenceName'),
//                     'Address' => $this->input->post('Address'),
//                     'ContactNo' => $this->input->post('ContactNo'),
//                     'modified_by' => $this->session->userdata['logged_in']['username'],
//                     'modified_date' => date("Y-m-d H:i:s"));

//       if($code!=''){
//         $cond = array('SeriesNo' => $code);
//         if($this->cud_model->updateDB('EmployeeReferences',$data, $cond)){         
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB('EmployeeReferences',$data)){ 
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }
//   }

//   public function saveEmployee_Organization()
//   {  
//       $code = $this->input->post('orgID');

//       $new_orgcode = $this->input->post('OrgCode');
//       $new_MemberFrom = $this->input->post('MemberFrom');
//       $new_MemberTo = $this->input->post('MemberTo');

//       if($new_MemberFrom=='')
//         $new_MemberFrom = Null;

//       if($new_MemberTo=='')
//         $new_MemberTo = Null;

//       if($new_orgcode==''){
//         $crd = array('Descr' => $this->input->post('OrgDesc'),
//                      'created_by' => $this->session->userdata['logged_in']['username'],
//                      'modified_by' => $this->session->userdata['logged_in']['username'],
//                      'IsDeleted' => 0);
//         $new_orgcode = $this->cud_model->insertDB_CodeReference($crd,'Org',true);
//       }

//       $data = array('EmployeeNo' => $this->input->post('EmployeeNo'),
//                     'OrgCode' => $new_orgcode,
//                     'Address' => $this->input->post('Address'),
//                     'MembershipTitle' => $this->input->post('MembershipTitle'),
//                     'NoOfHours' => $this->input->post('NoOfHours'),
//                     'MemberFrom' => $new_MemberFrom,
//                     'MemberTo' => $new_MemberTo,
//                     'IsCivic' => $this->input->post('IsCivic'),
//                     'modified_by' => $this->session->userdata['logged_in']['username'],
//                     'modified_date' => date("Y-m-d H:i:s"));

//       if($code!=''){
//         $cond = array('OrgNo' => $code);
//         if($this->cud_model->updateDB('EmployeeOrganization',$data, $cond)){         
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB('EmployeeOrganization',$data)){ 
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }
//   }

//   public function saveEmployee_Training()
//   {  
//       $code = $this->input->post('trainID');
//       $new_schedID = $this->input->post('schedID');
//       $new_trainingID = $this->input->post('trainingID');
//       $new_VenueID = $this->input->post('VenueID');
//       $new_ProviderID = $this->input->post('ProviderID');
//       $new_FacilitatorID = $this->input->post('FacilitatorID');

//       $new_DateStart = $this->input->post('DateStart');
//       $new_DateEnd = $this->input->post('DateEnd');

//       if($new_DateStart=='')
//         $new_DateStart = Null;

//       if($new_DateEnd=='')
//         $new_DateEnd = Null;

//       if($new_trainingID==''&&($this->input->post('trainDesc')!=''&&$this->input->post('trainDesc')!='null')){
//         $datainsert = array('TrainingDesc' => $this->input->post('trainDesc'),
//                              'TrainingType' => '',
//                              'TypeOfLD' => '',
//                              'Remarks' => '',
//                              'created_by' => $this->session->userdata['logged_in']['username'],
//                              'modified_by' => $this->session->userdata['logged_in']['username'],
//                              'IsDeleted' => 0);
//         $new_trainingID = $this->cud_model->insertDB('TrainingMaster',$datainsert,true);
//       }

//       if($this->input->post('VenueID')==''&&($this->input->post('venueDesc')!=''&&$this->input->post('venueDesc')!='null')){
//         $datainsert = array('Venue' => $this->input->post('venueDesc'),
//                              'LiveIn' => '0',
//                              'created_by' => $this->session->userdata['logged_in']['username'],
//                              'modified_by' => $this->session->userdata['logged_in']['username'],
//                              'IsDeleted' => 0);
//         // $new_VenueID = $this->cud_model->insertDB('TrainingVenue',$datainsert,true);
//         $new_VenueID = $this->cud_model->insertDB_returnExist('TrainingVenue',$datainsert,true
//                                             ,$this->input->post('venueDesc'),'VenueID',"Venue='".$this->input->post('venueDesc')."'");
//       }

//       if($this->input->post('ProviderID')==''&&($this->input->post('providerDesc')!=''&&$this->input->post('providerDesc')!='null')){
//         $datainsert = array('Abbrv' => '',
//                              'ProviderName' => $this->input->post('providerDesc'),
//                              'Address' => '',
//                              'ContactPerson' => '',
//                              'ContactNo' => '',
//                              'Remarks' => '',
//                              'created_by' => $this->session->userdata['logged_in']['username'],
//                              'modified_by' => $this->session->userdata['logged_in']['username'],
//                              'IsDeleted' => 0);
//         // $new_ProviderID = $this->cud_model->insertDB('TrainingProvider',$datainsert,true);
//         $new_ProviderID = $this->cud_model->insertDB_returnExist('TrainingProvider',$datainsert,true
//                                             ,$this->input->post('providerDesc'),'ProviderID',"ProviderName='".$this->input->post('providerDesc')."'");
//       }

//       if($this->input->post('FacilitatorID')==''&&($this->input->post('faciDesc')!=''&&$this->input->post('faciDesc')!='null')){
//         $datainsert = array('FaciName' => $this->input->post('faciDesc'),
//                              'Remarks' => '',
//                              'created_by' => $this->session->userdata['logged_in']['username'],
//                              'modified_by' => $this->session->userdata['logged_in']['username'],
//                              'IsDeleted' => 0);
//         // $new_FacilitatorID = $this->cud_model->insertDB('TrainingFacilitator',$datainsert,true);
//         $new_FacilitatorID = $this->cud_model->insertDB_returnExist('TrainingFacilitator',$datainsert,true
//                                             ,$this->input->post('faciDesc'),'SeriesNo',"FaciName='".$this->input->post('faciDesc')."'");
//       }

//       $trainingdata = array('TrainingID' => $new_trainingID,
//                             'VenueID' => $new_VenueID,
//                             'ProviderID' => $new_ProviderID,
//                             'FacilitatorID' => $new_FacilitatorID,
//                             'TypeOfLD' => $this->input->post('TypeOfLD'),
//                             'DateStart' => $new_DateStart,
//                             'DateEnd' => $new_DateEnd,
//                             'Hours' => $this->input->post('Hours'),
//                             'Status' => '',
//                             'modified_by' => $this->session->userdata['logged_in']['username'],
//                             'modified_date' => date("Y-m-d H:i:s"));

//       if($new_schedID!=''){
//         $cond = "TrainingListID = '".$new_schedID."'";
//         $this->cud_model->updateDB('TrainingList', $trainingdata, $cond); 
//       }
//       else{
//         $trainingdata['created_by'] = $this->session->userdata['logged_in']['username'];
//         $trainingdata['created_date'] = date("Y-m-d H:i:s"); 
//         $new_schedID = $this->cud_model->insertDB('TrainingList',$trainingdata,true);
//       }

//       $data = array('TrainingListID' => $new_schedID,
//                     'EmployeeNo' => $this->input->post('EmployeeNo'),
//                     'Office' => $this->input->post('Office'),
//                     'IsSubmitted' => '0',
//                     'IsPresent' => '1',
//                     'Remarks' => '',
//                     'ImplementRemarks' => '',
//                     'modified_by' => $this->session->userdata['logged_in']['username'],
//                     'modified_date' => date("Y-m-d H:i:s"));

//       if($code!=''){
//         $cond = array('SeriesNo' => $code);
//         if($this->cud_model->updateDB('TrainingAttendance',$data, $cond)){         
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB('TrainingAttendance',$data)){ 
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }
//   }

//   public function saveEmployee_Skill()
//   {  
//       $code = $this->input->post('skillID');
      
//       $new_skillcode = $this->input->post('EmployeeSkill');

//       if($new_skillcode==''){
//         $crd = array('Descr' => $this->input->post('SkillDesc'),
//                      'created_by' => $this->session->userdata['logged_in']['username'],
//                      'modified_by' => $this->session->userdata['logged_in']['username'],
//                      'IsDeleted' => 0);
//         $new_skillcode = $this->cud_model->insertDB_CodeReference($crd,'S',true);
//       }

//       $data = array('EmployeeNo' => $this->input->post('EmployeeNo'),
//                     'EmployeeSkill' => $new_skillcode,
//                     'modified_by' => $this->session->userdata['logged_in']['username'],
//                     'modified_date' => date("Y-m-d H:i:s"));

//       if($code!=''){
//         $cond = array('SkillNo' => $code);
//         if($this->cud_model->updateDB('EmployeeSkills',$data, $cond)){         
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB('EmployeeSkills',$data)){ 
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }
//   }

//   public function saveEmployee_Recognition()
//   {  
//       $code = $this->input->post('recogID');

//       $new_recogcode = $this->input->post('RecogCode');

//       if($new_recogcode==''){
//         $crd = array('Descr' => $this->input->post('RecogDesc'),
//                      'created_by' => $this->session->userdata['logged_in']['username'],
//                      'modified_by' => $this->session->userdata['logged_in']['username'],
//                      'IsDeleted' => 0);
//         $new_recogcode = $this->cud_model->insertDB_CodeReference($crd,'recog',true);
//       }

//       $data = array('EmployeeNo' => $this->input->post('EmployeeNo'),
//                     'RecognitionID' => $new_recogcode,
//                     'modified_by' => $this->session->userdata['logged_in']['username'],
//                     'modified_date' => date("Y-m-d H:i:s"));

//       if($code!=''){
//         $cond = array('SeriesNo' => $code);
//         if($this->cud_model->updateDB('EmployeeRecognition',$data, $cond)){         
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB('EmployeeRecognition',$data)){ 
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }
//   }


//   public function saveEducation()
//   {  
//       $code = $this->input->post('educno');
//       $data = array('EmployeeNo' => $this->input->post('empno')
//                    ,'EducationLevel' => $this->input->post('educlvl')
//                    ,'SchoolCode' => $this->input->post('schoolid')
//                    ,'DegreeCode' => $this->input->post('degreeid')
//                    ,'YearGraduated' => $this->input->post('yeargrad')
//                    ,'Units' => $this->input->post('units')
//                    ,'HonorRecieved' => $this->input->post('honors')
//                    ,'EducationFrom' => $this->input->post('educfrom')
//                    ,'EducationTo' => $this->input->post('educto')
//                    ,'modified_by' => $this->session->userdata['logged_in']['username']
//                    ,'modified_date' => date("Y-m-d H:i:s"));

//       if($code){
//         $cond = array('EducNo' => $code);
//         if($this->cud_model->updateDB('EmployeeEducation',$data, $cond)){         
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB('EmployeeEducation',$data)){ 
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }

//   }

//   public function saveWorkXP()
//   {  
//       $code = $this->input->post('id');
//       $data = array('EmployeeNo' => $this->input->post('appid')
//                    ,'Designation' => $this->input->post('position')
//                    ,'Station' => $this->input->post('office')
//                    ,'Status' => $this->input->post('status')
//                    ,'Salary' => $this->input->post('salary')
//                    ,'ServiceDateFrom' => $this->input->post('datefrom')
//                    ,'ServiceDateTo' => $this->input->post('dateto')
//                    ,'SeparationDate' => $this->input->post('datecause')
//                    ,'SeparationCause' => $this->input->post('cause')
//                    ,'IsGovernment' => $this->input->post('isgovern')
//                    ,'modified_by' => $this->session->userdata['logged_in']['username']
//                    ,'modified_date' => date("Y-m-d H:i:s"));

//       if($code){
//         $cond = array('SeriesNo' => $code);
//         if($this->cud_model->updateDB('EmployeeWorkXP',$data, $cond)){         
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB('EmployeeWorkXP',$data)){ 
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }

//   }

//   public function saveEligibility()
//   {  
//       $code = $this->input->post('id');
//       $data = array('EmployeeNo' => $this->input->post('empno')
//                    ,'EligCode' => $this->input->post('eligcode')
//                    ,'DateOfExam' => $this->input->post('examdate')
//                    ,'Rating' => $this->input->post('rating')
//                    ,'PlaceOfExam' => $this->input->post('examplace')
//                    ,'LicenseNo' => $this->input->post('licenseno')
//                    ,'ValidityDate' => $this->input->post('validdate')
//                    ,'modified_by' => $this->session->userdata['logged_in']['username']
//                    ,'modified_date' => date("Y-m-d H:i:s"));

//       if($code){
//         $cond = array('SeriesNo' => $code);
//         if($this->cud_model->updateDB('EmployeeEligibility',$data, $cond)){      
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB('EmployeeEligibility',$data)){ 
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }

//   }

//   public function saveReference()
//   {  
//       $code = $this->input->post('id');
//       $data = array('EmployeeNo' => $this->input->post('appid')
//                    ,'ReferenceName' => $this->input->post('refname')
//                    ,'Address' => $this->input->post('address')
//                    ,'ContactNo' => $this->input->post('contactno')
//                    ,'modified_by' => $this->session->userdata['logged_in']['username']
//                    ,'modified_date' => date("Y-m-d H:i:s"));
      
//       if($code){
//         $cond = array('SeriesNo' => $code);
//         if($this->cud_model->updateDB('EmployeeReference',$data, $cond)){         
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB('EmployeeReference',$data)){ 
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }

//   }

//   public function saveIndicator()
//   {  
//       $code = $this->input->post('id');
//       $data = array('IndicatorDesc' => $this->input->post('desc')
//                    ,'QualityFlag' => $this->input->post('qflag')
//                    ,'EnficiencyFlag' => $this->input->post('eflag')
//                    ,'TimelinessFlag' => $this->input->post('tflag')
//                    ,'Remarks' => $this->input->post('remarks')
//                    ,'modified_by' => $this->session->userdata['logged_in']['username']
//                    ,'modified_date' => date("Y-m-d H:i:s"));

//       if($code){
//         $cond = array('SeriesNo' => $code);
//         if($this->cud_model->updateDB('IPCRIndicator',$data, $cond)){      
//           echo json_encode(true);   
//         }
//         else{
//           echo json_encode(false);
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB('IPCRIndicator',$data)){ 
//           echo json_encode(true); 
//         }
//         else{
//           echo json_encode(false);
//         }
//       }

//   }

//   public function saveFunction()
//   {  
//       $code = $this->input->post('id');
//       $crd = array('Descr' => $this->input->post('desc'),
//                    'modified_by' => $this->session->userdata['logged_in']['username'],
//                    'modified_date' => date("Y-m-d H:i:s"),
//                    'IsDeleted' => 0);
      
//       if($code){
//         $cond = array('ParentCode' => $this->input->post('parentcode'), 'Code' => $code);
//         if($this->cud_model->updateDB('CodeReferenceDetail',$crd, $cond)){         
//           echo json_encode(true);  
//         }
//         else{
//           echo json_encode(false);
//         }
//       }
//       else{
//         $crd['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB_CodeReference($crd,'CF',false)){ 
//           echo json_encode(true); 
//         }
//         else{
//           echo json_encode(false);
//         }
//       }

//   }


//   public function changeLockPDSflag()
//   {  
//       $empid = $this->input->post('empid');
//       $data = array('lockPDSflag' => $this->input->post('bool'));

//       $action = '';
//       if($this->input->post('bool')=='1'){
//         $action = 'Locked';
//       }
//       else{
//         $action = 'Unlocked';
//       }
      
//       $cond = array('EmployeeNo' => $empid);
//       if($this->cud_model->updateDB('Employees',$data, $cond)){  
//         //// this is to insert into Log table ////
//         $ip = $_SERVER['REMOTE_ADDR'];
//         $reviewquery = "Execute usp_InsertLog '".$action." PDS of ".$empid."','ONLINE PDS','".$this->session->userdata['logged_in']['username']."','".$ip."'";
//         $this->cud_model->ExecuteQuery($reviewquery);       
//         echo json_encode(true);
//       }
//       else{
//         echo json_encode(false);
//       }

//   }

//   public function changeReviewPDSflag()
//   {  
//       $empid = $this->input->post('empid');
      
//       //// this is to insert into Log table ////
//       $ip = $_SERVER['REMOTE_ADDR'];
//       $reviewquery = "Execute usp_ReviewPDS '".$empid."','".$this->session->userdata['logged_in']['username']."','".$ip."','ONLINE PDS','".$this->input->post('bool')."'";

//       if($this->cud_model->ExecuteQuery($reviewquery)){  
//         echo json_encode(true);
//       }
//       else{
//         echo json_encode(false);
//       }

//   }

//   public function saveSucessIndicator()
//   {  
//       $code = $this->input->post('id');
//       $data = array('FunctionCode' => $this->input->post('code')
//                    ,'SuccessIndicator' => $this->input->post('si')
//                    ,'ActAcc' => $this->input->post('aa')
//                    ,'Quality' => $this->input->post('rate_q')
//                    ,'Efficiency' => $this->input->post('rate_e')
//                    ,'Timeliness' => $this->input->post('rate_t')
//                    ,'Average' => $this->input->post('rate_a')
//                    ,'Remarks' => $this->input->post('remark')
//                    ,'modified_by' => $this->session->userdata['logged_in']['username']
//                    ,'modified_date' => date("Y-m-d H:i:s"));
      
//       if($code){
//         $cond = array('SeriesNo' => $code);
//         if($this->cud_model->updateDB('IPCRdetail',$data, $cond)){         
//           echo true; 
//         }
//         else{
//           echo false;
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         if($this->cud_model->insertDB('IPCRdetail',$data)){ 
//           echo true;
//         }
//         else{
//           echo false;
//         }
//       }

//   }

//   public function saveIPCR_Function()
//   {  
//       $code = $this->input->post('id');
//       $data = array('IPCRCode' => $this->input->post('ipcrcode')
//                    ,'FunctionID' => $this->input->post('functionid')
//                    ,'IPCRType' => $this->input->post('typeid')
//                    ,'modified_by' => $this->session->userdata['logged_in']['username']
//                    ,'modified_date' => date("Y-m-d H:i:s"));
      
//       if($code){
//         $cond = array('SeriesNo' => $code);
//         if($this->cud_model->updateDB('IPCRFunction',$data, $cond)){         
//           echo json_encode(true); 
//         }
//         else{
//           echo json_encode(false);
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         $functioncode = $this->cud_model->insertDB('IPCRFunction',$data, true);
//         $functiondata = array('FunctionCode' => $functioncode
//                              ,'SuccessIndicator' => 1
//                              ,'created_by' => $this->session->userdata['logged_in']['username']
//                              ,'modified_by' => $this->session->userdata['logged_in']['username']
//                              ,'modified_date' => date("Y-m-d H:i:s"));
//         if($this->cud_model->insertDB('IPCRdetail',$functiondata)){ 
//           echo json_encode(true);
//         }
//         else{
//           echo json_encode(false);
//         }
//       }

//   }

//   public function saveIPCR()
//   {  
//       $code = $this->input->post('id');
//       $data = array('EmployeeNo' => $this->input->post('empid')
//                    ,'PositionCode' => $this->input->post('poscode')
//                    ,'EmploymentStatus' => $this->input->post('employcode')
//                    ,'Office' => $this->input->post('office')
//                    ,'ImdSuprv' => $this->input->post('imdsuprv')
//                    ,'DepHead' => $this->input->post('dephead')
//                    ,'DateFrom' => $this->input->post('datefrom')
//                    ,'DateTo' => $this->input->post('dateto')
//                    ,'Remarks' => $this->input->post('remarks')
//                    ,'modified_by' => $this->session->userdata['logged_in']['username']
//                    ,'modified_date' => date("Y-m-d H:i:s"));
      
//       if($code){
//         $cond = array('IPCRCode' => $code);
//         if($this->cud_model->updateDB('IPCRHistory',$data, $cond)){         
//           echo json_encode(true); 
//         }
//         else{
//           echo json_encode(false);
//         }
//       }
//       else{
//         $data['created_by'] = $this->session->userdata['logged_in']['username'];
//         $ipcrcode = $this->cud_model->insertDB('IPCRHistory',$data, true);
//         if($ipcrcode){ 
//           echo json_encode($ipcrcode);
//         }
//         else{
//           echo json_encode(false);
//         }
//       }

//   }

//   public function saveFacilitator()
//   {  
//       $data = $this->input->post('row');
//       $type = $this->input->post('type');

//       $datatrans = array('FaciName' => $data['FaciName'], 
//                          'Remarks' => $data['Remarks'],
//                          'modified_by' => $this->session->userdata['logged_in']['username'],
//                          'modified_date' => date("Y-m-d H:i:s"));    

//       if($type!='0'){ //means update
//           $cond = "SeriesNo = '".$data['SeriesNo']."'";
//           if($this->cud_model->updateDB('TrainingFacilitator',$datatrans,$cond)){ 
//             echo json_encode(true); 
//           }
//           else{
//             echo json_encode(false); 
//           }
//       }
//       else{
//           $datatrans['created_by'] = $this->session->userdata['logged_in']['username'];
//           if($this->cud_model->insertDB('TrainingFacilitator',$datatrans)){ 
//             echo json_encode(true); 
//           }
//           else{
//             echo json_encode(false); 
//           }
//       }

//   }

//   public function copy_IPCR()
//   {
//     $query = "Execute usp_IPCR_copy '".$this->input->post('id')."','".$this->input->post('copyid')."','".$this->session->userdata['logged_in']['username']."'";
//     if($this->cud_model->ExecuteQuery($query))
//     {
//       echo json_encode(true);
//     }
//     else
//       echo json_encode(false);
//   }

//   public function add_IPCRconstants()
//   {
//     $query = "Execute usp_IPCR_addConstants '".$this->input->post('id')."','".$this->session->userdata['logged_in']['username']."'";
//     if($this->cud_model->ExecuteQuery($query))
//     {
//       echo json_encode(true);
//     }
//     else
//       echo json_encode(false);
//   }

//   // DELETE // start
//   public function deleteDB_IPCR_Indicator()
//   {
//     $cond = array('SeriesNo' => $this->input->post('id'));
//     if($this->cud_model->deleteDB('IPCRdetail',$cond))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

//   public function deleteDB_IPCR_Function()
//   {
//     $cond = array('SeriesNo' => $this->input->post('id'));
//     if($this->cud_model->deleteDB('IPCRFunction',$cond))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

//   public function deleteDB_Indicator() // maintenance
//   {
//     $cond = array('SeriesNo' => $this->input->post('id'));
//     if($this->cud_model->deleteDB('IPCRIndicator',$cond))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

//   public function deleteDB_CodeReference()
//   {
//     $cond = array('ParentCode' => $this->input->post('parentcode'),'Code' => $this->input->post('id'));
//     if($this->cud_model->deleteDB('CodeReferenceDetail',$cond))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

//   public function deleteEmployee_Education()
//   {
//     if($this->cud_model->deleteDB('EmployeeEducation',"EducNo='".$this->input->post('id')."'"))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

//   public function deleteEmployee_WorkXP()
//   {
//     if($this->cud_model->deleteDB('EmployeeWorkXP',"SeriesNo='".$this->input->post('id')."'"))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

//   public function deleteEmployee_Eligibility()
//   {
//     if($this->cud_model->deleteDB('EmployeeEligibility',"SeriesNo='".$this->input->post('id')."'"))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

//   public function deleteEmployee_Family()
//   {
//     if($this->cud_model->deleteDB('EmployeeRelative',"SeriesNo='".$this->input->post('id')."'"))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

//   public function deleteEmployee_Reference()
//   {
//     if($this->cud_model->deleteDB('EmployeeReferences',"SeriesNo='".$this->input->post('id')."'"))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

//   public function deleteEmployee_Organization()
//   {
//     if($this->cud_model->deleteDB('EmployeeOrganization',"OrgNo='".$this->input->post('id')."'"))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

//   public function deleteEmployee_Training()
//   {
//     if($this->cud_model->deleteDB('TrainingAttendance',"Seriesno='".$this->input->post('id')."'"))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

//   public function deleteEmployee_Skill()
//   {
//     if($this->cud_model->deleteDB('EmployeeSkills',"SkillNo='".$this->input->post('id')."'"))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

//   public function deleteEmployee_Recognition()
//   {
//     if($this->cud_model->deleteDB('EmployeeRecognition',"Seriesno='".$this->input->post('id')."'"))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

//   public function deleteLMS_Signatory()
//   {
//     $cond = array('SeriesNo' => $this->input->post('id'));
//     if($this->cud_model->deleteDB_LMS('Signatory',$cond))
//     {
//       echo true;
//     }
//     else
//       echo false;
//   }

  // DELETE // end

}
