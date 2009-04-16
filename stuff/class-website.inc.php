<?php

class Website {
  var $m_id;
  var $m_url;
 
  function Website($id, $url) {
    $this->m_id = $id;
    $this->set_url($url);
  }
 
  function get_id() {
    return $m_id;
  }

  function get_url() {
    return $m_url;
  }

  function set_id($url) {
    $this->m_url = $url;
  }

  function getSQL_DELETE_WEBSITE() {
    return "DELETE FROM websites WHERE ws_id=".$this->get_id();
  }

  function getSQL_DELETE_REPORTS() {
    return "DELETE FROM reports WHERE ws_id=".$this->get_id();
  }
  
  function getSQL_DELETE_REPORTRULES() {
    $sql_rp = "SELECT mt_id FROM reports WHERE ws_id=".$this->get_id();
    $result_rp = mysql_query($sql_rp);

    if (@mysql_num_rows($result_rp) > 0) {
      while(list($mt_id)=mysql_fetch_row($result_rp)) {
        $i++;
        if ($i==1) $collection = "$mt_id";
        else $collection .= ",$mt_id";
      }
      return "DELETE FROM reportrules WHERE mt_id IN ($collection)";
    }
  }

}

?>
