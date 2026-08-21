(function ($) {
  'use strict';

  function bindBrochurePicker() {
    var $input = $('#cw_enquiry_brochure_id');
    var $preview = $('#cw-brochure-preview');
    var $remove = $('#cw-brochure-remove');
    var frame;

    if (!$input.length) {
      return;
    }

    $('#cw-brochure-select').on('click', function (e) {
      e.preventDefault();

      if (frame) {
        frame.open();
        return;
      }

      frame = wp.media({
        title: 'Select PDF brochure',
        button: { text: 'Use this PDF' },
        library: { type: 'application/pdf' },
        multiple: false
      });

      frame.on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();
        if (attachment.mime !== 'application/pdf') {
          window.alert('Please choose a PDF file.');
          return;
        }

        $input.val(attachment.id);
        $preview.html(
          '<p style="margin:0;"><span class="dashicons dashicons-media-document" style="vertical-align:text-bottom;"></span> ' +
          '<a href="' + attachment.url + '" target="_blank" rel="noopener noreferrer">' +
          (attachment.filename || attachment.title || 'Brochure') +
          '</a></p>'
        );
        $remove.show();
      });

      frame.open();
    });

    $remove.on('click', function (e) {
      e.preventDefault();
      $input.val('');
      $preview.empty();
      $remove.hide();
    });
  }

  $(bindBrochurePicker);
})(jQuery);
