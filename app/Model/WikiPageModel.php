<?php
class WikiPage extends AppModel {
    public $name = 'WikiPage';
    
    public $hasMany = array(
        'WikiElement' => array(
            'className'     => 'WikiElement',
            'foreignKey'    => 'page_id',
            'dependent'     => true // delete if the WikiPage gets deleted
        )
    );
    // TODO validation needed
}
?>
