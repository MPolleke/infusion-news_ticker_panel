<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| Copyright © 2019 - 2019 Marcel Pol
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------+

+----------------------------------------------------+
| News Ticker Script by Matonor
| Infusion Code by MrX2003
| v 1.02 fix by Matonor
| v 1.03 update for v9 by Marcel Pol
+---------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

if (file_exists(INFUSIONS."news_ticker_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."news_ticker_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."news_ticker_panel/locale/English.php";
}
opentable($locale['NTIC_001']);
//settings
//0 = no, 1= yes

//show author?
$ticker_author = "1";

//show date?
$ticker_date = "1";

//show commentcount?
$ticker_comments = "1";

//show readcounts?
$ticker_reads = "1";

//the higher the value the slower, default is 100
$ticker_speed = "100";

//ticker width? (use either % or px values)
$ticker_width = "100%";

$tickerquery = dbquery("SELECT tn.*, tu.user_id, user_name ,COUNT(comment_item_id) AS news_comments
	        FROM ".DB_PREFIX."news tn
                LEFT JOIN ".DB_PREFIX."users tu ON tn.news_name=tu.user_id
	        LEFT JOIN ".DB_PREFIX."comments ON news_id=comment_item_id AND comment_type='N'
	        GROUP BY news_id
                ORDER BY news_datestamp DESC LIMIT 0,10");
$ticker_content = "<marquee Behavior='scroll' Direction='left' ScrollDelay='".$ticker_speed."' width='".$ticker_width."' onmouseover='this.stop()' onmouseout='this.start()'>";

     while($data = dbarray($tickerquery)) {
	      $ticker_content .= "<span style='font-weight:bold;'><a href=\"".INFUSIONS."news/news.php?readmore=".$data['news_id']."\">".$data['news_subject']."</a></span> ";
		if($ticker_author+$ticker_date+$ticker_comments+$ticker_reads != "0" )  {
		       $ticker_content .= "[";

			if($ticker_author == "1") {
				$ticker_content .= $locale['NTIC_002'].$data['user_name'];
				if($ticker_date+$ticker_comments+$ticker_reads != "0"){
					$ticker_content .= " | ";
				}
			}



			if($ticker_date == "1") {
				$ticker_content .= showdate("shortdate", $data['news_datestamp']);
				if($ticker_comments+$ticker_reads != "0"){
					$ticker_content .= " | ";
				}
			}


			if($ticker_comments == "1") {
				$ticker_content .= $data['news_comments'] .$locale['NTIC_003'];
				if($ticker_reads != "0"){
					$ticker_content .= " | ";
				}
			}

			if($ticker_reads == "1") {
				$ticker_content .= $data['news_reads'] .$locale['NTIC_004'];
			}

		      $ticker_content .= "]";
		 }
                      $ticker_content .= "      ";
	}
	$ticker_content .= "</marquee>";
	echo $ticker_content;
closetable();
?>
