<?php
$ddiirr = "./i/";
$content = '';
if(isset($_GET['pass'])){
    if($_GET['pass'] == "s3cret"){
        $dir = scandir($ddiirr);
        
        $content .= '<h3>Uploaded images</h3>';
        foreach($dir as $file){
            if($file != '.' && $file != '..'){
                $link = 'http://4nk1t.gq/i/'.$file;
                $content .= '<div class="nextTo"><img src="/i/'.$file.'" height="40%" width="auto"><br><span class="link"><a target="_blank" href="'.$link.'">'.$link.'</a></span></div>';
            }
        }
    } else {
        $content = 'Incorrect password!';
    }
} elseif (isset($_POST["submit"])) {
    $random = random_string();
    $target = $ddiirr. $random .'.jpg';
    $link = 'http://4nk1t.gq/i/'. $random .'.jpg';
    
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target);
    $content = 'File successfully uploaded.<br>Link: <a href="'.$link.'">'.$link.'</a>';
}

function random_string($length = 8){
    $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $len = strlen($string);

    $random_string = '';
    for($i = 0; $i < 8; $i++){
        $r = rand(0, $len - 1);
        $random_string .= $string[$r];
    }

    return $random_string;
}
?>
<html>
<head>
<title>Images</title>
<style>
    .nextTo {
        display: inline-block;
        margin-left: 10px;
        margin-right: 10px;
    }
    a {
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
    <div id="content" align="center">
        <?php
            if($content != ''){
                echo $content;
            } else {
        ?>
            <h3>Upload image</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="file" id="file" name="fileToUpload"><br>
                <input type="submit" name="submit" value="Upload!">
            </form>
        <?php
            }
        ?>
    </div>
</body>
</html>