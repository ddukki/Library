# Compiled Frontend Assets Excluded from Version Control

## Status

Approved

## Context and Problem Statement

The legacy Laravel Mix build pipeline (Vue 2 + jQuery + Bootstrap 4) outputs compiled assets to `public/css/` and `public/js/`. These files are large binaries generated from source, and committing them creates merge conflicts, bloats the repository, and obscures code reviews.

The Mix build also requires `NODE_OPTIONS=--openssl-legacy-provider` to work with Node.js 22+, adding a fragile external dependency to the checkout workflow.

## Decision Drivers

* Keep the repository lean (no generated files)
* Avoid merge conflicts in compiled assets
* Build must be reproducible from source

## Considered Options

* **Keep compiled assets in repo** — traditional Laravel approach
* **Remove from version control, document build steps** — build on checkout
* **CI-generated assets** — build during CI deployment

## Decision Outcome

Chosen option: "Remove from version control, document build steps", because it keeps the repo focused on source code and ensures every commit is buildable from source. The `docs/BUILD.md` file documents the exact steps.

### Consequences

* Good, because diffs no longer include binary asset changes
* Good, because merge conflicts from compiled assets are eliminated
* Good, because `git clone` is faster without generated bloat
* Bad, because a fresh checkout requires `npm install` and the Mix build before the app is functional
* Bad, because the `--openssl-legacy-provider` flag is an additional required dependency

### Confirmation

A fresh checkout followed by the steps in `docs/BUILD.md` produces a fully functional application. The `public/css` and `public/js` directories are listed in `.gitignore`.

## Pros and Cons of the Options

### Keep compiled assets in repo

* Good, because checkout is immediately runnable
* Bad, because every asset change creates large binary diffs
* Bad, because merge conflicts in `.js` and `.css` files are unresolvable
