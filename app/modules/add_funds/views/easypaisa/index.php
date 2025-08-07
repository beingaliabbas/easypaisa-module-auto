<style>
    .ff {
        background: #164964;
        border: 1px solid #00ffff;
        padding: 0.5rem;
        border-radius: 3px;
        margin-bottom: 1rem;
    }

</style>

<?php
  $option               = get_value($payment_params, 'option');
  $min_amount           = get_value($payment_params, 'min');
  $max_amount           = get_value($payment_params, 'max');
  $type                 = get_value($payment_params, 'type');
  $tnx_fee              = get_value($option, 'tnx_fee');
  $title                = get_value($option, 'title');
  $number               = get_value($option, 'number');
  $currency_rate_to_usd = get_value($option, 'rate_to_usd');
?>

<div class="add-funds-form-content">
  <form class="form actionAddFundsForm"
        action="<?= cn('easypaisa/create_payment'); ?>"
        method="POST">
    <div class="row">
      <div class="col-md-12">

        <!-- Logo -->
        <div class="form-group text-center">
          <img src="<?=BASE?>/assets/images/payments/easypaisa.png"
               alt="EasyPaisa Logo"
               style="width: 90%;">
          <p class="p-t-10">
            <small>
              <?= sprintf(
                    lang("you_can_deposit_funds_with_paypal_they_will_be_automaticly_added_into_your_account"),
                    'EasyPaisa'
                  ) ?>
            </small>
          </p>
        </div>

        <!-- Account Info -->
        <fieldset class="ff mt-1">
          <center>
            <div class="for-group text-center text-uppercase">
              <strong>EASYPAISA ACCOUNT TITLE:</strong>
              <h2>
                <span id="holderName"><?= $title; ?></span>
              </h2>
            </div>

            <div class="for-group text-center">
              <strong>EASYPAISA ACCOUNT NUMBER:</strong>
              <h2>
                <span id="accountNumber"><?= $number; ?></span>
                
              </h2>
            </div>
          </center>
        </fieldset>

        <!-- Amount -->
        <div class="form-group">
          <label><?= sprintf(lang("amount_usd"), 'PKR') ?></label>
          <input class="form-control square"
                 type="number"
                 name="amount"
                 min="<?= $min_amount; ?>"
                 max="<?= $max_amount; ?>"
                 placeholder="<?= $min_amount; ?>"
                 required>
        </div>

        <!-- Transaction ID -->
        <div class="form-group">
          <label>TRANSACTION ID (e.g. 0123456789)</label>
          <input class="form-control square"
                 type="text"
                 name="order_id"
                 placeholder="Enter your TX ID"
                 required>
        </div>

        <!-- Notes -->
        <div class="form-group">
          <label><?= lang("note"); ?></label>
          <ul>
            <?php if ($tnx_fee > 0): ?>
              <li>
                <?= lang("transaction_fee"); ?>:
                <strong><?= $tnx_fee; ?>%</strong>
              </li>
            <?php endif; ?>
            <li>
              <?= lang("Minimal_payment"); ?>:
              <strong><?= $min_amount; ?> PKR</strong>
            </li>
            <?php if ($max_amount > 0): ?>
              <li>
                <?= lang("Maximal_payment"); ?>:
                <strong><?= $max_amount; ?> PKR</strong>
              </li>
            <?php endif; ?>
            <?php if ($currency_rate_to_usd > 1): ?>
              <li>
                <?= lang("currency_rate"); ?>:
                1 USD = <strong><?= $currency_rate_to_usd; ?></strong> PKR
              </li>
            <?php endif; ?>
          </ul>
        </div>

        <!-- Agreement Checkbox -->
        <div class="form-group">
          <label class="custom-control custom-checkbox">
            <input type="checkbox"
                   class="custom-control-input"
                   name="agree"
                   value="1"
                   required>
            <span class="custom-control-label text-uppercase">
              <strong>
                <?= lang("yes_i_understand_after_the_funds_added_i_will_not_ask_fraudulent_dispute_or_chargeback") ?>
              </strong>
            </span>
          </label>
        </div>

        <!-- Submit -->
        <div class="form-actions left">
          <input type="hidden" name="payment_id"
                 value="<?= $payment_id; ?>">
          <input type="hidden" name="payment_method"
                 value="<?= $type; ?>">
          <button type="submit"
                  class="btn round btn-primary btn-min-width mr-1 mb-1"
                  style="border-radius: 5px !important;
                         background-color: #04a9f4;
                         color: #fff;
                         min-width: 120px;
                         margin: 15px 5px 5px 0;">
            <?= lang("Pay"); ?>
          </button>
        </div>

      </div>
    </div>
  </form>
</div>
