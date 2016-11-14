<?php
/**
 * Sentence Spliter.
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   SplitSens
 * @author    Bong Yeon, Kim <kimbongyeon83@gmail.com>
 * @copyright xxx
 * @license   MIT Licence
 * @link      http://xxxx
 */

declare(strict_types=1);

namespace SplitSens;

/**
 * Sentence Spliter.
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   SplitSens
 * @author    Bong Yeon, Kim <kimbongyeon83@gmail.com>
 * @copyright
 * @license   MIT Licence
 * @version   Release: 0.1.0
 * @link      http://xxxx
 */
class SplitSens
{
    /**
     * Empty constructer
     *
     * @return null
     */
    public function __construct()
    {
    }

    /**
     * Return sentence array from phrase.
     *
     * @param array $phrase The current input phrase.
     * @return  array
     */
    public function split($phrase = null)
    {
        $sens = [];
        if (is_integer($phrase)) {
            return $sens;
        }
        if (is_numeric($phrase)) {
            return $sens;
        }

        if (!is_string($phrase)) {
            $sens = [];
            return $sens;
        }

        // Pre filterling.
        $phrase = trim($phrase);
        $phrase = preg_replace('/(&#?[a-z0-9]+;?)/i', '', $phrase);
        $phrase = preg_replace('/(【[^】]*】)/i', '', $phrase);
        $phrase = strip_tags($phrase);
        $phrase = preg_replace('/[^\p{L}\p{N}\n.?;。！~◎☆★!♪ ]/u', '', $phrase);
        if (strlen($phrase) < 1) {
            $sens = [];
            return $sens;
        }

        $regexp = '/(?<=[^\n.?;。！~◎☆★!♪])[\n.?;。！~◎☆★!♪]+/u';
        $sens = preg_split($regexp, $phrase);
        if (sizeof($sens) < 1) {
            return $sens;
        }

        $sens = array_map('trim', $sens);
        $newsens = [];
        foreach ($sens as $sentens) {
            $tmpsens = preg_replace('/^[0-9\s]*$/i', '', $sentens);
            if (empty($tmpsens)) {
                continue;
            }
            $newsens[] = $tmpsens;
        }
        $sens = $newsens;
        $sens = array_filter($sens);
        return $sens;
    }
}
