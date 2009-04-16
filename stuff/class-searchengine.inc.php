<?php

class SearchEngine {
  var $m_id;
  var $m_title;
  var $m_startkey;
  var $m_endkey;
  var $m_separator;
  var $m_host;
  var $m_scriptpath;
  var $m_querydata;
  var $m_addurl;
  var $m_url;
  var $m_langcode;
  var $m_noresult;

  // phpSERA breaks out of all loops if a sane ranking code (0 or higher)
  // was returned by getRanking. If not, it does $max_connect_attempts
  // to retry. If it fails again, it AGAIN does this number of attempts,
  // resulting in a default of a maximum of 6 attempts to get a ranking.
  // Which should be enough, unless the search engine is down or you're
  // having serious network problems.
  var $max_connect_attempts = 3;


  var $timeout = 10;
  var $method = "GET";


  /* constructor */
  function SearchEngine($id, $title, $startkey, $endkey, $separator, $host, $scriptpath, $querydata, $addurl, $url, $langcode, $noresult) {
    $this->m_id = $id;
    $this->set_title($title);
    $this->set_startkey($startkey);
    $this->set_endkey($endkey);
    $this->set_separator($separator);
    $this->set_host($host);
    $this->set_scriptpath($scriptpath);
    $this->set_querydata($querydata);
    $this->set_addurl($addurl);
    $this->set_url($url);
    $this->set_langcode($langcode);
    $this->set_noresult($noresult);
  }


  /*
    name   :  getRanking()
    desc   :  determine website position in SE rankings
    params :  $website    --> website object to look for
              $keyphrase  --> keyphrase object to use
    return : - position # (if listed in search results)
             - "0" (not listed)
             - "-1" (regexp error)
             - "-2" (host connect error)
             - "-3" (regexp error, but website URL found in resultlist)
             
    TODO   : return "-2" on seemingly different SE layout; perhaps
             based on sizeof($searchresults)?
             Perhaps a new field [searchengines].[results]
             Less than [results] = error, more than [results]+2 error or so...
  */
  function getRanking($website, $keyphrase) {
    $url = $website->get_url();
    $phrase = $keyphrase->get_phrase();

    /* init some variables */
    $regexp_begin = $searchengine[3];   /* "<ol>\n<li>" */
    $regexp_end   = $searchengine[4];   /* "<\/ol>" */
    $key          = $searchengine[5];   /* separator key; "<li>" */
    $host         = $searchengine[6];   /* "search.yahoo.com" */
    $script       = $searchengine[7];   /* "/cgi-bin/search" */
    $data         = str_replace("[__KEYPHRASE__]", urlencode($phrase), $searchengine[8]);
    $langcode     = $searchengine[9];   /* country code (not used here) */
    $noresult     = $searchengine[10];  /* string which would mark an empty resultlist */
  
    $ranking["position"]   = 0; // init to "not listed"


    /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
      phpSERA typically performs 2 attempts to get a ranking, 
      each attempt consisting of max. 3 connect attempts.

      phpSERA ranking codes:
        0  : not listed or empty resultlist
        >0 : listed, and this is the apparent ranking position
        -1 : regexp error
        -2 : connect error (only after 6 failing attempts!)
        -3 : regexp error (but website URL found in HTML)
    +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

    for ($ranking_attempt=1; $ranking_attempt <= 2; $ranking_attempt++) {
      /* max. N attempts to connect */
      for ($connect_attempt=1; $connect_attempt <= $this->max_connect_attempts; $connect_attempt++) {
        $html      = getPage($keyphrase);
        if ($html["exitcode"] != -1) break;
      }

      /* if all attempts failed */
      if ($html["exitcode"] == -1) {
        $ranking["position"] = -2;
        $ranking["message"]  = $html["message"]; // i.e. socket timeout
        continue; // next $ranking_attempt
      } else {
    
        $result = preg_match("/".$this->get_startkey()."(.+)".$this->get_endkey()."/s", $html, $templist);
    
        if (!$result) { 
        /*
          ERROR in regular expressions due to:
            1) SE has a zero result list for this query, and returns
               a webpage which doesn't comply with the (otherwise)
               correct regexp's
            2) SE changed it's layout
        */
  
          // SITUATION 1
          if (strlen($this->get_noresult()) > 3 && strstr($html, $this->get_noresult())) {
            $ranking["position"] = 0;
            $ranking["message"] = "No results";
          // SITUATION 2
          } elseif (strstr($searchresults[$i],$website->get_url()) || 
                strstr($searchresults[$i],urlencode($website->get_url()))) {
            $ranking["position"] = -3;
            $ranking["message"] = "Invalid regexp, but website seems to be listed";
          } else {
            $ranking["position"] = -1;
            $ranking["message"] = "Invalid regexp";
          }
          continue; // next $ranking_attempt
        } else {
          /* 
            Hmm... we seem to have HTML output and valid regex's.
            Let's find out the ranking position!
          */
          $searchresults = explode($key, $templist[1]);
      
          // loop through the search result items
          for ($i=0; $i < sizeof($searchresults); $i++) {
            // TODO: improve this check

            $output .= "<li>[index $i, looking for '".$website->get_url()."']".$searchresults[$i]."</li>";

            if (strstr($searchresults[$i],$website->get_url()) || 
                strstr($searchresults[$i],urlencode($website->get_url()))) {
              if ($searchresults[0]=="") $ranking["position"]=$i;
              else $ranking["position"]=$i+1; 
              break 2; // Houston, we have a ranking
            }
          } // for
          if ($verbose) echo "<ol>$output</ol>";
        }
      }
    } // for $ranking_attempt
    return $ranking;
  }


  /*
    name   :  getPage()
    desc   :  grab some webpage
    params :  $keyphrase   --> keyphrase object
    return :  full HTML page or array w/error code
  */
  function getPage($keyphrase) {
    $query   = str_replace(
                    "[__KEYPHRASE__]", 
                    urlencode($keyphrase->get_phrase()), 
                    $this->get_querydata()
                  );

    // try to open socket, exit on error
    // PS: the errorcode and msg should be used
    if (!($fp = fsockopen(
                     $this->get_host(),
                     80, 
                     $ret["error"], 
                     $ret["message"], 
                     $this->timeout))
                   ) {
      $ret["exitcode"]=-1;
      return $ret;
    }

    if ($this->method == "GET") 
      $query = $this->get_path()."?".$query;
    else
      $query = $this->get_path()."?".$query;
  
    // send our request header
    fputs($fp, "$method $query HTTP/1.1\n");
    fputs($fp, "Host: ".$this->get_host()."\n");
    //fputs($fp, "Referer: ".$this->get_host()."/$query\n");
    fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
    if ($method == "POST")
      fputs($fp, "Content-length: " . strlen($query) . "\n");
    fputs($fp, "User-Agent: MSIE\n");
    fputs($fp, "Connection: close\n\n");
    if ($method == "POST")
      fputs($fp, $query);
  
    // retrieve the result page
    while (!feof($fp))
      $buffer .= fgets($fp,128);
  
    // close socket
    fclose($fp);
    return $buffer;
  }




  /* GET functions */

  function get_id() {
    return $this->m_id;
  }

  function get_title() {
    return $this->m_title;
  }

  function get_startkey() {
    return $this->m_startkey;
  }

  function get_endkey() {
    return $this->m_endkey;
  }

  function get_separator() {
    return $this->m_separator;
  }

  function get_host() {
    return $this->m_host;
  }

  function get_scriptpath() {
    return $this->m_scriptpath;
  }

  function get_querydata() {
    return $this->m_querydata;
  }

  function get_addurl() {
    return $this->m_addurl;
  }

  function get_url() {
    return $this->m_url;
  }

  function get_langcode() {
    return $this->m_langcode;
  }

  function get_noresult() {
    return $this->m_noresult;
  }







  /* SET functions */

  function set_title($title) {
    $this->m_title = $title;
  }

  function set_startkey($startkey) {
    $this->m_startkey = $startkey;
  }

  function set_endkey($endkey) {
    $this->m_endkey = $endkey;
  }

  function set_separator($separator) {
    $this->m_separator = $separator;
  }

  function set_host($host) {
    $this->m_host = $host;
  }

  function set_scriptpath($scriptpath) {
    $this->m_script = $scriptpath;
  }

  function set_querydata($querydata) {
    $this->m_querydata = $querydata;
  }

  function set_addurl($addurl) {
    $this->m_addurl = $addurl;
  }

  function set_url($url) {
    $this->m_url = $url;
  }

  function set_langcode($langcode) {
    $this->m_langcode = $langcode;
  }

  function set_noresult($noresult) {
    $this->m_noresult = $noresult;
  }



}

?>
