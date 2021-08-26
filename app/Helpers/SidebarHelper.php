<?php
if (!function_exists('buildMenuAdmin')) {
   
    function buildMenuAdmin($elements,$parent=0,$url='admin')
    {
        $result = '';
        if($parent){
            $result .= '<ul class="nav nav-treeview">';
        }
        foreach ($elements as $element)
        {
            if ($element->parent_id == $parent){
                
                if (menuHasChildren($elements,$element->id)){
                    $result.= '<li id="menu-'.$element->id.'" class="nav-item has-treeview"><a href="'.url($url.'/'.$element->menu_route).'" class="nav-link"><i class="nav-icon fa '.$element->menu_icon.'"></i> <p>'.$element->menu_name.' <i class="right fas fa-angle-left"></i></p>';
                    // $result.= '<span class="pull-right-container">
                    // <i class="class="right fas fa-angle-left""></i>
                    // </span>';
                    $result.='</a>';
                }
                else{
                    $result.= '<li id="menu-'.$element->id.'" class="nav-item has-treeview"><a href="'.url($url.'/'.$element->menu_route).'" class="nav-link"><i class="nav-icon fa '.($element->menu_icon?$element->menu_icon:'fa fa-circle-o').'"></i> <p>'.$element->menu_name.'</p></a>';
                }
                if (menuHasChildren($elements,$element->id))
                    $result.= buildMenuAdmin($elements,$element->id,$url);
                $result.= "</li>";
            }
        }
        if($parent){
            $result.= "</ul>";
        }
        return $result; 
    }
}

if (!function_exists('menuHasChildren')) {
    function menuHasChildren($rows,$id) {
        foreach ($rows as $row) {
            if ($row->parent_id == $id)
                return true;
        }
        return false;
    }
}