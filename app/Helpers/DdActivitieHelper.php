<?php
if (!function_exists('buildDD2')) {
    function buildDD2(array $elements, $parentId = 0, $currLevel = 0, $prevLevel = -1){
        foreach ($elements as $element) {

            if ($element['parent_id'] == $parentId) {                       
                if ($currLevel > $prevLevel){
                    if($parentId == 0){
                        echo '<ol class="dd-list">'; 
                    }
                    else{
                        echo '<ol>';
                    }
                }
            
                if ($currLevel == $prevLevel) echo " </li> ";
            
                    echo '<li class="dd-item" data-id="'.$element['id'].'">
                    <div class="item_actions">
                        <a class="btn btn-sm text-blue-800 edit" data-id="'.$element['id'].'">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a class="btn btn-sm delete text-danger-400" data-id="'.$element['id'].'">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                    <div class="dd-handle '.(@$element['status']==0?'disabled':'').'">
                        <span>'.$element['activity'].'</span> 
                    </div>';
            
                if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
            
                $currLevel++; 
            
                buildDD2 ($elements, $element['id'], $currLevel, $prevLevel);
            
                $currLevel--;               
            }   
        
        }
        if ($currLevel == $prevLevel) echo " </li>  </ol> ";
    }
}