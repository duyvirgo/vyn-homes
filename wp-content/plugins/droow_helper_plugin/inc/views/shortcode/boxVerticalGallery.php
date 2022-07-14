<?php
$attr    = get_query_var( 'attr' );
$content = get_query_var( 'content' );


$shortCode = new DroowShortCode( $attr );


$caption_text = $shortCode->Caption();
$title        = $shortCode->Title();
$description  = $shortCode->Description();
$url_link     = $shortCode->UrlLink();

$arrayImage = array_filter( array(
    'class'          => $shortCode->parallaxType() ,
    'data-dsn-scale' => $shortCode->scale() ,
    'data-dsn-y'     => $shortCode->transY() ,
    'style'          => $shortCode->style()

) );


?>

<div class="box-gallery-vertical section-padding <?php echo esc_attr( $shortCode->className() . ' ' . $shortCode->styleVertical() ) ?>"
    <?php echo esc_attr( $shortCode->changeColor() ) ?> >
    <div class="mask-bg"></div>
    <div class="<?php echo esc_attr( $shortCode->layout() ) ?>">
        <div class="row align-items-center h-100">
            <div class="col-lg-6 ">
                <?php if ( $id_img = $shortCode->Image() ): ?>
                    <div class="box-im" data-dsn-grid="move-up"
                         data-overlay="<?php echo esc_attr( $shortCode->overlay() ) ?>"
                        <?php echo esc_attr( $shortCode->triggerhook() ) ?>
                        <?php echo esc_attr( $shortCode->duration() ) ?>>
                        <?php
                        echo wp_get_attachment_image( $id_img  , $shortCode->sizeImage() , false , $arrayImage );
                        ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-6">
                <div class="box-info">

                    <?php if ( $shortCode->TitleCover() ): ?>
                        <div class="title-cover" data-dsn-grid="move-section" data-dsn-opacity="0.1"
                             data-dsn-duration="170%" data-dsn-move="0%">
                            <?php echo esc_html( $shortCode->TitleCover() ); ?>
                        </div>
                    <?php endif; ?>

                    <?php

                    if ( $title )
                        printf( '<div class="vertical-title"><h2 %s>%s</h2></div>' , $shortCode->titleAnimate() , $title );
                    if ( $content )
                        printf( '<h6 %s>%s</h6>' , $shortCode->DescriptionAnimate() , $content );

                    if ( $description )
                        printf( '<p %s>%s</p>' , $shortCode->DescriptionAnimate() , $description );


                    if ( $url_link ): ?>
                        <div class="link-custom" data-dsn-animate="up">
                            <a class="image-zoom effect-ajax" href="<?php echo esc_url( $url_link ) ?>"
                               data-dsn="parallax">
                                <span><?php echo esc_html( $shortCode->TitleLink( 'View More' ) ) ?></span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>

    </div>
</div>





