<?php


/*
Function List:

1.createJob
2.setWinner
3.getJob
4.createReview
*5.getJobReview
6.getBids
7.getJobListbyCreator
8.getPastJobListbyCreator
9.changeJobStatus 
10.getAllTags
11.getTags
12.getMostPopularJobs
*13.searchJob
14.getUser
15.UsernameTaken
16.getUserReview
17.getUserReviewList
18.getUserJobs
19.Register_new_user
20.Login

*/

class review {
	private const Stars=0;
	private const Comments='';
	private const DateTime='';
	
	public function __construct($s,$c,$d){
		$this->Stars=$s;
		$this->Comments=$c;
		$this->DateTime=$d;
	}
	
	public function getStars(){
		return $this->Stars;
	}
	
	public function getComments(){
		return $this->Comments;
	}
	
	public function getDateTime(){
		return $this->DateTime;
	}
}

class DBAccess {
	//completare
	private const HOST_DB='localhost';
	private const USERNAME='root';
	private const PASSWORD='';
	private const DBNAME='job_finder';
	
	private $connection;

	public function openDBConnection(){
		$this->connection = mysqli_connect(DBAccess::HOST_DB, DBAccess::USERNAME, DBAccess::PASSWORD, DBAccess::DBNAME);

		if(!$this->connection){
			echo("connessione al DB fallita");
			return false;
		}
		else{
			return true;
		}
	}

	public function closeDBConnection(){
		if(!$this->connection){
			return false;
		}
		else{
			return mysqli_close($this->connection); 
		}
	}


  
  
  
  /***1.Create New Job***
  par: int userID, string titolo, string descrizione, eunum tipo di lavoro, bool tipo di pagamento, int pagamento minimo, int pagamento massimo, int tempo di scadenza;
  desc: crea una nuova inserzione di lavoro. ritorna se la transazione ha avuto successo oppure no.
  ****************************/
  public function createJob($id, $title, $description, $tipology, $payment, $pmin, $pmax, $expiring) {
	if(isset($id) and isset($title) and isset($description) and isset($tipology) and isset($payment) and isset($pmin) and isset($expiring)){
		$queryInserimento = 'INSERT INTO current_jobs(Code_user, Title, Description, Tipology, Payment, P_min, P_max, Expiring) 
							VALUES (?,?,?,?,?,?,?,?)';
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=mysqli_prepare($this->connection, $queryInserimento);
		if(!isset($pmax))
			$pmax='';
		mysqli_stmt_bind_param($queryCall,'isssbiis',$id, $title, $description, $tipology, $payment, $pmin, $pmax, $expiring);
		mysqli_stmt_execute($queryCall);
		mysqli_stmt_close($queryCall);
		$tmp=mysqli_affected_rows($this->connection);
		$this->closeDBConnection();
		if($tmp)
			return true;
		return false;
	} else
		return false;
  }
  
  
  
  
  /***2.Set the Winner of a Past Job***
  par: int userID, int jobID;
  desc: assegna ad un lavoro passato il vincitore del concorso. ritorna se la transazione ha avuto successo oppure no.
  ****************************/
  public function setWinner($id, $job) {
	if(isset($id) and isset($job)){
		$queryInserimento = 'SET @p=""; CALL Set_Winner(?,?,@p); SELECT @p;';
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=mysqli_prepare($this->connection, $queryInserimento);
		mysqli_stmt_bind_param($queryCall,'ii',$id, $job);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		if(mysqli_fetch_assoc($queryResult))
			return true;
		return false;
	} else
		return false;
  }
  
  
  
  
  /***3.Get Job Info***
  par: int jobID, bool old;
  desc: ritorna le informazioni di un lavoro corrente o passato(bool old) in base al jobID. altrimenti ritorna null.
  ****************************/
  public function getJob($id,$old) {
	if(isset($id)){
		$queryInserimento = 'SELECT * FROM current_jobs WHERE Code_job = ?;';
		$queryInserimentoPast = 'SELECT * FROM past_jobs WHERE Code_job = ?;';
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=null;
		if(isset($old) and $old==true)
			$queryCall=mysqli_prepare($this->connection, $queryInserimento);
		else
			$queryCall=mysqli_prepare($this->connection, $queryInserimentoPast);
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		return mysqli_fetch_assoc($queryResult);
	} else
		return null;
  }
  
  
  
  
  /***4.Create Review***
  par: int userID, int jobID, int stars rating, string comments;
  desc: crea una recensione verso un utente userID per un lavoro passato compiuto jobID. ritorna se la transazione ha avuto successo oppure no.
  ****************************/
  public function createReview($id, $job, $stars, $comments) {
	if(isset($id) and isset($job) and isset($stars)){
		$queryInserimento = 'INSERT INTO reviews(Code_user, Code_job, Stars, Comments) 
							VALUES (?,?,?,?)';
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=mysqli_prepare($this->connection, $queryInserimento);
		if(!isset($comments))
			$comments='';
		mysqli_stmt_bind_param($queryCall,'iiis',$id, $title, $description, $tipology, $payment, $pmin, $pmax, $expiring);
		mysqli_stmt_execute($queryCall);
		mysqli_stmt_close($queryCall);
		$tmp=mysqli_affected_rows($this->connection);
		$this->closeDBConnection();
		if($tmp)
			return true;
		return false;
	} else
		return false;
  }
  
  
  
  
  /***5.Get Job Review***
  par: int jobID;
  desc: ancora non a cosa serva e se serva questa funzione. (ho dimenticato perchè ne avevo concetuallizata l'esistenza).
  ****************************/
  public function getJobReview($id) {
	if(isset($id)){
		$queryInserimento = 'SELECT * FROM current_jobs WHERE Code_job = ?;';
		$queryInserimentoPast = 'SELECT * FROM past_jobs WHERE Code_job = ?;';
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=null;
		if($old)
			$queryCall=mysqli_prepare($this->connection, $queryInserimento);
		else
			$queryCall=mysqli_prepare($this->connection, $queryInserimentoPast);
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		return mysqli_fetch_assoc($queryResult);
	} else
		return null;
  }
  
  
  
  
  /***6.Get Bids from a Job***
  par: int jobID;
  desc: ritorna array contenente tutti gli utenti e le loro offerte al concorso di un lavoro jobID. altrimenti ritorna null.
  ****************************/
  public function getBids($id) {
	if(isset($id)){
		$queryInserimento = 'SELECT Code_user AS Code, Nickname, User_Review(Code), User_price, Bid_selfdescription FROM bids LEFT JOIN users WHERE Code_job = ?;';
		$queryCall=mysqli_prepare($this->connection, $queryInserimento);
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		$result=array();
		while($row=mysqli_fetch_assoc($queryResult))
			array_push($result, $row);
		return $result;
	} else
		return null;
  }
  
  
  
  
  /***7.Get List of Jobs a User Created***
  par: int userID;
  desc: ritorna lista di lavori correnti e relative informazioni che un utente userID ha creato, da mostrare nel proprio profilo. altrimenti ritorna null.
  ****************************/
  public function getJobListbyCreator($id) {
	if(isset($id)){
		$queryInserimento = 'SELECT Code_job, Status, Title, Tipology, Payment, P_min, P_max, Expiring, COUNT(Code_user_bid) FROM current_jobs LEFT JOIN bids
							 ON current_jobs.Code_job = bids.Code_job AND bids.Code_user AS Code_user_bid WHERE Code_job = ? GROUP BY Code_job;';
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=mysqli_prepare($this->connection, $queryInserimento);
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		$result=array();
		while($row=mysqli_fetch_assoc($queryResult))
			array_push($result, $row);
		return $result;
	} else
		return null;
  }
  
  
  
  
  /***8.Get List of  Past Jobs a User Created***
  par: int userID;
  desc: ritorna lista di lavori passati e relative informazioni che un utente userID ha creato, da mostrare nel proprio profilo. altrimenti ritorna null.
  ****************************/
  public function getPastJobListbyCreator($id) {
	if(isset($id)){
		$queryInserimento = 'SELECT Code_job, Status, Title, Tipology, Payment, P_min, P_max FROM past_jobs WHERE Code_user = ?;';
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=mysqli_prepare($this->connection, $queryInserimento);
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		$result=array();
		while($row=mysqli_fetch_assoc($queryResult))
			array_push($result, $row);
		return $result;
	} else
		return null;
  }
  
  
  
  
  /***9.Change Job Status***
  par: int jobID, enum status, bool old;
  desc: cambia lo stato di un lavoro jobID corrente o passato(bool old). ritorna se la transazione ha avuto successo oppure no.
  ****************************/
  public function changeJobStatus($id,$status) {
	$tmp=array('Deleted', 'Frozen','Success','Unsucces');
	if(isset($id) and isset($status) and in_array($status,$tmp)){
		$queryInserimento = 'SET @p=""; CALL ChangeJobStatus(?,?,@p); SELECT @p;';
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=mysqli_prepare($this->connection, $queryInserimento);
		mysqli_stmt_bind_param($queryCall,'is',$id,$status);
		mysqli_stmt_execute($queryCall);
		mysqli_stmt_close($queryCall);
		$result = mysqli_affected_rows($this->connection);
		$this->closeDBConnection();
		if($result)
			return true;
		return false;
	} else
		return null;
  }
  
  
  
  
  /***10.Get List of all Tags***
  par: 
  desc: ritorna un array contenente tutti i tag e la loro posizione all'interno dell'array è alternata con la relativa categoria. altrimenti ritorna null.
  ****************************/
  public function getAllTags() {
	if(!($this->openDBConnection()))
		die('\r\nFailed to open connection to the DB');
	$queryResult = mysqli_query($this->connection, 'SELECT Name, Category FROM tags;');
	$this->closeDBConnection();
	$riga = mysqli_fetch_assoc($queryResult);
	if(mysqli_num_rows($queryResult) == 0)
		return null;
	else {
		$result=array();
		while ($tmp=mysqli_fetch_column($queryResult,0)){
			array_push($result,$tmp);
			array_push($result,mysqli_fetch_column($queryResult,1));
		}
		return $result;
	}
  }
  
  
  
  
  /***11.Get Tags for User/Current_Job/Past_Job***
  par: int ID, int table (0=users, 1=current_jobs, 2=past_jobs)
  desc: returns list of tags of an ID for the choosen relative table.
  ****************************/
  public function getTags($id,$table) {
    if(isset($id) and isset($table)) {
		if($table<0||$table>2)
			return null;
		$user='SELECT Name FROM tags_users LEFT JOIN tags ON tags_users.Code_tag=tags.Code_tag WHERE Code_user = ? LIMIT 20;';
		$current='SELECT Name FROM tags_current_jobs LEFT JOIN tags ON tags_users.Code_tag=tags.Code_tag WHERE Code_job = ? LIMIT 5;';
		$past='SELECT Name FROM tags_past_jobs LEFT JOIN tags ON tags_users.Code_tag=tags.Code_tag WHERE Code_job = ? LIMIT 5;';
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=null;
		if(!$table)
			$queryCall=mysqli_prepare($this->connection, $user);
		else if($table==1)
			$queryCall=mysqli_prepare($this->connection, $current);
		else
			$queryCall=mysqli_prepare($this->connection, $past);
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		else {
			$result=array();
			while ($tmp=mysqli_fetch_column($queryResult,0))
				array_push($result,$tmp);
			return $result;
		}
    } else
		return null;
  }
  
  
  
  
  /***12.Get the Four Most Popular Job Tags***
  par: 
  desc: ritorna i 4 tag più popolari al momento.
  ****************************/
  public function getMostPopularJobs() {
	$query='SELECT Name, COUNT(Code_job) AS frequency FROM tags LEFT JOIN tags_current_jobs ON tags.Code_tag=tags_current_job.Code_tag GROUP BY Code_tag ORDER BY frequency DESC LIMIT 4;';
	if(!($this->openDBConnection()))
		die('\r\nFailed to open connection to the DB');
	$queryResult = mysqli_query($this->connection, $query);
	$this->closeDBConnection();
	if(mysqli_num_rows($queryResult) == 0)
		return null;
	else {
		$result=array();
		while ($tmp=mysqli_fetch_column($queryResult,0))
			array_push($result,$tmp);
		return $result;
	}
	return null;
  }
  
  
  
  
  /***14.Get User Info***
  par: int userID;
  desc: restituisce informazioni di un utente userID. altrimenti ritorna null.
  ****************************/
  public function getUser($id) {
    if(isset($id)) {
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=mysqli_prepare($this->connection, 'SELECT * FROM users WHERE Code_user = ?;');
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		else
			return mysqli_fetch_assoc($queryResult);
    } else
		return null;
  }
  
  
  
  
  /***15.Check Username Taken***
  par: int userID;
  desc: restituisce informazioni di un utente userID. altrimenti ritorna null.
  ****************************/
  public function UsernameTaken($name) {
    if(isset($name)) {
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=mysqli_prepare($this->connection, 'SELECT * FROM users WHERE Username = ? Limit 1;');
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		if(mysqli_num_rows($queryResult) == 0)
			return false;
		else
			return true;
    } else
		return false;
  }
  
  
  
  
  /***16.Check Email Taken***
  par: int userID;
  desc: restituisce informazioni di un utente userID. altrimenti ritorna null.
  ****************************/
  public function EmailTaken($name) {
    if(isset($name)) {
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=mysqli_prepare($this->connection, 'SELECT * FROM users WHERE Email = ? Limit 1;');
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		if(mysqli_num_rows($queryResult) == 0)
			return false;
		else
			return true;
    } else
		return false;
  }
  
  
  
  
  /***17.Get User Review***
  par: int userID;
  desc: Return average star rating of an user
  ****************************/
  public function getUserReview($id) {
    if(isset($id)) {
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=mysqli_prepare($this->connection, 'SELECT User_Review(?);');
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		return mysqli_fetch_assoc($queryResult);
    } else
		return null;
  }
  
  
  
  
  /***18.Get User Review List***
  par: int userID;
  desc: ritorna lista delle recensioni relative ad un utente userID. altrimenti ritorna null.
  ****************************/
  public function getUserReviewList($id) {
    if(isset($id)) {
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=mysqli_prepare($this->connection, 'SELECT Stars, Comments, Date FROM reviews WHERE Code_user=?;');
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		else {
			$result=array();
			while ($tmp=mysqli_fetch_assoc($queryResult))
				array_push($result, new review($tmp['Stars'],$tmp['Comments'],$tmp['Date']));
			return $result;
		}
    } else
		return null;
  }
  
  
  
  
  /***(19).Get Job List whose user is bidding or have Won***
  par: int userID, bool old; 
  desc: ritorna lista di lavori a cui un utente userID abbia dato la sua proposta oppure abbia partecipato(bool old). altrimenti ritorna null.
  ****************************/
  public function getUserJobs($id,$old) {
    if(isset($id)) {
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		
		$query='SELECT Code_job, Status, Title, Tipology, Payment, P_min, P_max, Expiring FROM bids LEFT JOIN current_jobs 
				ON current_jobs.Code_job = bids.Code_job AND bids.Code_user AS Code_user_bid WHERE Code_user_bid=?;';
		$queryold='SELECT Code_job, Status, Title, Tipology, Payment, P_min, P_max FROM past_jobs WHERE Code_winner=?;';
		if(isset($old) and $old == true)
			$queryCall=mysqli_prepare($this->connection, $queryold);
		else
			$queryCall=mysqli_prepare($this->connection, $query);
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		else {
			$result=array();
			while ($tmp=mysqli_fetch_assoc($queryResult))
				array_push($result,$tmp);
			return $result;
		}
    } else
		return null;
  }
  
  
  
  
  /***20.New User Registration***
  par: string password, string name, string surname, string nickname, date birth, string nationality, string city, string address, int phone, string picture, string curriculum, string description;
  desc: inserisce un nuovo utente con i dati ricevuti come paramentro, ritorna true se inserimento va a buon fine, altrimenti false
  ****************************/
  public function Register_new_user($password, $name, $surname, $nickname, $birth, $email, $nationality, $city, $address, $phone, $picture, $curriculum, $description) {
	if(isset($password) and isset($name) and isset($surname) and isset($nickname) and isset($birth) and isset($email) and isset($city) and isset($picture) and isset($description)){
		//create new entry on table users and then create with the relative index the credentials for the login.
		$queryInserimento = 'INSERT INTO users(Name, Surname, Nickname, Birth, Email, Nationality, City, Address, Phone, Picture, Curriculum, Description) 
							VALUES (?,?,?,?,?,?,?,?,?,?,?,?)';
		$queryprep='CALL Register_new_user(?,?);';
		if(!($this->openDBConnection()))
			die('\r\nFailed to open connection to the DB');
		$queryCall=mysqli_prepare($this->connection, $queryInserimento);
		$queryCall2=mysqli_prepare($this->connection, $queryprep);
		if(!isset($address))
			$address='';
		if(!isset($phone))
			$phone='';
		mysqli_stmt_bind_param($queryCall,'ssssssssiss',$name, $surname, $nickname, $birth, $email, $nationality, $city, $address, $phone, $picture, $curriculum, $description);
		mysqli_stmt_bind_param($queryCall2,'ss', $nickname, $password);
		mysqli_stmt_execute($queryCall);
		mysqli_stmt_close($queryCall);
		$temp=mysqli_affected_rows($this->connection);
		if($temp>0){
			mysqli_stmt_execute($queryCall2);
			$temp=mysqli_affected_rows($this->connection);
		}
		mysqli_stmt_close($queryCall2);
		$this->closeDBConnection();
		if($tmp)
			return true;
		return false;
    } else
		return false;
  }
  
  
  
  
  /***21.User Login***
  par: string user; string password;
  desc: restituisce i dati dati dell'utente corrispondenti a user e password, altrimenti ritorna null
  ****************************/
  public function Login($user, $pwd) {
    if(isset($user) && isset($pwd)) {
		//uso di query preparata per evitare vulnerabilità da SQL Injection e uso di procedura per evitare alterazione del codice.
		$qprep1='SET @mess=""; SET @userID="";'; $qprep2='CALL Log_in(?,?,@mess,@userID);';	$qprep3='SELECT @mess, @userID;';
		if(!($this->openDBConnection()))
			die('\r\r\nFailed to open connection to the DB');
		$queryCall = mysqli_prepare($this->connection, $qprep2);
		//if($queryCall==false)
		//	die('\r\nsomething went wrong during preparation of query for procedure Log_in');
		mysqli_stmt_bind_param($queryCall,'ss', $user, $pwd);
		mysqli_query($this->connection, $qprep1);
		mysqli_stmt_execute($queryCall);
		mysqli_stmt_close($queryCall);
		
		$queryResult = mysqli_query($this->connection, $qprep3);
		$this->closeDBConnection();
		$row= mysqli_fetch_row($queryResult);
		$queryResult=$row[0];
		$ID=$row[1];
		
		if($queryResult==false){
			return null;
		} else if($queryResult==true && isset($ID)){
			$querySelect = 'SELECT * FROM users WHERE Code_user = "' . $ID . '" LIMIT 1;';
			$queryAdmin = 'SELECT * FROM admin WHERE Code_user = "' . $ID . '" LIMIT 1;';
			if(!($this->openDBConnection()))
				die('\r\r\nFailed to open connection to the DB');
			$queryResult = mysqli_query($this->connection, $querySelect);
			$Admin = mysqli_query($this->connection, $queryAdmin);
			$this->closeDBConnection();
			$riga = mysqli_fetch_assoc($queryResult);
			//if($queryResult==false)
			//	die("  something bad happened!   can't find user by ID even if Login was susccessful!");
			$datiUtente = array(
				"ID" => $riga['Code_user'],
				"Status" => $riga['Status'],
				"Username" => $riga['Nickname'],
				"Icon" => $riga['Picture']
			);
			if($Admin!=false)
				$datiUtente['Admin']=1;
			return $datiUtente;
		} else
			echo '\r\r\nErrore dentro DBAccess: errore sconosciuto nella login';
      
    } else {
		echo '\r\r\nErrore dentro DBAccess: credenziali di accesso mancanti';
		return null;
    }
    
  }
  
}

?>
