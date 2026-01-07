<?php

function is_superadmin(): bool
{
    $user = auth()->user();

    if (! $user) {
        return false;
    }

    return $user->inGroup('superadmin');
}