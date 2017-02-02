<?php
namespace CoreUI\Form\Classes\Control;
use CoreUI\Form\Classes\Control;
use CoreUI\Exception;
use CoreUI\Utils\Mtpl;


require_once __DIR__ . '/../Control.php';



/**
 * Class Wysiwyg
 * @package CoreUI\Form\Control
 */
class Wysiwyg extends Control {

    protected $value         = '';
    protected $config        = 'basic';
    protected $custom_config = array();


    /**
     * @param string $label
     * @param string $name
     * @param string $config
     */
    public function __construct($label, $name, $config = 'basic') {
        parent::__construct($label, $name);
        $this->config = $config;
    }


    /**
     * @param string $config
     * @return self
     */
    public function setConfig($config) {
        $this->config = $config;
        return $this;
    }


    /**
     * @param  string $string
     * @return self
     */
    public function setValue($string) {
        $this->value = htmlspecialchars($string);
        return $this;
    }


    /**
     * @param array $config
     * @return self
     */
    public function setCustomConfig(array $config) {
        $this->config = 'custom';
        $this->custom_config = $config;
        return $this;
    }


    /**
     * @return string
     * @throws Exception
     */
    protected function makeControl() {

        $tpl = new Mtpl(__DIR__ . '/../../html/form/controls/wysiwyg.html');

        if ($this->readonly) {
            $tpl->readonly->assign('[VALUE]', $this->value);

        } else {
            $id = uniqid('ck');
            $attributes = array(
                "id=\"{$id}\""
            );

            if ( ! empty($this->attributes)) {
                foreach ($this->attributes as $attr_name => $value) {
                    if (trim($attr_name) != 'id') {
                        $attributes[] = "$attr_name=\"$value\"";
                    }
                }
            }

            if ($this->required) {
                $attributes[] = 'required="required"';
            }


            switch ($this->config) {
                case 'basic':
                    $config = ',' . json_encode(array(
                            'toolbarGroups' => array(
                                array('name' => 'basicstyles', 'groups' => array('basicstyles')),
                                array('name' => 'links', 'groups' => array('links')),
                                array('name' => 'paragraph', 'groups' => array('list', 'indent', 'align')),
                                array('name' => 'insert', 'groups' => array('insert')),
                            ),
                            'removeButtons' => 'Underline,Strike,Subscript,Superscript,Anchor,SpecialChar,Flash,Smiley,Iframe,PageBreak'
                        ));
                    break;

                case 'standard':
                    $config = ',' . json_encode(array(
                            'toolbarGroups' => array(
                                array('name' => 'clipboard', 'groups' => array('undo', 'clipboard')),
                                array('name' => 'links', 'groups' => array('links')),
                                array('name' => 'insert', 'groups' => array('insert')),
                                array('name' => 'tools', 'groups' => array('Maximize')),
                                '/',
                                array('name' => 'basicstyles', 'groups' => array('basicstyles', 'cleanup')),
                                array('name' => 'paragraph', 'groups' => array('list', 'indent', 'blocks', 'align')),
                            ),
                            'removeButtons' => 'Underline,Strike,Subscript,Superscript,Anchor,SpecialChar,Flash,Smiley,Iframe,PageBreak,CreateDiv'
                        ));
                    break;

                case 'full':
                    $config = ',' . json_encode(array(
                            'toolbarGroups' => array(
                                array('name' => 'clipboard', 'groups' => array('undo', 'clipboard')),
                                array('name' => 'links', 'groups' => array('links')),
                                array('name' => 'insert', 'groups' => array('insert')),
                                array('name' => 'editing', 'groups' => array( 'find', 'spellchecker')),
                                array('name' => 'tools', 'groups' => array('Maximize')),
                                '/',
                                array('name' => 'basicstyles', 'groups' => array('basicstyles', 'cleanup')),
                                array('name' => 'paragraph', 'groups' => array('list', 'indent', 'blocks', 'align')),
                                array('name' => 'colors'),
                                '/',
                                array('name' => 'styles'),
                            ),
                            'removeButtons' => 'Underline,Strike,Subscript,Superscript,Anchor,SpecialChar,Flash,Smiley,Iframe,PageBreak,CreateDiv,Styles'
                        ));
                    break;

                case 'custom':
                    $config = ',' . json_encode($this->custom_config);
                    break;

                default : throw new Exception("Incorrect ckeditor config '{$this->config}'"); break;
            }

            $this->addJs($this->theme_src . '/editors/ckeditor/ckeditor.js', true);

            $tpl->control->assign('[TPL_DIR]',    $this->theme_src);
            $tpl->control->assign('[ATTRIBUTES]', implode(' ', $attributes));
            $tpl->control->assign('[VALUE]',      $this->value);
            $tpl->control->assign('[ID]',         $id);
            $tpl->control->assign('[CONFIG]',     $config);
        }

        return $tpl->render();
    }
}