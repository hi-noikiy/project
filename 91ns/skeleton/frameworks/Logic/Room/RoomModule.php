<?php

namespace Micro\Frameworks\Logic\Room;

use Micro\Frameworks\Logic\Room\RoomMgr;
use Micro\Frameworks\Logic\Room\RoomOper;

class RoomModule{
    protected $roomMgr = null;
    protected $roomOper = null;

    public function __construct(){
    }

    public function getRoomMgrObject() {
        if ($this->roomMgr == null) {
            $this->roomMgr = new RoomMgr();
        }

        return $this->roomMgr;
    }

    public function getRoomOperObject() {
        if ($this->roomOper == null) {
            $this->roomOper = new RoomOper();
        }

        return $this->roomOper;
    }
}