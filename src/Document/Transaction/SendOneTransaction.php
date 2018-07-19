<?php

namespace Adshares\AdsOperator\Document\Transaction;

use Adshares\AdsOperator\Document\ArrayableInterface;
use Adshares\Ads\Entity\Transaction\SendOneTransaction as BaseSendOneTransaction;

class SendOneTransaction extends BaseSendOneTransaction implements ArrayableInterface
{
    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "size" => $this->size,
            "type" => $this->type,
            "blockId" => $this->blockId,
            "messageId" => $this->messageId,
            "amount" => $this->amount,
            "message" => $this->message,
            "msgId" => $this->msgId,
            "node" => $this->node,
            "senderAddress" => $this->senderAddress,
            "senderFee" => $this->senderFee,
            "signature" => $this->signature,
            "targetAddress" => $this->targetAddress,
            "targetNode" => $this->targetNode,
            "targetUser" => $this->targetUser,
            "time" => $this->time,
            "user" => $this->user,
        ];
    }
}
