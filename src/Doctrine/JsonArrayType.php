<?php

namespace App\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

/**
 * Removed type from DBAL in v3.
 * The type is no more used, but we must keep it in order to avoid error during migrations.
 *
 * @see https://github.com/doctrine/dbal/commit/6ed32a9a941acf0cb6ad384b84deb8df68ca83f8
 * @see https://dunglas.dev/2022/01/json-columns-and-doctrine-dbal-3-upgrade/
 */
class JsonArrayType extends JsonType
{
    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || '' === $value) {
            return [];
        }

        $value = \is_resource($value) ? stream_get_contents($value) : $value;

        return json_decode($value, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'json_array';
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
