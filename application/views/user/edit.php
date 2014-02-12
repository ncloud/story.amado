<?php
	$errors = array();
?>    

<style type="text/css">        
    @import url("<?php echo site_url('/css/image_uploader.css');?>");
    @import url("<?php echo site_url('/css/user.css');?>");
</style>

<div class="container">
  <form id="editform" class="edit_form form-horizontal" method="post">
    <div class="page-header">  
    	<?php if(isset($message) && !empty($message)) { ?>
  	  <div class="alert alert-<?php echo $message->type;?>">
  	  	<button type="button" class="close" data-dismiss="alert">&times;</button>
  	  	<?php
  	  		if(is_array($message->content)) {
  	  			$errors = $message->content;
  	  			echo '에러가 발생했습니다';
  	  		} else {
  	  			echo $message->content;
  			}
  	  	?>
  	  </div>
  	  <?php } ?>
      <h3>설정</h3>
    </div>

    <fieldset>
      <div class="form-group<?php echo isset($errors['name']) ? ' error' : '';?>">
          <label for="name">이름</label>
  		    <input id="name" type="text" class="form-control" name="name" value="<?php echo $user_data->name;?>" />
      </div>
      <div class="form-group<?php echo isset($errors['email']) ? ' error' : '';?>">
          <label for="email">이메일</label>
          <input id="email" type="text" class="form-control" name="email" value="<?php echo $user_data->email;?>" />
      </div>
      <div class="form-group<?php echo isset($errors['description']) ? ' error' : '';?>">
          <label for="description">설명</label>
          <textarea id="description" class="form-control" name="description"><?php echo $user_data->description;?></textarea>
          <div class="help-block">설명은 문장내 링크를 자동으로 인식합니다.<br />예) http://withstories.com / &lt;a href="http://withstories.com"&gt;http://withstories.com&lt;/a&gt;</div>
      </div>

      <hr />

      <div class="form-group">          

        <label>프로필</label>
        <section id="profile_image_upload" class="js-drop-zone small <?php !empty($user_data->profile) ? 'pre-image-uploader' : 'image-uploader';?>">
        <?php if(!empty($user_data->profile)) { ?>
          <img class="js-upload-target" src="<?php echo $user_data->profile;?>"/>
        <?php } else { ?>
          <span class="media"><span class="hidden">Image Upload</span></span><img class="js-upload-target" style="display: none;" src="">
        <?php } ?>
          <input data-url="upload" class="js-fileupload main fileupload" type="file" name="uploadimage">
          <input type="hidden" class="data_value" name="profile" value="<?php echo $user_data->profile;?>" />
          <div class="js-fail failed" style="display: none">Something went wrong :(</div>
          <button class="js-fail button-add" style="display: none">Try Again</button>
          <a class="image-url" title="Add image from URL"><span class="hidden">URL</span></a>
        </section>
      </div>

      <div class="form-group">          

        <label>커버</label>
        <section id="cover_image_upload" class="js-drop-zone <?php !empty($user_data->cover) ? 'pre-image-uploader' : 'image-uploader';?>">
        <?php if(!empty($user_data->cover)) { ?>
          <img class="js-upload-target" src="<?php echo $user_data->cover;?>"/>
        <?php } else { ?>
          <span class="media"><span class="hidden">Image Upload</span></span><img class="js-upload-target" style="display: none;" src="">
        <?php } ?>
          <input data-url="upload" class="js-fileupload main fileupload" type="file" name="uploadimage">
          <input type="hidden" class="data_value" name="cover" value="<?php echo $user_data->cover;?>" />
          <div class="js-fail failed" style="display: none">Something went wrong :(</div>
          <button class="js-fail button-add" style="display: none">Try Again</button>
          <a class="image-url" title="Add image from URL"><span class="hidden">URL</span></a>
        </section>
      </div>
    </fieldset>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">변경사항 저장</button>
        <a href="<?php echo site_url('/');?>" class="btn">취소</a>

        <div class="pull-right"><a href="#myModal" role="button" class="btn btn-info" data-toggle="modal">비밀번호 변경</a></div>
    </div>
  </form>

  <div id="myModal" class="password_modal modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">비밀번호 변경</h4>
      </div>
      <div class="modal-body">
        <form method="POST" action="<?php echo site_url('/ajax/change_password');?>" class="form-horizontal" onsubmit="onChangePassword(this); return false;">
        <fieldset>
          <div class="form-group">
            <label class="col-sm-4 control-label " for="old_password">현재 비밀번호</label>
            <div class="col-sm-7">
              <input id="old_password" type="password" class="form-control" name="old_password" value="" />
            </div>
          </div>
          <hr />
          <div class="form-group">
            <label class="col-sm-4 control-label" for="new_password">새로운 비밀번호</label>
            <div class="col-sm-7">
              <input id="new_password" type="password" class="form-control" name="new_password" value="" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label" for="new_password_re">새로운 비밀번호 확인</label>
            <div class="col-sm-7">
              <input id="new_password_re" type="password" class="form-control" name="new_password_re" value="" />
              <p class="help-block">
                잘못된 입력을 맞기 위해 새로운 비밀번호를 한번 더 입력해주세요.
              </p>
            </div>
          </div>
        </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
        <button type="button" class="btn btn-primary" onclick="doChangePassword(); return false;">변경하기</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/jquery.ui.widget.js');?>"></script>

<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/jquery.file.upload.js');?>"></script>
<script type="text/javascript" src="<?php echo site_url('/js/ghostdown/jquery.ui.upload.js');?>"></script>

<script type="text/javascript">
  function doChangePassword() {
    $("#myModal").find('form').submit();
  }

  function onChangePassword(form) {
    if(form.old_password.value == '') {
      form.old_password.focus();
      return false;
    }

    if(form.new_password.value == '') {
      form.new_password.focus();
      return false;
    }

    if(form.new_password_re.value == '') {
      form.new_password_re.focus();
      return false;
    }

    if(form.new_password.value != form.new_password_re.value) {
      alert('"새로운 비밀번호"와 "새로운 비밀번호 확인"을 같게 입력해주세요.');

      return false;
    }

    $.ajax({
      url: "<?php echo site_url('/ajax/update_user_data/'.$current_user->id);?>",
      type: "POST",
      data: "old_password=" + form.old_password.value + "&new_password=" + form.new_password.value + "&new_password_re=" + form.new_password_re.value,
      dataType: 'json',
      success: function(data) {
        if(data.success) { // 성공
          $("#myModal").modal('hide');
        } else {
          alert(data.message);
        }
      },
      error: function(data) {
      }
    });


    return true;
  }


  var filestorage = $('#editformt').data('filestorage');
  $('.js-drop-zone').upload({editor: true, fileStorage: filestorage});
  $('.js-drop-zone').on('uploadsuccess', function(e, data) {
    if(data == 'http://') data = '';

    var $target = $(e.target);
    $target.find('input.data_value').val(data);
  });
</script>
