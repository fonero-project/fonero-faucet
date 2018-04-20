<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require_once('recaptchalib.php');
	$secret = "6LcHfD0UAAAAAL-XZa9wk1gQ_NeeKMVNkp03V3YI";
	$response = null;
	$reCaptcha = new ReCaptcha($secret);
	$_REQUEST['wallet'] = trim($_REQUEST['wallet']);

	if (isset($_POST['g-recaptcha-response'])) {
            $response = $reCaptcha->verifyResponse(
                $_SERVER["REMOTE_ADDR"],
                $_POST["g-recaptcha-response"]
            );
	    if (isset($_REQUEST['wallet']) && strlen($_REQUEST['wallet']) == '95' && (mb_substr($_REQUEST['wallet'], '0', '1') == '8' || mb_substr($_REQUEST['wallet'], '0', '1') == '9') && $response != null && $response->success) {
		if ($mysql->data('SELECT COUNT(faucet_id) AS count FROM web_faucets WHERE (faucet_wallet = "' . $mysql->escape($_REQUEST['wallet']) . '" OR faucet_addr = "' . $mysql->escape($_SERVER['REMOTE_ADDR']) . '") AND faucet_date = "' . $mysql->escape(date('Y-m-d', time())) . '"', false ) -> count == '0' ) {
			//$faucet_amount = number_format(((mt_rand('1', '10') / '7') / '3'), '2');
			$faucet_amount = number_format(((mt_rand('1', '21') / mt_rand('11', '19')) / mt_rand('2', '5')), '2');
			$mysql->exec( 'INSERT INTO web_faucets (faucet_id, faucet_date, faucet_time, faucet_hash, faucet_addr, faucet_wallet, faucet_amount, faucet_txid, faucet_status) VALUES (NULL, "' . $mysql -> escape (date('Y-m-d', time())) . '", "' . $mysql -> escape (date('H:i:s', time())) . '", "' . $mysql->escape(md5($_REQUEST['wallet'] . $_SERVER['REMOTE_ADDR'])) . '", "' . $mysql->escape($_SERVER['REMOTE_ADDR']) . '", "' . $mysql->escape($_REQUEST['wallet']) . '", "' . $mysql->escape($faucet_amount) . '", "-", "waited");' );
			header('location: /?status=success&amount=' . $faucet_amount);
			exit();
		} else {
			header('location: /?status=error');
			exit();
		}
	    }
        }
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Fonero (FNO) - The secure, private, untraceable cryptocurrency with integrated RingCT technology</title>
        <meta name="description" content="The secure, private, untraceable cryptocurrency with integrated RingCT technology">
        <link rel="stylesheet" href="/templates/js/bootstrap/css/bootstrap.css">
        <link href="/templates/css/templates.css" rel="stylesheet">
	<link rel="icon" type="image/png" id="favicon" href="/templates/img/img-favicon.png"/>
	<style>
		@import url('https://fonts.googleapis.com/css?family=Open+Sans|Roboto:900');
	</style>
    </head>
    <body>

	<?php
		$blocked = false;
		if ($mysql->data('SELECT COUNT(faucet_id) AS count FROM web_faucets WHERE faucet_addr = "' . $mysql->escape($_SERVER['REMOTE_ADDR']) . '" AND faucet_date = "' . $mysql->escape(date('Y-m-d', time())) . '"', false ) -> count != '0' ) {
			$blocked = true;
		}
	?>
	<div id="particles-js" style="position: absolute; height: 100%; width: 100%;">
		<div class="content">
                        <a href="http://fonero.org" style="text-decoration: none;" target="_blank"><div style="width: 480px; margin-right: auto; margin-left: auto;"><h1 class="cover-heading" style="font-family: 'Roboto', sans-serif; font-size: 90px;"><img src="/templates/img/img-newyear.png" style="width:80px; position:relative; right: -42px; top: -15px;" />Fonero</a> <span style="float: right; margin-top: 10px; font-size: 30px;">[faucet]</span></h1></div>
                        <p class="lead">You can receive coins of Fonero for free, just specify your wallet. Use the service only once a day.</p>
                        <p class="lead">
				Good news, on January 1, at 00:00, among all requests will be played a jackpot of <b>30,000</b> coins.<br />
				In addition, a daily jackpot of <b>100</b> coins has been delivered until January 1.<br />
				Please, after you receive a coin or win a jackpot, write about it in our forums.<br />
				<a href="https://bitcointalk.org/index.php?topic=2413943" target="_blank">https://bitcointalk.org/index.php?topic=2413943</a>
			</p>

                        <p class="lead">Вы можете бесплатно получить монеты Fonero, просто укажите свой кошелек. Доступно 1 раз в сутки.</p>
                        <p class="lead">
				Хорошие новости, 1 января, в 00:00, среди всех запросов будет разыгран джекпот в размере <b>30,000</b> монет.<br />
				Кроме того, добавлен ежедневный джекпот в размере <b>100</b> монет до 1 января.<br />
				Пожалуйста, после того, как вы получите монеты или выиграете джекпот, напишите об этом на форумах.<br />
				<a href="https://bitcointalk.org/index.php?topic=2384890" target="_blank">https://bitcointalk.org/index.php?topic=2384890</a>
			</p>

			<div class="clearfix" style="margin-top: 30px;"></div>
			<?php
				if(isset($_REQUEST['status']) && isset($_REQUEST['amount']) && $_REQUEST['status'] == 'success') {
					echo '<div class="alert alert-success" style="text-shadow: none;"><b>Congratulations, you are given ' . $_REQUEST['amount'] . ' FNO.</b><br />Your translation is queued for sending, in the near future you will receive coins on your wallet.</div>';
				} elseif(isset($_REQUEST['status']) && $_REQUEST['status'] == 'error') {
					echo '<div class="alert alert-danger" style="text-shadow: none;">Sorry, but you have already received free coins.</div>';
				}
			?>
			<?php
				if($blocked) {
					echo '<div class="alert alert-danger" style="text-shadow: none;">Sorry, you can make the following request not earlier than ' . date('Y-m-d 00:00:00', (time() + '86400')) . ' (MSK)</div>';
				} else {
			?>
			<form action="/" method="post">
			 	<div class="form-group" style="text-align: left;">
					<label style="font-family: 'Roboto', sans-serif; font-size: 18px;">Fonero address:</label>
					<div class="input-group">
						<input type="text" class="form-control" name="wallet" placeholder="Enter your Fonero address... (8...)" <?=(($blocked) ? 'disabled' : ''); ?>>
						<span class="input-group-btn">
							<button class="btn <?=(($blocked) ? 'btn-secondary' : 'btn-primary'); ?>" type="submit" style="font-family: 'Roboto', sans-serif; font-size: 18px;" <?=(($blocked) ? 'disabled' : ''); ?>>Get free coins...</button>
						</span>
					</div>

<div style="margin-top: 15px; float: left; font-size: 13px; color: grey;">
<?php

echo 'This day: ' . $mysql->data('SELECT COUNT(faucet_id) AS count FROM web_faucets WHERE faucet_date = "' . $mysql->escape(date('Y-m-d', time())) . '"', false )->count . ' (' . number_format($mysql->data('SELECT SUM(faucet_amount) AS sum FROM web_faucets WHERE faucet_date = "' . $mysql->escape(date('Y-m-d', time())) . '"', false )->sum, '2') . ' FNO)<br />';
echo 'Yesterday: ' . $mysql->data('SELECT COUNT(faucet_id) AS count FROM web_faucets WHERE faucet_date = "' . $mysql->escape(date('Y-m-d', strtotime('-1 day'))) . '"', false )->count . ' (' . number_format($mysql->data('SELECT SUM(faucet_amount) AS sum FROM web_faucets WHERE faucet_date = "' . $mysql->escape(date('Y-m-d', strtotime('-1 day'))) . '"', false )->sum, '2') . ' FNO)<br />';
echo 'All the time: ' . $mysql->data('SELECT COUNT(faucet_id) AS count FROM web_faucets', false )->count . ' (' . number_format($mysql->data('SELECT SUM(faucet_amount) AS sum FROM web_faucets', false )->sum, '2') . ' FNO)<br />';

?>
</div>

					<div class="g-recaptcha" data-sitekey="6LcHfD0UAAAAAO283tgOnNbjrLKx24h7uZ-H-a8x" style="margin-top: 15px; float: right;"></div>
					<script src="https://www.google.com/recaptcha/api.js"></script>
					<div class="clearfix"></div>
				</div>
			</form>
		<?php
			}
		?>

<div class="clearfix" style="margin-top: 30px;"></div>
<div style="text-align: left;">
	<label style="font-family: 'Roboto', sans-serif; font-size: 18px;" >Last 10 jackpots:</label>
	<table class="table table-bordered table-striped table-hover table-sm table-dark" style="text-align: center;">
	  <thead>
	    <tr>
	      <th scope="col"><span style="padding-left: 5px; padding-right: 5px;">#</span></th>
	      <th scope="col"><span style="padding-left: 5px; padding-right: 5px;">Date</span></th>
	      <th scope="col"><span style="padding-left: 5px; padding-right: 5px;">Wallet</span></th>
	      <th scope="col"><span style="padding-left: 5px; padding-right: 5px;">Amount</span></th>
	      <th scope="col"><span style="padding-left: 5px; padding-right: 5px;">Txid</span></th>
	      <th scope="col"><span style="padding-left: 5px; padding-right: 5px;">Status</span></th>
	    </tr>
	  </thead>
	  <tbody>
<?php
if($mysql->data('SELECT COUNT(faucet_id) AS count FROM web_faucets WHERE faucet_jackpot = "1"', false)->count != '0') {
	$items = $mysql->data('SELECT * FROM web_faucets WHERE faucet_jackpot = "1" ORDER By faucet_id DESC LIMIT 50');
	foreach($items as $item) {
		if($item->faucet_status == 'successed') {
		   echo '<tr>
		      <th scope="row" width="1"><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_id . '</span></th>
		      <td nowrap><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_date . ' ' . $item->faucet_time . '</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;">' . substr($item->faucet_wallet, '0', '30') . '...</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_amount . '&nbsp;FNO</span></td>
		      <td><span style="padding-left: 5px; padding-right: 5px;"><a href="http://blocks.fonero.org/tx/' . $item->faucet_txid . '" style="color: #007bff;">' . substr($item->faucet_txid, '0', '20') . '...</a></span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;"><span class="badge badge-success" style="text-shadow: none; font-size: 15px; padding: 2px 7px 5px 7px;">success</span></span></td>
		    </tr>';
		}
		if($item->faucet_status == 'rejected') {
		    echo '<tr>
		      <th scope="row" width="1"><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_id . '</span></th>
		      <td nowrap><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_date . ' ' . $item->faucet_time . '</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;">' . substr($item->faucet_wallet, '0', '30') . '...</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_amount . '&nbsp;FNO</span></td>
		      <td><span style="padding-left: 5px; padding-right: 5px;">-</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;"><span class="badge badge-danger" style="text-shadow: none; font-size: 15px; padding: 2px 7px 5px 7px;">reject</span></span></td>
		    </tr>';
		}
		if($item->faucet_status == 'waited') {
		    echo '<tr>
		      <th scope="row" width="1"><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_id . '</span></th>
		      <td nowrap><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_date . ' ' . $item->faucet_time . '</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;">' . substr($item->faucet_wallet, '0', '30') . '...</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_amount . '&nbsp;FNO</span></td>
		      <td><span style="padding-left: 5px; padding-right: 5px;">-</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;"><span class="badge badge-warning" style="text-shadow: none; font-size: 15px; padding: 2px 7px 5px 7px; color: #fff;">wait</span></span></td>
		    </tr>';
		}
	}
}
?>
 
  </tbody>
</table>
</div>

<div class="clearfix" style="margin-top: 30px;"></div>
<div style="text-align: left;">
	<label style="font-family: 'Roboto', sans-serif; font-size: 18px;" >Last 50 requests:</label>
	<table class="table table-bordered table-striped table-hover table-sm table-dark" style="text-align: center;">
	  <thead>
	    <tr>
	      <th scope="col"><span style="padding-left: 5px; padding-right: 5px;">#</span></th>
	      <th scope="col"><span style="padding-left: 5px; padding-right: 5px;">Date</span></th>
	      <th scope="col"><span style="padding-left: 5px; padding-right: 5px;">Wallet</span></th>
	      <th scope="col"><span style="padding-left: 5px; padding-right: 5px;">Amount</span></th>
	      <th scope="col"><span style="padding-left: 5px; padding-right: 5px;">Txid</span></th>
	      <th scope="col"><span style="padding-left: 5px; padding-right: 5px;">Status</span></th>
	    </tr>
	  </thead>
	  <tbody>
<?php
if($mysql->data('SELECT COUNT(faucet_id) AS count FROM web_faucets WHERE faucet_jackpot = "0"', false)->count != '0') {
	$items = $mysql->data('SELECT * FROM web_faucets WHERE faucet_jackpot = "0" ORDER By faucet_id DESC LIMIT 50');
	foreach($items as $item) {
		if($item->faucet_status == 'successed') {
		   echo '<tr>
		      <th scope="row" width="1"><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_id . '</span></th>
		      <td nowrap><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_date . ' ' . $item->faucet_time . '</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;">' . substr($item->faucet_wallet, '0', '30') . '...</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_amount . '&nbsp;FNO</span></td>
		      <td><span style="padding-left: 5px; padding-right: 5px;"><a href="http://blocks.fonero.org/tx/' . $item->faucet_txid . '" style="color: #007bff;">' . substr($item->faucet_txid, '0', '20') . '...</a></span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;"><span class="badge badge-success" style="text-shadow: none; font-size: 15px; padding: 2px 7px 5px 7px;">success</span></span></td>
		    </tr>';
		}
		if($item->faucet_status == 'rejected') {
		    echo '<tr>
		      <th scope="row" width="1"><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_id . '</span></th>
		      <td nowrap><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_date . ' ' . $item->faucet_time . '</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;">' . substr($item->faucet_wallet, '0', '30') . '...</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_amount . '&nbsp;FNO</span></td>
		      <td><span style="padding-left: 5px; padding-right: 5px;">-</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;"><span class="badge badge-danger" style="text-shadow: none; font-size: 15px; padding: 2px 7px 5px 7px;">reject</span></span></td>
		    </tr>';
		}
		if($item->faucet_status == 'waited') {
		    echo '<tr>
		      <th scope="row" width="1"><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_id . '</span></th>
		      <td nowrap><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_date . ' ' . $item->faucet_time . '</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;">' . substr($item->faucet_wallet, '0', '30') . '...</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;">' . $item->faucet_amount . '&nbsp;FNO</span></td>
		      <td><span style="padding-left: 5px; padding-right: 5px;">-</span></td>
		      <td width="1"><span style="padding-left: 5px; padding-right: 5px;"><span class="badge badge-warning" style="text-shadow: none; font-size: 15px; padding: 2px 7px 5px 7px; color: #fff;">wait</span></span></td>
		    </tr>';
		}
	}
}
?>
 
  </tbody>
</table>
</div>
                        <p style="padding-top: 20px;"><a href="https://stocks.exchange/trade/FNO/BTC" target="_blank"><img src="/templates/img/img-stocks.exchange.png" width="150"></a></p>
			<div class="clearfix"></div>
                        <p style="font-size: 11px;">2017 &copy; Russian computing technologies<br />All rights reserved.</p>
		</div>
	</div>
	<style>
		.modal-dialog {
			margin-top: 30px;
		}
		h5 {
			font-family: 'Roboto', sans-serif;
		}
		.content {
		    padding: 15px;
		    min-width: 600px !important;
		}
		body {
		
		}
	</style>
        <script src="/templates/js/jquery/js/jquery.js"></script>
        <script src="/templates/js/popper/js/popper.js"></script>
        <script src="/templates/js/bootstrap/js/bootstrap.js"></script>
        <script src="/templates/js/particles/js/particles.js"></script>
	<script type="text/javascript">
		jQuery.fn.center = function() {
		    this.css('position', 'absolute');
		    this.css({
		        'width': '',
		        'height': '',
		        'top': '',
		        'left': '',
		        'z-index': '999',
		        'margin': ''
		    });
		    this.css('top', Math.max('0', (($(window).height() - this.outerHeight()) / '2') + $(window).scrollTop()) + 'px');
		    this.css('left', Math.max('0', (($(window).width() - this.outerWidth()) / '2') + $(window).scrollLeft()) + 'px');
		    return this;
		}
		$(document).ready(function () {
			$('.content').center(true);
			$(window).resize(function () {
				$('.content').center(true);
			});
			$(document).ready(function() {
			    //$("#news").modal('show');
			});
		});
	</script>

	<!-- Yandex.Metrika counter -->
	<script type="text/javascript" >
	    (function (d, w, c) {
	        (w[c] = w[c] || []).push(function() {
	            try {
	                w.yaCounter46674825 = new Ya.Metrika({
	                    id:46674825,
	                    clickmap:true,
	                    trackLinks:true,
	                    accurateTrackBounce:true
	                });
	            } catch(e) { }
	        });
	
	        var n = d.getElementsByTagName("script")[0],
	            s = d.createElement("script"),
	            f = function () { n.parentNode.insertBefore(s, n); };
	        s.type = "text/javascript";
	        s.async = true;
	        s.src = "https://mc.yandex.ru/metrika/watch.js";
	
	        if (w.opera == "[object Opera]") {
	            d.addEventListener("DOMContentLoaded", f, false);
	        } else { f(); }
	    })(document, window, "yandex_metrika_callbacks");
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/46674825" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->

    </body>
</html>