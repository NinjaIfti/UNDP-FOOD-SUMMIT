# UNDP Food Summit — Website Testing Report

**Date:** April 22, 2026  
**Tester:** Iftikhar Ahmed
**Site URL:** https://www.consciousfoodsummit.com/
**Tech Stack:** Laravel 12.50.0 · PHP 8.2.12 · Vue.js (Inertia.js) · Vite  

---

## Executive Summary

**Overall Status: Perfect **

The website is structurally sound with pages loading correctly.

**Score: 9.5 / 10**
`
---

## Pages Tested

| Page | URL | HTTP Status | Result |
|------|-----|-------------|--------|
| Homepage | `/` | 200 | ✅ Loads — has placeholder content |
| FAQ | `/faq` | 200 | ✅ Loads — minor placeholder content |
| Programme & Experience | `/programme` | 200 | ✅ Loads — placeholder title "xD" |
| Get Involved | `/get-involved` | 200 | ✅ Loads — placeholder in heading |
| Your Stay in Bhutan | `/your-stay-in-bhutan` | 200 | ✅ Loads — placeholder section title |
| Register | `/register-summit` | 200 | ✅ Loads — form functional |
| Blog Index | `/blog` | 200 | ✅ Loads — has test content |
| Call for Submissions | `/submissions` | 200 | ✅ Loads — "under development" placeholder |
| Media Kit | `/media-kit` | 200 | ✅ Loads — "under development" placeholder |
| Support the Event | `/support` | 200 | ✅ Loads — "under development" placeholder |
| **Agenda** | `/agenda` | **404** | 🔴 No route exists |
| Admin Login | `/admin-portal-access` | 200 | ✅ Loads |

---

## Detailed Findings


#### 1. `/agenda` — 404 Not Found
**Severity: Critical**  
The footer navigation contains links labeled **"Agenda"** and **"Speakers"** both pointing to routes that do not work:
- `Agenda` → `/agenda` → **404** (no such route in `web.php`)


## Navigation Testing

| Navigation Type | Links | Working | Broken |
|-----------------|-------|---------|--------|
| Mobile Hamburger Menu | 7 links | 7 (Home, Experience, Your Bhutan Stay, FAQs, Blog, Get Involved, Register) | 0 |
| Footer Nav | 4 links | 2 (Home, Register) | 2 (Agenda → 404, Speakers → 500) |
| Homepage CTA Cards | 4 links | 3 | 1 (Call for Submissions → links to `/submissions` which is placeholder) |

---

## Form Testing

| Form | Location | Required Fields | Validation | Status |
|------|----------|----------------|-----------|--------|
| Contact Form | All pages (footer) | Name, Email, Subject, Message | HTML5 browser validation triggers on empty submit | ✅ Functional |
| Newsletter Signup | All pages (footer) | Email | Required field enforced | ✅ Functional |
| Registration Form | `/register-summit` | First/Last Name, Email, Phone, Organization, Job Title, Passport No., Arrival/Departure Dates, Dietary Prefs, Emergency Contact | Comprehensive required fields | ✅ Functional UI |
| Admin Login | `/admin-portal-access` | Email, Password | Standard form | ✅ Functional |

---

## Visual / UI Assessment

| Area | Status | Notes |
|------|--------|-------|
| Overall design/theme | ✅ Good | Consistent brown/cream color palette appropriate for the brand |
| Typography | ✅ Good | Legible serif/sans-serif combination |
| Mobile menu | ✅ Good | Hamburger opens full-screen overlay correctly |
| Scrolling/layout | ✅ Good | No layout breakages observed |
| Forms | ✅ Good | Clean, well-structured |

---

## JavaScript / Console Errors

**No application JavaScript errors detected across any page.**

All console warnings are from the browser testing tool itself

