jQuery(document).ready(function(){

    //wp editor object to invoke wp_editor
    var $wtab_editor = {

        editor_id: '',
        defaultSettings: wp.editor.getDefaultSettings,
        media_buttons: true,
        //toolbar: 'formatselect,bold,italic,bullist,numlist,link,blockquote,alignleft,aligncenter,alignright,strikethrough,hr,forecolor,pastetext,removeformat,codeformat,undo,redo',
        toolbar: 'formatselect, bold, italic, bullist, numlist, blockquote, alignleft, aligncenter, alignright, link, wp_more, spellchecker',
        quicktags: true,
        tinymce: {
				branding: false,
				theme: 'modern',
				skin: 'lightgray',
				language: 'en',
				formats: {
					alignleft: [
						{ selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign:'left' } },
						{ selector: 'img,table,dl.wp-caption', classes: 'alignleft' }
					],
					aligncenter: [
						{ selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign:'center' } },
						{ selector: 'img,table,dl.wp-caption', classes: 'aligncenter' }
					],
					alignright: [
						{ selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign:'right' } },
						{ selector: 'img,table,dl.wp-caption', classes: 'alignright' }
					],
					strikethrough: { inline: 'del' }
				},
				relative_urls: false,
				remove_script_host: false,
				convert_urls: false,
				browser_spellcheck: true,
				fix_list_elements: true,
				entities: '38,amp,60,lt,62,gt',
				entity_encoding: 'raw',
				keep_styles: false,
				paste_webkit_styles: 'font-weight font-style color',
				preview_styles: 'font-family font-size font-weight font-style text-decoration text-transform',
				end_container_on_empty_block: true,
				wpeditimage_disable_captions: false,
				wpeditimage_html5_captions: true,
				plugins: 'charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview',
				menubar: false,
				wpautop: true,
				indent: false,
				resize: true,
				theme_advanced_resizing: true,
				theme_advanced_resize_horizontal: false,
				statusbar: true,
				toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,wp_adv',
				toolbar2: 'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
				toolbar3: '',
				toolbar4: '',
				tabfocus_elements: ':prev,:next',
			},

        invoke: function(){
            wp.editor.initialize( this.editor_id, {
                mediaButtons: this.media_buttons,
                formatselect: true,
                tinymce: this.tinymce,
                quicktags: this.quicktags
            });
        }
    };

    //return an unique index for newly generated row.
    var row_length = function(){

        var index   = Math.floor((Math.random() * 9999) + 1);
        var indexes = jQuery("#wtab-panel-tabs").data( "indexes");

        if( (jQuery( '#wtab_desc_field_'+index ).length > 0) && (jQuery.inArray(index, indexes) !== -1) ){
            row_length();
        }else{
            indexes.push(index);
            jQuery("#wtab-panel-tabs").data( "indexes", indexes);
            return index;
        }
    };

    //action_buttons
    var $wtab_action_buttons = {

        parent_container: '.wtab-row',

        remove: function( current ){
            jQuery(current).parents(this.parent_container).remove();
        },

        move_up: function( current ){

            var item = jQuery(current).parents(this.parent_container);
            var prev = item.prev(this.parent_container);

            if( prev.length > 0 ){
                
                //generate new index for current and next tab
                var new_length      =   row_length();
                var new_length_prv  =   row_length();

                var data_length     =   jQuery(current).data('length');
                var data_length_prv =   prev.data('length');

                //get index of current tab and next tab
                data_length         =   data_length.toString();
                data_length_prv     =   data_length_prv.toString();

                //get current tab's title and content
                var this_title      =   jQuery("#wtab_text_field_"+data_length).val();
                var this_content    =   wtab_get_content_from_wysiwyg("wtab_desc_field_"+data_length );

                //get next tab's title and content
                var prev_title      =   jQuery("#wtab_text_field_"+data_length_prv).val();
                var prev_content    =   wtab_get_content_from_wysiwyg("wtab_desc_field_"+ data_length_prv);

                prev.remove();

                //replace intended strings in markup and insert newly generated markup next to current element.
                var result  =   wtab_markup.replace( /{tab_title}/g, this_title );
                result      =   result.replace( /{tab_desc}/g,  this_content );
                result      =   result.replace( /{length}/g,    new_length );

                item.before(result);
                result = ''; //Reset result variable
                $wtab_editor.editor_id = "wtab_desc_field_" + new_length;
                $wtab_editor.invoke(); //invoke editor

                var new_prev = item.prev(this.parent_container);
                item.remove();
                result      =   wtab_markup.replace( /{tab_title}/g, prev_title );
                result      =   result.replace( /{tab_desc}/g,  prev_content );
                result      =   result.replace( /{length}/g,    new_length_prv );

                new_prev.after( result );
                result = '';
                $wtab_editor.editor_id = "wtab_desc_field_" + new_length_prv;
                $wtab_editor.invoke(); //invoke editor
            }
        },

        move_down: function( current ){

            var item = jQuery(current).parents(this.parent_container);
            var next = item.next(this.parent_container);

            if( next.length > 0 ){

                //generate new index for current and next tab
                var new_length      =   row_length();
                var new_length_nxt  =   row_length();

                var data_length     =   jQuery(current).data('length');
                var data_length_nxt =   next.data('length');

                //get index of current tab and next tab
                data_length         =   data_length.toString();
                data_length_nxt     =   data_length_nxt.toString();

                //get current tab's title and content
                var this_title      =   jQuery("#wtab_text_field_"+data_length).val();
                var this_content    =   wtab_get_content_from_wysiwyg("wtab_desc_field_"+data_length );

                //get next tab's title and content
                var next_title      =   jQuery("#wtab_text_field_"+data_length_nxt).val();
                var next_content    =   wtab_get_content_from_wysiwyg("wtab_desc_field_"+ data_length_nxt);

                next.remove();

                //replace intended strings in markup and insert newly generated markup next to current element.
                var result  =   wtab_markup.replace( /{tab_title}/g, this_title );
                result      =   result.replace( /{tab_desc}/g,  this_content );
                result      =   result.replace( /{length}/g,    new_length );

                item.after(result);
                result = ''; //Reset result variable
                $wtab_editor.editor_id = "wtab_desc_field_" + new_length;
                $wtab_editor.invoke(); //invoke editor
                
                var new_next = item.next(this.parent_container);
                item.remove();
                result      =   wtab_markup.replace( /{tab_title}/g, next_title );
                result      =   result.replace( /{tab_desc}/g,  next_content );
                result      =   result.replace( /{length}/g,    new_length_nxt );
                
                new_next.before( result );
                result = '';
                $wtab_editor.editor_id = "wtab_desc_field_" + new_length_nxt;
                $wtab_editor.invoke(); //invoke editor

            }
        }
    };

    //Remove any tab from product data tab
    jQuery('body').on('click', '.wtab-remove-row', function(e){
        e.preventDefault();
        $wtab_action_buttons.remove(this);
    });

    //Move any tab up
    jQuery('body').on('click', '.wtab-move-up', function(e){
        e.preventDefault();
        $wtab_action_buttons.move_up(this);
    });

    //Move any tab down
    jQuery('body').on('click', '.wtab-move-down', function(e){
        e.preventDefault();
        $wtab_action_buttons.move_down(this);
    });


    //Invoke wp editor for textareas
    var invoke_editors = (function(){
        
        var indexes = [];
        
        if( jQuery('.wtab-desc-textarea').length > 0 ){

            jQuery('.wtab-desc-textarea').each(function( index ){
                var id = jQuery(this).attr('id');
                var content = jQuery(this).val();

                indexes.push(index);
                $wtab_editor.editor_id = id;
                $wtab_editor.invoke();
            });
        }

        jQuery("#wtab-panel-tabs").data( "indexes", indexes);
        return indexes;
    }());

    // Add new row to panel with textbox and wp editor
    jQuery('body').on( 'click', '.wtab-add-row', function(e){
        e.preventDefault();
        var length = row_length();

        //replace intended strings in markup and insert newly generated markup next to current element.
        var result  =   wtab_markup.replace( /{tab_title}/g, '' );
        result      =   result.replace( /{tab_desc}/g,  '' );
        result      =   result.replace( /{length}/g,    length );
        
        jQuery("#wtab_custom_product_data .clone-wrapper").append( result );
        $wtab_editor.editor_id = "wtab_desc_field_"+length;
        $wtab_editor.invoke();
    });

    jQuery('body').on('click', '.wtab-add-saved', function(e){
        e.preventDefault();
       jQuery("#wtab-dialog").dialog({
           modal: true
       }); 
    });

});