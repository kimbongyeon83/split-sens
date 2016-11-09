<?php declare(strict_types=1);

namespace SplitSens;

class SplitSens
{
    public function __construct()
    {
    }

    public function split($phrase=null)
    {
        $sens = [];
        if (is_integer($phrase)) {
            $sens[] = $phrase;
            return $sens;
        }

        if (!is_string($phrase)) {
            $sens = [];
            return $sens;
        }

        $phrase = trim($phrase);
        if (strlen($phrase) < 1) {
            $sens = [];
            return $sens;
        }

        #$sens = explode('.', $phrase);
        $regexp = '/(?<=[^\n.?;。！~◎☆★!♪])[\n.?;。！~◎☆★!♪]/u';
        $sens = preg_split($regexp, $phrase);
        if (sizeof($sens) > 0) {
            $sens = array_map('trim', $sens);
            $sens = array_filter($sens);
            return $sens;
        }

        return $sens;
    }
}
