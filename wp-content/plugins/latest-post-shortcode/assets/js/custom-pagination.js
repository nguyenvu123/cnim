(function ($) {
  window.LPS_check_ajax_pagination = {
    config: {},
    init: function () {
      LPS_check_ajax_pagination.initEvents();
    },

    initEvents: function () {
      LPS_check_ajax_pagination.sectionsSetup();
    },

    sectionsSetup: function () {
      var $sections = $('.lps-top-section-wrap');
      $sections.each(function () {
        var $current = $(this);
        var $maybe_ajax = $current.find('.ajax_pagination').first();
        if (typeof $maybe_ajax === 'object' && $maybe_ajax.length) {
          var $a = $current.find('ul.latest-post-selection.pages li>a');
          var $pagination = $current.find('ul.latest-post-selection').first();
          $a.unbind('click');
          $a.on('click', function (e) {
            e.preventDefault();
            LPS_check_ajax_pagination.lpsNavigate(
              $current,
              $(this).data('page'),
              $current.data('args'),
              $current.data('current'),
              $pagination
            );
          });
        }
      });
    },

    lpsNavigate: function ($parent, page, args, current, $pagination) {
      $parent.addClass('loading-spinner');
      $.ajax({
        type: "POST",
        url: LPS.ajaxurl,
        data: {
          action: 'lps_navigate_to_page',
          page: page,
          args: args,
          current: current,
          lps_ajax: 1,
        },
        cache: false,
      }).done(function (response) {
        if ($pagination.length && $pagination.hasClass('lps-load-more')) {
          $pagination.remove();
          $parent.append(response);
        } else {
          $parent.html(response);
        }
        LPS_check_ajax_pagination.init();
        $parent.removeClass('loading-spinner');
        $(document.body).trigger('post-load');
      });
    }
  };

  $(document).ready(function () {
    LPS_check_ajax_pagination.init();
  });

})(jQuery);
