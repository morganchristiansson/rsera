<?php
ini_set('default_charset', 'UTF-8');
error_reporting(E_ALL);


/*
  
  Search Engine Ranking Analysis
  ------------------------------
  File    : global.inc.php
  Desc.   : variables and functions

  Version : 0.3
  Author  : Matthijs Koot

  History : 28-04-03 - file created
            05-05-03 - getPage: 'host not found' now returns -1
                       added support for empty resultlists for SEs with a known 'no results' message
            21-07-03 - getRanking now retries on failing socket connection
            28-07-03 - added logerr() for development
            30-07-03 - added ADODB layer, in hope for dbms independence...
            01-08-03 - disabled the second analysis that used to be done if ranking result is 0 (not listed)
                       added experimental debug stuff
            05-08-03 - creuzerm - creuzerm@users.sourceforge.net
                       declared $buffer before it is used for a dot concatanation
            21-08-03 - removed PHP short tags
            22-09-03 - added vars for sql tablenames nikl@spielepsychatrie.de
            22-09-03 - added german language file nikl@spielepsychatrie.de
            01-12-03 - updated version :)
            11-12-03 - added set_time_limit(20) in getRanking()
                       added 'i' flag to all preg_match() calls for case insensitive matching
            19-01-04 - added utf8_before_urlencode fix for Excite.fr
            21-01-04 - added error_reporting(E_ALL)... our code should be perfect :)
            01-02-04 - fixed small bug in getRanking: changed $searchresults[$i] to $html
            15-04-04 - changed getRanking to return a value for $ranking["indexed_page"]
                       (containing the exact page associated with the hit, i.e. '/' or '/products.html')
            03-05-04 - changed phpSERA to use UTF-8 (!) as it should have used to start with
            04-05-04 - added support for HTTP proxies (see $PROXYSERVER and $PROXYPORT)
                       added customizable User-Agent header (see $USERAGENT)
*/

/*******************************************************
  1) INITIALIZE CONSTANTS
********************************************************/
  /* config (change to your configuration) */
    $DB_USER            = "phpsera";
    $DB_PASS            = "";
    $DB_HOST            = "localhost";
    $DB_NAME            = "phpsera";
    $DB_DRIVER          = "mysql";  // any ADODB driver, please mail to opensource at koot.biz if you get phpSERA to work with a DBMS other than MySQL!

   $LANGFILE           = "english.inc.php";
//    $LANGFILE          = "dutch.inc.php";
//    $LANGFILE          = "german.inc.php";
//    $LANGFILE          = "french.inc.php";
    $FONTFILE           = "/apps/php-4.0.6/php-bin/truetype/ARIAL.TTF";
    
    /*******************************************************
    * Database Tabledefinitions
    ********************************************************/
    $DB_Keyphrases	= 'keywords';
    $DB_Searchengines	= 'searchengines';
    $DB_Reports		= 'reports';
    $DB_Reportrules	= 'reportrules';
    $DB_Websites	= 'websites';
 
  /* other (no need to change these) */
    $TITLE    = "phpSERA - Search Engine Ranking Analysis - GNU GPL";
    $VERSION  = "0.3alpha1";
    $RELEASE  = "2004-05-04";
    $LOGFILE  = "/tmp/phpsera.log"; // only used for development, by logerr()
    $MAX_CONNECT_ATTEMPTS  = 3; // $MAX_CONNECT_ATTEMPTS is de maximum number of socket connection attempts phpSERA performs
    $ANALYSIS_DEBUG = false; // prints all separated search results when analysis is performed from the Quick (quick.php) and New report (newser.php) pages

    // Spoofing the User-Agent HTTP header
    $USERAGENT = "Mozilla/4.0 (compatible; MSIE 5.5; Windows 98; Win 9x 4.90)";
    //$USERAGENT = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.6) Gecko/20040206 Firefox/0.8";

    // uncomment the next two lines to use a HTTP proxy
    // $PROXYSERVER = "";
    // $PROXYPORT = 80;

/*******************************************************
  2) LOAD TRANSLATIONS
********************************************************/
    include_once "lang/".$LANGFILE;
    
/*******************************************************
  3) CONNECT TO DATABASE
********************************************************/
    include_once('adodb/adodb.inc.php');

    $db = ADONewConnection($DB_DRIVER);
    $db->debug = false; // prints inline SQL statements
    if (@!$db->Connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME))
      err("Please check your database connection settings in global.inc.php!");

/*******************************************************
  4) SERA FUNCTIONS
********************************************************/
  /*
    name   :  getRanking()
    desc   :  determine website position in SE rankings
    params :  $url    --> url of the site to look for
              $phrase --> keyphrase to use
              $searchengine[] --> array w/desc of a SE
              $verbose --> "1" to display each search result item
                           (to verify the regexps from the db) 
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
  function getRanking($url, $phrase, $searchengine,$verbose=0) {
    global $MAX_CONNECT_ATTEMPTS, $ANALYSIS_DEBUG;
    set_time_limit(20);

    /* init some variables */
    if (@!$searchengine[1]) $phrase = utf8_decode($phrase);
    $searchurl    = str_replace("[__KEYPHRASE__]", urlencode($phrase), $searchengine[2]); /* url to search for */
    $regexp_begin = $searchengine[3];   /* e.g. "<ol>\n<li>" */
    $regexp_end   = $searchengine[4];   /* e.g. "<\/ol>" */
    $key          = $searchengine[5];   /* separator key; e.g. "<li>" */
    $host         = $searchengine[6];   /* "search.yahoo.com" */
    $script       = $searchengine[7];   /* "/cgi-bin/search" */
    $data         = str_replace("[__KEYPHRASE__]", urlencode($phrase), $searchengine[8]);
    $langcode     = $searchengine[9];   /* country code (not used here) */
    $noresult     = $searchengine[10];  /* string which would mark an empty resultlist */
  
    $ranking["position"]   = 0; // default to "not listed"
    $ranking["indexed_page"]   = "";


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

      /* max. N attempts to connect */
      for ($connect_attempt=1; $connect_attempt <= $MAX_CONNECT_ATTEMPTS; $connect_attempt++) {
        $html      = getPage($host, "GET", $script, $data);
        if ($html["exitcode"] != -1) break;
      }

      /* if all attempts failed */
      if ($html["exitcode"] == -1) {
        $ranking["position"] = -2;
        $ranking["message"]  = $html["message"]; // i.e. socket timeout
      } else {
    
        $result = preg_match("/$regexp_begin(.+)$regexp_end/is", $html, $templist);
    
        if (!$result) { 
        /*
          ERROR in regular expressions due to:
            1) SE has a zero result list for this query, and returns
               a webpage which doesn't comply with the (otherwise)
               correct regexp's
            2) SE changed it's layout
        */
  
          // SITUATION 1
          if (strlen($noresult) > 3 && strstr($html, $noresult)) {
            $ranking["position"] = 0;
            $ranking["message"] = "The search engine returned an empty resultlist";
          // SITUATION 2
          } elseif (strstr($html,$url) || 
                strstr($html,urlencode($url))) {
            $ranking["position"] = -3;
            $ranking["message"] = "Invalid regex (check Begin-Of-List regex, End-Of-List regex and Separator settings for this search engine; your website seems to be listed, though!";
          } else {
            $ranking["position"] = -1;
            $ranking["message"] = "Invalid regex (check Begin-Of-List regex, End-Of-List regex and Separator settings for this search engine)" ;
          }
        } else {
          /* 
            Hmm... we seem to have HTML output and valid regex's.
            Let's find out the ranking position!
          */
          $searchresults = explodei($key, $templist[1]);

          // loop through the search result items
          for ($i=0; $i < sizeof($searchresults); $i++) {

            /* 
              There's 2 ways to enable analysis debug output:
               - set a global variable ($ANALYSIS_DEBUG=true)
               - pass a parameter to this function ($verbose=true)

              EXPERIMENTAL DEBUG STUFF
            */
            if ($ANALYSIS_DEBUG || $verbose) {
              $urlpattern = '/((http|https|ftp):\/\/|www)' // line 1
                  .'[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&#;,]*' // line 2
                  .'[a-z0-9\/]{1}/si'; // line 3
 
              preg_match($urlpattern, $searchresults[$i], $matches); 
              if ($i==0) { 
                echo "<table>\n";
                echo "<tr>\n";
                echo "  <th style='font-size:12px'>Position</th>\n";
                echo "  <th style='font-size:12px'>URL</th>\n";
                echo "</tr>\n";
              }
              foreach ($matches as $match) {
                echo "<tr>\n";
                echo "  <td>".($i+1)."</td>\n";
                echo "  <td>$match</td>\n";
                echo "</tr>\n";
                break;
              }
              if ($i==(sizeof($searchresults)-1)) echo "</table>\n";
            }
            /* END EXPERIMENTAL DEBUG STUFF */

            // TODO: improve this check
            if (strstr($searchresults[$i],$url) || 
                strstr($searchresults[$i],urlencode($url))) {
              if ($searchresults[0]=="") $ranking["position"]=$i;
              else $ranking["position"]=$i+1; 


              $hrefs = array();
              preg_match_all("/href=*?[a-zA-Z0-9\/\.\?\=\&\"\':\-_\(\)~\!+]*/", $searchresults[$i], $hrefs);
              $hrefs = $hrefs[0];

              $zooi = "";
              (substr($url, 0, 7) == "http://") ? $needle = $url : $needle = "http://".$url;

              // If a "href=http://$url" link is present, $found_direct_link will become TRUE.
              // Yahoo and several other use redirect links, so we cannot determine the exact 
              // page (products.html, /shop/, etc) we're dealing with.
              $first_direct_link = ""; // will hold 'http://www.foobar.com/page.html'

              // loop through all HREFs
              for ($k = 0; $k < count($hrefs); $k++) {
                $offset = strpos($hrefs[$k], "http://");
                if (substr($hrefs[$k], $offset, strlen($needle)) == $needle) {
                  $first_direct_link = substr($hrefs[$k], $offset);

                  // hack away any trailing quotes
                  if ($first_direct_link[strlen($first_direct_link)-1]=='"' 
                      || $first_direct_link[strlen($first_direct_link)-1]=="'")
                    $first_direct_link[strlen($first_direct_link)-1] = null;
                  break;
                }
              }

              if ($first_direct_link)
                $ranking["indexed_page"] = substr($first_direct_link, strlen($needle));
              else
                $ranking["indexed_page"] = "(unknown)";
              if ($ranking["indexed_page"] == "") $ranking["indexed_page"] = "/";

              break; // Houston, we have a ranking
            }
          } // for
        }
      }
    return $ranking;
  }
 


  /*
    name   :  getHitPath()
    desc   :  handles (output) calls from the htmlparse procedure
    params :  $element    --> element/tag name (A, DIV, SPAN, etc)
              $attributes --> array of attribute key-value pairs 
    return :  value of the HREF attribute or NULL
  */
  function getHitPath($element,$attributes) {
    $allowed["a"] =array("href");
    $element = strtolower($element);
    if (!isset($allowed[$element])) return;

    if (sizeof($attributes)) {
      while (list($k, $v) = each($attributes)) {
        $k = strtolower($k);
        if ($k == "href") echo "<br>".$v;
      }
    }
  }

  /*
    name   :  getPage()
    desc   :  grab some webpage
    params :  $host   --> server to connect to
              $method --> "POST" or "GET"
              $path   --> (...) 
              $data   --> (...) 
    return :  full HTML page
  */
  function getPage($host,$method,$path,$data) {
    global $USERAGENT, $PROXYSERVER, $PROXYPORT;
    $timeout=10;
    $usingProxy = false;

    // try to open socket, exit on error
    // PS: the errorcode and msg should be used


    if ($PROXYSERVER != "" && is_numeric($PROXYPORT))
      if (!($fp = fsockopen($PROXYSERVER, $PROXYPORT, $ret["error"], $ret["message"], $timeout))) {
        $ret["exitcode"]=-2;
        return $ret;
      } else $usingProxy = true;
    else
      if (!($fp = fsockopen($host, 80, $ret["error"], $ret["message"], $timeout))) {
        $ret["exitcode"]=-1;
        return $ret;
      }

    if ($method == "GET")
      $path .= "?" . $data;

    // send our request header
    if ($usingProxy)
      fputs($fp, "$method http://$host$path HTTP/1.0\n\n");
    else {
      fputs($fp, "$method $path HTTP/1.0\n");

      fputs($fp, "Host: $host\n");
      fputs($fp, "Accept: */*\n");
      fputs($fp, "Pragma: no-cache\n");
      fputs($fp, "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\n");
      fputs($fp, "Content-type: application/x-www-form-urlencoded\n");

      if ($method == "POST")
        fputs($fp, "Content-length: " . strlen($data) . "\n");

      fputs($fp, "User-Agent: ". $USERAGENT. "\n");
      fputs($fp, "Connection: close\n\n");

      if ($method == "POST")
        fputs($fp, $data);
    }

      // retrieve the result page
      $buffer = '';
      while (!feof($fp))
        $buffer .= fgets($fp,128);

    // close socket
    fclose($fp);
    return $buffer;  
  } 

  /*
    name   :  unhtmlentities()
    desc   :  replace html entities by the character they represent
              (does the same as html_entity_decode() in PHP>=4.3.0)
    params :  $string containing html entities
    return :  - decoded string
  */
  function unhtmlentities ($string) {
    $trans_tbl = get_html_translation_table (HTML_ENTITIES);
    $trans_tbl = array_flip ($trans_tbl);
    return @strtr ($string, $trans_tbl);
  }


  /*
    name   :  explodei()
    desc   :  case insensitive alternative for PHP's explode() function
    params :  $separator, $string, $limit
    return :  - exploded array of strings
    NOTE: this function was ripped from a PHP.net user comment from siavash79_99
  */
  function explodei($separator, $string, $limit = false ) 
  { 
     $len = strlen($separator); 
     for ( $i = 0; ; $i++ ) 
     { 
       if ( ($pos = stripos( $string, $separator )) === false || ($limit !== false && $i > $limit - 2 ) ) 
       { 
           $result[$i] = $string; 
           break; 
       } 
       $result[$i] = substr( $string, 0, $pos ); 
       $string = substr( $string, $pos + $len ); 
     } 
     return $result; 
   } 




/*******************************************************
  5) DEBUGGING FUNCTIONS
********************************************************/

  /*    
    name   :  err()
    desc   :  generic error handler 
    params :  $msg (string) --> error message
              $thing (any) --> variable to debug (optional)
    return :  - nothing
  */ 
  function err($msg,$thing=null) {
    echo "<h3 style='color: #036; font-family: Arial; font-weight: bold'>Error occured</h3>";
    echo "<p style='font-size: 10pt; color: #036; font-family: Arial;'>$msg";

   if (!empty($thing)) 
     echo "<pre>".ss_as_string($thing)."</pre>";
   die();
  }

  /*
    name   :  logerr()
    desc   :  logs a given message to $LOGFILE, if set
    params :  $msg (string) --> error message
              $thing (any) --> variable or object to log (optional)
    return :  - nothing
  */
  function logerr($msg,$thing=null) {
    global $LOGFILE, $PHP_SELF;
    umask(0111);
    if ($fp = fopen($LOGFILE, 'a')) {
      // please note that $PHP_SELF will always contain the
      // right logerr() caller, as data.inc.php is INCLUDED
      // and never requested directly by any client.
      $content = date("Ymd H:i:s")." $PHP_SELF - $msg\n";

      if ($thing != null)  {
        $content .= date("Ymd H:i:s")." $PHP_SELF requested me to log a variable, so here we go:\n".ss_as_string($thing, 0, true)."\n";
        $content .= "--------------------------------------------\n";
      }
 
      fwrite($fp,$content);
      fclose($fp);
    }
  }
    
  /*
    name   :  ss_array_as_string()
              --> called from ss_as_string()
    desc   :  returns array w/values as single string
    params :  $array (array) some array to parse
              $column (int) --> see ss_as_string()
              $nohtml (bool) --> use plaintext chars, no HTML
    return :  - string w/all elements of array
  */
  function ss_array_as_string (&$array, $column = 0, $nohtml) {
    if ($nohtml) {
      $space = " ";
      $break = ""; // no "\n" here --> newline char is already appended
    } else {
      $space = "&nbsp;";
      $break = "<BR>";
    }

    $str = "Array($break\n";
    while(list($var, $val) = each($array)){
        for ($i = 0; $i < $column+1; $i++){
            $str .= str_repeat($space, 4);
        }
        $str .= $var.' ==> ';
        $str .= ss_as_string($val, $column+1, $nohtml)."$break\n";
    }
    for ($i = 0; $i < $column; $i++){
        $str .= str_repeat($space, 4);
    }
    return $str.')';
  }

  /*
    name   :  ss_object_as_string()
              --> called from ss_as_string()
    desc   :  returns object w/values as single string
    params :  $object (any) some arbitrary object to parse
              $column (int) --> see ss_as_string()
              $nohtml (bool) --> use plaintext chars, no HTML
    return :  - string w/all attributes of object
  */ 
  function ss_object_as_string (&$object, $column = 0, $nohtml) {
    if ($nohtml) {
      $space = " ";
      $break = ""; // no "\n" here --> newline char is already appended
    } else {
      $space = "&nbsp;";
      $break = "<BR>";
    }

    if (empty($object->classname)) {
        return "$object";
    }
    else {
        $str = $object->classname."($break\n";
        while (list(,$var) = each($object->persistent_slots)) {
            for ($i = 0; $i < $column; $i++){
                $str .= str_repeat($space, 4);
            }
            global $$var;
            $str .= $var.' ==> ';
            $str .= ss_as_string($$var, column+1, $nohtml)."$break\n";
        }
        for ($i = 0; $i < $column; $i++){
            $str .= str_repeat($space, 4);
        }
        return $str.')';
    }
  }

  /*
    name   :  ss_as_string()
              --> call from anywhere to debug any var
    desc   :  returns given 'thing' w/all values as string
    params :  $thing (any) any variable to output debug
              $nohtml (bool) --> use plaintext chars, no HTML
    return :  - string w/all values of given 'thing'
  */
  function ss_as_string (&$thing, $column = 0, $nohtml = false) {
    if ($nohtml) {
      $space = " ";
      $break = ""; // no "\n" here --> newline char is already appended
    } else {
      $space = "&nbsp;";
      $break = "<BR>";
    }

    if (is_object($thing)) {
        return ss_object_as_string($thing, $column, $nohtml);
    }
    elseif (is_array($thing)) {
        return ss_array_as_string($thing, $column, $nohtml);
    }
    elseif (is_double($thing)) {
        return "Double(".$thing.")";
    }
    elseif (is_long($thing)) {
        return "Long(".$thing.")";
    }
    elseif (is_string($thing)) {
        return "String(".$thing.")";
    }
    else {
        return "Unknown(".$thing.")";
    }
  }

?>
