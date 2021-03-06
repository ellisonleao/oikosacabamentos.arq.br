<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php if($type == 'logout') : ?>

	<form action="index.php" method="post" name="login" id="">
	<div class="jv-login-form">
		<div class="jv-field-submit"><input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'BUTTON_LOGOUT'); ?>" /></div>
		<?php if ($params->get('greeting')) : ?>
		<div class="jv-field-greeting">	
		<?php if ($params->get('name')) : {
			echo JText::sprintf( 'HINAME', $user->get('name') );
		} else : {
			echo JText::sprintf( 'HINAME', $user->get('username') );
		} endif; ?>
		</div>
		<?php endif; ?>
	</div>
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="logout" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	</form>

<?php else : ?>

	<?php if(JPluginHelper::isEnabled('authentication', 'openid')) :
			$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
			$langScript = 	'var JLanguage = {};'.
							' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
							' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
							' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
							' var modlogin = 1;';
			$document = &JFactory::getDocument();
			$document->addScriptDeclaration( $langScript );
			JHTML::_('script', 'openid.js');
	endif; ?>
	<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login">
	<div class="jv-login-form">
		<div class="clearfix">
			<div class="jv-field">
				<input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="18" value="<?php echo JText::_('Username') ?>" onblur="if(this.value=='') this.value='<?php echo JText::_('Username') ?>';" onfocus="if(this.value=='<?php echo JText::_('Username') ?>') this.value='';" />
			</div>
			<div class="jv-field">
				<input id="modlgn_passwd" type="password" name="passwd" class="inputbox" size="18" alt="password" value="<?php echo JText::_('Password') ?>" onblur="if(this.value=='') this.value='<?php echo JText::_('Password') ?>';" onfocus="if(this.value=='<?php echo JText::_('Password') ?>') this.value='';" />
			</div>
			<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
			<div class="jv-field">
				<input id="modlgn_remember" type="checkbox" name="remember" value="yes" alt="Remember Me" />
				<label for="modlgn_remember"><?php echo JText::_('Remember me') ?></label>
			</div>
			<?php endif; ?>
			<div class="jv-field-submit">
				<input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" />
			</div>
		</div>
		<div>
			<ul>
			<?php
			$usersConfig = &JComponentHelper::getParams( 'com_users' );
			if ($usersConfig->get('allowUserRegistration')) : ?>
			<li><a href="<?php echo JRoute::_( 'index.php?option=com_user&view=register' ); ?>"><?php echo JText::_('REGISTER'); ?></a></li>
			<?php endif; ?>
			<li><a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>"><?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a></li>
			<li><a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>"><?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a></li>
			</ul>
		</div>

		<input type="hidden" name="option" value="com_user" />
		<input type="hidden" name="task" value="login" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</div>
	</form>
<?php endif; ?>