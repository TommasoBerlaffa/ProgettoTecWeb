<?php

function ErrorMessage(){
	$tmp='';
	switch($_SESSION['error']){
		case 'errCodeJob':
			$tmp='<p class="resultfail"> An error occurred. The job for this operation was not specified.<br>Please try again. </p>';
			break;
		case 'errINVOP':
			$tmp='<p class="resultfail"> Invalid operation. </p>';
			break;
		case 'errNotPast':
			$tmp='<p class="resultfail"> Operation allowed only on concluded jobs. </p>';
			break;
	    case 'errNotPresent':
			$tmp='<p class="resultfail"> Operation allowed only on active jobs. </p>';
			break;
		/*---------------------OFFER-CANCEL-TERMINATE----------------------*/
		case 'OCsucc':
			$tmp='<p class="resultsucc"> Job successfully cancelled. </p>';
			break;
		case 'OCfail':
			$tmp='<p class="resultfail"> An error occurred while cancelling the job, please try again. </p>';
			break;
		case 'OTsucc':
			$tmp='<p class="resultsucc"> Job successfully terminated. </p>';
			break;
		case 'OTfail':
			$tmp='<p class="resultfail"> An error occurred while terminating the job, please try again. </p>';
			break;
		case 'removeTrue':
			$tmp='<p class="resultsucc"> Bid removed successfully. </p>';
			break;
		case 'removeFalse':
			$tmp='<p class="resultfail"> An error occurred while deleting the bid, please try again. </p>';
			break;
		/*-------------------------CHOOSE-WINNER--------------------------*/
		case 'errCAlready':
			$tmp='<p class="resultfail"> The winner of this job has been already selected. </p>';
			break;
		case 'errCWinner':
			$tmp='<p class="resultfail"> Unable to read the selected winner, please try to select the <a href="#winner">winner</a> again. </p>';
			break;
		case 'Csucc':
			$tmp='<p class="resultsucc"> The winner has been selected successfully. </p>';
			break;
		case 'Cfail':
			$tmp='<p class="resultfail"> An unexpected error occurred. Please retry to select the <a href="#winner">winner</a>. </p>';
			break;
		/*---------------------------ADD-BIDS-----------------------------*/
		case 'errABPrice':
			$tmp='<p class="resultfail"> Unable to read the price of the bid, please try to select the <a href="#Price">price</a> again. </p>';
			break;
		case 'errABCreaor':
			$tmp='<p class="resultfail"> Cannot place a bid on your own job. </p>';
			break;
		case 'errABAlready':
			$tmp='<p class="resultfail"> You already have placed <a href="#bids">Bid</a> for this job. </p>';
			break;
		case 'ABsucc':
			$tmp='<p class="resultsucc"> The bid has been created successfully. </p>';
			break;
		case 'ABfail':
			$tmp='<p class="resultfail"> An unexpected error occurred. Please retry to compile the <a href="#addBid">Bid</a>. </p>';
			break;
		/*-------------------------REMOVE-BIDS---------------------------*/
		case 'errRBNoBid':
			$tmp='<p class="resultfail"> No bids to delete present. </p>';
			break;
		case 'RBsucc':
			$tmp='<p class="resultsucc"> The bid has been deleted successfully. </p>';
			break;
		case 'RBfail':
			$tmp='<p class="resultfail"> An unexpected error occurred. Please try to delete the <a href="#bids">Bid</a> again. </p>';
			break;
		/*-------------------------ADD-FEEDBACK----------------------------*/
		case 'errFStar':
			$tmp='<p class="resultfail"> Unable to read the stars rating, please try to select the <a href="#stars">star rating</a> again.</p>';
			break;
		case 'errFComm':
			$tmp='<p class="resultfail"> Unable to read the comment, please try to type the <a href="#comment">comment</a> again.p>';
			break;
		case 'Fsucc':
			$tmp='<p class="resultsucc"> The feedback has been created successfully. </p>';
			break;
		case 'Ffail':
			$tmp='<p class="resultfail"> An unexpected error occurred. Please retry to compile the <a href="#AddFeedback">Review</a>. </p>';
			break;
		case 'errFWinner':
			$tmp='<p class="resultfail"> Choose a <a href="#bids">Winner</a> before compiling the feedback form. </p>';
		case 'errFAlready':
			$tmp='<p class="resultfail"> This user already has a <a href="#feedback">Review</a> </p>';
		default:
			$tmp='';
	}
	return ($tmp);
	
	
	
}

?>