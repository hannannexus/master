<?
class Base extends Eloquent {
    protected function objectToArray($phpObj) {
    	if(!is_null($phpObj) && !empty($phpObj)) {
    		foreach($phpObj as $key => $obj) {
	    		$array[$key] = (array)$obj;
	    	} 
    	}
    	else {
    		$array = array();
    	}
        return $array;
    }
    
    protected function objectToSingle($phpObj) {
    	if(!is_null($phpObj) && !empty($phpObj)) {
    		$array = (array)$phpObj[0];
    	}
        else {
        	$array = array();
        }
        return $array;
    }
}
?>
