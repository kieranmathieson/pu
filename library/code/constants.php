<?php
// App wide constants

// User roles
const ADMIN_ROLE = 'admin';
const STAFF_ROLE = 'staff';
const FACULTY_ROLE = 'faculty';
const STUDENT_ROLE = 'student';

// Field names
// person table
const DB_FIELD_NAME_PERSON_ID = "person_id";
const DB_FIELD_NAME_USERNAME = "username";
const DB_FIELD_NAME_PASSWORD = "password";
const DB_FIELD_NAME_FIRST_NAME = "first_name";
const DB_FIELD_NAME_LAST_NAME = "last_name";
const DB_FIELD_NAME_EMAIL = "email";
const DB_FIELD_NAME_TELEPHONE = "telephone";
const DB_FIELD_NAME_ABOUT = "about";
const DB_FIELD_NAME_ADMIN = "admin";
const DB_FIELD_NAME_STAFF = "staff";
const DB_FIELD_NAME_FACULTY = "faculty";
const DB_FIELD_NAME_STUDENT = "student";

// course table
const DB_FIELD_NAME_COURSE_ID = "course_id";
const DB_FIELD_NAME_COURSE_CODE = "code";
const DB_FIELD_NAME_COURSE_TITLE = "title";
const DB_FIELD_NAME_COURSE_MAX_ENROLLMENTS = 'max_enrollments';
const DB_FIELD_NAME_COURSE_INSTRUCTOR = 'instructor';

// How many characters in a course code?
const COURSE_CODE_NUM_CHARS = 7;

// File paths.
const SECRET_DB_PARAMS_FILE_PATH = '../db-params.ini';
const ERROR_LOG_FILE_PATH = '../error-log.csv';
const AUDIT_TRAIL_FILE_PATH = '../audit-log.csv';

const INTERNAL_ERROR_MESSAGE = "
    Sorry, there was an internal error. Please 
    <a href='reporting-errors.php' target='_blank'>report it</a>";