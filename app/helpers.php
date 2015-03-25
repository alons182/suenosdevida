<?php

function errors_for($attribute, $errors)
{
    return $errors->first($attribute,'<span class="error label label-warning">:message</span>');
}

function link_to_profile($text = 'Profile')
{
    return link_to_route('profile', $text, Auth::user()->username,['class'=>'btn-profile']);
}
function link_to_payments($text = 'Pagos')
{
    return link_to_route('payments.index', $text, null,['class'=>'btn-payments']);
}
function link_to_orders($text = 'Mi historial de compras')
{
    return link_to_route('orders.index', $text,null,['class'=>'btn-orders']);
}

function get_depth($depth)
{
    return str_repeat('<span class="depth">—</span>', $depth);
}

function money($amount, $symbol = '$')
{
    return (!$symbol) ? number_format($amount, 0, ".", ",") : $symbol . number_format($amount, 0, ".", ",");
}
function number($amount)
{
    return preg_replace("/([^0-9\\.])/i", "", $amount);
}
function percent($amount, $symbol = '%')
{
    return $symbol . number_format($amount, 0, ".", ",");
}

function set_active($path, $active = 'active' )
{
    return Request::is($path) ? $active : '';
}
function dir_photos_path($dir)
{
    return public_path() . '/images_store/'. $dir .'/';
}
function dir_downloads_path($dir = null)
{
    return ($dir) ? public_path() . '/downloads_files/'. $dir .'/' : public_path() . '/downloads_files/' ;
}

function photos_path($dir)
{
    return '/images_store/'. $dir .'/';
}
function existDataArray($data, $index)
{
    if(isset($data[$index]))
    {
        $array = array_where($data[$index], function($key, $value)
        {
            if(trim($value) != "")
                return $value;
        });

    }else
    {
        $array = [];
    }

    return $array;
}
function build_tree($arrs, $parent_id=0, $level=0) {
    $tree = '';
   // $ret = '<ul>';
   // dd($arrs);
    foreach ($arrs as $arr) {
        if ($arr['parent_id'] == $parent_id) {
            $tree .= str_repeat("-", $level)." ".$arr['username']."<br />";
            build_tree($arrs, $arr['id'], $level+1);
        }
    }
    return $tree;
}

function matriz_table($aray,$nr_elm)
{
  // dd($aray[0]);
    // Numeric array with data that will be displayed in HTML table
//$aray = array('http://coursesweb.net', 'www.marplo.net', 'Courses', 'Web Programming', 'PHP-MySQL');
//$nr_elm = count($aray);        // gets number of elements in $aray

// Create the beginning of HTML table, and of the first row
    $html_table = '<table border="1 cellspacing="0" cellpadding="2""><tr>';
    $nr_col = 15;       // Sets the number of columns

// If the array has elements
    if ($nr_elm > 0) {
        // Traverse the array with FOR
        for($i=0; $i<$nr_elm; $i++) {
            $html_table .= '<td>' .json_encode($aray[$i]). '</td>';       // adds the value in column in table

            // If the number of columns is completed for a row (rest of division of ($i + 1) to $nr_col is 0)
            // Closes the current row, and begins another row
            $col_to_add = ($i+1) % $nr_col;
            if($col_to_add == 0) { $html_table .= '</tr><tr>'; }
        }

        // Adds empty column if the current row is not completed
        if($col_to_add != 0) $html_table .= '<td colspan="'. ($nr_col - $col_to_add). '">&nbsp;</td>';
    }

    $html_table .= '</tr></table>';         // ends the last row, and the table

// Delete posible empty row (<tr></tr>) which cand be created after last column
    $html_table = str_replace('<tr></tr>', '', $html_table);

    return $html_table;        // display the HTML table
}
