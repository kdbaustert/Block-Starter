# Block Starter

A starter block theme for WordPress 6.7 and later. Nearly all of its design lives in `theme.json`. Templates, template parts and patterns are block markup, and the Site Editor can change all of them. There are no classic PHP templates.

The PHP that remains sits in `src/` under the `BlockStarter` namespace, loaded by Composer. `functions.php` only loads the autoloader and boots the theme.

## Requirements

- WordPress 6.7 or later
- PHP 8.1 or later
- Composer

## Install

```sh
cd wp-content/themes
git clone git@github.com:kdbaustert/Block-Starter.git block-starter
cd block-starter
composer install --no-dev
```

Then activate **Block Starter** under Appearance → Themes. If `vendor/autoload.php` is missing, the theme shows an admin notice and loads nothing else.

To build a zip for a site without Composer, run `composer install --no-dev` first and include `vendor/` in the zip.

### Run it with wp-env

With Docker running:

```sh
composer install
npx wp-env start
```

The site runs at http://localhost:8888 (user `admin`, password `password`), with the theme already active. `npx wp-env stop` shuts it down.

## What's in it

```
theme.json          Palette, fonts, type and spacing scales, element and block styles
templates/          index, single, page, archive, search, 404, page-wide, page-no-title
parts/              header, header-minimal, footer, post-meta
patterns/           Block patterns (PHP so their strings can be translated and escaped)
styles/             Full style variations (Ink, Sage)
styles/colors/      Color-only variations (Harbor, Clay)
styles/typography/  Typography-only variations (All serif, All sans)
styles/sections/    Section styles for Group and Columns blocks
src/                Namespaced PHP, PSR-4 autoloaded
tests/              PHPUnit unit and integration suites
bin/install-wp.sh   Throwaway WordPress on SQLite for the integration tests
```

### Design tokens

Every color, font size, font family and spacing value is a preset in `theme.json`. Templates, patterns and variations refer to them as `var:preset|color|accent` and never use raw values, so a style variation only has to swap the presets. The unit tests fail if anything refers to a preset that doesn't exist.

The palette slugs are `base`, `base-2`, `contrast`, `contrast-2`, `accent` and `accent-2`. Each variation that changes colors redefines all six.

Fonts are system stacks, so there are no font files to license or load.

### Patterns

All theme patterns sit in a **Block Starter** category in the inserter.

| Pattern | Notes |
| --- | --- |
| Hero | Headline, intro and two buttons |
| Three features | Columns using the `section-muted` style |
| Testimonial | A single quote |
| Call to action | Full-width band using the `section-dark` style |
| Posts list | Query loop used by index, archive and search |
| Landing page | Offered as starter content when you create a new page |
| 404 content | Hidden from the inserter, used by the 404 template |

### Section styles

**Muted**, **Accent** and **Dark** (`section-muted`, `section-accent`, `section-dark` in markup) show up in the Styles panel of Group, Columns and Column blocks. Each one sets the background, the text color, and colors for links, headings and buttons inside the section.

### Announcement bar

Settings → General has an **Announcement** field. Whatever you put there shows in a bar above the header on every page, and the bar disappears when the field is empty. Links, bold and italics are allowed.

The header fills the bar with a block binding (`block-starter/announcement`), and the post meta part shows reading time the same way (`block-starter/reading-time`).

## Security

Input is sanitized when it's saved and escaped again when it's printed.

- The announcement option is registered with `register_setting()` and a sanitize callback, so the settings form, the REST API and `update_option()` all pass through `wp_kses()` with an allowlist of `a[href]`, `strong` and `em`. `wp_kses()` also strips `javascript:` links and event handlers.
- Output uses `esc_html()`, `esc_attr()`, `esc_textarea()`, `esc_url()` or `wp_kses()`, depending on context. Pattern strings go through `esc_html_e()` and `esc_attr__()`.
- PHPCS with the WordPress standard flags unescaped output, and the integration tests check sanitizing and escaping directly.

## Development

```sh
composer install
composer lint          # PHPCS, WordPress Coding Standards
composer lint:fix      # PHPCBF
composer test:unit     # No WordPress needed
```

`phpcs.xml.dist` turns off three WPCS rules, and a comment next to each says why. The `class-foo.php` file naming rule doesn't apply to `src/` and `tests/`, which follow PSR-4. Test methods don't need docblocks or snake_case names, because PHPUnit expects names like `setUp()`.

### Integration tests

The integration suite loads a real WordPress install with this theme active. Without one it skips itself. To set one up on SQLite, with no MySQL or Docker:

```sh
bin/install-wp.sh /tmp/block-starter-wp
WP_PATH=/tmp/block-starter-wp composer test
```

The script downloads WordPress, WP-CLI and the SQLite Database Integration plugin, installs the site and symlinks the theme into it. Set `WP_VERSION=6.7` to test an older release.

With wp-env running, `composer test:wp-env` runs the suite inside its tests container instead.

### CI

`.github/workflows/ci.yml` runs on pushes to `main` and on pull requests:

- PHPCS, with violations shown inline on the pull request
- PHPUnit on PHP 8.1 with WordPress 6.7, and on PHP 8.3 with the latest WordPress. The integration step uses `--fail-on-skipped`, so it can't pass without WordPress loading.

## License

GPL-2.0-or-later
