
$(function() {
    tinyMCE.baseURL = "http://localhost/arcadebooster/application/plugin/tinymce/tiny_mce_4.0.5";
    tinymce.init({
        selector: "textarea.tinymce",
        theme: "modern",
        width: '98%',
        height: 150, 
        plugins: [
            "autoresize advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons paste textcolor"
        ],
        content_css: "http://localhost/arcadebooster/application/plugin/tinymce/css/content.css",
                toolbar: " insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview fullpage | forecolor backcolor",
        style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ]
    });
});;