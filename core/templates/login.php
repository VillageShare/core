<!--div style="margin-top:5px; width: 22em; padding: 0; float:right; padding-right: 400px;"-->
<!--div style="margin-bottom:0px;margin-right:30px;padding-left: 40px;"><h1><b>Already Registered? Login</b></h1></div-->
<!--[if IE 8]><style>input[type="checkbox"]{padding:0;}</style><![endif]-->
<form method="post">
	<fieldset>
		 <?php if (!empty($_['redirect_url'])) {
                print_unescaped('<input type="hidden" name="redirect_url" value="' . OC_Util::sanitizeHTML($_['redirect_url']) . '" />');
                } ?>
                <ul>
                        <?php if (isset($_['invalidcookie']) && ($_['invalidcookie'])): ?>
                        <li class="errors">
                                <?php p($l->t('Automatic logon rejected!')); ?><br>
                                <small><?php p($l->t('If you did not change your password recently, your account may be compromised!')); ?></small>
                                <br>
                                <small><?php p($l->t('Please change your password to secure your account again.')); ?></small>
                        </li>
                        <?php endif; ?>
                        <?php if (isset($_['invalidpassword']) && ($_['invalidpassword'])): ?>
                                <a href="<?php print_unescaped(OC_Helper::linkToRoute('core_lostpassword_index')) ?>">
                                        <li class="errors">
                                                <?php p($l->t('Lost your password?')); ?>
                                        </li>
                                </a>
                        <?php endif; ?>
                </ul>
		<p class="infield grouptop">
			<input type="text" name="user" id="user" placeholder=""
			value="<?php p($_['username']); ?>"<?php p($_['user_autofocus'] ? ' autofocus' : ''); ?>
			autocomplete="on" required/>
			<label for="user" class="infield"><?php p($l->t('Username')); ?></label>
			<img class="svg" src="<?php print_unescaped(image_path('', 'actions/user.svg')); ?>" alt=""/>
		</p>
		<?php if ($_['locations'] !== null AND $_['default_location'] !== null) : ?>
                        <p class="infield groupmiddle">
                        <label for="location"><?php echo $l->t('Location'); ?></label>
                                <select name="location" id="location">
                                <?php foreach ($_['locations'] as $location ) :
                                        if ($location->getLocation() === $_['default_location']) : ?>
                                                <option selected="true" value='<?php echo $location->getLocation(); ?>'><?php echo $location->getLocation(); ?></option>
                                        <?php  else :  ?>
                                                <option value='<?php echo $location->getLocation() ?>'><?php echo $location->getLocation() ?></option>
                                        <?php   endif;
                                endforeach;?>
                                </select>
                        </p>
                <?php endif; ?>

		<p class="infield groupbottom">
			<input type="password" name="password" id="password" value="" data-typetoggle="#show" placeholder=""
			required<?php p($_['user_autofocus'] ? '' : ' autofocus'); ?> />
			<label for="password" class="infield"><?php p($l->t('Password')); ?></label>
			<img class="svg" id="password-icon" src="<?php print_unescaped(image_path('', 'actions/password.svg')); ?>" alt=""/>
			<input type="checkbox" id="show" name="show" />
			<label for="show"></label>
		</p>
		<input type="checkbox" name="remember_login" value="1" id="remember_login"/><label
		for="remember_login"><?php p($l->t('remember')); ?></label>
		<input type="hidden" name="timezone-offset" id="timezone-offset"/>
		<input type="submit" id="submit" class="login primary" value="<?php p($l->t('Log in')); ?>"/>
		</fieldset>
        <br />
        <a href="core/templates/register.php">Register here.</a>
	</form>
	<?php if (!empty($_['alt_login'])) { ?>
	<form id="alternative-logins">
		<fieldset>
		<legend><?php p($l->t('Alternative Logins')) ?></legend>
		<ul>
			<?php foreach($_['alt_login'] as $login): ?>
			<li><a class="button" href="<?php print_unescaped($login['href']); ?>" ><?php p($login['name']); ?></a></li>
			<?php endforeach; ?>
		</ul>
		</fieldset>
	</form>
<!--/div-->
<?php } ?>

<?php
OCP\Util::addscript('core', 'visitortimezone');
?>

