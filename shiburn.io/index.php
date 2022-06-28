<?php #error_reporting(E_ERROR|E_PARSE); ?>
<html>
<head>
    <title>shiburn.io</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php
	$contractAddress = '0x95ad61b0a150d79219dcf64e1e6cc01f0b64c4ce';
	$burnAddress = '0xdead000000000000000042069420694206942069';
	$numTransactions = 10000;
	$apiKey = file_get_contents('../storage/apiKey.php');
	$url = 'https://api.etherscan.io/api?module=account&action=tokentx&contractaddress=' . $contractAddress . '&address=' . $burnAddress . '&page=1&offset=' . $numTransactions . '&tag=latest&apikey=' . $apiKey;
	$json = file_get_contents($url);
	$json = str_replace('},
	]',"}
	]",$json);
	$jsonArray = json_decode($json, true);
    $fullArray = $jsonArray['result']; // full array

    $result = $fullArray[0]; // into array 1 level deep
    $value = $result['value'] / 1000000000000000000;

    $totalSupply = '999992170305138';
	$totalBurned = $value;
	$totalSupplyLeft = ($totalSupply - $value);
    $totalSupplyLeftPercent = (($totalBurned / $totalSupply) * 100);
	$marketPrice = '0.00001029';
	$totalUsdValue = ($value * $marketPrice);
	$numTransactions = count($fullArray);

    $tsNF = number_format($totalSupply);
    $tbNF = number_format($totalBurned);
    $tslNF = number_format($totalSupplyLeft);
    $tslpNF = number_format($totalSupplyLeftPercent);
    $tuvNF = number_format($totalUsdValue);
    $ntNF = number_format($numTransactions);

    echo "
    <header class='header'>
        <p class='no-result vertical-align-outer'>
        <b>Official Burn Address: </b><a href='https://etherscan.io/token/0x95ad61b0a150d79219dcf64e1e6cc01f0b64c4ce?a=0xdead000000000000000042069420694206942069' target='_blank'>$burnAddress</a>
        <br />
        <b>Total Supply: </b>$tsNF
        <br />
        <b>Total Burned: </b> $tbNF
        <br />
        <b>Total Supply Left: </b>$tslNF ($tslpNF%)
        <br />		
        <b>Total USD Value: </b>≈ $$tuvNF 
        <br />
        <b>Total # of Burns: </b>$ntNF
        <br />
        <b>Current Market Price: </b>≈ $$marketPrice
        <br />
        <input class='search' type='text' placeholder='Search wallet address...' autocomplete='off'></input>
        <style class='search_style'></style>
        </p>
       </header>
    ";
?>

<div class="grid-container">
<?php
    foreach (array_reverse($jsonArray['result']) as $result) {

        $date = date('l, m/d/Y, H:i:s ',$result['timeStamp']);
		$from = $result['from'];
		$burned = number_format($result['value'] / 1000000000000000000);
		$usdValue = number_format(($result['value'] / 1000000000000000000) * $marketPrice);
		$txHash = $result['hash'];

		echo "
        <div class='searchable' data-index='$from'>
            <b>Date: </b> $date
            <br />
            <b>From: </b> <a href='https://etherscan.io/tx/$txHash' target='_blank'>$from</a>
            <br />
            <b>Amount: </b> $burned
            <br />
            <b>USD Value: </b>≈ $$usdValue
            <br />
        </div>
		";
	}
?>
</div>

<footer class="footer">
    <p>
    &copy; <?php echo date("Y"); ?> shiburn.io | All Rights Reserved.
    <br />
    All other trademarks and images are property of their respective owners.
    </p>
<script src="assets/js/search.js"></script>
</footer>
</body>
</html>
