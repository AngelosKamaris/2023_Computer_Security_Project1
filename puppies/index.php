<html>
  <head>
    <style>
      .center {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
      }
  
      .image-container {
        display: flex;
        justify-content: center;
      }
  
      img {
        width: 400px;
        height: 400px;
        object-fit: cover;
        margin: 10px;
      }
  
      body {
        margin: 0;
        padding: 0;
        background: url('https://media.tenor.com/8-l7HBzVDJYAAAAC/robot-fail.gif') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        color: black;
        text-shadow: 2px 2px 0 white, -2px -2px 0 white, 2px -2px 0 white, -2px 2px 0 white;
      }
    </style>
  </head>
  <body>
    <div class="center">
      <h1><?= "Googling Seminar by Psarotaverna o takis" ?></h1>
      <iframe src="https://www.facebook.com/plugins/video.php?height=420&href=https%3A%2F%2Fwww.facebook.com%2F100064188126442%2Fvideos%2F2194316944103282%2F&show_text=false&width=560&t=0" width="560" height="420" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true" autoplay></iframe>
      <br>
      <h1><?= "Presented by:" ?></h1>
      <div class="image-container">
        <img src="https://debater.gr/wp-content/uploads/2022/05/%CE%B3%CE%B7%CE%BA%CE%BE%CE%B7%CE%B8%CE%BE%CE%B7%CE%B71.jpg">
        <img src="https://image2.fthis.gr/userfiles/articles/AAA_DECEMBER/MAMALAKIS_DIMITRIS.jpg?w=600&h=600&mode=crop&scale=both&quality=85&anchor=topcenter" width="500" height="500"> 
        <img src="https://img.uncyc.org/el/thumb/c/c7/%CE%91%CF%85%CF%84%CE%B1_%CE%BC%CE%B1%CF%81%CE%B5%CF%83%CE%BF%CF%85%CE%BD.gif/300px-%CE%91%CF%85%CF%84%CE%B1_%CE%BC%CE%B1%CF%81%CE%B5%CF%83%CE%BF%CF%85%CE%BD.gif">
      </div>
    </div>
    <?php
$currentPage = $_SERVER['PHP_SELF'];
$newPage = 'index_old.php'; 

// Replace the last segment of the URL with the new page name
$updatedURL = preg_replace('/[^\/]+$/', $newPage, $currentPage);
?>
<a href="<?php echo $updatedURL; ?>">Click here to go to the normal page</a>
  </body>
</html>
