<?php
require_once (dirname(__FILE__) . "/URFAClient.php");

class URFAClient_Admin extends URFAClient
{
  /**
  * Возвращает объект URFAClient_User5 используя текущие настройки подключения
  *
  * @return URFAClient_User5
  */
  public function getURFAClient_User5($login, $pass, $ssl = true)
  {
    return new URFAClient_User5($login, $pass, $this->address, $this->port, $ssl);
  }
  function rpcf_get_doc_types_list() { //0x7024

    $ret=array();

    if (!$this->connection->urfa_call(0x7024)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $x = $this->connection->urfa_get_data();// Types count

    $count = $x->DataGetInt();

    $ret['count'] = $count;

    for ($i=0;$i<$count;$i++) {

      //                      $x = $this->connection->urfa_get_data();

      $type['doc_name']=$x->DataGetString();

      $type['id']=$x->DataGetInt();



      $ret['doc_types'][]=$type;

    }

    //              $this->connection->urfa_get_data();

    return $ret;

  }



  function rpcf_add_account($account,$user_id,$is_basic=1,$account_name='auto create account',$discount_period_id=0) { //0x2031

    if (!$this->connection->urfa_call(0x2031)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    if (!isset($account['int_status']))

    $account['int_status']=1;



    $packet = $this->connection->getPacket();

    $packet->DataSetInt($user_id);

    $packet->DataSetInt($is_basic);

    $packet->DataSetInt($account['is_blocked']);

    $packet->DataSetString($account_name);

    $packet->DataSetDouble($account['balance']);

    $packet->DataSetDouble($account['credit']);

    $packet->DataSetInt($discount_period_id);

    $packet->DataSetInt($account['dealer_account_id']);

    $packet->DataSetDouble($account['comission_coefficient']);

    $packet->DataSetDouble($account['default_comission_value']);

    $packet->DataSetInt($account['is_dealer']);

    $packet->DataSetDouble($account['vat_rate']);

    $packet->DataSetDouble($account['sale_tax_rate']);

    $packet->DataSetInt($account['int_status']);

    $this->connection->urfa_send_param($packet);

    if ($x = $this->connection->urfa_get_data()) {

      $ret=$x->DataGetInt();

      //        $x = $this->connection->urfa_get_data();

    }

    return $ret;

  }



  function rpcf_get_account_external_id($account_id) { //0x2039

    $ret=array();

    if (!$this->connection->urfa_call(0x2039)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($account_id);

    $this->connection->urfa_send_param($packet);

    if ($x = $this->connection->urfa_get_data()) {

      $external_id = $x->DataGetString();

    }

    //		$this->connection->urfa_get_data();

    return $external_id;

  }

  function rpcf_add_discount_period($id,$start,$expire,$periodic_type_t,$cd,$di) { //0x2603

    $ret=0;

    if (!$this->connection->urfa_call(0x2603)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($id);

    $packet->DataSetInt($start);

    $packet->DataSetInt($expire);

    $packet->DataSetInt($periodic_type_t);

    $packet->DataSetInt($cd);

    $packet->DataSetInt($di);

    $this->connection->urfa_send_param($packet);

    $this->connection->urfa_get_data();

  }

  function rpcf_edit_tariff($tariff_id,$tariff_name,$expire_date,$is_blocked,$balance_rollover) { //0x3013

    $ret=0;

    if (!$this->connection->urfa_call(0x3013)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($tariff_id);

    $packet->DataSetString($tariff_name);

    $packet->DataSetInt($expire_date);

    $packet->DataSetInt($is_blocked);

    $packet->DataSetInt($balance_rollover);

    $this->connection->urfa_send_param($packet);

    if ($x = $this->connection->urfa_get_data()){

      $ret = $x->DataGetInt();

      //			$this->connection->urfa_get_data();

    }

    return $ret;

  }

  function rpcf_add_discount_period_return($static_id,$start_date,$expire_date,$periodic_type,$custom_duration,$discount_interval) { //0x2605

    $ret=array();

    if (!$this->connection->urfa_call(0x2605)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($static_id);

    $packet->DataSetInt($start_date);

    $packet->DataSetInt($expire_date);

    $packet->DataSetInt($periodic_type);

    $packet->DataSetInt($custom_duration);

    $packet->DataSetInt($discount_interval);

    $this->connection->urfa_send_param($packet);

    if ($x = $this->connection->urfa_get_data()) {

      $ret['discount_period_id']=$x->DataGetInt();

      //			$this->connection->urfa_get_data();

    }

    return $ret;

  }

  function rpcf_get_groups_for_user($user_id) { //0x2550

    $ret=array();

    if (!$this->connection->urfa_call(0x2550)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($user_id);

    $this->connection->urfa_send_param($packet);

    $x = $this->connection->urfa_get_data();

    $count=$x->DataGetInt();

    $ret['count']=$count;

    for ($i=0;$i<$count;$i++) {

      $group['group_id']=$x->DataGetInt();

      $group['group_name']=$x->DataGetString();

      $ret['group'][]=$group;

    }

    //		$this->connection->urfa_get_data();

    return $ret;

  }

  function rpcf_add_group($group_id, $group_name) { //0x2401, 0x2402

    $ret=array();

    if (!$this->connection->urfa_call(0x2401)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($group_id);

    $packet->DataSetString($group_name);

    $this->connection->urfa_send_param($packet);

    $this->connection->urfa_get_data();



    return;

  }

  function rpcf_get_accountinfo($account_id) { //0x2030

    $ret=array();

    if (!$this->connection->urfa_call(0x2030)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($account_id);

    $this->connection->urfa_send_param($packet);

    if ($x = $this->connection->urfa_get_data()) {

      $ret['unused']=$x->DataGetInt();

      $ret['is_blocked']=$x->DataGetInt();

      $ret['dealer_account_id']=$x->DataGetInt();

      $ret['is_dealer']=$x->DataGetInt();

      $ret['vat_rate']=$x->DataGetDouble();

      $ret['sale_tax_rate']=$x->DataGetDouble();

      $ret['comission_coefficient']=$x->DataGetDouble();

      $ret['default_comission_value']=$x->DataGetDouble();

      $ret['credit']=$x->DataGetDouble();

      $ret['balance']=$x->DataGetDouble();

      $ret['int_status']=$x->DataGetInt();

      $ret['block_recalc_abon']=$x->DataGetInt();

      $ret['block_recalc_prepaid']=$x->DataGetInt();

      $ret['unlimited']=$x->DataGetInt();

      //			$this->connection->urfa_get_data();

    }

    return $ret;

  }

  function rpcf_add_group_to_user($user_id,$group_id) { //0x2552

    if (!$this->connection->urfa_call(0x2552)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($user_id);

    $packet->DataSetInt($group_id);

    $this->connection->urfa_send_param($packet);

    $this->connection->urfa_get_data();

  }

  function rpcf_get_groups_list($user_id=0) { //0x2400

    $ret=array();

    if (!$this->connection->urfa_call(0x2400)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($user_id);

    $this->connection->urfa_send_param($packet);

    $x = $this->connection->urfa_get_data();

    $count=$x->DataGetInt();

    $ret['count']=$count;

    for ($i=0;$i<$count;$i++) {

      //			$x = $this->connection->urfa_get_data();

      $group['group_id']=$x->DataGetInt();

      $group['group_name']=$x->DataGetString();

      $ret['group'][]=$group;

    }

    //		$this->connection->urfa_get_data();

    return $ret;

  }

  function rpcf_add_house($house) { //0x2811

    if (!$this->connection->urfa_call(0x2811)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt(isset($house['house_id']) ? $house['house_id'] : 0);

    $packet->DataSetInt(isset($house['connect_date']) ? $house['connect_date'] : time());



    foreach (array('post_code','country','region',

    'city','street','number','building') as $var)

    $packet->DataSetString(isset($house[$var]) ? $house[$var] : "");



    if (isset($house['ipzones'])) {

      $packet->DataSetInt(count($house['ipzones'])); # count

      foreach ($house['ipzones'] as $zone) {

        $packet->DataSetInt($zone);

      }

    }else {

      $packet->DataSetInt(1); # count

      $packet->DataSetInt(1); # zone

    }



    $this->connection->urfa_send_param($packet);

    $this->connection->urfa_get_data();



    return 0;

  }

  function rpcf_get_house($house_id) { // 0x2812

    $ret=array();

    if (!$this->connection->urfa_call(0x2812)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($house_id);

    $this->connection->urfa_send_param($packet);



    $x = $this->connection->urfa_get_data();

    $ret['house_id'] = $x->DataGetInt();

    $ret['connect_date'] = $x->DataGetInt();

    $ret['post_code'] = $x->DataGetString();

    $ret['country'] = $x->DataGetString();

    $ret['region'] = $x->DataGetString();

    $ret['city'] = $x->DataGetString();

    $ret['street'] = $x->DataGetString();

    $ret['number'] = $x->DataGetString();

    $ret['building'] = $x->DataGetString();

    $ret['count'] = $x->DataGetInt();

    for ($i=0;$i<$ret['count'];$i++) {

      #$x = $this->connection->urfa_get_data();

      $ipzone['ipzone_id'] = $x->DataGetInt();

      $ipzone['ipzone_name'] = $x->DataGetString();

      $ret['ipzones'][]=$ipzone;

    }

    $this->connection->urfa_get_data();

    return $ret;

  }



  При неправильно заданном house_id, может аварийно завершить главный процесс

  function rpcf_add_ipzone($zone_id, $zone_name, $subnets) { //0x2801

    $ret=array();

    if (!$this->connection->urfa_call(0x2801)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($zone_id);

    $packet->DataSetString($zone_name);

    $packet->DataSetInt(count($subnets)); # count

    foreach ($subnets as $val) {

      $packet->DataSetIPAddress($val['net']);

      $packet->DataSetIPAddress($val['mask']);

      $packet->DataSetIPAddress($val['gateway']);

    }

    $this->connection->urfa_send_param($packet);

    if ($x = $this->connection->urfa_get_data()) {

      $code=$x->DataGetInt(); #id

    }

    $this->connection->urfa_get_data();



    return $code;

  }

  function rpcf_get_houses_list() { //0x2810

    $ret=array();

    if (!$this->connection->urfa_call(0x2810)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $x = $this->connection->urfa_get_data();

    $count = $x->DataGetInt();

    $ret['count'] = $count;

    for ($i=0;$i<$count;$i++) {

      $house['house_id']=$x->DataGetInt();

      $house['ip_zone_id']=$x->DataGetInt();

      $house['connect_date']=$x->DataGetInt();

      $house['post_code']=$x->DataGetString();

      $house['country']=$x->DataGetString();

      $house['region']=$x->DataGetString();

      $house['city']=$x->DataGetString();

      $house['street']=$x->DataGetString();

      $house['number']=$x->DataGetString();

      $house['building']=$x->DataGetString();

      $ret['houses'][]=$house;

    }

    return $ret;

  }





  function rpcf_add_message($receiver_id,$subject,$message,$mime="text/plain",$is_for_all=0) { //0x5001

    if (!$this->connection->urfa_call(0x5001)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($receiver_id);

    $packet->DataSetString($subject);

    $packet->DataSetString($message);

    $packet->DataSetString($mime);

    $packet->DataSetInt($is_for_all);

    $this->connection->urfa_send_param($packet);

    $this->connection->urfa_get_data();

  }

  function rpcf_get_core_time() { //0x11112

    $ret=array();

    if (!$this->connection->urfa_call(0x11112)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }



    if ($x = $this->connection->urfa_get_data()){

      $ret['time']=$x->DataGetInt();

      $ret['tzname']=$x->DataGetString();

    }

    return $ret;

  }



  function rpcf_add_once_service_new($parent_id, $tariff_id, $service_id, $service_name, $comment, $link_by_default, $cost, $drop_by_group) { //0x2116

    if (!$this->connection->urfa_call(0x2116)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($parent_id);

    $packet->DataSetInt($tariff_id);

    $packet->DataSetInt($service_id);

    $packet->DataSetString($service_name);

    $packet->DataSetString($comment);

    $packet->DataSetInt($link_by_default);

    $packet->DataSetDouble($cost);

    $packet->DataSetInt($drop_by_group);

    $this->connection->urfa_send_param($packet);

    $this->connection->urfa_get_data();

  }

  function rpcf_edit_tclass($tclass_id, $tclass_name, $graph_color, $is_display, $is_fill, $time_range_id, $dont_save, $local_traf_policy, $tclass) { //0x2303

    if (!$this->connection->urfa_call(0x2303)) {

      print "Error calling function ". __FUNCTION__ ."\n";

      return FALSE;

    }

    $packet = $this->connection->getPacket();

    $packet->DataSetInt($tclass_id);

    $packet->DataSetString($tclass_name);

    $packet->DataSetInt($graph_color);

    $packet->DataSetInt($is_display);

    $packet->DataSetInt($is_fill);

    $packet->DataSetInt($time_range_id);

    $packet->DataSetInt($dont_save);

    $packet->DataSetInt($local_traf_policy);

    $packet->DataSetInt(count($tclass));

    for($i=0; $i	        $packet->DataSetIPAddress($tclass[$i]['saddr']);

    $packet->DataSetIPAddress($tclass[$i]['saddr_mask']);

    $packet->DataSetInt($tclass[$i]['sport']);

    $packet->DataSetInt($tclass[$i]['input']);

    $packet->DataSetIPAddress($tclass[$i]['src_as']);

    $packet->DataSetIPAddress($tclass[$i]['daddr']);

    $packet->DataSetIPAddress($tclass[$i]['daddr_mask']);

    $packet->DataSetInt($tclass[$i]['dport']);

    $packet->DataSetInt($tclass[$i]['output']);

    $packet->DataSetIPAddress($tclass[$i]['dst_as']);

    $packet->DataSetInt($tclass[$i]['proto']);

    $packet->DataSetInt($tclass[$i]['tos']);

    $packet->DataSetInt($tclass[$i]['nexthop']);

    $packet->DataSetInt($tclass[$i]['tcp_flags']);

    $packet->DataSetIPAddress($tclass[$i]['ip_from']);

    $packet->DataSetInt($tclass[$i]['use_sport']);

    $packet->DataSetInt($tclass[$i]['use_input']);

    $packet->DataSetInt($tclass[$i]['use_src_as']);

    $packet->DataSetInt($tclass[$i]['use_dport']);

    $packet->DataSetInt($tclass[$i]['use_output']);

    $packet->DataSetInt($tclass[$i]['use_dst_as']);

    $packet->DataSetInt($tclass[$i]['use_proto']);

    $packet->DataSetInt($tclass[$i]['use_tos']);

    $packet->DataSetInt($tclass[$i]['use_nexthop']);

    $packet->DataSetInt($tclass[$i]['use_tcp_flags']);

    $packet->DataSetInt($tclass[$i]['skip']);

  }

  $this->connection->urfa_send_param($packet);

  $this->connection->urfa_get_data();

  return 0;

}

function rpcf_add_once_service_to_user($user_id,$account_id,$service_id,$tplink,$slink_id,$discount_date,$quantity,$invoice_id) { //0x2555

  $ret=array();

  if (!$this->connection->urfa_call(0x2555)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $packet->DataSetInt($account_id);

  $packet->DataSetInt($service_id);

  $packet->DataSetInt($tplink);

  $packet->DataSetInt($slink_id);

  $packet->DataSetInt($discount_date);

  $packet->DataSetDouble($quantity);

  $packet->DataSetInt($invoice_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret['result']=$x->DataGetString();

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}



function rpcf_edit_user_new($user,$parameters) { //0x2126

  $ret = array();

  if (!$this->connection->urfa_call(0x2126)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }



  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user['user_id']);

  $packet->DataSetString($user['login']);

  $packet->DataSetString($user['password']);

  $packet->DataSetString($user['full_name']);

  $packet->DataSetInt($user['is_juridical']);

  $packet->DataSetString($user['jur_address']);

  $packet->DataSetString($user['act_address']);

  $packet->DataSetString($user['flat_number']);

  $packet->DataSetString($user['entrance']);

  $packet->DataSetString($user['floor']);

  $packet->DataSetString($user['district']);

  $packet->DataSetString($user['building']);

  $packet->DataSetString($user['passport']);

  $packet->DataSetInt($user['house_id']);

  $packet->DataSetString($user['work_tel']);

  $packet->DataSetString($user['home_tel']);

  $packet->DataSetString($user['mob_tel']);

  $packet->DataSetString($user['web_page']);

  $packet->DataSetString($user['icq_number']);

  $packet->DataSetString($user['tax_number']);

  $packet->DataSetString($user['kpp_number']);

  $packet->DataSetString($user['email']);

  $packet->DataSetInt($user['bank_id']);

  $packet->DataSetString($user['bank_account']);

  $packet->DataSetString($user['comments']);

  $packet->DataSetString($user['personal_manager']);

  $packet->DataSetInt($user['connect_date']);

  $packet->DataSetInt($user['is_send_invoice']);

  $packet->DataSetInt($user['advance_payment']);

  $packet->DataSetInt($user['switch_id']);

  $packet->DataSetInt($user['port_number']);

  $packet->DataSetInt($user['binded_currency_id']);

  $packet->DataSetInt(count($parameters));

  foreach ($parameters as $array_item){

    $packet->DataSetInt($array_item['id']);

    $packet->DataSetString($array_item['value']);

  }

  $this->connection->urfa_send_param($packet);

  $ret['user_id']=0;

  if($x = $this->connection->urfa_get_data()){

    $ret['user_id'] = $x->DataGetInt();

    $ret['error_msg'] = $x->DataGetString();

  }

  return $ret;

}





// Количество параметров уменьшено (Kayfolom)

function rpcf_add_payment_for_account($account_id,$payment,$payment_date,$burn_date,

$payment_method,$admin_comment='',$comment='',$payment_ext_number='') { //0x3110

  $ret=array();

  if (!$this->connection->urfa_call(0x3110)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $unused=0;

  $currency_id=810;

  $payment_to_invoice=0;

  $turn_on_inet=0;

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($account_id);

  $packet->DataSetInt($unused);

  $packet->DataSetDouble($payment);

  $packet->DataSetInt($currency_id);

  $packet->DataSetInt($payment_date);

  $packet->DataSetInt($burn_date);

  $packet->DataSetInt($payment_method);

  $packet->DataSetString($admin_comment);

  $packet->DataSetString($comment);

  $packet->DataSetString($payment_ext_number);

  $packet->DataSetInt($payment_to_invoice);

  $packet->DataSetInt($turn_on_inet);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret['payment_transaction_id']=$x->DataGetInt();

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcf_get_currency_list() { //0x2910

  $ret=array();

  if (!$this->connection->urfa_call(0x2910)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();

  $count = $x->DataGetInt();

  $ret['count']= $count;

  for ($i=0;$i<$count;$i++) {

    //			$x = $this->connection->urfa_get_data();

    $currency['id'] = $x->DataGetInt();

    $currency['currency_brief_name'] = $x->DataGetString();

    $currency['currency_full_name'] = $x->DataGetString();

    $currency['percent'] = $x->DataGetDouble();

    $currency['rates'] = $x->DataGetDouble();

    $ret['currency'][]=$currency;

  }

  //		$this->connection->urfa_get_data();

  return $ret;

}

function rpcf_get_ipgroup($ipgroup_id) { //0x2902

  $ret=array();

  if (!$this->connection->urfa_call(0x2902)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($ipgroup_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()){

    $ret['name']=$x->DataGetString();

    $ret['count']=$x->DataGetInt();

    for ($i=0;$i<$ret['count'];$i++) {

      $set['ip']=$x->DataGetIPAddress();

      $set['mask']=$x->DataGetIPAddress();

      $set['gateway']=$x->DataGetIPAddress();

      $ret['ipgroup'][]=$set;

    }

  }

  return $ret;

}



function rpcf_add_tariff($tariff_name,$expire_date,$is_blocked,$balance_rollover) { //0x3012

  if (!$this->connection->urfa_call(0x3012)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetString($tariff_name);

  $packet->DataSetInt($expire_date);

  $packet->DataSetInt($is_blocked);

  $packet->DataSetInt($balance_rollover);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()){

    $ret = $x->DataGetInt();

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}



function rpcf_add_to_ipgroup($id,$ip,$mask,$login="",$pass="",$mac="",$cid="") { //0x5200

  $ret=array();

  if (!$this->connection->urfa_call(0x5200)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($id);

  $packet->DataSetIPAddress($ip);

  $packet->DataSetIPAddress($mask);

  $packet->DataSetString($login);

  $packet->DataSetString($pass);

  $packet->DataSetString($mac);

  $packet->DataSetString($cid);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $code=$x->DataGetInt();

  }

  //		$this->connection->urfa_get_data();

  // -1 Error (bug in api.xml - 0)

  return $code;

}

function rpcf_get_dhs_report($param)

{ // 0x5015

  $ret=array();

  if (!$this->connection->urfa_call(0x5015))

  {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt( $param['user_id'] );

  $packet->DataSetInt( $param['account_id'] );

  $packet->DataSetInt( $param['apid'] );

  $packet->DataSetInt( $param['t_start'] );

  $packet->DataSetInt( $param['t_end'] );

  $this->connection->urfa_send_param($packet);

  if ( $x = $this->connection->urfa_get_data() )

  {

    $ret['dhs_log_size'] = $x->DataGetInt();

    for ( $i = 0; $i < $ret['dhs_log_size']; $i++ )

    {

      $ari = array();

      //$x = $this->connection->urfa_get_data(); //убрал, т.к. скрипт входил в бесконечный цикл

      $ari['id'] = $x->DataGetInt();

      $ari['account_id'] = $x->DataGetInt();

      $ari['slink_id'] = $x->DataGetInt();

      $ari['recv_date'] = $x->DataGetInt();

      $ari['last_update_date'] = $x->DataGetInt();

      $ari['Called_Station_Id'] = $x->DataGetString();

      $ari['Calling_Station_Id'] = $x->DataGetString();

      $ari['framed_ip'] = $x->DataGetIPAddress();

      $ari['nas_port'] = $x->DataGetInt();

      $ari['acct_session_id'] = $x->DataGetString();

      $ari['nas_port_type'] = $x->DataGetInt();

      $ari['uname'] = $x->DataGetString();

      $ari['service_type'] = $x->DataGetInt();

      $ari['framed_protocol'] = $x->DataGetInt();

      $ari['nas_ip'] = $x->DataGetIPAddress();

      $ari['nas_id'] = $x->DataGetString();

      $ari['acct_status_type'] = $x->DataGetInt();

      $ari['acct_inp_pack'] = $x->DataGetLong();

      $ari['acct_inp_oct'] = $x->DataGetLong();

      $ari['acct_inp_giga'] = $x->DataGetLong();

      $ari['acct_out_pack'] = $x->DataGetLong();

      $ari['acct_out_oct'] = $x->DataGetLong();

      $ari['acct_out_giga'] = $x->DataGetLong();

      $ari['acct_sess_time'] = $x->DataGetLong();

      $ari['acct_term_cause'] = $x->DataGetInt();

      $ari['total_cost'] = $x->DataGetDouble();

      $ret['dhs_log'][] = $ari;

    }

  }

  return $ret;

}

function rpcf_add_user_contact ( $contact ) { //by ssb ssb@bigmir.net



  if (!$this->connection->urfa_call( 0x2042) ) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($contact['user_id']);

  $packet->DataSetString($contact['descr']);//должность

  $packet->DataSetString($contact['reason']);

  $packet->DataSetString($contact['person']);//Полное имя

  $packet->DataSetString($contact['short_name']);//Сокращённое название

  $packet->DataSetString($contact['contact']);

  $packet->DataSetString($contact['email']);

  $packet->DataSetInt($contact['id_exec_man']);



  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret=$x->DataGetInt();

  } else {

    return -1;

  }

  return $ret;

}

function rpcf_get_ipgroups_list() { //0x2900

  $ret=array();

  if (!$this->connection->urfa_call(0x2900)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();

  $groups_count=$x->DataGetInt();

  $ret['groups_count']=$groups_count;

  for ($i=0;$i<$groups_count;$i++) {

    $x = $this->connection->urfa_get_data();

    $count=$x->DataGetInt();

    for($j=0; $j<$count;$j++) {

      //				$x = $this->connection->urfa_get_data();

      $group['id']=$x->DataGetInt();

      $group['ip']=$x->DataGetIPAddress();

      $group['mask']=$x->DataGetIPAddress();

      $group['mac']=$x->DataGetString();

      $group['login']=$x->DataGetString();

      $group['allowed_cid']=$x->DataGetString();

      $groups['group'][]=$group;

    }

    $groups['group_count']=$count;

    $ret['groups'][]=$groups;

    unset($groups);

  }

  //		$this->connection->urfa_get_data();

  return $ret;

}

function rpcf_add_user($user,$parameters) { //0x2005

  $ret=array();

  if (!$this->connection->urfa_call(0x2005)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user['user_id']);

  $packet->DataSetString($user['login']);

  $packet->DataSetString($user['password']);

  $packet->DataSetstring($user['full_name']);

  if ($user['user_id'] == 0){

    $unused = 0;

    $packet->DataSetInt($unused);

  }

  $packet->DataSetInt($user['is_juridical']);

  $packet->DataSetString($user['jur_address']);

  $packet->DataSetString($user['act_address']);

  $packet->DataSetString($user['flat_number']);

  $packet->DataSetString($user['entrance']);

  $packet->DataSetString($user['floor']);

  $packet->DataSetString($user['district']);

  $packet->DataSetString($user['building']);

  $packet->DataSetString($user['passport']);

  $packet->DataSetInt($user['house_id']);

  $packet->DataSetString($user['work_tel']);

  $packet->DataSetString($user['home_tel']);

  $packet->DataSetString($user['mob_tel']);

  $packet->DataSetString($user['web_page']);

  $packet->DataSetString($user['icq_number']);

  $packet->DataSetString($user['tax_number']);

  $packet->DataSetString($user['kpp_number']);

  $packet->DataSetString($user['email']);

  $packet->DataSetInt($user['bank_id']);

  $packet->DataSetString($user['bank_account']);

  $packet->DataSetString($user['comments']);

  $packet->DataSetString($user['personal_manager']);

  $packet->DataSetInt($user['connect_date']);

  $packet->DataSetInt($user['is_send_invoice']);

  $packet->DataSetInt($user['advance_payment']);

  $packet->DataSetInt(count($parameters));

  foreach ($parameters as $array_item){

    $packet->DataSetInt($array_item['id']);

    $packet->DataSetString($array_item['value']);

  }

  $this->connection->urfa_send_param($packet);

  $ret['user_id']=0;

  if($x = $this->connection->urfa_get_data()){

    $z_user_id = $x->DataGetInt();

    $error_msg = $x->DataGetString();

    $ret['user_id']=$z_user_id;

    $ret['error_msg']=$error_msg;

    //          $x = $this->connection->urfa_get_data();

  }

  return $ret;

}



function rcpf_get_ipzone($zone_id) { //0x2802

  $ret=array();

  if (!$this->connection->urfa_call(0x2802)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($zone_id);

  $this->connection->urfa_send_param($packet);



  $x = $this->connection->urfa_get_data();

  $ret['name'] = $x->DataGetString();

  $ret['count'] = $x->DataGetInt();

  for ($i=0;$i<$ret['count'];$i++) {

    $x = $this->connection->urfa_get_data();

    $subnet['net']=$x->DataGetIPAddress();

    $subnet['mask']=$x->DataGetIPAddress();

    $subnet['gateway']=$x->DataGetIPAddress();

    $ret['subnets'][]=$subnet;

  }

  $this->connection->urfa_get_data();

  return $ret;

}

function rpcf_add_user_new($user,$parameters,$groups) { //0x2125

  $ret=array();

  if (!$this->connection->urfa_call(0x2125)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }



  $packet = $this->connection->getPacket();

  $packet->DataSetString($user['login']);

  $packet->DataSetString($user['password']);

  $packet->DataSetstring($user['full_name']);

  $packet->DataSetInt($user['is_juridical']);

  $packet->DataSetString($user['jur_address']);

  $packet->DataSetString($user['act_address']);

  $packet->DataSetString($user['flat_number']);

  $packet->DataSetString($user['entrance']);

  $packet->DataSetString($user['floor']);

  $packet->DataSetString($user['district']);

  $packet->DataSetString($user['building']);

  $packet->DataSetString($user['passport']);

  $packet->DataSetInt($user['house_id']);

  $packet->DataSetString($user['work_tel']);

  $packet->DataSetString($user['home_tel']);

  $packet->DataSetString($user['mob_tel']);

  $packet->DataSetString($user['web_page']);

  $packet->DataSetString($user['icq_number']);

  $packet->DataSetString($user['tax_number']);

  $packet->DataSetString($user['kpp_number']);

  $packet->DataSetString($user['email']);

  $packet->DataSetInt($user['bank_id']);

  $packet->DataSetString($user['bank_account']);

  $packet->DataSetString($user['comments']);

  $packet->DataSetString($user['personal_manager']);

  $packet->DataSetInt($user['connect_date']);

  $packet->DataSetInt($user['is_send_invoice']);

  $packet->DataSetInt($user['advance_payment']);

  $packet->DataSetInt($user['switch_id']);

  $packet->DataSetInt($user['port_number']);

  $packet->DataSetInt($user['binded_currency_id']);

  $packet->DataSetInt(count($parameters));

  foreach ($parameters as $array_item){

    $packet->DataSetInt($array_item['id']);

    $packet->DataSetString($array_item['value']);

  }

  $packet->DataSetInt(count($groups));

  foreach ($groups as $array_item){

    $packet->DataSetInt($array_item['value']);

  }

  $packet->DataSetInt($user['is_blocked']);

  $packet->DataSetDouble($user['balance']);

  $packet->DataSetDouble($user['credit']);

  $packet->DataSetDouble($user['vat_rate']);

  $packet->DataSetDouble($user['sale_tax_rate']);

  $packet->DataSetInt($user['int_status']);



  $this->connection->urfa_send_param($packet);

  $ret['user_id']=0;

  if($x = $this->connection->urfa_get_data()){

    $z_user_id = $x->DataGetInt();

    if ($z_user_id == 0) {

      $error_code = $x->DataGetInt();

      $error_msg = $x->DataGetString();

    }

    if ($z_user_id != 0) {

      $account_id = $x->DataGetInt();



    }

    $ret['user_id']=$z_user_id;

    $ret['error_msg']=$error_msg;

    $ret['error_code']=$error_code;

    $ret['basic_account']=$account_id;

    //	   $this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcf_get_dialup_service($sid) { // 0x210c



  $ret=array();

  if (!$this->connection->urfa_call(0x210c)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }



  $packet = $this->connection->getPacket();

  $packet->DataSetInt($sid);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret['service_name']=$x->DataGetString();

    $ret['comment']=$x->DataGetString();

    $ret['link_by_default']=$x->DataGetInt();

    $ret['is_dynamic']=$x->DataGetInt();

    $ret['cost']=$x->DataGetDouble();

    $ret['pm_every_day']=$x->DataGetInt();

    $ret['discount_method']=$x->DataGetInt();

    $ret['start_date']=$x->DataGetInt();

    $ret['expire_date']=$x->DataGetInt();

    $ret['pool_name']=$x->DataGetString();

    $ret['max_timeout']=$x->DataGetInt();

    $ret['null_service_prepaid']=$x->DataGetInt();

    $ret['radius_sessions_limit']=$x->DataGetInt();

    $ret['login_prefix']=$x->DataGetString();

    $ret['cost_size']=$x->DataGetInt();

    for($i=0;$i<$ret['cost_size'];$i++) {

      $ret['cost'][$i]['tr_time'] = $x->DataGetString();

      $ret['cost'][$i]['param'] = $x->DataGetDouble();

      $ret['cost'][$i]['id'] = $x->DataGetInt();

    }

    $ret['is_parent_id'] = $x->DataGetInt();

    $ret['tariff_id'] = $x->DataGetInt();

    $ret['parent_id'] = $x->DataGetInt();

  }

  return $ret;

}

function rpcf_block_account($account_id, $block) { //0x2037

  $ret=array();

  if (!$this->connection->urfa_call(0x2037)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($account_id);

  $packet->DataSetInt($block);

  $this->connection->urfa_send_param($packet);

  $this->connection->urfa_get_data();

}

function rpcf_get_discount_period($period_id) { //0x2602

  $ret=array();

  if (!$this->connection->urfa_call(0x2602)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($period_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret['start_date'] = $x->DataGetInt();

    $ret['end_date'] = $x->DataGetInt();

    $ret['periodic_type'] = $x->DataGetInt();

    $ret['custom_duration'] = $x->DataGetInt();

    $ret['discounts_per_week'] = $x->DataGetInt();

    $ret['next_discount_period_id'] = $x->DataGetInt();

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcf_blocks_report($user_id,$account_id,$group_id,$apid,$time_start,$time_end,$show_all=1){ //"0x3004

  if (!$this->connection->urfa_call(0x3004)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $packet->DataSetInt($account_id);

  $packet->DataSetInt($group_id);

  $packet->DataSetInt($apid);

  $packet->DataSetInt($time_start);

  $packet->DataSetInt($time_end);

  $packet->DataSetInt($show_all);

  $this->connection->urfa_send_param($packet);



  $ret=array();

  if ($x = $this->connection->urfa_get_data()) {

    $accounts_count=$x->DataGetInt();

    $ret['accounts_count']=$accounts_count;

    $account=array();

    for ($i=0; $i<$accounts_count; $i++) {

      //$x = $this->connection->urfa_get_data();

      $atr_size=$x->DataGetInt();

      $account['atr_size']=$atr_size;

      $blocks_report=array();

      for( $j=0; $j<$atr_size; $j++ ) {

        //$x = $this->connection->urfa_get_data();

        $blocks_report['account_id']	=$x->DataGetInt();

        $blocks_report['login']	=$x->DataGetString();

        $blocks_report['start_date']	=$x->DataGetInt();

        $blocks_report['expire_date']	=$x->DataGetInt();

        $blocks_report['what_blocked']=$x->DataGetInt();

        $blocks_report['block_type']	=$x->DataGetInt();

        $blocks_report['comment']	=$x->DataGetString();

        $account['blocks_report'][$j]=$blocks_report;

      }

      $ret['account'][$i]=$account;

    }

    //$x = $this->connection->urfa_get_data();

  }

  return $ret;

}



==== EXAMPLE ====

$s='';

try {

  $urfa_admin = new URFAClient_Admin(UTM5_login,UTM5_passwd);

  $report=$urfa_admin->rpcf_blocks_report($_SESSION['URFA']['user_id'],0,0,0,strtotime("-2 month"),strtotime("+1 day"),1);

} catch (Exception $exception) {

  echo "Error in line ", $exception->getLine();

  echo $exception->getMessage();

}

$cnt=$report['accounts_count'];

for ($i=0; $i<$cnt; $i++) {

  $account=$report['account'][$i];

  $atr_size=$account['atr_size'];

  if (0==$atr_size) {

    $s.='
    Отчёт о блокировках

    ';

    $s.='';

    $s.='';

    $s.='';

    $s.='';

    $s.='';

    $s.='';

  } else {

    $s.='
    Блокировки лицевого счёта '.($account['blocks_report']['0']['account_id']).'

    ';

    $s.='
    Дата	Причина блокировки
    '.date("d.m.Y H:i",time()).'	Блокировок не было
    ';

    $s.='';

    for ($j=$atr_size-1;$j>=0;$j--) {

      $r=$account['blocks_report'][$j];

      $start_date	=$r['start_date'];

      $expire_date	=$r['expire_date'];

      $block_type	=(1==$r['block_type'])?'Отрицательный баланс':'Заблокирован администратором';

      $s.='';

      $s.='';

      $s.='';

      $s.="";

      $s.='';

    }

  }

  $s.='
  Заблокирован	Разблокирован	Причина блокировки
  '.date("d.m.Y H:i",$start_date).'	'.((2000000000==$expire_date)?'':date("d.m.Y H:i",$expire_date)).'	$block_type
  ';

}

function rpcf_general_report_new($user_id=0,$account_id=0,$group_id=0,$discount_period_id=0,$start_date,$end_date) { //0x3020

  $ret=array();

  if (!$this->connection->urfa_call(0x3020)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $packet->DataSetInt($account_id);

  $packet->DataSetInt($group_id);

  $packet->DataSetInt($discount_period_id);

  $packet->DataSetInt($start_date);

  $packet->DataSetInt($end_date);

  $this->connection->urfa_send_param($packet);

  $x = $this->connection->urfa_get_data();

  $count=$x->DataGetInt();

  $ret['count']=$count;

  for ($i=0;$i<$count;$i++) {

    //			$x = $this->connection->urfa_get_data();

    $rep['account_id']=$x->DataGetInt();

    $rep['login']=$x->DataGetString();

    $rep['incoming_rest']=$x->DataGetDouble();

    $rep['discounted_once']=$x->DataGetDouble();

    $rep['discounted_periodic']=$x->DataGetDouble();

    $rep['discounted_iptraffic']=$x->DataGetDouble();

    $rep['discounted_hotspot']=$x->DataGetDouble();

    $rep['discounted_dialup']=$x->DataGetDouble();

    $rep['discounted_telephony']=$x->DataGetDouble();

    $rep['tax']=$x->DataGetDouble();

    $rep['discounted_with_tax']=$x->DataGetDouble();

    $rep['payments']=$x->DataGetDouble();

    $rep['outgoing_rest']=$x->DataGetDouble();

    $ret['report'][]=$rep;

  }

  //		$this->connection->urfa_get_data();

  return $ret;

}

function rpcf_cancel_payment_for_account($pay_t_id,$com_for_user='',$com_for_admin='') { //0x3111

  if (!$this->connection->urfa_call(0x3111)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($pay_t_id);

  $packet->DataSetString($com_for_user);

  $packet->DataSetString($com_for_admin);

  $this->connection->urfa_send_param($packet);

  $this->connection->urfa_get_data();

}

// Функция сделана для НОВОЙ реализации urfa_get_data()!!!



function rpcf_generate_doc_for_user($doc_type_id,$acc_id,$template_id) { //0x7030

  $ret=array();

  if (!$this->connection->urfa_call(0x7030)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet=$this->connection->getPacket();

  $packet->DataSetInt($doc_type_id);

  $packet->DataSetInt(0);

  $packet->DataSetInt($acc_id);

  $packet->DataSetInt($template_id);

  $this->connection->urfa_send_param($packet);

  if($x = $this->connection->urfa_get_data()){//

    $ret['template_id']=$x->DataGetInt();

    $ret['static_id']=$x->DataGetInt();

    if ($ret['static_id']!=0){



      $count = $x->DataGetInt();

      $ret['count'] = $count;

      for ($i=0;$i<$count;$i++) {

        $ret['text'][$i]=$x->DataGetString();

      }

      $ret['dynamic_landscape']=$x->DataGetInt();

    }else{



      $ret['dynamic_id']=$x->DataGetInt();

      $ret['count']=$x->DataGetInt();

      for ($i=0;$i<$ret['count'];$i++){

        $ret['text'][$i]=$x->DataGetString();

      }



      $ret['static_landscape']=$x->DataGetInt();

    }

  }

}

return $ret;

}



function rpcf_change_intstat_for_user($user_id,$block) { //0x2003

  if (!$this->connection->urfa_call(0x2003)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $packet->DataSetInt($block);

  $this->connection->urfa_send_param($packet);

  $this->connection->urfa_get_data();

}



function rpcf_get_ipzones_list() { //0x2800

  $ret=array();

  if (!$this->connection->urfa_call(0x2800)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();// count

  $count = $x->DataGetInt();

  $ret['count'] = $count;

  for ($i=0;$i<$count;$i++) {

    $x = $this->connection->urfa_get_data();

    $ipzone['zone_id']=$x->DataGetInt();

    $ipzone['zone_name']=$x->DataGetString();

    $ret['ipzones'][]=$ipzone;

  }

  $this->connection->urfa_get_data();

  return $ret;

}

function rpcf_core_build() { //0x0046

  $ret=array();

  if (!$this->connection->urfa_call(0x0046)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();

  $ret['core_build']=$x->DataGetString();

  //		$this->connection->urfa_get_data();

  return $ret;

}

function rpcf_get_new_secret($len=8) { //0x0060

  $ret=array();

  if (!$this->connection->urfa_call(0x0060)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($len);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret['error'] = $x->DataGetString();

    $ret['secret'] = $x->DataGetString();

    //			$this->connection->urfa_get_data();

  }

  // 0 Error

  return $ret;

}

function rpcf_core_version() { //0x0045

  $ret=array();

  if (!$this->connection->urfa_call(0x0045)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();

  $ret['core_version']=$x->DataGetString();

  //		$this->connection->urfa_get_data();

  return $ret;

}

function rpcf_get_once_service($sid){// 0x210a

  $ret=array();

  if (!$this->connection->urfa_call(0x210a)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($sid);

  $this->connection->urfa_send_param($packet);



  if ($x = $this->connection->urfa_get_data()) {

    $ret['name'] = $x->DataGetString();

    $ret['comment'] = $x->DataGetString();

    $ret['LinkByDefault'] = $x->DataGetInt();

    $ret['cost'] = $x->DataGetDouble();

    $ret['IsParentId'] = $x->DataGetInt();

    $ret['tariffId'] = $x->DataGetInt();

    $ret['parentId'] = $x->DataGetInt();

    $this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcs_del_router($router_id) { //0x5007



  if (!$this->connection->urfa_call(0x5007)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($router_id);

  $this->connection->urfa_send_param($packet);



}

function rpcf_edit_group($group_id, $group_name) { //0x2401, 0x2402

  $ret=array();

  if (!$this->connection->urfa_call(0x2402)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($group_id);

  $packet->DataSetString($group_name);

  $this->connection->urfa_send_param($packet);

  $this->connection->urfa_get_data();



  return;

}

function rpcf_delete_from_ipgroup_by_ipgroup($id,$ip,$mask) { //0x5102

  $ret=array();

  if (!$this->connection->urfa_call(0x5102)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($id);

  $packet->DataSetIPAddress($ip);

  $packet->DataSetIPAddress($mask);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $code=$x->DataGetInt();

  }

  //		$this->connection->urfa_get_data();

  // 0 Error

  return $code;

}

function rpcf_get_discount_periods() { //0x2600

  $ret=array();

  if (!$this->connection->urfa_call(0x2600)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();//Periods count

  $count = $x->DataGetInt();

  $ret['count']= $count;

  for ($i=0;$i<$count;$i++) {

    //			$x = $this->connection->urfa_get_data();

    $period['static_id']=$x->DataGetInt();

    $period['discount_period_id']=$x->DataGetInt();

    $period['start_date']=$x->DataGetInt();

    $period['end_date']=$x->DataGetInt();

    $period['periodic_type']=$x->DataGetInt();

    $period['custom_duration']=$x->DataGetInt();

    $period['next_discount_period_id']=$x->DataGetInt();

    $period['canonical_length']=$x->DataGetInt();

    $ret['discount_periods'][]=$period;

  }

  //		$this->connection->urfa_get_data();

  return $ret;

}

function rpcf_delete_from_ipgroup($slink_id,$ip,$mask="255.255.255.255") { //0x5101

  if (!$this->connection->urfa_call(0x5101)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($slink_id);

  $packet->DataSetIPAddress($ip);

  $packet->DataSetIPAddress($mask);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $code=$x->DataGetInt();

    //			$this->connection->urfa_get_data();

  } else {

    return -1; // invalid slink_id

  }

  // 0 delete error

  return $code;

}

function rpcf_get_periodic_service($service_id) { //0x2104

  $ret=array();

  if (!$this->connection->urfa_call(0x2104)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($service_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $service=array();

    $service['service_name']  = $x->DataGetString();

    $service['service_comment']  = $x->DataGetString();

    $service['link_by_default'] = $x->DataGetInt();

    $service['cost'] = $x->DataGetDouble();

    $service['deprecated'] = $x->DataGetInt();

    $service['discount_method'] = $x->DataGetInt();

    $service['start_date'] = $x->DataGetInt();

    $service['expire_date'] = $x->DataGetInt();

    $service['param'] = $x->DataGetInt();

    $service['tariff_id'] = $x->DataGetInt();

    $service['parent_id'] = $x->DataGetInt();

    $ret=$service;

  }

  return $ret;

}

function rpcf_delete_slink($slink_id) { //0x5100

  if (!$this->connection->urfa_call(0x5100)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($slink_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret=$x->DataGetInt();

    //			$this->connection->urfa_get_data();

  } else {

    return -1; // unable to delete service link

  }

  return $ret;

}



function rpcf_get_bytes_in_kb() { //0x10002

  $ret=array();

  if (!$this->connection->urfa_call(0x10002)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();

  $ret['bytes_in_kb']=$x->DataGetInt();

  //		$this->connection->urfa_get_data();

  return $ret;

}

function rpcf_get_all_services_for_user($account_id) { //0x2700

  $ret=array();

  if (!$this->connection->urfa_call(0x2700)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($account_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $count=$x->DataGetInt();

    $ret['count']=$count;

    for($i=0; $i<$count;$i++) {

      //				$x = $this->connection->urfa_get_data();

      $service['id'] = $x->DataGetInt();

      if ($service['id'] != -1) {

        $service['type'] = $x->DataGetInt();

        $service['name'] = $x->DataGetString();

        $service['tarif_name'] = $x->DataGetString();

        $service['cost'] = $x->DataGetDouble();

        $service['slink_id'] = $x->DataGetInt();

        $service['period'] = $x->DataGetInt();

      } else {

        $service['type'] = -1;

        $service['name'] = "";

        $service['tarif_name'] = "";

        $service['cost'] = -1;

        $service['slink_id'] = -1;

        $service['period'] = -1;

      }

      $ret['services'][]=$service;

    }

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcf_get_prepaid_units($slink_id) { //0x5500

  $ret=array();

  if (!$this->connection->urfa_call(0x5500)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($slink_id);

  $this->connection->urfa_send_param($packet);

  if($x = $this->connection->urfa_get_data())

  {

    $ret['bytes_in_mbyte'] = $x->DataGetInt();

    $ret['pinfo_size'] = $x->DataGetInt();

    for($i=0;$i<$ret['pinfo_size'];$i++)

    {

      //				$x = $this->connection->urfa_get_data();

      $pinfo['id'] = $x->DataGetInt();

      $pinfo['old'] = $x->DataGetLong();

      $pinfo['cur'] = $x->DataGetLong();

      $ret[]=$pinfo;

    };

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcf_get_banks() { //0x6002

  $ret=array();

  if (!$this->connection->urfa_call(0x6002)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();// Tariff count

  $count = $x->DataGetInt();

  $ret['banks_size'] = $count;

  for ($i=0;$i<$count;$i++) {

    //                      $x = $this->connection->urfa_get_data();

    $bank['id']=$x->DataGetInt();

    $bank['bic']=$x->DataGetString();

    $bank['name']=$x->DataGetString();

    $bank['city']=$x->DataGetString();

    $bank['kschet']=$x->DataGetString();

    $ret['banks'][]=$bank;

  }

  //              $this->connection->urfa_get_data();

  return $ret;

}



function rpcf_get_sys_users_list() { //0x4405

  $ret=array();

  if (!$this->connection->urfa_call(0x4405)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  if ($x = $this->connection->urfa_get_data()) {

    $count=$x->DataGetInt();

    $ret['count']=$count;

    for($i=0;$i<$count;$i++) {

      //				$x = $this->connection->urfa_get_data();

      $user['user_id']=$x->DataGetInt();

      $user['login']=$x->DataGetString();

      $user['ip_address']=$x->DataGetIPAddress();

      $user['mask']=$x->DataGetIPAddress();

      $ret['users'][]=$user;

    }

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}



function rpcf_get_sup() { //0x8011

  $ret=array();

  if (!$this->connection->urfa_call(0x8011)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();

  $count=$x->DataGetInt();

  $ret['count']=$count;

  for ($i=0;$i<$count;$i++) {

    $sup['id']=$x->DataGetInt();

    $sup['name']=$x->DataGetString();

    $sup['ur_adress']=$x->DataGetString();

    $sup['act_adress']=$x->DataGetString();

    $sup['inn']=$x->DataGetString();

    $sup['kpp']=$x->DataGetString();

    $sup['bank_id']=$x->DataGetInt();

    $sup['account']=$x->DataGetString();

    $sup['fio_headman']=$x->DataGetString();

    $sup['fio_bookeeper']=$x->DataGetString();

    $sup['fio_headman_sh']=$x->DataGetString();

    $sup['fio_bookeeper_sh']=$x->DataGetString();

    $sup['name_sh']=$x->DataGetString();

    $sup['bank_bic']=$x->DataGetString();

    $sup['bank_name']=$x->DataGetString();

    $sup['bank_city']=$x->DataGetString();

    $sup['bank_kschet']=$x->DataGetString();

    $ret[]=$sup;

  }

  return $ret;

}











**Пришлось подправить, то что выше не работало:**

function rpcf_get_sup() { //0x8011

  $ret=array();

  if (!$this->connection->urfa_call(0x8011)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();// Tariff count

  $count = $x->DataGetInt();

  $ret['count'] = $count;

  for ($i=0;$i<$count;$i++) {

    $x = $this->connection->urfa_get_data();



    $sup['id']=$x->DataGetInt();

    $sup['name']=$x->DataGetString();

    $sup['ur_adress']=$x->DataGetString();

    $sup['act_adress']=$x->DataGetString();

    $sup['inn']=$x->DataGetString();

    $sup['kpp']=$x->DataGetString();

    $sup['bank_id']=$x->DataGetInt();

    $sup['account']=$x->DataGetString();

    $sup['fio_headman']=$x->DataGetString();

    $sup['fio_bookeeper']=$x->DataGetString();

    $sup['fio_headman_sh']=$x->DataGetString();

    $sup['fio_bookeeper_sh']=$x->DataGetString();

    $sup['name_sh']=$x->DataGetString();

    $sup['bank_bic']=$x->DataGetString();

    $sup['bank_name']=$x->DataGetString();

    $sup['bank_city']=$x->DataGetString();

    $sup['bank_kschet']=$x->DataGetString();

    $ret[]=$sup;



  }

  $this->connection->urfa_get_data();

  return $ret;

}



function rpcf_get_doc_templates_list($doc_type_id) { //0x7022

  $ret=array();

  if (!$this->connection->urfa_call(0x7022)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet=$this->connection->getPacket();

  $packet->DataSetInt($doc_type_id);

  $this->connection->urfa_send_param($packet);

  $x = $this->connection->urfa_get_data();// Tariff count

  $count = $x->DataGetInt();

  $ret['count'] = $count;

  for ($i=0;$i<$count;$i++) {

    //                      $x = $this->connection->urfa_get_data();

    $doc_template['id']=$x->DataGetInt();

    $doc_template['doc_id']=$x->DataGetInt();

    $doc_template['date']=$x->DataGetInt();

    $doc_template['doc_name']=$x->DataGetString();

    $doc_template['def']=$x->DataGetInt();



    $ret['doc_templates'][]=$doc_template;

  }

  //              $this->connection->urfa_get_data();

  return $ret;

}



function rpcf_get_free_ips_for_house($house_id) { //0x2813

  $ret=array();

  if (!$this->connection->urfa_call(0x2813)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($house_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()){

    $ret['ips_size']=$x->DataGetInt();

    for ($i=0;$i<$ret['ips_size'];$i++) {

      $set['ips_ip']=$x->DataGetIPAddress();

      $set['zone_name']=$x->DataGetString();

      $ret['free_ips'][]=$set;

    }

  }

  return $ret;

}

function rpcf_get_iptraffic_service($sid)



$ret=array();

if (!$this->connection->urfa_call(0x2105)) {

  print "Error calling function ". __FUNCTION__ ."\n";

  return FALSE;

}



$packet = $this->connection->getPacket();

$packet->DataSetInt($sid);

$this->connection->urfa_send_param($packet);

if ($x = $this->connection->urfa_get_data()) {

  $ret['service_name']=$x->DataGetString();

  $ret['comment']=$x->DataGetString();

  $ret['link_by_default']=$x->DataGetInt();

  $ret['is_dynamic']=$x->DataGetInt();

  $ret['cost']=$x->DataGetDouble();

  $ret['pm_every_day']=$x->DataGetInt();

  $ret['discount_method']=$x->DataGetInt();

  $ret['start_date']=$x->DataGetInt();

  $ret['expire_date']=$x->DataGetInt();

  $ret['null_service_prepaid']=$x->DataGetInt();

  $ret['borders_count']=$x->DataGetInt();

  for($i=0; $i<$ret['borders_count']; $i++) {

    $ret['borders'][$i]['tclass']=$x->DataGetInt();

    if($ret['borders'][$i]['tclass'] != -1) {

      $ret['borders'][$i]['borders_size'] = $x->DataGetLong();

      for($j=0; $j<$ret['borders'][$i]['borders_size']; $j++) {

        $ret['borders'][$i]['border'][$j]['border_id'] = $x->DataGetLong();

        $ret['borders'][$i]['border'][$j]['border_cost'] = $x->DataGetDouble();

      }

    }

  }

  $ret['prepaid_count']=$x->DataGetInt();

  for($i=0;$i<$ret['prepaid_count'];$i++) {

    $ret['prepaid'][$i]['tclass'] = $x->DataGetInt();

    if($ret['prepaid'][$i]['tclass'] != -1) {

      $ret['prepaid'][$i]['prepaid_amount'] = $x->DataGetLong();

      $ret['prepaid'][$i]['prepaid_max'] = $x->DataGetLong();

    }

  }

  $ret['tclass_id2group_size'] = $x->DataGetInt();

  for($i=0;$i<$ret['tclass_id2group_size'];$i++) {

    $ret['tclass_id2group'][$i]['tclass_id'] = $x->DataGetInt();

    $ret['tclass_id2group'][$i]['tclass_group_id'] = $x->DataGetInt();

  }

  $ret['service_data_parent_id'] = $x->DataGetInt();

  $ret['tariff_id'] = $x->DataGetInt();

  $ret['parent_id'] = $x->DataGetInt();

}

return $ret;

}

function rpcf_get_iptraffic_service_link($slink_id) { //0x2702

  $ret = array();

  if (!$this->connection->urfa_call(0x2702)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($slink_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret['tariff_link_id']=$x->DataGetInt();

    $ret['is_blocked']=$x->DataGetInt();

    $ret['discount_period_id']=$x->DataGetInt();

    $ret['start_date']=$x->DataGetInt();

    $ret['expire_date']=$x->DataGetInt();

    $ret['unabon']=$x->DataGetInt();

    $ret['unprepay']=$x->DataGetInt();

    $ret['tariff_id']=$x->DataGetInt();

    $ret['parent_id']=$x->DataGetInt();

    $ret['ip_groups_count']=$x->DataGetInt();

    for($i=0;$i<$ret['ip_groups_count'];$i++) {

      $ipgroup['ip']=$x->DataGetIPAddress();

      $ipgroup['mask']=$x->DataGetIPAddress();

      $ipgroup['mac']=$x->DataGetString();

      $ipgroup['login']=$x->DataGetString();

      $ipgroup['password']=$x->DataGetString();

      $ipgroup['allowed_cid']=$x->DataGetString();

      $ipgroup['not_vpn']=$x->DataGetInt();

      $ipgroup['dont_use_fw']=$x->DataGetInt();

      $ipgroup['router_id']=$x->DataGetInt();

      $ret['ip_groups'][]=$ipgroup;

    }

    $ret['quotas_count']=$x->DataGetInt();

    for($i=0;$i<$ret['quotas_count'];$i++) {

      $quota['tclass_id']=$x->DataGetInt();

      $quota['tclass_name']=$x->DataGetString();

      $quota['quota']=$x->DataGetLong();

      $ret['quotas'][]=$quota;

    }

    //			$this->connection->urfa_get_data();

  } else {

    return -1; // invalid slink_id

  }

  return $ret;

}

function rpcf_get_payment_methods_list() { //0x3100

  $ret=array();

  if (!$this->connection->urfa_call(0x3100)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();

  $count=$x->DataGetInt();

  $ret['count']=$count;

  for ($i=0; $i < $count; $i++ ) {

    $list['id']=$x->DataGetInt();

    $list['name']=$x->DataGetString();

    $ret['payments_methods'][]=$list;

  }

  //		$this->connection->urfa_get_data();

  return $ret;

}

function rpcf_get_routers_list() { //0x5002

  $ret = array();

  if (!$this->connection->urfa_call(0x5002)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $routers_size = $x->DataGetInt();

    $ret['routers_size'] = $routers_size;

    for ($i = 0; $i < $routers_size; $i++) {

      $router['router_id'] = $x->DataGetInt();

      $router['router_type'] = $x->DataGetInt();

      $router['router_ip'] = $x->DataGetString();

      $router['login'] = $x->DataGetString();

      $router['password'] = $x->DataGetString();

      $router['router_comments'] = $x->DataGetString();

      $router['router_bin_ip'] = $x->DataGetInt();

      $ret['routers'][] = $router;

      unset ($router);

    }

  }

  return $ret;

}

// Данную функцию необходимо перепроверить

// ↑ функция отрабатывает отлично

function rpcf_get_services_list($which_service=-1) { //0x2101

  $ret=array();

  if (!$this->connection->urfa_call(0x2101)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($which_service);

  $this->connection->urfa_send_param($packet);

  if($x = $this->connection->urfa_get_data()){

    $count=$x->DataGetInt();

    $ret['count']=$count;

    for ($i=0;$i<$count;$i++) {

      $services['service_id']=$x->DataGetInt();

      $services['service_name']=$x->DataGetString();

      $services['service_type']=$x->DataGetInt();

      $services['service_comment']=$x->DataGetString();

      $service_status=$x->DataGetInt();

      $services['service_status']=$service_status;

      if ($service_status==2){

        $services['tariff_name']=$x->DataGetString();

      } else {

        $services['tariff_name']='';

      }

      $ret['services'][]=$services;

    }

  }

  return $ret;

}

function rpcf_get_sys_user($user_id) { //0x4409

  $ret=array();

  if (!$this->connection->urfa_call(0x4409)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()){

    $ret['login']=$x->DataGetString();

    $ret['ip']=$x->DataGetIPAddress();

    $ret['mask']=$x->DataGetIPAddress();

    $ret['group_count']=$x->DataGetInt();

    for ($i=0;$i<$ret['group_count'];$i++) {

      $group['group_id']=$x->DataGetInt();

      $group['group_name']=$x->DataGetString();

      $ret['groups'][]=$group;

    }

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcf_get_tariff($tariff_id) { //0x3011

  $ret=array();

  if (!$this->connection->urfa_call(0x3011)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($tariff_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret['tariff_name'] = $x->DataGetString();

    $ret['tariff_create_date'] = $x->DataGetInt();

    $ret['who_create'] = $x->DataGetInt();

    $ret['who_create_login'] = $x->DataGetString();

    $ret['tariff_change_date'] = $x->DataGetInt();

    $ret['who_change'] = $x->DataGetInt();

    $ret['who_change_login'] = $x->DataGetString();

    $ret['tariff_expire_date'] = $x->DataGetInt();

    $ret['tariff_is_blocked'] = $x->DataGetInt();

    $ret['tariff_balance_rollover'] = $x->DataGetInt();

    $ret['services_count'] = $x->DataGetInt();

    for ($i=0;$i<$ret['services_count'];$i++) {

      //			$x = $this->connection->urfa_get_data();

      $service['service_id'] = $x->DataGetInt();

      $service['service_type'] = $x->DataGetInt();

      $service['service_name'] = $x->DataGetString();

      $service['comment'] = $x->DataGetString();

      $service['link_by_default'] = $x->DataGetInt();

      $service['is_dynamic'] = $x->DataGetInt();

      $ret['services'][]=$service;

    }

    //		$this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcf_set_radius_attr($attr) { //0x10100

  $ret = array();

  if (!$this->connection->urfa_call(0x10100)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }



  $packet = $this->connection->getPacket();

  $packet->DataSetInt($attr['sid']);

  $packet->DataSetInt($attr['st']);

  $packet->DataSetInt($attr['cnt']);

  for($i=0;$i<$attr['cnt'];$i++) {

    $packet->DataSetInt($attr['attr'][$i]['vendor']);

    $packet->DataSetInt($attr['attr'][$i]['attr']);

    $packet->DataSetInt($attr['attr'][$i]['param1']);

    $packet->DataSetString($attr['attr'][$i]['cval']);

  }



  $this->connection->urfa_send_param($packet);



}

function rpcf_get_tariff_id_by_name($name) { //0x301d

  $ret=array();

  if (!$this->connection->urfa_call(0x301d)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetString($name);

  $this->connection->urfa_send_param($packet);



  if($x = $this->connection->urfa_get_data()) {

    $ret['tid']=$x->DataGetInt();

  }

  return $ret;

}

function rpcf_get_tariffs_list() { //0x3010

  $ret=array();

  if (!$this->connection->urfa_call(0x3010)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();// Tariff count

  $count = $x->DataGetInt();

  $ret['count'] = $count;

  for ($i=0;$i<$count;$i++) {

    //			$x = $this->connection->urfa_get_data();

    $tariff['id']=$x->DataGetInt();

    $tariff['name']=$x->DataGetString();

    $tariff['create_date']=$x->DataGetInt();

    $tariff['who_create']=$x->DataGetInt();

    $tariff['login']=$x->DataGetString();

    $tariff['change_create']=$x->DataGetInt();

    $tariff['who_change']=$x->DataGetInt();

    $tariff['login_change']=$x->DataGetString();

    $tariff['expire_date']=$x->DataGetInt();

    $tariff['is_blocked']=$x->DataGetInt();

    $tariff['balance_rollover']=$x->DataGetInt();

    $ret['tariffs'][]=$tariff;

  }

  //		$this->connection->urfa_get_data();

  return $ret;

}

function rpcf_get_tclass($class_id) { //0x2302

  $ret=array();

  if (!$this->connection->urfa_call(0x2302)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($class_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret['tclass_name']=$x->DataGetString();

    $ret['graph_color']=$x->DataGetInt();

    $ret['is_display']=$x->DataGetInt();

    $ret['is_fill']=$x->DataGetInt();

    $ret['time_range_id']=$x->DataGetInt();

    $ret['dont_save']=$x->DataGetInt();

    $ret['local_traf_policy']=$x->DataGetInt();

    $ret['tclass_count']=$x->DataGetInt();

    $count = $ret['tclass_count'];

    for ($i=0;$i<$count;$i++) {

      //				$x = $this->connection->urfa_get_data();

      $tclass['saddr']=$x->DataGetIPAddress();

      $tclass['saddr_mask']=$x->DataGetIPAddress();

      $tclass['sport']=$x->DataGetInt();

      $tclass['input']=$x->DataGetInt();

      $tclass['src_as']=$x->DataGetIPAddress();

      $tclass['daddr']=$x->DataGetIPAddress();

      $tclass['daddr_mask']=$x->DataGetIPAddress();

      $tclass['dport']=$x->DataGetInt();

      $tclass['output']=$x->DataGetInt();

      $tclass['dst_as']=$x->DataGetIPAddress();

      $tclass['proto']=$x->DataGetInt();

      $tclass['tos']=$x->DataGetInt();

      $tclass['nexthop']=$x->DataGetInt();

      $tclass['tcp_flags']=$x->DataGetInt();

      $tclass['ip_from']=$x->DataGetIPAddress();

      $tclass['use_sport']=$x->DataGetInt();

      $tclass['use_input']=$x->DataGetInt();

      $tclass['use_src_as']=$x->DataGetInt();

      $tclass['use_dport']=$x->DataGetInt();

      $tclass['use_output']=$x->DataGetInt();

      $tclass['use_dst_as']=$x->DataGetInt();

      $tclass['use_proto']=$x->DataGetInt();

      $tclass['use_tos']=$x->DataGetInt();

      $tclass['use_nexthop']=$x->DataGetInt();

      $tclass['use_tcp_flags']=$x->DataGetInt();

      $tclass['skip']=$x->DataGetInt();

      $ret['tclass'][]=$tclass;

    }

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcf_get_tclasses() { //0x2300

  $ret=array();

  if (!$this->connection->urfa_call(0x2300)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();

  $count=$x->DataGetInt();

  $ret['count']=$count;

  for($i=0; $i<$count;$i++) {

    //			$x = $this->connection->urfa_get_data();

    $tclass['id']=$x->DataGetInt();

    $tclass['name']=$x->DataGetString();

    $tclass['graph_color']=$x->DataGetInt();

    $tclass['is_display']=$x->DataGetInt();

    $tclass['is_fill']=$x->DataGetInt();

    $ret['tclasses'][]=$tclass;

  }

  //		$this->connection->urfa_get_data();

  return $ret;

}

function rpcf_service_report($user_id=0,$account_id=0,$group_id=0,$apid=0,$time_start,$time_end) {  //3002

  $ret=array();

  if (!$this->connection->urfa_call(0x3002)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $packet->DataSetInt($account_id);

  $packet->DataSetInt($group_id);

  $packet->DataSetInt($apid);

  $packet->DataSetInt($time_start);

  $packet->DataSetInt($time_end);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret['accounts_count'] = $x->DataGetInt();

    for ($i=0;$i<$ret['accounts_count'];$i++) {

      //				$x = $this->connection->urfa_get_data();

      if($user_id) $x = $this->connection->urfa_get_data();

      $services['atr_size'] = $x->DataGetInt();

      for($j=0;$j<$services['atr_size'];$j++){

        $x = $this->connection->urfa_get_data();

        $services['atr_size_array'][$j]['account_id'] = $x->DataGetInt();

        $services['atr_size_array'][$j]['login'] = $x->DataGetString();

        $services['atr_size_array'][$j]['discount_date'] = $x->DataGetInt();

        $services['atr_size_array'][$j]['discount_period_id'] = $x->DataGetInt();

        $services['atr_size_array'][$j]['discount'] = $x->DataGetDouble();

        $services['atr_size_array'][$j]['service_name'] = $x->DataGetString();

        $services['atr_size_array'][$j]['service_type'] = $x->DataGetInt();

        $services['atr_size_array'][$j]['comment'] = $x->DataGetString();

      }

      $ret['services'][]=$services;

    }

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}



///// у меня заработало вот так:



function rpcf_service_report($param) {  //0x3021

  $ret = array();

  if (!$this->connection->urfa_call(0x3021)) { print "Error calling function ". __FUNCTION__ ."\n";return FALSE; }



  $packet=$this->connection->getPacket();

  $packet->DataSetInt( $param['user_id'] );

  $packet->DataSetInt( $param['account_id'] );

  $packet->DataSetInt( $param['group_id'] );

  $packet->DataSetInt( $param['apid'] );

  $packet->DataSetInt( $param['t_start'] );

  $packet->DataSetInt( $param['t_end'] );

  $this->connection->urfa_send_param($packet);



  if( $x = $this->connection->urfa_get_data() ){

    $ret['accounts_count'] = $x->DataGetInt();

    for ( $i=0 ; $i < $ret['accounts_count'] ; $i++ ) {

      $services['atr_size'] = $x->DataGetInt();

      for( $j=0 ; $j < $services['atr_size'] ; $j++ ){

        $services['atr_size_array'][$j]['account_id'] = $x->DataGetInt();

        $services['atr_size_array'][$j]['login'] = $x->DataGetString();

        $services['atr_size_array'][$j]['full_name'] = $x->DataGetString();

        $services['atr_size_array'][$j]['discount_date'] = $x->DataGetInt();

        $services['atr_size_array'][$j]['discount_period_id'] = $x->DataGetInt();

        $services['atr_size_array'][$j]['discount'] = $x->DataGetDouble();

        $services['atr_size_array'][$j]['service_name'] = $x->DataGetString();

        $services['atr_size_array'][$j]['service_type'] = $x->DataGetInt();

      }

      $ret['services'][]=$services;

    }

  }

  return $ret;

}



function rpcf_get_telephony_service_link($slink_id) { // 0x5058

  $ret = array();



  if (!$this->connection->urfa_call(0x5058)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($slink_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret['tariff_link_id'] = $x->DataGetInt();

    $ret['is_blocked']=$x->DataGetInt();

    $ret['discount_period_id']=$x->DataGetInt();

    $ret['start_date']=$x->DataGetInt();

    $ret['expire_date']=$x->DataGetInt();

    $ret['unabon']=$x->DataGetInt();

    $ret['unprepay']=$x->DataGetInt();

    $ret['tariff_id']=$x->DataGetInt();

    $ret['parent_id']=$x->DataGetInt();

    $ret['tel_numbers_count']=$x->DataGetInt();



    for ($i = 0; $i < $ret['tel_numbers_count']; $i++) {

      $tel_login['item_id'] = $x->DataGetInt();

      $tel_login['tel_number'] = $x->DataGetString();

      $tel_login['tel_login'] = $x->DataGetString();

      $tel_login['tel_password'] = $x->DataGetString();

      $tel_login['tel_allowed_cid'] = $x->DataGetString();

      $ret['tel_numbers'][] = $tel_login;



      $x = $this->connection->urfa_get_data();

    }

  } else {

    return -1;

  }

  return $ret;

}

function rpcf_get_tps_for_user($uid,$aid,$tpid,$tplink) { //0x301a

  if (!$this->connection->urfa_call(0x301a)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($uid);

  $packet->DataSetInt($aid);

  $packet->DataSetInt($tpid);

  $packet->DataSetInt($tplink);

  $packet->DataSetInt('');

  $this->connection->urfa_send_param($packet);



  $x = $this->connection->urfa_get_data();

  $service_size=$x->DataGetInt();

  $ret = array();

  for($i = 0;$i < $service_size;$i++){

    $ret[$i]['sid'] = $x->DataGetInt();

    $ret[$i]['service_name'] = $x->DataGetString();

    $ret[$i]['service_type'] = $x->DataGetInt();

    $ret[$i]['comment'] = $x->DataGetString();

    $ret[$i]['slink'] = $x->DataGetInt();

    $ret[$i]['value'] = $x->DataGetInt();

  }

  return $ret;



}

//$type

//1-Отчет с группировкой по часам

//2-Отчет с группировкой по дням

//3-Общий отчет

//4-Отчет с группировкой по IP

function rpcf_traffic_report_ex($user_id,$time_start,$time_end, $type) { //0x3009

  $ret=array();

  if (!$this->connection->urfa_call(0x3009)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($type);

  $packet->DataSetInt($user_id);

  $packet->DataSetInt(0);

  $packet->DataSetInt(0);

  $packet->DataSetInt(0);

  $packet->DataSetInt($time_start);

  $packet->DataSetInt($time_end);



  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret['bytes_in_kbyte']=$x->DataGetDouble();

    $users_count=$x->DataGetInt();

    $ret['users_count']=$users_count;

    $traffic=array();

    for( $i=0; $i<$users_count; $i++ ) {

      $atr_size=$x->DataGetInt();

      $traffic['atr_size']=$atr_size;

      $ips=array();

      for( $j=0; $j<$atr_size; $j++ ) {

        $ips['account_id']=$x->DataGetInt();

        $ips['login']=$x->DataGetString();

        $ips['discount_date']=$x->DataGetInt();

        $ips['tclass']=$x->DataGetInt();

        $ips['base_cost']=$x->DataGetDouble();

        $ips['bytes']=$x->DataGetLong();

        $ips['discount']=$x->DataGetDouble();

        $traffic['ips'][$j]=$ips;

      }

      $ret['traffic'][$i]=$traffic;

    }

  }

  return $ret;

}



//Вариант для "старого" urfa_get_data

function rpcf_traffic_report_ex($user_id,$time_start,$time_end, $type) { //0x3009

  $ret=array();

  if (!$this->connection->urfa_call(0x3009)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($type);

  $packet->DataSetInt($user_id);

  $packet->DataSetInt(0);

  $packet->DataSetInt(0);

  $packet->DataSetInt(0);

  $packet->DataSetInt($time_start);

  $packet->DataSetInt($time_end);



  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret['bytes_in_kbyte']=$x->DataGetDouble();

    $users_count=$x->DataGetInt();

    $ret['users_count']=$users_count;

    $traffic=array();

    for( $i=0; $i<$users_count; $i++ ) {

      $x = $this->connection->urfa_get_data();

      $atr_size=$x->DataGetInt();

      $traffic['atr_size']=$atr_size;

      $ips=array();

      for( $j=0; $j<$atr_size; $j++ ) {

        $x = $this->connection->urfa_get_data();

        $ips['account_id']=$x->DataGetInt();

        $ips['login']=$x->DataGetString();

        $ips['discount_date']=$x->DataGetInt();

        $ips['tclass']=$x->DataGetInt();

        $ips['base_cost']=$x->DataGetDouble();

        $ips['bytes']=$x->DataGetLong();

        $ips['discount']=$x->DataGetDouble();

        $traffic['ips'][$j]=$ips;

      }

      $ret['traffic'][$i]=$traffic;

    }

    $x = $this->connection->urfa_get_data();

  }

  return $ret;

}



==== EXAMPLE ====

$s.="
Отчёт по трафику

";

try {

  $urfa_admin = new URFAClient_Admin(UTM5_login,UTM5_passwd);

  $report=$urfa_admin->rpcf_traffic_report_ex($_SESSION['URFA']['user_id'],strtotime("-2 month"),strtotime("+1 day"),2); //0x3009

} catch (Exception $exception) {

  echo "Error in line ", $exception->getLine();

  echo $exception->getMessage();

}

$traffic=$report['traffic'];

$atr_size=$traffic['0']['atr_size'];

$ips=$traffic['0']['ips'];

$t=array();

for ($i=0;$i<$atr_size;$i++) {

  $d_date		= $ips[$i]['discount_date'];

  $tclass		= $ips[$i]['tclass'];

  $bytes		= $ips[$i]['bytes'];

  $discount	= $ips[$i]['discount'];



  $t[$d_date][$tclass]['bytes']=$bytes;

  $t[$d_date][$tclass]['discount']=$discount;

}

krsort($t);

$s.='';

$s.='';

if (0==sizeof($t)) {

  $s.='';

  $s.='';

  $s.='';

  $s.='';

  $s.='';

  $s.='';

} else {

  $i=0;

  while(list($d_date,$a)=each($t)) {

    $s.='';

    $s.='';

    $s.='';

    $s.='';

    $s.='';

    $s.='';

    $i++;

  }

}

$s.='
Дата	Входящий трафик (байт)	Исходящий трафик (байт)	Стоимость трафика (руб.)
'.date("d.m.Y H:i",time()).'	---	---	0.00
'.date("d.m.Y",$d_date).'	'.number_format($a[T_CLASS_IN]['bytes']).'	'.number_format($a[T_CLASS_OUT]['bytes']).'	'.sprintf("%01.2f",$a[T_CLASS_IN]['discount']).'
';

function rpcf_get_user_account_list($user_id) { //0x2033

  $ret=array();

  if (!$this->connection->urfa_call(0x2033)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $count=$x->DataGetInt();

    $ret['count']=$count;

    for($i=0; $i<$count;$i++) {

      $account['id']=$x->DataGetInt();

      $account['name']=$x->DataGetString();

      $ret['accounts'][]=$account;

    }

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}

// return user_id or 0 if user not found

function rpcf_get_user_by_account($account_id) { //0x2026

  $ret=array();

  if (!$this->connection->urfa_call(0x2026)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($account_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $user_id = $x->DataGetInt();

  }

  //		$this->connection->urfa_get_data();

  return $user_id;

}

function rpcf_get_user_contacts_new($uid) { //0x2040

  $ret=array();

  if (!$this->connection->urfa_call(0x2040)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($uid);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $size=$x->DataGetInt();



    for($i=0; $i<$size; $i++){

      $ret[$i]['id']=$x->DataGetInt();

      $ret[$i]['descr']=$x->DataGetString();

      $ret[$i]['reason']=$x->DataGetString();

      $ret[$i]['person']=$x->DataGetString();

      $ret[$i]['short_name']=$x->DataGetString();

      $ret[$i]['contact']=$x->DataGetString();

      $ret[$i]['email']=$x->DataGetString();

      $ret[$i]['id_exec_man']=$x->DataGetInt();



    }

    if($size>0) return $ret; else return 0;

  }

  return false;

}

function rpcf_get_user_othersets($user_id=0) { //0x9021

  $ret=array();

  if (!$this->connection->urfa_call(0x9021)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $this->connection->urfa_send_param($packet);

  if( $x = $this->connection->urfa_get_data() )

  {

    $count=$x->DataGetInt();

    $ret['count']=$count;

    for ($i=0;$i<$count;$i++) {

      $othersets['type']=$x->DataGetInt();

      if( $othersets['type'] == 1 )

      {

        $othersets['switch_id'] = $x->DataGetInt();

        $othersets['port'] = $x->DataGetInt();

      }

      elseif( $othersets['type'] == 3 )

      {

        $othersets['cur_id'] = $x->DataGetInt();

        $othersets['name'] = $x->DataGetString();

      }

      $ret['othersets'][]=$othersets;

      unset( $othersets );

    }

  }

  return $ret;

}

function rpcf_get_user_tariffs($user_id, $account_id=0) { //0x3017

  $ret=array();

  if (!$this->connection->urfa_call(0x3017)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $packet->DataSetInt($account_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $count=$x->DataGetInt();

    $ret['count']=$count;

    for($i=0; $i<$count;$i++) {

      $tariff['current_tariff'] = $x->DataGetInt();

      $tariff['next_tariff'] = $x->DataGetInt();

      $tariff['discount_period_id'] = $x->DataGetInt();

      $tariff['tariff_link_id'] = $x->DataGetInt();

      $ret['user_tariffs'][]=$tariff;

    }

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcf_get_userinfo($user_id) { //0x2006

  $ret=array();

  if (!$this->connection->urfa_call(0x2006)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $this->connection->urfa_send_param($packet);

  $x = $this->connection->urfa_get_data();

  $user = $x->DataGetInt();

  $ret['user_id']= $user;

  if ($user!=0) {

    $ret['user_id']= $user;

    $accounts_count = $x->DataGetInt();

    $ret['accounts_count']= $accounts_count;

    for($i=0;$i<$accounts_count;$i++) {

      $accounts['id']=$x->DataGetInt();

      $accounts['name']=$x->DataGetString();

      $ret['accounts'][]=$accounts;

    }

    $ret['login']=$x->DataGetString();

    $ret['password']=$x->DataGetString();

    $ret['basic_account']=$x->DataGetInt();

    $ret['full_name']=$x->DataGetString();

    $ret['create_date']=$x->DataGetInt();

    $ret['last_change_date']=$x->DataGetInt();

    $ret['who_create']=$x->DataGetInt();

    $ret['who_change']=$x->DataGetInt();

    $ret['is_juridical']=$x->DataGetInt();

    $ret['jur_address']=$x->DataGetString();

    $ret['act_address']=$x->DataGetString();

    $ret['work_tel']=$x->DataGetString();

    $ret['home_tel']=$x->DataGetString();

    $ret['mob_tel']=$x->DataGetString();

    $ret['web_page']=$x->DataGetString();

    $ret['icq_number']=$x->DataGetString();

    $ret['tax_number']=$x->DataGetString();

    $ret['kpp_number']=$x->DataGetString();

    $ret['bank_id']=$x->DataGetInt();

    $ret['bank_account']=$x->DataGetString();

    $ret['comments']=$x->DataGetString();

    $ret['personal_manager']=$x->DataGetString();

    $ret['connect_date']=$x->DataGetInt();

    $ret['email']=$x->DataGetString();

    $ret['is_send_invoice']=$x->DataGetInt();

    $ret['advance_payment']=$x->DataGetInt();

    $ret['house_id']=$x->DataGetInt();

    $ret['flat_number']=$x->DataGetString();

    $ret['entrance']=$x->DataGetString();

    $ret['floor']=$x->DataGetString();

    $ret['district']=$x->DataGetString();

    $ret['building']=$x->DataGetString();

    $ret['passport']=$x->DataGetString();

    $ret['parameters_size']=$x->DataGetInt();

    for ($i=0; $i < $ret['parameters_size']; $i++ ) {

      $parameters['id']=$x->DataGetInt();

      $parameters['value']=$x->DataGetString();

      $ret['parameters'][]=$parameters;

    }

  }

  //		$this->connection->urfa_get_data();

  return $ret;

}

function rpcf_get_users_count($card_user=0) { //0x2011

  $ret=0;

  if (!$this->connection->urfa_call(0x2011)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($card_user);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()){

    $ret = $x->DataGetInt();

  }

  return $ret;

}

function rpcf_get_users_list($from,$to,$card_user=0) { //0x2001

  $ret=array();

  if (!$this->connection->urfa_call(0x2001)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($from);

  $packet->DataSetInt($to);

  $packet->DataSetInt($card_user);

  $this->connection->urfa_send_param($packet);

  if($x = $this->connection->urfa_get_data()){

    $count=$x->DataGetInt();

    $ret['count']=$count;

    for ($i=0;$i<$count;$i++) {

      $users['user_id']=$x->DataGetInt();

      $users['login']=$x->DataGetString();

      $users['basic_account']=$x->DataGetInt();

      $users['full_name']=$x->DataGetString();

      $users['is_blocked']=$x->DataGetInt();

      $users['balance']=$x->DataGetDouble();

      $ip_adr_size=$x->DataGetInt();

      $users['ip_adr_size']=$ip_adr_size;

      $ipgroup=array();

      for ($j=0;$j<$ip_adr_size;$j++) {

        $group_size=$x->DataGetInt();

        $ipgroup['group_size']=$group_size;

        $ips=array();

        for ($k=0;$k<$group_size;$k++) {

          $ips['ip_address']=$x->DataGetIPAddress();

          $ips['mask']=$x->DataGetIPAddress();

          $ips['group_type']=$x->DataGetInt();

          $ipgroup['ips'][]=$ips;

        }

        $users['ipgroup']=$ipgroup;

      }

      $users['user_int_status']=$x->DataGetInt();

      $ret['users'][]=$users;

    }

  }

  return $ret;

}

function rpcf_link_user_tariff($user_id,$account_id=0,$tariff_current,$tariff_next=$tariff_current,$discount_period_id,$tariff_link_id=0) { //0x3018

  $ret=array();

  if (!$this->connection->urfa_call(0x3018)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $packet->DataSetInt($account_id);

  $packet->DataSetInt($tariff_current);

  $packet->DataSetInt($tariff_next);

  $packet->DataSetInt($discount_period_id);

  $packet->DataSetInt($tariff_link_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $ret['tariff_link_id']=$x->DataGetInt();

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcf_unlink_user_tariff($user_id,$account_id=0,$tariff_link_id=0) { //0x3019

  $ret=array();

  if (!$this->connection->urfa_call(0x3019)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $packet->DataSetInt($account_id);

  $packet->DataSetInt($tariff_link_id);

  $this->connection->urfa_send_param($packet);

  $this->connection->urfa_get_data();

  return $ret;

}

function rpcf_payments_report_owner($time_start,$time_end) { //0x3008

  $ret=array();

  if (!$this->connection->urfa_call(0x3008)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet=$this->connection->getPacket();

  $packet->DataSetInt(0);

  $packet->DataSetInt(0);

  $packet->DataSetInt(0);

  $packet->DataSetInt(0);

  $packet->DataSetInt($time_start);

  $packet->DataSetInt($time_end);



  $this->connection->urfa_send_param($packet);

  if($x = $this->connection->urfa_get_data()){//

    $unused=$x->DataGetInt();

    $ret['count']=$x->DataGetInt();

    for($i=0;$i<$ret['count'];$i++){

      $ret[$i]['id']=$x->DataGetInt();

      $ret[$i]['account_id']=$x->DataGetInt();

      $ret[$i]['login']=$x->DataGetString();

      $ret[$i]['actual_date']=$x->DataGetInt();

      $ret[$i]['payment_enter_date']=$x->DataGetInt();

      $ret[$i]['payment']=$x->DataGetDouble();

      $ret[$i]['payment_incurrency']=$x->DataGetDouble();

      $ret[$i]['currency_id']=$x->DataGetInt();

      $ret[$i]['method']=$x->DataGetInt();

      $ret[$i]['who_received']=$x->DataGetInt();

      $ret[$i]['admin_comment']=$x->DataGetString();

      $ret[$i]['comment']=$x->DataGetString();



    }



  }

  return $ret;

}





function rpcf_put_router($router) { //0x5003

  $ret = array();

  if (!$this->connection->urfa_call(0x5003)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }



  $packet = $this->connection->getPacket();

  $packet->DataSetInt($router['router_id']);

  $packet->DataSetInt($router['router_type']);

  $packet->DataSetString($router['router_ip']);

  $packet->DataSetString($router['login']);

  $packet->DataSetString($router['password']);

  $packet->DataSetString($router['router_comments']);

  $packet->DataSetInt($router['router_bin_ip']);



  $this->connection->urfa_send_param($packet);



}

/**

* Put user contact

* @param array $contact contact array

* @return array

*/

function rpcf_put_user_contact ( $contact ) {

  global $config;



  //if ( $config->getDebugMode() ) {

  //    $this->writeDebugLog(__METHOD__.", user_id = ".$user_id);

  //}



  $ret=array();





  if (!$this->connection->urfa_call( 0x2022) ) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($contact['id']);

  $packet->DataSetInt($contact['user_id']);

  $packet->DataSetString($contact['person']);

  $packet->DataSetString($contact['descr']);

  $packet->DataSetString($contact['contact']);

  $packet->DataSetString($contact['email']);

  //$packet->DataSetInt($contact['email_notify']);

  $packet->DataSetInt(1);

  $packet->DataSetString($contact['short_name']);

  $packet->DataSetString($contact['birthday']);

  $packet->DataSetInt($contact['id_exec_man']);

  if ( $config->getDebugMode() ) {

    $this->writeDebugLog(__METHOD__.", packet = ".var_export($packet, true));

  }

  $this->connection->urfa_send_param($packet);

  //$x = $this->connection->urfa_get_data();



  if ( $config->getDebugMode() ) {

    $this->writeDebugLog(__METHOD__.", x = ".var_export($x, true));

  }



  return true;

}





тут я использовал свои метода writeDebugLog(), $config->getDebugMode(); - можно закомментировать
function rpcf_remove_tariff($tariff_id) { //0x301b

  $ret=1;

  if (!$this->connection->urfa_call(0x301b)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($tariff_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()){

    $ret = $x->DataGetInt();

    //			$this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcf_whoami() { //0x440a

  $ret=array();

  if (!$this->connection->urfa_call(0x440a)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $x = $this->connection->urfa_get_data();

  $ret['my_uid']=$x->DataGetInt();

  $ret['login']=$x->DataGetString();

  $ret['user_ip']=$x->DataGetIPAddress();

  $ret['user_mask']=$x->DataGetIPAddress();

  $count=$x->DataGetInt();

  $ret['system_group_size']=$count;

  for ($i=0; $i < $count; $i++ ) {

    $list['system_group_id']=$x->DataGetInt();

    $list['system_group_name']=$x->DataGetString();

    $list['system_group_info']=$x->DataGetString();

    $ret['system_groups'][]=$list;

  }

  $count=$x->DataGetInt();

  $ret['allowed_fids_size']=$count;

  for ($i=0; $i < $count; $i++ ) {

    $list['id']=$x->DataGetInt();

    $list['name']=$x->DataGetString();

    $list['module']=$x->DataGetString();

    $ret['allowed_fids'][]=$list;

  }

  $count=$x->DataGetInt();

  $ret['not_allowed_size']=$count;

  for ($i=0; $i < $count; $i++ ) {

    $list['id_not_allowed']=$x->DataGetInt();

    $list['name_not_allowed']=$x->DataGetString();

    $list['module_not_allowed']=$x->DataGetString();

    $ret['not_allowed_fids'][]=$list;

  }



  //                $this->connection->urfa_get_data();

  return $ret;

}





function rpcf_remove_user_from_group($user_id,$group_id) { //0x2408

  if (!$this->connection->urfa_call(0x2408)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $packet->DataSetInt($group_id);

  $this->connection->urfa_send_param($packet);

  $this->connection->urfa_get_data();

}

function rpcf_remove_user($user_id) { //0x200e

  $ret=array();

  if (!$this->connection->urfa_call(0x200e)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($user_id);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()){

    $ret['result']=$x->DataGetInt();

    //                        $this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcf_save_account($account_id,$account, $block_start_date, $block_end_date, $discount_period_id) { //0x2032

  if (!$this->connection->urfa_call(0x2032)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  if ($block_start_date == -1)

  $block_start_date = now();

  if ($block_end_date == -1)

  $block_end_date = max_time();

  $packet = $this->connection->getPacket();

  $packet->DataSetInt($account_id);

  $packet->DataSetInt($discount_period_id);

  $packet->DataSetDouble($account['credit']);

  $packet->DataSetInt($account['is_blocked']);

  if ($account['is_blocked']!=0) {

    $packet->DataSetInt($block_start_date);

    $packet->DataSetInt($block_end_date);

  }

  $packet->DataSetInt($account['dealer_account_id']);

  $packet->DataSetDouble($account['vat_rate']);

  $packet->DataSetDouble($account['sale_tax_rate']);

  $packet->DataSetInt($account['int_status']);

  $packet->DataSetInt($account['block_recalc_abon']);

  $packet->DataSetInt($account['block_recalc_prepaid']);

  $packet->DataSetInt($account['unlimited']);

  $this->connection->urfa_send_param($packet);

  $this->connection->urfa_get_data();

}

}

function rpcf_search_cards($card_id) { //0x1201

  $ret=array();

  if (!$this->connection->urfa_call(0x1201)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt(1); //select type

  $packet->DataSetInt(1); //patterns count

  $packet->DataSetInt(1); //what_id 'id'

  $packet->DataSetInt(3); //criteria_id '='

  $packet->DataSetString($card_id); //card_id

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()) {

    $count=$x->DataGetInt();

    $ret['count']=$count;

    for($i=0; $i<$count;$i++) {

      //$x = $this->connection->urfa_get_data();

      $card['card_id'] = $x->DataGetInt();

      $card['pool_id'] = $x->DataGetInt();

      $card['secret']  = $x->DataGetString();

      $card['balance'] = $x->DataGetDouble();

      $card['currency']= $x->DataGetInt();

      $card['expire']  = $x->DataGetInt();

      $card['days']    = $x->DataGetInt();

      $card['is_used'] = $x->DataGetInt();

      $card['tp_id']   = $x->DataGetInt();

      $ret['cards'][]  = $card;

    }

    //$this->connection->urfa_get_data();

  }

  return $ret;

}

function rpcf_search_users_light($login="%",$email="%",$fname="%") { //0x1202

  $ret=array();

  if (!$this->connection->urfa_call(0x1202)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetString($login);

  $packet->DataSetString($email);

  $packet->DataSetString($fname);

  $this->connection->urfa_send_param($packet);

  if ($x = $this->connection->urfa_get_data()){

    $ret['success'] = $x->DataGetInt();

    $ret['total'] = $x->DataGetInt();

    $ret['show_count'] = $x->DataGetInt();

    if($ret['show_count']>0){

      for($i=0;$i<=$ret['show_count']-1;$i++){

        //               $x = $this->connection->urfa_get_data();

        $ret['list'][$i]['id']= $x->DataGetInt();

        $ret['list'][$i]['login']= $x->DataGetString();

        $ret['list'][$i]['email']= $x->DataGetString();

        $ret['list'][$i]['fname']= $x->DataGetString();

      }

    }

    //         $this->connection->urfa_get_data();

  }

  return $ret;

}

// Функция сделана для новой(правильной) реализации urfa_get_data()



function rpcf_search_users_new($poles,$patterns,$sel_type) { //0x1205

  $ret=array();

  if (!$this->connection->urfa_call(0x1205)) {

    print "Error calling function ". __FUNCTION__ ."\n";

    return FALSE;

  }

  $packet = $this->connection->getPacket();

  $packet->DataSetInt(count($poles));

  for ($i=0; $i        $packet->DataSetInt($poles[$i]);

};

$packet->DataSetInt($sel_type);

$pat_count=count($patterns);

$packet->DataSetInt($pat_count);

for ($i=0;$i        $packet->DataSetInt($patterns[$i]['what_id']);

$packet->DataSetInt($patterns[$i]['criteria_id']);

if ($patterns[$i]['what_id']==33) {

  $packet->DataSetInt($patterns[$i]['pattern']);

}else{

  $packet->DataSetString($patterns[$i]['pattern']);



}

}



$this->connection->urfa_send_param($packet);

if ($x = $this->connection->urfa_get_data()){

  $ret['user_data_size']=$x->DataGetInt();

  for ($i=0;$i<$ret['user_data_size'];$i++){

    $ret[$i]['user_id']=$x->DataGetInt();

    $ret[$i]['login']=$x->DataGetString();

    $ret[$i]['basic_account']=$x->DataGetInt();

    $ret[$i]['full_name']=$x->DataGetString();

    $ret[$i]['is_blocked']=$x->DataGetInt();

    $ret[$i]['balance']=$x->DataGetDouble();

    $ret[$i]['ip_address_size']=$x->DataGetInt();

    for ($j=0;$j<$ret[$i]['ip_address_size'];$j++){

      $ret[$i]['ip_address'][$j]['ip_groups_count']=$x->DataGetInt();

      for ($k=0;$k<$ret[$i]['ip_address'][$j]['ip_groups_count'];$k++){

        $ret[$i]['ip_address'][$j]['ip_group'][$k]['type']=$x->DataGetInt();

        $ret[$i]['ip_address'][$j]['ip_group'][$k]['ip']=$x->DataGetIPAddress();

        $ret[$i]['ip_address'][$j]['ip_group'][$k]['mask']=$x->DataGetIPAddress();

      }

    }



    for ($j=0;$j                        if ($poles[$j]==4){$ret[$i]['discount_period_id']=$x->DataGetInt();}

    if ($poles[$j]==6){$ret[$i]['create_date']=$x->DataGetInt();}

    if ($poles[$j]==7){$ret[$i]['last_change_date']=$x->DataGetInt();}

    if ($poles[$j]==8){$ret[$i]['who_create']=$x->DataGetInt();}

    if ($poles[$j]==9){$ret[$i]['who_change']=$x->DataGetInt();}

    if ($poles[$j]==10){$ret[$i]['is_juridical']=$x->DataGetInt();}

    if ($poles[$j]==11){$ret[$i]['juridical_address']=$x->DataGetString();}

    if ($poles[$j]==12){$ret[$i]['actual_address']=$x->DataGetString();}

    if ($poles[$j]==13){$ret[$i]['work_telephone']=$x->DataGetString();}

    if ($poles[$j]==14){$ret[$i]['home_telephone']=$x->DataGetString();}

    if ($poles[$j]==15){$ret[$i]['mobile_telephone']=$x->DataGetString();}

    if ($poles[$j]==16){$ret[$i]['web_page']=$x->DataGetString();}

    if ($poles[$j]==17){$ret[$i]['icq_number']=$x->DataGetString();}

    if ($poles[$j]==18){$ret[$i]['tax_number']=$x->DataGetString();}

    if ($poles[$j]==19){$ret[$i]['kpp_number']=$x->DataGetString();}

    if ($poles[$j]==21){$ret[$i]['house_id']=$x->DataGetInt();}

    if ($poles[$j]==22){$ret[$i]['flat_number']=$x->DataGetString();}

    if ($poles[$j]==23){$ret[$i]['entrance']=$x->DataGetString();}

    if ($poles[$j]==24){$ret[$i]['floor']=$x->DataGetString();}

    if ($poles[$j]==25){$ret[$i]['email']=$x->DataGetString();}

    if ($poles[$j]==26){$ret[$i]['passport']=$x->DataGetString();}

    if ($poles[$j]==40){$ret[$i]['district']=$x->DataGetString();}

    if ($poles[$j]==41){$ret[$i]['building']=$x->DataGetString();}

  }

}

}

return $ret;

}



} ?>
