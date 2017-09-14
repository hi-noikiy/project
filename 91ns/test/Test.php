<?php

namespace Test;

/**
 * Class Test
 */
class Test extends \TestBase {

    public function testTestCase() {

        $this->assertEquals('works',
            'works',
            'This is OK'
        );

        $this->assertEquals('works',
            'works1',
            'This wil fail'
        );
    }
}