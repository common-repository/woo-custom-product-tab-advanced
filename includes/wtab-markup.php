<?php

/*
 * Create Checkboxes
 */
if( !function_exists('wtab_checkbox') ){
    function wtab_checkbox( $args ) {

        $checkbox = '<input type="checkbox" ';

        if( $args['name'] )
            $checkbox .= ' name="'.$args['name'].'" ';

        if( $args['id'] )
            $checkbox .= ' id="'.$args['id'].'" ';

        $checkbox .= ' />';

        echo $checkbox;

    }
}

/*
 * Create labels
 */
if( !function_exists('wtab_labels') ){
    function wtab_labels( $args ){

        $labels = '<label ';

        if( !empty($args['for']) )
            $labels .= ' for="'.$args['for'].'">';

        if( !empty($args['text']) )
            $labels .= $args['text'];    

        $labels .= '</label>';

        echo $labels;
    }
}

/*
 * Create select box
 */
if( !function_exists('wtab_multiselectbox') ){
    function wtab_multiselectbox( $args ){

        $select = '<select multiple';

        if( $args['name'] ){
            $select .= ' name="'.$args['name'].'[]" ';
        }

        if( $args['id'] )
            $select .= ' id="'.$args['id'].'"';

        $select .= '>';

        if( !empty( $args['options'] ) && is_array( $args['options'] ) ){

            foreach( $args['options'] as $key=>$val ) {

                if( isset($args['options'][$key]['level']) && $args['options'][$key]['level'] !== 0 ){
                    for($i=0; $i<=$args['options'][$key]['level']; $i++){

                        $args['options'][$key]['label'] = '&nbsp;&nbsp;&nbsp;'. $args['options'][$key]['label'];
                    }
                }

                $selected = in_array( $args['options'][$key]['value'], $args['selected'] );
                $select .= '<option '. ( $selected ? 'selected="selected"' : '' ) .' value="'.$args['options'][$key]['value'].'">'.$args['options'][$key]['label'].'</option>';
            }
        }

        $select .= '</select>';

        echo $select;
    }
}