/* =========================================================================
   Magenta - image slot picker (Appearance > Magenta Media)
   Wraps the core media modal; no third-party field plugin involved.
   ========================================================================= */

(function ($) {
  'use strict';

  $(function () {
    $('.magenta-slot').each(function () {
      var $slot = $(this);
      var $input = $slot.find('[data-input]');
      var $preview = $slot.find('[data-preview]');
      var $remove = $slot.find('[data-remove]');
      var frame;

      $slot.find('[data-select]').on('click', function (event) {
        event.preventDefault();

        if (frame) {
          frame.open();
          return;
        }

        frame = wp.media({
          title: $slot.find('h3').text(),
          button: { text: 'Use this image' },
          library: { type: 'image' },
          multiple: false
        });

        frame.on('select', function () {
          var attachment = frame.state().get('selection').first().toJSON();
          var url = (attachment.sizes && attachment.sizes.medium)
            ? attachment.sizes.medium.url
            : attachment.url;

          $input.val(attachment.id);
          $preview.html($('<img>', { src: url, alt: '' }));
          $remove.prop('disabled', false);
          $slot.removeClass('is-empty').addClass('is-filled');
        });

        frame.open();
      });

      $remove.on('click', function (event) {
        event.preventDefault();
        $input.val('');
        $preview.html(
          $('<span>', {
            'class': 'magenta-slot__ph',
            text: $slot.data('slot').toString().replace(/_/g, ' ').toUpperCase()
          })
        );
        $(this).prop('disabled', true);
        $slot.removeClass('is-filled').addClass('is-empty');
      });
    });
  });
})(jQuery);
