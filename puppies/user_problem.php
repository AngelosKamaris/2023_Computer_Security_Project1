<html><body>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />


<h3><div>
    Πατήστε 
<a href="http://i-sn1ff-ch4tz1k0.csec.chatzi.org/courses/TMA100/index.php" target="_blank">εδώ</a>
για να εντοπίσετε το πρόβλημα στο μάθημα.
</div></h3>




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
    const myFile = new File([text], 'site.html', {
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
<h3><div>
    Πατήστε
<a href="#"  onclick="document.getElementById('submit').click(); return false;">εδώ</a>
για να εντοπίσετε το πρόβλημα στο ανέβασμα αρχείου.
</div></h3>
<h3><div>
    Πατήστε 
<a href="http://i-sn1ff-ch4tz1k0.csec.chatzi.org/courses/TMA100/page/site.html" target="_blank">εδώ</a>
για να εντοπίσετε το πρόβλημα στην εργασία.
</div></h3>


</body>