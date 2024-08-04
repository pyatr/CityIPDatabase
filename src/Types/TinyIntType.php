<?php

namespace App\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\SmallIntType;

class TinyIntType extends SmallIntType
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return 'TINYINT' . (!empty($column['unsigned']) ? ' UNSIGNED' : '');
    }


    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    public function getName()
    {
        return 'tinyint';
    }
}