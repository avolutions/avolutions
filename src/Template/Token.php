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

namespace Avolutions\Template;

/**
 * Token class
 *
 * TODO
 *
 * @author	Alexander Vogt <alexander.vogt@avolutions.org>
 * @since	0.9.0
 */
class Token
{
    /**
     * TODO
     *
     * @var int
     */
    public int $type;

    /**
     * TODO
     *
     * @var string
     */
    public string $value;

    /**
     * TODO
     *
     * @param int $type TODO
     * @param string $value TODO
     */
    public function __construct(int $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}