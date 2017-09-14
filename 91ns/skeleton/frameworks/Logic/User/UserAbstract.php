<?php

namespace Micro\Frameworks\Logic\User;

abstract class UserAbstract {

    abstract function getUserInfoObject();

    abstract function getUserItemsObject();

    abstract function getUserFoucusObject();

    abstract function getUserFansObject();

    abstract function getUserApplyObject();

    abstract function getUserSecurityObject();

    abstract function getUserActivityObject();

    abstract function getUserInformationObject();
}
