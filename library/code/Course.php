<?php
require_once 'DbConnector.php';
require_once 'utils.php';

class Course
{
    // Name to identify this file in error logs.
    private const PROGRAM_ID = 'Course';

    // Init object properties.
    private $id = 0;
    private $code = '';
    private $title = '';
    private $maxEnrollment = 0;
    private $instructor = 0;

    /**
     * Get the course's id.
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * Set the course's id.
     * @param int $idIn
     * @return string Error message for user.
     */
    public function setId(int $idIn): string
    {
        $error = '';
        // Data type check.
        if (! is_numeric($idIn)) {
            logError(self::PROGRAM_ID, "Error in setId: id is not a number: $idIn");
            $error = 'Sorry, course id must be a number<br>';
        }
        // Range check.
        elseif ($idIn <= 0) {
            logError(self::PROGRAM_ID,"Error in setId: value is $idIn");
            $error = 'Sorry, course id must be 1 or more.<br>';
        }
        else {
            // All OK, remember it.
            $this->id = $idIn;
        }
        return $error;
    }

    /**
     * Get the course code.
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set the course code.
     * @param string $codeIn
     * @return string Error message.
     */
    public function setCode(string $codeIn): string
    {
        $error = '';
        // Remove leading and trailing spaces.
        $codeIn = trim($codeIn);
        if ($codeIn == '') {
            logError(self::PROGRAM_ID,'Error in setCode. Value MT.');
            $error = 'Sorry, course code cannot be empty.<br>';
        }
        elseif (strlen($codeIn) != COURSE_CODE_NUM_CHARS) {
            logError(self::PROGRAM_ID,"Error in setCode. Num chars wrong: $codeIn");
            $numChars = COURSE_CODE_NUM_CHARS;
            $error = "Sorry, course code must be $numChars characters.<br>";
        }
        else {
            $this->code = $codeIn;
        }
        return $error;
    }

    /**
     * Get the course title.
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the course title.
     * @param string $titleIn
     * @return string Error message.
     */
    public function setTitle(string $titleIn): string
    {
        $error = '';
        // Remove leading and trailing spaces.
        $titleIn = trim($titleIn);
        if ($titleIn == '') {
            logError(self::PROGRAM_ID,'Error in setTitle. Value MT.');
            $error = 'Sorry, course title cannot be empty.<br>';
        }
        else {
            $this->title = $titleIn;
        }
        return $error;
    }

    /**
     * Get the maximum enrollment.
     * @return int
     */
    public function getMaxEnrollments(): int
    {
        return $this->maxEnrollment;
    }

    /**
     * Set the maximum enrollment.
     * @param int $maxEnrollmentIn
     * @return string Error message.
     */
    public function setMaxEnrollments($maxEnrollmentIn): string
    {
        $error = '';
        // Data type check.
        if ($maxEnrollmentIn == '' || ! is_numeric($maxEnrollmentIn)) {
            logError(self::PROGRAM_ID,
                "Error in setMaxEnrollment: value is not a number: $maxEnrollmentIn");
            $error = 'Sorry, maximum enrollment must be a number.<br>';
        }
        // Range check.
        elseif ($maxEnrollmentIn <= 0) {
            logError(self::PROGRAM_ID,
                "Error in setMaxEnrollment: value is $maxEnrollmentIn");
            $error = 'Sorry, maximum enrollment must be 1 or more.<br>';
        }
        else {
            // All OK, remember it.
            $this->maxEnrollment = $maxEnrollmentIn;
        }
        return $error;
    }

    /**
     * Get the instructor.
     * @return int Instructor id, key in people table.
     */
    public function getInstructor()
    {
        return $this->instructor;
    }

    /**
     * Set the instructor.
     * @param int $instructorIn Instructor id, or zero.
     * @return string Error message.
     */
    public function setInstructor(int $instructorIn): string
    {
        $error = '';
        // Data type check.
        if (! is_numeric($instructorIn)) {
            logError(self::PROGRAM_ID,
                "Error in setInstructor: value is not a number: $instructorIn");
            $error = 'Sorry, instructor id must be a number.<br>';
        }
        // Range check.
        elseif ($instructorIn < 0) {
            logError(self::PROGRAM_ID,
                "Error in setInstructor: value is $instructorIn");
            $error = 'Sorry, instructor id cannot be negative.<br>';
        }
        // 0 is OK.
        elseif ($instructorIn == 0) {
            $this->instructor = $instructorIn;
        }
        else {
            // Must be > 0.
            // Make sure the instructor exists.
            $person = new Person();
            $errorMessage = $person->load($instructorIn);
            if ($errorMessage != '') {
                logError(
                    self::PROGRAM_ID,
                    "Error in setInstructor, not found: value is $instructorIn"
                );
                $error = 'Sorry, instructor must be the id of a person.<br>';
            } else {
                // All OK, remember it.
                $this->instructor = $instructorIn;
            }
        }
        return $error;
    }

    /**
     * Load course record from database.
     * @param int $courseId Course id.
     * @return string Error message, MT for no error.
     */
    public function load(int $courseId): string
    {
        global $dbConnector;
        $accumulatedErrors = '';
        // Get a DB connection.
        $dbConnection = $dbConnector->getConnection();
        // Get people record for $courseId.
        $sql = 'select * from courses where course_id = ?';
        try {
            $stmnt = $dbConnection->prepare($sql);
            $queryResult = $stmnt->execute([$courseId]);
        } catch (PDOException $e) {
            logError(self::PROGRAM_ID, $e->getMessage());
            return INTERNAL_ERROR_MESSAGE;
        }
        // Check for execution success.
        if (!$queryResult) {
            logError(self::PROGRAM_ID, "Course load failed for id: $courseId");
            return INTERNAL_ERROR_MESSAGE;
        }
        // Get exactly one row?
        $numRows = $stmnt->rowCount();
        if ($numRows != 1) {
            logError(self::PROGRAM_ID, "Course load failed. Num rows: $numRows");
            return INTERNAL_ERROR_MESSAGE;
        }
        // Grab the row.
        $userRow = $stmnt->fetch();
        $accumulatedErrors .= $this->populateFromDatabaseRow($userRow);
        return $accumulatedErrors;
    }

    /**
     * Populate fields from a DB row.
     * @param array $courseRow
     * @return string Error messages.
     */
    public function populateFromDatabaseRow(array $courseRow): string {
        $accumulatedErrors = '';
        $courseRow = replaceNullWithMtString($courseRow);
        $accumulatedErrors .= $this->setId($courseRow['course_id']);
        $accumulatedErrors .= $this->populateFields(
            $courseRow['code'],
            $courseRow['title'],
            $courseRow['max_enrollments'],
            $courseRow['instructor']
        );
        return $accumulatedErrors;
    }

    /**
     * Populate fields.
     * @param string $codeIn Course code. Required.
     * @param string $titleIn Title. Required.
     * @param string $maxEnrollments maximum enrollments. Required.
     * @param string $instructor Instructor. Required.
     * @return string Error message, null for no error.
     */
    public function populateFields(
        string $codeIn, string $titleIn,
        string $maxEnrollments, string $instructor
    ): string {
        $accumulatedErrors = '';
        $accumulatedErrors .= $this->setCode($codeIn);
        $accumulatedErrors .= $this->setTitle($titleIn);
        $accumulatedErrors .= $this->setInstructor($instructor);
        $accumulatedErrors .= $this->setMaxEnrollments($maxEnrollments);
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
            INSERT INTO courses (
                code,
                title, 
                max_enrollments,
                instructor
            )
            values (
                :code,
                :title,
                :maxEnrollments,
                :instructor
            );
        ";
        $insertData = [
            'code' => $this->getCode(),
            'title' => $this->getTitle(),
            'maxEnrollments' => $this->getMaxEnrollments(),
            'instructor' => $this->getInstructor(),
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
        $personId = $currentUser->getId();
        logTransaction(self::PROGRAM_ID, "User $personId added course $this->id");
        return $accumulatedErrors;
    }

    /**
     * Check whether required fields are present.
     * @return string Errors, or MT string if all OK.
     */
    private function checkRequiredDataPresent(): string {
        $accumulatedErrors = '';
        if ($this->getCode() == '') {
            $accumulatedErrors .= 'Sorry, code is required.<br>';
        }
        if ($this->getTitle() == '') {
            $accumulatedErrors .= 'Sorry, title is required.<br>';
        }
        if ($this->getMaxEnrollments() == '') {
            $accumulatedErrors .= 'Sorry, maximum enrollments is required.<br>';
        }
//        if ($this->getInstructor() == '') {
//            $accumulatedErrors .= 'Sorry, instructor is required.<br>';
//        }
        return $accumulatedErrors;
    }

    /**
     * Update record in DB, using current values.
     * @return string Error message, null for no error.
     */
    public function update(): string {
        global $dbConnector;
        /** @var $currentUser Person */
        global $currentUser;
        $accumulatedErrors = '';
        // Get a DB connection.
        $dbConnection = $dbConnector->getConnection();
        // Check that required data is present.
        $accumulatedErrors .= $this->checkRequiredDataPresent();
        if ($accumulatedErrors != '') {
            return $accumulatedErrors;
        }
        $updateData = [
            'code' => $this->getCode(),
            'title' => $this->getTitle(),
            'maxEnrollments' => $this->getMaxEnrollments(),
            'instructor' => $this->getInstructor(),
            'id' => $this->getId()
        ];
        $sql = "
            UPDATE courses SET
                code = :code, 
                title = :title, 
                max_enrollments = :maxEnrollments, 
                instructor = :instructor
            WHERE course_id = :id
        ";
        try {
            $stmnt = $dbConnection->prepare($sql);
            $stmnt->execute($updateData);
        } catch (PDOException $e) {
            logError(self::PROGRAM_ID, $e->getMessage());
            return INTERNAL_ERROR_MESSAGE;
        }
        $personId = $currentUser->getId();
        logTransaction(self::PROGRAM_ID, "User $personId updated course $this->id");
        return $accumulatedErrors;
    }

    /**
     * Delete a course record from the database.
     * @param int $courseId Id of the record.
     * If not given, or zero, then use this's value.
     * @return string Error message, null for no error.
     */
    public function delete(int $courseId = 0): string {
        /** @var $currentUser Person */
        global $currentUser;
        $error = '';
        global $dbConnector;
        // Get a DB connection.
        $dbConnection = $dbConnector->getConnection();
        // What id to use?
        if ($courseId == 0) {
            $courseId = $this->getId();
        }
        // Make delete statement.
        $queryData = [
            'id' => $courseId
        ];
        $sql = 'DELETE FROM courses WHERE course_id = :id';
        try {
            $stmnt = $dbConnection->prepare($sql);
            $queryResult = $stmnt->execute($queryData);
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }
        // Check for execution success.
        if (!$queryResult) {
            logError(self::PROGRAM_ID, "Delete failure (maybe) for course $courseId");
            return INTERNAL_ERROR_MESSAGE;
        }
        $personId = $currentUser->getId();
        logTransaction(self::PROGRAM_ID, "User $personId deleted course $courseId");
        return $error;
    }

}