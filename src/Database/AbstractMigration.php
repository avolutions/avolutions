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

namespace Avolutions\Database;

/**
 * AbstractMigration class
 *
 * An abstract class which has to be extended by every Migration.
 *
 * @author	Alexander Vogt <alexander.vogt@avolutions.org>
 * @since	0.1.1

 */
abstract class AbstractMigration implements MigrationInterface
{
    /**
     * The version of the migration
     *
	 * @var int $version
	 */
    public int $version;
}