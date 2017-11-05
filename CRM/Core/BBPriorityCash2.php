<?php

/**
 *
 * @package BBPriorityCash [after Dummy Payment Processor]
 * @author Gregory Shilin <gshilin@gmail.com>
 */

require_once 'CRM/Core/Payment.php';

/**
 * BBPriorityCash payment processor
 */
class CRM_Core_BBPriorityCash2 extends CRM_Core_Payment
{
    /**
     * mode of operation: live or test
     *
     * @var object
     */
    protected $_mode = NULL;
    protected $_params = array();
    protected $_doDirectPaymentResult = array();

    /**
     * Set result from do Direct Payment for test purposes.
     *
     * @param array $doDirectPaymentResult
     *  Result to be returned from test.
     */
    public function setDoDirectPaymentResult($doDirectPaymentResult)
    {
        $this->_doDirectPaymentResult = $doDirectPaymentResult;
        if (empty($this->_doDirectPaymentResult['trxn_id'])) {
            $this->_doDirectPaymentResult['trxn_id'] = array();
        } else {
            $this->_doDirectPaymentResult['trxn_id'] = (array)$doDirectPaymentResult['trxn_id'];
        }
    }

    /**
     * We only need one instance of this object. So we use the singleton
     * pattern and cache the instance in this variable
     *
     * @var object
     * @static
     */
    static private $_singleton = NULL;
    /**
     * Payment Type Processor Name
     *
     * @var string
     */
    protected $_processorName = null;

    /**
     * Constructor.
     *
     * @param string $mode
     *   The mode of operation: live or test.
     *
     * @param $paymentProcessor
     *
     */
    public function __construct($mode, &$paymentProcessor)
    {
        $this->_mode = $mode;
        $this->_paymentProcessor = $paymentProcessor;
        $this->_processorName = 'BB Payment Cash';
    }

    /**
     * Singleton function used to manage this object
     *
     * @param string $mode the mode of operation: live or test
     *
     * @return object
     * @static
     *
     */
    static function &singleton($mode, &$paymentProcessor)
    {
        $processorName = $paymentProcessor["name"];
        if (self::$_singleton[$processorName] === NULL) {
            self::$_singleton[$processorName] = new self($mode, $paymentProcessor);
        }
        return self::$_singleton[$processorName];
    }

    /**
     * Submit a payment using Advanced Integration Method.
     *
     * @param array $params
     *   Assoc array of input parameters for this transaction.
     *
     * @return array
     *   the result in a nice formatted array (or an error object)
     */
    public function doDirectPayment(&$params)
    {
        return NULL;
    }

    /**
     * Are back office payments supported.
     *
     * E.g paypal standard won't permit you to enter a credit card associated with someone else's login.
     *
     * @return bool
     */
    protected function supportsLiveMode()
    {
        return TRUE;
    }

    /**
     * Generate error object.
     *
     * Throwing exceptions is preferred over this.
     *
     * @param string $errorCode
     * @param string $errorMessage
     *
     * @return CRM_Core_Error
     *   Error object.
     */
    public function &error($errorCode = NULL, $errorMessage = NULL)
    {
        $e = CRM_Core_Error::singleton();
        if ($errorCode) {
            $e->push($errorCode, 0, NULL, $errorMessage);
        } else {
            $e->push(9001, 0, NULL, 'Unknown System Error.');
        }
        return $e;
    }

    /**
     * This function checks to see if we have the right config values.
     *
     * @return string
     *   the error message if any
     */
    public function checkConfig()
    {
        return NULL;
    }

    /**
     * Get an array of the fields that can be edited on the recurring contribution.
     *
     * Some payment processors support editing the amount and other scheduling details of recurring payments, especially
     * those which use tokens. Others are fixed. This function allows the processor to return an array of the fields that
     * can be updated from the contribution recur edit screen.
     *
     * The fields are likely to be a subset of these
     *  - 'amount',
     *  - 'installments',
     *  - 'frequency_interval',
     *  - 'frequency_unit',
     *  - 'cycle_day',
     *  - 'next_sched_contribution_date',
     *  - 'end_date',
     *  - 'failure_retry_day',
     *
     * The form does not restrict which fields from the contribution_recur table can be added (although if the html_type
     * metadata is not defined in the xml for the field it will cause an error.
     *
     * Open question - would it make sense to return membership_id in this - which is sometimes editable and is on that
     * form (UpdateSubscription).
     *
     * @return array
     */
    public function getEditableRecurringScheduleFields()
    {
        return array('amount', 'next_sched_contribution_date');
    }

    function doTransferCheckout(&$params, $component = 'contribute')
    {
        /* DEBUG
            echo "<pre>";
            var_dump($this->_paymentProcessor);
            var_dump($params);
            echo "</pre>";
            exit();
        */

        if ($component != 'contribute' && $component != 'event') {
            CRM_Core_Error::fatal(ts('Component is invalid'));
        }

        if (array_key_exists('webform_redirect_success', $params)) {
            $returnURL = $params['webform_redirect_success'];
        } else {
            $url = ($component == 'event') ? 'civicrm/event/register' : 'civicrm/contribute/transact';
            $returnURL = CRM_Utils_System::url($url,
                "_qf_ThankYou_display=1&qfKey={$params['qfKey']}",
                TRUE, NULL, FALSE
            );
        }

        // Print the tpl to redirect to Pelecard
        $template = CRM_Core_Smarty::singleton();
        $template->assign('url', $returnURL);
        print $template->fetch('CRM/Core/Payment/Bbprioritycash2.tpl');

        CRM_Utils_System::civiExit();
    }
}
