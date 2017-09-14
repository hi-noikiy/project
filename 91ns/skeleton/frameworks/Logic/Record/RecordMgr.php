<?php

namespace Micro\Frameworks\Logic\Record;

use Micro\Frameworks\Logic\Record\ChatRecord;

/**
 * 记录管理模块（水军聊天记录、用户操作记录等）
 */
class RecordMgr{

    protected $chatObject = null;

    public function __construct(){
    }

    public function getChatObject() {
        if ($this->chatObject == null) {
            $this->chatObject = new ChatRecord();
        }

        return $this->chatObject;
    }
}
