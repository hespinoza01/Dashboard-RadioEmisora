var optype = document.getElementById("optype");
var nucarp = document.getElementById("nucarp");
//var namecarpaudio = document.getElementById("namecarpaudio");
var sizefileall = document.getElementById("sizefileall");
var cantmusicTotal = document.getElementById('cantmusicTotal');
var minmusicatotal = document.getElementById('minmusicatotal');
		
function vpb_multiple_file_uploader(vpb_configuration_settings)
{
	this.vpb_settings = vpb_configuration_settings;
	this.vpb_files = "";
	this.vpb_browsed_files = []
	var self = this;
	var vpb_msg = "Sorry, your browser does not support this application. Thank You!";
	
	//Get all browsed file extensions
	function vpb_file_ext(file) {
		return (/[.]/.exec(file)) ? /[^.]+$/.exec(file.toLowerCase()) : '';
	}
	
	/* Display added files which are ready for upload */
	//with their file types, names, size, date last modified along with an option to remove an unwanted file
	vpb_multiple_file_uploader.prototype.vpb_show_added_files = function(vpb_value)
	{
		this.vpb_files = vpb_value;
		if(this.vpb_files.length > 0)
		{
			var vpb_added_files_displayer = vpb_file_id = "";
 			for(var i = 0; i<this.vpb_files.length; i++)
			{
				//Use the names of the files without their extensions as their ids
				var files_name_without_extensions = this.vpb_files[i].name.substr(0, this.vpb_files[i].name.lastIndexOf('.')) || this.vpb_files[i].name;
				vpb_file_id = files_name_without_extensions.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
				
				var vpb_file_to_add = vpb_file_ext(this.vpb_files[i].name);
				var vpb_class = $("#added_class").val();
				var vpb_file_icon;
				
				//Check and display File Size
				var vpb_fileSize = (this.vpb_files[i].size / 1024);
				if (vpb_fileSize / 1024 > 1)
				{
					if (((vpb_fileSize / 1024) / 1024) > 1)
					{
						vpb_fileSize = (Math.round(((vpb_fileSize / 1024) / 1024) * 100) / 100);
						var vpb_actual_fileSize = vpb_fileSize + " <span style='color: green'>GB</span>";
					}
					else
					{
						vpb_fileSize = (Math.round((vpb_fileSize / 1024) * 100) / 100)
						var vpb_actual_fileSize = vpb_fileSize + " <span style='color: green'>MB</span>";
					}
				}
				else
				{
					vpb_fileSize = (Math.round(vpb_fileSize * 100) / 100)
					var vpb_actual_fileSize = vpb_fileSize  + " <span style='color: green'>KB</span>";
				}
				
				//Check and display the date that files were last modified
				var vpb_date_last_modified = new Date(this.vpb_files[i].lastModifiedDate);
				var dd = vpb_date_last_modified.getDate();
				var mm = vpb_date_last_modified.getMonth() + 1;
				var yyyy = vpb_date_last_modified.getFullYear();
				var vpb_date_last_modified_file = dd + '/' + mm + '/' + yyyy;
				
				//File Display Classes
				if( vpb_class == 'vpb_blue' ) { 
					var new_classc = 'vpb_white';
				} else {
					var new_classc = 'vpb_blue';
				}
				
				
				if(typeof this.vpb_files[i] != undefined && this.vpb_files[i].name != "")
				{
					//Check for the type of file browsed so as to represent each file with the appropriate file icon
					
					if( vpb_file_to_add == "jpg" || vpb_file_to_add == "JPG" || vpb_file_to_add == "jpeg" || vpb_file_to_add == "JPEG" || vpb_file_to_add == "gif" || vpb_file_to_add == "GIF" || vpb_file_to_add == "png" || vpb_file_to_add == "PNG" ) 
						vpb_file_icon = '<img src="../imagenes/images_file.gif" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "doc" || vpb_file_to_add == "docx" || vpb_file_to_add == "rtf" || vpb_file_to_add == "DOC" || vpb_file_to_add == "DOCX" )
						vpb_file_icon = '<img src="../imagenes/doc.gif" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "pdf" || vpb_file_to_add == "PDF" )
						vpb_file_icon = '<img src="../imagenes/pdf.gif" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "txt" || vpb_file_to_add == "TXT" || vpb_file_to_add == "RTF" )
						vpb_file_icon = '<img src="../imagenes/txt.png" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "php" )
						vpb_file_icon = '<img src="../imagenes/php.png" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "css" )
						vpb_file_icon = '<img src="../imagenes/general.png" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "js" )
						vpb_file_icon = '<img src="../imagenes/general.png" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "html" || vpb_file_to_add == "HTML" || vpb_file_to_add == "htm" || vpb_file_to_add == "HTM" )
						vpb_file_icon = '<img src="../imagenes/html.png" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "setup" )
						vpb_file_icon = '<img src="../imagenes/setup.gif" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "video" )
						vpb_file_icon = '<img src="../imagenes/video.gif" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "real" )
						vpb_file_icon = '<img src="../imagenes/real.gif" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "psd" )
						vpb_file_icon = '<img src="../imagenes/psd.gif" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "fla" )
						vpb_file_icon = '<img src="../imagenes/fla.gif" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "xls" )
						vpb_file_icon = '<img src="../imagenes/xls.gif" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "swf" )
						vpb_file_icon = '<img src="../imagenes/swf.gif" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "eps" )
						vpb_file_icon = '<img src="../imagenes/eps.gif" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "exe" )
						vpb_file_icon = '<img src="../imagenes/exe.gif" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "binary" )
						vpb_file_icon = '<img src="../imagenes/binary.png" align="absmiddle" border="0" alt="" />';
					else if( vpb_file_to_add == "zip" )
						vpb_file_icon = '<img src="../imagenes/archive.png" align="absmiddle" border="0" alt="" />';
					else
						vpb_file_icon = '<img src="../imagenes/general.png" align="absmiddle" border="0" alt="" />';
					
					//Assign browsed files to a variable so as to later display them below
					// vpb_added_files_displayer += '<tr id="add_fileID'+vpb_file_id+'" class="'+new_classc+'"><td>'+vpb_file_icon+' '+this.vpb_files[i].name.substring(0, 40)+'</td><td><span id="uploading_'+vpb_file_id+'"><span style=color:blue;>Ready</span></span></td><td>'+vpb_actual_fileSize+'</td><td><span id="remove'+vpb_file_id+'"><span class="vpb_files_remove_left_inner" onclick="vpb_remove_this_file(\''+vpb_file_id+'\',\''+this.vpb_files[i].name+'\');">Remove</span></span></td></tr></div>';
					vpb_added_files_displayer += '<div id="add_fileID'+vpb_file_id+'" class="'+new_classc+' list_group_music">'+
						'<span style="color: #000">'+vpb_file_icon+' '+this.vpb_files[i].name.substring(0, 40)+'</span><br>'+
							'<span id="uploading_'+vpb_file_id+'">'+
								'<span style=color:green;>Ready</span>'+
							'</span>'+
							'<span style="color: #000"> <span style="color: red">|</span> '+vpb_actual_fileSize+' <span style="color: red">|</span> </span>'+
							'<span id="progressCounter_'+vpb_file_id+'" class="progressCount">0 <span style="color: red; font-size: 30px;">%</span></span>'+
						'</div>';
					
				}
			}

			// '<span id="remove'+vpb_file_id+'" style="border-button: 1px solid #000">'+
			// 					'<span class="vpb_files_remove_left_inner" onclick="vpb_remove_this_file(\''+vpb_file_id+'\',\''+this.vpb_files[i].name+'\');">Remove</span>'+
			// 				'</span>'+
			//Display browsed files on the screen to the user who wants to upload them
			$("#add_files").append(vpb_added_files_displayer);
			$("#added_class").val(new_classc);
		}
	}
	
	//File Reader
	vpb_multiple_file_uploader.prototype.vpb_read_file = function(vpb_e) {
		if(vpb_e.target.files) {
			cantmusicTotal.innerHTML = vpb_e.target.files.length;
			self.vpb_show_added_files(vpb_e.target.files);
			self.vpb_browsed_files.push(vpb_e.target.files);
		} else {
			alert('Lo sentimos, un archivo que ha especificado no se pudo leer en este momento. ¡Gracias!');
		}
	}
	
	
	function addEvent(type, el, fn){
	if (window.addEventListener){
		el.addEventListener(type, fn, false);
	} else if (window.attachEvent){
		var f = function(){
		  fn.call(el, window.event);
		};			
		el.attachEvent('on' + type, f)
	}
}

	
	//Get the ids of all added files and also start the upload when called
	vpb_multiple_file_uploader.prototype.vpb_starter = function() {
		if (window.File && window.FileReader && window.FileList && window.Blob) {		
			 var vpb_browsed_file_ids = $("#"+this.vpb_settings.vpb_form_id).find("input[type='file']").eq(0).attr("id");
			 document.getElementById(vpb_browsed_file_ids).addEventListener("change", this.vpb_read_file, false);
			 document.getElementById(this.vpb_settings.vpb_form_id).addEventListener("submit", this.vpb_submit_added_files, true);
		} 
		else { alert(vpb_msg); }
	}
	
	//Call the uploading function when click on the upload button
	vpb_multiple_file_uploader.prototype.vpb_submit_added_files = function(){ 
		
		if(optype.value==0)
			alert("Seleccione una extension a convertir");
		else if(document.getElementById("archivo").value==""){
			alert("Elegir archivos...");
				
			}
			else{
		
			if(document.getElementById("one_music").checked == true){
				if($("#nucarp").val()=="") {
					alert("Nombre Carpeta de Audios * REQUERIDA");
				}
				else{
					self.vpb_upload_bgin();
				}
			}

			
						

			if(document.getElementById("radioBuscar").checked == true){
				if(document.getElementById("listnamemusica").value == "0"){
					alert("Seleccione una Carpeta");
				}
				else{
				self.vpb_upload_bgin();
				}
			}
		}

	}
	
	//Start uploads
	vpb_multiple_file_uploader.prototype.vpb_upload_bgin = function() {
		if(this.vpb_browsed_files.length > 0) {
			for(var k=0; k<this.vpb_browsed_files.length; k++){
				var file = this.vpb_browsed_files[k];
				this.vasPLUS(file,0);
			}
		}
	}
	
	//Main file uploader
	vpb_multiple_file_uploader.prototype.vasPLUS = function(file,file_counter)
	{
		if(typeof file[file_counter] != undefined && file[file_counter] != '')
		{
			
			//Use the file names without their extensions as their ids
			var files_name_without_extensions = file[file_counter].name.substr(0, file[file_counter].name.lastIndexOf('.')) || file[file_counter].name;
	
			var ids = files_name_without_extensions.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
	
			var vpb_browsed_file_ids = $("#"+this.vpb_settings.vpb_form_id).find("input[type='file']").eq(0).attr("id");
	
			var removed_file = $("#"+ids).val();
			
			if ( removed_file != "" && removed_file != undefined && removed_file == ids )
			{
				self.vasPLUS(file,file_counter+1);
			}
			else
			{

				sumtam += file[file_counter].size;
				
				var dataString = new FormData();
				dataString.append('upload_file',file[file_counter]);
				dataString.append('upload_file_ids',ids);

				// dataString.append("archivo", file[file_counter]);
		    if(optype.value==1){
			    var ext_sin_conversion="";
			    let nombre = file[file_counter].name.split('.');
			    ext_sin_conversion = "." + nombre[1];
			    dataString.append("optype", ext_sin_conversion);
		    }
		    else{
	                dataString.append("optype", optype.value);
		    }
                dataString.append("nucarp", nucarp.value);
                //dataString.append("namecarpaudio", namecarpaudio.value);
                //dataString.append("listnameaudios", listnameaudios.value);
                dataString.append("listnamemusica", listnamemusica.value);
                dataString.append("switchopctions_audios", audiosRadioValue('switchopctions_audios'));
				dataString.append("switchopctions_musica", audiosRadioValue('switchopctions_musica'));
				
				sizefileall.innerHTML = formatBytes(sumtam);
				minmusicatotal.innerHTML = file_counter + 1;


					
				// url:this.vpb_settings.vpb_server_url,
				$.ajax({
					type:"POST",
					url:this.vpb_settings.vpb_server_url,
					data:dataString,
					cache: false,
					contentType: false,
					processData: false,
					xhr: function()
					{
						var xhr = new window.XMLHttpRequest();
						//Upload progress
						xhr.upload.addEventListener("progress", function(evt){
							if (evt.lengthComputable) {
							var percentComplete = Math.round((evt.loaded / evt.total)*100);
							//Do something with upload progress
							$("#progressCounter_"+ids).html(percentComplete+' <span style="color: red; font-size: 30px;">%</span>');
							}
						}, false);

					   //Download progress
						// XMLHttpRequest.addEventListener("progress", function(evt){
						// 	if (evt.lengthComputable) {  
						// 	var percentComplete = evt.loaded / evt.total;
						// 	//Do something with download progress
						// 	}
						// }, false); 

					  return xhr;
					},
					beforeSend: function() 
					{
						// $("#uploading_"+ids).html('<img src="images/loadings.gif" width="80" align="absmiddle" title="Upload...."/>');
						$("#remove"+ids).html('<span style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:blue;">Subiendo...</span>');
					},
					success:function(response) 
					{
						setTimeout(function() {
							var response_brought = response.indexOf(ids);
							//alert(response_brought);
							if ( response_brought != -1) {
								let nombre = file[file_counter].name.split('.');

								if(optype.value==1){
									ultarchivobase.innerHTML =file[file_counter].name;
																}
								else{
									ultarchivobase.innerHTML = nombre[0]+optype.value;
								}
								
								scrollheightdinamic += 75;
								// $("#ultarchivobase").html(file[file_counter].name);
								$("#add_files").animate({ scrollTop: scrollheightdinamic}, 1000);
								$("#uploading_"+ids).html('<span style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:blue;">Completado</span>');
								$("#remove"+ids).html('<span style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:gray;">Subido</span>');
							} else {
								var fileType_response_brought = response.indexOf('file_type_error');
								if ( fileType_response_brought != -1) {
									
									var filenamewithoutextension = response.replace('file_type_error&', '').substr(0, response.replace('file_type_error&', '').lastIndexOf('.')) || response.replace('file_type_error&', '');
									var fileID = filenamewithoutextension.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
									$("#uploading_"+fileID).html('<span style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:red;">Archivo no valido</span>');
									$("#remove"+fileID).html('<span style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:orange;">Cancelado</span>');
									
								} else {
									var filesize_response_brought = response.indexOf('file_size_error');
									if ( filesize_response_brought != -1) {
										var filenamewithoutextensions = response.replace('file_size_error&', '').substr(0, response.replace('file_size_error&', '').lastIndexOf('.')) || response.replace('file_size_error&', '');
										var fileID = filenamewithoutextensions.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
										$("#uploading_"+fileID).html('<span style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:red;">Tamaño excedido</span>');
										$("#remove"+fileID).html('<span style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:orange;">Cancelado</span>');
									} else {
										var general_response_brought = response.indexOf('general_system_error');
										if ( general_response_brought != -1) {
											alert('Lo sentimos, el archivo no se cargó...');
										}
										else { /* Do nothing */}
									}
								}
							}
							if (file_counter+1 < file.length ) {
								self.vasPLUS(file,file_counter+1); 
							} 
							else {
								
								location.reload(true);
								
							}
						},2000);
					}
				});
			 }
		} 
		else { alert('Sorry, this system could not verify the identity of the file you were trying to upload at the moment. Thank You!'); }
	}
	this.vpb_starter();
}

function vpb_remove_this_file(id, filename)
{
	if(confirm('If you are sure to remove the file: '+filename+' then click on OK otherwise, Cancel it.'))
	{
		$("#vpb_removed_files").append('<input type="hidden" id="'+id+'" value="'+id+'">');
		$("#add_fileID"+id).slideUp();
	}
	return false;
}