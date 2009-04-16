<?php
//----------------------------------//
// GERMAN.INC.PHP                   //
//                                  //
// German translations for phpSERA  //
//                                  //
// Nikl, 2003-09-09                 //
// Matthijs, 2003-12-11             //
// Matthijs, 2004-04-15             //
//                                  //
//----------------------------------//

/*******************************
        country names
*******************************/
$lang["cc"]["be"] = "Belgien";
$lang["cc"]["dk"] = "D&auml;nemark";
$lang["cc"]["de"] = "Deutschland";
$lang["cc"]["ie"] = "Irland";
$lang["cc"]["fi"] = "Finnland";
$lang["cc"]["fr"] = "Frankreich";
$lang["cc"]["hu"] = "Ungarn";
$lang["cc"]["it"] = "Italien";
$lang["cc"]["si"] = "Slovenien";
$lang["cc"]["hr"] = "Kroatien";
$lang["cc"]["lu"] = "Luxemburg";
$lang["cc"]["nl"] = "Niederlande";
$lang["cc"]["no"] = "Norwegen";
$lang["cc"]["at"] = "&Ouml;sterreich";
$lang["cc"]["fr"] = "Griechenland";
$lang["cc"]["pt"] = "Portugal";
$lang["cc"]["pl"] = "Polen";
$lang["cc"]["es"] = "Spanien";
$lang["cc"]["se"] = "Schweden";
$lang["cc"]["ch"] = "Schweiz";
$lang["cc"]["cz"] = "Tschechische Republik";
$lang["cc"]["sk"] = "Slovakai";
$lang["cc"]["tr"] = "T&uuml;rkei";
$lang["cc"]["uk"] = "Groß Britannien";

/*******************************
        language codes
*******************************/
$lang["lc"]["en"] = "Englisch";
$lang["lc"]["de"] = "Deutsch";
$lang["lc"]["fr"] = "Französisch";
$lang["lc"]["es"] = "Spanisch";
$lang["lc"]["nl"] = "Niederl&auml;ndisch";
$lang["lc"]["it"] = "Italienisch";
$lang["lc"]["no"] = "Norwegisch";
$lang["lc"]["sv"] = "Schwedisch";
$lang["lc"]["fi"] = "Finnisch";
$lang["lc"]["da"] = "D&auml;nisch";
$lang["lc"]["pl"] = "Polish";
$lang["lc"]["@@"] = "International";

/*******************************
    leftmenu.php 
*******************************/
$lang["leftmenu"]["home"]="Home";

$lang["leftmenu"]["rankings"]="Rangliste";
$lang["leftmenu"]["rankings_new"]="Neuer Bericht";
$lang["leftmenu"]["rankings_quick"]="Schnelle Rangliste";
$lang["leftmenu"]["rankings_overview"]="&Uuml;bersicht";
$lang["leftmenu"]["rankings_trends"]="Trends";

$lang["leftmenu"]["admin"]="Admin";
$lang["leftmenu"]["admin_searchengines"]="Suchmaschinen";
$lang["leftmenu"]["admin_websites"]="Webseiten";
$lang["leftmenu"]["admin_keyphrases"]="Keyw&ouml;rter";
$lang["leftmenu"]["admin_errorlog"]="Errorlog";

/*******************************
    welcome.php
*******************************/
$lang["welcome"]["welcometo"]="Herzlich Willkommen";
$lang["welcome"]["nr_of_engines"]="Suchmaschinen in der Datenbank";
$lang["welcome"]["supported_languages"]="phpSERA ist Sprach&uuml;bergreifend (eher als L&auml;nder&uuml;bergreifend) und unterst&uuml;tzt die folgenden Sprachen:";

/*******************************
    newser.php
*******************************/
$lang["newser"]["new_analysis"]="Neue Ranglisten Analyse";
$lang["newser"]["analysis"]="Rangliste";
$lang["newser"]["date"]="Datum";
$lang["newser"]["type"]="Modus";
$lang["newser"]["name"]="Name";
$lang["newser"]["adminlink"]="admin";
$lang["newser"]["keyphrase"]="Keyw&ouml;rter";
$lang["newser"]["searchengines"]="Suchmaschinen";
$lang["newser"]["select_all"]="alle";
$lang["newser"]["select_none"]="keine";
$lang["newser"]["start"]="Start";
$lang["newser"]["real"]="Bericht (Analyse speichern)";
$lang["newser"]["test"]="Test (Analyse nicht speichern)";
$lang["newser"]["saveindb"]="Speicher Details in der Datenbank";
$lang["newser"]["selectse"]="Suchmaschinen (nach Sprachen)";
$lang["newser"]["rampage_mode"]="Rampage modus";
$lang["newser"]["rampage_info"]="Keine Sprach&uuml;berpr&uuml;fung. <i>Warnung:</i> Wenn Sie die Sprach&uuml;berpr&uuml;fung abschalten, werden alle m&ouml;glichen Kombinationen von Keyw&ouml;rter und Suchmaschinen analysiert. Haken Sie diese Box NICHT an wenn Sie gezielte Analysen f&uuml;r viele Webseiten und Keyw&ouml;rter machen, ausser Sie haben vor Ihren Systemadminstrator oder die Suchmaschinen zu qu&auml;len. Abschalten des Sprachchecks f&uuml;hrt zu einer Multiplikation der Banbreitenauslastung. Stattdessen schalten Sie lieber die Sprachchecks f&uuml;r spezielle Keyw&ouml;rter und Suchmaschinen &uuml;ber die Konfigurationsseite.";
$lang["newser"]["explanation"]="Wenn Sie 'Start' klicken, wird phpSERA mit der Analyse der Suchergebnisse der einzelnen Suchmaschinen beginnen. Alle Keyw&ouml;rter und Suchmaschinen sind standardm&auml;ssig angeschaltet. phpSERA ist in der Lage zu entscheiden welche Keyw&ouml;rter bei welchen Suchmaschinen verwendet werden sollen. Basierend auf den Spracheinstellungen der Keyw&ouml;rter und Suchmaschinen:
<p>
   <table border=0>
   <tr><th>Einstellung</th><th></th><th>Wert</th><th></th><th>Analysieren?</th></tr>
   <tr><td>Keywort Sprache</td><td></td><td>stimmt &uuml;berein</td><td>=</td><td>Ja - f&uuml;r diese Kombination (standard)</td></tr>
   <tr><td>Keywort Sprache</td><td></td><td>stimmt nicht &uuml;berein</td><td>=</td><td>Nein - f&uuml;r diese Kombination (standard)</td></tr>
   <tr><td>Keywort Sprache</td><td></td><td>'international'</td><td>=</td><td>Ja - Analyse wird f&uuml;r ALLE Suchmaschinen durchgef&uuml;hrt (Ausnahme)</td></tr>
   <tr><td>Suchmaschine Sprache</td><td></td><td>'international'</td><td>=</td><td>Ja - Analyse wird f&uuml;r ALLE Keyw&ouml;rter durchgef&uuml;hrt (Ausnahme)</td></tr>
   <tr><td>Sprachecheck</td><td></td><td>ausgeschaltet</td><td>=</td><td>Ja - Analyse wird f&uuml;r alle Kombinationen durchgef&uuml;hrt (Ausnahme)</td></tr>
   </table>
</p>";
$lang["newser"]["explanation_header"] = "Beschreibung";

/*******************************
    quick.php
*******************************/
$lang["quick"]["selectse"]="Suchmaschinen (nach Sprache)";
$lang["quick"]["start"]="Start";
$lang["quick"]["explanation_header"] = "Beschreibung";
$lang["quick"]["explanation"]="Wenn Sie 'Start' klicken, wird phpSERA eine Analyse f&uuml;r die angegebene Webseite und Keyw&ouml;rter in Kombinationen mit allen gew&auml;hlten Suchmaschinen beginnen (Unabh&auml;ngig von der Sprache).";   

/*******************************
    analyze.php
*******************************/
$lang["analyze"]["report"]="Ranglisten Analyse";
$lang["analyze"]["website"]="Webseite";
$lang["analyze"]["phrase(s)"]="Keyw&ouml;rter";
$lang["analyze"]["type"]="Modus";
$lang["analyze"]["searchengine"]="Suchmaschine";
$lang["analyze"]["position"]="Position";
$lang["analyze"]["notfound"]="nicht gefunden";
$lang["analyze"]["real"]="Report";
$lang["analyze"]["test"]="Test";
$lang["analyze"]["regexerror"]="Fehler in der regex oder kein Suchergebnis gefunden. Bitte &uuml;berpr&uuml;fen Sie manuell!";
$lang["analyze"]["hosterror"]="Server nicht gefunden!";
$lang["analyze"]["quick"]="Schnelle Rangliste";
$lang["analyze"]["endofanalysis"]="Ende der Analyse";
$lang["analyze"]["indexed_page"]="Indexed page";

/*******************************
    overview.php
*******************************/
$lang["overview"]["overview"]="Report &Uuml;bersicht";
$lang["overview"]["selectwebsite"]="W&auml;hlen Sie eine Webseite";
$lang["overview"]["monthreport"]="Monatlicher Bericht";
$lang["overview"]["singlereport"]="Individuelle Analyse";
$lang["overview"]["monthreport_info"]="W&auml;hlen Sie eine Webseite. W&auml;hlen Sie eine Zeitperiod aus der list. Klicken Sie 'Open' um die aktuellste Platzierung der Periode zu sehen (es k&ouml;nnten mehrere Ranking Berichte w&auml;hrend der Periode angelegt worden seine.";
$lang["overview"]["singlereport_info"]="Klicken Sie auf 'Show' um die Ergebnisse einer einzelnen Analyse anzusehen. Die Anzahl der enthaltenen Ranglisten ist unter 'Ranglisten' einzusehen.";


/*******************************
    trends.php
*******************************/
$lang["trends"]["trends"]="Trend Analyse";

/*******************************
    showreport.php
*******************************/
$lang["showreport"]["monthreport"]="Monatlischer Bericht";
$lang["showreport"]["singlereport"]="Individuelle Analyse";
$lang["showreport"]["lastonly"]="Dies sind die Platzierungen vom LETZTEN Bericht dieses Monats!";
$lang["showreport"]["done_by"]="Durchgef&uuml;hrt durch";
$lang["showreport"]["ranking"]="Rangliste";
$lang["showreport"]["keyphrase"]="Keyword";
$lang["showreport"]["se"]="Suchmaschine";
$lang["showreport"]["goodrankings"]="Good rankings";
$lang["showreport"]["badrankings"]="Bad rankings (SEO todo)";

/*******************************
    keyphrases.php
*******************************/
$lang["keyphrases"]["select_a_website"]="W&auml;hle eine Webseite";
$lang["keyphrases"]["add_a_keyphrase"]="Keyword hinzuf&uuml;gen";
$lang["keyphrases"]["keyphrase"]="Keyword";
$lang["keyphrases"]["language"]="Sprache";
$lang["keyphrases"]["intlkeyphrase"]="Internationales Keywort. Diese Box bei Markennamen w&auml;hlen ('Google'), Eigennamen ('marketing', 'webdesign'), etc. Benutzen Sie diese Auswahl nur f&uuml;r W&ouml;rter die nicht zu einer Sprache zuzuordnen sind. Wenn diese Option gew&auml;hlt wurde, wird das Keyword auf allen Suchmaschinen Analysiert, unabh&auml;ngig von Ihrer Sprache.";
$lang["keyphrases"]["add"]="Hinzuf&uuml;gen";
$lang["keyphrases"]["delete"]="L&ouml;schen";
$lang["keyphrases"]["current_keyphrases"]="Aktuelle Keyw&ouml;rter";
$lang["keyphrases"]["usage_info"]="(not translated yet) This is the keyphrase management page. Use the same spelling as you would in a search engine. This exact phrase will be used for analysis. Avoid adding misspelled phrases on purpose, unless your webserver logfiles point out that those misspelled phrases actually lead visitors to your site. A maximum of 50 keyphrases per site is recommended. <b>Once added, a keyphrases CANNOT be changed through phpSERA! (preventing pollution of existing statistics; a workaround is to delete the keyphrase and add it again - old statistics relating to that specific keyphrase will be lost))</b>";
$lang["keyphrases"]["default_lang"]="de";
$lang["keyphrases"]["priorities"]=array("", "High", "Normal", "Low");

/*******************************
    websites.php
*******************************/
$lang["websites"]["add_a_website"]="Webseite hinzuf&uuml;gen";
$lang["websites"]["add"]="Hinzuf&uuml;gen";
$lang["websites"]["delete"]="L&ouml;schen";
$lang["websites"]["website_url"]="Webseiten URL";
$lang["websites"]["usage_info"]="(not translated yet) Enter your website URL, as it can be found in the resultpages of search engines. This is the exact phrase phpSERA will try to find. Keep in mind that entering 'foobar.com' here may result in less false negatives than entering 'http://www.foobar.com/index.html'. <b>Once added, a website CANNOT be changed through phpSERA! (preventing pollution of existing statistics; a workaround is to delete the website and add it again - old statistics relating to that specific website will be lost)</b>";

/*******************************
    se_top.php
*******************************/
$lang["se"]["select_a_se"]="W&auml;hlen oder hinzf&uuml;gen einer Suchmaschine";
$lang["se"]["language"]="Sprache";
$lang["se"]["script_path"]="Script (path)";
$lang["se"]["data"]="Querydaten";
$lang["se"]["host"]="Server";
$lang["se"]["new"]="Neu";
$lang["se"]["delete"]="L&ouml;schen";
$lang["se"]["startkey"]="Beginn-der-Listen regex";
$lang["se"]["endkey"]="Ende-der-Listen regex";
$lang["se"]["separator"]="Trennzeichen (keine regex)";
$lang["se"]["noresults"]="Keine Ergenisse";
$lang["se"]["test_settings"]="Testen der Einstellungen";
$lang["se"]["save_settings"]="Speicher diese Suchmaschine";
$lang["se"]["testphrase"]="Test keyphrase";
$lang["se"]["showrawhtml"]="Raw HTML";

/*******************************
    graph.php
*******************************/
$lang["graph"]["select_a_se"]="W&auml;hle eine Suchmaschine";

?>
