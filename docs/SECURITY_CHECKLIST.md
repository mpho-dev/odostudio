# Security Checklist

Quick reference for security best practices and audit checklist.

---

## Pre-Deployment Security Checklist

### Environment Configuration

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` generated and secure
- [ ] `APP_URL` set to HTTPS URL
- [ ] `SESSION_ENCRYPT=true`
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] `LOG_LEVEL=error` or `critical`

### Dependencies

- [ ] `composer update` run
- [ ] No known CVE vulnerabilities
- [ ] Third-party packages verified

### Database

- [ ] Migrations run
- [ ] Indexes created
- [ ] Soft deletes implemented where needed
- [ ] No sensitive data in migrations

### Authentication

- [ ] Password hashing using bcrypt/argon
- [ ] Session regeneration on login
- [ ] CSRF protection enabled
- [ ] Rate limiting on auth routes
- [ ] Password reset secure

### Authorization

- [x] Role middleware implemented
- [x] Policies for all models
- [x] Form Request authorization checked
- [x] No authorization bypasses

---

## Code Review Checklist

### Input Validation

- [ ] All user input validated
- [ ] Type casting where appropriate
- [ ] Length limits enforced
- [ ] Format validation (regex, email, etc.)
- [ ] File uploads validated server-side

### Output Encoding

- [ ] Blade automatic escaping used (`{{ }}`)
- [ ] Raw output carefully reviewed (`{!! !!}`)
- [ ] HTMLPurifier for rich text
- [ ] Markdown sanitized

### Database

- [ ] Eloquent ORM used (no raw SQL)
- [ ] Parameterized queries
- [ ] Mass assignment protected
- [ ] Indexes on queried columns
- [ ] Foreign key constraints

### File Operations

- [x] File type validated server-side
- [ ] File size limits enforced
- [ ] Filenames sanitized
- [ ] Path traversal prevented
- [ ] Storage access controlled

### Business Logic

- [x] Status transitions validated
- [x] Double-booking prevented
- [x] Race conditions handled
- [ ] Transactions for multi-step operations
- [ ] Concurrency handled

---

## Common Vulnerabilities Checklist

### SQL Injection

- [ ] No raw SQL queries
- [ ] User input never in query strings
- [ ] Eloquent query builder used
- [ ] Parameterized where raw SQL needed

### XSS (Cross-Site Scripting)

- [ ] All output escaped
- [x] CSP headers configured
- [ ] HTMLPurifier for rich text
- [ ] No unsafe inline scripts

### CSRF (Cross-Site Request Forgery)

- [ ] `@csrf` on all forms
- [ ] CSRF middleware enabled
- [ ] Double-submit cookie pattern if needed

### Authentication Bypass

- [ ] All routes properly protected
- [ ] Middleware applied correctly
- [ ] Policy authorization checked
- [ ] Session validation

### Mass Assignment

- [x] `$fillable` arrays defined
- [x] Sensitive fields guarded
- [x] No user-controllable IDs

### Security Headers

- [ ] X-Frame-Options: DENY
- [ ] X-Content-Type-Options: nosniff
- [ ] Strict-Transport-Security
- [x] Content-Security-Policy

---

## Testing Checklist

### Unit Tests

- [ ] Model security methods tested
- [ ] Validation rules tested
- [ ] Authorization logic tested

### Feature Tests

- [ ] Authentication flows tested
- [ ] Authorization tested for each role
- [ ] Input validation tested
- [ ] Error handling tested

### Security Tests

- [ ] SQL injection attempts fail
- [ ] XSS attempts sanitized
- [ ] CSRF protection works
- [ ] Rate limiting enforced

---

## Quick Security Commands

```bash
# Update dependencies
composer update

# Check for vulnerabilities
composer audit

# Generate app key
php artisan key:generate

# Clear caches
php artisan config:clear
php artisan cache:clear

# Run tests
php artisan test

# Format code
vendor/bin/pint
```

---

## Vulnerability Severity Guide

| Severity | Response Time | Examples |
|----------|----------------|----------|
| Critical | 24 hours | Auth bypass, RCE, Data breach |
| High | 1 week | SQL injection, XSS, CSRF |
| Medium | 1 month | Information disclosure, DoS |
| Low | Next sprint | Minor issues, best practices |

---

## Useful Resources

- [Laravel Security Docs](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [CVE Database](https://cve.mitre.org/)
- [Security Headers](https://securityheaders.com/)

---

*Last Updated: April 2026 (Phase 1-3 fixes applied)*
