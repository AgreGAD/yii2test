<?php

namespace app\models;

use app\models\traits\ObjectNameTrait;
use app\models\types\HistoryEvent;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%history}}".
 *
 * @property integer $id
 * @property string $ins_ts
 * @property integer $customer_id
 * @property string $event
 * @property string $object
 * @property integer $object_id
 * @property string $message
 * @property string $detail
 * @property integer $user_id
 *
 * @property string $eventText
 *
 * @property Customer $customer
 * @property User $user
 *
 * @property Task $task
 * @property Sms $sms
 * @property Call $call
 */
class History extends ActiveRecord
{
    use ObjectNameTrait;

    public static function tableName(): string
    {
        return '{{%history}}';
    }

    public function rules(): array
    {
        return [
            [['ins_ts'], 'safe'],
            [['customer_id', 'object_id', 'user_id'], 'integer'],
            [['event'], 'required'],
            [['message', 'detail'], 'string'],
            [['event', 'object'], 'string', 'max' => 255],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ins_ts' => Yii::t('app', 'Ins Ts'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'event' => Yii::t('app', 'Event'),
            'object' => Yii::t('app', 'Object'),
            'object_id' => Yii::t('app', 'Object ID'),
            'message' => Yii::t('app', 'Message'),
            'detail' => Yii::t('app', 'Detail'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return array
     */
    public static function getEventTexts(): array
    {
        return [
            HistoryEvent::EVENT_CREATED_TASK->value => 'Task created',
            HistoryEvent::EVENT_UPDATED_TASK->value => 'Task updated',
            HistoryEvent::EVENT_COMPLETED_TASK->value => 'Task completed',
            HistoryEvent::EVENT_INCOMING_SMS->value => 'Incoming message',
            HistoryEvent::EVENT_OUTGOING_SMS->value => 'Outgoing message',
            HistoryEvent::EVENT_CUSTOMER_CHANGE_TYPE->value => 'Type changed',
            HistoryEvent::EVENT_CUSTOMER_CHANGE_QUALITY->value => 'Property changed',
            HistoryEvent::EVENT_OUTGOING_CALL->value => 'Outgoing call',
            HistoryEvent::EVENT_INCOMING_CALL->value => 'Incoming call',
            HistoryEvent::EVENT_INCOMING_FAX->value => 'Incoming fax',
            HistoryEvent::EVENT_OUTGOING_FAX->value => 'Outgoing fax',
        ];
    }

    public static function getEventTextByEvent(HistoryEvent $event): string
    {
        return static::getEventTexts()[$event->value] ?? $event->value;
    }

    public function getEventAsType(): HistoryEvent
    {
        return HistoryEvent::from($this->event);
    }

    public function setEvent(HistoryEvent $event): void
    {
        $this->event = $event->value;
    }

    public function getEventText(): string
    {
        return static::getEventTextByEvent($this->getEventAsType());
    }

    /**
     * @param $attribute
     * @return null
     */
    public function getDetailChangedAttribute(string $attribute)
    {
        $detail = json_decode($this->detail);
        return isset($detail->changedAttributes->{$attribute}) ? $detail->changedAttributes->{$attribute} : null;
    }

    /**
     * @param string $attribute
     * @return null
     */
    public function getDetailOldValue(string $attribute)
    {
        $detail = $this->getDetailChangedAttribute($attribute);
        return isset($detail->old) ? $detail->old : null;
    }

    /**
     * @param string $attribute
     * @return null
     */
    public function getDetailNewValue(string $attribute)
    {
        $detail = $this->getDetailChangedAttribute($attribute);
        return isset($detail->new) ? $detail->new : null;
    }
}
