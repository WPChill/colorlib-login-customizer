<?php
if ( is_multisite() ) {
	/**
	 * Filters the Multisite sign up URL.
	 *
	 * @since 3.0.0
	 *
	 * @param string $sign_up_url The sign up URL.
	 */
	wp_redirect( apply_filters( 'wp_signup_location', network_site_url( 'wp-signup.php' ) ) );
	exit;
}

if ( ! get_option( 'users_can_register' ) ) {
	wp_redirect( site_url( 'wp-login.php?registration=disabled' ) );
	exit();
}

$user_login = '';
$user_email = '';

if ( $http_post ) {
	if ( isset( $_POST['user_login'] ) && is_string( $_POST['user_login'] ) ) {
		$user_login = $_POST['user_login'];
	}

	if ( isset( $_POST['user_email'] ) && is_string( $_POST['user_email'] ) ) {
		$user_email = wp_unslash( $_POST['user_email'] );
	}

	$errors = register_new_user( $user_login, $user_email );
	if ( ! is_wp_error( $errors ) ) {
		$redirect_to = ! empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : 'wp-login.php?checkemail=registered';
		wp_safe_redirect( $redirect_to );
		exit();
	}
}

$registration_redirect = ! empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';
/**
 * Filters the registration redirect URL.
 *
 * @since 3.0.0
 *
 * @param string $registration_redirect The redirect destination URL.
 */
$redirect_to = apply_filters( 'registration_redirect', $registration_redirect );
clc_login_header( __( 'Registration Form' ), '<p class="message register">' . __( 'Register For This Site' ) . '</p>', $errors );
?>
	<form name="registerform" id="registerform"
	      action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>" method="post"
	      novalidate="novalidate">
		<p>
			<label for="user_login"><span
					id="clc-register-sername-label"><?php echo  (!isset($clc_options['register-username-label']) || '' == $clc_options['register-username-label'] ) ? __( 'Username' ) : esc_html($clc_options['register-username-label']); ?></span><br/>
				<input type="text" name="user_login" id="user_login" class="input"
				       value="<?php echo esc_attr( wp_unslash( $user_login ) ); ?>" size="20"
				       autocapitalize="off"/></label>
		</p>
		<p>
			<label for="user_email"><span
					id="clc-register-email-label"><?php echo (!isset($clc_options['register-email-label']) || '' == $clc_options['register-email-label'] ) ? __( 'Email' ) : esc_html($clc_options['register-email-label']); ?></span><br/>
				<input type="email" name="user_email" id="user_email" class="input"
				       value="<?php echo esc_attr( wp_unslash( $user_email ) ); ?>" size="25"/></label>
		</p>
		<?php
		/**
		 * Fires following the 'Email' field in the user registration form.
		 *
		 * @since 2.1.0
		 */
		do_action( 'register_form' );
		?>
		<p id="reg_passmail"><?php echo (!isset($clc_options['register-confirmation-email']) || '' == $clc_options['register-confirmation-email']) ?  __( 'Registration confirmation will be emailed to you.' ) : esc_html($clc_options['register-confirmation-email']); ?></p>
		<br class="clear"/>
		<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>"/>
		<p class="submit"><input type="submit" name="wp-submit" id="wp-submit"
		                         class="button button-primary button-large"
		                         value="<?php echo ( !isset($clc_options['register-button-label']) || '' == $clc_options['register-button-label']) ? __( 'Register') : esc_attr($clc_options['register-button-label']); ?>"/></p>
	</form>

	<p id="nav">
		<a href="<?php echo esc_url( wp_login_url() ); ?>" id="login-link-label"><?php echo (!isset($clc_options['login-link-label']) || '' == $clc_options['login-link-label']) ? __( 'Log in' ) : esc_html($clc_options['login-link-label']) ; ?></a>
		<?php echo esc_html( $login_link_separator ); ?>
		<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" id="clc-lost-password-text"><?php echo (!isset($clc_options['lost-password-text']) || '' == $clc_options['lost-password-text']) ? __( 'Lost your password?' ) : esc_html($clc_options['lost-password-text']); ?></a>
	</p>

<?php
if(!is_customize_preview())
clc_login_footer( 'user_login' );
