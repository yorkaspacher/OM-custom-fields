<?php
/*
 * Plugin Name: Custom Group Fields Plugin
 *
*/
add_filter( "dt_custom_fields_settings", "arrow_custom_fields", 1, 2 );
function arrow_custom_fields( array $fields, string $post_type = ""){
    //check if we are dealing with a contact
    if ( $post_type === "groups" ) {
        //check if the language field is already set
        if ( !isset( $fields["least-reached-category"] ) ) {
            //define the language field
            $fields["least-reached-category"] = [
                "name"    => __( "Least Reached Category", "disciple_tools_language" ),
                "type"    => "key_select",
                "default" => [
                    "no-wit"    => __( "No Witness; No Response", "disciple_tools_language" ),
                    "wit-nocom" => __( "Witness; No Community", "disciple_tools_language" ),
                    "com-iso"   => __( "Community but Isolated", "disciple_tools_language" )
                ]
            ];
        }
        if ( !isset( $fields["other-partners"] ) ) {
            $fields["other-partners"] = [
                "name" => __( "Other Partners", "disciple_tools_language" ),
                "type" => "text"
            ];
        }
        if ( !isset( $fields["your-contributions"] ) ) {
            $fields["your-contributions"] = [
                "name"    => __( "Your Contributions", "disciple_tools_language" ),
                "type"    => "key_select",
                "default" => [
                    "none" => __( "None", "disciple_tools_language" ),
                    "20"   => __( "20%", "disciple_tools_language" ),
                    "40"   => __( "40%", "disciple_tools_language" ),
                    "60"   => __( "60%", "disciple_tools_language" ),
                    "80"   => __( "80%", "disciple_tools_language" ),
                    "100"  => __( "100%", "disciple_tools_language" )
                ]
            ];
        }
    }
    return $fields;
}

add_filter( "dt_details_additional_section_ids", "arrow_declare_section_id", 999, 2 );
function arrow_declare_section_id( $sections, $post_type = "" ){
    //check if we are on a contact
    if ($post_type === "groups"){
        $group_fields = Disciple_Tools_Groups_Post_Type::instance()->get_custom_fields_settings();
        //check if the language field is set
        if ( isset( $group_fields["least-reached-category"] ) ){
            $sections[] = "arrow_addons";
        }
        //add more section ids here if you want...
    }
    return $sections;
}

add_action( "dt_details_additional_section", "arrow_add_section" );
function arrow_add_section( $section ){
    if ($section == "arrow_addons"){
        $group_id = get_the_ID();
        $group_fields = Disciple_Tools_Groups_Post_Type::instance()->get_custom_fields_settings();
        $group = Disciple_Tools_Groups::get_group( $group_id, true )
        ?>
        <!-- need you own css? -->
        <style type="text/css">
            .required-style {
                color: red
            }
        </style>

        <label class="section-header">
            <?php esc_html_e( 'Arrow Custom', 'disciple_tools' )?>
        </label>
        <div class="section-subheader">
            <?php esc_html_e( 'Least Reached Category', 'disciple_tools' )?> <span class="required-style">*</span>
        </div>
        <select class="select-field" id="least-reached-category" style="margin-bottom: 5px">
            <?php
            foreach ( $group_fields["least-reached-category"]["default"] as $key => $value ){
                if ( isset( $group["least-reached-category"]["key"] ) && $group["least-reached-category"]["key"] === $key ) {
                    ?>
                    <option value="<?php echo esc_html( $key ) ?>" selected><?php echo esc_html( $value["label"] ); ?></option>
                <?php } else { ?>
                    <option value="<?php echo esc_html( $key ) ?>"><?php echo esc_html( $value["label"] ); ?></option>
                <?php }
            }
            ?>
        </select>
        <div class="section-subheader">
          <?php esc_html_e( 'Other partners', 'disciple_tools' )?> <span class="required-style">*</span>
        </div>
        <input type="text" class="text-input" id="other-partners" style="margin-bottom:0px;width:100%;padding:10px;" />

        <div class="section-subheader">
            <?php esc_html_e( 'Your Contributions', 'disciple_tools' )?> <span class="required-style">*</span>
        </div>
        <select class="select-field" id="your-contributions" style="margin-bottom: 5px">
            <?php
            foreach ( $group_fields["your-contributions"]["default"] as $key => $value ){
                if ( isset( $group["your-contributions"]["key"] ) && $group["your-contributions"]["key"] === $key ) {
                    ?>
                    <option value="<?php echo esc_html( $key ) ?>" selected><?php echo esc_html( $value["label"] ); ?></option>
                <?php } else { ?>
                    <option value="<?php echo esc_html( $key ) ?>"><?php echo esc_html( $value["label"] ); ?></option>
                <?php }
            }
            ?>
        </select>


        <script type="application/javascript">
            //enter jquery here if you need it
            jQuery(($)=>{
            })
        </script>
        <?php
    }

    //add more sections here if you want...
}
