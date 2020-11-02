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
 * @param $arr Array to process.
 * @return array Processed array.
 */
function replaceNullWithSpace(array $arr) {
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
 * Get a field value from POST.
 * @param string $fieldName Name of the field.
 * @return string Trimmed value. MT if field not found.
 */
function getFieldValueFromPost(string $fieldName) {
    $value = '';
    if (isset($_POST[$fieldName])) {
        if ($_POST[$fieldName]) {
            $value = trim($_POST[$fieldName]);
        }
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
    global $dbConnection;
    $sql = 'select person_id from people where username = :username';
    $queryData = [
        'username' => $username
    ];
    try {
        $stmnt = $dbConnection->prepare($sql);
        $stmnt->execute($queryData);
    }
    catch (PDOException $e) {
        logError(self::PROGRAM_ID, $e->getMessage());
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
    global $dbConnection;
    $sql = 'select person_id from people where email = :email';
    $queryData = [
        'email' => $email
    ];
    try {
        $stmnt = $dbConnection->prepare($sql);
        $stmnt->execute($queryData);
    }
    catch (PDOException $e) {
        logError(self::PROGRAM_ID, $e->getMessage());
        return INTERNAL_ERROR_MESSAGE;
    }
    $numRows = $stmnt->rowCount();
    return $numRows > 0;
}

