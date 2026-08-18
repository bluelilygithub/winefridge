(function ($) {
  'use strict';

  function bindVideoPicker() {
    var $input = $('#cw_video_id');
    var $preview = $('#cw-video-preview');
    var $remove = $('#cw-video-remove');
    var frame;

    $('#cw-video-select').on('click', function (e) {
      e.preventDefault();

      if (frame) {
        frame.open();
        return;
      }

      frame = wp.media({
        title: 'Select or upload video',
        button: { text: 'Use this video' },
        library: { type: 'video' },
        multiple: false
      });

      frame.on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();
        $input.val(attachment.id);
        $preview.html(
          '<video src="' + attachment.url + '" controls style="max-width:100%;max-height:200px;"></video>'
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

  function bindGalleryPicker() {
    var $input = $('#cw_gallery_ids');
    var $list = $('#cw-gallery-preview');
    var frame;

    function syncInput() {
      var ids = [];
      $list.find('li').each(function () {
        ids.push($(this).data('id'));
      });
      $input.val(ids.join(','));
    }

    $('#cw-gallery-select').on('click', function (e) {
      e.preventDefault();

      if (frame) {
        frame.open();
        return;
      }

      frame = wp.media({
        title: 'Add photos',
        button: { text: 'Add to gallery' },
        library: { type: 'image' },
        multiple: true
      });

      frame.on('select', function () {
        var selection = frame.state().get('selection');
        selection.each(function (attachment) {
          attachment = attachment.toJSON();
          if ($list.find('[data-id="' + attachment.id + '"]').length) {
            return;
          }
          var thumb = attachment.sizes && attachment.sizes.thumbnail
            ? attachment.sizes.thumbnail.url
            : attachment.url;
          $list.append(
            '<li data-id="' + attachment.id + '"><img src="' + thumb + '" alt=""></li>'
          );
        });
        syncInput();
      });

      frame.open();
    });

    $('#cw-gallery-clear').on('click', function (e) {
      e.preventDefault();
      $list.empty();
      syncInput();
    });

    $list.on('click', 'li', function () {
      if (confirm('Remove this photo from the gallery?')) {
        $(this).remove();
        syncInput();
      }
    });
  }

  $(function () {
    bindVideoPicker();
    bindGalleryPicker();
  });
})(jQuery);
