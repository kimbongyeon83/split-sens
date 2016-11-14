<?php declare(strict_types=1);
namespace SplitSens;
class SplitSens
{
    public function __construct()
    {
    }

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

        # pre filterling
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
