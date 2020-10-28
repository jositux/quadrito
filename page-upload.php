<?php get_header(); ?>

    <section class = "inner-page-wrapper">
        <section class = "container">
            <section class = "row content">
                <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <h1><?php the_title(); ?></h1>
                        <article class="entry-content">
                            <?php the_content(); ?>
                            
                            <div class = "col-md-6 upload-form">
                                <div class= "upload-response"></div>
                                <div class = "form-group">
                                    <label><?php __('Elegir fotos:', 'cvf-upload'); ?></label>
									
									
                                    <input type = "file" name = "files0" accept = "image/*" class = "files-data form-control"/>
									<img id="files0" src="#" alt="your image"  rel=""/>
									<input type = "file" name = "files1" accept = "image/*" class = "files-data form-control" />
									<img id="files1" src="#" alt="your image" />
									<input type = "file" name = "files2" accept = "image/*" class = "files-data form-control" />
									<img id="files2" src="#" alt="your image" />
									
									<input type = "file" name = "files3" accept = "image/*" class = "files-data-extra form-control invisible" />
									<img id="files3" src="#" alt="your image" />
									
									
                                </div>
                                <div class = "form-group">
                                    <input id="lalaala" type = "submit" value = "Subir fotos" class = "btn btn-primary btn-upload" />
                                </div>
                            </div>
							
							<div class="modal"></div>
                                                                                    
                            <script type = "text/javascript">
                            jQuery(document).ready(function( $ ) {
                                // When the Upload button is clicked...
                                $('body').on('click', '.upload-form .btn-upload', function(e){
                                    e.preventDefault;
									
									console.log($(this).attr('id'));
                                    
                                    console.log('subiendo');

                                    var fd = new FormData();
                                    var files_data = $('.upload-form .files-data'); // The <input type="file" /> field
									
									var real_index = 0;
                                    
                                    // Loop through each data and create an array file[] containing our files data.
                                    $.each($(files_data), function(i, obj) {
										console.log(obj.files.length);
                                        $.each(obj.files,function(j,file){
                                        if(obj.files.length == 1) {
                                            fd.append('files[' + real_index + ']', file);
											//console.log(j + file);
											real_index++;
										}
                                        })
                                    });
									
									if(real_index > 2) {
                                    
										// our AJAX identifier
										fd.append('action', 'cvf_upload_files');  

										// uncomment this code if you do not want to associate your uploads to the current page.
										fd.append('post_id', <?php echo $post->ID; ?>); 

										$.ajax({
											type: 'POST',
											url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
											data: fd,
											contentType: false,
											processData: false,
											success: function(response){
												//$('.upload-response').html(response); // Append Server Response
												$('.upload-response').append(response);
											}
										});
										
									}
                                });
								
								$body = $("body");
								
								/* Loading */
								$(document).on({
									ajaxStart: function() { $body.addClass("loading"); $('.modal').addClass("loading");    },
									ajaxStop: function() { $body.removeClass("loading"); $('.modal').removeClass("loading"); $('.files-data').val(""); }
								});
								
								/* Global value input file */
								var current_value = "";
								
								function readURL(input) {

								  if (input.files && input.files[0]) {
									var reader = new FileReader();

									reader.onload = function(e) {
									  $('#' + $(input).attr('name')).attr('src', e.target.result);
									  $('#' + $(input).attr('name')).attr('rel', $(input).val());
									}

									reader.readAsDataURL(input.files[0]); // convert to base64 string
								  }
								}
								
								function saveURL(input) {
								  current_value = $(input).val();
								  //console.log(current_value);
								}
								

								$(".files-data").on( "change", function() {
								  //$(this).val($('#' + $(this).attr('name')).attr('rel')); // Paso como parametro el rel de la imagen
								  console.log($(this).val());
									
								  $('.upload-form .btn-upload').attr('id', $(this).attr('name'));
									
								  readURL(this);
									
								  var files_data = $('.upload-form .files-data'); // The <input type="file" /> field
								  var real_index = 0;
                                    
                                    // Loop through each data and create an array file[] containing our files data.
                                    $.each($(files_data), function(i, obj) {
                                        $.each(obj.files,function(j,file){
                                        if(obj.files.length == 1) {
											real_index++;
										}
                                        })
                                    });
									
									//console.log('CAMBIANDO - ' + 	real_index );
									if (real_index > 2) {
										$(".files-data-extra").removeClass('invisible');
									}
									
								});
								
								/* Extra */
								$(".files-data-extra").change(function() {
								  readURL(this);
								});
								
								$(".files-data").on('click', function(){
									//console.log("Saving value " + $(this).val());
									$(this).data('val', $(this).val());
									
									console.log("Valor OLD " + $(this).data('val'));
									console.log("Actual Valor " + $(this).val());
								});
								
								
								
                            });                     
                            </script>
                            
                            
                        </article>
                    </article>
                <?php endwhile; ?>
            </section>
        </section>
    </section>
    
<?php get_footer(); ?>
