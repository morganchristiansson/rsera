<?php
//----------------------------------//
// FRENCH.INC.PHP                   //
//                                  //
// French translations for phpSERA  //
//                                  //
// Marco, 2003-12-10                //
// Matthijs, 2003-12-11             //
// Matthijs, 2004-04-14             //
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
$lang["lc"]["en"] = "Anglais";
$lang["lc"]["de"] = "Allemand";
$lang["lc"]["fr"] = "Fran&ccedil;ais";
$lang["lc"]["es"] = "Espagnol";
$lang["lc"]["nl"] = "Hollandais";
$lang["lc"]["it"] = "Italien";
$lang["lc"]["no"] = "Norv&eacute;gien";
$lang["lc"]["sv"] = "Su&eacute;dois";
$lang["lc"]["fi"] = "Finlandais";
$lang["lc"]["da"] = "Danois";
$lang["lc"]["pl"] = "Polish";
$lang["lc"]["@@"] = "International";

/*******************************
    leftmenu.php 
*******************************/
$lang["leftmenu"]["home"]="Accueil";

$lang["leftmenu"]["rankings"]="Analyses";
$lang["leftmenu"]["rankings_new"]="Nouveau rapport";
$lang["leftmenu"]["rankings_quick"]="Analyse rapide";
$lang["leftmenu"]["rankings_overview"]="Rapports enregistr&eacute;s";
$lang["leftmenu"]["rankings_trends"]="Evolutions";

$lang["leftmenu"]["admin"]="Administration";
$lang["leftmenu"]["admin_searchengines"]="Moteurs";
$lang["leftmenu"]["admin_websites"]="Sites web";
$lang["leftmenu"]["admin_keyphrases"]="Mots-cl&eacute;s";
$lang["leftmenu"]["admin_errorlog"]="Logs d'erreurs";


/*******************************
    welcome.php
*******************************/
$lang["welcome"]["welcometo"]="Bienvenue sur";
$lang["welcome"]["nr_of_engines"]="Nombre de moteurs enregistr&eacute;s";
$lang["welcome"]["supported_languages"]="phpSERA est bas&eacute;e sur les langues (plut&ocirc;t que les pays) et suporte les langues suivantes :";

/*******************************
    newser.php
*******************************/
$lang["newser"]["new_analysis"]="Nouveau rapport d'analyse";
$lang["newser"]["analysis"]="Analyse";
$lang["newser"]["date"]="Date";
$lang["newser"]["type"]="Mode";
$lang["newser"]["name"]="Nom";
$lang["newser"]["adminlink"]="admin";
$lang["newser"]["keyphrase"]="Mot(s)-cl&eacute;(s)";
$lang["newser"]["searchengines"]="Moteurs";
$lang["newser"]["select_all"]="Tous";
$lang["newser"]["select_none"]="Aucun";
$lang["newser"]["start"]="Lancer";
$lang["newser"]["real"]="Rapport (enregistrer l'analyse)";
$lang["newser"]["test"]="Test (ne pas enregistrer l'analyse)";
$lang["newser"]["saveindb"]="enregistrer l'analyse dans la base";
$lang["newser"]["selectse"]="Moteurs (par langue)";
$lang["newser"]["rampage_mode"]="Mode global";
$lang["newser"]["rampage_info"]="Ne fait aucun contr&ocirc;le de langue.  Attention : Le contr&ocirc;le de langue &eacute;tant d&eacute;sactiver, toutes les combinaisons de mots-cl&eacute;s et de moteurs de recherche sont analys&eacute;es. Ne cochez pas cette case si vous faites des analyses r&eacute;guli&egrave;res pour beaucoup de sites et mots-cl&eacute;s, &agrave; moins que &ccedil;a ne vous d&eacute;range pas de surcharger les moteurs de recherche et votre propre syst&egrave;me. Ne pas prendre en compte la langue entraine une surconsommation de la bande passante. Pr&eacute;f&eacute;rez supprimer le contr&ocirc;le de langue pour chaque mot-cl&eacute; et chaque  moteur de recherche dans la partie administration.";
$lang["newser"]["explanation"]="Lorsque vous cliquez sur 'Lancer', phpSERA lance la page des r&eacute;sultats d'analyse des moteurs de recherche. Tous les mot-cl&eacute;s et moteurs de recherche sont s&eacute;lectionn&eacute;s par d&eacute;faut. phpSERA est capable de d&eacute;terminer les combinaisons entre les diff&eacute;rents moteurs de recherche et les mots-cl&eacute;s qui doivent &ecirc;tres analys&eacute;s, en tenant compte de la langue configur&eacute; pour chaque mot-cl&eacute; et chaque moteur de recherche:
<p>
   <table border=0>
   <tr><th>Param&egrave;tre</th><th></th><th>Valeur</th><th></th><th>Analyse ?</th></tr>
   <tr><td>Mot-cl&eacute; par langue</td><td></td><td>comparaison avec le moteur</td><td>=</td><td>Oui - pour ce param&egrave;tre (d&eacute;faut)</td></tr>
   <tr><td>Mot-cl&eacute; par langue</td><td></td><td>pas de comparaison pas avec le moteur</td><td>=</td><td>Non - pour ce param&egrave;tre (d&eacute;faut)</td></tr>
   <tr><td>Mot-cl&eacute; par langue</td><td></td><td>'international'</td><td>=</td><td>Oui - analyse avec TOUS les moteurs de recherche (sauf exception)</td></tr>
   <tr><td>Langue du moteur</td><td></td><td>'international'</td><td>=</td><td>Oui - analyse avec TOUS les mots-cl&eacute;s (sauf exception)</td></tr>
   <tr><td>V&eacute;rification de la langue</td><td></td><td>d&eacute;sactiver</td><td>=</td><td>Oui - analyse avec TOUTES les combinaisons (sauf exception)</td></tr>
   </table>
</p>";
$lang["newser"]["explanation_header"] = "Explications";

/*******************************
    quick.php
*******************************/
$lang["quick"]["selectse"]="Moteurs de recherche (par langue)";
$lang["quick"]["start"]="Lancer";
$lang["quick"]["explanation_header"] = "Explications";
$lang["quick"]["explanation"]="Lorsque vous cliquez sur 'Lancer', phpSERA lance l'analyse pour le site s&eacute;lectionn&eacute; et les mots-cl&eacute;s associ&eacute;s, et pour TOUS les moteurs de recherche s&eacute;lectionn&eacute;s (sans tenir compte de leur propre langue).";

/*******************************
    analyze.php
*******************************/
$lang["analyze"]["report"]="Rapport d'analyse";
$lang["analyze"]["website"]="Site web";
$lang["analyze"]["phrase(s)"]="Mot(s)-cl&eacute;(s)";
$lang["analyze"]["type"]="Mode";
$lang["analyze"]["searchengine"]="Moteur";
$lang["analyze"]["position"]="Position";
$lang["analyze"]["notfound"]="non pr&eacute;sent";
$lang["analyze"]["real"]="Rapport";
$lang["analyze"]["test"]="Test";
$lang["analyze"]["regexerror"]="Erreur dans l'expression reguli&egrave;re (regex) ou liste de r&eacute;sultat vide. SVP v&eacute;rifiez manuellement !";
$lang["analyze"]["hosterror"]="Serveur non trouv&eacute; !";
$lang["analyze"]["quick"]="Analyse rapide";
$lang["analyze"]["endofanalysis"]="Fin de l'analyse";
$lang["analyze"]["indexed_page"]="Indexed page";

/*******************************
    overview.php
*******************************/
$lang["overview"]["overview"]="Rapports enregistr&eacute;s";
$lang["overview"]["selectwebsite"]="S&eacute;lectionnez un site web";
$lang["overview"]["monthreport"]="Rapports mensuels";
$lang["overview"]["singlereport"]="Nombre de recherche";
$lang["overview"]["monthreport_info"]="S&eacute;lectionnez un site web. S&eacute;lectionnez une p&eacute;riode dans la liste. Cliquez 'Ouvrir' pour visualiser l'analyse la plus r&eacute;cente pour cette p&eacute;riode (il est possible qu'il y ai eu plusieurs rapports d'analyses ce mois).";
$lang["overview"]["singlereport_info"]="Cliquez 'Afficher' pour visualiser les r&eacute;sultats d'une analyse. Le nombre de r&egrave;gles appliqu&eacute;es est indiqu&eacute; dans la colonne 'Analyses'.";


/*******************************
    trends.php
*******************************/
$lang["trends"]["trends"]="Evolutions";

/*******************************
    showreport.php
*******************************/
$lang["showreport"]["monthreport"]="Rapport mensuel";
$lang["showreport"]["singlereport"]="Analyse individuelle";
$lang["showreport"]["lastonly"]="Il n'est indiqu&eacute; le rang que pour le DERNIER rapport pour ce mois !";
$lang["showreport"]["done_by"]="Made by";
$lang["showreport"]["ranking"]="Position";
$lang["showreport"]["keyphrase"]="Mot-cl&eacute;";
$lang["showreport"]["se"]="Moteur";
$lang["showreport"]["goodrankings"]="Bons classements";
$lang["showreport"]["badrankings"]="Mauvais classements (SEO todo)";

/*******************************
    keyphrases.php
*******************************/
$lang["keyphrases"]["select_a_website"]="S&eacute;lectionez un site web";
$lang["keyphrases"]["add_a_keyphrase"]="Ajouter un mot-cl&eacute;";
$lang["keyphrases"]["keyphrase"]="Mot-cl&eacute;";
$lang["keyphrases"]["language"]="Langue";
$lang["keyphrases"]["intlkeyphrase"]="Mot-cl&eacute; international. Cochez cette case pour les marques ('Google'), mots techniques ('marketing', 'webdesign'), etc. Ne pas cocher pour un mot-cl&eacute; valable dans une seule langue. Si cette case est coch&eacute;e, l'analyse sera faite pour TOUS les moteurs, sans tenir compte de leur langue.";
$lang["keyphrases"]["add"]="Ajouter";
$lang["keyphrases"]["delete"]="Supprimer";
$lang["keyphrases"]["current_keyphrases"]="Mots-cl&eacute; actuels";
$lang["keyphrases"]["usage_info"]="Page de gestion des mots-clés. Utiliser les mêmes mots-clés que vous souhaitez dans les moteurs. Cette phrase exacte sera utlisée pour l'analyse. Evitez de mettre des phrases n'ayant pas de sens, sauf si vos logs serveur vous indiques que ces phrases sont réellement utilisées. Il est recommandé de ne pas dépasser 50 mots-clés par site. Les mots-clés peuvente être ajoutés et supprimés. <b>Ils ne peuvent pas être modifiés sous peine d'avoir de fausses statistiques (a workaround is to delete the website and add it again - old statistics relating to that specific keyphrase will be lost).</b>";
$lang["keyphrases"]["default_lang"]="fr";
$lang["keyphrases"]["priorities"]=array("", "High", "Normal", "Low");

/*******************************
    websites.php
*******************************/
$lang["websites"]["add_a_website"]="Ajouter un site";
$lang["websites"]["add"]="Ajouter";
$lang["websites"]["delete"]="Supprimer";
$lang["websites"]["website_url"]="URL du site";
$lang["websites"]["usage_info"]="Saisissez l'url de votre site, comme il peut ête trouvé sur les pages de résultats. c'est la phrase exacte que phpSERA va essayer de trouver. Rappelez-vous qu'en mettant 'foobar.com' ici donnera plus de résultat que 'http://www.foobar.com/index.html'. <b>Once added, a website CANNOT be changed through phpSERA! (preventing pollution of existing statistics; a workaround is to delete the website and add it again - old statistics relating to that specific website will be lost)</b>";

/*******************************
    se_top.php
*******************************/
$lang["se"]["select_a_se"]="S&eacute;lectionez ou ajouter un moteur";
$lang["se"]["language"]="Langue";
$lang["se"]["script_path"]="Script (chemin)";
$lang["se"]["data"]="donn&eacute;es demand&eacute;es";
$lang["se"]["host"]="Serveur";
$lang["se"]["new"]="Nouveau";
$lang["se"]["delete"]="Supprimer";
$lang["se"]["startkey"]="D&eacute;but de la liste regex";
$lang["se"]["endkey"]="Fin de la liste regex";
$lang["se"]["separator"]="S&eacute;parateur (pas pour les regex)";
$lang["se"]["noresults"]="Aucun r&eacute;sultat";
$lang["se"]["test_settings"]="Tester ces param&egrave;tres";
$lang["se"]["save_settings"]="Enregistrer ce moteur";
$lang["se"]["testphrase"]="Test keyphrase";
$lang["se"]["showrawhtml"]="Raw HTML";

/*******************************
    graph.php
*******************************/
$lang["graph"]["select_a_se"]="S&eacute;lectionez un moteur de recherche";

?>
