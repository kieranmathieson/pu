<?php
// A collection of useful things.

/**
 * Log an app error.
 * @param string $programId The program, usually the PHP file name.
 * @param string $message The message.
 */
function logError(string $programId, string $message) {
    writeCsvMessage(ERROR_LOG_FILE_PATH, $programId, $message);
}

/**
 * Add to audit log.
 * @param string $programId The program, usually the PHP file name.
 * @param string $message The message.
 */
function logTransaction(string $programId, string $message) {
    writeCsvMessage(AUDIT_TRAIL_FILE_PATH, $programId, $message);
}


/**
 * Write a CSV message to a file.
 * @param string $filePath Where to write.
 * @param string $programId The program, usually the PHP file name.
 * @param string $message The message.
 */
function writeCsvMessage(string $filePath, string $programId, string $message) {
    // Replace , with _ in both strings, to keep the CSV format intact.
    $programId = str_replace(',', '_', $programId);
    $message = str_replace(',', '_', $message);
    // Get the date/time.
    $now = date(DATE_ISO8601);
    // Create the message.
    $output = "$now,$programId,$message\n";
    // Write it.
    file_put_contents($filePath, $output, FILE_APPEND);
}



/**
 * Run through an array, replacing nulls with MT strings.
 * @param $arr array to process.
 * @return array Processed array.
 */
function replaceNullWithMtString(array $arr) {
    $result = [];
    foreach ($arr as $index=>$value) {
        if (is_null($value)) {
            $value = '';
        }
        $result[$index] = $value;
    }
    return $result;
}

/**
 * Check whether there is an id in GET that is a valid person id.
 * @return bool True if valid.
 */
function isPersonIdInGetOK() {
    $personIdOk = false;
    if (isset($_GET['id'])) {
        $personId = $_GET['id'];
        // Validate.
        if (is_numeric($personId) && $personId > 0) {
            // Does the person exist?
            $person = new Person();
            $errorMessage = $person->load($personId);
            if ($errorMessage == '') {
                // Id is OK.
                $personIdOk = true;
            }
        }
    }
    return $personIdOk;
}

/**
 * Check whether there is an id in GET that is a valid course id.
 * @return bool True if valid.
 */
function isCourseIdInGetOK() {
    $courseIdOk = false;
    if (isset($_GET['id'])) {
        $courseId = $_GET['id'];
        // Validate.
        if (is_numeric($courseId) && $courseId > 0) {
            // Does the course exist?
            $course = new Course();
            $errorMessage = $course->load($courseId);
            if ($errorMessage == '') {
                // Id is OK.
                $courseIdOk = true;
            }
        }
    }
    return $courseIdOk;
}


/**
 * Get a field value from POST.
 * @param string $fieldName Name of the field.
 * @return string Trimmed value. MT if field not found.
 */
function getFieldValueFromPost(string $fieldName) {
    $value = '';
    if (isset($_POST[$fieldName])) {
        $value = trim($_POST[$fieldName]);
    }
    return $value;
}

/**
 * Return 'checked' when passed true. Used when generating HTML for
 * checkbox fields.
 * @param bool $dataValue
 * @return string checked, or MT.
 */
function makeCheckboxValueFromBool(bool $dataValue) {
    $result = '';
    if ($dataValue) {
        $result = 'checked';
    }
    return $result;
}

/**
 * Check whether a person with the given username exists.
 * @param string $username To check.
 * @return bool
 */
function isUsernameExists(string $username): bool {
    global $dbConnector;
    $dbConnection = $dbConnector->getConnection();
    $sql = 'select person_id from people where username = :username';
    $queryData = [
        'username' => $username
    ];
    try {
        $stmnt = $dbConnection->prepare($sql);
        $stmnt->execute($queryData);
    }
    catch (PDOException $e) {
        logError(__FILE__, $e->getMessage());
        return INTERNAL_ERROR_MESSAGE;
    }
    $numRows = $stmnt->rowCount();
    return $numRows > 0;
}

/**
 * Check whether a person with the given email exists.
 * @param string $email To check.
 * @return bool
 */
function isEmailExists(string $email): bool {
    global $dbConnector;
    $dbConnection = $dbConnector->getConnection();
    $sql = 'select person_id from people where email = :email';
    $queryData = [
        'email' => $email
    ];
    try {
        $stmnt = $dbConnection->prepare($sql);
        $stmnt->execute($queryData);
    }
    catch (PDOException $e) {
        logError(__FILE__, $e->getMessage());
        return INTERNAL_ERROR_MESSAGE;
    }
    $numRows = $stmnt->rowCount();
    return $numRows > 0;
}

/**
 * Check whether one string contains another, not case-sensitive.
 * (Abstracts away from strangeness in stripos()).
 * @param string $haystack String the search.
 * @param string $needle String to find.
 * @return bool True if found.
 */
function containsString(string $haystack, string $needle): bool {
    $found = !(stripos($haystack, $needle) === FALSE);
    return $found;
}