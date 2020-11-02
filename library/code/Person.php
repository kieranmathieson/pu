<?php
require_once 'DbConnector.php';
require_once 'utils.php';

class Person
{
    // Name to identify this file in error logs.
    private const PROGRAM_ID = 'Person';

    // Init object properties.
    private $id = 0;
    private $lastName = '';
    private $firstName = '';
    private $userName = '';
    private $encryptedPassword = '';
    private $email = '';
    private $telephone = '';
    private $about = '';
    private $isAdmin = false;
    private $isStaff = false;
    private $isFaculty = false;
    private $isStudent = false;

    /**
     * Get the person's id.
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the person's id.
     * @param int $idIn
     * @return string Error message for user.
     */
    public function setId(int $idIn): string
    {
        $error = '';
        // Data type check.
        if (is_nan($idIn)) {
            logError(self::PROGRAM_ID, "Error in setId: id is not a number: $idIn");
            $error = 'Sorry, person id must be a number<br>';
        }
        // Range check.
        elseif ($idIn <= 0) {
            logError(self::PROGRAM_ID,"Error in setId: value is $idIn");
            $error = 'Sorry, person id must be 1 or more.<br>';
        }
        else {
            // All OK, remember it.
            $this->id = $idIn;
        }
        return $error;
    }

    /**
     * Get the username.
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set the user name.
     * @param string $userNameIn
     * @return string Error message.
     */
    public function setUserName(string $userNameIn): string
    {
        $error = '';
        // Remove leading and trailing spaces.
        $userNameIn = trim($userNameIn);
        if ($userNameIn == '') {
            logError(self::PROGRAM_ID,'Error in setUserName. Value MT.');
            $error = 'Sorry, username cannot be empty.<br>';
        }
        else {
            $this->userName = $userNameIn;
        }
        return $error;
    }

    /**
     * Get the encrypted password.
     * @return string
     */
    public function getEncryptedPassword(): string
    {
        return $this->encryptedPassword;
    }

    /**
     * Set the encrypted password.
     * @param string $encryptedPasswordIn
     * @return string Error message.
     */
    public function setEncryptedPassword(string $encryptedPasswordIn)
    {
        $error = '';
        if ($encryptedPasswordIn == '') {
            logError(self::PROGRAM_ID,'Error in setEncryptedPassword. Password empty.');
            $error = 'Sorry, password cannot be empty.<br>';
        }
        else {
            $this->encryptedPassword = $encryptedPasswordIn;
        }
        return $error;
    }

    /**
     * Get the last name.
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Set the last name.
     * @param string $lastNameIn
     * @return string Error message.
     */
    public function setLastName(string $lastNameIn): string
    {
        $error = '';
        $lastNameIn = trim($lastNameIn);
        if ($lastNameIn == '') {
            logError(self::PROGRAM_ID, 'Error in setLastName. Value empty.');
            $error = 'Sorry, last name cannot be empty.<br>';
        }
        else {
            $this->lastName = $lastNameIn;
        }
        return $error;
    }

    /**
     * Get the first name.
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the first name.
     * @param string $firstNameIn
     * @return string Error message.
     */
    public function setFirstName(string $firstNameIn): string
    {
        $error = '';
        $firstNameIn = trim($firstNameIn);
        if ($firstNameIn == '') {
            logError(self::PROGRAM_ID, 'Error in setFirstName. Value MT.');
            $error = 'Sorry, first name cannot be empty.<br>';
        }
        else {
            $this->firstName = $firstNameIn;
        }
        return $error;
    }

    /**
     * Get the full name.
     * @return string
     */
    public function getFullName()
    {
        return "$this->firstName $this->lastName";
    }

    /**
     * Get the telephone number.
     * @return string Telephone
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set the telephone number.
     * @param string $telephoneIn
     * @return string Error message.
     */
    public function setTelephone(string $telephoneIn): string
    {
        $error = '';
        $telephoneIn = trim($telephoneIn);
        $this->telephone = $telephoneIn;
        return $error;
    }

    /**
     * Get email address.
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set email address.
     * @param string $emailIn
     * @return string
     */
    public function setEmail(string $emailIn): string
    {
        $error = '';
        $emailIn = trim($emailIn);
        if ($emailIn != '') {
            if (!filter_var($emailIn, FILTER_VALIDATE_EMAIL)) {
                logError(self::PROGRAM_ID,"Error in setEmail. Value: $emailIn");
                $error = 'Sorry, the email address is invalid.<br>';
            }
        }
        if ($error == '') {
            $this->email = $emailIn;
        }
        return $error;
    }

    /**
     * Get about.
     * @return string
     */
    public function getAbout(): string
    {
        return $this->about;
    }

    /**
     * Set about.
     * @param string $aboutIn
     * @return string Error message.
     */
    public function setAbout(string $aboutIn): string
    {
        $error = '';
        $aboutIn = trim($aboutIn);
        $this->about = $aboutIn;
        return $error;
    }

    /**
     * Is the user an admin?
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * Set admin role.
     * @param bool $isAdminIn
     * @return string Error message.
     */
    public function setIsAdmin(bool $isAdminIn): string
    {
        $error = '';
        $this->isAdmin = $isAdminIn;
        return $error;
    }

    /**
     * Is staff?
     * @return bool
     */
    public function isStaff(): bool
    {
        return $this->isStaff;
    }

    /**
     * Set staff role.
     * @param bool $isStaffIn
     * @return string Error message.
     */
    public function setIsStaff(bool $isStaffIn): string
    {
        $error = '';
        $this->isStaff = $isStaffIn;
        return $error;
    }

    /**
     * Is faculty?
     * @return bool
     */
    public function isFaculty(): bool
    {
        return $this->isFaculty;
    }

    /**
     * Set faculty role.
     * @param bool $isFacultyIn
     * @return string Error message.
     */
    public function setIsFaculty(bool $isFacultyIn): string
    {
        $error = '';
        $this->isFaculty = $isFacultyIn;
        return $error;
    }

    /**
     * Is student?
     * @return bool
     */
    public function isStudent(): bool
    {
        return $this->isStudent;
    }

    /**
     * Set student role.
     * @param bool $isStudentIn
     * @return string Error message.
     */
    public function setIsStudent(bool $isStudentIn): string
    {
        $error = '';
        $this->isStudent = $isStudentIn;
        return $error;
    }


    /**
     * Load user record from database.
     * @param int $userId User id.
     * @return string Error message, MT for no error.
     */
    public function load(int $userId): string
    {
        global $dbConnector;
        $accumulatedErrors = '';
        // Get a DB connection.
        $dbConnection = $dbConnector->getConnection();
        // Get people record for $userId.
        $sql = 'select * from people where person_id = ?';
        try {
            $stmnt = $dbConnection->prepare($sql);
            $queryResult = $stmnt->execute([$userId]);
        } catch (PDOException $e) {
            logError(self::PROGRAM_ID, $e->getMessage());
            return INTERNAL_ERROR_MESSAGE;
        }
        // Check for execution success.
        if (!$queryResult) {
            logError(self::PROGRAM_ID, "Person load failed for user id: $userId");
            return INTERNAL_ERROR_MESSAGE;
        }
        // Get exactly one row?
        $numRows = $stmnt->rowCount();
        if ($numRows != 1) {
            logError(self::PROGRAM_ID, "Person load failed. Num rows: $numRows");
            return INTERNAL_ERROR_MESSAGE;
        }
        // Grab the row.
        $userRow = $stmnt->fetch();
        $accumulatedErrors .= $this->populateFromDatabaseRow($userRow);
        return $accumulatedErrors;
    }

    /**
     * Populate fields from a DB row.
     * @param array $userRow
     * @return string Error messages.
     */
    public function populateFromDatabaseRow(array $userRow): string {
        $accumulatedErrors = '';
        $userRow = replaceNullWithSpace($userRow);
        $accumulatedErrors .= $this->setId($userRow['person_id']);
        $accumulatedErrors .= $this->populateFields(
            $userRow['username'], $userRow['password'],
            $userRow['last_name'], $userRow['first_name'],
            $userRow['email'], $userRow['telephone'],
            $userRow['about'],
            $userRow['admin'] == 1,
            $userRow['staff'] == 1,
            $userRow['faculty'] == 1,
            $userRow['student'] == 1
        );
        return $accumulatedErrors;
    }


    /**
     * Populate fields.
     * @param string $userNameIn User name. Required.
     * @param string $encryptedPasswordIn Encrypted password. Required.
     * @param string $lastNameIn Last name. Required.
     * @param string $firstNameIn First name. Required.
     * @param string $emailIn Email. Required.
     * @param string $telephoneIn Telephone.
     * @param string $aboutIn About statement.
     * @param bool $isAdminIn Is admin?
     * @param bool $isStaffIn Is staff?
     * @param bool $isFacultyIn Is faculty?
     * @param bool $isStudentIn Is student?
     * @return string Error message, null for no error.
     */
    public function populateFields(
        string $userNameIn, string $encryptedPasswordIn,
        string $lastNameIn, string $firstNameIn,
        string $emailIn, string $telephoneIn,
        string $aboutIn,
        bool $isAdminIn, bool $isStaffIn, bool $isFacultyIn, bool $isStudentIn
    ): string {
        $accumulatedErrors = '';
        $accumulatedErrors .= $this->setUserName($userNameIn);
        $accumulatedErrors .= $this->setEncryptedPassword($encryptedPasswordIn);
        $accumulatedErrors .= $this->setFirstName($firstNameIn);
        $accumulatedErrors .= $this->setLastName($lastNameIn);
        $accumulatedErrors .= $this->setEmail($emailIn);
        $accumulatedErrors .= $this->setTelephone($telephoneIn);
        $accumulatedErrors .= $this->setAbout($aboutIn);
        $accumulatedErrors .= $this->setIsAdmin($isAdminIn);
        $accumulatedErrors .= $this->setIsStaff($isStaffIn);
        $accumulatedErrors .= $this->setIsFaculty($isFacultyIn);
        $accumulatedErrors .= $this->setIsStudent($isStudentIn);
        return $accumulatedErrors;
    }

    public function saveAsNew(): string {
        global $dbConnector;
        /** @var $currentUser Person */
        global $currentUser;
        $accumulatedErrors = '';
        // Get a DB connection.
        $dbConnection = $dbConnector->getConnection();
        // Make INSERT.
        $sql = "
            INSERT INTO people (
                username, password, 
                first_name, last_name,
                email, telephone, 
                about,
                admin, staff, faculty, student
            )
            values (
                :userName,
                :encryptedPassword,
                :firstName,
                :lastName,
                :email,
                :telephone,
                :about,
                :admin,
                :staff,
                :faculty,
                :student
            );
        ";
        $insertData = [
          'userName' => $this->getUserName(),
          'encryptedPassword' => $this->getEncryptedPassword(),
          'firstName' => $this->getFirstName(),
          'lastName' => $this->getLastName(),
          'email' => $this->getEmail(),
          'telephone' => $this->getTelephone(),
          'about' => $this->getAbout(),
          'admin' => (int)$this->isAdmin(),
          'staff' => (int)$this->isStaff(),
          'faculty' => (int)$this->isFaculty(),
          'student' => (int)$this->isStudent(),
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
        logTransaction(self::PROGRAM_ID, "User $currentUser->id added user $this->id");
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
        $error = '';
        // Get a DB connection.
        $dbConnection = $dbConnector->getConnection();
        $updateData = [
            'userName' => $this->getUserName(),
            'encryptedPassword' => $this->getEncryptedPassword(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail(),
            'telephone' => $this->getTelephone(),
            'about' => $this->getAbout(),
            'admin' => (int)$this->isAdmin(),
            'staff' => (int)$this->isStaff(),
            'faculty' => (int)$this->isFaculty(),
            'student' => (int)$this->isStudent(),
            'id' => $this->getId()
        ];
        $sql = "
            UPDATE people SET
                username = :userName, password = :encryptedPassword, 
                first_name = :firstName, last_name = :lastName,
                email = :email, telephone = :telephone,
                about = :about,
                admin = :admin, staff = :staff, faculty = :faculty, student = :student
            WHERE person_id = :id
        ";
        try {
            $stmnt = $dbConnection->prepare($sql);
            $stmnt->execute($updateData);
        } catch (PDOException $e) {
            logError(self::PROGRAM_ID, $e->getMessage());
            return INTERNAL_ERROR_MESSAGE;
        }
        logTransaction(self::PROGRAM_ID, "User $currentUser->id updated user $this->id");
        return $error;
    }

    /**
     * Delete a person record from the database.
     * @param int $personId Id of the record.
     * If not given, or zero, then use this's value.
     * @return string Error message, null for no error.
     */
    public function delete(int $personId = 0): string {
        /** @var $currentUser Person */
        global $currentUser;
        $error = '';
        global $dbConnector;
        // Get a DB connection.
        $dbConnection = $dbConnector->getConnection();
        // What id to use?
        if ($personId == 0) {
            $personId = $this->getId();
        }
        // Make delete statement.
        $queryData = [
            'id' => $personId
        ];
        $sql = 'DELETE FROM people WHERE person_id = :id';
        try {
            $stmnt = $dbConnection->prepare($sql);
            $queryResult = $stmnt->execute($queryData);
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }
        // Check for execution success.
        if (!$queryResult) {
            logError(self::PROGRAM_ID, "Delete failure (maybe) for person $personId");
            return INTERNAL_ERROR_MESSAGE;
        }
        logTransaction(self::PROGRAM_ID, "User $currentUser->id deleted user $personId");
        return $error;
    }

}