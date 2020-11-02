<?php

/**
 * Redirect to access denied page if flag is false.
 */
function checkAccess(bool $accessOk) {
    if (! $accessOk) {
        accessDenied();
    }
}

/**
 * Check whether the current user has a given role.
 *
 * @param string $roleName Role name: admin, staff, faculty, or student.
 * @return bool True if current user has the role.
 */
function isCurrentUserHasRole(string $roleName) {
    /** @var Person $currentUser */
    global $currentUser;
    $hasRole = false;
    if ($roleName == ADMIN_ROLE && $currentUser->isAdmin()) {
        $hasRole = true;
    }
    elseif ($roleName == STAFF_ROLE && $currentUser->isStaff()) {
        $hasRole = true;
    }
    elseif ($roleName == FACULTY_ROLE && $currentUser->isFaculty()) {
        $hasRole = true;
    }
    elseif ($roleName == STUDENT_ROLE && $currentUser->isStudent()) {
        $hasRole = true;
    }
    return $hasRole;
}

/**
 * Redirect to access denied page.
 */
function accessDenied() {
    header('Location: access-denied.php');
    exit();
}
