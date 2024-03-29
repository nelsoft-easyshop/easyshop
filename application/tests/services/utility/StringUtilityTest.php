<?php
/**
 * @backupGlobals disabled
 */
class StringUtilityTest extends PHPUnit_Framework_TestCase
{
    private $stringUtility;
    private $CI;

    /**
     * Constructor
     */
    public function __construct($name = null, array $data = [], $dataName = "")
    {
        $CI =& get_instance();
        $this->stringUtility = $CI->kernel->serviceContainer['string_utility'];
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Test for paramsToArray from string utility
     * @dataProvider paramsToArrayProvider
     */
    public function testParamsToArray($inputString, $expectedArray)
    {
        $this->assertEquals($this->stringUtility->paramsToArray($inputString), $expectedArray);
    }

    /**
     * dataProvider testParamsToArray
     * @return array
     */
    public function paramsToArrayProvider()
    {
        return [
            [
                "first=value&arr[]=foo+bar&arr[]=baz",
                [
                    'first' => 'value',
                    'arr' => [
                        'foo bar',
                        'baz'
                    ]
                ]
            ]
        ];
    }

    /**
     * Test for cleanString from string utility
     * @dataProvider cleanStringProvider
     */
    public function testCleanString($inputString, $expectedString)
    {
        $this->assertEquals($this->stringUtility->cleanString($inputString), $expectedString);
    }

    /**
     * String collection dataProvider for testCleanString
     * @return array
     */
    public function cleanStringProvider()
    {
        return [
            ["&& foo bar - baz && 00 À© --", "foo-bar-baz-00"],
            ["foo bar baz", "foo-bar-baz"]
        ];
    }

    /**
     * Test for purifyHTML from string utility
     * @dataProvider purifyHTMLProvider
     */
    public function testPurifyHTML($inputString, $expectedString)
    {
        $this->assertEquals($this->stringUtility->purifyHTML($inputString), $expectedString);
    }

    /**
     * String collection dataProvider for testPurifyHTML
     * @return array
     */
    public function purifyHTMLProvider()
    {
        return [
            ["<script>alert('XSS')</script>", ""],
            ["<STYLE>.XSS{background-image:url(\"javascript:alert('XSS')\");}</STYLE><A CLASS=XSS></A>", '<a class="XSS"></a>']
        ];
    }

    /**
     * Test for removeNonUTF from string utility
     * @dataProvider removeNonUTFProvider
     */
    public function testRemoveNonUTF($inputString, $expectedString)
    {
        $this->assertEquals($this->stringUtility->removeNonUTF($inputString), $expectedString);
    }

    /**
     * dataProvider for testRemoveNonUTF
     * @return array
     */
    public function removeNonUTFProvider()
    {
        return [
            ["℠foo && bar© baz © 00  ™  ", "foo && bar baz 00"],
            ["foo && bar && baz", "foo && bar && baz"]
        ];
    }

    /**
     * Test for removeSpecialCharsExceptSpace from string utility
     * @dataProvider removeSpecialCharsExceptSpaceProvider
     */
    public function testRemoveSpecialCharsExceptSpace($inputString, $expectedString)
    {
        $this->assertEquals($this->stringUtility->removeSpecialCharsExceptSpace($inputString), $expectedString);
    }

    /**
     * dataProvider for testRemoveSpecialCharsExceptSpace
     * @return array
     */
    public function removeSpecialCharsExceptSpaceProvider()
    {
        return [
            ["℠foo && bar© baz © 00  ™  ", "foo bar baz 00"],
            ["foo && bar && baz", "foo bar baz"]
        ];
    }
}
