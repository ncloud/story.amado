
(function () {
    var caches = [];

    var ghostdown = function () {
        return [
            // --- xxx --- block
            {
                type: 'lang',
                filter: function (text) {
                    var blockMarkdownRegex = /^\-\-\-(.*?)\-\-\-?$/gim;

                    return text.replace(blockMarkdownRegex, function (match, key) {
                        if(match == '------') return match;

                        if(match.indexOf('-end') < 0) return '<section class="block_begin">' + key+ '</section>';
                        else return '<section class="block_end"></section>';
                    });
                }
            },
            {
                type: 'html',
                filter: function (text) {
                    var blockMarkdownRegex = /\<section class=\"block_begin\"\>(.*?)\<\/section\>?$/gim;

                    text = text.replace(blockMarkdownRegex, function (match, id) {
                        var keys = $.trim(id).split(':');
                        
                        if(keys.length == 1) { 
                            keys[1] = '';
                            keys[2] = '';
                        }

                        if(keys.length == 2) keys[2] = '';

                        return '<section class="block block-' + keys[0] + '"' + (keys[1] != '' ? ' style="background-color:' + keys[1] + ';' : '') + '">' + (keys[2] != '' ? '<h3 class="block-title">' + keys[2] + '</h3>' : '');
                    });

                    text = text.replace(/\<section class=\"block_end\"\>\<\/section\>?$/gim, function (match) {
                        return '</section>';
                    });

                    return text;
                }
            },

            // : keyword syntax
            {
                type: 'lang',
                filter: function(text) {
                    var keywordMarkdownRegex = /^\:( |[^\:])(.*?)( |[^\:])\:( |[^\:])(.*?)?$/gim;

                    return text.replace(keywordMarkdownRegex, function (match) {  
                        var keys = new Array();
                        var matches = match.split(':');
                        if(matches.length > 2) {
                            keys.push(matches[1]);

                            var temp = new Array();;
                            for(var i = 2 ; i < matches.length ; i++ ) temp.push(matches[i]);

                            keys.push(temp.join(':'));
                        }

                        for(var i = 0 ; i < keys.length ; i++) keys[i] = $.trim(keys[i]);

                        if(keys.length == 2) {
                            switch(keys[0]) {
                                case 'youtube':                            
                                    var youtube_video_id = youtubeParser(keys[1]);

                                    return '<section class="youtube-widget" style="background-image:url(' + (youtube_video_id == '' ? '' : ('http://img.youtube.com/vi/' + youtube_video_id + '/0.jpg')) + ');">' +
                                           '<span class="media"></span>' +
                                           '<span class="vendor">Youtube</span>' +
                                           '</section>';
                                break;
                                case 'vimeo':                                
                                    var vimeo_video_id = vimeoParser(keys[1]);
                                    var default_style = '';
                                    if(typeof(caches['vimeo_thumbnail_' + vimeo_video_id]) != 'undefined') {
                                        default_style = 'background-image:url(' + caches['vimeo_thumbnail_' + vimeo_video_id] + ');';
                                    } else {
                                        $.ajax({
                                            type:'GET',
                                            url: 'http://vimeo.com/api/v2/video/' + vimeo_video_id + '.json',
                                            jsonp: 'callback',
                                            dataType: 'jsonp',
                                            success: function(data){
                                                var thumbnail_src = data[0].thumbnail_large;
                                                $(".vimeo_widget_" + vimeo_video_id).css('background-image', 'url(' + thumbnail_src + ')');
                                                caches['vimeo_thumbnail_' + vimeo_video_id] = thumbnail_src;
                                            }
                                        });
                                    }

                                    return '<section class="vimeo_widget_' + vimeo_video_id + ' vimeo-widget" style="' + default_style + '">' +
                                           '<span class="media"></span>' +
                                           '<span class="vendor">Vimeo</span>' +
                                           '</section>';
                                break;
                                default: 
                                    return ''; 
                                break;
                            }
                        } else {
                            return 'error';
                        }
                    });

                }
            },

            // ![] image syntax
            {
                type: 'lang',
                filter: function (text) {
                    var imageMarkdownRegex = /^(?:\{<(.*?)>\}|[\>+] )?!(?:\[([^\n\]]*)\])(?:\(([^\n\]]*)\))?$/gim,
                        /* regex from isURL in node-validator. Yum! */
                        uriRegex = /^(?!mailto:)(?:(?:https?|ftp):\/\/)?(?:\S+(?::\S*)?@)?(?:(?:(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[0-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))|localhost)(?::\d{2,5})?(?:\/[^\s]*)?$/i,
                        pathRegex = /^(\/)?([^\/\0]+(\/)?)+$/i;

                    return text.replace(imageMarkdownRegex, function (match, key, alt, src) {  
                        var classname = '';
                        var alts = alt.split(':');

                        if(alts.length > 1 && alts[alts.length-1].indexOf('/') === -1) {
                            classname = 'image_' + alts[alts.length-1];
                            delete alts[alts.length-1];

                            alt = alts.join(':');
                        }

                        var result = "";

                        if (src && (src.match(uriRegex) || src.match(pathRegex))) {
                            result = '<img class="js-upload-target' + (classname.length > 0 ? ' ' + classname : '') + '" src="' + src + '"/>';
                        }
                        return '<section id="image_upload_' + key + '" class="js-drop-zone image-uploader' + (classname.length > 0 ? ' ' + classname + '_parent' : '') + '">' + result +
                               (classname.length > 0 ? '<div class="classname">' + classname.substr(6) + '</div>' : '') +
                               '<div class="description">Add image of <strong>' + alt + '</strong></div>' +
                               '<input data-url="upload" class="js-fileupload main fileupload" type="file" name="uploadimage">' +
                               '</section>';
                    });
                }
            },

            // 4 or more inline underscores e.g. Ghost rocks my _____!
            {
                type: 'lang',
                filter: function (text) {
                    return text.replace(/([^_\n\r])(_{4,})/g, function (match, prefix, underscores) {
                        return prefix + underscores.replace(/_/g, '&#95;');
                    });
                }
            }
        ];
    };

    // Client-side export
    if (typeof window !== 'undefined' && window.Showdown && window.Showdown.extensions) {
        window.Showdown.extensions.ghostdown = ghostdown;
    }
    // Server-side export
    if (typeof module !== 'undefined') module.exports = ghostdown;
}());