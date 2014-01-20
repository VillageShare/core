<!--[if IE 8]><style>input[type="checkbox"]{padding:0;}</style><![endif]-->
<?php
require_once '../../lib/base.php';
?>
<div><h1>Register Here</h1></div>
<form action="registeruser.php" method="post">
                <fieldset>
                        <p class="infield grouptop">
                                <input type="text" name="regname" id="user" placeholder="Username" 
                                value=""<?php p($_['user_autofocus'] ? ' autofocus' : ''); ?>
                                autocomplete="on" required/>
                                <label for="user" ></label>
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
                                <input type="password" name="regpass1" id="password" value="" data-typetoggle="#show" placeholder="Password"
                                required<?php p($_['user_autofocus'] ? '' : ' autofocus'); ?> />
                                <label for="password" class="infield"></label>
                                <img class="svg" id="password-icon" src="<?php print_unescaped(image_path('', 'actions/password.svg')); ?>" alt=""/>
                                <input type="checkbox" id="show" name="show" />
                                <label for="show"></label>
                        </p>
                        <p class="infield groupbottom">
                                <input type="password" name="regpass2" id="password" value="" data-typetoggle="#show" placeholder="Password"
                                required<?php p($_['user_autofocus'] ? '' : ' autofocus'); ?> />
                                <label for="password" class="infield"></label>
                                <img class="svg" id="password-icon" src="<?php print_unescaped(image_path('', 'actions/password.svg')); ?>" alt=""/>
                                <input type="checkbox" id="show" name="show" />
                                <label for="show"></label>
                        </p>
                        <!--input type="hidden" name="timezone-offset" id="timezone-offset"/-->
                        <input type="submit" id="submit" class="login primary" value="Register"/>
                </fieldset>
</form>
