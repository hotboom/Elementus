<?
class Products extends E{
    static function get($params){
        $elements=parent::get($params);
        if(!empty($elements)){
            foreach($elements as $i=>$element) {
                if(!empty($elements[$i]['brand'])) {
                    $brand=parent::getById($elements[$i]['brand']);
                    $elements[$i]['name']=$elements[$i]['header'].' '.$brand['name'];
                }
                else $elements[$i]['name']=$elements[$i]['header'];

            }
        }
        return $elements;
    }

    static function getById($id){
        $elements=self::get($id);
        return $elements[0];
    }
}
?>