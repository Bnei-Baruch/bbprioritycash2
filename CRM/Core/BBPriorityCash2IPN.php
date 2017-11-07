<?php

class CRM_Core_Payment_BBPriorityCash2IPN extends CRM_Core_Payment_BaseIPN
{
    function __construct()
    {
        parent::__construct();
    }

    function single(&$input, &$ids, &$objects, $recur = FALSE, $first = FALSE)
    {
        $contribution = &$objects['contribution'];

        $transaction = new CRM_Core_Transaction();
        // check if contribution is already completed, if so we ignore this ipn
        if ($contribution->contribution_status_id == 1) {
            $transaction->commit();
            CRM_Core_Error::debug_log_message("returning since contribution has already been handled");
            echo "Success: Contribution has already been handled<p>";
            return TRUE;
        }

        $this->completeTransaction($input, $ids, $objects, $transaction, $recur);
        return true;
    }

    function getInput(&$input, &$ids)
    {
        $input = array(
            // GET Parameters
            'module' => self::retrieve('md', 'String', 'GET', true),
            'component' => self::retrieve('md', 'String', 'GET', true),
            'qfKey' => self::retrieve('qfKey', 'String', 'GET', false),
            'contributionID' => self::retrieve('contributionID', 'String', 'GET', true),
            'contactID' => self::retrieve('contactID', 'String', 'GET', true),
            'eventID' => self::retrieve('eventID', 'String', 'GET', false),
            'participantID' => self::retrieve('participantID', 'String', 'GET', false),
            'membershipID' => self::retrieve('membershipID', 'String', 'GET', false),
            'contributionPageID' => self::retrieve('contributionPageID', 'String', 'GET', false),
            'relatedContactID' => self::retrieve('relatedContactID', 'String', 'GET', false),
            'onBehalfDupeAlert' => self::retrieve('onBehalfDupeAlert', 'String', 'GET', false),
            'returnURL' => self::retrieve('returnURL', 'String', 'GET', false),
        );

        $ids = array(
            'contribution' => $input['contributionID'],
            'contact' => $input['contactID'],
        );
        if ($input['module'] == "event") {
            $ids['event'] = $input['eventID'];
            $ids['participant'] = $input['participantID'];
        } else {
            $ids['membership'] = $input['membershipID'];
            $ids['related_contact'] = $input['relatedContactID'];
            $ids['onbehalf_dupe_alert'] = $input['onBehalfDupeAlert'];
        }
    }

    function validateResult($paymentProcessor, &$input, &$ids, &$objects, $required = TRUE, $paymentProcessorID = NULL)
    {
        // This also initializes $objects
        parent::validateData($input, $ids, $objects, $required, $paymentProcessorID);

        $contribution = &$objects['contribution'];
        $input['amount'] = $contribution->total_amount;
        $contribution->txrn_id = 'cash';
        return true;
    }

    static function retrieve($name, $type, $location = 'POST', $abort = true)
    {
        static $store = null;
        $value = CRM_Utils_Request::retrieve($name, $type, $store, false, null, $location);
        if ($abort && $value === null) {
            CRM_Core_Error::debug_log_message("Could not find an entry for $name in $location");
            echo "Failure: Missing Parameter: $name<p>";
            exit();
        }
        return $value;
    }
}
