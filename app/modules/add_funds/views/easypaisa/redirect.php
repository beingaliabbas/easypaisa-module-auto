<?php
$status      = isset($status) ? $status : session('easypaisa_status');
$auto        = isset($auto) ? $auto : session('easypaisa_auto');
$amount      = isset($amount) ? $amount : session('easypaisa_amount');
$txid        = isset($txid) ? $txid : session('easypaisa_txid');
$converted   = isset($converted) ? $converted : session('easypaisa_converted');
$error_msg   = isset($error_msg) ? $error_msg : session('easypaisa_err');
$uid         = isset($uid) ? $uid : session('uid');
?>

<?php if ($status == 'success' && $auto): ?>
<div class="card" style="margin:40px auto; max-width:500px;">
    <div class="card-body text-center">
        <i class="fa fa-check-circle" style="color:green;font-size:60px"></i>
        <h3>Deposit Successful!</h3>
        <h5>Your Easypaisa deposit has been auto-verified and credited.</h5>
        <hr>
        <h4>آپ کی ایزی پیسہ ڈپازٹ کامیابی سے تصدیق ہو گئی ہے اور رقم جمع ہو گئی ہے۔</h4>
        <p>Amount: <b><?php echo $amount; ?> PKR</b> <br> Transaction ID: <b><?php echo $txid; ?></b></p>
        <a href="<?php echo cn('add_funds'); ?>" class="btn btn-primary mt-3">Back to Deposit / واپس جائیں</a>
    </div>
</div>
<?php elseif ($status == 'pending'): ?>
<div class="card" style="margin:40px auto; max-width:500px;">
    <div class="card-body text-center">
        <i class="fa fa-clock-o" style="color:orange;font-size:60px"></i>
        <h3>Deposit Pending Review</h3>
        <h5>Your Easypaisa deposit details have been sent and are pending admin approval.<br>
        You will be credited once reviewed.</h5>
        <hr>
        <h4>آپ کی ایزی پیسہ ڈپازٹ کی تفصیلات ایڈمن کو بھیج دی گئی ہیں اور منظوری کی منتظر ہیں۔</h4>
        <p>Amount: <b><?php echo $amount; ?> PKR</b> <br> Transaction ID: <b><?php echo $txid; ?></b></p>
        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-warning mt-2"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        <a href="<?php echo cn('add_funds'); ?>" class="btn btn-primary mt-3">Back to Deposit / واپس جائیں</a>
    </div>
</div>
<?php else: ?>
<div class="card" style="margin:40px auto; max-width:500px;">
    <div class="card-body text-center">
        <i class="fa fa-times-circle" style="color:red;font-size:60px"></i>
        <h3>Deposit Failed</h3>
        <h5>There was a problem processing your deposit. Please check your details or contact support.</h5>
        <hr>
        <h4>آپ کی ایزی پیسہ ڈپازٹ میں مسئلہ ہے۔ برائے مہربانی تفصیلات چیک کریں یا سپورٹ سے رابطہ کریں۔</h4>
        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger mt-2"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        <a href="<?php echo cn('add_funds'); ?>" class="btn btn-primary mt-3">Back to Deposit / واپس جائیں</a>
    </div>
</div>
<?php endif; ?>