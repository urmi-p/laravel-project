<link href="<?php echo e(asset('css/core.min.css'), false); ?>?v=<?php echo e($settings->version, false); ?>" rel="stylesheet">

<link href="<?php echo e(asset('css/feather.css'), false); ?>" rel="stylesheet">

<link href="<?php echo e(asset('css/bootstrap-icons.css'), false); ?>?v=<?php echo e($settings->version, false); ?>" rel="stylesheet">

<link href="<?php echo e(asset('css/icomoon.css'), false); ?>" rel="stylesheet">



<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->check() && auth()->user()->dark_mode == 'on'): ?>

<link href="<?php echo e(asset('css/bootstrap-dark.min.css'), false); ?>" rel="stylesheet">

<?php else: ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('settings.theme') == 'light' || auth()->check() && auth()->user()->dark_mode == 'off'): ?>

  <link href="<?php echo e(asset('css/bootstrap.min.css'), false); ?>" rel="stylesheet">

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>



<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>

<?php if(config('settings.theme') == 'dark'): ?>

  <link href="<?php echo e(asset('css/bootstrap-dark.min.css'), false); ?>" rel="stylesheet">

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>



<link href="{{ asset('css/styles.css') }}?v={{ $settings->version }}&{{ filemtime(public_path('css/styles.css')) }}" rel="stylesheet">

<link href="<?php echo e(asset('js/plyr/plyr.css'), false); ?>?v=<?php echo e($settings->version, false); ?>" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="<?php echo e(asset('js/reels/reels.css'), false); ?>?v=<?php echo e($settings->version, false); ?>">



<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>

<link href="<?php echo e(asset('js/fileuploader/font/font-fileuploader.css'), false); ?>" media="all" rel="stylesheet" type="text/css" />

<link href="<?php echo e(asset('js/fileuploader/jquery.fileuploader.min.css'), false); ?>" media="all" rel="stylesheet" type="text/css" />

<link href="<?php echo e(asset('js/fileuploader/jquery.fileuploader-theme-thumbnails.css'), false); ?>" media="all" rel="stylesheet" type="text/css" />

<link href="<?php echo e(asset('js/fileuploader/jquery.fileuploader-theme-dragdrop.css'), false); ?>" media="all" rel="stylesheet" type="text/css" />

<link href="<?php echo e(asset('js/jquery-ui/jquery-ui.min.css'), false); ?>" media="all" rel="stylesheet" type="text/css" />



<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->path() == '/' && $settings->story_status): ?>

<link href="<?php echo e(asset('js/story/zuck.min.css'), false); ?>?v=<?php echo e($settings->version, false); ?>" rel="stylesheet" type="text/css" />

<link href="<?php echo e(asset('js/story/snapssenger.css'), false); ?>?v=<?php echo e($settings->version, false); ?>" rel="stylesheet" type="text/css" />

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>



<?php if(request()->path() == '/' && $settings->story_status && $fonts || request()->is('create/story/text') && $settings->story_status && $fonts): ?>

<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=<?php echo e($fonts, false); ?>">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">


<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>



<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($settings->websockets): ?>

<script src="https://js.pusher.com/8.3.0/pusher.min.js"></script>

<script>

  window.pusher =

  {

    "cluster": "<?php echo e(config('broadcasting.connections.pusher.options.cluster'), false); ?>",

    "key": "<?php echo e(config('broadcasting.connections.pusher.key'), false); ?>"

};

</script>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>



<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($settings->push_notification_status): ?>

<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>

<script>

  const myDeviceKeysId = <?php echo json_encode(auth()->user()->oneSignalDevices->pluck('player_id')->all()); ?>;

  const pushPromptPrimaryColor = "<?php echo e($settings->color_default ?: '#e2394c', false); ?>";
  const pushPromptPrimaryHoverColor = "#d52d41";
  const pushPromptAcceptText = "<?php echo e(__('general.activate'), false); ?>";

  function styleOneSignalPushPrompt() {
    document.querySelectorAll('[class*="onesignal-slidedown"]').forEach(function (container) {
      container.querySelectorAll('button').forEach(function (button) {
        if (button.dataset.closeOnlyStyled === 'true') {
          return;
        }

        var buttonText = (button.textContent || '').trim();

        if (buttonText === pushPromptAcceptText) {
          button.style.backgroundColor = pushPromptPrimaryColor;
          button.style.borderColor = pushPromptPrimaryColor;
          button.style.color = '#fff';
          button.dataset.closeOnlyStyled = 'true';

          button.addEventListener('mouseenter', function () {
            button.style.backgroundColor = pushPromptPrimaryHoverColor;
            button.style.borderColor = pushPromptPrimaryHoverColor;
          });

          button.addEventListener('mouseleave', function () {
            button.style.backgroundColor = pushPromptPrimaryColor;
            button.style.borderColor = pushPromptPrimaryColor;
          });
        }
      });
    });
  }

  var oneSignalPromptObserver = new MutationObserver(function () {
    styleOneSignalPushPrompt();
  });

  oneSignalPromptObserver.observe(document.documentElement, {
    childList: true,
    subtree: true
  });

  styleOneSignalPushPrompt();



  var OneSignal = window.OneSignal || [];

    var initConfig = {

      appId: "<?php echo e($settings->onesignal_appid, false); ?>",

      autoResubscribe: true,

      safari_web_id: "web.onesignal.auto.0c986762-0fae-40b1-a5f6-ee95f7275a97",

      notifyButton: {

        enable: false,

      },

      welcomeNotification: {

        message: "<?php echo e(__('general.notifications_activated_successfully'), false); ?>"

      },

      persistNotification: true,



      promptOptions: {

      slidedown: {

        prompts: [

          {

            type: "push", // current types are "push" & "category"

            autoPrompt: true,

            text: {

              /* limited to 90 characters */

              actionMessage: "<?php echo e(__('general.push_notification_title', ['app' => $settings->title]), false); ?>",

              /* acceptButton limited to 15 characters */

              acceptButton: "<?php echo e(__('general.activate'), false); ?>",

              /* cancelButton limited to 15 characters */

              cancelButton: "<?php echo e(__('general.maybe_later'), false); ?>"

            },

            delay: {

              pageViews: 1,

              timeDelay: 20

            }

          }

        ]

      }

    }

    // END promptOptions,

    };

 



  OneSignal.push(function () {

        OneSignal.SERVICE_WORKER_PARAM = { scope: '/' };

        OneSignal.SERVICE_WORKER_PATH = '/OneSignalSDKWorker.js'

        OneSignal.SERVICE_WORKER_UPDATER_PATH = '/OneSignalSDKUpdaterWorker.js'

        OneSignal.init(initConfig);



        OneSignal.showSlidedownPrompt();

    });



  OneSignal.push(function() {



    // Get User Id

    OneSignal.getUserId(function(userId) {

      pushUserId = userId;



      if (pushUserId !== null) {

        var isRegisterDevice = $.inArray(pushUserId, myDeviceKeysId);

        if (isRegisterDevice === -1) {

          $.post("<?php echo e(url('api/device/register'), false); ?>", {player_id: pushUserId, user_id: <?php echo e(auth()->id(), false); ?> });

        }

      }

    });



    OneSignal.isPushNotificationsEnabled(function(isEnabled) {

    if (isEnabled)

      console.log("Push notifications are enabled!");

    else

      console.log("Push notifications are not enabled yet.");

  });

    

  // Subscription Change

	OneSignal.on("subscriptionChange", 

  function(isSubscribed) {



    OneSignal.push(function() {

        OneSignal.getUserId(function(userId) {

          pushUserId = userId;



        if (isSubscribed == false) {

        $.get("<?php echo e(url('api/device/delete'), false); ?>", {player_id: pushUserId});

      } else {

            $.post("<?php echo e(url('api/device/register'), false); ?>", {player_id: pushUserId, user_id: <?php echo e(auth()->id(), false); ?> });

          }});



        });

      });

});

</script>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>



<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
@php
    $pageTitle = trim($__env->yieldContent('title') . ' ' . $settings->title . ' - ' . __('seo.slogan'));
@endphp


<script type="text/javascript">

// Global variables

  var URL_BASE = "<?php echo e(url('/'), false); ?>";

  var urlPreviousPage = "<?php echo e(!Helper::isPreviousUrlReelsSection() ? url()->previous() : url('/'), false); ?>";

  var lang = '{{ auth()->user()->language ?? session('locale') }}';
  var _title = @json($pageTitle);

  var session_status = "<?php echo e(auth()->check() ? 'on' : 'off', false); ?>";

  var ReadMore = "<?php echo e(__('general.view_all'), false); ?>";

  var copiedSuccess = "<?php echo e(__('general.copied_success'), false); ?>";

  var copied = "<?php echo e(__('general.copied'), false); ?>";

  var copy_link = "<?php echo e(__('general.copy_link'), false); ?>";

  var loading = "<?php echo e(__('general.loading'), false); ?>";

  var please_wait = "<?php echo e(__('general.please_wait'), false); ?>";

  var error_occurred = "<?php echo e(__('general.error'), false); ?>";

  var error_oops = "<?php echo e(__('general.error_oops'), false); ?>";

  var error_reload_page = "<?php echo e(__('general.error_reload_page'), false); ?>";

  var ok = "<?php echo e(__('users.ok'), false); ?>";

  var user_count_carousel = <?php if(auth()->guest() && request()->path() == '/'): ?> <?php echo e($users->count(), false); ?><?php else: ?> 0 <?php endif; ?>;

  var no_results_found = "<?php echo e(__('general.no_results_found'), false); ?>";

  var no_results = "<?php echo e(__('general.no_results'), false); ?>";

  var no_one_seen_story_yet =  "<?php echo e(__('general.no_one_seen_story_yet'), false); ?>";

  var is_profile = <?php echo e((request()->routeIs('profile') || request()->is('profile/*')) ? 'true' : 'false', false); ?>;

  var error_scrollelement = false;

  var captcha = <?php echo e($settings->captcha == 'on' ? 'true' : 'false', false); ?>;

  var alert_adult = <?php echo e($settings->alert_adult == 'on' ? 'true' : 'false', false); ?>;

  var error_internet_disconnected = "<?php echo e(__('general.error_internet_disconnected'), false); ?>";

  var announcement_cookie = "<?php echo e($settings->announcement_cookie, false); ?>";

  var resend_code = "<?php echo e(__('general.resend_code'), false); ?>";

  var resending_code = "<?php echo e(__('general.resending_code'), false); ?>";

  var query = "<?php echo e(strlen(request()->get('q')) > 2 ? trim(str_replace('#', '%23', request()->get('q'))) : false, false); ?>";

  var sortBy = "<?php echo e(in_array(request()->get('sort'), ['oldest', 'unlockable', 'free']) ? trim(request()->get('sort')) : false, false); ?>";

  var login_continue = "<?php echo e(__('general.login_continue'), false); ?>";

  var register = "<?php echo e(__('auth.sign_up'), false); ?>";

  var login_with = "<?php echo e(__('auth.login_with'), false); ?>";

  var sign_up_with = "<?php echo e(__('auth.sign_up_with'), false); ?>";

  var currentPage = "<?php echo url()->full(); ?>";

  var requestGender = <?php echo e(request()->get('gender') ? 'true' : 'false', false); ?>;

  var is_bookmarks = <?php echo e(request()->is('my/bookmarks') ? 'true' : 'false', false); ?>;

  var is_likes = <?php echo e(request()->is('my/likes') ? 'true' : 'false', false); ?>;

  var is_purchases = <?php echo e(request()->is('my/purchases') ? 'true' : 'false', false); ?>;

  var is_explore = <?php echo e(request()->is(['explore']) ? 'true' : 'false', false); ?>;

  var sessionActive = <?php echo e(auth()->check() ? 'true' : 'false', false); ?>;

  var isReelPage = <?php echo e(request()->routeIs('reels.section.*') ? 'true' : 'false', false); ?>;



<?php if(auth()->guard()->check()): ?>

  var isMessageChat = <?php echo e(request()->is('messages/*') ? 'true' : 'false', false); ?>;

  var delete_confirm = "<?php echo e(__('general.delete_confirm'), false); ?>";

  var confirm_delete_comment = "<?php echo e(__('general.confirm_delete_comment'), false); ?>";

  var confirm_delete_update = "<?php echo e(__('general.confirm_delete_update'), false); ?>";

  var yes_confirm = "<?php echo e(__('general.yes_confirm'), false); ?>";

  var cancel_confirm = "<?php echo e(__('general.cancel_confirm'), false); ?>";

  var formats_available = "<?php echo e(__('general.formats_available'), false); ?>";

  var formats_available_images = "<?php echo e(__('general.formats_available_images'), false); ?>";

  var formats_available_verification = "<?php echo e(__('general.formats_available_verification_form_w9', ['formats' => 'JPG, PNG, GIF']), false); ?>";

  var file_size_allowed = <?php echo e($settings->file_size_allowed * 1024, false); ?>;

  var max_size_id = "<?php echo e(__('general.max_size_id').' '.Helper::formatBytes($settings->file_size_allowed * 1024), false); ?>";

  var max_size_id_lang = "<?php echo e(__('general.max_size_id').' '.Helper::formatBytes($settings->file_size_allowed_verify_account * 1024), false); ?>";

  var maxSizeInMb = "<?php echo e(floor($settings->file_size_allowed / 1024), false); ?>";

  var file_size_allowed_verify_account = <?php echo e($settings->file_size_allowed_verify_account * 1024, false); ?>;

  var error_width_min = "<?php echo e(__('general.width_min',['data' => 20]), false); ?>";

  var story_length = <?php echo e($settings->story_length, false); ?>;

  var payment_card_error = "<?php echo e(__('general.payment_card_error'), false); ?>";

  var confirm_delete_message = "<?php echo e(__('general.confirm_delete_message'), false); ?>";

  var confirm_delete_conversation = "<?php echo e(__('general.confirm_delete_conversation'), false); ?>";

  var confirm_cancel_subscription = "<?php echo __('general.confirm_cancel_subscription'); ?>";

  var yes_confirm_cancel = "<?php echo e(__('general.yes_confirm_cancel'), false); ?>";

  var confirm_delete_notifications = "<?php echo e(__('general.confirm_delete_notifications'), false); ?>";

  var confirm_delete_withdrawal = "<?php echo e(__('general.confirm_delete_withdrawal'), false); ?>";

  var change_cover = "<?php echo e(__('general.change_cover'), false); ?>";

  var pin_to_your_profile = "<?php echo e(__('general.pin_to_your_profile'), false); ?>";

  var unpin_from_profile = "<?php echo e(__('general.unpin_from_profile'), false); ?>";

  var post_pinned_success = "<?php echo e(__('general.post_pinned_success'), false); ?>";

  var post_unpinned_success = "<?php echo e(__('general.post_unpinned_success'), false); ?>";

  var stripeKey = "<?php echo e(PaymentGateways::where('id', 2)->where('enabled', '1')->whereSubscription('yes')->first() ? env('STRIPE_KEY') : false, false); ?>";

  var stripeKeyWallet = "<?php echo e(PaymentGateways::where('id', 2)->where('enabled', '1')->first() ? env('STRIPE_KEY') : false, false); ?>";

  var thanks = "<?php echo e(__('general.thanks'), false); ?>";

  var tip_sent_success = "<?php echo e(__('general.tip_sent_success'), false); ?>";

  var error_payment_stripe_3d = "<?php echo e(__('general.error_payment_stripe_3d'), false); ?>";

  var colorStripe = <?php echo auth()->user()->dark_mode == 'on' ? "'#dcdcdc'" : "'#32325d'"; ?>;

  var full_name_user = '<?php echo e(auth()->user()->name, false); ?>';

  var color_default = '<?php echo e($settings->color_default, false); ?>';

  var formats_available_upload_file = "<?php echo e(__('general.formats_available_upload_file'), false); ?>";

  var cancel_subscription = "<?php echo e(__('general.unsubscribe'), false); ?>";

  var your_subscribed = "<?php echo e(__('general.your_subscribed'), false); ?>";

  var subscription_expire = "<?php echo e(__('general.subscription_expire'), false); ?>";

  var formats_available_verification_form_w9 = "<?php echo e(__('general.formats_available_verification_form_w9', ['formats' => 'PDF']), false); ?>";

  var payment_was_successful = "<?php echo e(__('general.payment_was_successful'), false); ?>";

  var public_post = "<?php echo e(__('general.public'), false); ?>";

  var locked_post = "<?php echo e(__('users.content_locked'), false); ?>";

  var maximum_files_post = <?php echo e($settings->maximum_files_post, false); ?>;

  var maximum_files_msg = <?php echo e($settings->maximum_files_msg, false); ?>;

  var great = "<?php echo e(__('general.great'), false); ?>";

  var msg_success_sent_all_subscribers = "<?php echo e(__('general.msg_success_sent_all_subscribers'), false); ?>";

  var video_on_way = "<?php echo e(__('general.video_on_way'), false); ?>";

  var story_on_way = "<?php echo e(__('general.story_on_way'), false); ?>";

  var video_processed_info = "<?php echo e(__('general.video_processed_info'), false); ?>";

  var confirm_end_live = "<?php echo e(__('general.confirm_end_live'), false); ?>";

  var yes_confirm_end_live = "<?php echo e(__('general.yes_confirm_end_live'), false); ?>";

  var liveMode = false;

  var min_width_height_image = <?php echo e($settings->min_width_height_image, false); ?>;

  var min_width_image_error = '<?php echo e(__('general.width_min', ['data' => $settings->min_width_height_image]), false); ?>';

  var decimalZero = <?php echo e(in_array(config('settings.currency_code'), config('currencies.zero-decimal')) ? 0 : 2, false); ?>;

  var confirm_exit_live = "<?php echo e(__('general.confirm_exit_live'), false); ?>";

  var yes_confirm_exit_live = "<?php echo e(__('general.yes_confirm_exit_live'), false); ?>";

  var purchase_processed_shortly = "<?php echo e(__('general.purchase_processed_shortly'), false); ?>";

  var confirm_reject_order = "<?php echo e(__('general.confirm_reject_order'), false); ?>";

  var reject_order = "<?php echo e(__('general.reject_order'), false); ?>";

  var action_cannot_reversed = "<?php echo e(__('general.action_cannot_reversed'), false); ?>";

  var mark_as_delivered = "<?php echo e(__('general.mark_as_delivered'), false); ?>";

  var confirm_restrict = "<?php echo e(__('general.confirm_restrict'), false); ?>";

  var restrict = "<?php echo e(__('general.restrict'), false); ?>";

  var remove_restriction = "<?php echo e(__('general.remove_restriction'), false); ?>";

  var show_only_free = "<?php echo e(__('general.show_only_free'), false); ?>";

  var show_all = "<?php echo e(__('general.show_all'), false); ?>";

  var audioStatus = <?php if($settings->disable_audio): ?> [] <?php else: ?> ["audio/x-matroska","audio/mpeg"] <?php endif; ?>;

  <?php if($settings->video_encoding == 'off'): ?>

  var extensionsPostMessage = ['png','jpeg','jpg','gif','ief','video/mp4',...audioStatus];

  var extensionsStories = ['png','jpeg','jpg','gif','ief','video/mp4'];

  var extensionsReels = ['video/mp4','video/quicktime','video/3gpp','video/mpeg','video/x-matroska','video/x-ms-wmv','video/vnd.avi','video/avi','video/x-flv'];

  <?php else: ?>

  var extensionsPostMessage = ['png','jpeg','jpg','gif','ief','video/mp4','video/quicktime','video/3gpp','video/mpeg','video/x-matroska','video/x-ms-wmv','video/vnd.avi','video/avi','video/x-flv',...audioStatus];

  var extensionsStories = ['png','jpeg','jpg','gif','ief','video/mp4','video/quicktime','video/3gpp','video/mpeg','video/x-matroska','video/x-ms-wmv','video/vnd.avi','video/avi','video/x-flv'];

  var extensionsReels = ['video/mp4','video/quicktime','video/3gpp','video/mpeg','video/x-matroska','video/x-ms-wmv','video/vnd.avi','video/avi','video/x-flv'];

  <?php endif; ?>

  var errorStoryMaxVideosLength = "<?php echo e(__('general.error_story_max_videos_length', ['length' => $settings->story_max_videos_length]), false); ?>";

  var errorReelMaxVideosLength = "<?php echo e(__('general.error_story_max_videos_length', ['length' => 60]), false); ?>";

  var storyMaxVideosLength = <?php echo e($settings->story_max_videos_length, false); ?>;

  var confirm_delete_image_cover = "<?php echo e(__('general.confirm_delete_image_cover'), false); ?>";

  var at = "<?php echo e(__('general.at'), false); ?>";

  var publish = "<?php echo e(__('general.publish'), false); ?>";

  var schedule = "<?php echo e(__('general.schedule'), false); ?>";

  var reject_request = "<?php echo e(__('general.reject_request'), false); ?>";

  var advertising = <?php echo e($advertising->count() ? 'true' : 'false', false); ?>;

  var invalid_format_epub = "<?php echo e(__('general.invalid_format', ['formats' => 'EPUB']), false); ?>";

  var gift_sent_success = "<?php echo e(__('general.gift_sent_success'), false); ?>";



  var choose_file_to_upload = "<?php echo e(__('general.choose_file_to_upload'), false); ?>";

  var browse_file = "<?php echo e(__('general.browse_file'), false); ?>";

  var one_file_chosen = "<?php echo e(__('general.one_file_chosen'), false); ?>";

  var more_files_chosen = "<?php echo e(__('general.more_files_chosen'), false); ?>";



  var confirmDelete = "<?php echo e(__('general.confirm'), false); ?>";

  var cancelUpload = "<?php echo e(__('admin.cancel'), false); ?>";

  var nameFile = "<?php echo e(__('auth.name'), false); ?>";

  var typeFile = "<?php echo e(__('general.type'), false); ?>";

  var sizeFile = "<?php echo e(__('general.size'), false); ?>";

  var dimensionsFile = "<?php echo e(__('general.dimensions'), false); ?>";

  var durationFile = "<?php echo e(__('general.duration'), false); ?>";

  var cropFile = "<?php echo e(__('general.crop'), false); ?>";

  var rotateFile = "<?php echo e(__('general.rotate'), false); ?>";

  var sortFiles = "<?php echo e(__('general.sort'), false); ?>";

  var downloadFile = "<?php echo e(__('general.download'), false); ?>";

  var removeFile = "<?php echo e(__('general.delete'), false); ?>";

  var dropFiles = "<?php echo e(__('general.drop_files'), false); ?>";

  var pasteFiles = "<?php echo e(__('general.paste_files'), false); ?>";

  var removeConfirmation = "<?php echo e(__('general.removeConfirmation'), false); ?>";

  var filesLimit = "<?php echo e(__('general.filesLimit'), false); ?>";

  var iFile = "<?php echo e(__('general.file'), false); ?>";

  var iFiles = "<?php echo e(__('general.files'), false); ?>";

  var filesType = "<?php echo e(__('general.filesType'), false); ?>";

  var fileSize = "<?php echo e(__('general.fileSize'), false); ?>";

  var filesSizeAll = "<?php echo e(__('general.filesSizeAll'), false); ?>";

  var fileName = "<?php echo e(__('general.fileName'), false); ?>";

  var remoteFile = "<?php echo e(__('general.remoteFile'), false); ?>";

  var folderUpload = "<?php echo e(__('general.folderUpload'), false); ?>";

  var rejectedVideoCall = "<?php echo e(__('general.rejected_video_call'), false); ?>";

  var rejectedVideoCallInfo = "<?php echo e(__('general.rejected_video_call_info'), false); ?>";

  var unansweredVideoCall = "<?php echo e(__('general.video_call_unanswered'), false); ?>";

  var unansweredVideoCallInfo = "<?php echo e(__('general.video_call_unanswered_info'), false); ?>";

  var please_wait_answer = "<?php echo e(__('general.please_wait_answer'), false); ?>";

  var rejectedAudioCall = "<?php echo e(__('general.rejected_audio_call'), false); ?>";

  var rejectedAudioCallInfo = "<?php echo e(__('general.rejected_audio_call_info'), false); ?>";

  var unansweredAudioCall = "<?php echo e(__('general.audio_call_unanswered'), false); ?>";

  var unansweredAudioCallInfo = "<?php echo e(__('general.audio_call_unanswered_info'), false); ?>";

  var userAuthId = "<?php echo e(auth()->id(), false); ?>";

  var errorProportionVideoReel = "<?php echo e(__('general.error_proportion_video_reel'), false); ?>";

  var add = "<?php echo e(__('general.add'), false); ?>";

  var maximumFilesMsgError = "<?php echo e(__('general.files_can_be_selected_msg'), false); ?>";

  var information = "<?php echo e(__('general.information'), false); ?>";

  var followingFilesAlreadyAdded = "<?php echo e(__('general.following_files_already_added'), false); ?>";

  var newFilesHaveBeenAddedFollowingAlreadyExisted = "<?php echo e(__('general.new_files_have_been_added_following_already_existed'), false); ?>";

<?php endif; ?>

</script>



<style type="text/css">



button.btn-success {

  background-color: <?php echo e($settings->color_default, false); ?> !important;

  border-color: <?php echo e($settings->color_default, false); ?> !important;

}



.alert-custom {

  background-color: <?php echo e($settings->color_default, false); ?> !important;

  border-color: <?php echo e($settings->color_default, false); ?> !important;

}



.grid-img {

            width: 100%;

            height: 200px;

            object-fit: cover;

            background-color: <?php if(auth()->check() && auth()->user()->dark_mode == 'on'): ?> #414141 <?php else: ?> #fff <?php endif; ?>;

        }

        

        .grid-col {

            padding: 1px;

        }

        

        @media (min-width: 1200px) {

            .col-custom {

                flex: 0 0 20%;

                max-width: 20%;

            }

        }

        

        @media (min-width: 768px) and (max-width: 1199px) {

            .col-custom {

                flex: 0 0 25%;

                max-width: 25%;

            }

        }

        

        @media (min-width: 576px) and (max-width: 767px) {

            .col-custom {

                flex: 0 0 33.333333%;

                max-width: 33.333333%;

            }

        }

        

        @media (max-width: 575px) {

            .col-custom {

                flex: 0 0 50%;

                max-width: 50%;

            }

        }



.media-card {

position: relative;

border-radius: 12px;

overflow: hidden;

height: 280px;

background-size: cover;

background-position: center;

cursor: pointer;

transition: transform 0.2s ease;

}



.media-card:hover {

transform: translateY(-2px);

}



.media-date {

position: absolute;

top: 12px;

left: 12px;

background: rgba(0, 0, 0, 0.7);

color: white;

padding: 4px 8px;

border-radius: 6px;

font-size: 12px;

font-weight: 500;

}



.media-icon {

position: absolute;

bottom: 12px;

right: 12px;

width: 32px;

height: 32px;

background: rgba(0, 0, 0, 0.7);

border-radius: 50%;

display: flex;

align-items: center;

justify-content: center;

color: white;

}



.media-info {

position: absolute;

bottom: 0;

left: 0;

right: 0;

background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));

color: white;

padding: 40px 12px 12px;

}



.media-title {

font-size: 14px;

font-weight: 500;

margin-bottom: 4px;

}



.media-tags {

font-size: 12px;

opacity: 0.9;

display: flex;

align-items: center;

}

.media-icon-check {

    position: absolute;

    top: 0;

    right: 0;

    width: 50px;

    height: 50px;

    display: flex;

    align-items: center;

    justify-content: center;

    color: white;

    z-index: 1;

    font-size: 24px;

}



.fixed-top {z-index: 1000 !important;}

.sticky-top {z-index: 999 !important;}



<?php if(auth()->check() && auth()->user()->dark_mode == 'on'): ?>

.context-noty {color: #FFF !important;}

<?php else: ?>

.context-noty {color: <?php echo e($settings->color_default, false); ?> !important;}

<?php endif; ?>



<?php if($settings->custom_css): ?>

  <?php echo $settings->custom_css; ?>


<?php endif; ?>



<?php if(auth()->check() && auth()->user()->dark_mode == 'on' || auth()->guest() && config('settings.theme') == 'dark'): ?>

  body,

  .font-montserrat a,

  .font-montserrat a:hover { color: #FFF; }

  body a:not(.link-footer, .ico-social, .pulse-btn, .text-muted, .bg_white, .font_mobile_normal),

  body a:hover:not(.pulse-btn, .text-muted),

  .btn-link,

  .spinner-border.text-primary,

  .card-title.text-primary { color: #FFF }

  .text-primary { color: #FFF }

  .search-bar, .search-bar:focus {border: none !important; background-color: #474747 !important;}

  .text-notify {color: #E2E8F0;}

  .dd-menu-user:before { color: #222222; }

  .avatar-wrap, .card-avatar {background-color: #222;}

  .dropdown-item.balance:hover {background: #222 !important;color: #ffffff;}

  .blocked {background-color: transparent;}

  .btn-google, .btn-google:hover, .btn-google:active, .btn-google:focus {
  background: #FFFFF !important;
  border-color: #FFFFFF33 !important;;
  color: #344054 !important;
  border-radius: 8px;
}

.line-replies { border-color: #fff !important;}

.wrapper-media-music {border-color: #222 !important;}

.dropdown-divider {

  border-top: 1px solid #2e2e2e;

}

.border {border-color: #222 !important;}

.custom-switch-pro .custom-control-input:focus ~ .custom-control-label::before {

  border-color: #adb5bd !important;

  box-shadow: 0 1px 3px rgb(50 50 93 / 15%), 0 1px 0 rgb(0 0 0 / 2%);

}



.img-user,

.avatar-modal,

.img-user-small { border-color: #303030; }

.actionDeleteNotify,

.actionDeleteNotify:hover { color: #FFF; }



.nav-profile a, .nav-profile li.active a:hover, .nav-profile li.active a:active, .nav-profile li.active a:focus,

.sm-btn-size, .verified {

  color: #fff;

}

.input-group-text {

  border-color: #222;

  background-color: #161616;

}



.input-group .input-group-text {
  border-color: #222;
  background-color: #080808;
} 



.datepicker.dropdown-menu {background-color: #303030 !important;}

.datepicker-dropdown.datepicker-orient-bottom:after {border-top: 6px solid #303030 !important;}

.datepicker-dropdown:after {border-bottom: 6px solid #303030 !important;}



.form-control:focus,

.custom-select:focus {

  border-color: #222 !important;



}

.custom-select {

  background: #161616 url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23a5a5a5' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e")

  no-repeat right .75rem center/8px 10px;

  color: #fff;

}

.navbar-toggler,

.sweet-alert h2,

.sweet-alert p,

.ico-no-result {

  color: #FFF;

}

.btn-notify,

.btn-notify:hover,

.btn-notify:focus,

.btn-notify:active {

  color: #FFF;

}

.sweet-alert { background-color: #2f2f2f;}

.content-locked {background: #444444;}



@media (max-width: 991px) {

.navbar .navbar-collapse {

  background: #222;

}

.navbar .navbar-collapse .navbar-nav .nav-item .nav-link:not(.btn) {

  color: #ffffff;

}



.navbar-collapse .navbar-toggler span {

  background: #fff;

}

}

.link-scroll a.nav-link:not(.btn) {

  color: white;
  /* color: #969696; */

}

.btn-upload:hover,

.btn-post:hover,

.icons-live:hover {

background-color: #222222 !important;

}

.btn-active-hover {

background-color: #222222 !important;

}

.unread-chat {

  background-color: #444 !important;

}

.modal-danger .modal-content,

.wrapper-msg-inbox {

background-color: #303030;

}

h3, .h3 {font-size: 1.75rem;}

h2, .h2 {font-size: 2rem;}

h4, .h4 {font-size: 1.5rem;}

h5, .h5 {font-size: 1.25rem;}



@keyframes animate {

from {transition:none;}

to {background-color:#383838;transition: all 0.3s ease-out;}

}



.item-loading::before {

  background-color: #6b6b6b;

  content: ' ';

  display: block;

  height: 100%;

  position: absolute;

  top: 0;

  left: 0;

  width: 100%;

  animation-name: animate;

  animation-duration: 2s;

  animation-iteration-count: infinite;

  animation-timing-function: linear;

  background-image: none;

  border-radius: 0;

}

.loading-avatar::before {

border-radius: 50%;

}

.loading-avatar {background-color: inherit;}

.plyr--audio .plyr__controls {background: #212121; color: #ffffff;}

.readmore-js-collapsed:after {background-image: linear-gradient(hsla(0,0%,100%,0),#303030 95%);}

.sweet-alert .sa-icon.sa-success .sa-fix {background-color: #2f2f2f;}

.sweet-alert .sa-icon.sa-success::after, .sweet-alert .sa-icon.sa-success::before {background: #2f2f2f;}

.page-item.disabled .page-link, .page-link {background-color: #222222;}

.nav-pills .nav-link {background-color: #303030; color: #ffffff;}

a.social-share i {color: #dedede!important;}



.StripeElement {background-color: #222222; border: 1px solid #222222;}

.StripeElement--focus {border-color: #525252;}

.bg-autocomplete {background-color: #222;}



<?php endif; ?>



.bg-gradient {

  background: url('<?php echo e(url('img', $settings->bg_gradient), false); ?>');

  background-size: cover;

}



a.social-share i {color: #797979; font-size: 32px;}

a:hover.social-share { text-decoration: none; }

.btn-whatsapp {color: #50b154 !important;}

.close-inherit {color: inherit !important;}

.btn-twitter { background-color: #000;  color:#fff !important;  border: 1px solid #FFFFFF33; border-radius: 8px}



@media (max-width: 991px) {

  .navbar-user-mobile {

    font-size: 20px;

  }

}

.or {

display:flex;

justify-content:center;

align-items: center;

color:grey;

}



.or:after,

.or:before {

  content: "";

  display: block;

  background: #adb5bd;

  width: 50%;

  height:1px;

  margin: 0 10px;

}



.icon-navbar { font-size: 22px; vertical-align: bottom; <?php if(auth()->check() && auth()->user()->dark_mode == 'on'): ?> color: #FFF !important; <?php endif; ?> }







<?php if(auth()->check() && auth()->user()->dark_mode == 'off' || auth()->guest()): ?>

.navbar_background_color { background-color: <?php echo e($settings->navbar_background_color, false); ?> !important; }

.link-scroll a.nav-link:not(.btn), .navbar-toggler:not(.text-white) { color: <?php echo e($settings->navbar_text_color, false); ?> !important; }



@media (max-width: 991px) {

  .navbar .navbar-collapse, .dd-menu-user, .dropdown-item.balance:hover

  { background-color: <?php echo e($settings->navbar_background_color, false); ?> !important; color: <?php echo e($settings->navbar_text_color, false); ?> !important; }

  .navbar-collapse .navbar-toggler span { background-color: <?php echo e($settings->navbar_text_color, false); ?> !important; }

  .dropdown-divider { border-top-color: <?php echo e($settings->navbar_background_color, false); ?> !important;}

  }



.footer_background_color { background-color: <?php echo e($settings->footer_background_color, false); ?> !important; }

.footer_text_color, .link-footer:not(.footer-tiny) { color: <?php echo e($settings->footer_text_color, false); ?>; }

<?php endif; ?>



<?php if($settings->color_default <> ''): ?>

:root {

  --plyr-color-main: <?php echo e($settings->color_default, false); ?>;

  --swiper-theme-color: <?php echo e($settings->color_default, false); ?>;

  --color-media-wrapper: <?php if(auth()->check() && auth()->user()->dark_mode == 'off'): ?> #f1f1f1 <?php else: ?> #454545 <?php endif; ?>;

  --color-pulse-media-wrapper: <?php if(auth()->check() && auth()->user()->dark_mode == 'off'): ?> #f8f8f8 <?php else: ?> #373737 <?php endif; ?>;

}



.plyr--video.plyr--stopped .plyr__controls {display: none;}



@media (min-width: 767px) {

  .login-btn { padding-top: 12px !important;}

}



::selection{ background-color: <?php echo e($settings->color_default, false); ?>; color: white; }

::moz-selection{ background-color: <?php echo e($settings->color_default, false); ?>; color: white; }

::webkit-selection{ background-color: <?php echo e($settings->color_default, false); ?>; color: white; }



body a,

a:hover,

a:focus,

a.page-link,

.btn-outline-primary,

.btn-link {

    color: <?php echo e($settings->color_default, false); ?>;

}

.text-primary {

    color: <?php echo e($settings->color_default, false); ?>!important;

}



a.text-primary.btnBookmark:hover, a.text-primary.btnBookmark:focus {

  color: <?php echo e($settings->color_default, false); ?>!important;

}

.dropdown-menu {

  font-size: 16px !important;

  line-height: normal !important;

  padding: .5rem !important;

}

.dropdown-item {

  border-radius: 5px;

}

.btn-primary:not(:disabled):not(.disabled).active,

.btn-primary:not(:disabled):not(.disabled):active,

.show>.btn-primary.dropdown-toggle,

.btn-primary:hover,

.btn-primary:focus,

.btn-primary:active,

.btn-primary,

.btn-primary.disabled,

.btn-primary:disabled,

.custom-checkbox .custom-control-input:checked ~ .custom-control-label::before,

.page-item.active .page-link,

.page-link:hover,

.owl-theme .owl-dots .owl-dot span,

.owl-theme .owl-dots .owl-dot.active span,

.owl-theme .owl-dots .owl-dot:hover span

 {

    background-color: <?php echo e($settings->color_default, false); ?>;

    border-color: <?php echo e($settings->color_default, false); ?>;

}

.bg-primary,

.dropdown-item:focus,

.dropdown-item:hover,

.dropdown-item.active,

.dropdown-item:active,

.tooltip-inner,

.custom-range::-webkit-slider-thumb,

.custom-range::-webkit-slider-thumb:active {

    background-color: <?php echo e($settings->color_default, false); ?>!important;

}



.custom-range::-moz-range-thumb:active,

.custom-range::-ms-thumb:active {

  background-color: <?php echo e($settings->color_default, false); ?>!important;

}



.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before,

.custom-control-input:focus:not(:checked) ~ .custom-control-label::before,

.btn-outline-primary {

  border-color: <?php echo e($settings->color_default, false); ?>;

}

.custom-control-input:not(:disabled):active~.custom-control-label::before,

.custom-control-input:checked~.custom-control-label::before,

.btn-outline-primary:hover,

.btn-outline-primary:focus,

.btn-outline-primary:not(:disabled):not(.disabled):active,

.list-group-item.active,

.btn-outline-primary:not(:disabled):not(.disabled).active,

.badge-primary {

    color: #fff;

    background-color: <?php echo e($settings->color_default, false); ?>;

    border-color: <?php echo e($settings->color_default, false); ?>;

}

.popover .arrow::before { border-top-color: rgba(0,0,0,.35) !important; }

.bs-tooltip-bottom .arrow::before {

  border-bottom-color: <?php echo e($settings->color_default, false); ?>!important;

}

.arrow::before {

  border-top-color: <?php echo e($settings->color_default, false); ?>!important;

}

.nav-profile li.active {

  border-bottom: 3px solid <?php echo e(auth()->check() && auth()->user()->dark_mode == 'on' ? '#fff' : $settings->color_default, false); ?> !important;

}

.button-avatar-upload {left: 0;}

input[type='file'] {overflow: hidden;}

.badge-free { top: 10px; right: 10px; background: rgb(0 0 0 / 65%); color: #fff; font-size: 12px;}


.btn-facebook, .btn-twitter, .btn-google {position: relative; border-radius: 8px; }

.btn-facebook i  {

  position: absolute;

    left: 10px;

    bottom: 14px;

    width: 36px;

}



.btn-twitter i {

    position: absolute;

    left: 10px;

    bottom: 9px !important;

    bottom: 13px;

    width: 36px;

}



.btn-google img  {

  position: absolute;

    left: 18px;

    bottom: 12px;

    width: 18px;

}



.button-search {top: 0;}



@media (min-width: 768px) {

  .pace {display:none !important;}

}



@media (min-width: 992px) {

  .menuMobile {display:none !important;}

}



.pace{-webkit-pointer-events:none;pointer-events:none;-webkit-user-select:none;-moz-user-select:none;user-select:none}

.pace-inactive{display:none}

.pace .pace-progress{background:<?php echo e($settings->color_default, false); ?>;position:fixed;z-index:2000;top:0;right:100%;width:100%;height:3px}



.menuMobile {

  position: fixed;

  bottom: 0;

  left: 0;

  z-index: 1040;

  <?php if(auth()->check() && auth()->user()->dark_mode == 'off'): ?>

    background-color: <?php echo e($settings->navbar_background_color, false); ?> !important;

  <?php endif; ?>



}

.menuMobile .list-inline {

  position: relative;

  align-items: center;

}

.btn-mobile {border-radius: 25px;

  <?php if(auth()->check() && auth()->user()->dark_mode == 'off'): ?>

  color: <?php echo e($settings->navbar_text_color, false); ?> !important;

  <?php endif; ?>

}

.menuMobile--creator {

  --creator-menu-bottom-pad: 6px;
  --creator-menu-height: 68px;
  --creator-publish-size: 54px;
  --creator-publish-half: 27px;
  --creator-publish-clearance: calc(var(--creator-publish-size) + 42px);

  padding: 0 12px calc(env(safe-area-inset-bottom, 0px) + 6px);

  width: 100%;
  max-width: 100vw;
  box-sizing: border-box;

  z-index: 1060;

  overflow: visible !important;

  background: #000;

}

.app-auth-shell .menuMobile.menuMobile--creator .menuMobile-nav--creator {

  position: relative;

  display: grid !important;

  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) var(--creator-publish-clearance) minmax(0, 1fr) minmax(0, 1fr);

  justify-content: space-between;
  justify-items: center;

  align-items: center;

  border-radius: 0;

  min-height: var(--creator-menu-height);

  width: 100%;
  max-width: 100%;
  box-sizing: border-box;
  padding: 0 4px 8px;

  column-gap: 12px;

  background: transparent;

  box-shadow: none;

  overflow: visible;

  height: var(--creator-menu-height);

}

.menuMobile--creator .menuMobile-nav--creator > li {

  display: flex;
  min-width: 0;

  justify-content: center;

  align-items: center;

}

.menuMobile--creator .menuMobile-nav--creator .btn-mobile:not(.btn-mobile-publish) {

  min-width: 40px;

  min-height: 40px;

  display: inline-flex;

  align-items: center;

  justify-content: center;

  padding: 0;

  color: #fff !important;

}

.menuMobile--creator .menu-item-home {

  justify-content: center;

}

.menuMobile--creator .menu-item-profile {

  justify-content: center;

}

.menuMobile--creator .menu-item-message {

  justify-content: center;

}

.menuMobile--creator .menu-item-last {

  justify-content: center;

}

.menuMobile--creator .menu-mobile-publish-placeholder {

  width: var(--creator-publish-clearance);

  min-height: 1px;

  pointer-events: none;

}

.app-auth-shell .menuMobile.menuMobile--creator.is-home-active .menuMobile-nav--creator {

  grid-template-columns: max-content minmax(0, 1fr) var(--creator-publish-clearance) minmax(0, 1fr) 40px;

}

.app-auth-shell .menuMobile.menuMobile--creator.is-profile-active .menuMobile-nav--creator {

  grid-template-columns: minmax(0, 1fr) max-content var(--creator-publish-clearance) minmax(0, 1fr) 40px;

}

.app-auth-shell .menuMobile.menuMobile--creator.is-message-active .menuMobile-nav--creator {

  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) var(--creator-publish-clearance) max-content 10px;

}

.app-auth-shell .menuMobile.menuMobile--creator.is-last-active .menuMobile-nav--creator {

  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) var(--creator-publish-clearance) minmax(0, 1fr) max-content;

}

.app-auth-shell .menuMobile.menuMobile--creator .menu-item-home .btn-mobile.active.disabled,
.app-auth-shell .menuMobile.menuMobile--creator .menu-item-profile .btn-mobile.active.disabled,
.app-auth-shell .menuMobile.menuMobile--creator .menu-item-message .btn-mobile.active.disabled,
.app-auth-shell .menuMobile.menuMobile--creator .menu-item-last .btn-mobile.active.disabled {

  min-width: 0;
  min-height: 40px;
  padding: 0 14px;

}

.app-auth-shell .menuMobile.menuMobile--creator .menu-item-home .btn-mobile:not(.active),
.app-auth-shell .menuMobile.menuMobile--creator .menu-item-profile .btn-mobile,
.app-auth-shell .menuMobile.menuMobile--creator .menu-item-message .btn-mobile:not(.active),
.app-auth-shell .menuMobile.menuMobile--creator .menu-item-last .btn-mobile {

  min-width: 40px;
  padding-inline: 0;

}

html[data-bs-theme="light"] .menuMobile--creator .menuMobile-nav--creator {

  background: transparent;

}

.menu-mobile-publish-slot {

  position: fixed;

  left: 50%;

  bottom: calc(env(safe-area-inset-bottom, 0px) + var(--creator-menu-bottom-pad) + var(--creator-menu-height) - var(--creator-publish-half));

  width: var(--creator-publish-size);

  height: var(--creator-publish-size);

  transform: translateX(-50%);

  z-index: 1070;

  pointer-events: none;

  overflow: visible;

  display: flex;

  align-items: center;

  justify-content: center;

}

.btn-mobile-publish {

  position: absolute;

  left: auto;

  top: 0;

  transform: none;

  width: var(--creator-publish-size);

  height: var(--creator-publish-size);

  border: 0;

  border-radius: 50%;

  display: inline-flex;

  align-items: center;

  justify-content: center;

  background: linear-gradient(180deg, #23242e 0%, #161720 100%);

  color: #fff !important;

  box-shadow: 0 0 0 16px rgba(0, 0, 0, 0.9), 0 10px 24px rgba(0, 0, 0, 0.34);

  z-index: 1;

  pointer-events: auto;

  overflow: visible;

  transition: background-color .18s ease, box-shadow .18s ease;

}

.btn-mobile-publish i {

  font-size: 1.45rem;

  line-height: 1;

}

.btn-mobile-publish:hover,
.btn-mobile-publish:focus {

  color: #fff !important;

  background: linear-gradient(180deg, #292a35 0%, #1b1c25 100%);

  border: 0;

  box-shadow: 0 0 0 16px rgba(0, 0, 0, 0.92), 0 12px 28px rgba(0, 0, 0, 0.4);

}

.btn-mobile-publish.is-active {

  background: linear-gradient(180deg, #272935 0%, #1b1d27 100%);

  border: 0;

  box-shadow: 0 0 0 16px rgba(0, 0, 0, 0.92), 0 12px 28px rgba(0, 0, 0, 0.42);

}

.btn-mobile-publish:active,
.btn-mobile-publish:focus,
.btn-mobile-publish:focus-visible,
.btn-mobile-publish.active,
.btn-mobile-publish.show,
.btn-mobile-publish[aria-expanded="true"] {

  border: 0 !important;
  box-shadow: 0 0 0 16px rgba(0, 0, 0, 0.92), 0 12px 28px rgba(0, 0, 0, 0.4) !important;

}

.btn-mobile-publish.is-active:active,
.btn-mobile-publish.is-active:focus,
.btn-mobile-publish.is-active:focus-visible,
.btn-mobile-publish.is-active.active,
.btn-mobile-publish.is-active.show,
.btn-mobile-publish.is-active[aria-expanded="true"] {

  border: 0 !important;
  box-shadow: 0 0 0 16px rgba(0, 0, 0, 0.92), 0 12px 28px rgba(0, 0, 0, 0.42) !important;

}

.btn-mobile-publish.is-disabled {

  pointer-events: none;

  cursor: default;

}

body.modal-open .menuMobile.menuMobile--creator {

  opacity: 0 !important;
  visibility: hidden !important;
  pointer-events: none !important;

}

body.modal-open .menu-mobile-publish-slot {

  z-index: 1020 !important;
  pointer-events: none !important;
  opacity: 0 !important;
  visibility: hidden !important;

}

body.modal-open .menu-mobile-publish-slot .btn-mobile-publish {

  pointer-events: none !important;

}

.menuMobile--creator .btn-mobile.active.disabled {

  background: #ffffff;

  color: #111 !important;

  box-shadow: none;

  padding-inline: 14px;

}

.menuMobile--creator.is-message-active .menuItem-message,
.menuMobile--creator.is-message-active .menu-item-message {

  justify-content: center;

}

.menuMobile--creator.is-message-active .menu-item-message .btn-mobile.active.disabled {

  transform: none;

}

.menuMobile--creator.is-message-active .menu-item-last {

  justify-content: center;

}

.menuMobile--creator.is-message-active .menu-item-last .btn-mobile {

  transform: none;

}

.menuMobile--creator.is-last-active .menu-item-last .btn-mobile.active.disabled {

  transform: none;

}

.menuMobile--creator .btn-mobile.active.disabled .icon-navbar,
.menuMobile--creator .btn-mobile.active.disabled .mobile-nav-icon-wrap,
.menuMobile--creator .btn-mobile.active.disabled i {

  color: #111 !important;

}

.menuMobile--creator .btn-mobile .icon-navbar,
.menuMobile--creator .btn-mobile i,
.menuMobile--creator .btn-mobile span {

  color: inherit;

}

.creator-publish-modal {

  background: transparent;

  border: 0;

  box-shadow: none;

}

.creator-publish-modal > .modal-body > .card,
.creator-publish-modal .modal-body > .card,
.creator-publish-modal .card.bg-white.shadow.border-0 {

  background: #0b0b0d !important;

  border: 1px solid rgba(255, 255, 255, 0.16) !important;

  border-radius: 18px !important;

  box-shadow: 0 14px 32px rgba(0, 0, 0, 0.34) !important;

}

.creator-publish-modal .card-body.position-relative {

  padding: 20px 24px 22px !important;

}

.creator-publish-modal .card-body.position-relative > .mb-4.position-relative {

  margin-bottom: 18px !important;

  padding-right: 42px;

  color: #fff;

}

.creator-publish-modal #creator-publish-menu-title {

  color: #fff;

  font-size: 15px;

  font-weight: 700;

  line-height: 1.25;

}

.creator-publish-modal .btn-cancel-msg {

  position: absolute;

  top: -2px;

  right: 0;

  width: 28px;

  height: 28px;

  display: inline-flex;

  align-items: center;

  justify-content: center;

  color: rgba(255, 255, 255, 0.88);

  line-height: 1;

  cursor: pointer;

}

.creator-publish-modal .btn-cancel-msg i {

  display: block;

  font-size: 12px;

  line-height: 1;

}

.creator-publish-modal .btn-cancel-msg:hover,
.creator-publish-modal .btn-cancel-msg:focus {

  color: #fff;

}

.creator-publish-modal .choose-type-sale,
.creator-publish-modal .choose-type-sale-btn {

  display: block;

  width: 100%;

  color: #fff !important;

  text-align: left;

  text-decoration: none;

  background: #242424 !important;

  border: 1px solid rgba(255, 255, 255, 0.12) !important;

  border-radius: 18px !important;

  box-shadow: none !important;

}

.creator-publish-modal .choose-type-sale:hover,
.creator-publish-modal .choose-type-sale:focus,
.creator-publish-modal .choose-type-sale-btn:hover,
.creator-publish-modal .choose-type-sale-btn:focus {

  color: #fff !important;

  background: #2a2a2a !important;

  border-color: rgba(255, 255, 255, 0.18) !important;

}

.creator-publish-modal .choose-type-sale-btn {

  border: 0;

  padding: 0;

}

.creator-publish-modal .creator-publish-live-option {

  background: #242424 !important;

}

.btn-reset {

  background: transparent;

  border: 0;

}

.btn-reset:focus {

  outline: none;

  box-shadow: none;

}

@media (max-width: 767.98px) {

  .creator-home-composer {

    display: none;

  }

  body.creator-mobile-shell main[role="main"] {

    padding-bottom: 94px;

  }

  body.creator-mobile-shell .app-footer-shell {

    padding-bottom: 10px;

  }

  body.creator-mobile-shell.new-post-page main[role="main"] {

    padding-bottom: 128px;

  }

  body.creator-mobile-shell.new-post-page .app-footer-shell {

    padding-bottom: 128px;

  }

  body.creator-mobile-shell.new-post-page .section.section-sm {

    padding-bottom: 28px;

  }
}

.btn-mobile:hover {

    background-color: rgb(243 243 243 / 26%);

    text-decoration: none !important;

    -webkit-transition: all 200ms linear;

    -moz-transition: all 200ms linear;

    -o-transition: all 200ms linear;

    -ms-transition: all 200ms linear;

    transition: all 200ms linear;

}



@media (max-width: 991px) {

  .navbar .navbar-collapse {

    width: 300px !important;

    box-shadow: 5px 0px 8px #000;

  }



  .card-profile {width: 100% !important; text-align: center;}

  .section-msg {padding: 0 !important;}

  .text-center-sm { text-align: center !important;}



  #navbarUserHome { position: initial !important;}

  .section-sm.creator .menu-left-home {

    background: transparent !important;

    box-shadow: none !important;

    padding: 0 !important;

    border: 0 !important;

    margin-bottom: 10px !important;

  }

  .section-sm.creator .menu-left-home.side_bar_box_shadow {

    background: transparent !important;

    border: 0 !important;

    box-shadow: none !important;

  }

  .section-sm.creator .menu-left-home .btn-menu-expand {

    margin-bottom: 8px !important;

  }

  .section-sm.creator .menu-left-home .btn-category {

    width: 100%;

    white-space: normal;

    word-break: break-word;

    text-align: left;

  }

  .section-sm.creator .category-collapse {

    background: transparent !important;

    width: 100% !important;

    box-shadow: none !important;

    position: static !important;

  }



  .notify {

    top: 5px !important;

    right: 5px !important;

  }



  <?php if(auth()->guard()->check()): ?>

  .margin-auto {

      margin: auto!important;

  }

  <?php endif; ?>

}

.sidebar-overlay #mobileMenuOverlay {

    position: fixed;

    width: 100%;

    height: 100%;

    background: rgba(0, 0, 0, 0.8);

    z-index: 101;

    -webkit-transition: all .9s;

    -moz-transition: all .8s;

    -ms-transition: all .8s;

    -o-transition: all .8s;

    transition: all .8s;

    transition-delay: .35s;

    left: 0;

}

.noti_notifications, .noti_msg {display: none;}



.link-menu-mobile {border-radius: 6px;}

.link-menu-mobile:hover:not(.balance) {

  background: rgb(242 242 242 / 40%);

}

a.link-border {text-decoration: none;}

@media (max-width: 479px) {

  .card-border-0 {

    border-right: 0 !important;

    border-left: 0 !important;

    border-radius: 0 !important;

  }

  .card.rounded-large {

    border-radius: 0 !important;

  }

  .wrap-post {padding: 0 !important;}

}



@media (min-width: 576px) {

  .modal-login {

      max-width: 415px;

  }

}

.toggleComments { cursor: pointer;}

.blocked {left: 0; top: 0;}

.card-settings > .list-group-flush>.list-group-item {border-width: 0 0 0px !important; border-radius:8px;}

.btn-active-hover {background-color: #f3f3f3;}



/* Chrome, Safari, Edge, Opera */

input::-webkit-outer-spin-button,

input::-webkit-inner-spin-button {

  -webkit-appearance: none;

  margin: 0;

}



/* Firefox */

input[type=number] {

  -moz-appearance: textfield;

}



.section-msg {

    display: -webkit-box;

    display: -ms-flexbox;

    display: flex;

    -webkit-box-flex: 1;

    -ms-flex-positive: 1;

    flex-grow: 1;

    -webkit-box-orient: vertical;

    -webkit-box-direction: normal;

    -ms-flex-flow: column;

    flex-flow: column;

    min-width: 0;

    width: 100%;

}

.container-media-msg {max-width: 100%;max-height: 100%;}

.container-media-img {max-width: 100%;}

.rounded-top-right-0 {border-top-right-radius: 0 !important;}

.rounded-top-left-0{border-top-left-radius: 0 !important;}

.custom-rounded {border-radius: 10px;}

.card-profile {width: 75%;}

.fancybox-button {background: none !important;}

.modal-open .modal,

.modal,

.sweet-overlay {

  backdrop-filter: blur(10px);

  -webkit-backdrop-filter: blur(10px);

}

.pulse-btn:active > i,

.pulse-btn:active > svg {

  animation-duration: 600ms;

  animation-name: pulse-animation;

  animation-timing-function: ease-in;

  -webkit-transition: all .1s;

    transition: all .1s;

}

@keyframes pulse-animation{

  0% { transform:scale(1.2); }

  100% { transform:scale(0); }

}

.post-image {

  max-height: 600px;

  object-fit: cover;

  object-position: 100% center;

}

.post-image-full {

  max-height: 600px;

  object-fit: contain;

  object-position: center;

  text-align: center;

  margin: 0 auto;

  display: block;

  background: <?php if(auth()->check() && auth()->user()->dark_mode == 'on'): ?> #2c2c2c <?php else: ?> #f8f8f8 <?php endif; ?>;

}

@media (max-width: 600px) {

  .post-image {

      max-height: 90vw;

  }

}

.swiper-container {

  width: 100%;

}

.font-14 {

  font-size: 14px;

}

.card-user-profile {

  border-radius: .50rem!important;

}

.card-user-profile > .card-cover {

  border-top-left-radius: .50rem!important;

  border-top-right-radius: .50rem!important;

}

.btn-arrow-expand[aria-expanded='true'] > i.fa-chevron-down:before {

    transform: rotate(180deg);

    content: "\f077";

}



.btn-menu-expand[aria-expanded='true'] > i.fa-bars:before {

    content: "\f00d";

}

.wrapper-msg-right {

  float: initial;

  margin-right: auto;

  max-width: 500px;

}

.wrapper-msg-left {

  float: initial;

  margin-left: auto;

  max-width: 500px;

}

.post-img-grid {

  position: absolute;

  display: block;

  left: 0;

  right: 0;

  top: 0;

  bottom: 0;

  width: 100%;

  height: 100%;

  opacity: 0;

}

@keyframes placeHolderShimmer {

  0% {

    background-position: -150px 0;

  }

  100% {

    background-position: 150px 0;

  }

}

.media-wrapper {

  background-color: var(--color-media-wrapper);

  animation-name: pulse;

  animation-duration: 2s;

  animation-iteration-count: infinite;

  position: relative;

}



@keyframes pulse {

  0% {

    background-color: var(--color-media-wrapper);

  }

  50% {

    background-color: var(--color-pulse-media-wrapper);

  }

  100 {

    background-color: var(--color-media-wrapper);

  }

}

/* ----- MEDIA GRID 1 ------- */

.media-grid-1 .media-wrapper {

  position: relative;

  padding-top: 60%;

  width: 100%;

  overflow: hidden;

  background-size: cover;

  background-position: center;

  cursor: pointer;

  border-radius: 6px;

  -webkit-border-radius: 6px;

  -o-border-radius: 6px;

  -ms-border-radius: 6px;

  display: block;

}



/* ----- MEDIA GRID 2 ------- */

.media-grid-2 {

  position: relative;

  width: 100%;

  display: flex;

  display: -webkit-flex;

}



.media-grid-2 .media-wrapper {

  position: relative;

  padding-top: 45%;

  width: 50%;

  overflow: hidden;

  background-size: cover;

  background-position: center;

  cursor: pointer;

}

.media-grid-2 .media-wrapper:nth-child(1),

.media-grid-3 .media-wrapper:nth-child(1) {

  border-bottom-left-radius: 6px;

  -webkit-border-bottom-left-radius: 6px;

  -o-border-bottom-left-radius: 6px;

  -ms-border-bottom-left-radius: 6px;

  border-top-left-radius: 6px;

  -webkit-border-top-left-radius: 6px;

  -o-border-top-left-radius: 6px;

  -ms-border-top-left-radius: 6px;

}



.media-grid-2 .media-wrapper:nth-child(2) {

  border-bottom-right-radius: 6px;

  -webkit-border-bottom-right-radius: 6px;

  -o-border-bottom-right-radius: 6px;

  -ms-border-bottom-right-radius: 6px;

  border-top-right-radius: 6px;

  -webkit-border-top-right-radius: 6px;

  -o-border-top-right-radius: 6px;

  -ms-border-top-right-radius: 6px;

}



.media-grid-2 .media-wrapper:nth-child(2) {

  margin-left: 3px;

}

.media-grid-1,

.media-grid-3,

.media-grid-4,

.media-grid-5 {

  position: relative;

  width: 100%;

  display: table;

  overflow: hidden;

}

/* ----- MEDIA GRID 3 ------- */

.media-grid-3 .media-wrapper:nth-child(1) {

  position: relative;

  padding-top: 70.6%;

  width: calc(50% / 1 - 0px);

  overflow: hidden;

  background-size: cover;

  background-position: center;

  cursor: pointer;

  float: left;

}

.media-grid-3 .media-wrapper:nth-child(2) {

  position: relative;

  padding-top: 35%;

  width: calc(100% / 2 - 3px);

  overflow: hidden;

  background-size: cover;

  background-position: center;

  cursor: pointer;

  float: left;

  margin-left: 3px;

  margin-bottom: 3px;

  border-top-right-radius: 6px;

  -webkit-border-top-right-radius: 6px;

  -o-border-top-right-radius: 6px;

  -ms-border-top-right-radius: 6px;

}

.media-grid-3 .media-wrapper:nth-child(3) {

  position: relative;

  padding-top: 35%;

  width: calc(100% / 2 - 3px);

  overflow: hidden;

  background-size: cover;

  background-position: center;

  cursor: pointer;

  float: left;

  margin-left: 3px;

  border-bottom-right-radius: 6px;

  -webkit-border-bottom-right-radius: 6px;

  -o-border-bottom-right-radius: 6px;

  -ms-border-bottom-right-radius: 6px;

}



/* ----- MEDIA GRID 4/5 OR MORE ------- */

.media-grid-4 .media-wrapper:nth-child(1) {

  position: relative;

  padding-top: 50%;

  width: calc(100% / 1 - 0px);

  overflow: hidden;

  background-size: cover;

  background-position: center;

  cursor: pointer;

  float: left;

  margin-bottom: 3px;

  border-top-right-radius: 6px;

  -webkit-border-top-right-radius: 6px;

  -o-border-top-right-radius: 6px;

  -ms-border-top-right-radius: 6px;

  border-top-left-radius: 6px;

  -webkit-border-top-left-radius: 6px;

  -o-border-top-left-radius: 6px;

  -ms-border-top-left-radius: 6px;

}

.media-grid-5 .media-wrapper:nth-child(1) {

  position: relative;

  padding-top: 45%;

  width: calc(100% / 2 - 3px);

  float: left;

  overflow: hidden;

  background-size: cover;

  background-position: center;

  cursor: pointer;

  margin-bottom: 3px;

  margin-right: 3px;

}

.media-grid-5 .media-wrapper:nth-child(2) {

  position: relative;

  padding-top: 45%;

  width: calc(100% / 2 - 0px);

  float: left;

  overflow: hidden;

  background-size: cover;

  background-position: center;

  cursor: pointer;

  margin-bottom: 3px;



}

.media-grid-4 .media-wrapper:nth-child(2),

.media-grid-5 .media-wrapper:nth-child(3) {

  position: relative;

  padding-top: 30.3%;

  width: calc(100% / 3 - 0px);

  overflow: hidden;

  background-size: cover;

  background-position: center;

  cursor: pointer;

  float: left;

  border-bottom-left-radius: 6px;

  -webkit-border-bottom-left-radius: 6px;

  -o-border-bottom-left-radius: 6px;

  -ms-border-bottom-left-radius: 6px;

}

.media-grid-4 .media-wrapper:nth-child(3),

.media-grid-5 .media-wrapper:nth-child(4) {

  position: relative;

  padding-top: 30.3%;

  width: calc(100% / 3 - 3px);

  overflow: hidden;

  background-size: cover;

  background-position: center;

  cursor: pointer;

  float: left;

  margin-left: 3px;

}

.media-grid-4 .media-wrapper:nth-child(4),

.media-grid-5 .media-wrapper:nth-child(5) {

  position: relative;

  padding-top: 30.3%;

  width: calc(100% / 3 - 3px);

  overflow: hidden;

  background-size: cover;

  background-position: center;

  cursor: pointer;

  float: left;

  margin-left: 3px;

  border-bottom-right-radius: 6px;

  -webkit-border-bottom-right-radius: 6px;

  -o-border-bottom-right-radius: 6px;

  -ms-border-bottom-right-radius: 6px;

}

.media-grid-4 .media-wrapper:nth-child(1),

.media-grid-4 .media-wrapper:nth-child(2),

.media-grid-4 .media-wrapper:nth-child(3),

.media-grid-4 .media-wrapper:nth-child(4) > img,

.media-grid-4 .media-wrapper:nth-child(5) > img {

  z-index: 2;

}

.wrapper-media-music {

    width: 100%;

    max-width: 500px;

    height: auto;

    border: 1px solid #DDD;

    border-radius: 6px;

}

.progress-upload-cover {

  background-color: <?php echo e($settings->color_default, false); ?> !important;

}

.more-media {

  display: block;

  background: rgba(0,0,0,.3);

  position: absolute;

  top: 0;

  left: 0;

  width: 100%;

  height: 100%;

  z-index: 9;

}

.more-media h2 {

  line-height: 1.8;

  text-align: center;

  position: absolute;

  left: 0;

  width: 100%;

  top: 50%;

  margin-top: -30px;

  color: #fff;

  z-index: 5;

}

.container-post-media {

  position: relative;

  width: 100%;

  display: flex;

  display: -webkit-flex;

  overflow: hidden;

}

.button-play {

  cursor: pointer;

  margin: auto auto;

  width: 48px;

  height: 48px;

  position: absolute;

  top: 0;

  bottom: 0;

  left: 0;

  right: 0;

  z-index: 3;

  font-size: 28px;

  line-height: 48px;

  background-color: <?php echo e($settings->color_default, false); ?>!important;

  border-radius: 100%;

  text-align: center;

  opacity: 0.9;

}

.wrapper-msg-inbox {

  overflow: auto;

  overflow-x: hidden;

}

@media (max-width: 991px) {
/*
  .wrapper-msg-inbox {

    padding-top: 78px !important;

    padding-bottom: 60px !important;

  }*/

}

.wrapper-msg-inbox::-webkit-scrollbar {

    width: 5px;

    height: 8px;

    -webkit-transition: all 0.2s ease;

  	        transition: all 0.2s ease;

}

.wrapper-msg-inbox::-webkit-scrollbar-track {

    background-color: transparent;

    border-radius: 6px;

}

.wrapper-msg-inbox::-webkit-scrollbar-thumb {

    background-color: #ccc;

    border-radius: 6px;

}

.msg-inbox {

  border: 0;

  background: transparent;

  padding: 0 16px 0 12px;

  margin-top: 12px !important;

}

.msg-inbox .list-group {

  background: transparent;

}

.msg-inbox .item-chat {

  width: 100%;

  border: 0 !important;

  border-radius: 20px !important;

  padding: 16px !important;

}

.msg-inbox .item-chat .media {

  width: 100%;

  min-width: 0;

}

.msg-inbox .item-chat .media-body {

  min-width: 0;

}

@media (min-width: 1280px) {

  .container-lg-3 {

    max-width: 1300px;

  }

}

.menu-left-home li > a {

  padding: 5px 12px;

  display: block;

  font-size: 19px;

  text-decoration: none;

  margin-bottom: 8px;

  border-radius: <?php echo e($settings->button_style == 'rounded' ? '20px' : '8px', false); ?>;

  color: #8a96a3;

  -webkit-transition: all 200ms linear;

  -moz-transition: all 200ms linear;

  -o-transition: all 200ms linear;

  -ms-transition: all 200ms linear;

  transition: all 200ms linear;

}

.menu-left-home li > a:hover,

.menu-left-home li > a.active {

  background-color: <?php echo e($settings->color_default, false); ?>;

  color: white;

}

.sticky-top {

    top: 90px;

}

.btn-category {

  width: 100%;

  text-align: left;

}

.category-filter {

  padding: 10px 2px 6px 2px;

  display: block;

  font-weight: bold;

}

.text-red {

  color: #F00;

}

.text-orange {

  color: #ff3507;

}

.img-vertical-lg {

  padding-top: 80% !important;

  max-width: 300px;

}

.wrapper-msg-left .img-vertical-lg {

  float:right;

}

.wrapper-msg-right .img-vertical-lg {

  float:left;

}

.button-white-sm {

  padding: 4px 15px;

  border: 1px solid #ccc;

  border-radius: 20px;

  text-align: center;

  font-size: 14px;

  color: <?php if(auth()->check() && auth()->user()->dark_mode == 'on'): ?> #fff <?php else: ?> #333 <?php endif; ?>;

  display: inline-block;

  vertical-align: middle;

}

a:hover.button-white-sm {

  text-decoration: none;

  border-color: <?php echo e($settings->color_default, false); ?>;

  background-color: <?php echo e($settings->color_default, false); ?>;

  color: white;

  -webkit-transition: all 200ms linear;

  -moz-transition: all 200ms linear;

  -o-transition: all 200ms linear;

  -ms-transition: all 200ms linear;

  transition: all 200ms linear;

}

.msg-inbox .active .verified {

  color: #FFF !important;

}

select[multiple] {

  visibility: hidden;

}

.select2-container

.select2-selection--multiple {

  min-height: 47px !important;

}



.select2-container--default

.select2-selection--multiple {

  border-left-width: 0 !important;

  border-bottom-left-radius: 0 !important;

  border-top-left-radius: 0 !important;



  background-color: <?php if(auth()->check() && auth()->user()->dark_mode == 'on'): ?> #303030 !important <?php else: ?> #fff !important <?php endif; ?>;

  border-color: <?php if(auth()->check() && auth()->user()->dark_mode == 'on'): ?> #222  !important <?php else: ?> #cad1d7 !important <?php endif; ?>;

}

<?php if(auth()->check() && auth()->user()->dark_mode == 'on'): ?>

.custom-control-label:not(.switch)::before {

background-color: #333 !important;

border-color: #adb5bd !important;

}

.ui-widget-content {

  background: #303030;

}

<?php endif; ?>



.select2-hidden-accessible {

  position: absolute !important;

}

.select2-container--default

.select2-selection--multiple

.select2-selection__choice {

  background-color: var(--plyr-color-main) !important;

  border: 1px solid var(--plyr-color-main) !important;

  color: #fff !important;

  padding: 4px 10px !important;

}

.select2-results__option {

  color: #333;

}

.select2-container

.select2-search--inline

.select2-search__field {

  margin-top: 10px !important;

}

.announcements a {

  text-decoration: none;

  border-bottom: 1px solid;

  color: #fff;

}

.unread-chat {

  background-color: #f8f9fa;

}

.glightbox-open {

	height: auto !important;
	overflow: hidden !important;

}

.glightbox-container .gslide-inline {

	background: transparent !important;
	max-width: min(960px, calc(100vw - 32px));
	max-height: calc(100vh - 32px);
	width: 100%;
	padding: 0;
	overflow: hidden;

}

.glightbox-container .gslide-inline .ginlined-content {

	padding: 0;
	max-height: calc(100vh - 32px);
	overflow: hidden;
	display: flex;
	justify-content: center;

}

.glightbox-post-video {

	background: #171717;
	border-radius: 24px;
	width: min(960px, 100%);
	max-height: calc(100vh - 32px);
	overflow-x: hidden;
	overflow-y: auto;

}

.glightbox-post-video__media {

	background: #000;

}

.glightbox-post-video__media .plyr {

	border-radius: 0;

}

.glightbox-post-video__footer {

	padding: 20px 22px;

}

.glightbox-post-video__footer .card-footer {

	background: transparent;
	border: 0;
	padding: 0;

}

.glightbox-post-video__footer .action-pill {

	cursor: default;

}

.txt-black {color: #241e12;}

.p-bottom-8 {padding-bottom: 8px;}

.pinned-post {border-color:<?php echo e($settings->color_default, false); ?> !important;}

.post-pending {

  border-color:#ff9800 !important;

}

.table-striped tbody tr:nth-of-type(odd) {background-color: rgba(0,0,0,.03);}



<?php if(auth()->guard()->check()): ?>

.icon-dashboard {

  padding: 12px 11px;

  /* background-color: <?php if(auth()->user()->dark_mode == 'on'): ?> #414141 !important; <?php else: ?> <?php echo e($settings->color_default, false); ?>2b; <?php endif; ?> */

  border-radius: 35%;

  color: <?php if(auth()->user()->dark_mode == 'on'): ?> #FFF !important; <?php else: ?> <?php echo e($settings->color_default, false); ?> !important; <?php endif; ?>;

}

.icon-dash {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 46px;
  height: 46px;
  flex-shrink: 0;
  overflow: hidden;
}

.icon-dash .icon-dashboard {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  padding: 0;
  font-size: 16px;
}

.icon-dash .fa-stack {
  line-height: 1;
}

.icon-dash .fa-stack-1x {
  transform: translate(5px, -5px);
  font-size: 0.75em;
}

.icon-dash-euro .fa-stack-1x {
  transform: translate(5px, -5px);
  font-size: 0.75em;
}

.icon-dash-euro .icon-hand-euro {
  font-size: 1.4em;
}

.icon-dashboard-2 {

    background-color: <?php echo e(auth()->user()->dark_mode == 'on'? '#414141' : $settings->color_default.'2e', false); ?>;

    border-radius: 50%;

    color: <?php echo e(auth()->user()->dark_mode == 'on'? '#fff' : $settings->color_default, false); ?> !important;

    width: 3rem!important;

    height: 3rem!important;

    align-items: center!important;

    justify-content: center!important;

    flex-shrink: 0!important;

    display: inline-flex!important;

    font-size: 24px;

}

.icon-notify {

  color: <?php if(auth()->user()->dark_mode == 'on'): ?> #FFF !important <?php else: ?> <?php echo e($settings->color_default, false); ?> <?php endif; ?>;

  width: 60px;

  height: 60px;

  font-size: 55px;

  line-height: 60px;

}

.btn-blocked {

    position: absolute;

    width: 100%;

    height: 100%;

    background-color: rgba(255, 255, 255, 0.3);

    z-index: 99;

    top: 0;

    left: 0;

    border-radius: inherit;

}



.icon-sm-radio {

  padding: 7px 8px;

  border-radius: 35%;

  background-color: <?php if(auth()->user()->dark_mode == 'on'): ?> #414141;  <?php else: ?> <?php echo e($settings->color_default, false); ?>2b; <?php endif; ?>

  color: <?php if(auth()->user()->dark_mode == 'on'): ?> #FFF !important; <?php else: ?> <?php echo e($settings->color_default, false); ?> !important; <?php endif; ?>

}

<?php endif; ?>



@media (max-width: 991px) {

  .w-100-mobile {

    width: 100% !important;

  }

}

.button-like-live {

  font-size: 25px;

}

@media (max-width: 767px) {



  .wrapper-live-chat  {

    background-color: transparent !important;

    position: absolute !important;

    padding: 0 !important;

    z-index: 200 !important;

    max-height: 250px !important;

    bottom: 0;

    left: 0;

    -webkit-mask-image: linear-gradient(transparent 0%, rgba(50, 50, 50, 1.0) 20%);

  }

  .wrapper-live-chat > .card,

  #commentLive {

    background-color: transparent !important;

    color: #FFF;

  }

  .titleChat {

    display: none !important;

  }

  #allComments {

    padding-top: 20px !important;

  }

  .live-blocked {

    background-color: rgba(255, 255, 255, 0.1) !important;

  }

  li.chatlist {

    color: #FFF !important;

    font-size: 14px;

    text-shadow: 0 1px 2px #000;

  }

  .wrapper-live-chat .content {

    padding-bottom: 0 !important;

    padding-top: 0 !important;

  }

  .wrapper-live-chat .card-footer {

    border: 0 !important;

  }

  .buttons-live {

    color: #FFF !important;

  }

  .buttons-live:hover {

    background-color: transparent !important;

  }

  #commentLive::placeholder {

    color: #FFF !important;

    opacity: 1;

  }

  #commentLive::-moz-placeholder {

    color: #FFF !important;

    opacity: 1;

  }

  #commentLive::-ms-input-placeholder {

    color: #FFF !important;

    opacity: 1;

  }

  #commentLive:-ms-input-placeholder {

    color: #FFF !important;

    opacity: 1;

  }

  #commentLive::-webkit-input-placeholder {

    color: #FFF !important;

    opacity: 1;

  }

  input#commentLive {

    border: 1px solid #FFF !important;

    border-radius: 60px;

  }

  .offline-live { display: none;}

}/* End max-width 767 */



.tipped-live {

  font-size: 14px;

  text-shadow: 0 1px 4px #4b4b4b;

}

.live_offline::before {

  top: 0;

  left: 0;

  right: 0;

  bottom: 0;

  backdrop-filter: blur(15px);

  -webkit-backdrop-filter: blur(15px);

  content: "";

  z-index: 1;

  position: absolute;

}

.live_offline::after {

  top: 0;

  left: 0;

  right: 0;

  bottom: 0;

  content: "";

  z-index: 1;

  position: absolute;

  background-color: rgba(50, 50, 50, 0.8);

}

.text-content-live {

  position: relative;

  color: #FFF;

  z-index: 2 !important;

}

#full-screen-video {

  position: relative;

  width: 100%;

  height: 100%;

  flex: 1 1 auto;

  min-height: 320px;

  overflow: hidden;

  background-color: #000;

}

#full-screen-video > div,
#full-screen-video video,
#full-screen-video .agora_video_player,
#full-screen-video .agora_video_player video {

  width: 100% !important;

  height: 100% !important;

  object-fit: cover;

}

.liveContainer {

  background-color: #000;

}

.liveContainer:not(.live_offline) > .content.container-msg {

  height: 100%;

  display: flex;

  padding: 0 !important;

}

.live-top-menu {

  width: 100%;

  padding: 1rem!important;

  position: absolute;

  top: 0;

  right: 0;

  z-index: 200;

}

@keyframes pulse-live {

  0% {

    background-color: #ff0000;

  }

  50% {

    background-color: #ff0000b0;

  }

  100 {

    background-color: #ff0000;

  }

}

.live {

  color: #fff;

  border-color: #ff0000;

  background-color: #ff0000;

  padding: 5px 15px;

  border-radius: 4px;

  display: inline-block;

  vertical-align: text-top;

  font-weight: bold;

  animation-name: pulse-live;

  animation-duration: 2s;

  animation-iteration-count: infinite;

}

.live-views {

  color: #fff;

  border-color: #000000;

  background-color: #0000007a;

  padding: 5px 15px;

  border-radius: 4px;

  display: inline-block;

  vertical-align: text-top;

  font-weight: bold;

}

.close-live,

.exit-live,

.live-options {

  color: #FFF;

  font-size: 22px;

  vertical-align: bottom;

  cursor: pointer;

}

.text-shadow-sm {

  text-shadow: 0 1px 0px #000;

}

.div-flex {

  flex: 1 1 auto;

  position: relative;

  min-height: 12px;

}

.chat-msg {

  flex: 1 1 auto;

  display: flex;

  flex-direction: column;

}

.menu-options-live a {

  cursor: pointer;

}

.avatar-wrap-live {

  width: 150px;

  height: 150px;

  border-radius: 50%;

  margin: 0 auto;

  background-color: #FFF;

  margin-bottom: 15px;

}

.video-poster-html {

  position:absolute;

  right: 0;

  top: 0;

  min-width: 100%;

  max-width: 100%;

  height: 100%;

  width: auto;

  z-index: 2;

  margin: 0 auto;

  object-fit: cover;

}

.wrapper-live {

  position: relative;

  max-width: 100px;

  margin: auto;

}

@keyframes pulseLive {

  0% {

    opacity: 0.1;

    transform: scale(1.05);

  }

  50% {

    opacity: 1;

    transform: scale(1.15);

  }

  100% {

    opacity: 0.1;

    transform: scale(1.3);

  }

}

.live-pulse {

  top: 0;

  left: 0;

  right: 0;

  bottom: 0;

  position: absolute;

  border: 3px solid #ff0000;

  z-index: 1;

  border-radius: 50%;

}

.live-pulse::after,

.live-pulse::before {

  width: 100%;

  border: 2px solid #ff0000 !important;

  height: 100%;

  content: "";

  display: block;

  z-index: 2;

  position: absolute;

  border-radius: 50%;

  animation-name: pulseLive;

  animation-duration: 1.6s;

  animation-iteration-count: infinite;

  animation-timing-function: ease-in-out;

}

.live-pulse::after {

  animation-delay: .5s;

}

.live-span {

  width: 39px;

  left: 0;

  right: 0;

  bottom: -10px;

  height: 17px;

  border-radius: 3px;

  text-transform: uppercase;

  justify-content: center;

  background-color: #ff0000;

  margin: auto;

  display: flex;

  z-index: 2;

  position: absolute;

  font-size: 8px;

  text-align: center;

  align-items: center;

  font-weight: 900;

  color: #FFF;

}

.button-like-live .bi-heart-fill {

  color: #F00 !important;

}

.avatar-live {

  border: 2px solid #f00;

}

.liveLink {cursor: pointer;}

.icon-wrap {

  position: absolute;

  top: -30px;

  right: 10px;

  z-index: 0;

  font-size: 115px;

  color: rgba(0,0,0,0.10);

  transform: rotate(20deg);

}

.inner-wrap {

  position: relative;

  z-index: 1;

}

.fee-wrap {

  padding: 5px 10px;

  border: 1px dashed #ccc;

  border-radius: 6px;

}

.btn-arrow-expand-bi[aria-expanded='true'] .bi-chevron-down::before {

  transform: rotate(180deg);

}

.transition-icon::before {

  -moz-transition: all 0.3s ease;

  -webkit-transition: all 0.3s ease;

  transition: all 0.3s ease;

}

.limitLiveStreaming {

  position: absolute;

  top: 45px;

  left: 65px;

}

.subscriptionDiscount {

  font-size: 14px;

  padding: 2px 20px;

  border: 1px dashed;

  border-radius: 50px;

  margin-top: 5px;

  margin-bottom: 5px;

  display: inline-block;

}

.border-dashed-radius {

  border: 1px dashed #a7a7a7;

  border-radius: 10px;

}

.list-taxes:last-child {

  border-bottom: 0 !important;

  border-radius: inherit;

}

.search-bar {

  border-radius: 12px;

  padding: 12px 20px !important;

  height: 40px !important;

  background-color: #f6f6f6;

  border: 1px solid transparent !important;

}

.search-bar:focus {

  border: 1px solid #ced4da !important;

  -moz-transition: all 0.3s ease;

  -webkit-transition: all 0.3s ease;

  transition: all 0.3s ease;

}

<?php if(auth()->guard()->check()): ?>

.preview-shop .fileuploader {

  display: block;

  background: <?php if(auth()->user()->dark_mode == 'on'): ?> #414141 !important; <?php else: ?> #fafbfd !important; <?php endif; ?>;

}

<?php if(auth()->user()->dark_mode == 'on'): ?>

.file-shop .fileuploader-input-caption {

  background: #414141 !important;

  border: #414141 !important;

}

<?php endif; ?>

.file-shop .fileuploader {

  display: block;

}

<?php endif; ?>



.count-previews {

  position: absolute;

  right: 10px;

  top: 10px;

  padding: 8px 6px;

  background: #0000009e;

  border-radius: 6px;

  color: #fff;

  font-size: 12px;

}

a.link-shop,

a.choose-type-sale {

  color: inherit;

  text-decoration: none;

}

a:hover.choose-type-sale {

  border-color: <?php echo e($settings->color_default, false); ?>;

}

.text-truncate-2 {

  word-wrap: break-word;

  -webkit-line-clamp: 2;

  -webkit-box-orient: vertical;

  white-space: pre-wrap;

  white-space: -moz-pre-wrap;

  white-space: -pre-wrap;

  white-space: -o-pre-wrap;

  word-break: break-word;

  text-align: left;

  display: -webkit-box;

  line-height: normal;

  height: auto;

  overflow: hidden;

  text-overflow: ellipsis;

}

.price-shop {

  position: absolute;

  right: 10px;

  bottom: 10px;

  padding: 6px 12px;

  background: #E7000B;
  // background: <?php echo e($settings->color_default, false); ?>;

  border-radius: 10px;

  color: #fff;

  font-size: 16px;

  font-weight: 500;

}

.rounded-large {

  border-radius: 15px!important;

}

.shadow-large {

    box-shadow: 0 8px 30px rgba(0,0,0,.05)!important;

}

.buttons-mobile-nav {

  position: absolute;

  right: 10px;

  top: 25px;

}

.btn-mobile-nav {

  <?php if(auth()->check() && auth()->user()->dark_mode == 'off'): ?>

  color: <?php echo e($settings->navbar_text_color, false); ?> !important;

  <?php endif; ?>

}

.btn-mobile-nav:hover {

  text-decoration: none !important;

}



.modal-content,

.modal-content > .modal-body > .card {

  border-radius: 0.8rem;

}



.btn-arrow::after {

    font-family: "bootstrap-icons";

    display: inline-block;

    padding-left: 5px;

    content: "\f138";

    transition: transform 0.3s ease-out;

    vertical-align: middle;



}

.btn-arrow-sm::after {

  font-size: 13px;

}

.btn-arrow:hover::after {

    transform: translateX(4px);

}

.btn-search {

    color: #c3c3c3;

    background: none;

    position: absolute;

    left: 0;

    outline: none;

    border: none;

    width: 50px;

    text-align: center;

    bottom: 20%;

}

.custom-select {

    white-space: nowrap;

    text-overflow: ellipsis;

    overflow: hidden;

}

.ui-widget-content {

  padding: 10px;

  border: 0 !important;

  border-bottom-left-radius: 6px;

  border-bottom-right-radius: 6px;

  box-shadow: rgb(101 119 134 / 20%) 0px 0px 15px, rgb(101 119 134 / 15%) 0px 0px 3px 1px;

}

.ui-state-active,

.ui-widget-content

.ui-state-active {

    width: 100%;

    display: block;

    border: 0 !important; 

    background: <?php echo e(auth()->check() && auth()->user()->dark_mode == 'on' ? '#222' : '#efefef', false); ?>;

    color: #333;

    text-decoration: none;

    margin: 0;

    border-radius: 6px;

  }

.ui-menu .ui-menu-item-wrapper {

  width: 100%;

  display: block;

}

.ui-menu.ui-menu-item:not(:last-child) {

  margin-bottom: 10px;

}

.ui-widget {

  font-size: 15px;

}

.btn-sm-custom {

  font-size: .875rem;

  line-height: 1.5;

  padding: 0.25rem 0.5rem;

}

.dropdown-item {

  padding: 0.3rem 1.5rem;

}

.triggerEmoji {

  font-size: 20px;

  position: absolute;

  right: 15px;

  top: 25%;

  cursor: pointer;

}

.triggerEmojiPost {

  font-size: 20px;

  position: absolute;

  right: 0;

  top: 0px;

  cursor: pointer;

}

.emoji {

  font-size: 26px;

  line-height: 32px;

  padding: 5px 8px;

  display: block;

  cursor: pointer;

  margin-bottom: 2px;

}

.dropdown-emoji {

  width: 350px;

  max-width: 350px;

  max-height: 300px;

  overflow: auto scroll;

}

.type-item {

  position: absolute;

  top: 10px;

  left: 10px;

  z-index: 2;

  // background-color: #0000008c;
  background: #27272AE5;

  font-size: 12px;
  font-weight:400;
  color: #fff;

}

.font-13 {

  font-size: 13px;

}



.custom-scrollbar::-webkit-scrollbar {

    width: 5px;

    height: 8px;

    -webkit-transition: all 0.2s ease;

  	        transition: all 0.2s ease;

}

.custom-scrollbar::-webkit-scrollbar-track {

    background-color: transparent;

    border-radius: 6px;

}

.custom-scrollbar::-webkit-scrollbar-thumb {

    background-color: #ccc;

    border-radius: 6px;

}

.icon--dashboard {

    color: rgb(0 0 0 / <?php echo e(auth()->check() && auth()->user()->dark_mode == 'on' ? '5%' : '2%', false); ?>) !important;

    transform: none !important;

}

.text-revenue {

  position: relative;

  z-index: 10;

}

.quality-video {

  padding: 2px 4px;

  background-color: #fff;

  color: #000;

  border-radius: 3px;

  margin: 0 5px;

  font-weight: bold;

}

.line-replies {

  align-items: stretch;

  border: 0;

  border-bottom: 1px solid <?php echo e($settings->color_default, false); ?>;

  box-sizing: border-box;

  display: inline-block;

  flex-direction: column;

  flex-shrink: 0;

  font: inherit;

  font-size: 100%;

  height: 0;

  margin: 0;

  margin-right: 16px;

  padding: 0;

  position: relative;

  vertical-align: middle;

  width: 24px;

}

.mr-14px {

    margin-right: 14px;

}

.dot-item:not(:last-child):after {

   font-family: "bootstrap-icons";

    content: "\F309";

    margin-left: 6px;

    color: inherit;

    vertical-align: middle;

}

.add-story {

  max-height: 160px;

  max-width: 110px;

  width: 25vw;

  border-radius: 5px;

  display: inline-block;

  margin: 0 6px;

  vertical-align: top;

}

.item-add-story {

  text-decoration: none;

  text-align: left;

  color: #fff;

  position: relative;

  max-height: 160px;

  display: block;

}

.add-story-preview {

  display: block;

  box-sizing: border-box;

  font-size: 0;

  max-height: 160px;

  height: 48vw;

  overflow: hidden;

  transition: transform .2s;

}

.add-story-preview img {

  display: block;

  box-sizing: border-box;

  height: 100%;

  width: 100%;

  background-size: cover;

  background-position: 50%;

  object-fit: cover;

  border-radius: 5px;

  position: absolute;

}

.item-add-story > .info {

  top: auto;

  height: auto;

  box-sizing: border-box;

  position: absolute;

  left: 0;

  right: 0;

  bottom: 0;

  padding: 6px;

  font-weight: 700;

  font-size: 12px;

  text-shadow: 1px 1px 1px rgb(0 0 0 / 35%), 1px 0 1px rgb(0 0 0 / 35%);

  display: inline-block;

  margin-top: 0.5em;

  line-height: 1.2em;

  width: 100%;

  overflow: hidden;

  text-overflow: ellipsis;

  border-bottom-left-radius: 5px;

  border-bottom-right-radius: 5px;

  z-index: 10;

}

.item-add-story > .info > .name {

  font-weight: 500;

}

.delete-history {

  position: absolute;

  top: 5px;

  right: 5px;

  padding: 2px 6px;

  background-color: #00000096!important;

  color: #fff;

  z-index: 2;

  border-radius: 6px;

  z-index: 10;

}

.bg-dark-transparent {

  background-color: #00000096!important;

}

.storyBackgrounds, .fontColor {

  width: 30px;

  height: 30px;

  object-fit: cover;

  border-radius: 50px;

  cursor: pointer;

  display: inline-block;

}

.storyBackgrounds.active,

.storyBg:first-child,

.fontColor.active {

  border: 1px solid #fff;

  box-shadow: 0 0 0 3px <?php echo e($settings->color_default, false); ?>;

}

.fontColor-white {background-color: #fff;}

.fontColor-black {background-color: #000;}

.bg-current {

  border-radius: 10px;

  height: 700px;

}

.bg-inside {

  position: relative;

  overflow: auto;

  overflow-x: hidden;

  flex: 2;

  -webkit-box-flex: 2;

  border-radius: 10px;

  max-width: 400px;

  height: 650px;

}

.text-story {

  font-size: 24px;

  font-family: Arial;

  word-break: break-word;

  font-weight: bold;

  color: #fff;

}

.modalStoryViews {

  max-height: 400px;

}

.modalGifts {

  max-height: 600px;

}

.modal-text-story {

  color: #fff;

  position: absolute;

  top: 50%;

  left: 50%;

  z-index: 100;

  transform: translate(-50%, -50%);

  font-size: 24px;

  font-family: Arial;

  word-break: break-word;

  white-space: break-spaces;

  font-weight: bold;

  width: 45vh;

  text-align: center;

}

.action_user_info {
  display: flex;
  flex-direction: column;
  gap: 2px;
  min-width: 0;
  line-height: 1.15;
}

.action_user_heading {
  display: flex;
  align-items: center;
  gap: 6px;
  flex-wrap: wrap;
  min-width: 0;
}

.action_user_info strong {
  min-width: 0;
}

.action_user_info strong a {
  display: inline-block;
  max-width: 100%;
  overflow: hidden;
  text-overflow: ellipsis;
  vertical-align: top;
}

.action_user_info .verified {
  display: inline-flex;
  align-items: center;
  margin-top: 0 !important;
}

.action_user_info span {
  display: inline-flex;
  align-items: center;
  min-width: 0;
}

.action_user_info span small {
  display: block;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.live-span.live-span-inline {
  position: static;
  margin: 0;
  width: auto;
  min-width: 39px;
  padding: 0 6px;
  line-height: 17px;
}

.text-story-preview {

    color: #fff;

    position: absolute;

    top: 50%;

    left: 50%;

    z-index: 100;

    transform: translate(-50%, -50%);

    font-size: 5px;

    font-family: Arial;

    word-break: break-word;

    white-space: break-spaces;

    font-weight: bold;

    width: 10vh;

    text-align: center;

}

.profilePhoto, .info>.name {cursor: pointer;}

.profilePhoto {border-radius: 50%;}

.bg-black {background-color: #18191a!important;}

.border-2 {border: 2px solid #fff!important;}

.verified-story:after {

  font-family: "bootstrap-icons";

  display: inline-block;

  padding-left: 5px;

  content: "\F4B6";

  vertical-align: middle;

}
/*
.grecaptcha-badge {

  visibility: hidden;

}*/

.btn-post {

  width: 48px;

  height: 48px;

  padding: 0;

-webkit-transition: all 200ms linear;

  -moz-transition: all 200ms linear;

  -o-transition: all 200ms linear;

  -ms-transition: all 200ms linear;

  transition: all 200ms linear;

}

.btn-post:hover {

  background-color: #f3f3f3;

}

@media (max-width: 991px) {

  .btn-post {

    width: 40px;

    height: 40px;

  }

}

.icons-live {

  padding: 10px 12px;

-webkit-transition: all 200ms linear;

  -moz-transition: all 200ms linear;

  -o-transition: all 200ms linear;

  -ms-transition: all 200ms linear;

  transition: all 200ms linear;

}

.icons-live:hover {

  background-color: #f3f3f3;

}

.thumbnails-shop {

display: flex;

margin: 1rem auto 0;

padding: 0;

justify-content: center;

}





.thumbnail-shop {

width: 70px;

height: 70px;

overflow: hidden;

list-style: none;

margin: 0 0.2rem;

cursor: pointer;

opacity: 0.3;

}



.thumbnail-shop.is-active {

  opacity: 1;

}



.thumbnail-shop img {

width: 100%;

height: auto;

}

.buttonDisabled {

  opacity: .3;

  cursor: default;

}

.buttonDisabled:active > i {

  animation: none !important;

}

.container-full-w {

  max-width: 100% !important;

}

.font-12 {

  font-size: 12px;

}

.b-radio-custom {

  border-radius: 0.8rem !important;

}

.gslide-image {background-color: #fff !important;}

<?php if(auth()->check() && auth()->user()->dark_mode == 'on'): ?>

.card-updates .data-link {

  text-decoration: underline !important;

}

<?php endif; ?>



@-moz-document url-prefix() {

  .custom-scrollbar {

    scrollbar-color: #ccc transparent;

    scrollbar-width: thin;

  }



  .wrapper-msg-inbox {

    scrollbar-color: #ccc transparent;

    scrollbar-width: thin;

  }

}

<?php endif; ?>



<?php if(auth()->guard()->check()): ?>

.btn-radio {

  border-radius: 15px !important;

  margin: 0 10px 10px 0 !important;

  border: 1px solid <?php echo e(auth()->user()->dark_mode == 'on' ? '#5b5a5a' : '#ccc', false); ?>;

  color: <?php echo e(auth()->user()->dark_mode == 'on' ? '#fff' : '#212529', false); ?> !important;

}

.btn-radio.active {

  border-color:<?php echo e(auth()->user()->dark_mode == 'on' ? '#fff' : $settings->color_default, false); ?>;

}

.btn-group-radio > .btn:not(:disabled):not(.disabled).active {

  box-shadow: inset 0px 0px 0px 1px <?php echo e(auth()->user()->dark_mode == 'on' ? '#fff' : $settings->color_default, false); ?> !important;

}

<?php endif; ?>



@media (min-width: 992px) {
  .modern-navbar.site-header {
    padding: 10px 16px !important;
    min-height: 84px;
    margin: 12px 20px 0 !important;
    border-radius: 32px;
  }

  .modern-navbar.site-header .container-fluid {
    padding-left: 6px;
    padding-right: 6px;
    gap: 20px;
  }

  .modern-navbar.site-header .navbar-brand {
    margin-right: 14px;
    padding-top: 0;
    padding-bottom: 0;
  }

  .modern-navbar.site-header .logo {
    max-height: 44px;
    width: auto;
  }

  .modern-navbar.site-header .site-header-collapse {
    display: flex !important;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
  }

  .modern-navbar.site-header .site-header-search {
    flex: 0 0 280px;
    max-width: 280px;
    margin-right: 4px;
  }

  .modern-navbar.site-header .site-header-search .form-inline {
    width: 100%;
  }

  .modern-navbar.site-header .site-header-search .search-bar {
    width: 100%;
  }

  .modern-navbar.site-header .site-header-actions {
    flex: 1 1 auto;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 2px;
    margin-left: 0 !important;
  }

  .modern-navbar.site-header .site-header-actions .navbar_mid_link {
    padding-left: 10px !important;
    padding-right: 10px !important;
    white-space: nowrap;
  }
}

@media (min-width: 768px) and (max-width: 991.98px) {
  .modern-navbar.site-header {
    padding: 10px 14px !important;
    min-height: 84px;
    margin: 12px 20px 0 !important;
    border-radius: 32px;
  }

  .modern-navbar.site-header .container-fluid {
    padding-left: 6px;
    padding-right: 6px;
    gap: 16px;
  }

  .modern-navbar.site-header .logo {
    max-height: 42px;
    width: auto;
  }

  .modern-navbar.site-header .navbar-toggler,
  .modern-navbar.site-header .main_head_search,
  .modern-navbar.site-header .buttons-mobile-nav {
    display: none !important;
  }

  .modern-navbar.site-header .site-header-collapse.navbar-collapse {
    position: static !important;
    right: auto !important;
    top: auto !important;
    width: auto !important;
    height: auto !important;
    overflow: visible !important;
    background: transparent !important;
    box-shadow: none !important;
    padding: 0 !important;
    display: flex !important;
    flex-basis: auto !important;
    flex-grow: 1 !important;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .modern-navbar.site-header .site-header-collapse.collapse {
    display: flex !important;
  }

  .modern-navbar.site-header .site-header-search {
    display: flex !important;
    flex: 0 0 250px;
    max-width: 250px;
  }

  .modern-navbar.site-header .site-header-search .form-inline.d-none.d-lg-flex {
    display: flex !important;
    width: 100%;
  }

  .modern-navbar.site-header .site-header-search .search-bar {
    width: 100%;
  }

  .modern-navbar.site-header .site-header-actions {
    flex: 1 1 auto;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 2px;
    margin-left: 0 !important;
  }

  .modern-navbar.site-header .site-header-actions .nav-item.d-lg-block.d-none {
    display: block !important;
  }

  .modern-navbar.site-header .site-header-actions .nav-item.d-lg-none {
    display: none !important;
  }

  .modern-navbar.site-header .site-header-actions .navbar_mid_link {
    padding-left: 8px !important;
    padding-right: 8px !important;
    font-size: 14px;
    white-space: nowrap;
  }
}

</style>
