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

namespace Avolutions\Validation;

use Avolutions\Orm\Entity;
use DateTime;
use InvalidArgumentException;
use function implode;

/**
 * TypeValidator
 *
 * The TypeValidator validates the data type of the value.
 *
 * @author	Alexander Vogt <alexander.vogt@avolutions.org>
 * @since	0.6.0
 */
class TypeValidator extends AbstractValidator
{
    /**
     * The data type to compare.
     *
     * @var string $type
     */
    private string $type;

    /**
     * setOptions
     *
     * Set the passed options, property and Entity to internal properties.
     *
     * @param array $options An associative array with options.
     * @param string|null $property The property of the Entity to validate.
     * @param Entity|null $Entity The Entity to validate.
     */
    public function setOptions(array $options = [], ?string $property = null, ?Entity $Entity = null) {
        parent::setOptions($options, $property, $Entity);

        $validTypes = ['int', 'integer', 'string', 'bool', 'boolean', 'array', 'datetime'];
        if (
            !isset($options['type'])
            || !in_array($options['type'], $validTypes)
        ) {
            throw new InvalidArgumentException('Invalid type, must be either '.implode(', ', $validTypes));
        } else {
            $this->type = $options['type'];
        }
    }

    /**
     * isValid
     *
     * Checks if the passed value is valid considering the validator type and passed options.
     *
     * @param mixed $value The value to validate.
     *
     * @return bool Data is valid (true) or not (false).
     */
    public function isValid(mixed $value): bool {
        switch ($this->type) {
            case 'int':
            case 'integer':
                return is_int($value);
            case 'string':
                return is_string($value);
            case 'bool':
            case 'boolean':
                return is_bool($value);
            case 'array':
                return is_array($value);
            case 'datetime':
                return $value instanceof DateTime;
            default:
                return false;
        }
    }
}