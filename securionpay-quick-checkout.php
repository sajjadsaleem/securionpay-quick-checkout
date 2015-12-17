<?php
/**
@package: securion-quick-checkout
@author: Sajjad Saleem <sajjad@barqsol.com>
@Author URI: http://barqsol.com
@description: This example demostrates how to quickly setup securion checkout without using its official PHP SDK
*/


$private_key = ''; //Private Securion Key
$public_key  = ''; //Public Securion Key
$currency    = 'USD';

/**
 * Signs securion checkout request
 *
 * @param float   the amoutn in cents
 * @param string currency 
 * @param string prviate key from securion

 * @return text signed checkout request
 */ 

function sign_checkout_request($amount, $currency, $private_key)
{
    $data = json_encode(array(
        'charge' => array(
            'amount' => $amount,
            'currency' => $currency
        )
    ));

    $signarute = hash_hmac('sha256', $data, $private_key);
    return base64_encode($signarute . "|" . $data);
}

//If charge is complete
if(isset($_POST['securionpayChargeId']))
{
    echo '<p>Congratulations: You have successfully completed securion test transaction.</p>';
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';
    echo '<p><a href="'. $_SERVER['PHP_SELF'] .'">Try Another Transaction </a></p>';

//If payable amount has been submitted
} else if(isset($_POST['payable_amount']))
{
    $amount = round( 100 * $_POST['payable_amount'], 2); //Multiplying by 100 because, we need to send amount in cents to Securion
    $checkout_request = sign_checkout_request($amount, $currency, $private_key);
    
    ?>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <script src="https://securionpay.com/checkout.js"
              class="securionpay-button"
              data-key="<?php echo $public_key; ?>"
              data-checkout-request="<?php echo $checkout_request;?>"
              data-name="SecurionPay"
              data-description="Checkout example"
              data-checkout-button="Open Securion Popup">
            </script>
        </form>

        <p>Sample Credit Card Information</p>
        <p>CC No: 4242424242424242</p>
        <p>Expiry:12/30</p>
        <p>Pin:123</p>
    <?php
} else
{
    ?>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            Enter Payable Amount: <input type="text" name="payable_amount" >
            <input type="submit" value="Proceed">
        </form>    
    <?php
}
