<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userchat extends MY_Controller {

  public $layout_view = 'layout/indexpage';

  function __construct() {
      parent::__construct();
      date_default_timezone_set('Asia/Manila');
      
      if ( ! $this->session->userdata('logged_in')){ 
        $this->session->set_flashdata('failed', 'Oops! You need to login.');
        redirect('login');
      }     

   }

  public function homepage()
  {
    $this->data['session'] = $this->session->userdata('logged_in');
    $this->data['datetime'] = $this->userchat_model->getChat_ServerDT();
    
    echo json_encode($this->data);
  }

/* get CHAT*/

  public function getChatHome()
  {
      $cond = " (readFlag = 1 or (readSenderFlag = 1 and readFlag = 0)) 
                and (msgFrom = '".$this->session->userdata['logged_in']['username']."' or msgTo = '".$this->session->userdata['logged_in']['username']."')"; 


      $usercond = ' c.invisibleFlag = 0 ' ;
      $this->data['user'] = $this->userchat_model->getChat_LoadUser($usercond);
      // $this->data['chat'] = $this->userchat_model->getLMS_LeaveUserChat($cond);
      $this->data['chat'] = $this->userchat_model->getChat_UserChat($this->session->userdata['logged_in']['username']);
      $this->data['status'] = $this->userchat_model->getChat_Status();
      $this->data['userstatus'] = $this->userchat_model->getChat_UserStatus($this->session->userdata['logged_in']['username']);
      $this->data['alluserstat'] = $this->userchat_model->getChat_AllUserStatus();
      echo json_encode($this->data);
  }

  public function getUserList()
  {
      $cond = ' c.invisibleFlag = 0 ' ;
      $like = $this->input->post('like') ? $this->input->post('like') : '';
      $this->data['user'] = $this->userchat_model->getChat_LoadUser($cond,$like);
      echo json_encode($this->data);
  }

  public function getChatMoreChat()
  {
      $this->data['chat'] = $this->userchat_model->getChat_UserChat($this->session->userdata['logged_in']['username'],
                                                                $this->input->post('lastdate'),
                                                                $this->input->post('msgto'));
      echo json_encode($this->data);
  }

  public function getChatLog($receiver)
  {

      $cond = "";   

      if($receiver){
        $cond = " ((msgTo = '".$this->session->userdata['logged_in']['username']."' and msgFrom = '".$receiver."') "
              . " or (msgTo = '".$receiver."' and msgFrom = '".$this->session->userdata['logged_in']['username']."')) ";
      }

      $this->data['chat'] = $this->userchat_model->getLMS_LeaveUserChat($cond);
      echo json_encode($this->data);
  }

  public function getUnreadChatLog($sender='')
  {

      $cond1 = "readFlag = 0 and (msgTo = '".$this->session->userdata['logged_in']['username']."'";  
      $cond2 = "";    

      if($sender){
        // $cond = $cond . " (readSenderFlag = 0 and (msgFrom = '".$this->session->userdata['logged_in']['username']."' and msgTo = '".$sender."')) 
        //                or (readFlag = 0 and (msgTo = '".$this->session->userdata['logged_in']['username']."' and msgFrom = '".$sender."')) ";

        $cond1 = $cond1 . " and msgFrom = '".$sender."' ";
        // $cond2 = $cond2 . " readSenderFlag = 0 and (msgFrom = '".$this->session->userdata['logged_in']['username']."' and msgTo = '".$sender."') ";
      }

        $cond1 = $cond1 . ") ";

      // $this->data['chat'] = $this->userchat_model->getLMS_LeaveUserChat($cond);


      $this->data['chat'] = $this->userchat_model->getLMS_LeaveUserChat($cond1);
      $this->data['alluserstat'] = $this->userchat_model->getChat_AllUserStatus();
      // $this->data['newchat'] = $this->userchat_model->getLMS_LeaveUserChat($cond2);
      echo json_encode($this->data);
  }
/* end CHAT*/


/*cud CHAT*/
  public function sendChatToUser()
  { 
    $msg = str_replace("'","''",$this->input->post('msg'));
    if($this->userchat_model->getChat_isUserAvailable($this->input->post('msgto'))){
      if($this->userchat_model->saveNewChat($msg)){
        $result['msg'] = 'Successfull';
        $result['return'] = true;
        echo json_encode($result);
      }
      else{
        $result['msg'] = 'Somehting went wrong. Please try again later.';
        $result['return'] = false;
        echo json_encode($result);
      }
    }
    else{
        $result['msg'] = 'User is unavailabe. Please try again later.';
        $result['return'] = false;
        echo json_encode($result);
    }
  }

  public function readUnreadUserChat()
  {  
      $cond = "";   

      if($this->input->post('rcvr')){
        $cond = $cond . "readSenderFlag = 1 and readFlag = 0 
                        and (msgTo = '".$this->session->userdata['logged_in']['username']."' and msgFrom = '".$this->input->post('rcvr')."') ";
      }

      if($this->userchat_model->updateChat_ReadFlag($cond))
      {
        echo true;
      }
      else
        echo false;
  }

  public function readSenderNewChat()
  {  
      $cond = "";   

      if($this->input->post('rcvr')){
        $cond = $cond . " readSenderFlag = 0 and (msgFrom='".$this->session->userdata['logged_in']['username']."' and msgTo='".$this->input->post('rcvr')."') ";
      }

      if($this->userchat_model->updateChat_SenderReadFlag($cond))
      {
        echo true;
      }
      else
        echo false;
  }

  public function changeSenderStatus()
  {  
      if($this->userchat_model->updateChat_SenderStatus($this->input->post('statid')))
      {
        echo true;
      }
      else
        echo false;
  }
/*end cud CHAT*/



}
  