<?php declare(strict_types=1);

namespace SplitSens;

class SplitSensTest extends \PHPUnit_Framework_TestCase
{
    public function testSplitSens()
    {
        # 문단을 문장으로 분리
        $sens = new SplitSens();
        $this->assertEquals($sens->split(), []);
        $this->assertEquals($sens->split(null), []);
        $this->assertEquals($sens->split(''), []);
        $this->assertEquals($sens->split(' '), []);
        $this->assertEquals($sens->split('  '), []);
        $this->assertEquals($sens->split('   '), []);

        $this->assertEquals($sens->split(0), ['0']);
        $this->assertEquals($sens->split(1), ['1']);
        $this->assertEquals($sens->split(-1), ['-1']);
        $this->assertEquals($sens->split(2), ['2']);
        $this->assertEquals($sens->split(2123), ['2123']);

        $this->assertEquals($sens->split('a'), ['a']);
        $this->assertEquals($sens->split('aa'), ['aa']);
        $this->assertEquals($sens->split('aaa'), ['aaa']);
        $this->assertEquals($sens->split('aaba'), ['aaba']);

        $this->assertEquals($sens->split('a '), ['a']);
        $this->assertEquals($sens->split('a  '), ['a']);
        $this->assertEquals($sens->split('a   '), ['a']);

        $this->assertEquals($sens->split('a b'), ['a b']);
        $this->assertEquals($sens->split('a b c'), ['a b c']);
        $this->assertEquals($sens->split('a b c d'), ['a b c d']);

        $this->assertEquals($sens->split('.'), ['.']);
        $this->assertEquals($sens->split('a.'), ['a']);
        $this->assertEquals($sens->split('a. b.'), ['a', 'b']);
        $this->assertEquals($sens->split('a. b. c.'), ['a', 'b', 'c']);

        $this->assertEquals($sens->split(';'), [';']);
        $this->assertEquals($sens->split('a;'), ['a']);
        $this->assertEquals($sens->split('a; b;'), ['a', 'b']);
        $this->assertEquals($sens->split('a; b; c;'), ['a', 'b', 'c']);
    }
}
