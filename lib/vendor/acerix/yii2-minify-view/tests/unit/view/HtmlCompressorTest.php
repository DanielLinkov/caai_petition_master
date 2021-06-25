<?php
/**
 * HtmlCompressorTest.php
 * @author Revin Roman
 * @link https://rmrevin.ru
 */

namespace acerix\yii\minify\tests\unit\view;

use acerix\yii\minify\HtmlCompressor;
use acerix\yii\minify\tests\unit\TestCase;

/**
 * Class HtmlCompressorTest
 * @package acerix\yii\minify\tests\unit\view
 */
class HtmlCompressorTest extends TestCase
{

    public function testMain()
    {
        $pre_html = '<pre>  Inside pre
    <span>test</span></pre>';

        $input_str = ' ' . '
 <div class="  test"  data>
  <p>Inside text</p>
   <!-- comment -->
    '.$pre_html.'
 </div>
  ';

        $this->assertEquals(
            '<div
class="  test"  data><p>Inside text</p>'.$pre_html.'</div>',
            HtmlCompressor::compress($input_str)
        );

    }

    public function testPerformance()
    {
        $html_repeat_times = 4096;

        $input_str = str_repeat("<div> \n\n\n <p> </p> \n\n\n  </div>", $html_repeat_times);
        $expected_str = str_repeat("<div><p></p></div>", $html_repeat_times);

        // Start timer
        $start_time = microtime(true);

        $compressed_str = HtmlCompressor::compress($input_str);

        // End timer
        $total_time = microtime(true) - $start_time;

        $this->assertEquals(
            $expected_str,
            $compressed_str
        );

        $this->assertLessThan(0.1, $total_time, 'Took too long');

    }

}
