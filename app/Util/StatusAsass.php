<?php


namespace App\Util;

class StatusAsass
{
    public static $PENDING = 'pending';
    public static $RECEIVED = 'received';
    public static $CONFIRMED = 'confirmed';
    public static $OVERDUE = 'overdue';
    public static $REFUNDED = 'refunded';
    public static $RECEIVED_IN_CASH = 'received_in_cash';
    public static $REFUND_REQUESTED = 'refund_requested';
    public static $CHARGEBACK_REQUESTED = 'chargeback_requested';
    public static $CHARGEBACK_DISPUTE = 'chargeback_dispute';
    public static $AWAITING_CHARGEBACK_REVERSAL = 'avaiting_chargeback_reversal';
    public static $DUNNING_REQUESTED = 'dunning_requested';
    public static $DUNNING_RECEIVED = 'dunning_received';
    public static $AWAITING_RISK_ANALYSIS = 'awaiting_risk_analysis';
}
