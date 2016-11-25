<?php

//Shortcode para el formulario
function fpost_formshortcode($atts) {
  return fpost_form();
}

add_shortcode('formulario_postulacion', 'fpost_formshortcode');


function fpost_shareshortcode($atts) {
  global $post;
  $soctitle = get_post_meta($post->ID, 'rw_titulosocial', true);   
    $share['whatsapp'] = '<a target="_blank" href="whatsapp://send?text='.$post->post_title.' ' . get_permalink($post->ID).'" class="wa" title="Enviar por WhatsApp"><span class="fa-stack">
          <i class="fa fa-circle-o fa-stack-1x"></i>
          <i class="fa fa-phone fa-stack-1x"></i>
      </span></i></a>';
    $share['facebook'] = '<a target="_blank" class="fb" href="https://facebook.com/sharer.php?u='.get_permalink($post->ID).'" class="facebook"><i class="fa fa-facebook"></i></a>';
    //$share['twitter'] = '<a target="_blank" href="https://twitter.com/intent/tweet?url='.get_permalink($post->ID).'&text='.urlencode($soctitle).'" class="twt"><i class="fa fa-twitter"></i></a>';
    $share['gmas'] = '<a target="_blank" href="https://plus.google.com/share?url='.get_permalink($post->ID).'" class="gpl"><i class="fa fa-google-plus"></i></a>';
    $share = implode(' ', $share);
    $share = '<div class="sharing_toolbox">'.$share.'</div>';
    return $share;
}

add_shortcode('fpost_share', 'fpost_shareshortcode');

//shortcode para el botón
function fpost_buttonshortcode($atts) {
  $link = $atts['url'];
  $text = $atts['text'];
  return '<a href="'.$link.'" class="prepostbtn btn btn-lg btn-warning">'.$text.'</a>';
}

add_shortcode('fpost_btnform', 'fpost_buttonshortcode');
