# Proof / Evidence Checklist (Mini LIMS)

Use this file to collect submission proof.

## A) Required screenshots (UI proof)

Take these screenshots from browser (`http://54.221.66.77/mini-lims`):

1. `01-dashboard.png`
- Show dashboard with quick buttons and status cards.

2. `02-add-sample-form.png`
- Show Add Sample page before submit.

3. `03-sample-added-success.png`
- Show success alert after saving sample.

4. `04-view-samples-table.png`
- Show sample visible in table with status.

5. `05-add-test-form.png`
- Show Add Test page with sample selected.

6. `06-add-result-form.png`
- Show Add Result page with values filled.

7. `07-report-completed.png`
- Show Reports page with result and completed status.

8. `08-search-filter.png`
- Show search/filter in View Samples working.

## B) Server proof (terminal outputs)

Run on EC2 and capture screenshot or copy output:

```bash
sudo systemctl status apache2 --no-pager
sudo systemctl status mysql --no-pager
php -v
mysql --version
```

Save as:
- `09-apache-mysql-running.png`
- `10-php-mysql-version.png`

## C) Database proof (data exists)

Run:

```bash
mysql -u mini_lims_user -p -e "USE mini_lims; SHOW TABLES; SELECT COUNT(*) AS sample_count FROM samples; SELECT COUNT(*) AS test_count FROM tests; SELECT COUNT(*) AS result_count FROM results;"
```

Save as:
- `11-db-tables-and-counts.png`

## D) HTTP proof (site reachable)

Run from local machine:

```bash
curl -I http://54.221.66.77/mini-lims/
```

Expected: `HTTP/1.1 200 OK`

Save as:
- `12-http-200-proof.png`

## E) GitHub proof

Take screenshot of repository:
- Files list visible
- Latest commit visible

Save as:
- `13-github-repo.png`

## F) Suggested folder for submission

```text
submission/
└── screenshots/
    ├── 01-dashboard.png
    ├── 02-add-sample-form.png
    ├── 03-sample-added-success.png
    ├── 04-view-samples-table.png
    ├── 05-add-test-form.png
    ├── 06-add-result-form.png
    ├── 07-report-completed.png
    ├── 08-search-filter.png
    ├── 09-apache-mysql-running.png
    ├── 10-php-mysql-version.png
    ├── 11-db-tables-and-counts.png
    ├── 12-http-200-proof.png
    └── 13-github-repo.png
```

