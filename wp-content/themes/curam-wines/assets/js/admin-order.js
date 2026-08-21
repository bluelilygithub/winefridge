(function ($) {
  'use strict';

  $(function () {
    if (typeof cwAdminOrder === 'undefined') {
      return;
    }

    var $tbody = $('.wp-list-table tbody');
    if (!$tbody.length) {
      return;
    }

    $tbody.addClass('cw-order-enabled');

    var $status = $('<span class="cw-order-status" aria-live="polite"></span>');
    $('.wrap .wp-heading-inline, .wrap h1').first().after($status);

    function setStatus(text, cls) {
      $status.removeClass('is-saving is-saved is-error').addClass(cls).text(text);
    }

    $tbody.sortable({
      items: 'tr:not(.no-items, .inline-edit-row)',
      handle: '.cw-order-handle, .column-cw_order',
      axis: 'y',
      placeholder: 'ui-sortable-placeholder',
      helper: function (e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function (index) {
          $(this).width($originals.eq(index).width());
        });
        return $helper;
      },
      start: function (e, ui) {
        ui.placeholder.height(ui.item.outerHeight());
      },
      update: function () {
        var ids = [];
        $tbody.children('tr').each(function () {
          var id = parseInt(String(this.id).replace('post-', ''), 10);
          if (id) {
            ids.push(id);
          }
        });

        if (!ids.length) {
          return;
        }

        setStatus('Saving order…', 'is-saving');

        $.post(cwAdminOrder.ajaxUrl, {
          action: 'cw_save_post_order',
          nonce: cwAdminOrder.nonce,
          start: cwAdminOrder.start,
          ids: ids
        })
          .done(function (res) {
            if (res && res.success) {
              setStatus(cwAdminOrder.i18n.saved, 'is-saved');
            } else {
              setStatus(cwAdminOrder.i18n.error, 'is-error');
            }
          })
          .fail(function () {
            setStatus(cwAdminOrder.i18n.error, 'is-error');
          });
      }
    });
  });
})(jQuery);
