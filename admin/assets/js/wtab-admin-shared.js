/**
* Retrieve the content from the wp_editor
*
* @since 1.5
*
* @param  string | editor_id | the id of the editor (without the prefacing #)
* @return string | content   | the content of the editor
*/
function wtab_get_content_from_wysiwyg( editor_id ) {

        var content = '';

        // Check if tinymce is initialized, and if our instance is known
        if ( tinymce !== 'undefined' && tinymce.get( editor_id ) !== null ) {

                // Store the content
                content = tinymce.get( editor_id ).getContent();

                // If we don't have any content, check the textarea for a value and use it
                if ( content.length === 0 && jQuery( '#' + editor_id ).val().length > 0 ) {
                        content = jQuery( '#' + editor_id ).val();
                }
        } else {

                // If tinymce is not initialized, try getting the content from the textarea value
                content = jQuery( '#' + editor_id ).val();
        }

        return content;
}

/**
* Set the content for the wp_editor
*
* @since 1.5
*
* @param  string | editor_id | the id of the editor (without the prefacing #)
* @param  string | content	 | the content to supply the editor with
*/
function wtab_set_content_for_wysiwyg( editor_id, content ) {

        // Check if tinymce is initialized, and if our instance is known
        if ( tinymce !== 'undefined' && tinymce.get( editor_id ) !== null ) {

                // If it's initialized, we can just set the content from here using setContent()
                tinymce.get( editor_id ).setContent( content );

                // tinyMCE stores the value in both places, so we need to set the textarea content from here too
                jQuery( '#' + editor_id ).val( content );
        } else {

                // Else we need to set the value using the textarea's val
                jQuery( '#' + editor_id ).val( content );
        }
}

