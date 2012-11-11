<?
class Base extends Eloquent {
    protected function objectToArray($phpObj) {
    	foreach($phpObj as $key => $obj) {
    		$array[$key] = (array)$obj;
    	} 
        return $array;
    }
    
    protected function objectToSingle($phpObj) {
        $array = (array)$phpObj[0];
        return $array;
    }
}
?>
