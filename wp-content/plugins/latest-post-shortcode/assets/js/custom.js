/**
 * Primary JS file for the Latest Post Shortcode plugin.
 *
 * Localized vars available as lpsGenVars (see the main PHP class where we enqueue the script).
 */

(function ($) {
  window.LPS_generator = {
    config: {},
    init: function () {
      LPS_generator.initEvents();
    },
    initEvents: function () {
      //LPS_generator.sectionsSetup();
    },
    // Functions go here.
    resize: function() {
      var $content_wrap = $(window);
      var ww = parseInt($content_wrap.innerWidth());
      var hh = parseInt($content_wrap.innerHeight());

      var lpsw = ( ww > 800 ) ? 800 - 40 : ww - 40;
      var lpsh = hh - 40;

      var $p = $('#lps_shortcode_popup_container');
      $p.css({
        'left': parseInt(ww - lpsw) / 2 + 'px',
        'overflow-x': 'hidden',
        'width': lpsw + 'px',
        'max-width': lpsw + 'px',
        'height': lpsh + 'px',
        'max-height': lpsh + 'px',
      });

      var sheight = lpsh - 20;
      if ( ww <= 600 ) {
        sheight = lpsh - 20 - $('#lps_shortcode_popup_container .shortcode-preview').innerHeight();
      }
      $('#lps_shortcode_popup_container .shortcode-settings .inner').css({
      'height': sheight + 'px',
      });
    },

    openPopup: function () {
      lpsCurrentEditorId = '';
      if (typeof lpsCurrentEditor.id != 'undefined') {
        if (lpsCurrentEditor.id.indexOf('elementor') >= 0) {
          // Elementor editor support (it works differently).
          lpsCurrentEditorId = lpsCurrentEditor.id;
        }
      }
      LPS_generator.resize();
      $('#lps_shortcode_popup_container_bg').show();
      $('#lps_shortcode_popup_container').show();
      LPS_generator.initShortcode(lpsCurrentEditor);
    },

    initShortcode: function (ed) {
      var selected = '';
      if (lpsCurrentEditorId != '') {
        selected = $('#' + lpsCurrentEditorId).val();
      } else {
        if ( lpsCurrentEditorType === 'tinymce' ) {
          selected = ed.getContent();
        } else if (lpsCurrentEditorType === 'qtags') {
          try {
            selected = ed.getContent();
          } catch (e) {
            if (e instanceof TypeError) {
              selected = $('#content').val();
            } else {
              selected = '';
            }
          }
        } else {
          selected = $('#content').val();
        }
      }
      if (typeof selected == 'undefined') {
        selected = '';
      }

      var code = selected.match(/\[latest-selected-content\s(.*?)?\]/);
      if ( null == code) {
        code = '';
      } else {
        code = code[1];
      }

      var selPar = '#lps_shortcode_popup_container ';
      var c = code.replace('[latest-selected-content ', '');
      c = c.replace(']', '');
      var newTxt = c.match(/[\w-]+=\"[^\"]*\"/g);
      if (newTxt) {
        for (var i = 0; i < newTxt.length; i++) {
          var k = newTxt[i].split('=')[0];
          var v = newTxt[i].split('=')[1].replace('"', '');
          v = v.replace('"', '');
          v = v.replace(']', '');
          switch (k) {
            // These are 1:1 prefix + id with attribute name.
            case 'limit' :
            case 'date_limit' :
            case 'date_after' :
            case 'date_before' :
            case 'date_start' :
            case 'date_start_type' :
            case 'offset' :
            case 'showpages' :
            case 'display' :
            case 'titletag' :
            case 'chrlimit' :
            case 'image' :
            case 'image_placeholder' :
            case 'url' :
            case 'linktext' :
            case 'loadtext' :
            case 'lightbox_size' :
            case 'lightbox_attr' :
            case 'lightbox_val' :
            case 'css' :
            case 'taxonomy' :
            case 'term' :
            case 'taxonomy2' :
            case 'term2' :
            case 'tag' :
            case 'dtag' :
            case 'orderby' :
            case 'output' :
            case 'slidermode' :
            case 'centermode' :
            case 'centerpadd' :
            case 'sliderauto':
            case 'sliderspeed':
            case 'slidersponsive' :
            case 'respondto' :
            case 'slideslides' :
            case 'slidescroll' :
            case 'sliderdots' :
            case 'sliderinfinite' :
            case 'slideoverlay' :
            case 'slidegap' :
            case 'sliderbreakpoint_tablet' :
            case 'slideslides_tablet' :
            case 'slidescroll_tablet' :
            case 'sliderdots_tablet' :
            case 'sliderinfinite_tablet' :
            case 'sliderbreakpoint_mobile' :
            case 'slideslides_mobile' :
            case 'slidescroll_mobile' :
            case 'sliderdots_mobile' :
            case 'sliderinfinite_mobile' :
            case 'sliderheight' :
            case 'slidermaxheight' :
            case 'slidercontrols' :
            case 'sliderspeed' :
              $(selPar + '#lps_' + k).val(v);
              break;

            // These are somehow different.
            case 'perpage' :
              $(selPar + '#lps_per_page').val(v);
              break;
            case 'pagespos' :
              $(selPar + '#lps_showpages_pos').val(v);
              break;
            case 'type' :
              $(selPar + '#lps_post_type').val(v);
              break;
            case 'elements' :
              $(selPar + '#lps_elements').val(v);
              $(selPar + '#lps_elements_img_' + v).prop("checked", true);
              break;
            case 'id' :
              $(selPar + '#lps_post_id').val(v);
              break;
            case 'parent' :
              $(selPar + '#lps_parent_id').val(v);
              break;
            case 'author' :
              $(selPar + '#lps_author_id').val(v);
              break;
            case 'excludeid' :
              $(selPar + '#lps_excludepost_id').val(v);
              break;
            case 'excludeauthor' :
              $(selPar + '#lps_excludeauthor_id').val(v);
              break;
            case 'exclude_tags' :
              $(selPar + '#lps_exclude_tags').val(v);
              break;
            case 'exclude_categories' :
              $(selPar + '#lps_exclude_categories').val(v);
              break;

            case 'show_extra' :
              var p = v.split(',');
              for (jj = 0; jj <= p.length; jj++) {
                if (typeof $(selPar + '#lps_show_extra_' + p[jj]) != 'undefined') {
                  $(selPar + '#lps_show_extra_' + p[jj]).prop('checked', true);
                }
              }
              break;
            case 'status' :
              var p = v.split(',');
              for (jj = 0; jj <= p.length; jj++) {
                if (typeof $(selPar + '#lps_status_' + p[jj]) != 'undefined') {
                  $(selPar + '#lps_status_' + p[jj]).prop('checked', true);
                }
              }
              break;

            default :
              break;
          }
          if (( $(selPar + '#lps_offset').val() != 0 || $(selPar + '#lps_per_page').val() != 0 )) {
            $(selPar + '#lps_pagination_options').show();
            $(selPar + '#lps_use_pagination').val('yes');
          } else {
            $(selPar + '#lps_pagination_options').hide();
          }

          if ($(selPar + '#lps_output').val() == 'slider') {
            $(selPar + '#lps_display_slider').show();
          } else {
            $(selPar + '#lps_display_slider').hide();
          }
        }
      }
      LPS_generator.configureShortcode();
    },

    configureShortcode: function () {
      // Define the main selector.
      var selPar = '#lps_shortcode_popup_container ';
      var sc = '[latest-selected-content';

      // Shortcode output type.
      var output = $(selPar + '#lps_output').val();
      if (output === 'slider') {
        sc += ' output="' + output + '"';
      }

      // Posts limit.
      var use_pags = $(selPar + '#lps_use_pagination').val();
      if (use_pags == '') {
        sc = LPS_generator.append(sc, selPar, '#lps_limit', 'limit', true);
      }

      // Maybe compute pagination if the output is not a slider.
      if (output != 'slider') {
        sc = LPS_generator.computePagination(selPar, sc);
      }

      // Output as tiles.
      if (output != 'slider') {
        var display = $(selPar + '#lps_display').val();
        if (display != '') {
          sc += ' display="' + display + '"';
          if(display.indexOf('title') < 0) {
            $(selPar + '#lps_display_titletag').hide();
          } else {
            $(selPar + '#lps_display_titletag').show();
            sc = LPS_generator.append(sc, selPar, '#lps_titletag', 'titletag', true);
          }
          if (display.indexOf('content') >= 0) {
              $(selPar + '#lps_display_raw').show();
              LPS_generator.elementEnable(selPar + '#lps_show_extra_raw');
            } else {
              LPS_generator.elementDisableUncheck(selPar + '#lps_show_extra_raw');
              $(selPar + '#lps_display_raw').hide();
            }
          if(display.indexOf('_custom_') === 0) {
            $(selPar + '#lps_url_wrap').hide();
            $(selPar + '#custom_tile_description_wrap').show();
            $(selPar + '#tile_description_wrap').hide();
            $(selPar + '#lps_image_wrap').hide();
            $(selPar + '#lps_display_limit').hide();
            $(selPar + '#lps_display_date_diff').hide();
            $(selPar + 'label.without-link').hide();
            $(selPar + 'label.with-link').hide();
            $(selPar + 'label.custom-type').show();
            var template_id = $(selPar + '#lps_display option:selected').data('template-id');
            $(selPar + '#lps_elements').val(template_id);
            $(selPar + '#lps_elements_img_' + template_id).prop('checked', true);
          } else {
            $(selPar + '#lps_url_wrap').show();
            $(selPar + '#custom_tile_description_wrap').hide();
            $(selPar + '#tile_description_wrap').show();
            $(selPar + '#lps_image_wrap').show();
            $(selPar + 'label.custom-type').hide();
            if (display.indexOf('excerpt-small') >= 0 || display.indexOf('content-small') >= 0) {
              $(selPar + '#lps_display_limit').show();
              sc = LPS_generator.append(sc, selPar, '#lps_chrlimit', 'chrlimit', true);
            } else {
              $(selPar + '#lps_display_limit').hide();
            }
            if (display.indexOf('date') >= 0) {
              $(selPar + '#lps_display_date_diff').show();
              LPS_generator.elementEnable(selPar + '#lps_show_extra_date_diff');
            } else {
              LPS_generator.elementDisableUncheck(selPar + '#lps_show_extra_date_diff');
              $(selPar + '#lps_display_date_diff').hide();
            }
          }
        }
        $(selPar + '#lps_lightbox_options').hide();

        var type = $(selPar + '#lps_post_type').val();
        var url = $(selPar + '#lps_url').val();
        if ('attachment' != type ) {
          $(selPar + '#lps_url option[value="yes_media"]').attr('disabled', true);
          $(selPar + '#lps_url option[value="yes_media_blank"]').attr('disabled', true);
          $(selPar + '#lps_url option[value="yes_media_lightbox"]').attr('disabled', true);
          if(url == 'yes_media' || url == 'yes_media_blank' || url == 'yes_media_lightbox' ) {
            url = '';
            $(selPar + '#lps_url').val('');
          }
        } else {
          $(selPar + '#lps_url option[value="yes_media"]').attr('disabled', false);
          $(selPar + '#lps_url option[value="yes_media_blank"]').attr('disabled', false);
          $(selPar + '#lps_url option[value="yes_media_lightbox"]').attr('disabled', false);
        }

        if(display.indexOf('_custom_') !== 0) {
          $(selPar + 'label.custom-type').hide();
          $(selPar + '#custom_tile_description_wrap').hide();
          $(selPar + '#tile_description_wrap').show();
          var url = $(selPar + '#lps_url').val();
          var type = $(selPar + '#lps_post_type').val();
          if (url != '') {
            sc += ' url="' + url + '"';
            if ('yes_media_lightbox' == url && 'attachment' == type ) {
              $(selPar + '#lps_lightbox_options').show();
              sc = LPS_generator.append(sc, selPar, '#lps_lightbox_size', 'lightbox_size', true);
              sc = LPS_generator.append(sc, selPar, '#lps_lightbox_attr', 'lightbox_attr', true);
              sc = LPS_generator.append(sc, selPar, '#lps_lightbox_val', 'lightbox_val', true);
            } else {
              $(selPar + '#lps_lightbox_options').hide();
            }
            $(selPar + '#lps_url_options').show();
            $(selPar + 'label.without-link').hide();
            $(selPar + 'label.with-link').show();
            sc = LPS_generator.append(sc, selPar, '#lps_linktext', 'linktext', true);
          } else {
            $(selPar + '#lps_url_options').hide();
            $(selPar + 'label.with-link').hide();
            $(selPar + 'label.without-link').show();
          }

          // Adjust checked template.
          var sel_elems = $('#lps_shortcode_popup_container #lps_elements').val();
          var $viz_elems = $(selPar + 'input[name="lps_elements_img"]:visible' );
          if (! $(selPar + '#lps_elements_img_' + sel_elems + '' ).is(':visible')){
            sel_elems = $viz_elems.first().val();
          }
          $(selPar + '#lps_elements_img_' + sel_elems ).attr('checked', 'checked');
          $('#lps_shortcode_popup_container #lps_elements').val(sel_elems);
        } else {
          $(selPar + 'label.custom-type').show();
          $(selPar + '#custom_tile_description_wrap').show();
          $(selPar + '#tile_description_wrap').hide();
        }
      }

      if ($(selPar + '#lps_date_limit').val() == 1) {
        $(selPar + '#lps_date_limit_options_0').hide();
        $(selPar + '#lps_date_limit_options_1').show();
        sc = LPS_generator.append(sc, selPar, '#lps_date_limit', 'date_limit', true);
        sc = LPS_generator.append(sc, selPar, '#lps_date_start', 'date_start', true);
        sc = LPS_generator.append(sc, selPar, '#lps_date_start_type', 'date_start_type', true);
      } else {
        $(selPar + '#lps_date_limit_options_0').show();
        $(selPar + '#lps_date_limit_options_1').hide();
        sc = LPS_generator.append(sc, selPar, '#lps_date_after', 'date_after', true);
        sc = LPS_generator.append(sc, selPar, '#lps_date_before', 'date_before', true);
      }

      sc = LPS_generator.append(sc, selPar, '#lps_image', 'image', true);
      sc = LPS_generator.append(sc, selPar, '#lps_image_placeholder', 'image_placeholder', true);

      if ( output != 'slider' ) {
        sc = LPS_generator.append(sc, selPar, '#lps_elements', 'elements', true);
      }

      sc = LPS_generator.append(sc, selPar, '#lps_css', 'css', true);

      // Compute the content filters.
      sc = LPS_generator.computeFilters(selPar, sc);

      // Maybe compute extra options if the output is not a slider.
      if (output != 'slider') {
        sc = LPS_generator.computeExtra(selPar, sc);
      }

      if ( output == 'slider' ) {
        LPS_generator.toggleBlocks(selPar, 'slider');
        $(selPar + '#lps_display_slider').show();
        $(selPar + '#lps_display_date_diff').hide();
        var slidermode = $(selPar + '#lps_slidermode').val();
        sc += ' slidermode="'+slidermode+'"';
        LPS_generator.sliderModeToggle(selPar, slidermode);
        var centermode = $(selPar + '#lps_centermode').val();
        if ('' != centermode) {
          sc += ' centermode="'+centermode+'"';
          $(selPar + '#lps_centermode_options').show();
          sc = LPS_generator.append(sc, selPar, '#lps_centerpadd', 'centerpadd', false);
          LPS_generator.elementDisableVal(selPar + '#lps_slidescroll', 1);
          LPS_generator.elementDisableVal(selPar + '#lps_slidescroll_tablet', 1);
          LPS_generator.elementDisableVal(selPar + '#lps_slidescroll_mobile', 1);
        } else {
          $(selPar + '#lps_centermode_options').hide();
          LPS_generator.elementEnable(selPar + '#lps_slidescroll');
          LPS_generator.elementEnable(selPar + '#lps_slidescroll_tablet');
          LPS_generator.elementEnable(selPar + '#lps_slidescroll_mobile');
        }
        sc = LPS_generator.append(sc, selPar, '#lps_slideslides', 'slideslides', false);
        sc = LPS_generator.append(sc, selPar, '#lps_slidescroll', 'slidescroll', false);
        sc = LPS_generator.append(sc, selPar, '#lps_sliderdots', 'sliderdots', true);
        sc = LPS_generator.append(sc, selPar, '#lps_sliderinfinite', 'sliderinfinite', true);

        var slidersponsive = $(selPar + '#lps_slidersponsive').val();
        if ('' != slidersponsive) {
          sc += ' slidersponsive="'+slidersponsive+'"';
          $(selPar + '#lps_slidersponsive_options').show();
          sc = LPS_generator.append(sc, selPar, '#lps_respondto', 'respondto', true);
          sc = LPS_generator.append(sc, selPar, '#lps_sliderbreakpoint_tablet', 'sliderbreakpoint_tablet', false);
          sc = LPS_generator.append(sc, selPar, '#lps_slideslides_tablet', 'slideslides_tablet', false);
          sc = LPS_generator.append(sc, selPar, '#lps_slidescroll_tablet', 'slidescroll_tablet', false);
          sc = LPS_generator.append(sc, selPar, '#lps_sliderdots_tablet', 'sliderdots_tablet', true);
          sc = LPS_generator.append(sc, selPar, '#lps_sliderinfinite_tablet', 'sliderinfinite_tablet', true);

          sc = LPS_generator.append(sc, selPar, '#lps_sliderbreakpoint_mobile', 'sliderbreakpoint_mobile', false);
          sc = LPS_generator.append(sc, selPar, '#lps_slideslides_mobile', 'slideslides_mobile', false);
          sc = LPS_generator.append(sc, selPar, '#lps_slidescroll_mobile', 'slidescroll_mobile', false);
          sc = LPS_generator.append(sc, selPar, '#lps_sliderdots_mobile', 'sliderdots_mobile', true);
          sc = LPS_generator.append(sc, selPar, '#lps_sliderinfinite_mobile', 'sliderinfinite_mobile', true);
        } else {
          $(selPar + '#lps_slidersponsive_options').hide();
        }
        sc = LPS_generator.append(sc, selPar, '#lps_slideoverlay', 'slideoverlay', true);
        sc = LPS_generator.append(sc, selPar, '#lps_slidegap', 'slidegap', true);
        var sliderheight = $(selPar + '#lps_sliderheight').val();
        if ('fixed' == sliderheight) {
          sc += ' sliderheight="'+sliderheight+'"';
          $(selPar + '#lps_sliderheight_options').show();
          sc = LPS_generator.append(sc, selPar, '#lps_slidermaxheight', 'slidermaxheight', true);
        } else {
          $(selPar + '#lps_sliderheight_options').hide();
        }
        sc = LPS_generator.append(sc, selPar, '#lps_slidercontrols', 'slidercontrols', true);
        var sliderauto = $(selPar + '#lps_sliderauto').val();
        if ('' != sliderauto) {
          sc += ' sliderauto="'+sliderauto+'"';
          $(selPar + '#lps_sliderauto_options').show();
          sc = LPS_generator.append(sc, selPar, '#lps_sliderspeed', 'sliderspeed', true);
        } else {
          $(selPar + '#lps_sliderauto_options').hide();
        }
        $(selPar + '#lps_display_limit').show();
        sc = LPS_generator.append(sc, selPar, '#lps_chrlimit', 'chrlimit', true);
        sc = LPS_generator.append(sc, selPar, '#lps_url', 'url', true);
      } else {
        $(selPar + '#lps_display_slider').hide();
        LPS_generator.toggleBlocks(selPar, 'tiles');
      }
      sc += ']';
      $(selPar + '#lps_preview_embed_shortcode').html(sc);
    },

    toggleBlocks: function (selPar, type) {
      if (type === 'slider') {
        $(selPar + '.block-use.available-for-slider').show();
        $(selPar + '.block-use').not('.available-for-slider').hide();
      } else {
        $(selPar + '.block-use.available-for-tiles').show();
        $(selPar + '.block-use').not('.available-for-tiles').hide();
      }
    },

    updateElements: function (v) {
      var $el = $('#lps_shortcode_popup_container #lps_elements');
      $el.val(v);
      LPS_generator.configureShortcode();
    },

    computePagination: function(selPar, sc) {
      var use_pagination = $(selPar + '#lps_use_pagination').val();
      var perpage = $(selPar + '#lps_per_page').val();
      var offset = $(selPar + '#lps_offset').val();
      var showpages = $(selPar + '#lps_showpages').val();
      var pagespos = $(selPar + '#lps_showpages_pos').val();
      var loadtext = $(selPar + '#lps_loadtext').val();
      $(selPar + '#lps_showpages_options').hide();
      if (use_pagination != '') {
        $(selPar + '#lps_pagination_options').show();
        if (perpage != 0) {
          sc += ' perpage="' + perpage + '"';
        }
        if (offset != 0) {
          sc += ' offset="' + offset + '"';
        }
        if (showpages != '') {
          sc += ' showpages="' + showpages + '"';
          LPS_generator.elementEnable(selPar + '#lps_show_extra_ajax_pagination');
          LPS_generator.elementEnable(selPar + '#lps_show_extra_pagination_all');

          if (showpages == 'more') {
            $(selPar + '#lps_showpages_options').show();
            pagespos = 1
            sc += ' loadtext="' + loadtext + '"';

            LPS_generator.elementDisableVal(selPar + '#lps_showpages_pos', pagespos);
            LPS_generator.elementDisableCheck(selPar + '#lps_show_extra_ajax_pagination');
            LPS_generator.elementDisableUncheck(selPar + '#lps_show_extra_pagination_all');
          } else {
            LPS_generator.elementEnable(selPar + '#lps_showpages_pos');
          }
          if (pagespos != '') {
            sc += ' pagespos="' + pagespos + '"';
          }
        } else {
          LPS_generator.elementDisableVal(selPar + '#lps_showpages_pos', '');
          LPS_generator.elementDisableUncheck(selPar + '#lps_show_extra_ajax_pagination');
          LPS_generator.elementDisableUncheck(selPar + '#lps_show_extra_pagination_all');
        }
      } else {
        $(selPar + '#lps_pagination_options').hide();
      }

      return sc;
    },

    computeExtra: function (selPar, sc) {
      var show_extra = $(selPar + '.lps_show_extra:checkbox, ' + selPar + '.lps_show_extra:radio').map(function () {
        var extrael = $(this).hasClass('lps-is-taxonomy');
        var estraelsel = $(this).is(":checked");
        var att = $(this).attr('id');
        if (estraelsel) {
          $(selPar + '#' + att + '_pos_wrap').show();
        } else {
          $(selPar + '#' + att + '_pos_wrap').find('input').first().attr('checked', 'checked');
          $(selPar + '#' + att + '_pos_wrap').hide();
        }
        return estraelsel ? $(this).val() : '';
      }).get();
      if (show_extra != '') {
        show_extra = show_extra.filter(function (e) {
          return e
        });
        if ($.inArray('hide_uncategorized_category', show_extra) >= 0
          && $.inArray('category', show_extra) < 0 ) {
          show_extra = show_extra.toString();
          show_extra = show_extra.replace(',hide_uncategorized_category', '');
          show_extra = show_extra.replace('hide_uncategorized_category,', '');
          show_extra = show_extra.replace('hide_uncategorized_category', '');
        }
        if (show_extra != '') {
          sc += ' show_extra="' + show_extra + '"';
        }
      }

      return sc;
    },

    computeFilters: function (selPar, sc) {
      sc = LPS_generator.append(sc, selPar, '#lps_post_type', 'type', true);

      var status = $(selPar + '.lps_status:checkbox').map(function () {
        return $(this).is(":checked") ? $(this).val() : '';
      }).get();
      if (status != '') {
        status = status.filter(function (e) {
          return e
        });
        if (status != '') {
          sc += ' status="' + status + '"';
        }
      }
      sc = LPS_generator.append(sc, selPar, '#lps_taxonomy', 'taxonomy', true);
      sc = LPS_generator.append(sc, selPar, '#lps_term', 'term', true);
      sc = LPS_generator.append(sc, selPar, '#lps_taxonomy2', 'taxonomy2', true);
      sc = LPS_generator.append(sc, selPar, '#lps_term2', 'term2', true);

      var dtag = $(selPar + '#lps_dtag').val();
      if (dtag != '') {
        sc += ' dtag="' + dtag + '"';
      } else {
        sc = LPS_generator.append(sc, selPar, '#lps_tag', 'tag', true);
      }

      sc = LPS_generator.append(sc, selPar, '#lps_post_id', 'id', true);
      sc = LPS_generator.append(sc, selPar, '#lps_parent_id', 'parent', true);
      sc = LPS_generator.append(sc, selPar, '#lps_author_id', 'author', true);
      sc = LPS_generator.append(sc, selPar, '#lps_excludepost_id', 'excludeid', true);
      sc = LPS_generator.append(sc, selPar, '#lps_excludeauthor_id', 'excludeauthor', true);
      sc = LPS_generator.append(sc, selPar, '#lps_exclude_tags', 'exclude_tags', true);
      sc = LPS_generator.append(sc, selPar, '#lps_exclude_categories', 'exclude_categories', true);
      sc = LPS_generator.append(sc, selPar, '#lps_orderby', 'orderby', true);

      return sc;
    },

    elementDisableVal: function (id, val) {
      $(id).val(val);
      $(id).attr('disabled', 'disabled');
    },

    elementDisableCheck: function (id) {
      $(id).attr('disabled', 'disabled');
      $(id).attr('checked', 'checked');
    },

    elementDisableUncheck: function (id) {
      $(id).attr('disabled', 'disabled');
      $(id).removeAttr('checked');
    },

    elementEnable: function (id) {
      $(id).removeAttr('disabled');
    },

    sliderModeToggle: function (selPar, slidermode) {
      if ('fade' == slidermode) {
        LPS_generator.elementDisableVal(selPar + '#lps_centermode', '');
        LPS_generator.elementDisableVal(selPar + '#lps_slidersponsive', '');
        LPS_generator.elementDisableVal(selPar + '#lps_slideslides', 1);
        LPS_generator.elementDisableVal(selPar + '#lps_slideslides_tablet', 1);
        LPS_generator.elementDisableVal(selPar + '#lps_slideslides_mobile', 1);
        LPS_generator.elementDisableVal(selPar + '#lps_slidescroll', 1);
        LPS_generator.elementDisableVal(selPar + '#lps_slidescroll_tablet', 1);
        LPS_generator.elementDisableVal(selPar + '#lps_slidescroll_mobile', 1);
      } else {
        LPS_generator.elementEnable(selPar + '#lps_centermode');
        LPS_generator.elementEnable(selPar + '#lps_slidersponsive');
        LPS_generator.elementEnable(selPar + '#lps_slideslides');
        LPS_generator.elementEnable(selPar + '#lps_slideslides_tablet');
        LPS_generator.elementEnable(selPar + '#lps_slideslides_mobile');
        LPS_generator.elementEnable(selPar + '#lps_slidescroll');
        LPS_generator.elementEnable(selPar + '#lps_slidescroll_tablet');
        LPS_generator.elementEnable(selPar + '#lps_slidescroll_mobile');
      }
    },

    append: function (sc, selPar, id, attr, ne) {
      var val = $(selPar + id).val();
      if (ne === true) {
        if (val != '' && val != 'undefined' && val) {
          sc += ' ' + attr + '="' + val + '"';
        }
      } else {
        sc += ' ' + attr + '="' + val + '"';
      }
      return sc;
    },

    navToMenu: function (to) {
      var $container = $('#lps_shortcode_popup_container .shortcode-settings .inner');
      var $elemTo = $(to);
      $container.scrollTop(
        $elemTo.offset().top - $container.offset().top + $container.scrollTop()
      );
      $('#menu-' + to.replace('#','')).addClass('selected');
      $('#lps_shortcode_popup_container_current_menu').val('#menu-' + to.replace('#',''));
    },

    embedShortcode: function () {
      var lps_val = $('#lps_shortcode_popup_container #lps_preview_embed_shortcode').html();
      if ( lpsCurrentEditorId != '' ) {
        lpsCurrentEditor.setContent(lps_val);
      } else {
        if ( lpsCurrentEditorType === 'tinymce' ) {
          lpsCurrentEditor.insertContent(lps_val);
        } else if (lpsCurrentEditorType === 'qtags') {
          QTags.insertContent(lps_val);
        } else {
          alert(lps_val);
        }
      }
      LPS_generator.closePopup();
    },

    closePopup: function() {
      $('body').css({'position': 'relative'});
      $('#lps_shortcode_popup_container').hide();
      $('#lps_shortcode_popup_container_bg').hide();
    },

    selectTypeImg: function(val) {
      $('#lps_output').val(val);
      LPS_generator.configureShortcode();
    },

    isScrolledIntoView: function(elem) {
      var docViewTop = $(window).scrollTop();
      var docViewBottom = docViewTop + $(window).height();
      var elemTop = $(elem).offset().top;
      var elemBottom = elemTop + $(elem).height();
      return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    },

    scrollMenu: function() {
      var $menu = $('#lps_shortcode_popup_container .lps-ui-menu');
      var $elems = $('.settings-group');
      $elems.each(function(){
        var menuid = '#menu-' + $(this).attr('id');
        if ( LPS_generator.isScrolledIntoView($(this)) ) {
          $menu.find(menuid).addClass('selected');
          $('#lps_shortcode_popup_container_current_menu').val(menuid);
        } else {
          $menu.find(menuid).removeClass('selected');
        }
      });
      var cm = $('#lps_shortcode_popup_container_current_menu').val();
      $menu.find(cm).addClass('selected');
    }
  };

  // Init and setup functions.
  $(document).ready(function () {
    LPS_generator.init();
    if(window.attachEvent) {
      window.attachEvent('onresize', function() {
        LPS_generator.resize();
      });
    } else if (window.addEventListener) {
      window.addEventListener('resize', function() {
        LPS_generator.resize();
      }, true);
    } else {
      // Bummer, the browser does not support Javascript event binding.
    }

    $('#lps_shortcode_popup_container .shortcode-settings .inner').on('scroll', function() {
      LPS_generator.scrollMenu();
    });
  });

  // Hookup the button with the tinymce.
  $(document).on( 'tinymce-editor-setup', function( event, editor ) {
    editor.settings.toolbar1 += ',lpsbutton';
    editor.addButton( 'lpsbutton', {
      image: lpsGenVars.icon,
      title: lpsGenVars.title,
      onclick: function () {
        lpsCurrentEditor = editor;
        lpsCurrentEditorType = 'tinymce';
        lpsCurrentEditorId = '';
        LPS_generator.openPopup();
      }
    });
  });

  $(document).ready(function() {
    // Hookup the button into the Quick Tags if possible.
    if ( typeof QTags !== 'undefined' ) {
      QTags.addButton(
        'code_shortcode',
        'LPS',
        lps_qtags_callback
      );
      function lps_qtags_callback() {
        lpsCurrentEditor = QTags;
        lpsCurrentEditorType = 'qtags';
        lpsCurrentEditorId = '';
        LPS_generator.openPopup();
      }
    }
  });

})(jQuery);

function lpsRefresh() {
  LPS_generator.configureShortcode();
}
function selectTypeImg(val) {
  LPS_generator.selectTypeImg(val);
}
function lpsMenu(to) {
  LPS_generator.navToMenu(to);
}
function lpsEmbed() {
  LPS_generator.embedShortcode();
}
function lpsClose() {
  LPS_generator.closePopup();
}
function lpsOpenConfigurator() {
  LPS_generator.openPopup();
}
