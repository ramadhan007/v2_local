/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.allowedContent = true;
	
	//config.protectedSource.push( /<i[\s\S]*?\>/g ); //allows beginning <i> tag
	//config.protectedSource.push( /<\/i[\s\S]*?\>/g ); //allows ending </i> tag
	
	config.extraPlugins = 'readmore';
	config.toolbar = 'MyToolbar';
	
	config.toolbar_Basic =
        [
			{ name: 'tools', items : [ 'Maximize'] },
            { name: 'document', items : [ 'Source','-','Preview','Print','-' ] },
            { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
			{ name: 'links', items : [ 'Link','Unlink' ] },
			{ name: 'insert', items : [ 'Image','Table','HorizontalRule','Smiley','SpecialChar'] },
			'/',
			{ name: 'basicstyles', items : [ 'Bold','Italic','Underline' ] },
			{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote',
                '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
        ];
	
	config.toolbar_MyToolbar =
    [
        ['readmore']
    ];
	
	// allow i tags to be empty (for font awesome)
	// CKEDITOR.dtd.$removeEmpty['i'] = false
	
	// ALLOW <i></i>
	config.protectedSource.push(/<i[^>]*><\/i>/g);
	config.protectedSource.push(/<span[^>]*><\/span>/g);
	
};

/* CKEDITOR.on('dialogDefinition', function( ev ) {
    // Take the dialog window name and its definition from the event data.
    var dialogName = ev.data.name;
    var dialogDefinition = ev.data.definition;

    if ( dialogName == 'image' ) {
        dialogDefinition.onShow = function() {
            // This code will open the Link tab.
            this.selectPage( 'Link' );
        };
    }
}); */
