<?php
/**
 * Admin templace and classes file
 *
 * @package licence-verification-api
 */

class LICENCE_VERIFICATION_API_ADMIN {
    public function __construct() {
        $this->setup_hooks();
    }
    public function setup_hooks() {
        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
        // Load the translation.
        add_action( 'init', [ $this, 'i18n' ] );
        add_action('admin_menu', [ $this, 'menu' ] );
        add_action( 'admin_init', [ $this, 'setup' ] );
        add_action( 'admin_init', [ $this, 'style' ] );
        add_action( 'admin_init', [ $this, 'scripts' ] );
        add_action( 'admin_post_save_new_licence_api', [ $this, 'save' ] );
        // add_action( 'wp_ajax_licence_verification_api_status_toggle', [ $this, 'toggle' ] );
        // add_action( 'wp_ajax_nopriv_licence_verification_api_status_toggle', [ $this, 'toggle' ] );
        add_action( 'wp_ajax_licence_verification_api_remove', [ $this, 'delete' ] );
        add_action( 'wp_ajax_nopriv_licence_verification_api_remove', [ $this, 'delete' ] );
    }
    public function activate() {
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                "CREATE TABLE IF NOT EXISTS licenses (
                SRL bigint(20) UNSIGNED NOT NULL,
                ID text NOT NULL,
                API text NOT NULL,
                product text NOT NULL,
                counted bigint(255) NOT NULL DEFAULT 0,
                fullname text DEFAULT NULL,
                email text DEFAULT NULL,
                lc_type text NOT NULL DEFAULT '0',
                label text NOT NULL DEFAULT '0',
                comments mediumtext NOT NULL DEFAULT '\'\'',
                createdon timestamp NOT NULL DEFAULT current_timestamp()
                ) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='This tables saved licenses which is varified by API';"
            )
        );
    }
    public function deactivate() {}
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
    public function edit() {
        global $wpdb;$data = [];$is_edit = false;
        if( isset( $_GET[ 'id' ] ) ) {
            $edit = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM licenses WHERE ID = %s", $_GET[ 'id' ] ), ARRAY_A );
            if( $edit && count( $edit ) >= 1 || isset( $edit[ 'SRL' ] ) ) {
                $data = $edit;
                $is_edit = true;
            }
        }
        // print_r( $data );
        ?>
        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="table-form data-form" method="POST">
            <?php wp_nonce_field( 'save_new_licence_api', 'save_new_licence_api_nonce', true, true ); ?>
            <?php
            if( $is_edit ) {
                ?>
                <input type="hidden" name="is_edit" value="<?php echo esc_attr( $data[ 'SRL' ] ); ?>">
                <?php
            } ?>
            <div class="row">
                <div class="col-lg-6">
                    <h5 class="mb-3">Details</h5>
                    <div class="form-floating mb-3">
                        <input type="text" name="licence[name]" class="form-control" placeholder="Jhon Due." value="<?php echo esc_attr( isset( $data[ 'fullname' ] ) ? $data[ 'fullname' ] : '' ); ?>">
                        <label for="floatingInput">Client Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" name="licence[email]" class="form-control" placeholder="name@example.com" value="<?php echo esc_attr( isset( $data[ 'email' ] ) ? $data[ 'email' ] : '' ); ?>" required>
                        <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="licence[social]" class="form-control" placeholder="https://facebook.com/mahmudremal" value="<?php echo esc_url( isset( $data[ 'social' ] ) ? $data[ 'social' ] : '' ); ?>">
                        <label for="floatingInput">A social link</label>
                    </div>

                </div>
                <div class="col-lg-6">
                    <h5 class="mb-3">Package</h5>
                    <div class="form-floating">
                        <select class="form-select" name="licence[package]" aria-label="Floating label select example" required>
                            <option>Select package type</option>
                            <?php
                            $package = $this->package();
                            foreach( $package as $i => $iname ) :
                            ?>
                            <option value="<?php echo esc_attr( $i ); ?>" <?php echo esc_attr( isset( $data[ 'lc_type' ] ) && ( $data[ 'lc_type' ] == $i ) ? 'selected' : '' ); ?>><?php echo esc_html( $iname ); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="floatingSelect">Package, select carefully</label>
                    </div>

                    <div class="form-floating mt-3">
                        <select class="form-select" name="licence[product]" aria-label="Floating label select example" required>
                            <option>Select Product type</option>
                            <?php
                            $product = $this->product();
                            foreach( $product as $i => $iname ) :
                            ?>
                            <option value="<?php echo esc_attr( $i ); ?>" <?php echo esc_attr( isset( $data[ 'product' ] ) && ( $data[ 'product' ] == $i ) ? 'selected' : '' ); ?>><?php echo esc_html( $iname ); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="floatingSelect">Product, select carefully</label>
                    </div>
                    <div class="form-floating mt-3">
                        <input type="text" class="form-control" name="licence[ID]" placeholder="Licence" value="<?php echo isset( $data[ 'ID' ] ) ? $data[ 'ID' ] : $this->licence(); ?>">
                        <label for="floatingPassword">Licence</label>
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="form-floating mt-3">
                        <select class="form-select" name="licence[status]" aria-label="Floating label select example" required>
                            <option>Status</option>
                            <?php
                            $Status = [ 'Active', 'Inactive' ];
                            foreach( $Status as $i => $iname ) :
                            ?>
                            <option value="<?php echo esc_attr( $i ); ?>" <?php echo esc_attr( isset( $data[ 'status' ] ) && ( $data[ 'status' ] == $i ) ? 'selected' : '' ); ?>><?php echo esc_html( $iname ); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="floatingSelect">Status, select carefully</label>
                    </div>

                    <div class="form-floating mt-3">
                        <select class="form-select" name="licence[label]" aria-label="Floating label select example" required>
                            <option>Client Label</option>
                            <?php
                            $label = $this->label();
                            foreach( $label as $i => $iname ) :
                            ?>
                            <option value="<?php echo esc_attr( $i ); ?>" <?php echo esc_attr( isset( $data[ 'label' ] ) && ( $data[ 'label' ] == $i ) ? 'selected' : '' ); ?>><?php echo esc_html( $iname ); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="floatingSelect">Customer label/role.</label>
                    </div>
                    <div class="form-floating mt-3">
                        <input type="text" class="form-control" name="licence[API]" placeholder="API" value="<?php echo isset( $data[ 'API' ] ) ? $data[ 'API' ] : 'status'; ?>">
                        <label for="floatingPassword">Returning API</label>
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="form-floating mt-3">
                        <textarea class="form-control" name="licence[comments]" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px" spellcheck="false"><?php echo esc_html( isset( $data[ 'comments' ] ) ? $data[ 'comments' ] : '' ); ?></textarea>
                        <label for="floatingTextarea">Comments/About Client</label>
                    </div>
                </div>
                
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        <?php
    }
    public function save() {
        global $wpdb;

        // $wpdb->show_errors();print_r( $wpdb->print_error() );
        
        $licence = $_POST[ 'licence' ];
        $licence = wp_parse_args( $licence, [
            'name' => '','email' => '','social' => '','package' => 0,'product' => '','ID' => rand( 0, 99999999999 ),'status' => 0,'label' => 0,'API' => '','comments' => ''
        ] );
        $wpdb->query(
            ( ! isset( $_POST[ 'is_edit' ] ) || empty( $_POST[ 'is_edit' ] ) ) ? 
            $wpdb->prepare(
                "INSERT INTO licenses SET fullname = %s, email = %s, social = %s, lc_type = %s, product = %s, ID = %s, lc_status = %s, label = %s, API = %s, comments = %s",
                $licence[ 'name' ], $licence[ 'email' ], $licence[ 'social' ], $licence[ 'package' ], $licence[ 'product' ], $licence[ 'ID' ], $licence[ 'status' ], $licence[ 'label' ], $licence[ 'API' ], $licence[ 'comments' ]
            ) :
            $wpdb->prepare(
                "UPDATE licenses SET fullname = %s, email = %s, social = %s, lc_type = %s, product = %s, ID = %s, lc_status = %s, label = %s, API = %s, comments = %s WHERE SRL = %s",
                $licence[ 'name' ], $licence[ 'email' ], $licence[ 'social' ], $licence[ 'package' ], $licence[ 'product' ], $licence[ 'ID' ], $licence[ 'status' ], $licence[ 'label' ], $licence[ 'API' ], $licence[ 'comments' ], $_POST[ 'is_edit' ]
            )
        );
        // print_r( $_POST );wp_die( 'Form detected' );
        wp_redirect( admin_url( 'admin.php?page=licences' ) );
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
    public function delete() {
        global $wpdb;
        $licence = $_POST[ 'licence' ];
        $wpdb->query(
            $wpdb->prepare( "DELETE FROM licenses WHERE ID = %s", $licence )
        );
        wp_send_json_success( __( 'Successfully Removed!' ) );
    }
    public function setup() {}
    public function style() {
        wp_enqueue_style( 'licence-verification-api', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ) ) ) . '/style.css', [], filemtime( plugin_dir_path( __FILE__ ) . '/style.css'), 'all' );
        // wp_enqueue_style( 'bootstrap-switch', 'https://unpkg.com/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css', [], 'all' );
    }
    public function scripts() {
        // wp_enqueue_script( 'bootstrap-switch', 'https://unpkg.com/bootstrap-switch', [ 'jquery' ], true );
        wp_enqueue_script( 'licence-verification-api', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ) ) ) . '/scripts.js', [ 'jquery' ], filemtime( plugin_dir_path( __FILE__ ) . '/scripts.js'), true );
    }
    public function list() {
        global $wpdb;
        $rows = $wpdb->get_results( "SELECT * FROM licenses LIMIT 0, 999;", ARRAY_A );
        // var_dump( $rows );
        return $rows;
    }
    public function package( $pack = null ) {
        $packs = [ 'Trial', 'Premium', 'Monthly', 'Yearly' ];
        if( $pack !== null ) {
            return isset( $packs[ $pack ] ) ? $packs[ $pack ] : $pack;
        } else {
            return $packs;
        }
    }
    public function product( $prod = null ) {
        $prods = [
            'fiverr.chrome' => __( 'Fiverr chrome extension', 'domain' )
        ];
        if( $prod !== null ) {
            return isset( $prods[ $prod ] ) ? $prods[ $prod ] : $prod;
        } else {
            return $prods;
        }
    }
    public function label( $label = null ) {
        $labels = [
            __( 'Free', 'domain' ),
            __( 'Professional', 'domain' ),
            __( 'Studio', 'domain' )
        ];
        if( $label !== null ) {
            return isset( $labels[ $label ] ) ? $labels[ $label ] : $label;
        } else {
            return $labels;
        }
    }
    public function licence() {
        return str_shuffle( 'fwplc' . rand( 0, 9999999 ) ) . '-' . str_replace( [ '.', ' ', ',' ], [ '', '', '' ], microtime() );
    }
    
}
new LICENCE_VERIFICATION_API_ADMIN();