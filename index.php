<!doctype html>
<html>
<head>
    <title>Music Player</title>

    <meta charset="utf-8" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="./css/style_mp.css">
    <link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<?php
  $year = date("Y");
  $copyright = '© KSV '. $year .'<br>All rights reserved</br>';
 
  function getUserIP()
  {
      // Get real visitor IP behind CloudFlare network
      if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
      }
      $client  = @$_SERVER['HTTP_CLIENT_IP'];
      $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
      $remote  = $_SERVER['REMOTE_ADDR'];
  
      if(filter_var($client, FILTER_VALIDATE_IP))
      {
          $ip = $client;
      }
      elseif(filter_var($forward, FILTER_VALIDATE_IP))
      {
          $ip = $forward;
      }
      else
      {
          $ip = $remote;
      }
  
      return $ip;
  }
  
  
  $user_ip = getUserIP();


echo '<div class="footer"><br><br>Hello, stranger '.$user_ip.'!<br> Let\'s listen to some music!</div><br>';


// Вказуємо шлях до папки
$directory = './mp3';


// Перевіряємо, чи дійсно це папка
if (is_dir($directory)) {
    // Відкриваємо папку
    if ($dh = opendir($directory)) {
        $files = [];

        // Читаємо файли з папки
        while (($file = readdir($dh)) !== false) {
            // Пропускаємо спеціальні папки '.' та '..'
            if ($file != "." && $file != ".." && pathinfo($file, PATHINFO_EXTENSION) == 'mp3') {
                $file_path = $directory . '/' . $file;
                $files[] = ['file' => $file, 'path' => $file_path, 'date' => filemtime($file_path)];
      #           $files[] = ['file' => $file, 'path' => $file, 'date' => filemtime($file)];
            }
        }

        // Закриваємо папку
        closedir($dh);

        // Сортуємо файли за датою (новіші файли вище у списку)
        usort($files, function($a, $b) {
            return $b['date'] - $a['date'];
        });

        //виводимо таблицю
        echo '
        <table class="table" class="html-3">
        <thead>
          <tr>
            <td scope="col">№</td>
            <td scope="col">Filename</td>
            <td scope="col">&#9835;</td>
          </tr>
        </thead>
        <tbody>
        ';


        // Виводимо відсортовані файли
        foreach ($files as $index => $file) {
            $safe_file = htmlspecialchars($file['file'], ENT_QUOTES, 'UTF-8');
            $safe_path = htmlspecialchars($file['path'], ENT_QUOTES, 'UTF-8');
            $js_safe_path = addslashes($file['path']);            
            echo "<tr>";
            echo "<td class=\"number\">" . ($index + 1) . "</td>";
            echo "<td class=\"filename\"><a href='$safe_path' download>$safe_file</a></td>";
            echo "<td class=\"button\"><button onclick=\"playAudio('$js_safe_path')\">Play</button></td>";
            echo "</tr>";
        }

        echo '
        </tbody>
        </table>
        ';


        // Якщо не знайдено жодного mp3 файлу
        if (empty($files)) {
            echo '<div class="footer">There are no any .mp3 files.</div><br>';
        }
    } else {
        echo '<div class="footer"><br><br>Не вдалося відкрити папку '.$directory.'.</div><br>';
    }
} else {
     echo '<div class="footer"><br><br>'.$directory.' не є дійсною папкою.</div><br>';
}
?>

<div class="bg"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>


<footer>
  <div class="footer">
     <?php echo $copyright?>
<hr>	
  </div>
</footer>

<!-- JavaScript код для програвання аудіо -->
<script>
function playAudio(filePath) {
    // Зупиняємо всі програвачі
    var audios = document.querySelectorAll('audio');
    audios.forEach(audio => {
        audio.pause();
        audio.currentTime = 0;
    });

    // Видаляємо всі існуючі аудіо теги
    var existingAudioTags = document.querySelectorAll('.audio-player');
    existingAudioTags.forEach(tag => tag.remove());

    // Створюємо новий аудіо тег та додаємо його в DOM
    var audio = document.createElement('audio');
    audio.src = filePath;
    audio.controls = true;
    audio.autoplay = true;
    audio.classList.add('audio-player');
    //document.body.appendChild(audio);
    audio.style = "top:0.5em;left:0.5em;position:fixed;z-index: 9999";
    document.body.appendChild(audio);
}
</script>


