<form method="post" action="<?php echo get_admin_url(); ?>admin-post.php" class="settings-form">
    <ul class="settings_form">
        <li>
            <label for="email-address">From Email</label>
            <input type="text" name="settings_email"  placeholder="Enter your email address" />
        </li>
        <li>
            <label for="email-name">From Name</label>
            <input type="text" name="settings_name" placeholder="Enter the name you want to show on the message" />
        </li>
        <li>
            <label for="smtp-host">SMTP Host</label>
            <input type="text" name="settings_host" placeholder="smtp.example.com" />
        </li>
        <li>
            <label for="encryption-type">Encryption Type</label>
            <input type="radio" name="settings_encryption" value="none"/> None
            <input type="radio" name="settings_encryption" value="ssl"/> SSL
            <input type="radio" name="settings_encryption" value="tls"/> TLS
        </li>
        <li>
            <label for="smtp-port">SMTP Port</label>
            <input type="text" name="settings_smtp_port" value="25" />
        </li>
        <li>
            <label for="smtp-auth">SMTP Authentication</label>
            <input type="radio" name="settings_smtp_auth_yes"/> NO
            <input type="radio" name="settings_smtp_auth_no"/> YES
        </li>
        <li>
            <label for="smtp-username">SMTP Username</label>
            <input type="text" name="settings_smtp_username" />
        </li>
        <li>
            <label for="smtp-passwowrd">SMTP Password</label>
            <input type="password" name="settings_smtp_password"/>
        </li>
        <li>
            <input type="submit" value="Save Updates"/>
            <input type="hidden" name="wp_sms_submit" value="submit"/>
        </li>
    </ul>
</form>



