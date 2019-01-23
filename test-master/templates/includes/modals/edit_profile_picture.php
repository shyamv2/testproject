<?php
  include_once "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
  if(isset($_SESSION['id'])){
    $user = find_user($con, $_SESSION['id']);
  }
  else{
  	echo "An error occured!";
  	exit;
  }
?>
  <div class="edit-profile-picture">
  		<!--Image Preview-->
  		<div style="display: none" id="preview-image-profile">
  			<img src="<?php echo $user['picture'] ?>" class="preview" id="preview-image">
  		</div>
  	<div class="edit-profile-picture-opts">
  	<!--Original File Button and Form-->
  	<form id="formPreviewImage" style="display: none">
  		<input type="file" id="fileUpload" name="fileUpload" onchange="validateFile(this, this.value);"/>
  	</form>
  		<!--Fake Button-->
  		<button type="button" class="upload" id="uploadFake" data-toggle="tooltip" title="Upload a new file!"></button>
  		<!--Remove Current-->
  		<center><button type="button" onclick="previewRemoveImage();" class="btn btn-danger">Remove Current</button></center>
  	</div>
  	<!--Upload Process Bar-->
  		<center><div class="upload-bar-data"></div></center>
  		<div style="display: none" class="upload-bar"><div class="upload-bar-pro"></div></div>
  		<div class="msg_error"></div>
  	<!--Form Save Image-->
  	<form id="saveImage" style="display: inline">
  		<input type="hidden" id="x" name="x" />
          <input type="hidden" id="y" name="y" />
          <input type="hidden" id="w" name="w" />
          <input type="hidden" id="h" name="h" />
    </form>
  </div>


  <!--JAvascript function-->
  <script type="text/javascript">
  document.getElementById('uploadFake').addEventListener('click', function(){
      document.getElementById('fileUpload').click();
  });
  //Validate File
  function validateFile(input, fileName){
    var allowed_extensions = new Array("jpg", "jpeg", "png", "gif");
    var file_extension = fileName.split('.').pop(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.
    var file = input.files[0];
    var sizeLimit = 2 * Math.pow(10, 6);
    if($.inArray(file_extension, allowed_extensions) > -1){
      if(file.size <= sizeLimit){
        preloadProfileImage();
      }
      else{
        alert("File size limit: 2MB");
      }
    }
    else{
      alert("Upload a valid file. Allowed Extensions: JPG, PNG and GIF");
    }
  }
  //Preload Profile Image (send to server)
  function preloadProfileImage(){
      $('#formPreviewImage').ajaxSubmit({
      	dataType: "json",
          url  : '/src/ajax-actions/users/preload_profile_picture.php',
          uploadProgress: function(event, position, total, percentComplete){
          	$('.upload-bar').css("display", "block");
          	$('.upload-bar-pro').css("width", percentComplete + "%");
          	$('.upload-bar-data').html("File being uploaded, wait..." + percentComplete + "%");
          	$('.edit-profile-picture-opts').css("display", "none");
          },
          complete: function(data) {
          	data = $.parseJSON(data.responseText);
          	if(data['error'] == false){
  	            $(".edit-profile-picture-opts").html("<img style='float: right' src='" + data['img'] + "' id='imgCrop'>");
  	          	document.getElementById('saveImage').insertAdjacentHTML('beforeend', "<input type='hidden' id='image' name='image' value='" +  data['img'] + "' />");
  	          	var buttons = '<div class=\"modal-footer\"><button type=\"button\" onclick=\"loadModal(\'edit_profile_picture.php\', \'editProfilePictureModal\', this)\" data-dismiss=\"modal\" class=\"btn btn-secondary\">Cancel</button><button type=\"button\" class=\"btn btn-primary\" onclick=\"saveImageProfile(this)\">Save</button></div>';
  	          	$(".edit-profile-picture").after(buttons);
  	          	document.getElementById('preview-image').src =  data['img'];
  	          	document.getElementById('preview-image-profile').style.display = "block";
  	          	$('.upload-bar').css("display", "none");
          		$('.upload-bar-data').html("");
          		$('.edit-profile-picture-opts').css("display", "block");
  	            $('#imgCrop').Jcrop({
  		            aspectRatio: 1,
  		            onSelect: UpdateCrop,
  		            setSelect: [0, 0, 150, 150],
  	        	});
  	        }
  	        else{
  	        	alert(data['msg_error']);
  	        	$('.edit-profile-picture-opts').css("display", "block");
  	        	$('.upload-bar').css("display", "none");
          		$('.upload-bar-data').html("");
  	        }
          },
          error: function(){
          	alert("Something went wrong. Please, try again!");
          	$('.upload-bar').css("display", "none");
          	$('.upload-bar-data').html("");
          	$('.edit-profile-picture-opts').css("display", "block");
          },
          type : 'POST'
      });
  }
  function UpdateCrop(c){
  	    var image = document.getElementById('imgCrop');
  		var imgWidth = image.naturalWidth;
  		var imgHeight = image.naturalHeight;
  		var mulW = imgWidth/parseInt(image.style.width.replace("px", ""));
  		var mulH = imgHeight/parseInt(image.style.height.replace("px", ""));
          $('#x').val(c.x * mulW);
  		$('#y').val(c.y * mulH);
  		$('#w').val(c.w * mulW);
  		$('#h').val(c.h * mulH);
  		showPreview(c, parseInt(image.style.width.replace("px", "")), parseInt(image.style.height.replace("px", "")));
  }
  function showPreview(coords, iw, ih){
  	var rx = (140 / coords.w);
  	var ry = (140 / coords.h);
  	console.log(iw + " - " + ih);
  	$('#preview-image').css({
  		width: Math.round(rx * iw) + 'px',
  		height: Math.round(ry * ih) + 'px',
  		marginLeft: '-' + Math.round(rx * coords.x) + 'px',
  		marginTop: '-' + Math.round(ry * coords.y) + 'px'
  	});
  }
  function saveImageProfile(button){
  	 $('#saveImage').ajaxSubmit({
  		url: "/src/ajax-actions/users/save_profile_picture.php",
  		success: function(data){
  			button.innerHTML = "Done!";
  			setTimeout("location.reload()", 2000);
  		},
  		beforeSend: function(data){
  			button.innerHTML = "Saving...";
  		},
  		error: function(){
  			alert("An error occured!");
  		},
  		type: 'POST'
  	});
  }
  function previewRemoveImage(){
  	document.getElementById('preview-image').src = "/images/profile_picture.jpg";
    $("#preview-image").css({
      margin: "0 auto"
    })
  	$(".edit-profile-picture-opts").html("");
  	var buttons = "<div class='modal-footer'><button type='button' onclick=\"loadModal('edit_profile_picture.php', 'editProfilePictureModal', this)\" data-dismiss='modal' class='btn btn-secondary'>Cancel</button><button type='button' class='btn btn-primary' onclick='saveImageProfileStandard(this)'>Save</button></div>";
      document.getElementById('saveImage').insertAdjacentHTML('beforeend', buttons);
      document.getElementById('preview-image-profile').style.display = "block";
  }
  function saveImageProfileStandard(button){
  	$('#saveImage').ajaxSubmit({
  		url: "/src/ajax-actions/users/remove_profile_picture.php",
  		success: function(data){
  			button.innerHTML = "Done!";
  			setTimeout("location.reload()", 2000);
  		},
  		beforeSend: function(data){
  			button.innerHTML = "Saving...";
  		},
  		error: function(){
  			alert("An error occured!");
  		},
  	});
  }
  </script>
