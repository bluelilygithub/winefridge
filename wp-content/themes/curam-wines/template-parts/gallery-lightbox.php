<?php
/**
 * Shared image/video lightbox shell (gallery page + post media).
 */
?>
<div class="cw-gallery-lightbox" id="cw-gallery-lightbox" hidden role="dialog" aria-modal="true" aria-labelledby="cw-gallery-lightbox-title" aria-hidden="true">
  <div class="cw-gallery-lightbox-inner">
    <button type="button" class="cw-gallery-lightbox-close" aria-label="Close">&times;</button>
    <button type="button" class="cw-gallery-lightbox-prev" aria-label="Previous image">&lsaquo;</button>
    <button type="button" class="cw-gallery-lightbox-next" aria-label="Next image">&rsaquo;</button>
    <figure class="cw-gallery-lightbox-figure">
      <img class="cw-gallery-lightbox-img" id="cw-gallery-lightbox-img" alt="" hidden>
      <video class="cw-gallery-lightbox-video" id="cw-gallery-lightbox-video" controls playsinline hidden></video>
    </figure>
    <div class="cw-gallery-lightbox-meta">
      <p class="cw-gallery-lightbox-caption">
        <span class="cw-gallery-lightbox-counter" id="cw-gallery-lightbox-counter"></span>
        <span class="cw-gallery-lightbox-type" id="cw-gallery-lightbox-type"></span>
        <span class="cw-gallery-lightbox-title" id="cw-gallery-lightbox-title"></span>
      </p>
      <a class="cw-gallery-lightbox-link" id="cw-gallery-lightbox-link" href="#" hidden>View details <span>&rarr;</span></a>
    </div>
  </div>
</div>
