
# 🚀 Modular Scaffolder for Laravel

**Modular Scaffolder** is a developer-friendly package that helps you **generate full-stack modular CRUD systems** quickly.

<!-- ✅ Laravel Modular Backend  
✅ Inertia.js + React + Typescript Frontend  
✅ Full CRUD Pages (Create/Edit/Show/Index)  
✅ React Hook Form + Zod Validation  
✅ TailwindCSS + Shadcn/UI Ready  
✅ Toast Notifications + Confirm Dialogs Included -->

---

## 📦 Installation

**Step 1:** Install via Composer

```bash
composer require codedsultan/modular-scaffolder
```

**Step 2:** (Only for local development testing)

If developing locally inside another Laravel project:

```json
"repositories": [
    {
        "type": "path",
        "url": "./packages/ModularScaffolder"
    }
]
```
then run:

```bash
composer require codedsultan/modular-scaffolder:@dev
```

✅ Ready to use.

---

## 🚀 Available Artisan Commands

| Command | Action |
|:--------|:-------|
| `php artisan module:install {ModuleName}` | Scaffold full Laravel backend module |
| `php artisan module:install-frontend {ModuleName}` | Scaffold full React frontend module |
| `php artisan module:add-crud {ModuleName}` | Add CRUD operations to backend controller |

✅ One command creates model, service, repository, controller, requests, resources, events, routes, tests, factories, and more.

✅ Frontend side creates pages, services, hooks, components.

---

## ✨ Features

- Modular Folder Structure (Domain-Driven)
- Backend CRUD API generation
<!-- - Frontend CRUD UI generation (Inertia.js + React + Tailwind) -->
<!-- - React Hook Form + Zod Validation
- Toasts for Notifications
- Confirm Dialogs for Deletes
- Pagination Ready
- Reusable Layout (Dashboard)
- Extendable & Customisable -->

---

## 📋 Example Usage

```bash
php artisan module:install Product
php artisan module:install-frontend Product
php artisan module:add-crud Product
```

✅ Full working Product Management module created instantly.

---

## 📚 Requirements

- PHP 8.1+
- Laravel 10 or 11
<!-- - Node 18+ (for Vite + Tailwind)
- TailwindCSS
- Shadcn UI installed
- Inertia.js React adapter installed -->

---

## 🛠 Development Setup (Local Testing)

If you are developing this package locally:

```bash
cd packages/ModularScaffolder

composer dump-autoload
npm install
npm run dev
```

✅ Then require the package via `"type": "path"` setup.

---

## 🛡️ License

MIT License.

---

## ✨ About Codedsultan

Built and maintained by [Codedsultan](https://github.com/Codedsultan).  
<!-- We build tools that make Laravel and React development faster, cleaner, and happier. 🚀
```

---

 -->

[![Tests](https://github.com/Codedsultan/modular-scaffolder/actions/workflows/tests.yml/badge.svg)](https://github.com/Codedsultan/modular-scaffolder/actions)
[![codecov](https://codecov.io/gh/Codedsultan/modular-scaffolder/branch/main/graph/badge.svg)](https://codecov.io/gh/Codedsultan/modular-scaffolder)

<!-- Full-stack Laravel Modular CRUD Generator... -->



