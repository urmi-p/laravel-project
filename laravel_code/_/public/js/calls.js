(function ($) {
    "use strict";

    // Start Video Call
    $(document).on('click', '.startVideoCall', function (e) {

        e.preventDefault();

        $('#callingToFan').html(callingToFan);
        $('#callingStatus').html(please_wait_answer);
        
        $('#videoCallModal').modal({
            backdrop: 'static',
            keyboard: false,
                show: true
    	});

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: URL_BASE + '/create/video-call',
            data: { 'user': user_id_chat },
            dataType: 'json'
        }).done(function (data) {
            if (data.status) {
                $('#cancelCall').attr('data-id', data.buyer);
                $('#cancelCall').attr('data-videocall', data.videoCallId);
            } else {
                 $('#videoCallModal').modal('hide');
                 $('.popout').addClass('popout-error error-video-call').html(error_occurred).fadeIn('500');
            }

        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            $('#videoCallModal').modal('hide');
            
            const errorMessage = error_occurred + ' - ' + jqXHR.responseJSON?.message || error_occurred;

            $('.popout').addClass('popout-error error-video-call').html(errorMessage).fadeIn('500');
        });
    });

    $(document).on('click', '#cancelCall', function (e) {
        e.preventDefault();

        const videoCallId = $(this).attr('data-videocall');
        const buyerId = $(this).attr('data-id');
        const element = $(this);

        element.attr({ 'disabled': 'true' });
        $('#videoCallModal').modal('hide');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: URL_BASE + '/cancel/video-call/' + videoCallId,
            data: { 'user': buyerId },
            dataType: 'json'
        }).done(function () {
            element.removeAttr('disabled');

        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            element.removeAttr('disabled');

            const errorMessage = error_occurred + ' - ' + jqXHR.responseJSON?.message || error_occurred;

            $('.popout').addClass('popout-error error-video-call').html(errorMessage).fadeIn('500');
        });
    });

    // Start Audio Call
    $(document).on('click', '.startAudioCall', function (e) {

        e.preventDefault();

        $('#callingAudioToFan').html(callingToFan);
        $('#callingAudioStatus').html(please_wait_answer);
        
        $('#audioCallModal').modal({
            backdrop: 'static',
            keyboard: false,
                show: true
    	});

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: URL_BASE + '/create/audio-call',
            data: { 'user': user_id_chat },
            dataType: 'json'
        }).done(function (data) {
            if (data.status) {
                $('#cancelAudioCall').attr('data-id', data.buyer);
                $('#cancelAudioCall').attr('data-audiocall', data.audioCallId);
            } else {
                 $('#audioCallModal').modal('hide');
                 $('.popout').addClass('popout-error error-video-call').html(error_occurred).fadeIn('500');
            }

        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            $('#audioCallModal').modal('hide');
            
            const errorMessage = error_occurred + ' - ' + jqXHR.responseJSON?.message || error_occurred;

            $('.popout').addClass('popout-error error-video-call').html(errorMessage).fadeIn('500');
        });
    });

    $(document).on('click', '#cancelAudioCall', function (e) {
        e.preventDefault();

        const audioCallId = $(this).attr('data-audiocall');
        const buyerId = $(this).attr('data-id');
        const element = $(this);

        element.attr({ 'disabled': 'true' });
        $('#audioCallModal').modal('hide');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: URL_BASE + '/cancel/audio-call/' + audioCallId,
            data: { 'user': buyerId },
            dataType: 'json'
        }).done(function () {
            element.removeAttr('disabled');

        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            element.removeAttr('disabled');

            const errorMessage = error_occurred + ' - ' + jqXHR.responseJSON?.message || error_occurred;

            $('.popout').addClass('popout-error error-video-call').html(errorMessage).fadeIn('500');
        });
    });

})(jQuery);