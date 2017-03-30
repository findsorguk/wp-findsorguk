<?php
/**
 * Template used to display a single artefact figure.
 *
 * @package finds-org-uk-artefacts-and-coins
 */
?>

<figure class="fouaac-figure wp-caption alignnone">

    <img class="fouaac-size-medium size-medium"
         src="https://finds.org.uk/<?php esc_html_e( $this->get_artefact_record()->get_image_directory() );?>medium/<?php esc_html_e($this->get_artefact_record()->get_filename() ); ?>"
         alt="<?php esc_html_e( $this->get_caption_text_display() ); ?>
         (<?php esc_html_e( $this->get_artefact_record()->get_image_copyright_holder() ); ?>
         <?php _e( $this->get_artefact_record()->get_image_license_acronym() ); ?> 2.0)"
    >
    <small class="fouaac-copyright">
        <?php esc_html_e( $this->get_artefact_record()->get_image_copyright_holder() ); ?>
        <a href="https://creativecommons.org/licenses/<?php _e( strtolower( $this->get_artefact_record()->get_image_license_acronym() ) ); ?>/2.0/">
            <?php _e( $this->get_artefact_record()->get_image_license_acronym() ); ?> 2.0
        </a>
        <?php if ( 'none' == $this->get_caption_option() ): ?>
            (<a href="https://finds.org.uk/database/artefacts/record/id/<?php esc_html_e($this->get_artefact_record()->get_id());?>"><?php esc_html_e( $this->get_artefact_record()->get_old_find_id() );?></a>)
        <?php endif; ?>
    </small>
    <figcaption class="fouaac-caption-text wp-caption-text">
        <?php esc_html_e( $this->get_caption_text_display() ); ?>
        <?php if ( 'auto' == $this->get_caption_option() ): ?>
        (<a href="https://finds.org.uk/database/artefacts/record/id/<?php esc_html_e($this->get_artefact_record()->get_id());?>"><?php esc_html_e( $this->get_artefact_record()->get_old_find_id() );?></a>)
        <?php endif; ?>
    </figcaption>

</figure>


