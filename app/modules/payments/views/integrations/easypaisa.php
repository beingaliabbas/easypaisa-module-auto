<div id="main-modal-content">
  <div class="modal-right">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <?php
          $id = (!empty($payment->id))? $payment->id: '';
          if ($id != "") {
            $url = cn($module."/ajax_update/$id");
          }else{
            $url = cn($module."/ajax_update");
          }
        ?>
        <form class="form actionForm" action="<?php echo $url?>" data-redirect="<?php echo cn($module); ?>" method="POST">
          <div class="modal-header bg-pantone">
            <h4 class="modal-title"><i class="fa fa-edit"></i> <?php echo $payment->name; ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            </button>
          </div>
          <div class="modal-body">
            <div class="form-body">
              <div class="row justify-content-md-center">

                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label class="form-label" ><?php echo lang("method_name"); ?></label>
                    <input type="hidden" class="form-control square" name="payment_params[type]" value="<?php echo $payment->type; ?>">
                    <input type="text" class="form-control square" name="payment_params[name]" value="<?php echo (!empty($payment->name))? $payment->name : '' ; ?>">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label" ><?php echo lang("Minimal_payment"); ?></label>
                    <input type="number" class="form-control square" name="payment_params[min]" value="<?php echo (!empty($payment->min))? $payment->min : '' ; ?>">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label" ><?php echo lang("Maximal_payment"); ?></label>
                    <input type="number" class="form-control square" name="payment_params[max]" value="<?php echo (!empty($payment->max))? $payment->max : '' ; ?>">
                  </div>
                </div>
               
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label" ><?php echo lang("new_users"); ?></label>
                    <select name="payment_params[new_users]" class="form-control square">
                      <option value="1" <?php echo (!empty($payment->new_users) && $payment->new_users == 1)? 'selected' : '' ; ?>><?php echo lang("allowed"); ?></option>
                      <option value="0" <?php echo (isset($payment->new_users) && $payment->new_users != 1)? 'selected' : '' ; ?>><?php echo lang("not_allowed"); ?></option>
                    </select>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label"><?php echo lang("Status"); ?></label>
                    <select name="payment_params[status]" class="form-control square">
                      <option value="1" <?php echo (!empty($payment->status) && $payment->status == 1) ? 'selected' : '' ; ?>><?php echo lang("Active")?></option>
                      <option value="0" <?php echo (isset($payment->status) && $payment->status != 1) ? 'selected' : '' ; ?>><?php echo lang("Deactive")?></option>
                    </select>
                  </div>
                </div>
                <?php
                  $payment_params = json_decode($payment->params);
                  $option = $payment_params->option;
                ?>
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="form-label"><?=lang("transaction_fee")?></label>
                    <select name="payment_params[option][tnx_fee]" class="form-control square">
                      <?php
                        for ($i = 0; $i <= 30; $i++) {
                      ?>
                      <option value="<?=$i?>" <?=(isset($option->tnx_fee) && $option->tnx_fee == $i)? "selected" : ''?>><?php echo $i; ?>%</option>
                      <?php } ?>
                    </select>
                  </div>
                </div> 
                 
                <div class="col-md-12">
                  <hr>

                  <div class="form-group">
                    <label class="form-label">Account Title</label>
                    <input class="form-control" name="payment_params[option][title]" value="<?php echo (isset($option->title)) ? $option->title : ''; ?>">
                  </div>

                  <div class="form-group">
                    <label class="form-label">Account Number</label>
                    <input class="form-control" name="payment_params[option][number]" value="<?php echo (isset($option->number)) ? $option->number : ''; ?>">
                  </div>

                  <hr>

                  <div class="form-group">
                    <label class="form-label">IMAP Host <small>(e.g. {mail.beastsmm.pk:993/imap/ssl/novalidate-cert}INBOX)</small></label>
                    <input class="form-control" name="payment_params[option][imap_host]" value="<?php echo (isset($option->imap_host)) ? $option->imap_host : ''; ?>">
                  </div>

                  <div class="form-group">
                    <label class="form-label">IMAP User (Email Address)</label>
                    <input class="form-control" name="payment_params[option][imap_user]" value="<?php echo (isset($option->imap_user)) ? $option->imap_user : ''; ?>">
                  </div>

                  <div class="form-group">
                    <label class="form-label">IMAP Password</label>
                    <input class="form-control" name="payment_params[option][imap_pass]" type="password" value="<?php echo (isset($option->imap_pass)) ? $option->imap_pass : ''; ?>">
                  </div>

                  <div class="form-group">
                    <label class="form-label"><?=lang("currency_rate")?></label>
                    <div class="input-group">
                      <span class="input-group-prepend">
                        <span class="input-group-text">1USD =</span>
                      </span>
                      <input type="text" class="form-control text-right" name="payment_params[option][rate_to_usd]" value="<?php echo (isset($option->rate_to_usd)) ? $option->rate_to_usd : 76; ?>">
                      <span class="input-group-append">
                        <span class="input-group-text">PKR</span>
                      </span>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-group">
                      <span class="text-info"><strong>Note:</strong></span>
                      <ul class="small">
                        <li>
                          Enter your cPanel/hosting mail IMAP credentials above.<br>
                          Example for Host: <code>{mail.beastsmm.pk:993/imap/ssl/novalidate-cert}INBOX</code>
                        </li>
                        <li>
                          Easypaisa transaction verification will use your own email's IMAP, not Gmail.
                        </li>
                        <li>
                          <b>For security:</b> Use a dedicated mailbox for payments and a strong password.
                        </li>
                      </ul>
                    </div>
                  </div>

                </div>

              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn round btn-primary btn-min-width mr-1 mb-1"><?php echo lang("Submit")?></button>
            <button type="button" class="btn round btn-default btn-min-width mr-1 mb-1" data-dismiss="modal"><?php echo lang("Cancel")?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>