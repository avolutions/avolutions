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

use Avolutions\Orm\EntityCollection;

/**
 * UniqueValidator
 *
 * The UniqueValidator validates if the value of an Entity attribute is unique in database.
 *
 * @author	Alexander Vogt <alexander.vogt@avolutions.org>
 * @since	0.6.0
 */
class UniqueValidator extends AbstractValidator
{
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
        $EntityCollection = new EntityCollection($this->Entity->getEntityName());
        $exists = $EntityCollection->where($this->property.' = \''.$value.'\'')->getFirst();

        return ($exists == null);
    }
}