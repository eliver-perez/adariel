<?php

declare(strict_types=1);

namespace App\Core;

class Auth
{
    public static function id(): ?int
    {
        return $_SESSION['ADARIEL_ERP_ID'] ?? null;
    }

    public static function organizationId(): ?int
    {
        return $_SESSION['ADARIEL_ERP_ORGANIZATION_ID'] ?? null;
    }

    public static function organizationTimeZone(): ?string
    {
        return $_SESSION['ADARIEL_ERP_ORGANIZATION_TIMEZONE'] ?? null;
    }

    public static function organizationBranchId(): ?int
    {
        return $_SESSION['ADARIEL_ERP_ORGANIZATION_BRANCH_ID'] ?? null;
    }

    public static function organizationBranchTimeZone(): ?string
    {
        return $_SESSION['ADARIEL_ERP_ORGANIZATION_BRANCH_TIMEZONE'] ?? null;
    }
}