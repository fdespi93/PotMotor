<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get extends MY_Controller {

  public $layout_view = 'layout/indexpage';

  function __construct() {
      parent::__construct();
      // if (!($this->session->userdata('store_store_logged_in'))){ 
      //   $this->session->set_flashdata('failed', 'Oops! You need to login.');
      //   redirect('login');
      // }   
   }

  public function getDB_Data($table,$field,$cond="",$sort="",$sorting="ASC")
  {
      $this->data['data'] = $this->read_model->getDB($table,$field,$cond,$sort,$sorting);
      echo json_encode($this->data);
  }

  public function homepage()
   {
    $this->data['session'] = $this->session->userdata['store_logged_in'];
    
    // var_dump($this->session->userdata['store_logged_in']);
    echo json_encode($this->data);
  }

  //LIST//
  public function getInventoryHome()
  {
      $this->data['inventory'] = $this->read_model->getAll_Product();
      $this->data['coderef'] = $this->read_model->getAll_CodeReference();
      echo json_encode($this->data);
  }

  public function getMntncHome()
  {
      $this->data['mntnc'] = $this->read_model->getAll_CodeReference();
      echo json_encode($this->data);
  }

  public function getProduct($id='')
  {   
      if($id!=''){
        $id = "'a.ProductID = ''".$id."'''";
      }

      $this->data['prod'] = $this->read_model->getAll_Product($id);
      echo json_encode($this->data);
  }

  public function getReport_Sales()
  {
      $this->data['sales'] = $this->read_model->getAll_Sales();
      echo json_encode($this->data);
  }

  public function getUsers()
  {
      $this->data['user'] = $this->read_model->getAll_Users();

      $query = "Execute GetEmployeeNameList '" . $this->session->userdata['store_logged_in']['username'] . "'";
      $this->data['employees'] = $this->cud_model->ExecuteQuery_RetList($query);
      echo json_encode($this->data);
  }

  public function getAccessDetail($id="")
  {
      $accessmodule = "select b.id as Code, b.moduledesc as name
                       from StoreModuleRights as a
                       inner join StoreModule as b on b.id = a.ModuleID
                       where UserID = '" . $id . "'";

      $this->data['useraccess_module'] = $this->cud_model->ExecuteQuery_RetList($accessmodule);

      $this->data['module'] = $this->read_model->getDB('StoreModule', "id as Code, moduledesc as name");
      echo json_encode($this->data);
  }



















  // public function DTRlist($from,$to)
  // {
  //     $query = "Execute usp_LoadDTR_range '" .  $from . "', '" . $to . "'";
  //     $this->data['dtr'] = $this->cud_model->ExecuteQuery_BIO_RetList($query);
  //     echo json_encode($this->data);
  // }

  public function DTRlist()
  {
      //$cond = "convert(date,a.checktime) between '" .  $from . "' and '" . $to . "'";
      // $cond = "convert(date,a.checktime) between '" .  $this->input->post('datefrom') . "' and '" . $this->input->post('dateto') . "'";
      // $this->data['dtr'] = $this->read_model->getAllDTR($cond);
      $query = "Execute usp_LoadDTR_range '" .  $this->input->post('datefrom') . "', '" . $this->input->post('dateto') . "'";
      $this->data['dtr'] = $this->cud_model->ExecuteQuery_BIO_RetList($query);
      echo json_encode($this->data);
  }
  
  public function DTR_GPlist()
  {
      $cond = "convert(date,a.TransTime) between '" .  $this->input->post('datefrom') . "' and '" . $this->input->post('dateto') . "'";
      $this->data['dtr_gp'] = $this->read_model->getAll_GP_DTR($cond);
      echo json_encode($this->data);
  }

  public function getNotExistsDTR()
  {
      $query = "Execute usp_LoadDTR_range '" .  $this->input->post('datefrom') . "', '" . $this->input->post('dateto') . "'";
      $this->data['dtr'] = $this->cud_model->ExecuteQuery_BIO_RetList($query);

      $cond = "convert(date,a.TransTime) between '" .  $this->input->post('datefrom') . "' and '" . $this->input->post('dateto') . "'";
      $this->data['dtr_gp'] = $this->read_model->getAll_GP_DTR($cond);

      // $cond = "convert(date,a.checktime) between '" .  $this->input->post('datefrom') . "' and '" . $this->input->post('dateto') . "'";
      // $this->data['dtr'] = $this->read_model->getAllDTR($cond);
      echo json_encode($this->data);
  }

  public function getNotExistsLog()
  {
      $query = "Execute usp_getNotExist '".$this->input->post('datefrom')."','".$this->input->post('dateto')."'";
      $this->data['notexist'] = $this->cud_model->ExecuteQuery_BIO_RetList($query);
      // var_dump($this->data['notexist']);
      echo json_encode($this->data);
  }
  
  public function leaveapply()
  {
      $cond = "";
      $this->data['holi'] = $this->read_model->getAll_LMS_Holiday($cond);
      $this->data['ltype'] = $this->read_model->getAll_LMS_LeaveType($cond);
      $query = "Execute usp_EmployeeNameList '" . $this->session->userdata['store_logged_in']['username'] . "'";
      $this->data['employees'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);
      echo json_encode($this->data);
  }
  
  public function getLeaveAttachment($leavecode)
  {
      $this->data['attach'] = $this->read_model->getLMS_Attachment($leavecode);
      echo json_encode($this->data);
  }
  
  public function getLeaveAppliedDetail($leavecode)
  {
      $this->data['applied'] = $this->read_model->getLMS_AppliedDetail($leavecode);
      echo json_encode($this->data);
  }
  
  public function leaveapplication($leavecode="")
  {
      $cond = "";
      $this->data['fleave'] = $this->cud_model->ExecuteQuery_LMS_RetList("Select a.emp_no as EmployeeNo,
                                                                           a.fl_date as fldate
                                                                    From Force_Leave_Plots as a
                                                                    inner join DETAIL_LEAVE1 as b on b.employeeno = a.emp_no 
                                                                    where b.leave_id='" .$leavecode. "'");
      $this->data['holi'] = $this->read_model->getAll_LMS_Holiday($cond);
      $this->data['ltype'] = $this->read_model->getAll_LMS_LeaveType($cond);
      $query = "Execute usp_EmployeeNameList '" . $this->session->userdata['store_logged_in']['username'] . "'";
      $this->data['employees'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);
      
      // $this->data['leaveapp'] = $this->read_model->getDB_LMS('DETAIL_LEAVE1','*', "leave_id='" .$leavecode. "'");
      $leaverowsql = "select *
                      from DETAIL_LEAVE1 as a
                      left join StatusMaster as b on b.StatusDesc = a.leave_status
                      where leave_id='" .$leavecode. "'";
      $this->data['leaveapp'] = $this->cud_model->ExecuteQuery_LMS_RetList($leaverowsql);
      $this->data['leavedates'] = $this->read_model->getDB_LMS('LeaveDateApplied',
                                                                "convert(nvarchar,IsHalf) as isHalf,
                                                                 LeaveDate as thisdate,
                                                                 convert(nvarchar,IsHoli) as isHoli", 
                                                                "LeaveID='" .$leavecode. "'");

      $monetquery = "Execute usp_WebMonetAppliedDetail '".$leavecode."'";
      $this->data['monetdet'] = $this->cud_model->ExecuteQuery_LMS_RetList($monetquery);
      $this->data['attach'] = $this->read_model->getLMS_Attachment($leavecode);
      $this->data['applieddates'] = $this->read_model->getLMS_EmployeeAppliedDates("b.EmployeeNo = '".$this->data['leaveapp'][0]->EmployeeNo."' and b.leave_id<>'".$leavecode."'");

      $query = "Execute usp_WebLoad_AppliedDates_Category '".$this->data['leaveapp'][0]->EmployeeNo."','".$leavecode."'";
      $this->data['applieddates_cat'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);

      if(strpos($this->data['leaveapp'][0]->date_period, ',') == true){
        $this->data['oldleavedates'] = $this->cud_model->ExecuteQuery_LMS_RetList("Select 0 as isHalf,
                                                                                      Item as thisdate,
                                                                                      0 as isHoli  
                                                                                      from dbo.SplitDate('".$this->data['leaveapp'][0]->date_period."',',')");
      }
      else{
        $this->data['oldleavedates'] = $this->cud_model->ExecuteQuery_LMS_RetList("Select convert(nvarchar,'false') as isHalf,
                                                                                     '".$this->data['leaveapp'][0]->date_period."' as thisdate,
                                                                                        convert(nvarchar,'false') as isHoli");
      }
      
      echo json_encode($this->data);
  }
  
  public function getEmployeeBalance($empno,$leavecode="")
  {

      $this->data['fleave'] = $this->read_model->getDB_LMS('Force_Leave_Plots',
                                                              "emp_no as EmployeeNo,
                                                               fl_date as fldate", 
                                                              "emp_no='" .$empno. "'");
      $this->data['applieddates'] = $this->read_model->getLMS_EmployeeAppliedDates( "b.EmployeeNo = '".$empno."'");
      // $this->data['applieddates_cat'] = $this->read_model->getLMS_EmployeeAppliedDates_Category( "b.EmployeeNo = '".$empno."'");

      $query = "Execute usp_WebLoad_AppliedDates_Category '".$empno."','".$leavecode."'";
      $this->data['applieddates_cat'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);

      $cond = "EmployeeNo = '" . $empno . "'";
      $this->data['balance'] = $this->read_model->get_LMS_EmployeeBalance($cond);
      echo json_encode($this->data);
  }

  public function getLeaveApplicationDetail($leaveid)
  {

      // $queryhist = "Select leave_id as leavecode
      //                  ,app_action as stataction
      //                  ,approved_by as statactor
      //                  ,datename(month, approved_date) 
      //                   + right(convert(varchar(12), approved_date, 107), 9) 
      //                   + right(convert(varchar(32),approved_date,100),8) as statdate
      //                  ,b.StatColor as statcolor
      //                  ,a.histRemarks as statremarks
      //           From Leave_History as a
      //           left join StatusMaster as b on b.StatusDesc = a.app_action 
      //           where a.leave_id='".$leaveid."'
      //           order by approved_date DESC";
      // $this->data['stathist'] = $this->cud_model->ExecuteQuery_LMS_RetList($queryhist);
      $this->data['stathist'] = $this->read_model->getLMS_LeaveStatHistory($leaveid);
      
      $query = "Execute usp_GetLeaveApplication '" . $leaveid . "'";
      $this->data['leaverow'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);
      echo json_encode($this->data);
  }

  public function getLeaveApplication_History($leaveid)
  {
      $this->data['stathist'] = $this->read_model->getLMS_LeaveStatHistory($leaveid);
      echo json_encode($this->data);
  }

  public function getLeaveApplicationHome()
  {
      $queryrec = "Execute usp_WebLoadLeaveRecords '','',1,200,'" . $this->session->userdata['store_logged_in']['username'] . "', 0";
      // $querycount = "Execute usp_WebLoadLeaveRecords '','',1,200,'" . $this->session->userdata['store_logged_in']['username'] . "', 1";
      $querycountdet = "Execute usp_WebLoadLeaveRecordsCount '" . $this->session->userdata['store_logged_in']['username'] . "'";

      $this->data['leaveapps'] = $this->cud_model->ExecuteQuery_LMS_RetList($queryrec);
      // $this->data['leavecount'] = $this->cud_model->ExecuteQuery_LMS_RetList($querycount);
      $this->data['leavecountdet'] = $this->cud_model->ExecuteQuery_LMS_RetList($querycountdet);

      $this->data['signatory'] = $this->read_model->getLMS_Signatory();
      $this->data['approving'] = $this->read_model->getLMS_Signatory();
      $this->data['ltype'] = $this->read_model->getAll_LMS_LeaveType('');
      $this->data['stats'] = $this->read_model->getDB_LMS('StatusMaster','*');
      $this->data['office'] = $this->read_model->getDB_LMS('MST_Department','*','IsDeleted=0');

      $query1 = "EXECUTE usp_WebLoadLeaveStatus '" . $this->session->userdata['store_logged_in']['username'] . "'";
      $this->data['changestats'] = $this->cud_model->ExecuteQuery_LMS_RetList($query1);
      echo json_encode($this->data);
  }

  public function getLeaveApplicationCount()
  {
      $querycountdet = "Execute usp_WebLoadLeaveRecordsCount '" . $this->session->userdata['store_logged_in']['username'] . "'";
      $this->data['leavecountdet'] = $this->cud_model->ExecuteQuery_LMS_RetList($querycountdet);
      echo json_encode($this->data);
  }

  public function getLMSEmployeesHome()
  {
      $queryrec = "Execute usp_EmployeeNameList '" . $this->session->userdata['store_logged_in']['username'] . "'";
      $this->data['employees'] = $this->cud_model->ExecuteQuery_LMS_RetList($queryrec);
      $this->data['office'] = $this->read_model->getDB_LMS('MST_Department','*','IsDeleted=0');
      $this->data['signatory'] = $this->read_model->getLMS_Signatory();
      $this->data['signatory2'] = $this->read_model->getLMS_Signatory();
      echo json_encode($this->data);
  }

  public function getEmployeesHome()
  {
      $queryrec = "Execute usp_WebLoad_EmployeeNameList '" . $this->session->userdata['store_logged_in']['username'] . "'";
      $this->data['employees'] = $this->cud_model->ExecuteQuery_RetList($queryrec);
      $this->data['office'] = $this->read_model->getDB_AllOffice();
      // $this->data['signatory'] = $this->read_model->getLMS_Signatory();
      // $this->data['signatory2'] = $this->read_model->getLMS_Signatory();
      echo json_encode($this->data);
  }

  public function getEmployeesIPCRlist()
  {
      $queryrec = "Execute usp_WebLoad_IPCRlist '" . $this->session->userdata['store_logged_in']['username'] . "'";
      $this->data['ipcr'] = $this->cud_model->ExecuteQuery_RetList($queryrec);
      $this->data['office'] = $this->read_model->getDB('Department','*','IsDeleted=0');
      // $this->data['signatory2'] = $this->read_model->getLMS_Signatory();
      echo json_encode($this->data);
  }

  public function getEmployeesIPCR($ipcrcode)
  {
      $queryrec = "Execute usp_WebLoad_IPCR '".$this->session->userdata['store_logged_in']['username']."','".$ipcrcode."'";
      $this->data['employeeipcr'] = $this->cud_model->ExecuteQuery_RetList($queryrec);
      $this->data['office'] = $this->read_model->getDB('Department','*','IsDeleted=0');
      $this->data['header'] = $this->read_model->getDB_IPCRheader($ipcrcode);
      $this->data['indicator'] = $this->read_model->getDB_AllSuccessIndicator();
      $this->data['function'] = $this->read_model->getAllFunction();
      $this->data['functiontype'] = $this->read_model->getAllFunctionType();
      $this->data['typemaster'] = $this->read_model->getAllTypeMaster();
      echo json_encode($this->data);
  }

  public function getEmployeesPreviewIPCR($ipcrcode)
  {
      $queryrec = "Execute usp_WebLoad_IPCR '".$this->session->userdata['store_logged_in']['username']."','".$ipcrcode."'";
      $this->data['employeeipcr'] = $this->cud_model->ExecuteQuery_RetList($queryrec);
      echo json_encode($this->data);
  }

  public function getHome_AddIPCR()
  {
      $queryrec = "Execute usp_WebLoad_IPCRemployee '".$this->session->userdata['store_logged_in']['username']."'";
      $this->data['employee'] = $this->cud_model->ExecuteQuery_RetList($queryrec);
      // $this->data['office'] = $this->read_model->getDB('Department','*','IsDeleted=0');
      // $this->data['header'] = $this->read_model->getDB_IPCRheader($ipcrcode);
      // $this->data['indicator'] = $this->read_model->getDB_AllSuccessIndicator();
      // $this->data['function'] = $this->read_model->getAllFunction();
      // $this->data['functiontype'] = $this->read_model->getAllFunctionType();
      // $this->data['typemaster'] = $this->read_model->getAllTypeMaster();
      echo json_encode($this->data);
  }

  public function getUserNotif($user="")
  {
      $condtxt = "Module = 'txt' and IsDeleted = 0";
      $condnotif = "Module = 'notif' and IsDeleted = 0";

      if($user!='null'){
        $condtxt = $condtxt . " and userid = '" . $user . "'";
        $condnotif = $condnotif . " and userid = '" . $user . "'";
      }

      $this->data['usernotif_txt'] = $this->read_model->getDB_LMS('UserAccessDetail','AccessName as Code, AccessName as name',$condtxt);
      $this->data['usernotif_online'] = $this->read_model->getDB_LMS('UserAccessDetail','AccessName as Code, AccessName as name',$condnotif);
      $this->data['status'] = $this->read_model->getDB_LMS('StatusMaster','StatusDesc as Code, StatusDesc as name');
      echo json_encode($this->data);
  }
  
  public function getLeaveApplications()
  {
      $cri = '';
      $office = $this->input->post('office');
      $ltype = $this->input->post('leavetype');
      $lstat = $this->input->post('leavestat');
      $like = $this->input->post('searchlike');
      $first = $this->input->post('recfirst');
      $last = $this->input->post('reclast');
      $view = $this->input->post('view');


      if($office!='null'&&$office!=''){
        $cri = " a.agency = ''" . $office . "'' ";
      }

      if($ltype!='null'&&$ltype!=''){
        if($office!='null'&&$office!=''){$cri .= " and ";}
        $cri = " a.leave_type = ''" . $ltype . "'' ";
      }

      if($lstat!='null'&&$lstat!=''){
        if(($ltype!='null'&&$ltype!='')||($office!='null'&&$office!='')){$cri .= " and ";}
        $cri .= " a.leave_status = ''" . $lstat . "'' ";
      }

      if($like=='null'){  
        $like = '';
      }


      $queryrec = "Execute usp_WebLoadLeaveRecords '" . $cri . "','" . $like . "'," . $first . "," . $last . ",'" . $this->session->userdata['store_logged_in']['username'] . "', 0,".$view;
      // $querycount = "Execute usp_WebLoadLeaveRecords '" . $cri . "','" . $like . "'," . $first . "," . $last . ",'" . $this->session->userdata['store_logged_in']['username'] . "', 1,".$view;
      $querycountdet = "Execute usp_WebLoadLeaveRecordsCount '" . $this->session->userdata['store_logged_in']['username'] . "'";
      
      // var_dump($queryrec);
      $this->data['leaveapps'] = $this->cud_model->ExecuteQuery_LMS_RetList($queryrec);
      // $this->data['leavecount'] = $this->cud_model->ExecuteQuery_LMS_RetList($querycount);
      $this->data['leavecountdet'] = $this->cud_model->ExecuteQuery_LMS_RetList($querycountdet);
      echo json_encode($this->data);
  }

  // public function getLeaveApplications($office="",$ltype="",$lstat="",$like="",$first=1,$last=500)
  // {
  //     $cri = '';

  //     if($office!='null'){
  //       $cri = " a.agency = ''" . $office . "'' ";
  //     }

  //     if($ltype!='null'){
  //       if($office!='null'){$cri .= " and ";}
  //       $cri = " a.leave_type = ''" . $ltype . "'' ";
  //     }

  //     if($lstat!='null'){
  //       if($ltype!='null'||$office!='null'){$cri .= " and ";}
  //       $cri .= " a.leave_status = ''" . $lstat . "'' ";
  //     }

  //     if($like=='null'){  
  //       $like = '';
  //     }


  //     $queryrec = "Execute usp_WebLoadLeaveRecords '" . $cri . "','" . $like . "'," . $first . "," . $last . ",'" . $this->session->userdata['store_logged_in']['username'] . "', 0";
  //     $querycount = "Execute usp_WebLoadLeaveRecords '" . $cri . "','" . $like . "'," . $first . "," . $last . ",'" . $this->session->userdata['store_logged_in']['username'] . "', 1";

  //     var_dump($queryrec);
  //     $this->data['leaveapps'] = $this->cud_model->ExecuteQuery_LMS_RetList($queryrec);
  //     $this->data['leavecount'] = $this->cud_model->ExecuteQuery_LMS_RetList($querycount);
  //     echo json_encode($this->data);
  // }

  public function getLeaveApplication($leaveid="")
  {
      // $cond = "EmployeeNo = '" . $empno . "'";
      // $this->data['leaveapps'] = $this->read_model->get_LMS_EmployeeBalance($cond);

      $query = "Execute usp_GetLeaveApplication '" . $leaveid . "'";
      //$this->data['employees'] = $this->read_model->getAll_LMS_Employee($cond);
      $this->data['leaveapp'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);
      echo $this->data;
  }

  public function getLeaveBalances()
  {
      $query = "Execute usp_WebLoadLeaveCreditEmployee '" . $this->session->userdata['store_logged_in']['username'] . "'";

      $this->data['credits'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);
      $this->data['office'] = $this->read_model->getDB_LMS('MST_Department','*','IsDeleted=0');
      echo json_encode($this->data);
  }

  public function getForcedLeaves()
  {
      $query = "Execute usp_WebLoadForcedLeave '" . $this->session->userdata['store_logged_in']['username'] . "'";

      $this->data['fleave'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);
      $this->data['office'] = $this->read_model->getDB_LMS('MST_Department','*','IsDeleted=0');
      echo json_encode($this->data);
  }

  public function getLMSusers()
  {
      $this->data['lmsuser'] = $this->read_model->getDB_LMS('UserMaster','user_code, EmployeeNo, spec_group, usersname, password, 
                                                                          address, user_group, designation, position_level, 
                                                                          office_type, can_modify_AE, CanModifyFiling,  CanModifyValue, 
                                                                          CanAntiDate, IsHead, statchanger, remarks', 'IsDeleted=0');
      // $this->data['hrisuser'] = $this->read_model->getDB('UserMaster','*', 'IsDeleted=0');
      $this->data['usergroup'] = $this->read_model->getDB_LMS('UserGroupMaster','*');
      $query = "Execute usp_EmployeeNameList '" . $this->session->userdata['store_logged_in']['username'] . "'";
      $this->data['employees'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);
      $this->data['office'] = $this->read_model->getDB_LMS('MST_Department','*','IsDeleted=0');
      echo json_encode($this->data);
  }

  public function getEmployee($id)
  {
    $this->data['educlvl'] = $this->read_model->getAllEduclvl();
    $this->data['school'] = $this->read_model->getAllSchool();
    $this->data['degree'] = $this->read_model->getAllDegree();
    $this->data['elig'] = $this->read_model->getAllElig();
    $this->data['relation'] = $this->read_model->getCRD_Description('r');

    $this->data['position'] = $this->read_model->getDB_AllPosition();
    $this->data['office'] = $this->read_model->getDB_AllOffice();
    $this->data['status'] = $this->read_model->getDB_AllStatus();
    $this->data['tranch'] = $this->read_model->getDB_AllTranch();
    $this->data['sg'] = $this->read_model->getDB_AllSalaryGrade();

    $this->data['orgs'] = $this->read_model->getCRD_Description('org');
    $this->data['skills'] = $this->read_model->getCRD_Description('s');
    $this->data['recogs'] = $this->read_model->getCRD_Description('recog');
    $this->data['ldtype'] = $this->read_model->getCRD_Description('ld');

    $this->data['training'] = $this->read_model->getDB_AllTraining_Title();
    $this->data['venue'] = $this->read_model->getDB_AllTraining_Venue();
    $this->data['provider'] = $this->read_model->getDB_AllTraining_Provider();
    $this->data['facilitator'] = $this->read_model->getDB_AllTraining_Facilitator();

    $this->data['civilstat'] = $this->read_model->getAllCivilStat();
    $this->data['bloodtype'] = $this->read_model->getAllBloodType();
    
    $this->data['employee'] = $this->read_model->getDB('Employees','*',"employeeno='".$id."'");
    $this->data['aeducation'] = $this->read_model->getEmployee_Education($id);
    $this->data['aworkxp'] = $this->read_model->getEmployee_WorkXP($id);
    $this->data['aeligibility'] = $this->read_model->getEmployee_Eligibility($id);
    $this->data['family'] = $this->read_model->getEmployee_Family($id);
    $this->data['areference'] = $this->read_model->getEmployee_Reference($id);
    $this->data['org'] = $this->read_model->getEmployee_Organization($id);
    $this->data['train'] = $this->read_model->getEmployee_Training($id);
    $this->data['alltrain'] = $this->read_model->getDB_AllTraining_Schedule();
    $this->data['skill'] = $this->read_model->getEmployee_Skill($id);
    $this->data['recog'] = $this->read_model->getEmployee_Recognition($id);
    $this->data['other'] = $this->read_model->getEmployee_Other($id);
    echo json_encode($this->data);
  }

  public function getEmployee_Education($id)
  {
    $this->data['aeducation'] = $this->read_model->getEmployee_Education($id);
    echo json_encode($this->data);
  }

  public function getEmployee_AddEduc()
  {
    $this->data['educlvl'] = $this->read_model->getAllEduclvl();
    $this->data['school'] = $this->read_model->getAllSchool();
    $this->data['degree'] = $this->read_model->getAllDegree();
    echo json_encode($this->data);
  }

  public function getEmployee_WorkXP($id)
  {
    $this->data['aworkxp'] = $this->read_model->getEmployee_WorkXP($id);
    $this->data['position'] = $this->read_model->getDB_AllPosition();
    $this->data['office'] = $this->read_model->getDB_AllOffice();
    $this->data['status'] = $this->read_model->getDB_AllStatus();
    echo json_encode($this->data);
  }

  public function getEmployee_Eligibility($id)
  {
    $this->data['aeligibility'] = $this->read_model->getEmployee_Eligibility($id);
    echo json_encode($this->data);
  }

  public function getEmployee_Eligibility_Dates($id)
  {
    $this->data['eligdates'] = $this->read_model->getEmployee_Eligibility_Dates($id);
    echo json_encode($this->data);
  }

  public function getEmployee_Family($id)
  {
    $this->data['family'] = $this->read_model->getEmployee_Family($id);
    echo json_encode($this->data);
  }

  public function getEmployee_AddElig()
  {
    $this->data['elig'] = $this->read_model->getAllElig();
    echo json_encode($this->data);
  }

  public function getEmployee_AddRelative()
  {
    
    $this->data['relation'] = $this->read_model->getCRD_Description('r');
    echo json_encode($this->data);
  }

  public function getEmployee_Reference($id)
  {
    $this->data['areference'] = $this->read_model->getEmployee_Reference($id);
    echo json_encode($this->data);
  }

  public function getEmployee_Organization($id)
  {
    $this->data['org'] = $this->read_model->getEmployee_Organization($id);
    $this->data['orgs'] = $this->read_model->getCRD_Description('org');
    echo json_encode($this->data);
  }

  public function getEmployee_Training($id)
  {
    $this->data['train'] = $this->read_model->getEmployee_Training($id);
    $this->data['alltrain'] = $this->read_model->getDB_AllTraining_Schedule();
    
    $this->data['training'] = $this->read_model->getDB_AllTraining_Title();
    $this->data['venue'] = $this->read_model->getDB_AllTraining_Venue();
    $this->data['provider'] = $this->read_model->getDB_AllTraining_Provider();
    $this->data['facilitator'] = $this->read_model->getDB_AllTraining_Facilitator();
    echo json_encode($this->data);
  }

  public function getEmployee_Skill($id)
  {
    $this->data['skill'] = $this->read_model->getEmployee_Skill($id);
    $this->data['skills'] = $this->read_model->getCRD_Description('s');
    echo json_encode($this->data);
  }

  public function getEmployee_Recognition($id)
  {
    $this->data['recog'] = $this->read_model->getEmployee_Recognition($id);
    $this->data['recogs'] = $this->read_model->getCRD_Description('recog');
    echo json_encode($this->data);
  }

  public function getEmployee_Other($id)
  {
    $this->data['other'] = $this->read_model->getEmployee_Other($id);
    echo json_encode($this->data);
  }

  public function getEmployee_CPGRelative($id)
  {
    $this->data['arelative'] = $this->read_model->getAllApplicant_CPGRelative($id);
    echo json_encode($this->data);
  }

  public function getTraining_Reference($id)
  {
    $this->data['att'] = $this->read_model->getDB_Training_Attendance($id);
    echo json_encode($this->data);
  }

  public function getStatHistoryHome()
  {
      $this->data['status'] = $this->read_model->getDB_LMS('StatusMaster','*');
      echo json_encode($this->data);
  }

  public function getStatHistory()
  {
      $query = "Execute usp_WebLoadLeaveStatusHistory '".$this->input->post('stat')."','".$this->input->post('datefrom')."','".$this->input->post('dateto')."'";
      $this->data['stathist'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);
      // $this->data['stathist'] = $this->read_model->getDB_LMS('StatusMaster','*');
      echo json_encode($this->data);
  }

  public function getReport_Monetization()
  {
      $query = "Execute usp_WebLoadReport_Monetization '".$this->input->post('datefrom')."','".$this->input->post('dateto')."','0','','".$this->input->post('view')."'";
      $this->data['repmonet'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);
      echo json_encode($this->data);
  }

  public function getLMS_WOPadjustingEntry()
  {
      $query = "Execute usp_WebLoad_WOPadjustingEntry '','".$this->input->post('datefrom')."','".$this->input->post('dateto')."','0','','".$this->input->post('view')."'";
      $this->data['wopae'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);
      echo json_encode($this->data);
  }

  public function getMonetDetail($empno,$mlctype)
  {
      $cond = "emp_no = '" . $empno . "'";
      $this->data['lastaccu'] = $this->read_model->get_LMS_EmployeeLastAccu($cond);
      $query = "Execute usp_WebMonetizationDetail '".$empno."','".$mlctype."'";
      $this->data['monetdet'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);
      echo json_encode($this->data);
  }

  public function getLeaveTypeHome()
  {
      $this->data['ltype'] = $this->read_model->getLMS_AllLeaveType();
      echo json_encode($this->data);
  }

  public function getSignatoryHome()
  {
      $this->data['signatory'] = $this->read_model->getLMS_Signatory();
      echo json_encode($this->data);
  }

  public function getIndicatorHome()
  {
      $this->data['indicator'] = $this->read_model->getDB_AllSuccessIndicator();
      echo json_encode($this->data);
  }

  public function getFunctionHome()
  {
      $this->data['function'] = $this->read_model->getAllFunction();
      echo json_encode($this->data);
  }

  public function getMonetizationList()
  {
      $query = "Execute usp_WebLoadLeaveStatusHistory '".$this->input->post('stat')."','".$this->input->post('datefrom')."','".$this->input->post('dateto')."'";
      $this->data['stathist'] = $this->cud_model->ExecuteQuery_LMS_RetList($query);
      // $this->data['stathist'] = $this->read_model->getDB_LMS('StatusMaster','*');
      echo json_encode($this->data);
  }

  public function getHRIS_Training_Facilitator()
  {
      $this->data['faci'] = $this->read_model->getDB_AllTraining_Facilitator();
      echo json_encode($this->data);
  }


}
  