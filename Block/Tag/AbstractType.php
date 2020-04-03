<?php
namespace Sga\Tracker\Block\Tag;

abstract class AbstractType extends \Magento\Framework\View\Element\AbstractBlock
{
    const REGEXP_FOUND = '/\{\{var\s+([a-zA-Z0-9_\.-]+)\}\}/';
    const REGEXP_REPLACE = '/\{\{var\s+(%s)\}\}/';
    const REGEXP_EVAL = '#\{\{\s*?eval\b[^}]*\}\}(.*?)\{\{/eval\b[^}]*\}\}#s';

    abstract protected function _loadContextVars();

    protected function _getTags()
    {
        $matches = array();
        preg_match_all(self::REGEXP_FOUND, $this->getTag()->getContent(), $matches);

        $tags = array();
        if (is_array($matches[1]) && count($matches[1]) > 0) {
            $tags = $matches[1];
        }
        return $tags;
    }

    protected function _toHtml()
    {
        $this->_loadContextVars();

        $content = $this->getTag()->getContent();

        foreach ($this->_getTags() as $tag) {
            $tagPart = preg_split('/\./', $tag);
            $attribute = $tagPart[count($tagPart) - 1];

            if (count($tagPart) > 1) {
                $obj = $this->_getObject($this, $tagPart[0]);
                if ($obj instanceof \Magento\Framework\DataObject) {
                    for ($i = 1; $i < count($tagPart) - 1; $i++) {
                        $obj = $this->_getObject($obj, $tagPart[$i]);
                        if (!$obj instanceof \Magento\Framework\DataObject) {
                            break;
                        }
                    }

                    $content = $this->_processObjectTag($obj, $attribute, $content, $tag);
                } else {
                    $content = $this->_processObjectTag($this, $attribute, $content, $tag);
                }
            } else {
                $content = $this->_processObjectTag($this, $attribute, $content, $tag);
            }
        }

        $matches = array();
        if (preg_match_all(self::REGEXP_EVAL, $content, $matches)) {
            if (isset($matches[1])) {
                foreach ($matches[1] as $i => $match) {
                    ob_start();
                    eval($match);
                    $replace = ob_get_contents();
                    ob_end_clean();
                    $content = str_replace($matches[0][$i], $replace, $content);
                }
            }
        }

        return '<!-- TAG : '.$this->getTag()->getIdentifier().' -->'.$content;
    }

    protected function _getObject($obj, $method)
    {
        try {
            $o = null;
            $caller = 'get' . $this->_ucwords($method, '');
            if (is_callable(array($obj, $caller))) {
                $o = $obj->$caller();
            } else {
                $o = $obj->getData($method);
            }
            return $o;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function _processObjectTag($obj, $attribute, $content, $tag)
    {
        $caller = 'get' . $this->_ucwords($attribute, '');
        if (is_callable(array($obj, $caller))) {
            $replace = $obj->$caller();
        } elseif ($obj) {
            $replace = $obj->getData($attribute);
        }

        if ((!isset($replace)) && $this->getTag()->getHideNullTags()) {
            $replace = '';
        }

        if (isset($replace)) {
            $content = preg_replace(sprintf(self::REGEXP_REPLACE, $tag), $this->escapeJsQuote($replace), $content);
        }
        return $content;
    }

    protected function _ucwords($str, $destSep='_', $srcSep='_')
    {
        return str_replace(' ', $destSep, ucwords(str_replace($srcSep, ' ', $str)));
    }
}