<?php
	// Ensure no output before headers
	ob_start();
	
	include "etc/includes.php";

	// Test mode configuration
	$test_mode = $GLOBALS['testMode'] ?? false;
	if ($test_mode) {
		?>
		<script type="text/javascript">
			window.TEST_MODE = true;
			window.TEST_DATA = {
				domains: [
					{ id: 'test1.hns', verified: true },
					{ id: 'test2.hns', verified: false },
					{ id: 'test3.hns', verified: true }
				],
				tlds: ['hns', 'handshake'],
				verification_code: 'test-verification-code-123'
			};
		</script>
		<?php
	}

	// Store invite code in a variable instead of outputting it directly
	$invite_script = '';
	if (@$_GET["invite"]) {
		$invite_script = '<script type="text/javascript">var invite = "' . htmlspecialchars(addslashes($_GET["invite"])) . '";</script>';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include "etc/head.php"; ?>
	<?php echo $invite_script; ?>
	<?php if ($test_mode): ?>
	<script>
		// Auto-skip verification in test mode
		document.addEventListener('DOMContentLoaded', function() {
			// Hide loading section
			document.querySelector('.section.loading').classList.remove('shown');
			// Show manageDomains section
			document.getElementById('manageDomains').style.display = 'block';
		});
	</script>
	<?php endif; ?>
</head>
<body data-page="id" <?php echo $test_mode ? 'data-test-mode="true"' : ''; ?>>
	<?php if ($test_mode): ?>
		<div class="test-mode-indicator">Test Mode</div>
	<?php endif; ?>
	<button class="close-window-button"><img src="/assets/img/icons/delete-sign.png" onclick="window.close();"></button>
	
	<div id="blackout"></div>
	<div class="popover" data-name="qr">
		<div class="head">
			<div class="title">Sync QR</div>
			<div class="icon action close" data-action="close"></div>
		</div>
		<div class="body">
			<div class="loading flex shown">
				<div class="lds-facebook"><div></div><div></div><div></div></div>
				<canvas id="frame"></canvas>
			</div>
			<video id="camera" autoplay playsinline></video>
		</div>
		<div class="response error"></div>
	</div>
	<div class="form" id="id">
		<a href="/">
			<div class="login-logo">
				<img draggable="false" src="/assets/img/login-logo.svg">
			</div>
		</a>
		<div class="section loading <?php echo $test_mode ? '' : 'shown'; ?>">
			<div class="loading flex shown">
				<div class="lds-facebook"><div></div><div></div><div></div></div>
			</div>
		</div>
		<div class="section" id="manageDomains" style="<?php echo $test_mode ? 'display: block;' : 'display: none;'; ?>">
			<div class="domains"></div>
			<div class="button" data-action="newDomain">Add Domain</div>
			<div class="button" data-action="scanQR">Scan Sync QR</div>
			<a href="/" id="startChatting" class="hidden">Start Chatting</a>
		</div>
		<div class="section" id="addDomain">
			<div class="button varo" data-action="addDomain">Authenticate with Varo Auth</div>
			<div class="or varo">OR</div>
			<div class="group">
				<input type="text" name="sld" placeholder="Create a name">
				<input type="text" name="dot" placeholder="." class="transparent" disabled>
				<select name="tld"></select>
				<input type="hidden" name="invite">
			</div>
			<div class="button" data-action="addSLD">Continue</div>
			<div class="response error"></div>
			<div class="link" data-action="manageDomains">Manage Domains</div>
		</div>
		<div class="section" id="verifyOptions">
			<input type="hidden" name="domain">
			<div class="title">How would you like to verify?</div><div id="code"></div>
			<div class="button" data-action="verifyDomainWithTXT">Verify with TXT Record</div>
			<div class="button" data-action="verifyDomainWithBob">Verify with Bob Extension</div>
			<div class="button" data-action="verifyDomainWithMetaMask">Verify with MetaMask</div>
			<div class="response error"></div>
		</div>
		<div class="section" id="verifyDomain">
			<input type="hidden" name="domain">
			<div class="title">Please create a TXT record with the following value: </div><div id="code"></div>
			<div class="button" data-action="verifyDomain">Verify</div>
			<div class="response error"></div>
		</div>
		<div class="section" id="startChatting">
			<div class="title">You're all set!</div>
			<div class="button" data-action="startChatting">Start Chatting</div>
		</div>
	</div>
</body>
</html>