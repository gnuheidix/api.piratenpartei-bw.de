<?php
class WikiDiv extends AppModel {
    public $name = 'WikiDiv';
    
    public $belongsTo = array(
        'WikiPage' => array(
            'className'    => 'WikiPage',
            'foreignKey'   => 'page_id'
        )
    );
    
    // TODO validation needed
}
?>
