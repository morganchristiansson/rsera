<?php

class Keyphrase {
  var $m_id;
  var $m_website;
  var $m_langcode;
  var $m_phrase;
 
  function Keyphrase($id, $website, $langcode, $phrase) {
    $this->m_id = $id;
    $this->set_website($website);
    $this->set_langcode($langcode);
    $this->set_phrase($phrase);
  }
 
  function get_id() {
    return $m_id;
  }

  function get_website() {
    return $m_website;
  }

  function get_langcode() {
    return $m_langcode;
  }

  function get_phrase() {
    return $m_phrase;
  }

  function set_website($website) {
    $this->m_website = $website;
  }

  function set_langcode($langcode) {
    $this->m_langcode = $langcode;
  }

  function set_phrase($phrase) {
    $this->m_phrase = $phrase;
  }

}

?>
