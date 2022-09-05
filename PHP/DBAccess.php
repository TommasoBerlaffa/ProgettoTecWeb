<?php


/*
Function List:

1.createJob
2.setWinner
2.deleteJob
3.getJob
4.createReview
5.getJobReview
6.getBids
7.getJobListbyCreator
8.getPastJobListbyCreator
9.changeJobStatus
10.getAllTags
11.getTags
11.2 searchTags
11.3 searchTagName
12.getMostPopularJobs
13.searchJob
14.getUser
15.usernameTaken
16.emailTakens
17.getUserReview
18.getUserReviewList
19.getUserJobs
20.register_new_user
21.login
22.createBid
23.changePassword
24.changeUserInfo
25.removeBid
26.AdminFunctions ( GetUsers, GetOffers, GetJobs)
*/

class DBAccess {
	//completare
	//private const HOST_DB='localhost';
	private const HOST_DB='127.0.0.1';
	private const USERNAME='root';
	private const PASSWORD='';
	private const DBNAME='job_finder';

	private $connection;

	public function openDBConnection(){
		$this->connection = mysqli_connect(DBAccess::HOST_DB, DBAccess::USERNAME, DBAccess::PASSWORD, DBAccess::DBNAME);
		if(!$this->connection){
			echo("connessione al DB fallita: ".mysqli_connect_errno());
			return false;
		}
		else
			return true;
	}

	public function closeDBConnection(){
		if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
			return mysqli_close($this->connection);
		else
			return false;
	}

  /***1.Create New Job***
  par: int userID, string titolo, string descrizione, eunum tipo di lavoro, bool tipo di pagamento, int pagamento minimo, int pagamento massimo, int tempo di scadenza;
  desc: crea una nuova inserzione di lavoro. ritorna se la transazione ha avuto successo oppure no.
  ****************************/
  public function createJob($id, $title, $description, $tipology, $payment, $pmin, $pmax, $expiring, $tags) {
	if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
        die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id) and isset($title) and isset($description) and isset($tipology) and isset($payment) and isset($pmin) and isset($expiring) and isset($tags)){
		$queryInserimento = 'INSERT INTO current_jobs(Code_user, Date ,Title, Description, Tipology, Payment, P_min, P_max, Expiring)
					VALUES (?,?,?,?,?,?,?,?,?)';
		$queryCall=mysqli_prepare($this->connection, $queryInserimento);
		if(!isset($pmax))
			$pmax='';
		mysqli_stmt_bind_param($queryCall,'issssiiis',$id, date("Y-m-d h:i:sa"), $title, $description, $tipology, $payment, $pmin, $pmax, date('Y-m-d h:i:sa', strtotime(' + ' .$expiring. ' hours')));
		mysqli_stmt_execute($queryCall);
		mysqli_stmt_close($queryCall);
		$Code_job=mysqli_insert_id($this->connection);
		if(mysqli_affected_rows($this->connection)){
			$queryTags= 'INSERT INTO tags_current_jobs(Code_job,Code_tag) VALUES ';
			$a='';
			$b=array();
			foreach($tags as $tag){
				$queryTags .= '(?,?),';
				$a.='ii';
				array_push($b,$Code_job,$tag );
			}
			$queryTags=rtrim($queryTags, ",");
			$queryCall=mysqli_prepare($this->connection, $queryTags);
			$queryCall->bind_param($a, ...$b);
			mysqli_stmt_execute($queryCall);
			$tmp=mysqli_affected_rows($this->connection);
			mysqli_stmt_close($queryCall);
			if($tmp)
				return true;
		}
		else
			return false;
    } else
      return false;
  }

  /***2.Set the Winner ***
  par: int winnerID, int jobID, int userID;
  desc: assegna ad un lavoro passato il vincitore del concorso. ritorna se la transazione ha avuto successo oppure no.
  ****************************/
  public function setWinner($winner, $job, $id) {
	if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id) and isset($job) and isset($winner)){
      $queryInserimento = 'CALL Set_Winner(?,?,?);';
      $queryCall=mysqli_prepare($this->connection, $queryInserimento);
      mysqli_stmt_bind_param($queryCall,'iii',$winner, $job, $id);
      mysqli_stmt_execute($queryCall);
      $queryResult = mysqli_stmt_get_result($queryCall);
      mysqli_stmt_close($queryCall);
      if(mysqli_affected_rows($this->connection))
        return true;
      return false;
    } else
      return false;
  }
  
  /***14.Get User Info***
  par: int userID;
  desc: restituisce informazioni di un utente userID. altrimenti ritorna null.
  ****************************/
  public function getWinner($job) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
		die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($job)) {
		$queryCall=mysqli_prepare($this->connection, 'SELECT `Code_winner` FROM `past_jobs` WHERE `Code_job` = ? LIMIT 1;');
		mysqli_stmt_bind_param($queryCall,'i',$job);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		else
			return mysqli_fetch_assoc($queryResult);
} else
		return null;
  }
  
  
  /***2.Delete a Job ***
  par: int userID, int jobID;
  desc: assegna ad un lavoro passato il vincitore del concorso. ritorna se la transazione ha avuto successo oppure no.
  ****************************/
  public function deleteJob($id, $job) {
	if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id) and isset($job)){
      $queryInserimento = 'CALL Delete_job(?,?);';
      $queryCall=mysqli_prepare($this->connection, $queryInserimento);
      mysqli_stmt_bind_param($queryCall,'ii',$job, $id);
      mysqli_stmt_execute($queryCall);
      mysqli_stmt_close($queryCall);
      $result = mysqli_affected_rows($this->connection);
      if($result)
        return true;
      return false;
    } else
      return false;
  }

  /***2.1.Terminate a Job ***
  par: int userID, int jobID;
  desc: termina il tempo disponibile per un lavoro
  ****************************/
  public function terminateJob($id, $job) {
    if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
        die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
      if(isset($id) and isset($job)){
        $queryInserimento = 'UPDATE current_jobs SET Expiring = ? WHERE Code_job = ? AND Code_user = ?;';
        $queryCall=mysqli_prepare($this->connection, $queryInserimento);
        mysqli_stmt_bind_param($queryCall,'sii',date("Y-m-d h:i:sa",strtotime("-1 minutes")),$job, $id);
        mysqli_stmt_execute($queryCall);
        mysqli_stmt_close($queryCall);
        $result = mysqli_affected_rows($this->connection);
        if($result)
          return true;
        return false;
      } else
        return false;
    }
  

  /***3.Get Job Info***
  par: int jobID;
  desc: ritorna le informazioni di un lavoro (corrente o passato)in base al jobID. altrimenti ritorna null.
  ****************************/
  public function getJob($id) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id)){
      $queryCall=mysqli_prepare($this->connection, 'SELECT * FROM current_jobs WHERE Code_job = ? LIMIT 1;');
	  mysqli_stmt_bind_param($queryCall,'i',$id);
      mysqli_stmt_execute($queryCall);
      $queryResult = mysqli_stmt_get_result($queryCall);
      mysqli_stmt_close($queryCall);
	  $queryResult=mysqli_fetch_assoc($queryResult);
	  if(!$queryResult){
		$queryCall=mysqli_prepare($this->connection, 'SELECT * FROM past_jobs WHERE Code_job = ? LIMIT 1;');
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		$queryResult=mysqli_fetch_assoc($queryResult);
	  }
      return $queryResult;
    } else
      return null;
  }

  /***4.Create Review***
  par: int userID, int jobID, int stars rating, string comments;
  desc: crea una recensione verso un utente userID per un lavoro passato compiuto jobID. ritorna se la transazione ha avuto successo oppure no.
  ****************************/
  public function createReview($id, $job, $stars, $comments) {
	if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
    die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id) and isset($job) and isset($stars)){
      $queryInserimento = 'INSERT INTO reviews(Code_user, Code_job, Stars, Comments)
                VALUES (?,?,?,?)';
      $queryCall=mysqli_prepare($this->connection, $queryInserimento);
      if(!isset($comments))
        $comments='';
      mysqli_stmt_bind_param($queryCall,'iiis',$id, $job, $stars, $comments);
      mysqli_stmt_execute($queryCall);
      mysqli_stmt_close($queryCall);
      $tmp=mysqli_affected_rows($this->connection);
      if($tmp)
        return true;
      return false;
    } else
      return false;
  }

  /***5.Get Job Review***
  par: int jobID;
  desc: information of the reviews about one job.
  ****************************/
  public function getJobReview($id) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id)){
      $queryInserimento = 'SELECT P.Code_user AS C_Rew, R.Code_user AS C_User, R.Stars , R.Comments, R.Date 
        FROM reviews AS R INNER JOIN past_jobs AS P WHERE R.Code_job =P.Code_job AND R.Code_job = ?;';
      $queryCall=null;
      $queryCall=mysqli_prepare($this->connection, $queryInserimento);
      mysqli_stmt_bind_param($queryCall,'i',$id);
      mysqli_stmt_execute($queryCall);
      $queryResult = mysqli_stmt_get_result($queryCall);
      mysqli_stmt_close($queryCall);
      if(mysqli_num_rows($queryResult) == 0)
        return null;
      else {
        if(mysqli_num_rows($queryResult) > 1)
          return null;
        else
          return mysqli_fetch_assoc($queryResult);
      }
    } else
      return null;
  }

  /***6.Get Bids from a Job***
  par: int jobID;
  desc: ritorna array contenente tutti gli utenti e le loro offerte al concorso di un lavoro jobID. altrimenti ritorna null.
  ****************************/
  public function getBids($id) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id)){
      $queryInserimento = 'SELECT users.Code_user AS Code, users.Picture as PFP,users.Nickname, bids.User_price AS Price, bids.Bid_selfdescription AS Description
							FROM bids INNER JOIN users ON bids.Code_user=users.Code_user WHERE Code_job = ? ;';
      $queryCall=mysqli_prepare($this->connection, $queryInserimento);
      mysqli_stmt_bind_param($queryCall,'i',$id);
      mysqli_stmt_execute($queryCall);
      $queryResult = mysqli_stmt_get_result($queryCall);
      mysqli_stmt_close($queryCall);
      if(mysqli_num_rows($queryResult) == 0)
        return null;
      $result=array();
      while($row=mysqli_fetch_assoc($queryResult))
        array_push($result, $row);
      return $result;
    } else
      return null;
  }
  
    /***6.Get Bids from a Job***
  par: int jobID;
  desc: ritorna array contenente tutti gli utenti e le loro offerte al concorso di un lavoro jobID. altrimenti ritorna null.
  ****************************/
  public function getWinnerBid($job) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($job)){
      $queryInserimento = 'SELECT * FROM bid_winner WHERE Code_job = ? LIMIT 1;';
      $queryCall=mysqli_prepare($this->connection, $queryInserimento);
      mysqli_stmt_bind_param($queryCall,'i',$job);
      mysqli_stmt_execute($queryCall);
      $queryResult = mysqli_stmt_get_result($queryCall);
      mysqli_stmt_close($queryCall);
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
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id)){
      $queryInserimento = 'SELECT COUNT(DISTINCT bids.Code_user) AS C,current_jobs.Code_job, Title, Tipology, Payment, P_min, P_max, Expiring 
      FROM current_jobs LEFT JOIN bids on current_jobs.Code_job = bids.Code_job WHERE current_jobs.Code_user = ? GROUP BY current_jobs.Code_job;';
      $queryCall=mysqli_prepare($this->connection, $queryInserimento);
      mysqli_stmt_bind_param($queryCall,'i',$id);
      mysqli_stmt_execute($queryCall);
      $queryResult = mysqli_stmt_get_result($queryCall);
      mysqli_stmt_close($queryCall);
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
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id)){
      $queryInserimento = 'SELECT Code_job, Status, Title, Tipology, Payment, P_min, P_max FROM past_jobs WHERE Code_user = ?;';
      $queryCall=mysqli_prepare($this->connection, $queryInserimento);
      mysqli_stmt_bind_param($queryCall,'i',$id);
      mysqli_stmt_execute($queryCall);
      $queryResult = mysqli_stmt_get_result($queryCall);
      mysqli_stmt_close($queryCall);
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
  par: int jobID, enum status;
  desc: cambia lo stato di un lavoro jobID passato. ritorna se la transazione ha avuto successo oppure no.
  ****************************/
  public function changeJobStatus($id,$status) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
	$queryInserimento = '';
    if(isset($id) and isset($status) and in_array($status,array('Deleted','Success','Unsucces'))){
		$queryInserimento = 'UPDATE `past_jobs` SET `Status` = ? WHERE `past_jobs`.`Code_job` = ?;';		
		$queryCall=mysqli_prepare($this->connection, $queryInserimento);
		mysqli_stmt_bind_param($queryCall,'si',$status,$id);
		mysqli_stmt_execute($queryCall);
		mysqli_stmt_close($queryCall);
		$result = mysqli_affected_rows($this->connection);
		if($result)
			return true;
		return false;
	}
    else
		return null;
  }

  /***10.Get List of all Tags***
  par:
  desc: ritorna un array contenente tutti i tag e la loro posizione all'interno dell'array è alternata con la relativa categoria. altrimenti ritorna null.
  ****************************/
  public function getAllTags() {
    if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    $queryResult = mysqli_query($this->connection, 'SELECT * FROM tags;');
    $riga = mysqli_fetch_assoc($queryResult);
    if(mysqli_num_rows($queryResult) == 0)
      return null;
    else {
      $result=array();
      while($row=mysqli_fetch_assoc($queryResult))
        array_push($result, $row);
      return $result;
    }
  }

  /***11.Get Tags for User/Current_Job/Past_Job***
  par: int ID, int table (0=users, 1=current_jobs, 2=past_jobs)
  desc: returns list of tags of an ID for the choosen relative table.
  ****************************/
  public function getTags($id,$table) {
	if(!(mysqli_ping($this->connection)))
		die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id) and isset($table)) {
		if($table<0||$table>2)
			return null;
		$user='SELECT tags_users.Code_tag, Name FROM tags_users INNER JOIN tags ON tags_users.Code_tag=tags.Code_tag WHERE Code_user = ?;';
		$current='SELECT tags_current_jobs.Code_tag, Name FROM tags_current_jobs INNER JOIN tags ON tags_current_jobs.Code_tag=tags.Code_tag WHERE Code_job = ?;';
		$past='SELECT tags_past_jobs.Code_tag, Name FROM tags_past_jobs INNER JOIN tags ON tags_past_jobs.Code_tag=tags.Code_tag WHERE Code_job = ?;';
		
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
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		else {
			$result=array();
			while ($tmp=mysqli_fetch_row($queryResult))
				$result[$tmp[1]]=$tmp[0];
			return $result;
		}
    } else
		return null;
  }
	
	
	
  /***11.2 Search Tags for Automplition Text***
  par: int ID, int table (0=users, 1=current_jobs, 2=past_jobs)
  desc: returns list of tags of an ID for the choosen relative table.
  ****************************/
  public function searchTags($word) {
	if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($word)) {
		$word.='%';
		$query="SELECT Code_tag, Name FROM tags WHERE Name LIKE ?";
		$queryCall=mysqli_prepare($this->connection, $query);
		//echo($queryCall===false);
		mysqli_stmt_bind_param($queryCall,'s',$word);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		else {
			$result=array();
			while ($tmp=mysqli_fetch_row($queryResult))
				$result[$tmp[1]]=$tmp[0];
			return $result;
		}
    } else
		return null;
  }
	
	
	
  /***11.3 Search Tags Name from Code_tag***
  par: int ID, int table (0=users, 1=current_jobs, 2=past_jobs)
  desc: returns list of tags of an ID for the choosen relative table.
  ****************************/
  public function searchTagName($id) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id)) {
		$query="SELECT Name FROM tags WHERE Code_tag = ? LIMIT 1";
		$queryCall=mysqli_prepare($this->connection, $query);
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		if(mysqli_num_rows($queryResult) == 0){
			return null;
		}
		else
			return mysqli_fetch_assoc($queryResult)['Name'];
    } else
		return null;
  }
  
  
  
  
  
  /***12.Get the Four Most Popular Job Tags***
  par:
  desc: ritorna i 4 tag più popolari al momento.
  ****************************/
  public function getMostPopularJobs() {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    $query='SELECT Name,tags.Code_tag, COUNT(Code_job) AS frequency FROM tags RIGHT JOIN tags_current_jobs ON tags.Code_tag= tags_current_jobs.Code_tag GROUP BY tags.Code_tag ORDER BY frequency DESC LIMIT 4;';
    $queryResult = mysqli_query($this->connection, $query);
    if(mysqli_num_rows($queryResult) == 0)
      return null;
    else {
      $result=array();
      while ($tmp=mysqli_fetch_array($queryResult))
        array_push($result,$tmp);
      return $result;
    }
    return null;
  }

  /***13.Search Job***
  par: string type, int min (prezzo minimo), int date (ultimi x secondi)
  desc: restituisce i lavori di un determinato Type, con price > di min e nell'ultima quantità x di secondi
  ****************************/ 
  public function searchJob($bool=false, $tipology='Any',$min=0,$date=9999999,$page=0,$pay=2){
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
		die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');

	if(!is_numeric($min) OR !is_numeric($date) OR !is_numeric($page))
		return null;
	$page--;
	
	$tags=$_SESSION['TagList'];
	
	//'CREATE TEMPORARY TABLE IF NOT EXISTS ? AS (
	//SELECT current_jobs.Code_job, Date, Title, Description, Tipology, Payment, P_min, P_max, Expiring FROM current_jobs
	//	 INNER JOIN(
	//		SELECT Code_job, COUNT(tags_current_jobs.Code_tag) AS counted FROM tags_current_jobs 
	//			INNER JOIN tags ON tags_current_jobs.Code_tag=tags.Code_tag
	//			WHERE
	//				tags_current_jobs.Code_tag=?
	//				OR tags_current_jobs.Code_tag=?
	//				[...]
	//				OR tags_current_jobs.Code_tag=?
	//			GROUP BY Code_job
	//		) res ON res.Code_job=current_jobs.Code_job
	//	WHERE
	//	 	TIMESTAMPDIFF(HOUR,Date,CURDATE())<? AND
	//	 	P_min > ? AND
	//	 	Tipology = ?
	//	 ORDER BY 
	//	 	counted 		DESC,
	//	 	Date 		   	DESC,
	//	 	Tipology
	//	);
	//
	//SELECT COUNT(*) FROM ?;
	//
	//SELECT * FROM ?
	//	LIMIT
	//		?, ?;
	//
	//DROP ?;
	//'

	//tag parts
	$tagsStart='
	INNER JOIN(
		SELECT Code_job, COUNT(tags_current_jobs.Code_tag) AS counted FROM tags_current_jobs 
			LEFT JOIN tags ON tags_current_jobs.Code_tag=tags.Code_tag
			WHERE
				tags_current_jobs.Code_tag=?
				';
	$tagsOR='OR tags_current_jobs.Code_tag=?
				';
	$tagsEnd='GROUP BY Code_job
		) res ON res.Code_job=current_jobs.Code_job
	';
	
	//query parts
	$begin='
	SELECT current_jobs.Code_job, Date, Title, Description, Tipology, Payment, P_min, P_max, Expiring FROM current_jobs';
	$middle='
		WHERE
		 	TIMESTAMPDIFF(HOUR,Date,CURDATE()) < ? AND
			P_min > ?
			';
	$tip='	AND
			Tipology = ?
	';
  $payString='';
  if($pay==1)
    $payString='	AND
      Payment != 0
      ';
  else if($pay==2)
    $payString='	AND
      Payment = 0
    ';

	$middle2='
		ORDER BY 
	';
	$count='counted 	DESC,
	';
	$end='
		 	Date 		   	DESC,
		 	Tipology';
	
	$Nresults='SELECT COUNT(*) FROM (';
	
	
	$limit=' LIMIT ?, ?;';
	
	//constrution of modular query with optional parameters
	//creation of $type and $param arrays to bind
	$type='';
	$param=array();
	$query=$begin;
	$i=0;
	if(!empty($tags)){
		$query.=$tagsStart;
		$type.='i';
		$param[$i]=array_values($tags)[0];
		$i++;
		foreach(array_slice($tags,1) as $name=>$value){	//append an Or other condition for number of tags selected
			$query.=$tagsOR;
			$type.='i';
			$param[$i]=$value;
			$i++;
		}
		$query.=$tagsEnd;
	}
	$query.=$middle;
  $query.=$payString;
	$type.='ii';
	$param[$i]=$date;
	$i++;
	$param[$i]=$min;
	$i++;
	if(in_array($tipology,array('Fulltime','Onetime','Urgent','Recruiter'))){
		$query.=$tip;
		$type.='s';
		$param[$i]=$tipology;
		$i++;
	}
	if(!$bool){
		$query.=$middle2;
		if(!empty($tags))
			$query.=$count;
		$query.=$end;
	}
	//$time=microtime(true);
	if($bool){
		$query=$Nresults.$query.') AS subquery;';
		$queryCall=mysqli_prepare($this->connection,$query);
		if(!$queryCall)
			die('prepare() failed: ' . htmlspecialchars($this->connection->error));
			//die('Errore preparazione query');
		//single bind for the entire modular statement
		$queryCall->bind_param($type, ...$param); 
		if(!$queryCall)
			die('Errore binding parametry query');
		mysqli_stmt_execute($queryCall);
		if(!$queryCall)
			die('Errore esecuzione query');
		$result=mysqli_fetch_row(mysqli_stmt_get_result($queryCall))[0];
		mysqli_stmt_close($queryCall);
		return $result;
	}
	$query.=$limit;
	$type.='ii';
	$param[$i]=$page*5;
	$param[$i+1]=5;
	$queryCall=mysqli_prepare($this->connection,$query);
	if(!$queryCall)
		die('prepare() failed: ' . htmlspecialchars($this->connection->error));
		//die('Errore preparazione query');
	//single bind for the entire modular statement
	$queryCall->bind_param($type, ...$param);  
	if(!$queryCall)
		die('Errore binding parametry query');
	//for($i=0;$i<10000;$i++){
		mysqli_stmt_execute($queryCall);
	//}
	if(!$queryCall)
		die('Errore esecuzione query');
	
	$queryResult = mysqli_stmt_get_result($queryCall);
	mysqli_stmt_close($queryCall);
	//echo(microtime(true)-$time);
	if(mysqli_num_rows($queryResult) == 0)
		return null;
	$result=array();
	while($row=mysqli_fetch_assoc($queryResult))
		array_push($result, $row);
	return $result;
  }

  /***14.Get User Info***
  par: int userID;
  desc: restituisce informazioni di un utente userID. altrimenti ritorna null.
  ****************************/
  public function getUser($id) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
		die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id)) {
		$queryCall=mysqli_prepare($this->connection, 'SELECT * FROM users WHERE Code_user = ? LIMIT 1;');
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		else
			return mysqli_fetch_assoc($queryResult);
} else
		return null;
  }
  
  
  /***14.Get User Info***
  par: int userID;
  desc: restituisce informazioni di un utente userID. altrimenti ritorna null.
  ****************************/
  public function getMinMaxPrice($job) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
		die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($job)) {
		$queryCall=mysqli_prepare($this->connection, 'SELECT P_min,P_max FROM current_jobs WHERE Code_job = ? LIMIT 1;');
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
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
  public function usernameTaken($name) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($name)) {
		$queryCall=mysqli_prepare($this->connection, 'SELECT Code_user FROM users WHERE Nickname = ? Limit 1;');
		if(!$queryCall)
			die("errore nella preparazione");
		mysqli_stmt_bind_param($queryCall,'s',$name);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
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
  public function emailTaken($name) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($name)) {
		$queryCall=mysqli_prepare($this->connection, 'SELECT Code_user FROM users WHERE Email = ? Limit 1;');
		mysqli_stmt_bind_param($queryCall,'s',$name);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
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
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id)) {
    // $queryCall=mysqli_prepare($this->connection, 'SELECT User_Review(?);');
		$queryCall=mysqli_prepare($this->connection, 'SELECT AVG(Stars) AS AvgStar FROM reviews WHERE Code_user =?;');
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		return mysqli_fetch_assoc($queryResult);
    } else
		return null;
  }

  /***18.Get User Review List***
  par: int userID;
  desc: ritorna lista delle recensioni relative ad un utente userID. altrimenti ritorna null.
  ****************************/
  public function getUserReviewList($id,$number) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id)) {
		$queryCall=mysqli_prepare($this->connection, 'SELECT P.Code_user AS JobGiver, R.Stars , R.Comments, R.Date 
			FROM reviews AS R LEFT JOIN past_jobs AS P ON R.Code_job = P.Code_job WHERE R.Code_user = ?  ORDER BY R.Date DESC LIMIT ?;');
		mysqli_stmt_bind_param($queryCall,'ii',$id,$number);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		else {
			$result=array();
			while ($tmp=mysqli_fetch_assoc($queryResult))
				array_push($result, $tmp);
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
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id)) {

		$query='SELECT bids.Code_job AS Code , Title, Tipology, Payment, P_min, P_max, Expiring FROM bids INNER JOIN current_jobs
				ON current_jobs.Code_job = bids.Code_job WHERE bids.Code_user =?;';
		$queryold='SELECT Code_job, Status, Title, Tipology, Payment, P_min, P_max FROM past_jobs WHERE Code_winner=?;';
		if(isset($old) and $old == true)
			$queryCall=mysqli_prepare($this->connection, $queryold);
		else
			$queryCall=mysqli_prepare($this->connection, $query);
		mysqli_stmt_bind_param($queryCall,'i',$id);
		mysqli_stmt_execute($queryCall);
		$queryResult = mysqli_stmt_get_result($queryCall);
		mysqli_stmt_close($queryCall);
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
  public function register_new_user($password, $name, $surname, $nickname, $birth, $email, $nationality, $city, $address, $phone, $picture, $curriculum, $description, $tags) {
	if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
	if(isset($password) and isset($name) and isset($surname) and isset($nickname) and isset($birth) and isset($email) and isset($city) and isset($picture) and isset($description) and isset($tags)){
		//create new entry on table users and then create with the relative index the credentials for the login.
		$queryInserimento = 'INSERT INTO users(Name, Surname, Nickname, Birth, Email, Nationality, City, Address, Phone, Picture, Curriculum, Description)
							VALUES (?,?,?,?,?,?,?,?,?,?,?,?)';
		$queryprep='CALL Register_new_user(?,?);';
		$queryCall=mysqli_prepare($this->connection, $queryInserimento);
		$queryCall2=mysqli_prepare($this->connection, $queryprep);
		if(!isset($address))
			$address=null;
		if(!isset($phone))
			$phone=null;
		if(!isset($curriculum))
			$curriculum=null;
		mysqli_stmt_bind_param($queryCall,'ssssssssisss',$name, $surname, $nickname, $birth, $email, $nationality, $city, $address, $phone, $picture, $curriculum, $description);
		mysqli_stmt_bind_param($queryCall2,'ss', $nickname, $password);
		mysqli_stmt_execute($queryCall);
		mysqli_stmt_close($queryCall);
		$Code_user=mysqli_insert_id($this->connection);
		$tmp=mysqli_affected_rows($this->connection);
		if($tmp){
			mysqli_stmt_execute($queryCall2);
			$tmp=mysqli_affected_rows($this->connection);
		}
		mysqli_stmt_close($queryCall2);
		if($tmp){
			$queryTags= 'INSERT INTO tags_users(Code_user,Code_tag) VALUES ';
			$a='';
			$b=array();
			foreach($tags as $tag){
				$queryTags .= '(?,?),';
				$a.='ii';
				array_push($b,$Code_user,$tag );
			}
			$queryTags=rtrim($queryTags, ",");
			$queryCall=mysqli_prepare($this->connection, $queryTags);
			$queryCall->bind_param($a, ...$b);
			mysqli_stmt_execute($queryCall);
			$tmp=mysqli_affected_rows($this->connection);
			mysqli_stmt_close($queryCall);
			if($tmp)
				return true;
		}
		else
			return false;
    } else
		return false;
  }

  /***21.User Login***
  par: string user; string password;
  desc: restituisce i dati dati dell'utente corrispondenti a user e password, altrimenti ritorna null
  ****************************/
  public function login($user, $pwd) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($user) && isset($pwd)) {
		//uso di query preparata per evitare vulnerabilità da SQL Injection e uso di procedura per evitare alterazione del codice.
		$qprep1='SET @mess="" AND @userID="";'; $qprep2='CALL Log_in(?,?,@mess,@userID);';	$qprep3='SELECT @mess, @userID;';
		$queryCall = mysqli_prepare($this->connection, $qprep2);
		//if($queryCall==false)
		//	die('<br>something went wrong during preparation of query for procedure Log_in');
		mysqli_stmt_bind_param($queryCall,'ss', $user, $pwd);
		mysqli_query($this->connection, $qprep1);
		mysqli_stmt_execute($queryCall);
		mysqli_stmt_close($queryCall);

		$queryResult = mysqli_query($this->connection, $qprep3);
		$row= mysqli_fetch_row($queryResult);
		$queryResult=$row[0];
		$ID=$row[1];

		if($queryResult==false){
			return null;
		} else if($queryResult==true && isset($ID)){
			$querySelect = 'SELECT Code_user, Status, Nickname, Picture  FROM users WHERE Code_user = "' . $ID . '" LIMIT 1;';
			$queryAdmin = 'SELECT Code_user FROM admin WHERE Code_user = "' . $ID . '" LIMIT 1;';
			$queryResult = mysqli_query($this->connection, $querySelect);
			$Admin = mysqli_query($this->connection, $queryAdmin);
			$riga = mysqli_fetch_assoc($queryResult);
			//if($queryResult==false)
			//	die("  something bad happened!   can't find user by ID even if Login was susccessful!");
			$datiUtente = array(
				"ID" => $riga['Code_user'],
				"Status" => $riga['Status'],
				"Username" => $riga['Nickname'],
				"Icon" => $riga['Picture']
			);
			if($Admin!=false && mysqli_num_rows($Admin)==1)
				$datiUtente['Admin']=1;
			return $datiUtente;
		} else
			echo '\r<br>Errore dentro DBAccess: errore sconosciuto nella login';

    } else {
		echo '\r<br>Errore dentro DBAccess: credenziali di accesso mancanti';
		return null;
    }

  }

  /***22.create Bid***
  par: int $id (user id), int job (job id), int price, string comment
  desc: 
  ****************************/
  public function createBid($id, $job, $price, $comments) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id) and isset($job) and isset($price)){
      $queryInserimento = 'INSERT INTO bids(Code_user, Code_job, User_Price, Bid_selfdescription)
                VALUES (?,?,?,?)';
      $queryCall=mysqli_prepare($this->connection, $queryInserimento);
      if(!isset($comments))
        $comments='';
      mysqli_stmt_bind_param($queryCall,'iiis',$id, $job, $price, $comments);
      mysqli_stmt_execute($queryCall);
      mysqli_stmt_close($queryCall);
      $tmp=mysqli_affected_rows($this->connection);
      if($tmp)
        return true;
      return false;
    } else
      return false;
  }

  /***23.changePassword***
  par: string oldPsw, string newPsw, 
  desc: confronta la password con quella precedente ed in caso sia corretta, la cambia
  ****************************/
  public function changePassword($id,$Psw) {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id) && isset($Psw)){
      $query = 'CALL Change_password(?,?);';
      $queryCall=mysqli_prepare($this->connection, $query);
      mysqli_stmt_bind_param($queryCall,'is',$id,$Psw);
      mysqli_stmt_execute($queryCall);
      mysqli_stmt_close($queryCall);
      if(mysqli_affected_rows($this->connection))
        return true;
      return false;
    } else
      return null;
  }
  
  /***24.changeUserInfo***
  par:  $id, $name, $surname, $nickname, $birth, $email, $nationality, $city, $address, $phone, $picture, $curriculum, $description
  desc: confronta la password con quella precedente ed in caso sia corretta, la cambia
  ****************************/
  public function changeUserInfo($id, $name, $surname, $nickname, $birth, $email, $nationality, $city, $address, $phone, $picture, $curriculum, $description) {
    if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    //create new entry on table users and then create with the relative index the credentials for the login.
    $queryInserimento = 'UPDATE users 
      SET Name=?,Surname=?,Nickname=?,Birth=?,Email=?,Nationality=?,City=?,Address=?,Phone=?,Picture=?,Curriculum=?,Description=? 
      WHERE Code_user=?;';
    $queryCall=mysqli_prepare($this->connection, $queryInserimento);
    mysqli_stmt_bind_param($queryCall,'ssssssssisssi',$name, $surname, $nickname, $birth, $email, $nationality, $city, $address, $phone, $picture, $curriculum, $description,$id);
    mysqli_stmt_execute($queryCall);
    mysqli_stmt_close($queryCall);
    $tmp=mysqli_affected_rows($this->connection);
    if($tmp)
      return true;
    else
      return false;
  }
  
  /***24.changeUserTags***
  par:  $id, $name, $surname, $nickname, $birth, $email, $nationality, $city, $address, $phone, $picture, $curriculum, $description
  desc: confronta la password con quella precedente ed in caso sia corretta, la cambia
  ****************************/
  public function changeUserTags($id, $tags) {
    if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    if(isset($id) && isset($tags)){
		$queryTags = 'DELETE FROM tags_users WHERE Code_user=?';
		$a='i';
		$queryCall=mysqli_prepare($this->connection, $queryTags);
		mysqli_stmt_bind_param($queryCall,$a,$id);
		mysqli_stmt_execute($queryCall);
		mysqli_stmt_close($queryCall);
		$queryCall=mysqli_prepare($this->connection, $queryTags);
		$queryTags ='INSERT INTO tags_users (Code_user,Code_tag) VALUES ';
		$a='';
		$b=array();
		foreach($tags as $tag){
			$queryTags .= '(?,?),';
			$a.='ii';
			array_push($b,$id,$tag );
		}
		$queryTags=rtrim($queryTags, ",");
		echo($queryTags);
		$queryCall=mysqli_prepare($this->connection, $queryTags);
		echo($a);
		print_r($b);
		$queryCall->bind_param($a, ...$b);
		mysqli_stmt_execute($queryCall);
		mysqli_stmt_close($queryCall);
		if(mysqli_affected_rows($this->connection))
			return true;
		return false;
	}
	else
		return false;
  }

  /***25.removeBid***
  par: int idJob, int idUser; 
  desc: delete a bid from a Job Offer from a specific User;
  ****************************/
  public function removeBid($idJob, $idUser) {
    if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
      die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    $queryDelete = 'DELETE FROM bids WHERE Code_user=? AND Code_job=?;';
    $queryCall=mysqli_prepare($this->connection, $queryDelete);
    mysqli_stmt_bind_param($queryCall,'ii',$idUser, $idJob);
    mysqli_stmt_execute($queryCall);
    mysqli_stmt_close($queryCall);
    $tmp=mysqli_affected_rows($this->connection);
    if($tmp)
      return true;
    else
      return false;
  }

  /*********26.AdminFunctions */
 
  /* getAdminUserAction 
  par: none;
  desc: return the list of Admin Actions on users;
  */
  public function getAdminUserAction() {
    if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
    die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    $queryResult  = mysqli_query($this->connection, 'SELECT users.Nickname AS Nick,users.Code_user AS Code,users.Status AS Stat,Date, Comments FROM users_admin_actions INNER JOIN users ON users_admin_actions.Code_user = users.Code_user;');
    if(mysqli_num_rows($queryResult) == 0)
      return null;
    else {
      $result=array();
      while($row=mysqli_fetch_assoc($queryResult))
      array_push($result, $row);
      return $result;
    }
  }

  /* getAdminJobAction
  par: none;
  desc: return the list of Admin Actions on current and past Jobs;
  */
  public function getAdminJobAction() {
    if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
    die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    $queryResult  = mysqli_query($this->connection, 'SELECT past_jobs.Title AS Title, past_jobs.Code_job AS Code,past_admin_actions.Date AS Date, Comments FROM past_admin_actions INNER JOIN past_jobs ON past_admin_actions.Code_job=past_jobs.Code_job;');
    if(mysqli_num_rows($queryResult) == 0)
      return null;
    else {
      $result=array();
      while($row=mysqli_fetch_assoc($queryResult))
      array_push($result, $row);
      return $result;
    }
  }

  /* Get all Users 
  par: none;
  desc: return the list of users for the admin page;
  */
  public function getUsers() {
	  if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
		die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
      $queryResult = mysqli_query($this->connection, 'SELECT Nickname, Code_User, Status FROM users;');
      if(mysqli_num_rows($queryResult) == 0)
        return null;
      else {
        $result=array();
        while($row=mysqli_fetch_assoc($queryResult))
          array_push($result, $row);
        return $result;
    }
  }

  /* Get all Past Jobs 
  par: none;
  desc: return the list of Past Jobs for the admin page;
  */
  public function getPastJobs() {
		if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
			die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
    $queryResult  = mysqli_query($this->connection, 'SELECT Title, Code_job, Status FROM past_jobs;');
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		else {
			$result=array();
			while($row=mysqli_fetch_assoc($queryResult))
			array_push($result, $row);
			return $result;
    }
  }

  /* Get all Offers 
  par: none;
  desc: return the list of Current Job Offer for the admin page;
  */
  public function getOffers() {
		if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
			die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
		$queryResult = mysqli_query($this->connection, 'SELECT Title, Code_job, Expiring FROM current_jobs;');
		if(mysqli_num_rows($queryResult) == 0)
			return null;
		else {
			$result=array();
			while($row=mysqli_fetch_assoc($queryResult))
			array_push($result, $row);
			return $result;
    }
  }
  
  /* DeleteJobAdmin ( Delete Offer ) 
  par: int Job, int idAdmin, string comment;
  desc: given a Job Id, an admin ID and a comment from the admin, moves the current job to past as deleted and adds an instance with the comment in past_admin_actions
  */
  public function DeleteJobAdmin($job,$idAdmin,$comment) {
		if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
			die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
		// chiama procedura per ban
    $sqlDelete = 'CALL Delete_job(?,?)';
    $queryCall=mysqli_prepare($this->connection, $sqlDelete);
    mysqli_stmt_bind_param($queryCall,'ii',$job,$idAdmin);
    mysqli_stmt_execute($queryCall);
    mysqli_stmt_close($queryCall);
    $tmp=mysqli_affected_rows($this->connection);
    if($tmp)
    {
      // INSERT INTO past_admin_actions(Code_job, Comments,Date,Code_admin) VALUES (?,?,?,?);
      $sqlUpdate = 'INSERT INTO past_admin_actions(Code_job, Comments,Date,Code_admin) VALUES (?,?,?,?);';
      $queryCall2=mysqli_prepare($this->connection, $sqlUpdate);
      if(!isset($comment))
        $comment='';
      mysqli_stmt_bind_param($queryCall2,'issi',$job,$comment,date("Y-m-d h:i:sa"),$idAdmin);
      mysqli_stmt_execute($queryCall2);
      mysqli_stmt_close($queryCall2);
      $tmp=mysqli_affected_rows($this->connection);
      if($tmp)
        return true;
      else
        return false;
    }
    else
      return false;
  }

  /* DeletePastJobAdmin ( Delete Past_job ) 
  par: int Job, int idAdmin, string comment;
  desc:given a Job Id, an admin ID and a comment from the admin, changes the past job status and adds an instance with the comment in past_admin_actions
  */
  public function DeletePastJobAdmin($job,$idAdmin,$comment) {
		if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
			die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
		// chiama procedura per ban
    $sqlDelete = 'UPDATE past_jobs SET Code_winner=NULL,Status=1 WHERE Code_job= ?;';
    $queryCall=mysqli_prepare($this->connection, $sqlDelete);
    mysqli_stmt_bind_param($queryCall,'i',$job);
    mysqli_stmt_execute($queryCall);
    mysqli_stmt_close($queryCall);
    $tmp=mysqli_affected_rows($this->connection);
    if($tmp)
    {
      // INSERT INTO past_admin_actions(Code_job, Comments,Date,Code_admin) VALUES (?,?,?,?);
      $sqlUpdate = 'INSERT INTO past_admin_actions(Code_job, Comments,Date,Code_admin) VALUES (?,?,?,?);';
      $queryCall2=mysqli_prepare($this->connection, $sqlUpdate);
      if(!isset($comment))
        $comment='';
      mysqli_stmt_bind_param($queryCall2,'issi',$job,$comment,date("Y-m-d h:i:sa"),$idAdmin);
      mysqli_stmt_execute($queryCall2);
      mysqli_stmt_close($queryCall2);
      $tmp=mysqli_affected_rows($this->connection);
      if($tmp)
        return true;
      else
        return false;
    }
    else
      return false;
  }
  
  /* Ban User Admin 
  par: int Job, int idAdmin, string comment;
  desc:given a Job Id, an admin ID and a comment from the admin, changes the user status and adds an instance with the comment in past_admin_actions
  */
  public function BanUserAdmin($id, $idAdmin,$comment) {
		if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
			die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
		// chiama procedura per ban
    $sqlBan = 'UPDATE users SET Status = 3 WHERE Code_user = ? ;';
    $queryCall=mysqli_prepare($this->connection, $sqlBan);
    mysqli_stmt_bind_param($queryCall,'i',$id);
    mysqli_stmt_execute($queryCall);
    mysqli_stmt_close($queryCall);
    $tmp=mysqli_affected_rows($this->connection);
    if($tmp)
    {
      // INSERT INTO users_admin_actions(Code_user, Comments, Date, Code_admin) VALUES (?,?,?,?)
      $sqlUpdate = 'INSERT INTO users_admin_actions(Code_user, Comments, Date, Code_admin) VALUES (?,?,?,?);';
      $queryCall2=mysqli_prepare($this->connection, $sqlUpdate);
      mysqli_stmt_bind_param($queryCall2,'issi',$id,$comment,date("Y-m-d h:i:sa"),$idAdmin);
      mysqli_stmt_execute($queryCall2);
      mysqli_stmt_close($queryCall2);
      $tmp=mysqli_affected_rows($this->connection);
      if($tmp)
        return true;
      else
        return false;
    }
    else
      return false;
  }

  /* UnBan User Admin 
  par: int Job, int idAdmin, string comment;
  desc:given a Job Id, an admin ID and a comment from the admin, changes the user status and adds an instance with the comment in past_admin_actions
  */
  public function UnBanUserAdmin($id, $idAdmin,$comment) {
	if(is_resource($this->connection) && get_resource_type($this->connection)==='mysql link')
		die('<br>You must call openDBConnection() before calling a DBAccess function.<br>Remember to always close it when you are done!');
	// chiama procedura per ban
    $sqlBan = 'UPDATE users SET Status = 1 WHERE Code_user = ? ;';
    $queryCall=mysqli_prepare($this->connection, $sqlBan);
    mysqli_stmt_bind_param($queryCall,'i',$id);
    mysqli_stmt_execute($queryCall);
    mysqli_stmt_close($queryCall);
    $tmp=mysqli_affected_rows($this->connection);
    if($tmp)
    {
      // INSERT INTO users_admin_actions(Code_user, Comments, Date, Code_admin) VALUES (?,?,?,?)
      $sqlUpdate = 'INSERT INTO users_admin_actions(Code_user, Comments, Date, Code_admin) VALUES (?,?,?,?);';
      $queryCall2=mysqli_prepare($this->connection, $sqlUpdate);
      mysqli_stmt_bind_param($queryCall2,'issi',$id,$comment,date("Y-m-d h:i:sa"),$idAdmin);
      mysqli_stmt_execute($queryCall2);
      mysqli_stmt_close($queryCall2);
      $tmp=mysqli_affected_rows($this->connection);
      if($tmp)
        return true;
      else
        return false;
    }
    else
      return false;
  }
  
}

?>
