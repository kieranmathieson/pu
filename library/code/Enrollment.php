<?php
require_once 'DbConnector.php';
require_once 'utils.php';
require_once 'Course.php';
require_once 'Person.php';

class Enrollment
{
    // Name to identify this file in error logs.
    private const PROGRAM_ID = 'Enrollment';

    // Init object properties.
    private $courseId = 0;
    private $studentId = 0;

    /**
     * Get the course id.
     * @return int
     */
    public function getCourseId(): int
    {
        return $this->courseId;
    }

    /**
     * Set the course id.
     * @param int $courseIdIn Course id.
     * @return string Error message.
     */
    public function setCourseId(int $courseIdIn): string
    {
        $error = '';
        // Data type check.
        if (! is_numeric($courseIdIn)) {
            logError(self::PROGRAM_ID,
                "Error in setCourseId: value is not a number: $courseIdIn");
            $error = 'Sorry, course id must be a number.<br>';
        }
        // Range check.
        elseif ($courseIdIn <= 0) {
            logError(self::PROGRAM_ID,
                "Error in setCourseId: value is $courseIdIn");
            $error = 'Sorry, course id cannot be negative.<br>';
        }
        else {
            // Must be > 0.
            // Make sure the course exists.
            $course = new Course();
            $errorMessage = $course->load($courseIdIn);
            if ($errorMessage != '') {
                logError(
                    self::PROGRAM_ID,
                    "Error in setCourseId, not found: value is $courseIdIn"
                );
                $error = 'Sorry, must be the id of a course.<br>';
            } else {
                // All OK, remember it.
                $this->courseId = $courseIdIn;
            }
        }
        return $error;
    }

    /**
     * Get the student id.
     * @return int
     */
    public function getStudentId(): int
    {
        return $this->studentId;
    }

    /**
     * Set the student id.
     * @param int $studentIdIn
     * @return string Error message.
     */
    public function setStudentId(int $studentIdIn): string
    {
        $error = '';
        // Data type check.
        if (! is_numeric($studentIdIn)) {
            logError(self::PROGRAM_ID,
                "Error in setStudentId: value is not a number: $studentIdIn");
            $error = 'Sorry, student id must be a number.<br>';
        }
        // Range check.
        elseif ($studentIdIn <= 0) {
            logError(self::PROGRAM_ID,
                "Error in setStudentId: value is $studentIdIn");
            $error = 'Sorry, student id cannot be negative or zero.<br>';
        }
        else {
            // Must be > 0.
            // Make sure the person exists.
            $person = new Person();
            $errorMessage = $person->load($studentIdIn);
            if ($errorMessage != '') {
                logError(
                    self::PROGRAM_ID,
                    "Error in setStudentId, not found: value is $studentIdIn"
                );
                $error = 'Sorry, must be the id of a person.<br>';
            }
            elseif (! $person->isStudent()) {
                logError(
                    self::PROGRAM_ID,
                    "Error in setStudentId, person not a student: $studentIdIn"
                );
                $error = 'Sorry, must be the id of a student.<br>';
            } else {
                // All OK, remember it.
                $this->studentId = $studentIdIn;
            }
        }
        return $error;
    }

    /**
     * Populate fields from a DB row.
     * @param array $enrollmentRow
     * @return string Error messages.
     */
    public function populateFromDatabaseRow(array $enrollmentRow): string {
        $accumulatedErrors = '';
        $accumulatedErrors .= $this->populateFields(
            $enrollmentRow['course'],
            $enrollmentRow['person']
        );
        return $accumulatedErrors;
    }

    /**
     * Populate fields.
     * @param string $courseIdIn Course code. Required.
     * @param string $studentIdIn Title. Required.
     * @return string Error message, null for no error.
     */
    public function populateFields(
        string $courseIdIn, string $studentIdIn
    ): string {
        $accumulatedErrors = '';
        $accumulatedErrors .= $this->setCourseId($courseIdIn);
        $accumulatedErrors .= $this->setStudentId($studentIdIn);
        return $accumulatedErrors;
    }

    public function saveAsNew(): string {
        global $dbConnector;
        /** @var $currentUser Person */
        global $currentUser;
        $accumulatedErrors = '';
        // Check that required data is present.
        $accumulatedErrors .= $this->checkRequiredDataPresent();
        if ($accumulatedErrors != '') {
            return $accumulatedErrors;
        }
        // Get a DB connection.
        $dbConnection = $dbConnector->getConnection();
        // Make INSERT.
        $sql = "
            INSERT INTO enrollments (
                course, person
            )
            values (
                :course,
                :person
            );
        ";
        $insertData = [
            'course' => $this->getCourseId(),
            'person' => $this->getStudentId(),
        ];
        try {
            $stmnt = $dbConnection->prepare($sql);
            $stmnt->execute($insertData);
            // Save the id of the new record.
            $this->id = $dbConnection->lastInsertId();
        } catch (PDOException $e) {
            logError(self::PROGRAM_ID, $e->getMessage());
            return INTERNAL_ERROR_MESSAGE;
        }
        $currentUserId = $currentUser->getId();
        logTransaction(self::PROGRAM_ID, "User $currentUserId added user $this->id");
        return $accumulatedErrors;
    }

    /**
     * Check whether required fields are present.
     * @return string Errors, or MT string if all OK.
     */
    private function checkRequiredDataPresent(): string {
        $accumulatedErrors = '';
        if ($this->getCourseId() == 0 || $this->getCourseId() == '' ) {
            $accumulatedErrors .= 'Sorry, course is required.<br>';
        }
        if ($this->getStudentId() == 0 || $this->getStudentId() == '') {
            $accumulatedErrors .= 'Sorry, encrypted password is required.<br>';
        }
        return $accumulatedErrors;
    }

    /**
     * Delete enrollment record from the database.
     * @return string Error message, null for no error.
     */
    public function delete(): string {
        global $currentUser;
        global $dbConnector;
        $error = '';
        // Get a DB connection.
        $dbConnection = $dbConnector->getConnection();
        // Make delete statement.
        $queryData = [
            'personId' => $this->getStudentId(),
            'courseId' => $this->getCourseId()
        ];
        $sql = 'DELETE FROM enrollments WHERE person = :personId and course = :courseId';
        try {
            $stmnt = $dbConnection->prepare($sql);
            $queryResult = $stmnt->execute($queryData);
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }
        $logFileRecordIdentifier = "enrollment, person {$this->getStudentId()}, course {$this->getCourseId()}";
        // Check for execution success.
        if (!$queryResult) {
            logError(self::PROGRAM_ID,
                "Delete failure (maybe) for $logFileRecordIdentifier");
            return INTERNAL_ERROR_MESSAGE;
        }
        $currentUserId = $currentUser->getId();
        logTransaction(self::PROGRAM_ID,
            "User $currentUserId deleted $logFileRecordIdentifier");
        return $error;
    }

}