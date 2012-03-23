<?php
class WikiElement extends AppModel {
    public $name = 'WikiElement';
    
    public $belongsTo = array(
        'WikiPage' => array(
            'className'    => 'WikiPage',
            'foreignKey'   => 'page_id'
        )
    );
    
    // TODO validation needed
}
?>
