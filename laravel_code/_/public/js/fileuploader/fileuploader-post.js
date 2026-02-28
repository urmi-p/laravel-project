/**

 * custom.js - fileuploader

 * Copyright (c) 2021 Innostudio.de

 * Website: https://innostudio.de/fileuploader/

 * Version: 2.2 (27 Nov 2020)

 * License: https://innostudio.de/fileuploader/documentation/#license

 */

$(document).ready(function() {

	var currentPath = window.location.pathname.replace(/\/+$/, '');
	var isNewUpdatePage = currentPath === '/new/update';
	var postUploadLimit = isNewUpdatePage ? 1 : maximum_files_post;



	// enable fileuploader plugin

	$('input[name="photo[]"]').fileuploader({

		fileMaxSize: maxSizeInMb,

    limit: postUploadLimit,

    extensions: extensionsPostMessage,



    captions: lang,



    dialogs: {

    // alert dialog

    alert: function(text) {

        return swal({

         title: error_oops,

         text: text,

         type: "error",

         confirmButtonText: ok

         });

    },



    // confirm dialog

    confirm: function(text, callback) {

        confirm(text) ? callback() : null;

    }

},



		changeInput: '<div class="fileuploader-input">' +
			'<div class="fileuploader-input-inner">' +
			'<div class="fileuploader-icon-main"></div>' +
			'<h3 class="fileuploader-input-caption"><span>Drag and drop an image or click<br>to upload</span></h3>' +
			'<button type="button" class="fileuploader-input-button"><span>Choose File</span></button>' +
			'</div>' +
			'</div>',

		theme: 'dragdrop',

        enableApi: true,

		addMore: !isNewUpdatePage,

        thumbnails: {
            popup: false
        },



		// while using upload option, please set

		// startImageRenderer: false

		// for a better effect

		upload: {

			url: URL_BASE+'/upload/media',

            data: null,

            type: 'POST',

            enctype: 'multipart/form-data',

            start: true,

            synchron: true,

            chunk: 50,

            beforeSend: function(item, listEl, parentEl, newInputEl, inputEl) {



              $('.btn-blocked').show();
              $(document).trigger('post-media-upload-start');



              if (typeof postId !== 'undefined') {

                item.upload.data.postId = postId;

              }



        // here you can create upload headers

        item.upload.headers = {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        };

        return true;

      },



            onSuccess: function(result, item) {

				var data = {};



				if (result && result.files) {

          data = result;

        } else {

          data.hasWarnings = true;

        }



				// if success

          if (data.isSuccess && data.files.length) {

                    item.name = data.files[0].name;

					item.html.find('.content-holder > h5').text(item.name).attr('title', item.name);

          $(document).trigger('post-media-uploaded', [{
            name: item.name,
            format: data.files[0].format,
            file: item.file || null
          }]);

          }



				// if warnings

				if (data.hasWarnings) {

          var error = '';



					for (var warning in data.warnings) {

            error += '<li><i class="fa fa-times-circle"></i> ' + data.warnings[warning];

					}



          $('#showErrorsUdpate').html(error);

  				$('#errorUdpate').fadeIn(500);



          item.remove();
          $(document).trigger('post-media-upload-failed');



					// item.html.removeClass('upload-successful').addClass('upload-failed');

					return this.onError ? this.onError(item) : null;

				}



        item.html.find('.fileuploader-action-remove').addClass('fileuploader-action-success');



				setTimeout(function() {

					item.html.find('.progress-holder').hide();

					item.renderThumbnail();



                    item.html.find('.fileuploader-action-popup, .fileuploader-item-image').show();

				}, 400);



        $('.btn-blocked').hide();

            },

            onError: function(item) {

				item.html.find('.progress-holder, .fileuploader-action-popup, .fileuploader-item-image').hide();



        $('.btn-blocked').hide();
        $(document).trigger('post-media-upload-failed');



            },

            onProgress: function(data, item) {

                var progressBar = item.html.find('.progress-holder');



                if(progressBar.length > 0) {

                    progressBar.show();

                    progressBar.find('.fileuploader-progressbar .bar').width(data.percentage + "%");

                }

                $(document).trigger('post-media-upload-progress', [{
                  percentage: data && typeof data.percentage !== 'undefined' ? data.percentage : 0
                }]);



                item.html.find('.fileuploader-action-popup, .fileuploader-item-image').hide();

            }

        },

		onRemove: function(item) {

			$.post(URL_BASE+'/delete/media', {

				file: item.name,

        _token: $('meta[name="csrf-token"]').attr('content')

			});

      $(document).trigger('post-media-removed');

		}



  }); // End fileuploader()

});
