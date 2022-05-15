<?php

namespace app\tests\components\HistoryBodyFormatter;

use app\components\HistoryBodyFormatter\EventFormatter\HistoryCallEventFormatter;
use app\components\HistoryBodyFormatter\EventFormatter\HistoryCustomerChangeQualityEventFormatter;
use app\components\HistoryBodyFormatter\EventFormatter\HistoryCustomerChangeTypeEventFormatter;
use app\components\HistoryBodyFormatter\EventFormatter\HistoryDefaultEventFormatter;
use app\components\HistoryBodyFormatter\EventFormatter\HistorySmsEventFormatter;
use app\components\HistoryBodyFormatter\EventFormatter\HistoryTaskEventFormatter;
use app\components\HistoryBodyFormatter\HistoryBodyFormatter;
use app\models\Call;
use app\models\Customer;
use app\models\History;
use app\models\Sms;
use app\models\Task;
use app\models\types\HistoryEvent;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use yii\db\ActiveRecord;

class HistoryBodyFormatterTest extends TestCase
{
    /**
     * @dataProvider dataProviderTaskEvent
     */
    public function test_formatTaskEvent_taskHasTitle_returnExpectedResult(HistoryEvent $event): void
    {
        $formatter = $this->createHistoryFormatter();

        $historyRecord = $this->getRecord(History::class, ['event', 'eventText', 'task']);
        $historyRecord->setEvent($event);
        $historyRecord->eventText = 'Test Event Text';
        $historyRecord->task = $this->getRecord(Task::class, ['title']);
        $historyRecord->task->title = 'Title 123';

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Test Event Text: Title 123', $result);
    }

    /**
     * @dataProvider dataProviderTaskEvent
     */
    public function test_formatTaskEvent_taskWithEmptyTitle_returnExpectedResult(HistoryEvent $event): void
    {
        $formatter = $this->createHistoryFormatter();

        $historyRecord = $this->getRecord(History::class, ['event', 'eventText', 'task']);
        $historyRecord->setEvent($event);
        $historyRecord->eventText = 'Test Event Text';
        $historyRecord->task = $this->getRecord(Task::class, ['title']);
        $historyRecord->task->title = null;

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Test Event Text: ', $result);
    }

    public function dataProviderTaskEvent(): iterable
    {
        yield [HistoryEvent::EVENT_CREATED_TASK];
        yield [HistoryEvent::EVENT_COMPLETED_TASK];
        yield [HistoryEvent::EVENT_UPDATED_TASK];
    }

    /**
     * @dataProvider dataProviderSmsEvent
     */
    public function test_formatSmsEvent_smsWithNoEmptyMessage_returnExpectedResult(HistoryEvent $event): void
    {
        $formatter = $this->createHistoryFormatter();

        $historyRecord = $this->getRecord(History::class, ['event', 'sms']);
        $historyRecord->setEvent($event);
        $historyRecord->sms = $this->getRecord(Sms::class, ['message']);
        $historyRecord->sms->message = 'Sms message 123';

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Sms message 123', $result);
    }

    /**
     * @dataProvider dataProviderSmsEvent
     */
    public function test_formatSmsEvent_smsWithEmptyMessage_returnExpectedResult(HistoryEvent $event): void
    {
        $formatter = $this->createHistoryFormatter();

        $historyRecord = $this->getRecord(History::class, ['event', 'sms']);
        $historyRecord->setEvent($event);
        $historyRecord->sms = $this->getRecord(Sms::class, ['message']);
        $historyRecord->sms->message = null;

        $result = $formatter->format($historyRecord);

        $this->assertEquals('', $result);
    }

    public function dataProviderSmsEvent(): iterable
    {
        yield [HistoryEvent::EVENT_INCOMING_SMS];
        yield [HistoryEvent::EVENT_OUTGOING_SMS];
    }

    /**
     * @dataProvider dataProviderFaxEvent
     */
    public function test_formatFaxEvent_commonCase_returnExpectedResult(HistoryEvent $event): void
    {
        $formatter = $this->createHistoryFormatter();

        $historyRecord = $this->getRecord(History::class, ['event', 'eventText']);
        $historyRecord->setEvent($event);
        $historyRecord->eventText = 'Fax event text 123';

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Fax event text 123', $result);
    }

    public function dataProviderFaxEvent(): iterable
    {
        yield [HistoryEvent::EVENT_OUTGOING_FAX];
        yield [HistoryEvent::EVENT_INCOMING_FAX];
    }

    public function test_formatCustomerChangeTypeEvent_filledBothStates_returnExpectedResult(): void
    {
        $formatter = $this->createHistoryFormatter();

        $detail = [
            'changedAttributes' => [
                'type' => [
                    'old' => Customer::TYPE_LEAD,
                    'new' => Customer::TYPE_DEAL,
                ],
            ],
        ];

        $historyRecord = $this->getRecord(History::class, ['event', 'eventText', 'detail']);
        $historyRecord->setEvent(HistoryEvent::EVENT_CUSTOMER_CHANGE_TYPE);
        $historyRecord->eventText = 'Event text 123';
        $historyRecord->detail = json_encode($detail);

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Event text 123 Lead to Deal', $result);
    }

    public function test_formatCustomerChangeTypeEvent_NotSetOldState_returnExpectedResult(): void
    {
        $formatter = $this->createHistoryFormatter();

        $detail = [
            'changedAttributes' => [
                'type' => [
                    'old' => null,
                    'new' => Customer::TYPE_DEAL,
                ],
            ],
        ];

        $historyRecord = $this->getRecord(History::class, ['event', 'eventText', 'detail']);
        $historyRecord->setEvent(HistoryEvent::EVENT_CUSTOMER_CHANGE_TYPE);
        $historyRecord->eventText = 'Event text 123';
        $historyRecord->detail = json_encode($detail);

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Event text 123 not set to Deal', $result);
    }

    public function test_formatCustomerChangeTypeEvent_NotSetNewState_returnExpectedResult(): void
    {
        $formatter = $this->createHistoryFormatter();

        $detail = [
            'changedAttributes' => [
                'type' => [
                    'old' => Customer::TYPE_LEAD,
                    'new' => null,
                ],
            ],
        ];

        $historyRecord = $this->getRecord(History::class, ['event', 'eventText', 'detail']);
        $historyRecord->setEvent(HistoryEvent::EVENT_CUSTOMER_CHANGE_TYPE);
        $historyRecord->eventText = 'Event text 123';
        $historyRecord->detail = json_encode($detail);

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Event text 123 Lead to not set', $result);
    }

    public function test_formatCustomerChangeTypeEvent_NoChangedAttribute_returnExpectedResult(): void
    {
        $formatter = $this->createHistoryFormatter();

        $detail = [
            'changedAttributes' => [
                'type' => [
                    'new' => Customer::TYPE_DEAL,
                ],
            ],
        ];

        $historyRecord = $this->getRecord(History::class, ['event', 'eventText', 'detail']);
        $historyRecord->setEvent(HistoryEvent::EVENT_CUSTOMER_CHANGE_TYPE);
        $historyRecord->eventText = 'Event text 123';
        $historyRecord->detail = json_encode($detail);

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Event text 123 not set to Deal', $result);
    }

    public function test_formatCustomerChangeTypeEvent_NoDetailInformation_returnExpectedResult(): void
    {
        $formatter = $this->createHistoryFormatter();

        $detail = [
            'changedAttributes' => [
            ],
        ];

        $historyRecord = $this->getRecord(History::class, ['event', 'eventText', 'detail']);
        $historyRecord->setEvent(HistoryEvent::EVENT_CUSTOMER_CHANGE_TYPE);
        $historyRecord->eventText = 'Event text 123';
        $historyRecord->detail = json_encode($detail);

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Event text 123 not set to not set', $result);
    }

    public function test_formatCustomerChangeTypeEvent_undefinedTypeText_returnExpectedResult(): void
    {
        $formatter = $this->createHistoryFormatter();

        $detail = [
            'changedAttributes' => [
                'type' => [
                    'old' => 'Old value 123',
                    'new' => 'New value 123',
                ],
            ],
        ];

        $historyRecord = $this->getRecord(History::class, ['event', 'eventText', 'detail']);
        $historyRecord->setEvent(HistoryEvent::EVENT_CUSTOMER_CHANGE_TYPE);
        $historyRecord->eventText = 'Event text 123';
        $historyRecord->detail = json_encode($detail);

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Event text 123 Old value 123 to New value 123', $result);
    }

    public function test_formatCustomerChangeQualityEvent_commonCase_returnExpectedResult(): void
    {
        $formatter = $this->createHistoryFormatter();

        $detail = [
            'changedAttributes' => [
                'quality' => [
                    'old' => Customer::QUALITY_ACTIVE,
                    'new' => Customer::QUALITY_UNASSIGNED,
                ],
            ],
        ];

        $historyRecord = $this->getRecord(History::class, ['event', 'eventText', 'detail']);
        $historyRecord->setEvent(HistoryEvent::EVENT_CUSTOMER_CHANGE_QUALITY);
        $historyRecord->eventText = 'Event text 123';
        $historyRecord->detail = json_encode($detail);

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Event text 123 Active to Unassigned', $result);
    }

    /**
     * @dataProvider dataProviderCallEvent
     */
    public function test_formatCallEvent_filledDataWithNoEmptyComment_returnExpectedResult(HistoryEvent $event): void
    {
        $formatter = $this->createHistoryFormatter();

        $historyRecord = $this->getRecord(History::class, ['event', 'call']);
        $historyRecord->setEvent($event);
        $historyRecord->call = $this->getRecord(Call::class, ['totalStatusText', 'comment']);
        $historyRecord->call->comment = 'Comment';
        $historyRecord->call->totalStatusText = 'Total status text 123';

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Total status text 123', $result);
    }

    /**
     * @dataProvider dataProviderCallEvent
     */
    public function test_formatCallEvent_filledDataWithEmptyComment_returnExpectedResult(HistoryEvent $event): void
    {
        $formatter = $this->createHistoryFormatter();

        $historyRecord = $this->getRecord(History::class, ['event', 'call']);
        $historyRecord->setEvent($event);
        $historyRecord->call = $this->getRecord(Call::class, ['totalStatusText', 'comment']);
        $historyRecord->call->comment = null;
        $historyRecord->call->totalStatusText = 'Total status text 123';

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Total status text 123', $result);
    }

    /**
     * @dataProvider dataProviderCallEvent
     */
    public function test_formatCallEvent_withNoCall_returnExpectedResult(HistoryEvent $event): void
    {
        $formatter = $this->createHistoryFormatter();

        $historyRecord = $this->getRecord(History::class, ['event', 'call']);
        $historyRecord->setEvent($event);
        $historyRecord->call = null;

        $result = $formatter->format($historyRecord);

        $this->assertEquals('<i>Deleted</i> ', $result);
    }

    public function dataProviderCallEvent(): iterable
    {
        yield [HistoryEvent::EVENT_INCOMING_CALL];
        yield [HistoryEvent::EVENT_OUTGOING_CALL];
    }

    public function test_formatUndefinedEvent_commonCase_returnExpectedResult(): void
    {
        $formatter = $this->createHistoryFormatter();

        $historyRecord = $this->getRecord(History::class, ['event', 'eventText']);
        $historyRecord->setEvent(HistoryEvent::EVENT_DEFAULT);
        $historyRecord->eventText = 'Undefined event text 123';

        $result = $formatter->format($historyRecord);

        $this->assertEquals('Undefined event text 123', $result);
    }

    private function getRecord(string $recordClass, array $attributes = []): History|ActiveRecord|MockObject
    {
        $mock = $this->getMockBuilder($recordClass)
            ->onlyMethods(['attributes'])
            ->getMock();

        $mock
            ->method('attributes')
            ->willReturn($attributes);

        return $mock;
    }

    private function createHistoryFormatter(): HistoryBodyFormatter
    {
        $formatters = [
            new HistoryDefaultEventFormatter(),
            new HistoryTaskEventFormatter(),
            new HistorySmsEventFormatter(),
            new HistoryCustomerChangeTypeEventFormatter(),
            new HistoryCustomerChangeQualityEventFormatter(),
            new HistoryCallEventFormatter(),
        ];

        return new HistoryBodyFormatter($formatters);
    }
}
