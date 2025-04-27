# ✨ Contributing Guide

First off, **thank you for considering contributing** to Modular Scaffolder! 🚀

We truly appreciate the community's effort to make this project better for everyone.

---

## 🛠 How to Contribute

1. Fork the repository
2. Create a new feature branch (`git checkout -b feature/your-feature-name`)
3. Make your changes
4. Ensure all tests pass locally (`php artisan test`)
5. Commit your changes (`git commit -m "feat: your message here"`)
6. Push your branch (`git push origin feature/your-feature-name`)
7. Open a Pull Request (PR)

✅ Every PR must pass **PHPUnit tests** and **build cleanly**.

---

## 📋 Pull Request Guidelines

- Follow [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/):
  - `feat:` for new features
  - `fix:` for bug fixes
  - `docs:` for documentation updates
  - `test:` for adding missing tests
  - `chore:` for maintenance work
- Keep PRs **small and focused** (one feature/fix per PR)
- Write clear PR descriptions
- Update the [CHANGELOG.md](CHANGELOG.md) if your changes are significant

---

## 🎨 Code Style

- Follow **PSR-12** coding standards for PHP
- Follow **Prettier** style for JavaScript/React code (optional)
- Run `php artisan test` before pushing your PR
- Format your code cleanly before committing

---

## 🛡️ Testing

- All new features should include tests (Feature, Unit, etc.)
- Existing tests should **not break** after your change
- Run locally:

```bash
php artisan test
npm run build
