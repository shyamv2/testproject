<?php
//Cleaning a string, often to XSS protection
function clearString($con, $string){
	$str = ltrim(rtrim(strip_tags($string)));
	return mysqli_real_escape_string($con, $str);
}
//Select
function se($value, $selected){
    return $value == $selected ? ' selected="selected"' : '';
}
//Checkbox
function che($value, $selected){
    return $value == $selected ? ' checked' : '';
}
//Remove all accents in a string
function remove_accents($string, $slug = false) {
	  $string = strtolower($string);
  // Código ASCII das vogais
  $ascii['a'] = range(224, 230);
  $ascii['e'] = range(232, 235);
  $ascii['i'] = range(236, 239);
  $ascii['o'] = array_merge(range(242, 246), array(240, 248));
  $ascii['u'] = range(249, 252);
  // Código ASCII dos outros caracteres
  $ascii['b'] = array(223);
  $ascii['c'] = array(231);
  $ascii['d'] = array(208);
  $ascii['n'] = array(241);
  $ascii['y'] = array(253, 255);
  foreach ($ascii as $key=>$item) {
    $acentos = '';
    foreach ($item AS $codigo) $acentos .= chr($codigo);
    $troca[$key] = '/['.$acentos.']/i';
  }
  $string = preg_replace(array_values($troca), array_keys($troca), $string);
  // Slug?
  if ($slug) {
    // Troca tudo que não for letra ou número por um caractere ($slug)
    $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
    // Tira os caracteres ($slug) repetidos
    $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
    $string = trim($string, $slug);
  }
  return $string;
}
//Transforms a normal string into a link, without accents or special caracters
function linka($string){
		//Remove accents
		$string = strtolower(utf8_decode($string));
		//Remove accents
		$string = remove_accents($string);
		//Remove special caracters
		$carac = array("-", ".", ",", "/", "_", "'", "\"", "!", "?", "(", ")", "[", "]", "^", "°", ":", ";", "º", "ª", "*", "#", "+", "@", "`", "~", "%");
		//Remove unsual spaces
		$string = trim(str_replace($carac, "", $string));
		//Remove unsual spaces
		$texto = preg_replace('/ +/',' ', $string);
		//Replace " " to "-"
		$string = str_replace(" ", "-", $string);
		if($string == ""){
				$string = "na";
		}
		return urldecode($string);
}
function limitName($x, $length){
	if(strlen($x)<=$length)
  {
    return $x;
  }
  else
  {
    $y=substr($x,0,$length) . '...';
    return $y;
  }
}

//Translate date
function translateDateTime($date, $ttype){
    $dt = explode(' ', $date);
    $dt[0] = translateDateHalf($dt[0]);
    $dt[1] = translateTimeHalf($dt[0], $ttype);
    return $dt[0] . ' ' . $dt[1];
}

function translateDateHalf($date){
    $d = explode('-', $date);
    //d = (0 => year, 1 => month, 2 =>day)
    $months = array(
        1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
    );
    $d[1] = intval($d[1]);
    $day = explode(" ", $d[2]);
    return $day[0] . " " . $months[$d[1]] . ", " . $d[0];
}

function translateTimeHalf($date, $ttype){

    $t = explode(':', $date);
    $hr = $t[0];
    $min = $t[1];
    $sec = $t[2];

    $r = turnTime($hr, $min, $sec, $ttype);

}

//($ttype): 1 - no change, 2 - worded, 3 - 12 hr no change, 4 - 12 hr worded, default - no change
function turnTime($hr, $min, $sec, $ttype){
    switch ($ttype){
        case 1:
            return $hr . ":" . $min . ':' . $sec;
            break;
        case 2:
            return $hr . " hours, " . $min . " minutes, " . $sec . " seconds";
            break;
        case 3:
            if ($hr > 12) {
                $apm = 'PM';
                $hr = $hr - 12;
            }
            else{
                $apm = 'AM';
            }
            return $hr . ":" . $min . ':' . $sec . ' ' . $apm;
            break;
        case 4:
            if ($hr > 12) {
                $apm = 'PM';
                $hr = $hr - 12;
            }
            else{
                $apm = 'AM';
            }
            return $hr . " hours, " . $min . " minutes, " . $sec . " seconds";
            break;
        default:
            return $hr . ":" . $min . ':' . $sec;
            break;
    }

}

function formatBytes($bytes, $precision = 2) {
    if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}
function convert_vote_into_border($voted, $button){
  $border = "";
  if($voted == $button){
    $border = "1px solid #34495e";
  }
  else{
    $border = "1px solid #eef";
  }
  return $border;
}
//Generate Random Code to be used in 'create a new group form'
function generateRandomCode(){
	$alfa = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	$code = "";
	for($i = 1; $i <= 7; $i++){
		$code .= $alfa[rand(0, strlen($alfa) - 1)];
	}
	return $code;
}
function idea_link($idea){
	return "/idea/" . $idea['id'] . "-" . linka($idea['name']);
}
function profile_link($user){
	return "/profile/" . $user['id'] . "-" . linka($user['name']);
}
function compress($source, $destination, $quality) {

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
}
function str_limit($text, $limit) {
  if (str_word_count($text, 0) > $limit) {
    $words = str_word_count($text, 2);
    $pos = array_keys($words);
    $text = substr($text, 0, $pos[$limit]);
	}
  return $text;
}
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function translate_genre($genre){
	switch ($genre){
			case 1:
				return "male";
				break;
			case 2:
				return "female";
				break;
			case 3:
				return "other";
				break;
	}
}
function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
function linkify_str($string){
	$text= preg_replace("/(^|[\n ])([\w]*?)([\w]*?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" >$3</a>", $string);
	$text= preg_replace("/(^|[\n ])([\w]*?)((www)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"http://$3\" >$3</a>", $text);
	$text= preg_replace("/(^|[\n ])([\w]*?)((ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"ftp://$3\" >$3</a>", $text);
	$text= preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i", "$1<a href=\"mailto:$2@$3\">$2@$3</a>", $text);
	return($text);
}
function compress_image($source_url, $destination_url, $quality) {
  $info = getimagesize($source_url);

  if ($info['mime'] == 'image/jpeg')
      $image = imagecreatefromjpeg($source_url);

  elseif ($info['mime'] == 'image/gif')
      $image = imagecreatefromgif($source_url);

  elseif ($info['mime'] == 'image/png')
      $image = imagecreatefrompng($source_url);

  imagejpeg($image, $destination_url, $quality);
  return $destination_url;
}
function shorten_post($output, $id, $limit = 5, $page = false) {
	if(!isset($page) OR (isset($page) AND $page == false)){
	  if (strlen($output) > $limit) {
	    $output = substr($output, 0, $limit) . '... <a href="/post/' . $id . '">read more</a>';
	  }
	  return $output;
	}
	else{
		return $output;
		}
}

function file_get_contents_curl($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

function dom_url_parser($url){
				$html = file_get_contents($url);

				//parsing begins here:
				$doc = new DOMDocument();
				@$doc->loadHTML($html);
				$nodes = $doc->getElementsByTagName('title');
				if(isset($nodes->item(0)->nodeValue)){
					$title = $nodes->item(0)->nodeValue;
				}

        $output = array();
				$domain = parse_url($url);
				$output['image'] = 'http://www.google.com/s2/favicons?domain='.$domain['host'];
				$output['domain'] = $domain['host'];
				if(isset($title)){
					$output['title'] = $title;
				}
				else{
					$output['domain'] = $url;
					$output['title'] = $domain['host'];
				}
        return $output;
}

?>
