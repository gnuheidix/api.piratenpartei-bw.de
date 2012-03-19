<?php
class WikiPage extends AppModel {
    public $name = 'WikiPage';
    
    public $hasMany = array(
        'WikiDiv' => array(
            'className'     => 'WikiDiv',
            'foreignKey'    => 'page_id',
            'dependent'     => true // delete if the WikiPage gets deleted
        )
    );
    // TODO validation needed
}
?>
