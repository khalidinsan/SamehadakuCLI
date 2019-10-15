<?php
include "samehadakuClass.php";
$sm = new samehadakuClass();
echo "###################################################\n";
echo "#                  SAMEHADAKU CLI                 #\n";
echo "#                  @khalidinsan                   #\n";
echo "###################################################\n\n";

echo "----------------------\n";
echo "Type Download\n";
echo "----------------------\n";
echo "1. Custom Link\n";
echo "2. Lihat daftar anime terbaru\n";
echo "----------------------\n";
$correct_type = false;
while($correct_type==false){
	echo "Pilih Type : ";
	$type = trim(fgets(STDIN));
	if($type==1||$type==2){
		$correct_type = true;
	}else{
		echo "Type yang anda masukkan tidak valid\n";
	}
}
if($type==1){
	$correct_link = false;
	while($correct_link==false){
		echo "\nMasukkan Link : ";
		$link = trim(fgets(STDIN));
		$plink = parse_url($link);
		if(isset($plink['host'])&&$plink['host']=='www.samehadaku.tv'){
			$slug = explode('/', $link);
			$slug = $slug[3];
			$selected_anime_slug = ($slug);
			$correct_link = true;
		}else{
			echo "Link yang anda masukkan tidak valid";
		}
	}
}elseif($type==2){
	$page = 1;
	$anime_selected = false;
	while($anime_selected==false){
		echo "\n----------------------\n";
		echo "List Anime\n";
		echo "----------------------\n";
		$animes = $sm->getNewestAnime($page);
		foreach($animes as $k => $a){
			$no = $k+1;
			echo "$no. ".$a['title']."\n";
		}

		echo "----------------------\n";
		if($page>1){
			echo "Sebelumnya : ".($page-1)."\n";
		}
		echo "Halaman saat ini : $page \n";
		echo "Selanjutnya : ".($page+1)." \n";
		echo "----------------------\n";
		echo "Untuk melihat halaman selanjutnya masukkan N atau untuk melihat halaman sebelumnya masukkan P\n";
		echo "Pilih Anime : ";
		$id = trim(fgets(STDIN));
		if(is_numeric($id)){
			$selected_anime = $animes[$id-1];
			$anime_selected = true;
		}else{
			if($id=="N"||$id=="n"){
				$page++;
			}elseif($id=="P"||$id="p"){
				$page--;
			}
		}
	}

	$selected_anime_slug = ($selected_anime['slug']);
}else{
	echo "System Error";
	exit(0);
}

$anime_detail = $sm->getAnimeDetails($selected_anime_slug);

echo "\n----------------------\n";
echo $anime_detail['title']."\n";
echo "----------------------\n";

foreach($anime_detail['video_type'] as $k => $j){
	$no = $k+1;
	echo "$no. $j\n";
}

echo "Pilih Jenis Video : ";
$jenis_video = trim(fgets(STDIN));
echo "----------------------\n";
$no=1;
$a_kualitas_video = array();
foreach($anime_detail['download_links'][$jenis_video-1] as $k => $d){
	echo "$no. $k\n";
	$a_kualitas_video[] = $k;
	$no++;
}
echo "Pilih Kualitas Video : ";
$kualitas_video = trim(fgets(STDIN));
echo "----------------------\n";
echo "Mendapatkan link download ...\n";
echo "----------------------\n";
$t_kualitas_video = $a_kualitas_video[$kualitas_video-1];
foreach($anime_detail['download_links'][$jenis_video-1][$t_kualitas_video] as $k => $d){
	echo $d['server']." => ".$sm->Bypass($d['link'])."\n";
}
echo "----------------------\n";