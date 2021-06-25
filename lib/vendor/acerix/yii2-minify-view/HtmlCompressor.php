<?php
/**
 * HtmlCompressor.php
 * @author Dylan Ferris
 */

namespace acerix\yii\minify;

/**
 * Class HtmlCompressor
 * @package acerix\yii\minify
 * @deprecated
 */
class HtmlCompressor
{

    /**
     * "Minify" an HTML page
     *
     * @param string $html
     * @param array $options
     *
     * 'cssMinifier' : (optional) callback function to process content of STYLE
     * elements.
     *
     * 'jsMinifier' : (optional) callback function to process content of SCRIPT
     * elements. Note: the type attribute is ignored.
     *
     * 'xhtml' : (optional boolean) should content be treated as XHTML1.0? If
     * unset, minify will sniff for an XHTML doctype.
     *
     * @return string
     */
    public static function compress($html, array $options = [])
    {
        return \Minify_HTML::minify($html, $options);
    }
}
