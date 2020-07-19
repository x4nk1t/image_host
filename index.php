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
    
    if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target)){
        $content = 'File successfully uploaded.<br>Link: <a target="_blank" href="'.$link.'">'.$link.'</a>';
    } else {
        $content = 'Something went wrong while moving file to directory.';
    }
} elseif (isset($_POST['ajax'])){
    $random = random_string();
    $target = $ddiirr. $random .'.jpg';
    $link = 'http://4nk1t.gq/i/'. $random .'.jpg';
    $img = $_POST["imgBase64"];
    
    $img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
    
    if(file_put_contents($target, $data)){
        echo 'File successfully uploaded.<br>Link: <a target="_blank" href="'.$link.'">'.$link.'</a>';
    } else {
        echo 'Something went wrong while moving file to directory.';
    }
    return;
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div id="content" align="center">
        <?php
            if($content != ''){
                echo $content;
            } else {
        ?>
            <div id="page1">
                <h2>Upload image</h2>
                <h4>You can upload or paste image from clipboard.</h4>
                
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="file" id="file" name="fileToUpload"><br>
                    <input type="submit" name="submit" value="Upload!">
                </form>
                
                <canvas id="canvas" hidden></canvas>
            </div>
            
            <div id="page2" hidden><div id="message"></div></div>
        <?php
            }
        ?>
        
        <script>
            document.onpaste = (data) => {
                document.onpaste = null; //To make sure multiple requests are not send.
                var items = data.clipboardData.items;
                
                retrieveImageFromClipboardAsBlob(data, imageBlob => {
                    if(imageBlob){
                        alert('Uploading...')
                        var ctx = canvas.getContext('2d');
                        
                        var img = new Image();

                        img.onload = function(){
                            canvas.width = this.width;
                            canvas.height = this.height;

                            ctx.drawImage(img, 0, 0);
                            
                            var formData = new FormData();
                            formData.append('ajax', '');
                            formData.append('imgBase64', canvas.toDataURL());
                            
                            $.ajax({
                               url: "index.php",
                               type: "POST",
                               data: formData,
                               processData: false,
                               contentType: false,
                            }).done(function(respond){
                                message.innerHTML = respond;
                                page1.hidden = true;
                                page2.hidden = false;
                            });
                        };

                        var URLObj = window.URL || window.webkitURL;
                        
                        img.src = URLObj.createObjectURL(imageBlob);
                    }
                })
            }
            
            function retrieveImageFromClipboardAsBlob(pasteEvent, callback){
                if(pasteEvent.clipboardData == false){
                    if(typeof(callback) == "function"){
                        callback(undefined);
                    }
                };

                var items = pasteEvent.clipboardData.items;

                if(items == undefined){
                    if(typeof(callback) == "function"){
                        callback(undefined);
                    }
                };

                for (var i = 0; i < items.length; i++) {
                    if (items[i].type.indexOf("image") == -1) continue;
                    var blob = items[i].getAsFile();

                    if(typeof(callback) == "function"){
                        callback(blob);
                    }
                }
            }
        </script>
    </div>
</body>
</html>
