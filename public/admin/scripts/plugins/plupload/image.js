$(function() {
    //alert(SITE_URL);    
     var site_url                                                               = $("#hidden_site_url").val();
        
        var uploader                                                            = new plupload.Uploader({
        runtimes                                                                : 'gears,html5,flash,silverlight,browserplus',
        browse_button                                                           : 'pickfiles',
        container                                                               : 'container',
        max_file_size                                                           : '10mb',
        multi_selection                                                         : false,    
        url                                                                     : SITE_URL+'upload.php/?image_type=attendee',//site_url+'admin/breed/process_image',
        flash_swf_url                                                           : SITE_URL+'ui/admin/scripts/plugin/plupload/js/plupload.flash.swf',
        silverlight_xap_url                                                     : SITE_URL+'ui/admin/scripts/plugin/plupload/js/plupload.silverlight.xap',
        filters : [
                {title : "Image files", extensions : "jpg,gif,png,jpeg"},
                {title : "Zip files", extensions : "zip"},
                //{title : "Video files", extensions : "mp4"}
        ],
        resize                                                          : {width : 200, height : 200, quality : 100,crop: true},
        views: {
            list: true,
            thumbs: true, // Show thumbs
            active: 'thumbs'
        },
	});

	uploader.bind('Init', function(up, params) {
		//$('#filelist').html("<div>Current runtime: " + params.runtime + "</div>");
	});

	$('#uploadfiles').click(function(e) {
		uploader.start();
		e.preventDefault();
	});

	uploader.init();

	uploader.bind('FilesAdded', function(up, files) {
           
            
                $.each(files, function(i, file) {
                                $('#filelist').append(
                                        '<div class="images temp_class"   id="' + file.id + '">  <b></b>' +
                                '</div>');
                                });
            
            
                                
		uploader.start();
                uploader.refresh();
                //setTimeout(function () { up.start(); });
		//up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('UploadProgress', function(up, file) {
            //alert('test');
		$('#' + file.id + " b").html(file.percent + "%");
                
                
                
	});

	uploader.bind('Error', function(up, err) {
		$('#filelist').append("<div>Error: " + err.code +
			", Message: " + err.message +
			(err.file ? ", File: " + err.file.name : "") +
			"</div>"
		);

		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('FileUploaded', function(up, file,info) {
            //alert('test');   
            var obj = JSON.parse(info.response);
            var filename                                                        = obj.result.cleanFileName; 
		$('#' + file.id + " b").html(" ");
		//$('#' + file.id ).html('<img src="'+SITE_URL+'uploads/attendee/'+filename+'">');
		$('#display_thumb_image').html('<img src="'+SITE_URL+'uploads/attendee/'+filename+'" height="76" width="76"><input type="hidden" name="profile_pic" id="profile_pic" value="'+filename+'">');
         
	});
        
        
        
});