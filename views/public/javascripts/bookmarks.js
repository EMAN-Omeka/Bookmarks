if (!Omeka) {
    var Omeka = {};
}
   Omeka.wysiwyg = function (params) {
        // Default parameters
        initParams = {
            convert_urls: false,
            mode: "textareas", // All textareas
            theme: "advanced",
            theme_advanced_toolbar_location: "top",
            theme_advanced_statusbar_location: "none",
            theme_advanced_toolbar_align: "left",
            theme_advanced_buttons1: "bold,italic,underline,|,justifyleft,justifycenter,justifyright,|,bullist,numlist,|,link,formatselect,code",
            theme_advanced_buttons2: "",
            theme_advanced_buttons3: "",
            theme_advanced_blockformats: "p,address,pre,h1,h2,h3,h4,h5,h6,blockquote,address,div",
            // plugins: "paste,inlinepopups,media,autoresize", // original settings, media plugin disabled to avoid aggressive HTML filtering
            plugins: "paste,inlinepopups,autoresize",
            media_strict: false,
            width: "100%",
            autoresize_max_height: 500,
            entities: "160,nbsp,173,shy,8194,ensp,8195,emsp,8201,thinsp,8204,zwnj,8205,zwj,8206,lrm,8207,rlm",
            validate: false,
            verify_html: false,
        };

//         tinyMCE.init($.extend(initParams, params));
    };
    
jQuery(document).ready(function() {
    var selector = '#lanote, #bio';

    Omeka.wysiwyg({
        selector: selector,
        menubar: 'edit view insert format table',
//         plugins: 'lists link code paste media autoresize image table charmap hr',
            toolbar: ["bold italic underline strikethrough | sub sup | link  | forecolor backcolor | formatselect code | superscript subscript ", "hr | alignleft aligncenter alignright alignjustify | indent outdent | bullist numlist | table | pastetext, pasteword | charmap | anchor | media "],
            plugins: "lists,link,code,paste,media,autoresize,anchor,charmap,hr,table,textcolor",
        browser_spellcheck: true
    });
    tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'lanote');
    tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'bio');    
});  
