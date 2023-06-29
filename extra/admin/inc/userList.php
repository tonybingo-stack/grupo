<?php
include 'admin.php';
$subs = query('SELECT id,name FROM `gr_subs`');
$roles = query('SELECT site_role_id,site_role_attribute FROM `gr_site_roles`');


/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'gr_site_users';
 
// Table's primary key
$primaryKey = 'user_id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'user_id', 'dt' => 0 ),
    array( 'db' => 'display_name', 'dt' => 1 ),
    array( 'db' => 'username', 'dt' => 2 ),
    array( 
        'db' => 'site_role_id',  
        'dt' => 3 ,
        'formatter' => function( $d, $row ) {
            global $roles;
            $data ='Unknown';

            foreach ($roles as $key => $value) {
               if ($value['site_role_id']==$d) {
                $data = $value['site_role_attribute'];
                break;
               }
            }

            return $data;
    }),
    array( 'db' => 'credits',   'dt' => 4 ),
    array( 
        'db' => 'subs', 
        'dt' => 5,
        'formatter' => function( $d, $row ) {
            if ($d == 0)
                return 'Not Sub';

            global $subs;
            $data ='Unknown';

            foreach ($subs as $key => $value) {
               if ($value['id']==$d) {
                $data = $value['name'];
                break;
               }
            }

            return $data;
        }
    ),
    array(
        'db'        => 'subs_end',
        'dt'        => 6,
        'formatter' => function( $d, $row ) {
            if ($d == 0)
                return 'Not Set';
            return date("Y-m-d H:i:s", $d);
        }
    ),
    array( 'db' => 'agency', 
           'dt' => 7,

           'formatter' => function( $d, $row ) {
            if ($d == 0)
                return 'No';
            else
                return 'Yes';
        }
    ),
    array( 'db' => 'extra', 'dt' => 8 )
);
 
// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => '',
    'db'   => 'grupo',
    'host' => 'localhost',
    'charset' => 'utf8mb4'
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);