<?php declare(strict_types=1);

namespace SplitSens;

class SplitSensTest extends \PHPUnit_Framework_TestCase
{
    private $sens;

    public function setup()
    {
        $this->sens = new SplitSens();
    }

    public function testBasic()
    {
        $sens = $this->sens;
        $desc =<<<DOC
メンズライクな印象を与えるWボタンのロングコートです。
シンプルな縦長シルエットにポケットなどでアクセントを付けたひと品★
風合いのいいウール混素材を使用し、柔らかで軽く、優れた保温性を発揮◎
ハイネックニットやスキニーパンツ合わせでモードに決めるのがGOODです。

◆2色：グレー／ブラック
DOC;

        $result = [
            0 => 'メンズライクな印象を与えるWボタンのロングコートです',
            1 => 'シンプルな縦長シルエットにポケットなどでアクセントを付けたひと品',
            2 => '風合いのいいウール混素材を使用し柔らかで軽く優れた保温性を発揮',
            3 => 'ハイネックニットやスキニーパンツ合わせでモードに決めるのがGOODです',
            4 => '2色グレーブラック'
        ];
        $this->assertEquals($sens->split($desc), $result);
    }

    public function testDecteDelimiter()
    {
        $sens = $this->sens;
        $delimiter = ['.', ';', '?', '.?', '.?;', '.?;!'];
        array_map(array($this, 'assertEqualsDelimiter'), $delimiter);

        $this->assertEquals($sens->split("a\r\nb"), ['a','b']);
        $this->assertEquals($sens->split("a@\r\nb"), ['a','b']);
        $this->assertEquals($sens->split("111\r\nb"), ['b']);
    }

    private function assertEqualsDelimiter($delimiter)
    {
        $sens = $this->sens;
        $this->assertEquals($sens->split($delimiter), [$delimiter]);
        $this->assertEquals($sens->split('a' . $delimiter), ['a']);
        $this->assertEquals($sens->split('a' . $delimiter . 'b' . $delimiter), ['a', 'b']);
        $this->assertEquals($sens->split('a' . $delimiter . 'b' . $delimiter . 'c' . $delimiter), ['a', 'b', 'c']);

    }

    public function testValidateParam()
    {
        # 파라미터 유효성 테스트
        $sens = $this->sens;
        $this->assertEquals($sens->split(), []);
        $this->assertEquals($sens->split(null), []);
        $this->assertEquals($sens->split(''), []);
        $this->assertEquals($sens->split(' '), []);
        $this->assertEquals($sens->split('  '), []);
        $this->assertEquals($sens->split('   '), []);

        $this->assertEquals($sens->split(0), []);
        $this->assertEquals($sens->split(1), []);
        $this->assertEquals($sens->split(-1), []);
        $this->assertEquals($sens->split(2), []);
        $this->assertEquals($sens->split(2123), []);

        $this->assertEquals($sens->split('0'), []);
        $this->assertEquals($sens->split('1'), []);
        $this->assertEquals($sens->split('-1'), []);
        $this->assertEquals($sens->split('2'), []);
        $this->assertEquals($sens->split('2123'), []);

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

        return $sens;
    }

    public function testRemoveSpecialChar()
    {
        # 특수문자 제거
        $sens = $this->sens;
        $this->assertEquals($sens->split('['), []);
        $this->assertEquals($sens->split('[('), []);
        $this->assertEquals($sens->split('[()'), []);
        $this->assertEquals($sens->split('a[e(c)'), ['aec']);
        $this->assertEquals($sens->split('a[e(c).a[e(c)'), ['aec', 'aec']);
    }

    public function testRemoveRangeSymbol()
    {
        # 【.*】 네용 제거
        $sens = $this->sens;
        $this->assertEquals($sens->split('【aa】'), []);
        $this->assertEquals($sens->split('【aa】【aa】'), []);
        $this->assertEquals($sens->split('【aa】【aa】【aa】'), []);
        $this->assertEquals($sens->split('a【aa】b【aa】c【aa】'), ['abc']);
        $this->assertEquals(
            $sens->split('a【aa】b【aa】c【aa】; a【aa】b【aa】c【aa】'),
            ['abc', 'abc']
        );
        return $sens;
    }

    public function testStripTags()
    {
        # tag 제거
        $sens = $this->sens;
        $this->assertEquals($sens->split('<a></a>'), []);
        $this->assertEquals($sens->split('<br>'), []);
        $this->assertEquals($sens->split('<br />'), []);

        $this->assertEquals($sens->split('a &nbsp; b<br />'), ['a  b']);
        return $sens;
    }

    public function testRemoveHtmlSpecChar()
    {
        # html special char 제거
        $sens = $this->sens;
        $this->assertEquals($sens->split('&nbsp;'), []);
        $this->assertEquals($sens->split('&euro;'), []);
        $this->assertEquals($sens->split('&#x20AC;'), []);
        $this->assertEquals($sens->split('&nbsp; &#x20AC;'), []);

        return $sens;
    }
}
