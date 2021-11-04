<?php

namespace Ht7\Html\Tests\Utilities;

class Minifier
{

    public function minify(string $filename, string $filenameMinified = '')
    {
        ob_start('self::helper');

        include $filename;

        $html = ob_get_clean();
    }

    protected function helper($html)
    {
        $search = [
            // Remove whitespaces after tags
            '/\>[^\S ]+/s',
            // Remove whitespaces before tags
            '/[^\S ]+\</s',
            // Remove multiple whitespace sequences
            '/(\s)+/s',
            // Removes comments
            '/<!--(.|\s)*?-->/'
        ];
        $replace = ['>', '<', '\\1'];
        $code = preg_replace($search, $replace, $html);

        return $code;
    }

}
