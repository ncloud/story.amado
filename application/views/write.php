    <style type="text/css">
        @import url("<?php echo site_url('/css/image_uploader.css');?>");
        @import url("<?php echo site_url('/css/ghostdown_widget.css');?>");
        @import url("<?php echo site_url('/css/ghostdown.css');?>");
        @import url("<?php echo site_url('/css/write.css');?>");

        @import url("<?php echo site_url('/css/view_content.css');?>");
    </style>

    <?php if(isset($message) && !empty($message)) { ?>
    <div class="alert alert-<?php echo $message->type;?>">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <?php
        if(is_array($message->content)) {
          $errors = $message->content;
          print_r($errors);
          echo '에러가 발생했습니다';
        } else {
          $errors = array();
          echo $message->content;
      }
      ?>
    </div>
    <?php } ?>

    <form id="write_form" class="write_form" method="POST" onsubmit="return onWrite(this);">
        <input type="hidden" name="story_id" value="<?php echo isset($story) ? $story->id : '';?>" />
        <input type="hidden" name="story_preview_id" value="<?php echo isset($story) ? $story->id : '';?>" />

        <div class="input_wrap">
            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="input-fluid title error" name="title" placeholder="제목" value="<?php echo isset($story) && $story->title ? htmlspecialchars($story->title) : '';?>" />                
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="is_publish"<?php echo isset($story) && $story->is_publish == 'yes' ? ' checked="checked"': '';?> /> 발행
                    </label>
                  </div>
                  <?php if(isset($story) && $story->publish_time != '0000-00-00 00:00:00') { ?>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="update_publish" /> 발행시간 업데이트
                      </label>
                    </div>
                  <?php } ?>
                </div>
            </div>
            <div class="form-group">
                <textarea spellcheck="false" class="textarea-fluid sub_title not_required" name="sub_title" placeholder="부제목 (생략가능)"><?php echo isset($story) && $story->sub_title ? htmlspecialchars($story->sub_title) : '';?></textarea>
            </div>
            
            <div class="editor">
                <div class="outer">
                  <div class="editorwrap">
                    <div class="entry-markdown">
                      <header class="floatingheader">
                        편집기 
                      </header>
                      <div class="entry-markdown-content">
                        <textarea id="entry-markdown"spellcheck="false" class="content" name="content" placeholder="내용"><?php echo isset($story) && $story->content ? $story->content : '';?></textarea>
                      </div>
                    </div>
                    <div class="entry-preview active">
                      <header class="floatingheader">
                        미리보기
                      </header>
                      <div class="entry-preview-content view_content">
                        <div class="rendered-markdown"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            <div class="form-tags">
                <input type="text" class="input-fluid tags not_required" name="tags" placeholder="태그 (생략가능)" value="<?php echo isset($story) && $story->tags ? $story->tags : '';?>" />
            </div>
        </div>
        
          <div class="form-actions">  
              <span id="save_hint" class="hint"></span>
              <button id="save_button" class="btn btn-primary disabled"><?php echo $edit_mode ? '저장' : '작성';?></button>
              <button id="preview_button" class="btn" onclick="onPreview(); return false;"><?php echo $edit_mode ? '보기' : '미리보기';?></button>
          </div>
    </form>

<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/codemirror.js');?>"></script>

<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/jquery.ui.widget.js');?>"></script>

<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/jquery.file.upload.js');?>"></script>
<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/jquery.ui.upload.js');?>"></script>

<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/showdown.js');?>"></script>
<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/showdown.ghostdown.js');?>"></script>
<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/extensions/showdown.table.js');?>"></script>

<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/ghostdown.js');?>"></script>

<script type="text/javascript">
    var $save_button = $("#save_button");
    var $save_hint = $("#save_hint");
    var $preview_button = $("#preview_button");

    function onWrite(form) {
        if(typeof(from) == 'undefined') {
          var $form = $("#write_form");
          var form = $form.get(0);
        } else {
          var $form = $(form);
        }
        
        if(form.title.value == '') {
            form.title.focus();

            return false;
        }

        var datas = new Array();
        $form.find('input, textarea').each(function(index, data) {
          if($.inArray(data.name, ['story_id']) >= 0) return;
          if(data.name == '') return;
          if(data.type == 'checkbox') {
            if(data.checked) {
              datas.push(data.name + "=yes");
            } else {
              datas.push(data.name + "=no");
            }

            return true;
          }

          datas.push(data.name + "=" + encodeURIComponent(data.value));
        });

        var $story_id = $form.find('input[name=story_id]');

        $save_button.addClass('disabled');
        $save_hint.text('저장중');
        $.ajax({
          url: "<?php echo site_url('/ajax/save');?>/" + $story_id.val(),
          type: "POST",
          data: datas.join('&'),
          dataType: "json",
          success: function(data) {
            if(data.success) {
              history.pushState('data', '', '<?php echo site_url('/edit/');?>/' + data.content.id);

              $story_id.val(data.content.id);
              
              $save_hint.text('저장됨');
              $save_button.text('저장');
              $preview_button.text('보기');
            }
          },
          error: function(data) {
              console.log(data);
          }
        });

        return false;
    }

    function onPreview(form) {
      if(typeof(from) == 'undefined') {
        var $form = $("#write_form");
        var form = $form.get(0);
      }
        
      if(form.title.value == '') {
          form.title.focus();

          return false;
      }

        var datas = new Array();
        $form.find('input, textarea').each(function(index, data) {
          if($.inArray(data.name, ['story_id']) >= 0) return;
          if(data.name == '') return;
          if(data.type == 'checkbox') {
            if(data.checked) {
              datas.push(data.name + "=yes");
            } else {
              datas.push(data.name + "=no");
            }

            return true;
          }

          datas.push(data.name + "=" + encodeURIComponent(data.value));
        });

        var $story_preview_id = $form.find('input[name=story_preview_id]');

        if($preview_button.text() == '보기') {
          var $story_id = $form.find('input[name=story_id]');

          var url = "<?php echo site_url('/view/');?>/" + $story_id.val();
          var win = window.open(url, '_blank');
        } else {
          $preview_button.addClass('disabled');

          $.ajax({
            url: "<?php echo site_url('/ajax/preview');?>/" + $story_preview_id.val(),
            type: "POST",
            data: datas.join('&'),
            dataType: "json",
            success: function(data) {
              if(data.success) {              
                $story_preview_id.val(data.content.temporary_id);
                
                var url = "<?php echo site_url('/preview');?>/" + data.content.temporary_id;
                var win=window.open(url, '_blank');
              }

              $preview_button.removeClass('disabled');
            },
            error: function(data) {
            }
          });
        }

      return false;
    }

    function onResizeEvent() {
      var $content = $("#content");
      var $input_wrap = $("#content .input_wrap");
      var height = 0;

      $input_wrap.find('> div').each(function(i, data) {
        var $data = $(data);
        if(!$data.is('.editor')) height += $(data).outerHeight();
      });

      height += 45; // 15*3 (여백)

      $content.find('.editor').height($content.outerHeight() - height - 15);
    }

    $(window).resize(function() {
      onResizeEvent();
    });

    $(window).bind('keydown', function(event) {
        if (event.ctrlKey || event.metaKey) {
            switch (String.fromCharCode(event.which).toLowerCase()) {
            case 's':
                onWrite();
                event.preventDefault();
                break;
            case 'p':
                onPreview();
                event.preventDefault();
                break;
            case 'f':
                event.preventDefault();
                break;
            case 'g':
                event.preventDefault();
                break;
            }
        }
    });
   
    $(document).ready(function(){
        $('textarea').autosize();   
        onResizeEvent();

        $("input[name=title], input[name=is_publish], textarea[name=sub_title], textarea[name=content], input[name=tags]").each(function(index, obj) {
          var $this = $(this);

          if(this.type == 'checkbox')
            var val = this.checked;
          else 
            var val = $this.val();

          $this.data('old_value', val);
        }).on('change keyup paste', function() {
          var $this = $(this);

          if(this.type == 'checkbox')
            var val = this.checked;
          else 
            var val = $this.val();

          if(val != $this.data('old_value')) {
            $this.data('old_value', val);
            
            $save_button.removeClass('disabled');
            $preview_button.text('미리보기');

            $save_hint.text('');
          }
        });
    });
</script>