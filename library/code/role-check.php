<?php

/**
 * Redirect to access denied page if flag is false.
 */
function checkAccess(bool $accessOk, string $message="(No message)") {
    if (! $accessOk) {
        accessDenied($message);
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
    if (is_null($currentUser)) {
        // Anon user has no roles.
        return false;
    }
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
 * @param string $message Write this to audit file.
 */
function accessDenied(string $message='(No message passed)') {
    // Sanitize message.
    $message = htmlspecialchars($message);
    logError('access-denied', $message);
    header('Location: access-denied.php');
    exit();
}
