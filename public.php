<?php
/**
 * Public side data collections and data manipulation class
 *
 * @package licence-verification-api
 */

class LICENCE_VERIFICATION_API_ADMIN {
    public function __construct() {
        $this->setup_hooks();
    }
    public function setup_hooks() {
        // Load the translation.
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'init', [ $this, 'style' ] );
        add_action( 'init', [ $this, 'scripts' ] );
        add_action( 'wp_ajax_licence_verification_api_status_toggle', [ $this, 'toggle' ] );
        add_action( 'wp_ajax_nopriv_licence_verification_api_status_toggle', [ $this, 'toggle' ] );

        add_action( 'init', [ $this, 'refer' ] );
    }
    public function i18n() {
        load_plugin_textdomain( 'licence-verification-api' );
    }
    public function menu() {
        add_menu_page( 
        'Licences API', 
        'Licences', 
        'edit_posts', 
        'licences', 
        [ $this, 'dashboard' ], 
        'dashicons-shield'
        );
        add_submenu_page( 'admin.php?page=licences', __( 'Add New Licence', 'domain' ), 'Add New', 'edit_posts', 'edit-licence', [ $this, 'edit'] );
    }
    public function dashboard() {
        ?>
        <!-- Start Content-->
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card-field-table">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-5">
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=edit-licence' ) ); ?>" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Products</a>
                                </div>
                                <div class="col-sm-7">
                                    <div class="text-sm-end">
                                        <!-- <button type="button" class="btn btn-success mb-2 me-1"><i class="mdi mdi-cog-outline"></i></button> -->
                                        <button type="button" class="btn btn-light mb-2 me-1">Import</button>
                                        <button type="button" class="btn btn-light mb-2">Export</button>
                                    </div>
                                </div><!-- end col-->
                            </div>

                            <div class="table-responsive">
                                <table class="table table-centered w-100 dt-responsive nowrap" id="products-datatable">
                                    <thead class="table-light">
                                        <tr>
                                            <!-- <th class="all" style="width: 20px;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="customCheck1">
                                                    <label class="form-check-label" for="customCheck1">&nbsp;</label>
                                                </div>
                                            </th> -->
                                            <th class="all">Client</th>
                                            <th>Package</th>
                                            <th>Licence</th>
                                            <th>Product</th>
                                            <th>API</th>
                                            <th>Fired</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th style="width: 85px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach( $this->list() as $row ) {
                                                ?>
                                                <tr>
                                                    <!-- <td>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="serial-<?php echo esc_attr( $row[ 'SRL' ] ); ?>" name="serial[<?php echo esc_attr( $row[ 'SRL' ] ); ?>]">
                                                            <label class="form-check-label" for="serial-<?php echo esc_attr( $row[ 'SRL' ] ); ?>">&nbsp;</label>
                                                        </div>
                                                    </td> -->
                                                    <td>
                                                        <?php echo esc_html( $row[ 'fullname' ] ); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo esc_html( $this->package( $row[ 'label' ] ) ); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo esc_html( substr( $row[ 'ID' ], 0, 5 ) . '...' . substr( $row[ 'ID' ], -5, 5 ) ); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo esc_html( $row[ 'product' ] ); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo esc_html( $row[ 'API' ] ); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo esc_html( number_format_i18n( $row[ 'counted' ], 2 ) ); ?>
                                                    </td>
                                                    <td>
                                                        <div class="switcher r checkbox-9">
                                                            <input type="checkbox" class="checkbox" onchange="window.licenceStatus( 'sts', '<?php echo esc_attr( $row[ 'ID' ] ); ?>', this )" <?php echo esc_attr( ( ! $row[ 'lc_status' ] || $row[ 'lc_status' ] == 0 ) ? 'checked' : '' ); ?>>
                                                            <div class="knobs">
                                                                <span></span>
                                                            </div>
                                                            <div class="layer"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php echo esc_html( date( 'M d, Y', strtotime( $row[ 'createdon' ] ) ) ); ?>
                                                    </td>
                                                    <td class="table-action d-inline-flex">
                                                        <?php
                                                        $details = $row;
                                                        $details[ 'product' ] = $this->product( $row[ 'product' ] );
                                                        $details[ 'label' ] = $this->label( $row[ 'label' ] );
                                                        $details[ 'counted' ] = number_format_i18n( $row[ 'counted' ], 0 );
                                                        $details[ 'lc_type' ] = $this->package( $row[ 'lc_type' ] );
                                                        $details[ 'lc_status' ] = ( $row[ 'lc_status' ] == 1 ) ? 'Active' : 'Disabled';
                                                        ?>
                                                        <a href="javascript:void(0);" class="action-icon" data-details="<?php echo esc_attr( json_encode( $details ) ); ?>" onclick="window.licenceStatus( 'see', '<?php echo esc_attr( $row[ 'ID' ] ); ?>', this )"> <i class="dashicons dashicons-visibility"></i></a>
                                                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=edit-licence&id=' . $row[ 'ID' ] ) ); ?>" class="action-icon"> <i class="dashicons dashicons-edit"></i></a>
                                                        <a href="javascript:void(0);" class="action-icon" onclick="window.licenceStatus( 'del', '<?php echo esc_attr( $row[ 'ID' ] ); ?>', this )"> <i class="dashicons dashicons-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
            <!-- end row -->    

        </div>
        <!-- container -->
        <?php
    }
    public function toggle() {
        global $wpdb;
        $licence = $_POST[ 'licence' ];$status = $_POST[ 'status' ];
        $res = true;
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE licenses SET lc_status = %s WHERE ID = %s",
                [
                    ( $status == 'on' ) ? 1 : 0,
                    $licence
                ]
            )
        );
        if( $res ) {
            wp_send_json_success( [ 'success' => true, 'message' => __( 'Successfully ' . ( ( $status == 'on' ) ? 'Reactivated' : 'Deactivated' ) ) ] );
        } else {
            wp_send_json_error( [ 'success' => false, 'message' => __( 'Faild to ' . ( ( $status == 'on' ) ? 'Reactivate' : 'Deactivate' ) ), 'msgdev' => $wpdb->print_error(), 'post' => $_POST ] );
        }
    }
    public function style() {
        // wp_enqueue_style( 'licence-verification-api', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ) ) ) . '/style.css', [], filemtime( plugin_dir_path( __FILE__ ) . '/style.css'), 'all' );
        // wp_enqueue_style( 'bootstrap-switch', 'https://unpkg.com/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css', [], 'all' );
    }
    public function scripts() {
        // wp_enqueue_script( 'bootstrap-switch', 'https://unpkg.com/bootstrap-switch', [ 'jquery' ], true );
        // wp_enqueue_script( 'licence-verification-api', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ) ) ) . '/scripts.js', [ 'jquery' ], filemtime( plugin_dir_path( __FILE__ ) . '/scripts.js'), true );
    }
    public function refer() {
        global $wpdb;
        $refer = isset( $_GET[ 'ref' ] ) ? $_GET[ 'ref' ] : ( isset( $_GET[ 'ad' ] ) ? $_GET[ 'ad' ] : false );
        if( ! isset( $_GET[ 'ref' ] ) || empty( $_GET[ 'ref' ] ) ) {return;}
        $ad = urldecode( base64_decode( $_GET[ 'ref' ] ) );
        $arr = explode( '&', $ad );$rows = [];
        foreach( $arr as $i => $v ) {
            $arr[ $i ] = explode( '=', $v );
            if( ! isset( $arr[ $i ][ 1 ] ) ) {continue;}
            if( in_array( $arr[ $i ][ 0 ], [ 's', 'pl' ] ) ) {$arr[ $i ][ 1 ] = urldecode( $arr[ $i ][ 1 ] );}
            $rows[ $arr[ $i ][ 0 ] ] = $arr[ $i ][ 1 ];
        }
        $res = $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO {$WPlicenses} SET lc_status = %s WHERE ID = %s",
                [
                    ( $status == 'on' ) ? 1 : 0,
                    $licence
                ]
            )
        );
        if( $res ) {
            // session_start();
            $_SESSION[ 'developer_ref' ] = $rows;
            // wp_die( print_r( $rows ) );
        }
    }
    
}
new LICENCE_VERIFICATION_API_ADMIN();