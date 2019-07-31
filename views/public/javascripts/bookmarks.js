if (!Omeka) {
    var Omeka = {};
}
    Omeka.wysiwyg = function (params) {
        // Default parameters
        initParams = {
            convert_urls: false,
            selector: "textarea",                     
            menubar: false,
            statusbar: false,
            toolbar_items_size: "small",
            toolbar: "bold italic underline | alignleft aligncenter alignright | bullist numlist | link formatselect code",
            plugins: "lists,link,code,paste,media,autoresize",
            autoresize_max_height: 500,
            entities: "160,nbsp,173,shy,8194,ensp,8195,emsp,8201,thinsp,8204,zwnj,8205,zwj,8206,lrm,8207,rlm",
            verify_html: false,
            add_unload_trigger: false
        };

        tinymce.init($.extend(initParams, params));
    };
    
jQuery(document).ready(function() {
    var selector = '#lanote, #biographie';

    Omeka.wysiwyg({
        selector: selector,       
        menubar: 'edit view insert format table',
//         plugins: 'lists link code paste media autoresize image table charmap hr',
        toolbar: ["bold italic underline strikethrough | sub sup | link  | forecolor backcolor | formatselect code | superscript subscript ", "hr | alignleft aligncenter alignright alignjustify | indent outdent | bullist numlist | table | pastetext, pasteword | charmap | anchor | media | image"],
        plugins: "lists,link,code,paste,media,autoresize,anchor,charmap,hr,table,textcolor,image",
        browser_spellcheck: false
    });
});   
/*
    tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'lanote');
    tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'bio');    
*/  
/*
    
    $('#submit').on('click', '#savepage, #savenote', function () {
      tinymce.triggerSave();
      return false;
    });
*/
 
/*
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
                console.log('Sauvegarde');                
            });
        },
*/