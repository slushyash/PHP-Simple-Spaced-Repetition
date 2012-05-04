<?php
include_once('MysqlDb.php');
class Flashcards {
	public $db;
	
	function __construct() {
		$this->db = new MysqlDb('localhost', 'root', '', 'flashcardapp');
	}
	
	function getNextRound() {
		/*
		Method:
			1. Check if there are active cards where (the last review date + interval < now)
				a. if there are, grab 'em from the deck
				b. if not, go to two
			2. Check if there are any new cards
				a. if yes, grab from the deck
					i. if there are less then 10, include (10 - amnt. of new cards) pending cards and change them to new
				b. if no, go to next step
			3. Check if there are any pending cards
				a. if yes, grab 10 pending cards and change them to new
				b. if no, go to next step
			4. Get 10 non-due active cards
		*/
		$test = $this->getActiveCards(10);
		if($test !== null) { return json_encode($test); }
		
		$test = $this->getNewCards(10);
		if($test !== null) {
			if(sizeof($test) < 10) {
				$numberOfNewCards = 10 - sizeof($test);
				$test = $this->getNewCards($numberOfNewCards) == null ? $test : array_merge($test, $this->getNewCards($numberOfNewCards));
			}
			return json_encode($test);
		}
		
		$test = $this->getPendingCards(10);
		if($test !== null) { return json_encode($test); }
		
		$test = $this->getNonDueActiveCards(10);
		if($test !== null) { return json_encode($test); }
		
		return false;
		
	}
	
	function registerLastRound($array) {
		/*
		
		*/
	
	}
	
	function registerANewCard($cardInfo) {
	/* Method:
	If wrong, streak goes to zero
	If right: streak++
		if streak is three/ or strong correct
			, make card active and set interval to 20 hrs if correct, 3 days if strong
			set last review date to time()
		else do nothing
	
	*/
		$correct = $cardInfo['correct'] == "correct" || $cardInfo['correct'] == "strong";
		if(!$correct) { 
			$cardInfo['streak'] = 0;
			$this->updateCardInDB($cardInfo);
		}
		else {
			if($cardInfo['streak'] == three) {
				// update card and make card active and set interval, set last review date to time()
			}
			
			else { cardInfo['streak'] += 1; $this->updateCardInDB($cardInfo); }
		}
	}
	
	function updateCardInDB($cardInfo) {
		$db->where('id', $cardInfo['id']);
		$db->update('APUSH', $cardInfo);
	}
	
	function registerActiveCard($cardInfo) {
	/*
	If due
		wrong: interval = interval * 0.5; lastreviewdate = time()
		correct: interval = interval * 1.4; lastreviewdate = time()
		strong: interval = interval * 2.2; lastreviewdate = time()
	*/
		if($cardInfo['lastreviewdate'] + $card['interval']  >= time()) { // if due active
			if($cardInfo['correct'] == "wrong") {
				$cardInfo['interval'] *= 1.4;
				$cardInfo['lastreviewdate'] = time();
				$db->where('id', $cardInfo['id']);
				$db->update('APUSH', $cardInfo);
			}
			
			if($cardInfo['correct'] == "correct") {
			
			}
			
			if($cardInfo['correct'] == "strong") {
			
			}
		}
	}
	
	function getActiveCards($num) {
		$db->where('status', 'Active');
		$data = $db->get('APUSH', $num);
		$filteredData = array();
		foreach($data as $card) {
			if($card['lastreviewdate'] + $card['interval'] >= time()) { $filteredData[] = $card; } // am I doing the time comparisons right?
		} 
		
		return sizeof($filteredData) === 0 ? null : $filteredData;
		
	}
	
	function getNewCards($num) {
		$db->where('status', 'New');
		$data = $db->get('APUSH', $num);
		if(sizeof($data) === 0) { return null; }
		return $data;
	
	}
	
	function getPendingCards($num) {
		$db->where('status', 'Pending');
		$data = $db->get('APUSH', $num);
		if(sizeof($data) === 0) { return null; }
		return $data;
	}
	
	function getNonDueActiveCards($num) {
		$db->where('status', 'Active');
		$data = $db->get('APUSH', $num);
		if(sizeof($data) === 0) { return null; }
		$filteredData = array();
		foreach($data as $card) {
			if($card['lastreviewdate'] + $card['interval'] < time()) { $filteredData[] = $card; }
		}
		
		return sizeof($filteredData === 0) ? null : $filteredData;
	}


}
