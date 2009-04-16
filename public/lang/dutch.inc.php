<?php
//----------------------------------//
// DUTCH.INC.PHP                    //
//                                  //
// Dutch translations for phpSERA   //
//                                  //
// Matthijs, December 11th 2003     //
//                                  //
//----------------------------------//

/*******************************
    country names
*******************************/
$lang["cc"]["be"] = "België";
$lang["cc"]["dk"] = "Denemarken";
$lang["cc"]["de"] = "Duitsland";
$lang["cc"]["ie"] = "Ierland";
$lang["cc"]["fi"] = "Finland";
$lang["cc"]["fr"] = "Frankrijk";
$lang["cc"]["hu"] = "Hongarije";
$lang["cc"]["it"] = "Italië";
$lang["cc"]["si"] = "Slovenië";
$lang["cc"]["hr"] = "Kroatië";
$lang["cc"]["lu"] = "Luxemburg";
$lang["cc"]["nl"] = "Nederland";
$lang["cc"]["no"] = "Noorwegen";
$lang["cc"]["at"] = "Oostenrijk";
$lang["cc"]["fr"] = "Griekenland";
$lang["cc"]["pt"] = "Portugal";
$lang["cc"]["pl"] = "Polen";
$lang["cc"]["es"] = "Spanje";
$lang["cc"]["se"] = "Zweden";
$lang["cc"]["ch"] = "Zwitserland";
$lang["cc"]["cz"] = "Tsjechië";
$lang["cc"]["sk"] = "Slowakije";
$lang["cc"]["tr"] = "Turkije";
$lang["cc"]["uk"] = "Groot Brittannië";

/*******************************
        language codes
*******************************/
$lang["lc"]["en"] = "Engels";
$lang["lc"]["de"] = "Duits";
$lang["lc"]["fr"] = "Frans";
$lang["lc"]["es"] = "Spaans";
$lang["lc"]["nl"] = "Nederlands";
$lang["lc"]["it"] = "Italiaans";
$lang["lc"]["no"] = "Noors";
$lang["lc"]["sv"] = "Zweeds";
$lang["lc"]["fi"] = "Fins";
$lang["lc"]["da"] = "Deens";
$lang["lc"]["pl"] = "Pools";
$lang["lc"]["@@"] = "Internationaal";

/*******************************
    leftmenu.php 
*******************************/
$lang["leftmenu"]["home"]="Home";

$lang["leftmenu"]["rankings"]="Metingen";
$lang["leftmenu"]["rankings_new"]="ACSI meting";
$lang["leftmenu"]["rankings_quick"]="Snelle meting";
$lang["leftmenu"]["rankings_overview"]="Overzicht";
$lang["leftmenu"]["rankings_trends"]="Trendanalyse";

$lang["leftmenu"]["admin"]="Beheer";
$lang["leftmenu"]["admin_searchengines"]="Zoekmachines";
$lang["leftmenu"]["admin_websites"]="Websites";
$lang["leftmenu"]["admin_keyphrases"]="Zoektermen";
$lang["leftmenu"]["admin_errorlog"]="Analysefouten";

/*******************************
    welcome.php
*******************************/
$lang["welcome"]["welcometo"]="Welkom bij";
$lang["welcome"]["nr_of_engines"]="Zoekmachines in database";
$lang["welcome"]["supported_languages"]="phpSERA analyseert op basis van taalgebieden en ondersteunt de volgende talen:";

/*******************************
    newser.php
*******************************/
$lang["newser"]["new_analysis"]="ACSI meting";
$lang["newser"]["analysis"]="Meting";
$lang["newser"]["date"]="Datum";
$lang["newser"]["type"]="Type";
$lang["newser"]["name"]="Naam";
$lang["newser"]["adminlink"]="beheer";
$lang["newser"]["keyphrase"]="Zoekterm(en)";
$lang["newser"]["searchengines"]="Zoekmachines";
$lang["newser"]["select_all"]="alle";
$lang["newser"]["select_none"]="geen";
$lang["newser"]["start"]="Start";
$lang["newser"]["real"]="Meting";
$lang["newser"]["test"]="Testmeting";
$lang["newser"]["saveindb"]="Resultaten opslaan in database";
$lang["newser"]["selectse"]="Zoekmachines per taal";
$lang["newser"]["rampage_mode"]="Zombie mode";
$lang["newser"]["rampage_info"]="Taalcontrole uitschakelen. <i>Waarschuwing:</i> zonder taalcontrole worden alle combinaties van keyphrases en zoekmachines geanalyseerd. Vink dit hokje NIET aan als je regelmatig metingen verricht voor veel keyphrases en websites, dat kost onnodig veel bandbreedte. Schakel de taalcontrole liever uit per trefwoord of zoekmachine via de management pagina's!";
$lang["newser"]["explanation"]="Als je op 'Start' klikt, zal phpSERA de resultaatpagina's van zoekmachines analyseren. Standaard zijn alle keyphrases en zoekmachines geselecteerd. phpSERA kan zélf bepalen welke combinaties van zoekmachines en keyphrases moeten worden geanalyseerd, op basis van taalinstellingen per keyphrase en zoekmachine:
<p>
   <table border=0>
   <tr><th>Instelling</th><th></th><th>Waarde</th><th></th><th>Wel/geen analyse?</th></tr>
   <tr><td>keyphrase taal</td><td></td><td>gelijk aan zoekmachine taal</td><td>=</td><td>wel, voor deze combinatie (normaal)</td></tr>
   <tr><td>keyphrase taal</td><td></td><td>ongelijk aan zoekmachine taal</td><td>=</td><td>geen, voor deze combinatie (normaal)</td></tr>
   <tr><td>keyphrase taal</td><td></td><td>'internationaal'</td><td>=</td><td>wel, en in combinatie met ALLE zoekmachines (uitzondering)</td></tr>
   <tr><td>zoekmachine taal</td><td></td><td>'wereldwijd'</td><td>=</td><td>wel, en in combinatie met ALLE keyphrases (uitzondering)</td></tr>
   <tr><td>taalcontrole</td><td></td><td>uitgeschakeld</td><td>=</td><td>wel, en voor ALLE combinaties (uitzondering)</td></tr>
   </table>
</p>";
$lang["newser"]["explanation_header"]="Uitleg";     

/*******************************
    quick.php
*******************************/
$lang["quick"]["selectse"]="Zoekmachines per taal";
$lang["quick"]["start"]="Start";
$lang["quick"]["explanation_header"]="Uitleg";     
$lang["quick"]["explanation"]="Als je op 'Start' klikt zal phpSERA analyses uitvoeren voor de opgegeven website en keyphrase, in combinatie met alle geselecteerde zoekmachines (ongeacht de taalinstelling van de zoekmachines).";     

/*******************************
    analyze.php
*******************************/
$lang["analyze"]["report"]="Positiemeting";
$lang["analyze"]["website"]="Website";
$lang["analyze"]["phrase(s)"]="Zoekterm(en)";
$lang["analyze"]["type"]="Type";
$lang["analyze"]["searchengine"]="Zoekmachine";
$lang["analyze"]["position"]="Positie";
$lang["analyze"]["notfound"]="niet gevonden";
$lang["analyze"]["real"]="Meting";
$lang["analyze"]["test"]="Testmeting";
$lang["analyze"]["regexerror"]="Fout in regex of leeg zoekresultaat. Svp handmatig checken!";
$lang["analyze"]["hosterror"]="Host niet gevonden!";
$lang["analyze"]["quick"]="Snelle meting";
$lang["analyze"]["endofanalysis"]="Einde van analyse";
$lang["analyze"]["indexed_page"]="Pagina";

/*******************************
    overview.php
*******************************/
$lang["overview"]["overview"]="Metingen en rapportages";
$lang["overview"]["selectwebsite"]="Selecteer een website";
$lang["overview"]["monthreport"]="Maandrapporten";
$lang["overview"]["singlereport"]="Individuele analyses";
$lang["overview"]["monthreport_info"]="Selecteer een website, en vervolgens een periode uit de lijst. Klik 'Open' om de meest recente resultaten van die periode te tonen (er kunnen in die maand meerdere analyses zijn uitgevoerd voor dezelfde site).";
$lang["overview"]["singlereport_info"]="Klik 'Show' om de resultaten van een enkele analyserapport te tonen. Het aantal analyses dat voor het rapport is uitgevoerd staat onder 'Rankings'.";

/*******************************
    trends.php
*******************************/
$lang["trends"]["trends"]="Trendanalyse";

/*******************************
    showreport.php/showreport2.php
*******************************/
$lang["showreport"]["monthreport"]="Maandrapport";
$lang["showreport"]["singlereport"]="Individuele analyse";
$lang["showreport"]["lastonly"]="Dit zijn alleen de resultaten van de laatste analyse in die maand!";
$lang["showreport"]["done_by"]="Uitgevoerd door";
$lang["showreport"]["ranking"]="Ranking";
$lang["showreport"]["keyphrase"]="Keyphrase";
$lang["showreport"]["se"]="Zoekmachine";
$lang["showreport"]["goodrankings"]="Positieve resultaten";
$lang["showreport"]["badrankings"]="Negatieve resultaten (SEO takenlijst)";

/*******************************
    keyphrases.php
*******************************/
$lang["keyphrases"]["select_a_website"]="Selecteer een website";
$lang["keyphrases"]["add_a_keyphrase"]="Keyphrase toevoegen";
$lang["keyphrases"]["keyphrase"]="Keyphrase";
$lang["keyphrases"]["language"]="Taal";
$lang["keyphrases"]["intlkeyphrase"]="Internationale keyphrase. Gebruik deze optie (alleen) voor merknamen ('Google'), leenwoorden ('marketing'), etc. Indien ingeschakeld zal analyse worden uitgevoerd met ALLE zoekmachines, ongeacht de taal van de zoekmachine.";
$lang["keyphrases"]["add"]="Toevoegen";
$lang["keyphrases"]["delete"]="Verwijder";
$lang["keyphrases"]["current_keyphrases"]="Huidige keyphrases";
$lang["keyphrases"]["usage_info"]="Hier worden de keyphrases ingevoerd die voor analyse in aanmerking komen. Gebruik dezelfde spelling als in een zoekmachine. Hoofdletters en accenttekens worden letterlijk overgenomen bij de analyse. Vermijdt de invoer van spellingsvarianten, tenzij uit logfiles van de webserver blijkt dat hier veel bezoekers op binnenkomen. Een maximum van 50 keyphrases per website wordt aangeraden. Keyphrases kunnen worden toegevoegd en verwijderd. Het is NIET mogelijk om keyphrases te wijzigen; dit zou leiden tot corruptie van bestaande analyseresultaten.<p><b>Aanvullen en verwijderen van keyphrases in SERA voor elke taal baseren op:</b> <ul><li>vakantiegedrag inwoners betreffende taalgebied/land. Met name landen, provincies en plaatsen die door hen gemiddeld gezien veel bezocht worden.</li> <li>zoekgedrag van (potentiële) kampeerders bij zoekmachines met het grootste aandeel in dat taalgebied/land. Bronnen: 1) door ACSI uitgevoerde campagnes met gesponsorde links en 2) beschikbare tools bij partijen zoals Overture en Google </li><li>keyphrases die bezoekers hebben ingegeven bij een zoekmachine, waarna betreffende website bezocht is. Bron: logfiles van betreffende ACSI-website.</li><li>aantal woorden waaruit veel ingegeven zoekopdrachten bestaan. Bronnen: 1) campagnes met gesponsorde links, 2) online tools, 3) logfiles.</li></ul><b>Eenmaal ingevoerd kan een keyphrase niet worden gewijzigd via phpSERA! (ter voorkoming van vervuiling van bestaande statistieken; u kunt echter de keyphrase verwijderen en opnieuw toevoegen - bestaande statistieken gerelateerd aan die keyphrase zullen verloren gaan)</b>";
$lang["keyphrases"]["default_lang"]="nl";
$lang["keyphrases"]["priorities"]=array("", "Hoog", "Normaal", "Laag");

/*******************************
    websites.php
*******************************/
$lang["websites"]["add_a_website"]="Website toevoegen";
$lang["websites"]["add"]="Toevoegen";
$lang["websites"]["delete"]="Verwijder";
$lang["websites"]["website_url"]="Website URL";
$lang["websites"]["usage_info"]="Voer de URL in van uw site, zoals deze terug te vinden zal zijn in de resultaatpagina's van de (meeste) zoekmachines. Dit is de tekst waar phpSERA naar zal zoeken. Voer de domeinnaam zo beknopt mogelijk in; 'domein.com' is beter dan 'http://www.domein.com/index.html'. <b>Eenmaal ingevoerd kan een website niet meer worden gewijzigd via phpSERA! (ter voorkoming van vervuiling van bestaande statistieken; u kunt echter de website verwijderen en opnieuw toevoegen - bestaande statistieken gerelateerd aan die website zullen verloren gaan)</b>";

/*******************************
    se_top.php
*******************************/
$lang["se"]["select_a_se"]="Selecteer een zoekmachine";
$lang["se"]["language"]="Taal";
$lang["se"]["script_path"]="Script (path)";
$lang["se"]["data"]="Querydata";
$lang["se"]["host"]="Host";
$lang["se"]["new"]="Nieuw";
$lang["se"]["delete"]="Verwijder";
$lang["se"]["startkey"]="Begin-lijst regex";
$lang["se"]["endkey"]="Einde-lijst regex";
$lang["se"]["separator"]="Scheidingsteken(s)";
$lang["se"]["noresults"]="Geen resultaten";
$lang["se"]["test_settings"]="Test deze instellingen";
$lang["se"]["save_settings"]="Zoekmachine opslaan";
$lang["se"]["testphrase"]="Test zoekterm";
$lang["se"]["showrawhtml"]="HTML broncode";


/*******************************
    graph.php
*******************************/
$lang["graph"]["select_a_se"]="Selecteer een zoekmachine";

?>
