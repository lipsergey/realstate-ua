<?php
/* MySQL settings */

$shhost="localhost"; 
$shuser="root";
$shpass="";
$shname="ua_realstate";

$ppt = "realst_"; //project prefix

$citename = "RealStateDB"; //Site name for admin console
define("ADMINICONSINLINE", 6); //icons per line

$SPUrl = "/xampp/htdocs/realstate-ua/";
$SRVUrl = "http://localhost/realstate-ua/";


define("SERVERCORE", "15");
define("MAINIMGCAT", "imgs/"); //image folder
define("MAINFILESCAT", "uplfiles/"); //main folders of downloading files

// LETTER COMPILATION CONFIGURATION START
define("SRVMAILADDR", "test@test.ru"); //email of site robot
define("SRVMAILPODPIS", "RealState"); //Site name for email
// LETTER COMPILATION CONFIGURATION END

define("ADMUSERPASSWLEIGHT", 10); //chars in admin password
define("HUMANURLSYSTEM", 0);
define("COOKIEPREFIX", "rstDS_"); //префикс куки
define("COOKIE_SALT_CHECK", "8t0qptQELB"); //добавка для верификации пароля

$LangList = array(
	"1" => array("name" => "український", "sqlf" => "UaText"),
	"2" => array("name" => "русский", "sqlf" => "RusText"),
	//"3" => array("name" => "английский", "sqlf" => "EngText"),
);

$Valuts = array(
	"1" => "TRNSL-GRIVNA",
	"2" => "TRNSL-DOLLAR",
	"3" => "TRNSL-EURO",
);

$ValutChars = array(
	"1" => "грн",
	"2" => "$",
	"3" => "&euro;",
);

$UserAccSel = array(
	"0" => "TRNSL-NO",
	"1" => "TRNSL-YES",
);

$UserViewFL = array(
	"0" => "TRNSL-NO",
	"1" => "TRNSL-YES",
	//"2" => "TRNSL-NO-MATTER",
);

$UserGrops = array(
	"1" => "TRNSL-USER-MANAGER",
	"2" => "TRNSL-USER-OPERATOR",
	"3" => "TRNSL-USER-ADMIN",
	"4" => "TRNSL-USER-REALTOR",
);

$WallMaterial = array(
	"1" => "TRNSL-MATERAL-PANEL",
	"2" => "TRNSL-MATERAL-BRICK",
	"3" => "TRNSL-MATERAL-BLOCK",
);

$EnterIntoFlat = array(
	"1" => "TRNSL-FL-ENT-PARADN",
	"2" => "TRNSL-FL-ENT-BALC",
	"3" => "TRNSL-FL-ENT-BALC-KITHC",
	"4" => "TRNSL-FL-ENT-CORR",
	"5" => "TRNSL-FL-ENT-KITCH",
	"6" => "TRNSL-FL-ENT-ROOM",
);

$HouseType = array(
	"1" => "TRNSL-FL-COMMUN",
	"1" => "TRNSL-FL-OBSHEJIT",
	"3" => "TRNSL-FL-KVALERK",
	"4" => "TRNSL-FL-MALOSEM",
	"5" => "TRNSL-FL-HRUCHEV",
	"6" => "TRNSL-FL-BREJNEV",
	"7" => "TRNSL-FL-STALIN",
	"8" => "TRNSL-FL-KIRP-MNOGOET",
	"9" => "TRNSL-FL-CHESH",
	"10" => "TRNSL-FL-POLSK",
	"11" => "TRNSL-FL-POLSK-LUX",
	"12" => "TRNSL-FL-ASTROVENG",
	"13" => "TRNSL-FL-NOVOSTROI",
);

$HeatType = array(
	"1" => "TRNSL-HT-CENTER",
	"2" => "TRNSL-HT-BAKE",
	"3" => "TRNSL-HT-NOHT",
	"4" => "TRNSL-HT-INDIV",
);

$Santehtype = array(
	"1" => "TRNSL-SNT-SOV",
	"2" => "TRNSL-SNT-90",
	"3" => "TRNSL-SNT-2000",
	"4" => "TRNSL-SNT-NORM",
	"5" => "TRNSL-SNT-SOVREM",
);

$Perekrit = array(
	"1" => "TRNSL-PEREKR-WOOD",
	"2" => "TRNSL-PEREKR-BETON",
);

$Mebel = array(
	"1" => "TRNSL-MEBEL-SOVREM",
	"2" => "TRNSL-MEBEL-DISIGN",
	"3" => "TRNSL-MEBEL-2000",
	"4" => "TRNSL-MEBEL-90",
	"5" => "TRNSL-MEBEL-SOV",
);

$Remont = array(
	"1" => "TRNSL-REMONT-SOV",
	"2" => "TRNSL-REMONT-SANIT",
	"3" => "TRNSL-REMONT-SOVREM",
	"4" => "TRNSL-REMONT-DISIGN",
);

$Kuhny = array(
	"1" => "TRNSL-KUHNI-SOV",
	"2" => "TRNSL-KUHNI-90",
	"3" => "TRNSL-KUHNI-2000",
	"4" => "TRNSL-KUHNI-NORM",
	"5" => "TRNSL-KUHNI-SOVREM",
);

$ObjTypes = array(
	"1" => "TRNSL-OBJ-TYPE-FLAT",
	"2" => "TRNSL-OBJ-TYPE-ROOM",
	"3" => "TRNSL-OBJ-TYPE-HOUSE",
	"4" => "TRNSL-OBJ-TYPE-CHOUSE",
);



define("OBJECTSPERPAGE", 100); //number of objects per page

define("PAGENUMBERHTML", "[ %PAGENUMBER% ]"); //HTML code of page number
define("PAGEBLOCKHTML", "<div class=\"pager\">Page: %PAGENUMBERS%</div>"); //HTML code of page block
define("USEBLOCKSINPAGEBLOCK", 1); //Use blocks in page listener
define("BLOCKOFVISIBLEPAGES", 7); //Number of pages in one block
define("BLOCKTOTALHTML", "<div class=\"pager\">Pages: %BLOCKCODE%</div>"); //HTML code of one block
define("BLOCKFIRSTPAGEHTML", "<a href=\"%FIRSTPAGEURL%\"><<</A>"); //HTML code first page
define("BLOCKLASTPAGEHTML", "<a href=\"%LASTPAGEURL%\">>></A>"); //HTML code last page
define("BLOCKNEXTBLOCKHTML", "<a href=\"%NEXTBLOCK%\">></a>"); //HTML code next block
define("BLOCKPREVBLOCKHTML", "<a href=\"%PREVBLOCK%\"><</a>"); //HTML code previous block

define("IMGSMALLWIDTH", 200);
define("IMGSMALLHEIGHT", 150);
define("IMGBIGWIDTH", 800);
define("IMGBIGHEIGHT", 0);



/*
$Monthes = array(
	"1" => "January",
	"2" => "February",
	"3" => "March",
	"4" => "April",
	"5" => "May",
	"6" => "June",
	"7" => "July",
	"8" => "August",
	"9" => "September",
	"10" => "October",
	"11" => "November",
	"12" => "December"
);

*/

?>