<?php
	include_once "{$_SERVER['DOCUMENT_ROOT']}/src/functions/system.php";
	if(isset($_SESSION['id'])){
		$user = find_user($con, $_SESSION['id']);
	}
	else{
		echo "Login to continue...";
		exit;
	}
?>
<div class="edit-profile-art">
	<!--Original File Button and Form-->
	<form id="formPreloadArt" style="display: none">
		<input type="file" id="fileUpload" name="fileUpload" onchange="validateFile(this, this.value);"/>
	</form>
	<!--Fake Button-->
	<button type="button" class="upload-art" id="uploadFake" style="background-size: 90%"></button>
	<!--Upload Process Bar-->
	<center><div class="upload-bar-data"></div></center>
	<div style="display: none" class="upload-bar"><div class="upload-bar-pro"></div></div>
	<div class="msg_error"></div>
</div>
	<form id="saveImage">
		<input type="hidden" id="x" name="x" />
        <input type="hidden" id="y" name="y" />
        <input type="hidden" id="w" name="w" />
        <input type="hidden" id="h" name="h" />
    </form>
<!--Javascript Functions-->
<script type="text/javascript">
//Fake Button Upload
document.getElementById('uploadFake').addEventListener('click', function(){
    document.getElementById('fileUpload').click();
});
//Preload Image Function
function preloadProfileArt(){
    $('#formPreloadArt').ajaxSubmit({
    	dataType: "json",
        url  : '/src/ajax-actions/users/preload_profile_art.php',
        uploadProgress: function(event, position, total, percentComplete){
					$('.upload-bar').css("display", "block");
					$('.upload-bar-pro').css("width", percentComplete + "%");
					$('.upload-bar-data').html("File being uploaded, wait..." + percentComplete + "%");
					$('.upload-art').css("display", "none");
        },
        complete: function(data) {
        	data = $.parseJSON(data.responseText);
        	console.log(data);
        	if(data['error'] == false){
	            $(".edit-profile-art").html("<img src='" + data['img'] + "' style='width: 100%' id='imgCrop'>");
	 						document.getElementById('saveImage').insertAdjacentHTML('beforeend', "<input type='hidden' id='image' name='image' value='" + data['img'] + "' />");
	          	var buttons = "<div class='modal-footer'><button type='button' onclick=\"loadModal('edit_profile_cover.php', 'upload_cover', this)\" data-dismiss='modal' class='btn btn-secondary'>Cancel</button><button type='button' class='btn btn-primary' onclick='saveProfileArt(this)'>Save</button></div>";
	          	document.getElementById('saveImage').insertAdjacentHTML('beforeend', buttons);
	            $('#imgCrop').Jcrop({
		            aspectRatio: 3,
		            onSelect: UpdateCrop,
		            setSelect: [0, 0, 823, 200],
	        	});
	        }
	        else{
	        	alert(data['msg_error']);
						$('.upload-art').css("display", "block");
						$('.upload-bar').css("display", "none");
						$('.upload-bar-data').html("");
	        }
        },
        error: function(){
        	alert("Something went wrong during the upload!");
					$('.upload-art').css("display", "block");
					$('.upload-bar').css("display", "none");
					$('.upload-bar-data').html("");
        },
        type : 'POST'
    });
}
//Validate File
function validateFile(input, fileName){
	var allowed_extensions = new Array("jpg", "jpeg", "png", "gif");
	var file_extension = fileName.split('.').pop(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.
	var file = input.files[0];
	var sizeLimit = 2 * Math.pow(10, 6);
	if($.inArray(file_extension, allowed_extensions) > -1){
		if(file.size <= sizeLimit){
			preloadProfileArt();
		}
		else{
			alert("File size limit: 2MB");
		}
	}
	else{
		alert("Upload a valid file. Allowed Extensions: JPG, PNG and GIF");
	}
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
}
function saveProfileArt(button){
	 $('#saveImage').ajaxSubmit({
		url: "/src/ajax-actions/users/save_profile_art.php",
		success: function(data){
			button.innerHTML = "Done!";
			setTimeout("location.reload()", 2000);
		},
		beforeSend: function(data){
			button.innerHTML = "Saving...";
		},
		error: function(){
			alert("Something went wrong during the saving process!");
		},
		type: 'POST'
	});
}
</script>
