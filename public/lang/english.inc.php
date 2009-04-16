<?php
//----------------------------------//
// ENGLISH.INC.PHP                  //
//                                  //
// English translations for phpSERA //
//                                  //
// Matthijs, 2004-04-15             //
//                                  //
//----------------------------------//

/*******************************
        country names
*******************************/
$lang["cc"]["be"] = "Belgium";
$lang["cc"]["dk"] = "Denmark";
$lang["cc"]["de"] = "Germany";
$lang["cc"]["ie"] = "Ireland";
$lang["cc"]["fi"] = "Finland";
$lang["cc"]["fr"] = "France";
$lang["cc"]["hu"] = "Hungary";
$lang["cc"]["it"] = "Italy";
$lang["cc"]["si"] = "Slovenia";
$lang["cc"]["hr"] = "Croatia";
$lang["cc"]["lu"] = "Luxembourg";
$lang["cc"]["nl"] = "Netherlands";
$lang["cc"]["no"] = "Norway";
$lang["cc"]["at"] = "Austria";
$lang["cc"]["fr"] = "Greece";
$lang["cc"]["pt"] = "Portugal";
$lang["cc"]["pl"] = "Poland";
$lang["cc"]["es"] = "Spain";
$lang["cc"]["se"] = "Sweden";
$lang["cc"]["ch"] = "Switzerland";
$lang["cc"]["cz"] = "Czech Republic";
$lang["cc"]["sk"] = "Slovakia";
$lang["cc"]["tr"] = "Turkey";
$lang["cc"]["uk"] = "Great Britain";

/*******************************
        language codes
*******************************/
$lang["lc"]["en"] = "English";
$lang["lc"]["de"] = "German";
$lang["lc"]["fr"] = "French";
$lang["lc"]["es"] = "Spanish";
$lang["lc"]["nl"] = "Dutch";
$lang["lc"]["it"] = "Italian";
$lang["lc"]["no"] = "Norwegian";
$lang["lc"]["sv"] = "Swedish";
$lang["lc"]["fi"] = "Finish";
$lang["lc"]["da"] = "Danish";
$lang["lc"]["pl"] = "Polish";
$lang["lc"]["@@"] = "International";

/*******************************
    leftmenu.php 
*******************************/
$lang["leftmenu"]["home"]="Home";

$lang["leftmenu"]["rankings"]="Rankings";
$lang["leftmenu"]["rankings_new"]="New report";
$lang["leftmenu"]["rankings_quick"]="Quick ranking";
$lang["leftmenu"]["rankings_overview"]="Overview";
$lang["leftmenu"]["rankings_trends"]="Trends";

$lang["leftmenu"]["admin"]="Admin";
$lang["leftmenu"]["admin_searchengines"]="Search Engines";
$lang["leftmenu"]["admin_websites"]="Websites";
$lang["leftmenu"]["admin_keyphrases"]="Keyphrases";
$lang["leftmenu"]["admin_errorlog"]="Errorlog";

/*******************************
    welcome.php
*******************************/
$lang["welcome"]["welcometo"]="Welcome to";
$lang["welcome"]["nr_of_engines"]="Search engines in database";
$lang["welcome"]["supported_languages"]="phpSERA's analyses based on language (rather than location/country) and currently supports the following languages:";

/*******************************
    newser.php
*******************************/
$lang["newser"]["new_analysis"]="New Ranking Analysis";
$lang["newser"]["analysis"]="Ranking";
$lang["newser"]["date"]="Date";
$lang["newser"]["type"]="Mode";
$lang["newser"]["name"]="Name";
$lang["newser"]["adminlink"]="admin";
$lang["newser"]["keyphrase"]="Keyphrase(s)";
$lang["newser"]["searchengines"]="Search Engines";
$lang["newser"]["select_all"]="all";
$lang["newser"]["select_none"]="none";
$lang["newser"]["start"]="Start";
$lang["newser"]["real"]="Report (save rankings)";
$lang["newser"]["test"]="Test (don't save rankings)";
$lang["newser"]["saveindb"]="Save results in database";
$lang["newser"]["selectse"]="Search Engines (by language)";
$lang["newser"]["rampage_mode"]="Rampage mode";
$lang["newser"]["rampage_info"]="Don't perform any language check. <i>Warning:</i> With the language check disabled, all combinations of keyphrases and search engines are analyzed. Do NOT check this box if you perform regular analysis for many websites and keyphrases, unless you don't care about being a pain in the butt to the search engines and your own sysop. Disabling the language check will lead to a multiplication of bandwidth consumage. Instead, please disable the language check for individual keyphrases and search engines through the management pages.";
$lang["newser"]["explanation"]="When you click 'Start', phpSERA will start analyzing resultpages from search engines. All keyphrases and search engines are selected by default. phpSERA is able to determine which combinations of keyphrases and search engines should be analyzed, based on language settings per keyphrase and per search engine:
<p>
   <table border=0>
   <tr><th>Setting</th><th></th><th>Value</th><th></th><th>Analysis?</th></tr>
   <tr><td>keyphrase language</td><td></td><td>matches search engine</td><td>=</td><td>Yes - for this combination (common)</td></tr>
   <tr><td>keyphrase language</td><td></td><td>doesn't match search engine</td><td>=</td><td>No - for this combination (common)</td></tr>
   <tr><td>keyphrase language</td><td></td><td>'international'</td><td>=</td><td>Yes - analysis performed with ALL search engines (exception)</td></tr>
   <tr><td>search engine language</td><td></td><td>'international'</td><td>=</td><td>Yes - analysis performed with ALL keyphrases (exception)</td></tr>
   <tr><td>language check</td><td></td><td>disabled</td><td>=</td><td>Yes - analysis performed for ALL combinations (exception)</td></tr>
   </table>
</p>";
$lang["newser"]["explanation_header"] = "Explanation";

/*******************************
    quick.php
*******************************/
$lang["quick"]["selectse"]="Search Engines (by language)";
$lang["quick"]["start"]="Start";
$lang["quick"]["explanation_header"] = "Explanation";
$lang["quick"]["explanation"]="When you click 'Start', phpSERA will perform an analysis for the given website and keyphrase, in combination with ALL selected search engines (regardless off their language setting).";   

/*******************************
    analyze.php
*******************************/
$lang["analyze"]["report"]="Ranking Analysis";
$lang["analyze"]["website"]="Website";
$lang["analyze"]["phrase(s)"]="Keyphrase(s)";
$lang["analyze"]["type"]="Mode";
$lang["analyze"]["searchengine"]="Search Engine";
$lang["analyze"]["position"]="Position";
$lang["analyze"]["notfound"]="not listed";
$lang["analyze"]["real"]="Report";
$lang["analyze"]["test"]="Test";
$lang["analyze"]["regexerror"]="Error in regex or empty result list. Please check manually!";
$lang["analyze"]["hosterror"]="Host not found!";
$lang["analyze"]["quick"]="Quick ranking";
$lang["analyze"]["endofanalysis"]="End of analysis";
$lang["analyze"]["indexed_page"]="Indexed page";

/*******************************
    overview.php
*******************************/
$lang["overview"]["overview"]="Report overview";
$lang["overview"]["selectwebsite"]="Select a website";
$lang["overview"]["monthreport"]="Monthly reports";
$lang["overview"]["singlereport"]="Individual analysis";
$lang["overview"]["monthreport_info"]="Select a website. Select a period from the list. Click 'Open' to display the most recent ranking results for that period (one may have created multiple ranking reports during that month).";
$lang["overview"]["singlereport_info"]="Click 'Show' to display the results of a single ranking report. The number of rankings contained within the report is displayed in the column 'Rankings'.";


/*******************************
    trends.php
*******************************/
$lang["trends"]["trends"]="Trend analysis";

/*******************************
    showreport.php
*******************************/
$lang["showreport"]["monthreport"]="Monthly report";
$lang["showreport"]["singlereport"]="Individual analysis";
$lang["showreport"]["lastonly"]="These are only the rankings of the LAST report from this month!";
$lang["showreport"]["done_by"]="Made by";
$lang["showreport"]["ranking"]="Ranking";
$lang["showreport"]["keyphrase"]="Keyphrase";
$lang["showreport"]["se"]="Search Engine";
$lang["showreport"]["goodrankings"]="Good rankings";
$lang["showreport"]["badrankings"]="Bad rankings (SEO todo)";

/*******************************
    keyphrases.php
*******************************/
$lang["keyphrases"]["select_a_website"]="Select a website";
$lang["keyphrases"]["add_a_keyphrase"]="Add a Keyphrase";
$lang["keyphrases"]["keyphrase"]="Keyphrase";
$lang["keyphrases"]["language"]="Language";
$lang["keyphrases"]["intlkeyphrase"]="International keyphrase. Check this box for brandnames ('Google'), loanwords ('marketing', 'webdesign'), etcetera. Only check this box for keyphrases that don't relate 1:1 to a language. If checked, analysis will be performed on ALL search engines, regardless of their language setting.";
$lang["keyphrases"]["add"]="Add";
$lang["keyphrases"]["delete"]="Delete";
$lang["keyphrases"]["current_keyphrases"]="Current Keyphrases";
$lang["keyphrases"]["usage_info"]="This is the keyphrase management page. Use the same keyphrase spelling as you would in a search engine. This exact phrase will be used for analysis. Avoid adding misspelled phrases on purpose, unless your webserver logfiles point out that those misspelled phrases actually lead visitors to your site. A maximum of 50 keyphrases per site is recommended. Keyphrase can be added and deleted. <b>Once added, a keyphrase CANNOT be changed through phpSERA! (preventing pollution of existing statistics; a workaround is to delete the keyphrase and add it again - old statistics relating to that specific keyphrase will be lost)</b>";
$lang["keyphrases"]["default_lang"]="en";
$lang["keyphrases"]["priorities"]=array("", "High", "Normal", "Low");

/*******************************
    websites.php
*******************************/
$lang["websites"]["add_a_website"]="Add a Website";
$lang["websites"]["add"]="Add";
$lang["websites"]["delete"]="Delete";
$lang["websites"]["website_url"]="Website URL";
$lang["websites"]["usage_info"]="Enter your website URL, as it can be found in the resultpages of search engines. This is the exact phrase phpSERA will try to find. Keep in mind that entering 'foobar.com' here may result in less false negatives than entering 'http://www.foobar.com/index.html'. <b>Once added, a website CANNOT be changed through phpSERA! (preventing pollution of existing statistics; a workaround is to delete the website and add it again - old statistics relating to that specific website will be lost)</b>";

/*******************************
    se_top.php
*******************************/
$lang["se"]["select_a_se"]="Select or Add a Search Engine";
$lang["se"]["language"]="Language";
$lang["se"]["script_path"]="Script (path)";
$lang["se"]["data"]="Querydata";
$lang["se"]["host"]="Host";
$lang["se"]["new"]="New";
$lang["se"]["delete"]="Delete";
$lang["se"]["startkey"]="Begin-Of-List regex";
$lang["se"]["endkey"]="End-Of-List regex";
$lang["se"]["separator"]="Separator (no regex)";
$lang["se"]["noresults"]="No results";
$lang["se"]["test_settings"]="Test these settings";
$lang["se"]["save_settings"]="Save this Search Engine";
$lang["se"]["testphrase"]="Test keyphrase";
$lang["se"]["showrawhtml"]="Raw HTML";

/*******************************
    graph.php
*******************************/
$lang["graph"]["select_a_se"]="Select a Search Engine";

?>
