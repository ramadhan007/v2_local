CKEDITOR.plugins.add('readmore',
{
	init: function (editor) {
		var pluginName = 'readmore';
		editor.ui.addButton('readmore',
			{
				label: 'Read More',
				command: 'OpenWindow1',
				icon: CKEDITOR.plugins.getPath('readmore') + 'images/readmore1.png'
			});
		var cmd = editor.addCommand('OpenWindow1', { exec: showMyDialog1 });
	}
});
function showMyDialog1(e) {
   e.insertHtml('{[readmore]}');
}