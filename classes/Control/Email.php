<?php
namespace CoreUI\Form\Classes\Control;
use CoreUI\Form\Classes\Control;


require_once __DIR__ . '/../Control.php';



/**
 * Class Email
 * @package CoreUI\Form\Control
 */
class Email extends Control {

    protected $attributes = array(
        'type'  => 'email',
        'class' => 'combine-form-control',
    );


    /**
     * @param  string     $type
     * @param  array|bool $params
     * @param  string     $message
     * @return self
     * @throws \Exception
     */
    public function addValidator($type, $params, $message) {

        $type = strtolower($type);

        switch ($type) {
            case 'regex' :
            case 'length' :
            case 'email' :
                $validator = new \stdClass();
                $validator->type    = $type;
                $validator->params  = $params;
                $validator->message = $message;

                $this->validators[] = $validator;
                break;

            default :
                throw new \Exception("Validator '{$type}' not found");
                break;
        };

        return $this;
    }


    /**
     * @return string
     */
    protected function makeControl() {

        $tpl = file_get_contents(__DIR__ . '/../../html/form/controls/email.html');

        $attributes = array();

        if ( ! empty($this->attributes)) {
            foreach ($this->attributes as $attr_name => $value) {
                $attributes[] = "$attr_name=\"$value\"";
            }
        }

        if ($this->required) {
            $attributes[] = 'required="required"';

            if ($this->required_message) {
                $attributes[] = "data-required-message=\"{$this->required_message}\"";
            }
        }


        // TODO сделать валидаторы
        if ( ! empty($this->validators)) {
            foreach ($this->validators as $validator) {

            }
        }


        $tpl = str_replace('[ATTRIBUTES]', implode(' ', $attributes), $tpl);

        return $tpl;
    }
}