<?php 
    /*
    Plugin Name: Adding  drop down roles  in registration 
    Plugin URI: http://fancynews.in
    Description: Adding  drop down roles  in registration 
    Author: Madiri Salman Aashish
    Version: 1.1
   
    */

function new_usersroles_actions() 
{
add_menu_page('ADDING USER ROLES', 'ADDING USER ROLES', 'manage_options', 'ADDING USER ROLES', 'new_usersroles_content');
 
}
 
 function new_usersroles_content()
 {
	
echo "<h4>For Quick Help Email at madirisalmanaashish@gmail.com</h4>";
global $wp_roles;	 
global $wpdb;

$charset_collate = $wpdb->get_charset_collate();
$table_name = $wpdb->prefix . "registrationuser"; 

$sql = "CREATE TABLE $table_name   (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  PRIMARY KEY  (id)
) $charset_collate;";
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );

 $jquery1 = <<<EOF
    <script>jQuery(document).ready(

 function($) {
    
    function cutAndPaste(from, to) {
        $(to).append(function() {
            return $(from + " option:selected").each(function() {
                this.outerHTML;
            }).remove();
        });
    }
    
    $("#forward").off("click").on("click", function() {
        cutAndPaste("#sourceSelect", "#destinationSelect");
    });
    
    $("#backward").off("click").on("click", function() {
        cutAndPaste("#destinationSelect", "#sourceSelect");
    });
    
}); </script>
EOF;

echo $jquery1;


echo  '<form action="" method="post">';
echo '<select name="role1" id="sourceSelect" class="input" multiple="multiple">';
    foreach ( $wp_roles->roles as $key=>$value ):
       echo '<option value="'.$key.'">'.$value['name'].'</option>';
    endforeach;
    echo '</select>'; 


echo '<input type="button" name="forward" id="forward" value=">" />
     <input type="button" name="backward" id="backward" value="<" />';


    echo '<select name="role2[]" class="input" id="destinationSelect" multiple="multiple">';
   
    echo '</select>'; 
	echo '<input type="submit" value="Submit">';
	echo '</form>';




	
if(!empty( $_POST['role2'] ))	
	
{

$roledata=$_POST['role2'];
$table_name1 = $wpdb->prefix . "registrationuser"; 
$sqla1="Delete  from $table_name1";
$wpdb->query($sqla1);
for($i=0;$i<count($roledata);$i++)
{
$roledata1=$_POST['role2'][$i];


$sqla="INSERT INTO $table_name1 (name) value ('$roledata1')";
$wpdb->query($sqla);
}
//echo "Role has been Saved";

}
}
 
 add_action('admin_menu', 'new_usersroles_actions');
 

add_action( 'register_form', 'new_usersroles_register_form' );

function new_usersroles_register_form() {

global $wpdb;

$table_name1 = $wpdb->prefix . "registrationuser"; 


$result1=$wpdb->get_results("SELECT (name) FROM $table_name1");   



 echo '<select name="role" class="input">';
    foreach ( $result1  as $value ):
       echo '<option value="'.$value->name.'">'.ucfirst($value->name).'</option>';
    endforeach;
    echo '</select>'; 


}


//2. Add validation.
add_filter( 'registration_errors', 'new_usersroles_registration_errors', 10, 3 );
function new_usersroles_registration_errors( $errors, $sanitized_user_login, $user_email ) {

    if ( empty( $_POST['role'] ) || ! empty( $_POST['role'] ) && trim( $_POST['role'] ) == '' ) {
         $errors->add( 'role_error', __( '<strong>ERROR</strong>: You must include a role.', 'mydomain' ) );
    }
  
if ( empty( $_POST['role2'] ) && trim( $_POST['role2'] ) == '' ) {
         $errors->add( 'role_error', __( '<strong>ERROR</strong>: You must include a role.', 'mydomain' ) );
    }


    return $errors;
}

//3. Finally, save our extra registration user meta.
add_action( 'user_register', 'new_usersroles_user_register' );
function new_usersroles_user_register( $user_id ) {

   $user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $_POST['role'] ) );
}
	 

	
	?>