<?php


/*
Function List:

createJob
setWinner
getJob
getOldJob
createReview
*getJobReview
*getBids
*getJobListbyCreator
*changeJobStatus
getAllTags
getTags
*searchJob
getUser
getUserReview
getUserReviewList
getUserJobsBidded
getUserJobsWon
Register_new_user
Login

*/



class DBAccess {
	//completare
	private const HOST_DB="localhost";
	private const USERNAME="root";
	private const PASSWORD="";
	private const DBNAME="job_finder";

	private $connection;
	
	public class review {
		private const Stars;
		private const Comments;
		private const DateTime;
		
		public function __construct($s,$c,$d){
			$this->Stars=$s;
			$this->Comments=$c;
			$this->DateTime=$d;
		}
		
		public function getStars()
			return $this->Stars;
		
		public function getComments()
			return $this->Comments;
		
		public function getDateTime()
			return $this->DateTime;
	}

	public function openDBConnection(){
		$this->connection = mysqli_connect(DBAccess::HOST_DB, DBAccess::USERNAME, DBAccess::PASSWORD, DBAccess::DBNAME);

		if(!$this->connection){
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


  
  
  
  /***Create New Job***
  par: string nome, cognome, telefono, mail, status, note; int numP; datetime data, ora; string status;
  desc: inserisce in db un nuovo utente con i dati ricevuti come paramentro, ritorna true se inserimento va a buon fine, altrimenti false
  ****************************/
  public function createJob($id, $title, $description, $tipology, $payment, $pmin, $pmax, $expiring) {
	//create new entry on table users and then create with the relative index the credentials for the login.
    $queryInserimento = "INSERT INTO current_jobs(Code_user, Title, Description, Tipology, Payment, P_min, P_max, Expiring) 
                          VALUES (?,?,?,?,?,?,?,?)";
	if(!($this->openDBConnection()))
		die("\nFailed to open connection to the DB");
    $queryCall=mysqli_prepare($this->connection, $queryInserimento);
	//if($queryCall==false)
	//	die("\nsomething went wrong during preparation of query inserimento prenotazione");
    mysqli_stmt_bind_param($queryCall,"isssbiis",$id, $title, $description, $tipology, $payment, $pmin, $pmax, $expiring);
	//if($queryCall==false)
	//	die("\nsomething went wrong during bindinig parameters for query inserimento prenotazione");
    mysqli_stmt_execute($queryCall);
	//if($queryCall==false)
	//	die("\nsomething went wrong during execution of query inserimento prenotazione");
	mysqli_stmt_close($queryCall);
	$tmp=mysqli_affected_rows($this->connection);
	$this->closeDBConnection();
    ($tmp)? return true : return false;
  }
  
  
  
  
  /***Set the Winner of a Past Job***
  par: string nome, cognome, telefono, mail, status, note; int numP; datetime data, ora; string status;
  desc: inserisce in db un nuovo utente con i dati ricevuti come paramentro, ritorna true se inserimento va a buon fine, altrimenti false
  ****************************/
  public function setWinner($id, $job) {
	//create new entry on table users and then create with the relative index the credentials for the login.
    $queryInserimento = "SET @p=''; CALL Set_Winner(?,?,@p); SELECT @p;";
	if(!($this->openDBConnection()))
		die("\nFailed to open connection to the DB");
    $queryCall=mysqli_prepare($this->connection, $queryInserimento);
	//if($queryCall==false)
	//	die("\nsomething went wrong during preparation of query inserimento prenotazione");
    mysqli_stmt_bind_param($queryCall,"ii",$id, $job);
	//if($queryCall==false)
	//	die("\nsomething went wrong during bindinig parameters for query inserimento prenotazione");
    mysqli_stmt_execute($queryCall);
	//if($queryCall==false)
	//	die("\nsomething went wrong during execution of query inserimento prenotazione");
	$queryResult = mysqli_stmt_get_result($queryCall);
	mysqli_stmt_close($queryCall);
	$this->closeDBConnection();
    (mysqli_fetch_assoc($queryResult))? return true : return false;
  }
  
  
  
  
  /***Get Job Info***
  par: string nome, cognome, telefono, mail, status, note; int numP; datetime data, ora; string status;
  desc: inserisce in db un nuovo utente con i dati ricevuti come paramentro, ritorna true se inserimento va a buon fine, altrimenti false
  ****************************/
  public function getJob($id,$old) {
	//create new entry on table users and then create with the relative index the credentials for the login.
    $queryInserimento = "SELECT * FROM current_jobs WHERE Code_job = ?;");
	$queryInserimentoPast = "SELECT * FROM past_jobs WHERE Code_job = ?;");
	if(!($this->openDBConnection()))
		die("\nFailed to open connection to the DB");
	$queryCall=null;
	if($old)
		$queryCall=mysqli_prepare($this->connection, $queryInserimento);
	else
		$queryCall=mysqli_prepare($this->connection, $queryInserimentoPast);
	//if($queryCall==false)
	//	die("\nsomething went wrong during preparation of query inserimento prenotazione");
    mysqli_stmt_bind_param($queryCall,"i",$id);
	//if($queryCall==false)
	//	die("\nsomething went wrong during bindinig parameters for query inserimento prenotazione");
    mysqli_stmt_execute($queryCall);
	//if($queryCall==false)
	//	die("\nsomething went wrong during execution of query inserimento prenotazione");
	$queryResult = mysqli_stmt_get_result($queryCall);
	mysqli_stmt_close($queryCall);
	$this->closeDBConnection();
	return mysqli_fetch_assoc($queryResult);
  }
  
  
  
  
  ///***Get Old Job Info***
  //par: string nome, cognome, telefono, mail, status, note; int numP; datetime data, ora; string status;
  //desc: inserisce in db un nuovo utente con i dati ricevuti come paramentro, ritorna true se inserimento va a buon fine, altrimenti false
  //****************************/
  //public function getOldJob($id) {
	////create new entry on table users and then create with the relative index the credentials for the login.
  //  $queryInserimento = "SELECT * FROM past_jobs WHERE Code_job = ?;");
	//if(!($this->openDBConnection()))
	//	die("\nFailed to open connection to the DB");
  //  $queryCall=mysqli_prepare($this->connection, $queryInserimento);
	////if($queryCall==false)
	////	die("\nsomething went wrong during preparation of query inserimento prenotazione");
  //  mysqli_stmt_bind_param($queryCall,"i",$id);
	////if($queryCall==false)
	////	die("\nsomething went wrong during bindinig parameters for query inserimento prenotazione");
  //  mysqli_stmt_execute($queryCall);
	////if($queryCall==false)
	////	die("\nsomething went wrong during execution of query inserimento prenotazione");
	//$queryResult = mysqli_stmt_get_result($queryCall);
	//mysqli_stmt_close($queryCall);
	//$this->closeDBConnection();
	//return mysqli_fetch_assoc($queryResult);
  //}
  
  
  
  
  /***Create Review***
  par: string nome, cognome, telefono, mail, status, note; int numP; datetime data, ora; string status;
  desc: inserisce in db un nuovo utente con i dati ricevuti come paramentro, ritorna true se inserimento va a buon fine, altrimenti false
  ****************************/
  public function createReview($id, $job, $stars, $comments) {
	//create new entry on table users and then create with the relative index the credentials for the login.
    $queryInserimento = "INSERT INTO reviews(Code_user, Code_job, Stars, Comments) 
                          VALUES (?,?,?,?)";
	if(!($this->openDBConnection()))
		die("\nFailed to open connection to the DB");
    $queryCall=mysqli_prepare($this->connection, $queryInserimento);
	//if($queryCall==false)
	//	die("\nsomething went wrong during preparation of query inserimento prenotazione");
    mysqli_stmt_bind_param($queryCall,"iiis",$id, $title, $description, $tipology, $payment, $pmin, $pmax, $expiring);
	//if($queryCall==false)
	//	die("\nsomething went wrong during bindinig parameters for query inserimento prenotazione");
    mysqli_stmt_execute($queryCall);
	//if($queryCall==false)
	//	die("\nsomething went wrong during execution of query inserimento prenotazione");
	mysqli_stmt_close($queryCall);
	$tmp=mysqli_affected_rows($this->connection);
	$this->closeDBConnection();
    ($tmp)? return true : return false;
  }
  
  
  
  
  /***Get Job Review***
  par: string nome, cognome, telefono, mail, status, note; int numP; datetime data, ora; string status;
  desc: inserisce in db un nuovo utente con i dati ricevuti come paramentro, ritorna true se inserimento va a buon fine, altrimenti false
  ****************************/
  public function getJobReview($id) {
	////create new entry on table users and then create with the relative index the credentials for the login.
    $queryInserimento = "SELECT * FROM past_jobs WHERE Code_job = ?;");
	//if(!($this->openDBConnection()))
	//	die("\nFailed to open connection to the DB");
    $queryCall=mysqli_prepare($this->connection, $queryInserimento);
	////if($queryCall==false)
	////	die("\nsomething went wrong during preparation of query inserimento prenotazione");
    mysqli_stmt_bind_param($queryCall,"i",$id);
	////if($queryCall==false)
	////	die("\nsomething went wrong during bindinig parameters for query inserimento prenotazione");
    mysqli_stmt_execute($queryCall);
	////if($queryCall==false)
	////	die("\nsomething went wrong during execution of query inserimento prenotazione");
	//$queryResult = mysqli_stmt_get_result($queryCall);
	//mysqli_stmt_close($queryCall);
	//$this->closeDBConnection();
	//return mysqli_fetch_assoc($queryResult);
  }
  
  
  
  
  /***Get List of all Tags***
  par: 
  desc: returns array alternating name of a tag and it's category.
  ****************************/
  public function getAllTags() {

	if(!($this->openDBConnection()))
		die("\nFailed to open connection to the DB");
	$queryResult = mysqli_query($this->connection, "SELECT Name, Category FROM tags;");
	$this->closeDBConnection();
	$riga = mysqli_fetch_assoc($queryResult);
	if(mysqli_num_rows($queryResult) == 0)
		return null;
	else {
		$result=array();
		while ($tmp=mysqli_fetch_column($queryResult,0)){
			array_push($result,$tmp)
			array_push($result,mysqli_fetch_column($queryResult,1))
		}
		return $result;
	}
  }
  
  
  
  /***Get Tags for User/Current_Job/Past_Job***
  par: int id, int table (0=users, 1=current_jobs, 2=past_jobs)
  desc: returns list of tags of an ID for the choosen relative table.
  ****************************/
  public function getTags($id,$table) {
	
    if(isset($id)) {
		if($table<0||$table>2)
			return null;
		$user="SELECT Name FROM tags_users LEFT JOIN tags ON tags_users.Code_tag=tags.Code_tag WHERE Code_user = ? LIMIT 20;";
		$current="SELECT Name FROM tags_current_jobs LEFT JOIN tags ON tags_users.Code_tag=tags.Code_tag WHERE Code_job = ? LIMIT 5;";
		$past="SELECT Name FROM tags_past_jobs LEFT JOIN tags ON tags_users.Code_tag=tags.Code_tag WHERE Code_job = ? LIMIT 5;";
		if(!($this->openDBConnection()))
			die("\nFailed to open connection to the DB");
		$queryCall=null;
		if(!$table)
			$queryCall=mysqli_prepare($this->connection, $user);
		else if($table==1)
			$queryCall=mysqli_prepare($this->connection, $current);
		else
			$queryCall=mysqli_prepare($this->connection, $past);
		mysqli_stmt_bind_param($queryCall,"i",$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		else {
			$result=array();
			while ($tmp=mysqli_fetch_column($queryResult,0))
				array_push($result,$tmp)
			return $result;
		}
    } else
		return null;
  }
  
  
  /***Get User Info***
  par: int id
  desc: restituisce prenotazione i dati della prenotazione con id passato come parametro
  ****************************/
  public function getUser($id) {
	
    if(isset($id)) {
		if(!($this->openDBConnection()))
			die("\nFailed to open connection to the DB");
		$queryCall=mysqli_prepare($this->connection, "SELECT * FROM users WHERE Code_user = ?;");
		mysqli_stmt_bind_param($queryCall,"i",$id);
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
  
  
  
  
  /***Get User Review***
  par: int id
  desc: Return average star rating of an user
  ****************************/
  public function getUserReview($id) {
	
    if(isset($id)) {
		if(!($this->openDBConnection()))
			die("\nFailed to open connection to the DB");
		$queryCall=mysqli_prepare($this->connection, "SELECT User_Review(?);");
		mysqli_stmt_bind_param($queryCall,"i",$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		return mysqli_fetch_assoc($queryResult);
    } else
		return null;
  }
  
  
  
  
  /***Get User Review List***
  par: int id
  desc: Return average star rating of an user
  ****************************/
  public function getUserReviewList($id) {
	
    if(isset($id)) {
		if(!($this->openDBConnection()))
			die("\nFailed to open connection to the DB");
		$queryCall=mysqli_prepare($this->connection, "SELECT Stars, Comments, Date FROM reviews WHERE Code_user=?;");
		mysqli_stmt_bind_param($queryCall,"i",$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$this->closeDBConnection();
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		else {
			$result=array();
			while ($tmp=mysqli_fetch_assoc($queryResult))
				array_push($result,DBAccess::review($tmp['Stars'],$tmp['Comments'],$tmp['Date']));
			return $result;
		}
    } else
		return null;
  }
  
  
  
  
  /***Get Job List whose user is bidding***
  par: int id
  desc: Return average star rating of an user
  ****************************/
  public function getUserJobs($id) {
	
    if(isset($id)) {
		if(!($this->openDBConnection()))
			die("\nFailed to open connection to the DB");
		$queryCall=mysqli_prepare($this->connection, "SELECT Code_job FROM bids WHERE Code_user=?;");
		mysqli_stmt_bind_param($queryCall,"i",$id);
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
  
  
  
  
  /***New User Registration***
  par: string nome, cognome, telefono, mail, status, note; int numP; datetime data, ora; string status;
  desc: inserisce in db un nuovo utente con i dati ricevuti come paramentro, ritorna true se inserimento va a buon fine, altrimenti false
  ****************************/
  public function Register_new_user($password, $name, $surname, $nickname, $birth, $email, $nationality, $city, $address, $phone, $picture, $curriculum, $description) {
	//create new entry on table users and then create with the relative index the credentials for the login.
    $queryInserimento = "INSERT INTO users(Name, Surname, Nickname, Birth, Email, Nationality, City, Address, Phone, Picture, Curriculum, Description) 
                          VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
	$queryprep="CALL Register_new_user(SELECT Code_user FROM users WHERE Email=?,?,?);";
	if(!($this->openDBConnection()))
		die("\nFailed to open connection to the DB");
    $queryCall=mysqli_prepare($this->connection, $queryInserimento);
    $queryCall2=mysqli_prepare($this->connection, $queryprep);
	//if($queryCall==false)
	//	die("\nsomething went wrong during preparation of query inserimento prenotazione");
    mysqli_stmt_bind_param($queryCall,"ssssssssiss",$name, $surname, $nickname, $birth, $email, $nationality, $city, $address, $phone, $picture, $curriculum, $description);
    mysqli_stmt_bind_param($queryCall2,"sss",$email, $email, $password);
	//if($queryCall==false)
	//	die("\nsomething went wrong during bindinig parameters for query inserimento prenotazione");
    mysqli_stmt_execute($queryCall);
	//if($queryCall==false)
	//	die("\nsomething went wrong during execution of query inserimento prenotazione");
	mysqli_stmt_close($queryCall);
    $temp=mysqli_affected_rows($this->connection);
	if($temp>0){
		mysqli_stmt_execute($queryCall2);
		$temp=mysqli_affected_rows($this->connection);
	}
	mysqli_stmt_close($queryCall2);
	$this->closeDBConnection();
    ($temp > 0)? return true : return false;
  }
  
  
  
  /***User Login***
  par: string user; string password;
  desc: restituisce i dati dati dell'utente corrispondenti a user e password, altrimenti ritorna null
  ****************************/
  public function Login($user, $pwd) {
    if(isset($user) && isset($pwd)) {
		//uso di query preparata per evitare vulnerabilità da SQL Injection e uso di procedura per evitare alterazione del codice.
		$qprep1="SET @mess=''; SET @userID='';"; $qprep2="CALL Login(?,?,@mess);";	$qprep3="SELECT @mess, @userID;";
		if(!($this->openDBConnection()))
			die("\nFailed to open connection to the DB");
		$queryCall = mysqli_prepare($this->connection, $qprep2);
		//if($queryCall==false)
		//	die("\nsomething went wrong during preparation of query for procedure Log_in");
		mysqli_stmt_bind_param($queryCall,"ss", $user, $pwd);
		//send first part of the query
		mysqli_query($this->connection, $qprep1);
		//execute the second part
		mysqli_stmt_execute($queryCall);
		//echo $queryCall->error;
		mysqli_stmt_close($queryCall);
		//send third part and get the result
		$queryResult = mysqli_query($this->connection, $qprep3);
		$this->closeDBConnection();
		$row= mysqli_fetch_row($queryResult);
		$queryResult=$row[0];
		$ID=$row[1];
		
		//some credential are wrong
		if($queryResult=="Error"){
			return null;
		} else if($queryResult=="Success" && isset($ID)){
			//actual success. let's get the user data. this code will be converted in future to prepared statements
			$querySelect = "SELECT * FROM user WHERE Code_user = '" . $ID . "';";
			if(!($this->openDBConnection()))
				die("\nFailed to open connection to the DB");
			$queryResult = mysqli_query($this->connection, $querySelect);
			$this->closeDBConnection();
			$riga = mysqli_fetch_assoc($queryResult);
			
			$datiUtente = array(
				"ID" => $riga['Code_user'],
				"Status" => $riga['Status'],
				"Nickname" => $riga['Nickname'],
				"Icon" => $riga['Picture']
			);
			
			return $datiUtente;
		} else {
			echo "\nsomething broke somewhere";
		}
      
    } else {
		echo "\nalcuni campi sono vuoti";
		return null;
    }
    
  }
  
}


?>