<?php

namespace app\models\types;

enum HistoryEvent: string
{
    case EVENT_DEFAULT = 'default';

    case EVENT_CREATED_TASK = 'created_task';
    case EVENT_UPDATED_TASK = 'updated_task';
    case EVENT_COMPLETED_TASK = 'completed_task';

    case EVENT_INCOMING_SMS = 'incoming_sms';
    case EVENT_OUTGOING_SMS = 'outgoing_sms';

    case EVENT_INCOMING_CALL = 'incoming_call';
    case EVENT_OUTGOING_CALL = 'outgoing_call';

    case EVENT_INCOMING_FAX = 'incoming_fax';
    case EVENT_OUTGOING_FAX = 'outgoing_fax';

    case EVENT_CUSTOMER_CHANGE_TYPE = 'customer_change_type';
    case EVENT_CUSTOMER_CHANGE_QUALITY = 'customer_change_quality';
}