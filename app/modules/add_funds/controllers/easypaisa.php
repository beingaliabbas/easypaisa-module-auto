<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class easypaisa extends MX_Controller {
	public $tb_users;
	public $tb_transaction_logs;
	public $tb_payments;
	public $tb_payments_bonuses;
	public $paypal;
	public $payment_type;
	public $payment_id;
	public $currency_code;
	public $payment_lib;
	public $mode;
	
	public $pm_gmail;
	public $pm_gmail_pass;
	public $currency_rate_to_usd;

	public function __construct($payment = ""){
		parent::__construct();
		$this->load->model('add_funds_model', 'model');

		$this->tb_users            = USERS;
		$this->tb_transaction_logs = TRANSACTION_LOGS;
		$this->tb_payments         = PAYMENTS_METHOD;
		$this->tb_payments_bonuses = PAYMENTS_BONUSES;
		$this->payment_type		   = get_class($this);
		$this->currency_code       = get_option("currency_code", "USD");
		if ($this->currency_code == "") {
			$this->currency_code = 'USD';
		}
		if (!$payment) {
			$payment = $this->model->get('id, type, name, params', $this->tb_payments, ['type' => $this->payment_type]);
		}
		$this->payment_id 	      = $payment->id;
		$params  			      = $payment->params;
		$option                   = get_value($params, 'option');
		$this->mode               = get_value($option, 'environment');
		$this->payment_fee        = get_value($option, 'tnx_fee');

		// Payment Option
		$this->pm_gmail          		= get_value($option, 'gmail');
		$this->pm_gmail_pass       		= get_value($option, 'gmail_password');
		$this->currency_rate_to_usd     = get_value($option, 'rate_to_usd');
	}

	public function index(){
		redirect(cn('add_funds'));
	}



	public function create_payment($data_payment = ""){
		_is_ajax($data_payment['module']);
		$amount = $data_payment['amount'];
		if (!$amount) {
			_validation('error', lang('There_was_an_error_processing_your_request_Please_try_again_later'));
		}

		if (!$this->pm_gmail || !$this->pm_gmail_pass) {
			_validation('error', lang('this_payment_is_not_active_please_choose_another_payment_or_contact_us_for_more_detail'));
		}

		$tx_orderID = post('order_id'); 

		if ($tx_orderID == "") {
			_validation('error', 'Please enter your Order ID');
		}

		if (strlen($tx_orderID) < 10) {
			_validation('error', 'The Order ID you entered was not valid');
		}

		if (strlen($tx_orderID) > 50) {
			_validation('error', 'The Order ID you entered was not valid');
		}
		
		/*----------  Check exists Order ID  ----------*/
		$exists_tx = $this->model->get('id', $this->tb_transaction_logs,  ["transaction_id" => $tx_orderID, 'type' => $this->payment_type]);

		if ($exists_tx) {
			_validation('error', 'Transaction failed! Make sure you enter same amount, Order ID you paid.');
		}

        $connection = imap_open(trim("{imap.gmail.com:993/imap/ssl/novalidate-cert}"), trim($this->pm_gmail), trim($this->pm_gmail_pass)) or die('Error : ' . imap_last_error());


		if($connection == false){
			_validation('error', 'Please try again later or raise a new ticket');
		}

		//Connection established to the mail server, now search the transaction
        $matchTxn = imap_search($connection, 'TEXT "'.$tx_orderID . '"', SE_FREE, "UTF-8");

        if ($matchTxn !== false) {
            //get message id
            $data = var_export($matchTxn, true);
            $whatIWant = substr($data, strpos($data, ">") + 1);
            $to = ", )";
            $c = chop($whatIWant, $to);
            $d = str_replace(",", "", $c);
            $e = preg_replace('/\s+/', '', $d);

            //only if the certain amount was sent
            $headerInfo = imap_body($connection, $e);
			
            $amount1 = "You have Received Rs ".$amount."";
            if (!strpos($headerInfo, $amount1)) {
            	_validation('error', 'Transaction failed. Invalid amount!');
			}

			// Insert Transaction Log
			$converted_amount = $amount / $this->currency_rate_to_usd;
			$data_tnx_log = array(
				"ids" 				=> ids(),
				"uid" 				=> session("uid"),
				"type" 				=> $this->payment_type,
				"transaction_id" 	=> $tx_orderID,
				"amount" 	        => round($converted_amount, 4) ,
				'txn_fee'           => round($converted_amount * ($this->payment_fee / 100), 4),
				"note" 	            => $amount,
				"status" 	        => 1,
				"created" 			=> NOW,
			);
			$transaction_log_id = $this->db->insert($this->tb_transaction_logs, $data_tnx_log);

			// Update Balance 
    		require_once 'add_funds.php';
    		$add_funds = new add_funds();
    		$add_funds->add_funds_bonus_email((object)$data_tnx_log, $this->payment_id);
    		
			_validation('success', 'The Transaction ID was verified successfully');

			set_session("transaction_id", $tx_orderID);
			redirect(cn("add_funds/success"));

		}else{
			_validation('error', 'The transaction ID you entered was not found.');
		}
	}

}
             