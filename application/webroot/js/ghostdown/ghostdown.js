

(function ($, ShowDown, CodeMirror) {
    "use strict";

  $(function() {

    if (!document.getElementById('entry-markdown'))
      return;
    
    var imageMarkdownRegex = /^(?:\{<(.*?)>\})?!(?:\[([^\n\]]*)\])(?:\(([^\n\]]*)\))?$/gim,
        markerRegex = /\{<([\w\W]*?)>\}/;

    var MarkerManager = function (editor) {
        var markers = {},
            uploadPrefix = 'image_upload',
            uploadId = 1;

        function addMarker(line, ln) {
            var marker,
                magicId = '{<' + uploadId + '>}';
            editor.setLine(ln, magicId + line.text);
            marker = editor.markText(
                {line: ln, ch: 0},
                {line: ln, ch: (magicId.length)},
                {collapsed: true}
            );

            markers[uploadPrefix + '_' + uploadId] = marker;
            uploadId += 1;
        }

        function getMarkerRegexForId(id) {
            id = id.replace('image_upload_', '');
            return new RegExp('\\{<' + id + '>\\}', 'gmi');
        }

        function stripMarkerFromLine(line) {
            var markerText = line.text.match(markerRegex),
                ln = editor.getLineNumber(line);

            if (markerText) {
                editor.replaceRange('', {line: ln, ch: markerText.index}, {line: ln, ch: markerText.index + markerText[0].length});
            }
        }

        function findAndStripMarker(id) {
            editor.eachLine(function (line) {
                var markerText = getMarkerRegexForId(id).exec(line.text),
                    ln;

                if (markerText) {
                    ln = editor.getLineNumber(line);
                    editor.replaceRange('', {line: ln, ch: markerText.index}, {line: ln, ch: markerText.index + markerText[0].length});
                }
            });
        }

        function removeMarker(id, marker, line) {
            delete markers[id];
            marker.clear();

            if (line) {
                stripMarkerFromLine(line);
            } else {
                findAndStripMarker(id);
            }
        }

        function checkMarkers() {
            _.each(markers, function (marker, id) {
                var line;
                marker = markers[id];
                if (marker.find()) {
                    line = editor.getLineHandle(marker.find().from.line);
                    if (!line.text.match(imageMarkdownRegex)) {
                        removeMarker(id, marker, line);
                    }
                } else {
                    removeMarker(id, marker);
                }
            });
        }

        function initMarkers(line) {
            var isImage = line.text.match(imageMarkdownRegex),
                hasMarker = line.text.match(markerRegex);

            if (isImage && !hasMarker) {
                addMarker(line, editor.getLineNumber(line));
            }
        }

        // public api
        _.extend(this, {
            markers: markers,
            checkMarkers: checkMarkers,
            addMarker: addMarker,
            stripMarkerFromLine: stripMarkerFromLine,
            getMarkerRegexForId: getMarkerRegexForId
        });

        // Initialise
        editor.eachLine(initMarkers);
    };

    var UploadManager = function (editor) {
        var markerMgr = new MarkerManager(editor);

        function findLine(result_id) {
            // try to find the right line to replace
            if (markerMgr.markers.hasOwnProperty(result_id) && markerMgr.markers[result_id].find()) {
                return editor.getLineHandle(markerMgr.markers[result_id].find().from.line);
            }

            return false;
        }

        function checkLine(ln, mode) {
            var line = editor.getLineHandle(ln),
                isImage = line.text.match(imageMarkdownRegex),
                hasMarker;

            // We care if it is an image
            if (isImage) {
                hasMarker = line.text.match(markerRegex);

                if (hasMarker && mode === 'paste') {
                    // this could be a duplicate, and won't be a real marker
                    markerMgr.stripMarkerFromLine(line);
                }

                if (!hasMarker) {
                    markerMgr.addMarker(line, ln);
                }
            }
            // TODO: hasMarker but no image?
        }

        function handleUpload(e, result_src) {
            /*jslint regexp: true, bitwise: true */
            var line = findLine($(e.currentTarget).attr('id')),
                lineNumber = editor.getLineNumber(line),
                match = line.text.match(/\([^\n]*\)?/),
                replacement = '(http://)';
            /*jslint regexp: false, bitwise: false */

            if (match) {
                // simple case, we have the parenthesis
                editor.setSelection({line: lineNumber, ch: match.index + 1}, {line: lineNumber, ch: match.index + match[0].length - 1});
            } else {
                match = line.text.match(/\]/);
                if (match) {
                    editor.replaceRange(
                        replacement,
                        {line: lineNumber, ch: match.index + 1},
                        {line: lineNumber, ch: match.index + 1}
                    );
                    editor.setSelection(
                        {line: lineNumber, ch: match.index + 2},
                        {line: lineNumber, ch: match.index + replacement.length }
                    );
                }
            }
            editor.replaceSelection(result_src);
        }

        function getEditorValue() {
            var value = editor.getValue();

            _.each(markerMgr.markers, function (marker, id) {
                /*jslint unparam:true*/
                value = value.replace(markerMgr.getMarkerRegexForId(id), '');
            });

            return value;
        }


        // Public API
        _.extend(this, {
            getEditorValue: getEditorValue,
            handleUpload: handleUpload
        });

        // initialise
        editor.on('change', function (cm, changeObj) {
            /*jslint unparam:true*/
            var linesChanged = _.range(changeObj.from.line, changeObj.from.line + changeObj.text.length);

            _.each(linesChanged, function (ln) {
                checkLine(ln, changeObj.origin);
            });

            // Is this a line which may have had a marker on it?
            markerMgr.checkMarkers();
        });
    };

    // init
    //var delay;
    var converter = new ShowDown.converter({extensions: ['ghostdown', 'table']}),
        editor = CodeMirror.fromTextArea(document.getElementById('entry-markdown'), {
            mode: 'markdown',
            tabMode: 'indent',
            lineWrapping: true
        }),
        uploadMgr = new UploadManager(editor);


    function updatePreview() {
        $('#entry-markdown').val(editor.getValue()).change();

        var preview = document.getElementsByClassName('rendered-markdown')[0];
        preview.innerHTML = converter.makeHtml(editor.getValue());

        var filestorage = $('#entry-markdown-content').data('filestorage');
        $('.js-drop-zone').upload({editor: true, fileStorage: filestorage});
        $('.js-drop-zone').on('uploadsuccess', uploadMgr.handleUpload);
    }

    $(document).ready(function () {
        $('.entry-markdown header, .entry-preview header').click(function (e) {
            $('.entry-markdown, .entry-preview').removeClass('active');
            $(e.target).closest('section').addClass('active');
        });

        editor.on("change", function () {
            updatePreview();
        });

        updatePreview();

        // Sync scrolling
        function syncScroll(e) {
            // vars
            var $codeViewport = $(e.target),
                $previewViewport = $('.entry-preview-content'),
                $codeContent = $('.CodeMirror-sizer'),
                $previewContent = $('.rendered-markdown'),

            // calc position
                codeHeight = $codeContent.height() - $codeViewport.height(),
                previewHeight = $previewContent.height() - $previewViewport.height(),
                ratio = previewHeight / codeHeight,
                previewPostition = $codeViewport.scrollTop() * ratio;

            // apply new scroll
            $previewViewport.scrollTop(previewPostition);
        }

        // TODO: Debounce
        $('.CodeMirror-scroll').on('scroll', syncScroll);

        // Shadow on Markdown if scrolled
        $('.CodeMirror-scroll').scroll(function() {
            if ($('.CodeMirror-scroll').scrollTop() > 10) {
                $('.entry-markdown').addClass('scrolling');
            } else {
                $('.entry-markdown').removeClass('scrolling');
            }
        });
        // Shadow on Preview if scrolled
        $('.entry-preview-content').scroll(function() {
            if ($('.entry-preview-content').scrollTop() > 10) {
                $('.entry-preview').addClass('scrolling');
            } else {
                $('.entry-preview').removeClass('scrolling');
            }
        });

    });
  });

}(jQuery, Showdown, CodeMirror));
