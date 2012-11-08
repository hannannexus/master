<?
class Base extends Eloquent {
    protected function objectToArray($phpObj) {
        $array = (array)$phpObj;
        return $array;
    }
    
    protected function objectToSingle($phpObj) {
        $array = (array)$phpObj[0];
        return $array;
    }
}
?>
