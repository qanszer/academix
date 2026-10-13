<?php

const ROLE_STUDENT = 'student';
const ROLE_STAFF = 'staff';
const ROLE_ADMIN = 'admin';

const ROLE_LABELS = [
    ROLE_STUDENT => 'Student',
    ROLE_STAFF => 'Registrar office',
    ROLE_ADMIN => 'Administrator',
];

const LANDING_PAGE_BY_ROLE = [
    ROLE_STUDENT => 'student/dashboard.php',
    ROLE_STAFF => 'staff/request-queue.php',
    ROLE_ADMIN => 'admin/university-integration.php',
];

const DEMO_USER_ID_BY_ROLE = [
    ROLE_STUDENT => '2021-00123',
    ROLE_STAFF => 'EMP-0101',
    ROLE_ADMIN => 'ADMIN-01',
];
