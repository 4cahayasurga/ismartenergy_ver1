<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pipeline_identity {
 public function valid($freshLogin=false){
  $ci=get_instance();$id=$ci->session->userdata('MIS_LOGGED_ID');if(!is_string($id)||$id==='')return false;
  $hasState=$ci->db->table_exists('PipelineUserSecurity');
  $ci->db->select('L.UserID,L.UserName,L.UserRole,L.Active')->from('Login L')->where('L.UserID',$id);
  if($hasState)$ci->db->select('S.AccessVersion,S.DeletedAt')->join('PipelineUserSecurity S','S.UserID=L.UserID','left');
  $row=$ci->db->get()->row_array();if(!$row||$row['Active']!=='1'||($hasState&&$row['DeletedAt']!==null))return false;
  $version=$hasState&&$row['AccessVersion']!==null?(int)$row['AccessVersion']:1;$previous=$ci->session->userdata('pipeline_access_version');
  if(!$freshLogin&&(($previous!==null&&(int)$previous!==$version)||($previous===null&&$version>1)))return false;
  $ci->session->set_userdata(array('pipeline_access_version'=>$version,'USER_ROLE'=>$row['UserRole'],'MIS_LOGGED_NAME'=>$row['UserName']));return true;
 }
}
