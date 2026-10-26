# Academix

A mockup frontend for requesting and tracking university documents. Built with plain PHP, CSS and JavaScript. There is no database. All data is sample data defined in `includes/mock-data.php` and `includes/profile-data.php`.

## Requirements

- PHP 8.0 or newer
- A modern browser

## Setup

### Ubuntu

```bash
sudo apt update
sudo apt install php-cli
php -v
```

### Windows 11

Using winget (PowerShell):

```powershell
winget install PHP.PHP.8.3
```

Close and reopen PowerShell, then check the install:

```powershell
php -v
```

Manual install: download the Thread Safe zip from https://windows.php.net/download, extract it to `C:\php`, and add `C:\php` to your `Path` environment variable.

### macOS

Using Homebrew:

```bash
brew install php
php -v
```

If Homebrew is not installed, get it from https://brew.sh first.

## Run

From the project root.

Ubuntu and macOS:

```bash
cd academix
php -S localhost:8000
```

Windows (PowerShell):

```powershell
cd academix
php -S localhost:8000
```

Open http://localhost:8000.

To use another port, change `8000`. Press `Ctrl+C` to stop the server.

### Troubleshooting

- `php` is not recognized (Windows): reopen the terminal, or check that the PHP folder is in `Path`.
- Port already in use: run with a different port, for example `php -S localhost:8080`.
- Theme changes do not save: make sure the `data/` folder is writable by your user.

## Demo login

The login form accepts any password. Pick a role tab and the ID fills in automatically.

| Role    | ID           | Lands on                          |
| ------- | ------------ | --------------------------------- |
| Student | `2021-00123` | `student/dashboard.php`           |
| Staff   | `EMP-0101`   | `staff/request-queue.php`         |
| Admin   | `ADMIN-01`   | `admin/university-integration.php` |

Alumni sign in through the Student tab.

## Features

**Student**
- Home: recent requests, action alerts, university details
- New request: searchable document catalog, copies, release method, file uploads, fee summary
- My requests: request list with status filters and a detail pane with messages
- Profile settings (see below)

**Staff (Registrar office)**
- Request queue: stats, request list, status changes, messages to students
- Profile settings (see below)

**Admin**
- Integration: university API connection and retrieved data fields
- Employees: turn employee access on or off
- Appearance: edit light and dark theme colors with live preview and contrast badges
- Settings: document types, fees, upload rules, general options

**Account pages**
- Activate account (student, employee, alumni)
- Forgot password

## Profile settings

Open it by clicking your name chip in the top bar. Available to students and staff.

- **Personal info:** profile photo, read-only university record, editable contact details with validation and an unsaved changes indicator
- **Password and security:** change password with show/hide, strength meter and requirement checklist, sign-in alert toggle, signed-in devices list with sign out
- **Privacy and data:** data read from the university system, who can see your information, visibility preferences, correction request and data download actions

Saved contact details and the profile photo are kept in the browser's `localStorage`, per role. Passwords are never stored. Sessions, correction requests and data downloads are simulated with toasts.

## Project structure

```
academix/
  index.php                  Log in
  activate-account.php       Account activation
  forgot-password.php        Password reset request
  student/                   Student pages
  staff/                     Staff pages
  admin/                     Admin pages
  includes/
    bootstrap.php            Session start and includes
    header.php, footer.php   Shared page layout and role guard
    navigation.php           Sidebar links per role
    roles.php                Role constants and landing pages
    mock-data.php            Sample students, requests, employees, API
    profile-data.php         Sample profile, privacy and session data
    components.php           Shared PHP render helpers
    profile-components.php   Profile settings render helpers
    profile-page.php         Shared body of student and staff profile pages
    document-catalog.php     Document types, fees and requirements
    request-status.php       Status constants and groupings
    theme.php                Theme loading, saving and CSS variables
    icons.php                Inline SVG icon set
  assets/
    style.css                All styles, themed with CSS variables
    app.js                   All client behavior
  data/
    theme.json               Saved theme colors
```

## Notes

- Access is guarded by the role stored in `$_SESSION`. This is a demo guard, not real authentication.
- `data/theme.json` is written by the Appearance page, so the PHP user needs write access to the `data/` folder.
- The theme toggle and sidebar state are saved in `localStorage` and apply before first paint.

## Adding a page

1. Create `student/your-page.php`, `staff/your-page.php` or `admin/your-page.php`.
2. Set `$requiredRole`, `$currentPage` and `$pageTitle`, then require `includes/header.php` and `includes/footer.php`.
3. Add a link in `NAVIGATION_LINKS_BY_ROLE` in `includes/navigation.php`.
4. Add any new icon to `ICON_PATHS` in `includes/icons.php`.