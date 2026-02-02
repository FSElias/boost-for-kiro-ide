# Manual Testing Guide for Laravel Boost v2.0 Commands

This guide provides instructions for manually testing the `boost:install` and `boost:add-skill` commands in a real Laravel project environment.

## Prerequisites

- A fresh Laravel project (Laravel 10.x, 11.x, or 12.x)
- PHP 8.1 or higher
- Composer installed

## Setup Test Environment

1. Create a new Laravel project:
```bash
composer create-project laravel/laravel boost-test-project
cd boost-test-project
```

2. Install the boost-for-kiro-ide package:
```bash
composer require jcf/boost-for-kiro-ide:dev-main
```

3. Verify Laravel Boost v2.0 is installed:
```bash
composer show laravel/boost
```

## Test 1: boost:install Command Lists Kiro IDE

**Validates: Requirements 3.2, 12.3**

### Steps:

1. Run the boost:install command:
```bash
php artisan boost:install
```

2. **Expected Result:**
   - The command should display a list of available agents
   - "Kiro" should appear in the list of available agents
   - The command should not throw any errors

3. **Verification:**
   - [ ] Kiro IDE appears in the agent list
   - [ ] Command executes without errors
   - [ ] Display name is "Kiro" (not "kiro")

## Test 2: boost:install Creates Configuration Files

**Validates: Requirements 4.1, 4.2, 4.3, 11.1, 11.2**

### Steps:

1. Run the boost:install command and select Kiro:
```bash
php artisan boost:install
```

2. When prompted, select "Kiro" from the list

3. **Expected Result:**
   - The command should complete successfully
   - Configuration files should be created in the correct locations

4. **Verification:**
   - [ ] File `.kiro/settings/mcp.json` exists
   - [ ] File `.kiro/steering/laravel-boost.md` exists
   - [ ] MCP config file contains valid JSON
   - [ ] Guidelines file contains Laravel Boost instructions
   - [ ] Command shows success message

5. Check the created files:
```bash
ls -la .kiro/settings/
ls -la .kiro/steering/
cat .kiro/settings/mcp.json
cat .kiro/steering/laravel-boost.md
```

## Test 3: boost:install Detects Kiro IDE Automatically

**Validates: Requirements 7.1, 7.2, 7.3**

### Steps:

1. Create a `.kiro` directory in the project:
```bash
mkdir -p .kiro
```

2. Run the boost:install command:
```bash
php artisan boost:install
```

3. **Expected Result:**
   - The command should detect that this is a Kiro project
   - Kiro should be highlighted or pre-selected as the recommended agent

4. **Verification:**
   - [ ] Command detects `.kiro` directory
   - [ ] Kiro is suggested or pre-selected
   - [ ] Detection works based on project structure

## Test 4: Installation Shows Success Message

**Validates: Requirements 11.3**

### Steps:

1. Run the complete installation:
```bash
php artisan boost:install
```

2. Select Kiro and complete the installation

3. **Expected Result:**
   - Clear success message is displayed
   - Message indicates what files were created
   - Message provides next steps or usage instructions

4. **Verification:**
   - [ ] Success message is clear and informative
   - [ ] No error messages or warnings
   - [ ] User knows what to do next

## Test 5: Auto-discovery Registers ServiceProvider

**Validates: Requirements 12.1**

### Steps:

1. In a fresh Laravel project with the package installed, check registered providers:
```bash
php artisan about
```

2. **Expected Result:**
   - BoostForKiroServiceProvider should be automatically registered
   - No manual configuration in `config/app.php` should be needed

3. **Verification:**
   - [ ] ServiceProvider is auto-discovered
   - [ ] No manual registration needed
   - [ ] Package works immediately after composer install

## Test 6: boost:add-skill Command

**Validates: Requirements 6.1, 6.2**

### Steps:

1. Ensure Kiro is installed via boost:install first

2. Run the boost:add-skill command with a test repository:
```bash
php artisan boost:add-skill laravel/boost-skill-example
```

3. **Expected Result:**
   - Command executes without errors
   - Skill files are downloaded and installed
   - Skills are placed in the correct directory

4. **Verification:**
   - [ ] Command completes successfully
   - [ ] Skill files are created in `.ai/skills/` directory
   - [ ] No errors or warnings
   - [ ] Skills work with Kiro IDE

5. Check installed skills:
```bash
ls -la .ai/skills/
```

## Test 7: Skills Work with Kiro IDE

**Validates: Requirements 5.1, 5.2, 5.4**

### Steps:

1. After installing a skill via boost:add-skill, verify it's accessible

2. Check that skill files are in the correct format:
```bash
cat .ai/skills/*/SKILL.md
```

3. **Expected Result:**
   - Skills are automatically loaded by Laravel Boost
   - No additional configuration needed in Kiro
   - Skills appear in the AI context

4. **Verification:**
   - [ ] Skills are installed correctly
   - [ ] No Kiro-specific configuration needed
   - [ ] Skills are loaded automatically
   - [ ] Skills work as expected in AI interactions

## Test 8: System Detection (Platform-Specific)

**Validates: Requirements 7.1**

### Platform-Specific Tests:

#### macOS:
1. Install Kiro IDE to `/Applications/Kiro.app`
2. Run `php artisan boost:install`
3. Verify Kiro is detected automatically

#### Linux:
1. Install Kiro IDE to one of:
   - `/opt/kiro`
   - `/usr/local/bin/kiro`
   - `~/.local/bin/kiro`
2. Run `php artisan boost:install`
3. Verify Kiro is detected automatically

#### Windows:
1. Install Kiro IDE to one of:
   - `%ProgramFiles%\Kiro`
   - `%LOCALAPPDATA%\Programs\Kiro`
2. Run `php artisan boost:install`
3. Verify Kiro is detected automatically

## Troubleshooting

### Command Not Found
If `php artisan boost:install` is not found:
- Verify Laravel Boost v2.0 is installed: `composer show laravel/boost`
- Clear cache: `php artisan cache:clear`
- Regenerate autoload: `composer dump-autoload`

### Kiro Not Listed
If Kiro doesn't appear in the agent list:
- Verify the package is installed: `composer show jcf/boost-for-kiro-ide`
- Check that ServiceProvider is registered: `php artisan about`
- Clear config cache: `php artisan config:clear`

### Files Not Created
If configuration files aren't created:
- Check directory permissions
- Verify the paths in the Kiro class
- Check Laravel Boost logs for errors

## Test Results Template

Copy this template to document your test results:

```
# Test Results - boost:install and boost:add-skill

Date: _______________
Tester: _______________
Environment:
- Laravel Version: _______________
- PHP Version: _______________
- Laravel Boost Version: _______________
- Package Version: _______________

## Test 1: boost:install Lists Kiro
- [ ] PASS / [ ] FAIL
- Notes: _______________

## Test 2: Creates Configuration Files
- [ ] PASS / [ ] FAIL
- Notes: _______________

## Test 3: Detects Kiro Automatically
- [ ] PASS / [ ] FAIL
- Notes: _______________

## Test 4: Shows Success Message
- [ ] PASS / [ ] FAIL
- Notes: _______________

## Test 5: Auto-discovery Works
- [ ] PASS / [ ] FAIL
- Notes: _______________

## Test 6: boost:add-skill Works
- [ ] PASS / [ ] FAIL
- Notes: _______________

## Test 7: Skills Work with Kiro
- [ ] PASS / [ ] FAIL
- Notes: _______________

## Test 8: System Detection
- [ ] PASS / [ ] FAIL
- Platform: _______________
- Notes: _______________

## Overall Result
- [ ] ALL TESTS PASSED
- [ ] SOME TESTS FAILED (see notes above)

## Additional Notes
_______________
```

## Conclusion

These manual tests verify that the boost-for-kiro-ide package works correctly with Laravel Boost v2.0 in a real-world environment. The automated tests in the test suite verify the component-level functionality, while these manual tests verify the end-to-end user experience.
