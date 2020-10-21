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
                                    <input type = "file" name = "files[]" accept = "image/*" class = "files-data form-control" multiple />
									<input type = "file" name = "files[]" accept = "image/*" class = "files-data form-control" multiple />
                                </div>
                                <div class = "form-group">
                                    <input type = "submit" value = "Subir fotos" class = "btn btn-primary btn-upload" />
                                </div>
                            </div>
							
							<div class="modal"></div>
                                                                                    
                            <script type = "text/javascript">
                            jQuery(document).ready(function( $ ) {
                                // When the Upload button is clicked...
                                $('body').on('click', '.upload-form .btn-upload', function(e){
                                    e.preventDefault;
                                    
                                    console.log('subiendo');

                                    var fd = new FormData();
                                    var files_data = $('.upload-form .files-data'); // The <input type="file" /> field
                                    
                                    // Loop through each data and create an array file[] containing our files data.
                                    $.each($(files_data), function(i, obj) {
                                        $.each(obj.files,function(j,file){
                                            fd.append('files[' + j + ']', file);
                                        })
                                    });
                                    
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
                                });
								
								$body = $("body");
								
								/* Loading */
								$(document).on({
									ajaxStart: function() { $body.addClass("loading"); $('.modal').addClass("loading");    },
									ajaxStop: function() { $body.removeClass("loading"); $('.modal').removeClass("loading"); }
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
