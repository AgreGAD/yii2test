<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\CommentedOutCodeSniff;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__.'/components',
        __DIR__.'/tests',
    ]);

    $ecsConfig->sets([
                         SetList::PSR_12,
                         SetList::CLEAN_CODE,
                         SetList::SYMFONY,
                     ]);

    $ecsConfig->rule(CommentedOutCodeSniff::class);
};
