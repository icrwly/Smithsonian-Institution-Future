# Upstream Configuration

This directory contains the shared Composer dependencies for the
**Smithsonian Institution Drupal 11 Custom Upstream**.

## Adding shared packages

Add packages that **every** child site should inherit to `composer.json`
in this directory — **not** to the root `composer.json`. The root
`composer.json` is reserved for per-site additions.

```bash
# From the repo root, add a package to the upstream:
composer require --no-update <package/name>:<version> \
  --working-dir=upstream-configuration
git add upstream-configuration/composer.json
git commit -m "Add <package> to upstream"
```

After merging this change into the upstream's main branch, each child site
picks it up the next time it applies upstream updates via Pantheon's dashboard
or `terminus upstream:updates:apply`.

## Site-specific packages

Packages used by only one site (private GitHub modules, site-specific
integrations) belong in that site's **root `composer.json`**, not here.

Examples of site-specific packages:
- `simplesamlphp/simplesamlphp` — Ocean Portal only
- `drupal/ldap` — Natural History only
- `smithsonian/si_verint` — National Zoo only (private GitHub)
