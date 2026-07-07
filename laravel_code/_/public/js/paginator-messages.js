(function ($) {

  "use strict";

  var isLoadingMessages = false;
  var mobileBreakpoint = window.matchMedia('(max-width: 767.98px)');
  var inboxObserver = null;

  function getMessagesContainer() {
    return document.getElementById('messagesContainer');
  }

  function getNextButton() {
    return document.querySelector('#messagesContainer .paginatorMsg');
  }

  function cleanupLoaders() {
    $('.loadMoreMsgSpinner').remove();
  }

  function appendMessages(data, loadMoreContainer) {
    cleanupLoaders();
    if (loadMoreContainer && loadMoreContainer.length) {
      loadMoreContainer.remove();
    }
    $(data).appendTo('#messagesContainer');
    jQuery('.timeAgo').timeago();
    observeMobileSentinel();
  }

  function finishLoading(loadMoreContainer) {
    isLoadingMessages = false;
    cleanupLoaders();
    if (loadMoreContainer && loadMoreContainer.length) {
      loadMoreContainer.removeClass('d-none');
    }
  }

  function loadNextPage(button) {
    if (isLoadingMessages || !button) {
      return;
    }

    var dataUrl = button.getAttribute('data-url');

    if (!dataUrl || dataUrl.indexOf('page=') === -1) {
      return;
    }

    var page = dataUrl.split('page=')[1];

    var loadMoreContainer = $(button).closest('.messages-load-more-container');

    isLoadingMessages = true;
    loadMoreContainer.addClass('d-none');
    $('<div class="w-100 p-3 d-block text-center loadMoreMsgSpinner"><span class="spinner-border text-primary"></span></div>').appendTo('#messagesContainer');

    $.ajax({
      url: URL_BASE + '/messages?page=' + page,
    }).done(function (data) {
      if (data) {
        appendMessages(data, loadMoreContainer);
      } else {
        finishLoading(loadMoreContainer);
        $('.popout').html(error_reload_page).slideDown('500').delay('2500').slideUp('500');
      }
    }).fail(function () {
      finishLoading(loadMoreContainer);
      $('.popout').html(error_reload_page).slideDown('500').delay('2500').slideUp('500');
    }).always(function () {
      isLoadingMessages = false;
    });
  }

  function maybeLoadOnContainerScroll(element) {
    if (mobileBreakpoint.matches || isLoadingMessages) {
      return;
    }

    if (element.scrollTop + element.clientHeight >= element.scrollHeight - 20) {
      loadNextPage(getNextButton());
    }
  }

  function maybeLoadOnWindowScroll() {
    if (!mobileBreakpoint.matches || isLoadingMessages) {
      return;
    }

    var button = getNextButton();

    if (!button) {
      return;
    }

    var rect = button.getBoundingClientRect();
    var viewportHeight = window.innerHeight || document.documentElement.clientHeight;

    if (rect.top <= viewportHeight + 120) {
      loadNextPage(button);
    }
  }

  function observeMobileSentinel() {
    if (!mobileBreakpoint.matches || !('IntersectionObserver' in window)) {
      return;
    }

    if (inboxObserver) {
      inboxObserver.disconnect();
    }

    var sentinel = document.querySelector('#messagesContainer .messages-load-more-sentinel');
    var button = getNextButton();

    if (!sentinel || !button) {
      return;
    }

    inboxObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          loadNextPage(getNextButton());
        }
      });
    }, {
      root: null,
      rootMargin: '0px 0px 160px 0px',
      threshold: 0.1
    });

    inboxObserver.observe(sentinel);
  }

  $(document).on('click', '.paginatorMsg', function (event) {
    event.preventDefault();
    loadNextPage(this);
  });

  $('.wrapper-msg-inbox').on('scroll', function () {
    maybeLoadOnContainerScroll(this);
  });

  $(window).on('scroll resize', function () {
    maybeLoadOnWindowScroll();
  });

  observeMobileSentinel();
  maybeLoadOnWindowScroll();

})(jQuery);
