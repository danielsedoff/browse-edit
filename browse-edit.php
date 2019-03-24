<html>
 <head>
  <meta charset="utf-8">
 </head>
 <body>
        
<?php
/* Pass: SamplePassword */
$PASSWORDMD5 = "c463b4b7d56ae82968a7683d29d558e7";
$step = $_POST["step"];
$pass_one = $_POST["password"];
$dirpost = $_POST["dirpost"];
if($dirpost  ==  "") {
   $dirpost=".";
   }

if ($step == "") {
    echo('<form name="temp" action="" method="post">
          <input type="password" name="password">
          <input type="hidden" name="step" value="1">
          <input type="submit" value="Ok"></form>');
    die();
    }

if ($step  ==  "1" && md5($pass_one) != $PASSWORDMD5) {
    die("Wrong pass.");
    }

if ($step  ==  "1" && md5($pass_one)  ==  $PASSWORDMD5) {
    echo('<listing>');
    $dir = (scandir($dirpost));
        
    foreach ($dir as &$diritem) {
        if(is_dir($dirpost . "/" . $diritem)) {
            $diritem = $diritem . " [DIR]";
            }
        }
   
    print_r($dir);
    echo('<BR>');
    echo($dirpost);
    echo('<BR>');
    echo('</listing>
          <form width="100%" name="post" action="" method="post">
          <input type="text" width="100%" name="filename"></input>
          <br>
          <input type="submit" name="ok" value="Ok">
          <input type="hidden" name="step" value="2"></input>
          <input type="hidden" name="dirpost" value="' . $dirpost .'"></input>
          </form>');
    die();
    }

if ($step == "2" && is_dir($dirpost . "/" . $_POST["filename"]) ) {
    $dirpost = ($dirpost . "/" . $_POST["filename"] . "/");
    echo('<listing>');
    $dir = (scandir($dirpost));
        
    foreach ($dir as &$diritem) {
        if(is_dir($dirpost . "/" . $diritem)) {
            $diritem = $diritem . " [DIR]";
            }
        }
   
    print_r($dir);
    echo('<BR>');
    echo($dirpost);
    echo('<BR>');
    echo('</listing>
          <form width="100%" name="post" action="" method="post">
          <input type="text" width="100%" name="filename"></input>
          <br>
          <input type="submit" name="ok" value="Ok">
          <input type="hidden" name="step" value="2"></input>
          <input type="hidden" name="dirpost" value="' . $dirpost .'"></input>
          </form>');
    die();
        
    }

if ($step == "2") {
    if(file_exists($_POST["dirpost"] . "/" . $_POST["filename"])  ==  false) {
        echo('Attention: new file<br>');
        }
    $text = file_get_contents($_POST["dirpost"] . "/" . $_POST["filename"]);
    
    $backupfile = getcwd() . "/backup/" . time() . $_POST["filename"];
    file_put_contents($backupfile, $text);
    echo("Backup in " . $backupfile . "<br>");
    
    $filename =  ($_POST["dirpost"] . "/" . $_POST["filename"]);
    
    $text_enc = base64_encode($text);

    echo('<form width="100%" name="post" action="" method="post">
          <textarea style="width:100%" rows="10" id="textarea1"
          name="file_content"></textarea><br>
          <script>
          
          document.getElementById("textarea1").innerHTML = 
          atob("' . $text_enc . '");
          </script>
          Pass:<input type="password" name="password"></input>
          <br><input type="submit" name="ok" value="Ok">
          <input type="hidden" name="filename" value="');
    echo($filename);
    echo('"></input>');
    echo('<input type="hidden" name="step" value="3"></input>');
    echo('<p>File:');
    echo($filename);
    echo('</p>');
    die();
    }
    
if ($step == "3" && md5($_POST["password"])  ==  $PASSWORDMD5) {
    $filename = $_POST["filename"];

    $file_content = $_POST["file_content"];
    $file_content = stripslashes($file_content);
    file_put_contents ( $filename, $file_content );
    die('<a href="' . htmlpath($filename) . '">Check</a>');
    }

if ($step == "3" && md5($_POST["password"]) != $PASSWORDMD5) {
    die("Wrong pass.");
    }
    

function htmlpath($relative_path) { 
    $realpath = realpath($relative_path);
    $htmlpath = str_replace($_SERVER['DOCUMENT_ROOT'],'',$realpath);
    return $htmlpath;
}


?>

 </body>
</html>
