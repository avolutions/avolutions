<?php
/**
 * AVOLUTIONS
 *
 * Just another open source PHP framework.
 *
 * @copyright   Copyright (c) 2019 - 2021 AVOLUTIONS
 * @license     MIT License (https://avolutions.org/license)
 * @link        https://avolutions.org
 */

namespace Avolutions\View;

use Avolutions\Orm\Entity;
use Avolutions\Orm\EntityConfiguration;
use Avolutions\Orm\EntityMapping;

/**
 * Form class
 *
 * Provides methods to create HTML forms with or without an Entity context.
 *
 * @author  Alexander Vogt <alexander.vogt@avolutions.org>
 * @since   0.2.0
 */
class Form
{
    /**
     * The Entity context of the form.
     *
     * @var Entity|null $Entity
     */
    private ?Entity $Entity = null;

    /**
     * The configuration of the Entity.
     *
     * @var EntityConfiguration|null $EntityConfiguration
     */
    private ?EntityConfiguration $EntityConfiguration = null;

    /**
     * The mapping of the Entity.
     *
     * @var EntityMapping|null $EntityMapping
     */
    private ?EntityMapping $EntityMapping = null;

    /**
     * The name of the Entity.
     *
     * @var string|null $entityName
     */
    private ?string $entityName = null;

    /**
     * Validation error messages.
     *
     * @var array $errors
     */
    private array $errors = [];

    /**
     * __construct
     *
     * Creates a new Form instance. If an Entity is given the method loads
     * the EntityConfiguration and EntityMapping automatically.
     *
     * @param Entity|null $Entity $Entity The Entity context of the form.
     * @param array $errors Validation error messages.
     */
    public function __construct(?Entity $Entity = null, array $errors = [])
    {
        if ($Entity instanceof Entity) {
            $this->Entity = $Entity;
            $this->errors = $Entity->getErrors();
            $this->entityName = $this->Entity->getEntityName();
            $this->EntityConfiguration = application()->make(EntityConfiguration::class, ['entity' => $this->entityName]
            );
            $this->EntityMapping = $this->EntityConfiguration->getMapping();
        }

        if (is_array($errors)) {
            $this->errors = array_merge($this->errors, $errors);
        }
    }

    /**
     * inputFor
     *
     * Creates an HTML input element for the given Entity field depending on
     * the Mapping of this field.
     *
     * @param string $fieldName The field of the Entity.
     * @param array $attributes The attributes for the input element.
     * @param bool $showLabel Indicates if a label should be generated or not.
     *
     * @return string An HTML input element for the field.
     */
    public function inputFor(string $fieldName, array $attributes = [], bool $showLabel = true): string
    {
        $input = '';

        $attributes['name'] = lcfirst($this->entityName) . '[' . $fieldName . ']';
        $attributes['value'] = $this->Entity->$fieldName;

        $inputType = $this->EntityMapping->$fieldName['form']['type'];

        // Do not show labels for input type hidden
        if ($showLabel && $inputType != 'hidden') {
            $input .= $this->labelFor($fieldName);
        }

        switch ($inputType) {
            case 'select':
                $options = $this->EntityMapping->$fieldName['form']['options'] ?? [];
                $input .= $this->select($options, $attributes);
                break;

            case 'textarea':
                $input .= $this->textarea($attributes);
                break;


            default:
                $input .= $this->input($inputType, $attributes);
                break;
        }

        if (isset($this->errors[$fieldName])) {
            $input .= $this->error($this->errors[$fieldName]);
        }

        return $input;
    }

    /**
     * error
     *
     * Creates a div with validation error message for an input field.
     *
     * @param array $messages The error messages to display.
     *
     * @return string A div with error message.
     */
    public function error(array $messages): string
    {
        $error = '';

        foreach ($messages as $message) {
            $error .= '<div class="error">' . $message . '</div>';
        }

        return $error;
    }

    /**
     * labelFor
     *
     * Creates an HTML label element for the given Entity field depending on
     * the Mapping for this field.
     *
     * @param string $fieldName The field of the Entity.
     *
     * @return string An HTML label element depending on the field.
     */
    public function labelFor(string $fieldName): string
    {
        $label = $this->EntityMapping->$fieldName['form']['label'] ?? $fieldName;

        return $this->label($label);
    }

    /**
     * generate
     *
     * Generates a Form for all fields of the Entity, depending on
     * the Mapping of the Entity.
     *
     * @param array $formAttributes The attributes for the opening form tag.
     * @param bool $submitButton Indicates if a submit button should be generated automatically.
     *
     * @return string An HTML form for the Entity.
     */
    public function generate(array $formAttributes = [], bool $submitButton = true): string
    {
        $formFields = $this->EntityMapping->getFormFields();

        $form = $this->open($formAttributes);
        foreach (array_keys($formFields) as $formField) {
            $form .= $this->inputFor($formField);
        }
        if ($submitButton) {
            $form .= $this->submit();
        }
        $form .= $this->close();

        return $form;
    }

    /**
     * open
     *
     * Opens a form.
     *
     * @param array $attributes The attributes for the form tag.
     *
     * @return string An opening HTML form tag.
     */
    public function open(array $attributes = []): string
    {
        $attributesAsString = self::getAttributesAsString($attributes);

        return '<form' . $attributesAsString . '>';
    }

    /**
     * close
     *
     * Close a form.
     *
     * @return string A closing HTML form tag.
     */
    public function close(): string
    {
        return '</form>';
    }

    /**
     * checkbox
     *
     * Creates an HTML input element of type checkbox.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type checkbox.
     */
    public function checkbox(array $attributes = []): string
    {
        return $this->input('checkbox', $attributes);
    }

    /**
     * color
     *
     * Creates an HTML input element of type color.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type color.
     */
    public function color(array $attributes = []): string
    {
        return $this->input('color', $attributes);
    }

    /**
     * date
     *
     * Creates an HTML input element of type date.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type date.
     */
    public function date(array $attributes = []): string
    {
        return $this->input('date', $attributes);
    }

    /**
     * datetime
     *
     * Creates an HTML input element of type datetime-local.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type datetime-local.
     */
    public function datetime(array $attributes = []): string
    {
        return $this->input('datetime-local', $attributes);
    }

    /**
     * email
     *
     * Creates an HTML input element of type email.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type email.
     */
    public function email(array $attributes = []): string
    {
        return $this->input('email', $attributes);
    }

    /**
     * file
     *
     * Creates an HTML input element of type file.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type file.
     */
    public function file(array $attributes = []): string
    {
        return $this->input('file', $attributes);
    }

    /**
     * hidden
     *
     * Creates an HTML input element of type hidden.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type hidden.
     */
    public function hidden(array $attributes = []): string
    {
        return $this->input('hidden', $attributes);
    }

    /**
     * image
     *
     * Creates an HTML input element of type image.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type image.
     */
    public function image(array $attributes = []): string
    {
        return $this->input('image', $attributes);
    }

    /**
     * label
     *
     * Creates an HTML label element.
     *
     * @param string $text The text of the label element.
     * @param array $attributes The attributes for the label tag.
     *
     * @return string An HTML input element of type image.
     */
    public function label(string $text, array $attributes = []): string
    {
        $attributesAsString = self::getAttributesAsString($attributes);

        return '<label' . $attributesAsString . '>' . $text . '</label>';
    }

    /**
     * month
     *
     * Creates an HTML input element of type month.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type month.
     */
    public function month(array $attributes = []): string
    {
        return $this->input('month', $attributes);
    }

    /**
     * number
     *
     * Creates an HTML input element of type number.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type number.
     */
    public function number(array $attributes = []): string
    {
        return $this->input('number', $attributes);
    }

    /**
     * password
     *
     * Creates an HTML input element of type password.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type password.
     */
    public function password(array $attributes = []): string
    {
        return $this->input('password', $attributes);
    }

    /**
     * radio
     *
     * Creates an HTML input element of type radio.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type radio.
     */
    public function radio(array $attributes = []): string
    {
        return $this->input('radio', $attributes);
    }

    /**
     * range
     *
     * Creates an HTML input element of type range.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type range.
     */
    public function range(array $attributes = []): string
    {
        return $this->input('range', $attributes);
    }

    /**
     * reset
     *
     * Creates an HTML input element of type reset.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type reset.
     */
    public function reset(array $attributes = []): string
    {
        return $this->input('reset', $attributes);
    }

    /**
     * search
     *
     * Creates an HTML input element of type search.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type search.
     */
    public function search(array $attributes = []): string
    {
        return $this->input('search', $attributes);
    }

    /**
     * submit
     *
     * Creates an HTML input element of type submit.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type submit.
     */
    public function submit(array $attributes = []): string
    {
        return $this->input('submit', $attributes);
    }

    /**
     * tel
     *
     * Creates an HTML input element of type tel.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type tel.
     */
    public function tel(array $attributes = []): string
    {
        return $this->input('tel', $attributes);
    }

    /**
     * text
     *
     * Creates an HTML input element of type text.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type text.
     */
    public function text(array $attributes = []): string
    {
        return $this->input('text', $attributes);
    }

    /**
     * time
     *
     * Creates an HTML input element of type time.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type time.
     */
    public function time(array $attributes = []): string
    {
        return $this->input('time', $attributes);
    }

    /**
     * url
     *
     * Creates an HTML input element of type url.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type url.
     */
    public function url(array $attributes = []): string
    {
        return $this->input('url', $attributes);
    }

    /**
     * week
     *
     * Creates an HTML input element of type week.
     *
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of type week.
     */
    public function week(array $attributes = []): string
    {
        return $this->input('week', $attributes);
    }

    /**
     * input
     *
     * Creates an HTML input element.
     *
     * @param string $type The type for the input tag.
     * @param array $attributes The attributes for the input tag.
     *
     * @return string An HTML input element of given type.
     */
    public function input(string $type, array $attributes = []): string
    {
        $attributes['type'] = $type;
        $attributesAsString = self::getAttributesAsString($attributes);

        return '<input' . $attributesAsString . ' />';
    }

    /**
     * button
     *
     * Creates an HTML button element.
     *
     * @param array $attributes The attributes for the button tag.
     *
     * @return string An HTML element of type button.
     */
    public function button(array $attributes = []): string
    {
        $value = $attributes['value'] ?? null;
        unset($attributes['value']); // To not render it to the select tag

        $attributesAsString = self::getAttributesAsString($attributes);

        return '<button' . $attributesAsString . '>' . $value . '</button>';
    }

    /**
     * textarea
     *
     * Creates an HTML textarea element.
     *
     * @param array $attributes The attributes for the textarea tag.
     *
     * @return string An HTML element of type textarea.
     */
    public function textarea(array $attributes = []): string
    {
        $value = $attributes['value'] ?? null;
        unset($attributes['value']); // To not render it to the select tag

        $attributesAsString = self::getAttributesAsString($attributes);

        return '<textarea' . $attributesAsString . '>' . $value . '</textarea>';
    }

    /**
     * select
     *
     * Creates an HTML select element with the given options.
     *
     * @param array $options The options for the select list.
     * @param array $attributes The attributes for the select tag.
     *
     * @return string An HTML element of type select.
     */
    public function select(array $options = [], array $attributes = []): string
    {
        $selectedValue = $attributes['value'] ?? null;
        unset($attributes['value']); // To not render it to the select tag

        $attributesAsString = self::getAttributesAsString($attributes);
        $optionsAsString = '';

        foreach ($options as $key => $value) {
            $optionsAsString .= $this->option($key, $value, $selectedValue);
        }

        return '<select' . $attributesAsString . '>' . $optionsAsString . '</select>';
    }

    /**
     * option
     *
     * Creates an HTML option element.
     *
     * @param string $value The value of the option tag.
     * @param string $text The text of the option element.
     * @param string|null $selectedValue The selected value of the option element.
     *
     * @return string An HTML element of type option.
     */
    private function option(string $value, string $text, ?string $selectedValue = null): string
    {
        $selected = $value == $selectedValue ? ' selected' : '';

        return '<option value="' . $value . '"' . $selected . '>' . $text . '</option>';
    }

    /**
     * getAttributesAsString
     *
     * Returns the attributes as a string in the format attribute="value"
     *
     * @param array $attributes The attributes for the select tag.
     *
     * @return string The attributes as a string.
     */
    private static function getAttributesAsString(array $attributes): string
    {
        $attributesAsString = '';

        foreach ($attributes as $attributeName => $attributeValue) {
            $attributesAsString .= ' ' . $attributeName . '="' . $attributeValue . '"';
        }

        return $attributesAsString;
    }
}