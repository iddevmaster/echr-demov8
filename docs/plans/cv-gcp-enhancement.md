# CV-GCP Enhancement (Requirements 4–14) — Implementation Plan

## Context

The ECHR (Ethics Committee on Human Research) system needs a set of related enhancements around researcher **CV** and **GCP / training certificates**. Today:

- Training records (`person_training`) are free-text course names with a start/end date and an attached file. There is **no training-type master data and no expiry date**.
- CV is a single uploaded file (`person.cv_file`) with no "last updated" date and no expiry/freshness concept.
- Researchers are told (via a UI note) to upload pre-signed documents; there is no electronic-signature / stamp / audit-trail flow.
- When selecting researchers into a submission there is no qualification check; the project detail view shows no CV/GCP expiry status.

The goal: make training types admin-configurable with validity periods, auto-compute expiry dates, warn researchers before training expires, replace "upload pre-signed file" with an in-system e-signature + audit trail, and surface qualification/expiry status when selecting researchers and viewing a project.

### Decisions confirmed with the user
1. **Re-Auth before e-sign**: support **both password re-auth and OTP**, but **only in the edit/profile case**. **During registration, no re-auth is required** (the user just set their password in the same flow).
2. **.docx CV/Certificate stamping**: **auto-convert .docx → PDF**, then stamp. Reuse the existing `CreateDocx::transformDocument(..., 'libreoffice', ...)` (phpdocx) already used in `SubmissionController` / `SiteController`.
3. **Qualification failures (items 12–13)**: **warn only** (show a symbol/tooltip), do not block selection or submission.
4. **Training-type validity**: seed all 5 types at **5 years**, Admin-editable afterward.
5. **Project-type training matrix (NEW)**: the required-training rules are keyed by **`submission_type`**, stored as **Admin-editable master data** (a mapping table + a `category` grouping on `training_type`), and qualification failures remain **warn-only**.

---

## ✅ Implementation Summary (Phases 1–6 — DONE & COMMITTED on branch `cv-gcp`)

All six phases below were implemented, verified in the Docker dev environment (echr container, MySQL `db` container), and committed one-per-phase. Migrations are timestamped `m260620_1000NN_*`.

| Phase | Commit | What shipped | Reqs |
|------|--------|--------------|------|
| 1 | `057f1f57` | `training_type` master (CRUD copied from ProjectType, seed 5 types @ 5 yrs, linked in `site/master-list`); schema migrations (`person_training.training_type_id/expire_date`, `person.cv_updated_at/cv_signed_at/cv_signed_by`, `person_document_audit` table, `notification_history` +`person_training_id/notify_type/notify_days`, 3 Settings); `models/PersonTraining` (relation, `computeExpireDate()` in `beforeSave`, query `expiringInDays/expired`); `models/PersonDocumentAudit`(+Query); `models/Person` helpers (`getIsCvFresh/getCvQualificationFail/getHasExpiredTraining/getQualificationWarning/getCvFreshnessStatusHtml/getTrainingExpiryStatusHtml`); `Setting` consts + `getValueOrDefault()` | 4 |
| 2 | `4cf7f7bd` | `views/person-training/_form.php` TrainingType Select2 + read-only auto-computed `expire_date` (JS on type/date change); training type + expiry (green/red) columns across all `person-training` grids + detail view | 5 |
| 3 | `b27523b2` | `commands/AlertController::actionCheck` training-expiry block (periods from `Setting::TRAINING_EXPIRE_ALERT_PERIODS`=`60,30`; only researchers on an active project; once-only via `notification_history`); `EmailQueue::TYPE_TRAINING_EXPIRE_REMINDER` + `sendMail` branch + `mail/training-expire-reminder.php`; `NotificationHistory` const + query helpers | 6 |
| 4 | `80c2d480` | `components/DocumentStamper.php` (PDF FPDI overlay via mPDF; `.docx`→PDF via `CreateDocx::transformDocument` then stamp; cover-sheet fallback for images); registration certify checkbox + `cv_updated_at` + stamp choice → stamp & audit on STEP4 (no re-auth); profile `PersonController::actionEsign` modal w/ password or OTP re-auth (`actionEsignOtp`, `ESIGN_OTP_ENABLE`); e-sign buttons on CV field + each training row | 7–11 |
| 5 | `0dc5cf67` | `person/search` dropdown appends `getQualificationWarning()` icon; `views/project-researcher/_columns.php` "คุณสมบัติ" column (warn icon vs green check, as-of submission date) | 12–13 |
| 6 | `6675a3a4` | `views/project-researcher/_columns-researcher.php` adds "วันหมดอายุ CV" + "วันหมดอายุการอบรม (GCP)" columns (green valid / red expired / grey no-data) | 14 |

**Settings created (Admin-editable at ตั้งค่าโปรแกรม):** `CV_FRESHNESS_MONTHS=6`, `TRAINING_EXPIRE_ALERT_PERIODS=60,30`, `ESIGN_OTP_ENABLE=0`.

**Verification performed:** all 6 migrations applied cleanly; every changed/added PHP file passes `php -l`; runtime-tested expiry computation (2024-06-20 +5y → 2029-06-20), once-only alert suppression, real PDF stamping (valid 931 KB output + audit row), qualification helpers (red/green/grey labels), and mail-body rendering.

> **NEW WORK BELOW (Phase 7)** extends the qualification/expiry logic so it is scoped to the training types **required by the project's submission type**, per the Admin-editable matrix in the requirement image.

---

## Phase 1 — Data model & master data

### Item 4 — `training_type` master table + admin CRUD
Copy the **`ProjectType`** pattern (model / query / search / controller / views).

- **Migration** `migrations/mYYMMDD_HHMMSS_create_training_type.php`: table `training_type` = `id`, `name varchar(255)`, `validity_years int NULL` (null = never expires), `deleted smallint default 0`, `created_by/at`, `updated_by/at`. Seed the 5 types (all `validity_years = 5`) in the same migration via `batchInsert`:
  - จริยธรรมการวิจัยในมนุษย์พื้นฐาน (Human Subject Protection)
  - จริยธรรมการวิจัยในมนุษย์สำหรับการวิจัยชีวการแพทย์ (Biomedical Research Ethics)
  - การปฏิบัติการวิจัยทางคลินิกที่ดี (GCP)
  - จริยธรรมการวิจัยในมนุษย์ด้านพฤติกรรมศาสตร์และสังคมศาสตร์ (Social Science and Behavioral Science Research)
  - การอบรมมาตรฐานการวิจัยทางคลินิกที่ดีเกี่ยวกับเครื่องมือแพทย์ (Medical device)
- **Model** `models/TrainingType.php` (copy `models/ProjectType.php`): `tableName()` → `training_type`; drop ProjectType-specific relations; add `validity_years`; `getPersonTrainings()` hasMany; keep Blameable + Timestamp; `find()` → `TrainingTypeQuery`.
- **Query** `models/TrainingTypeQuery.php` (copy `models/ProjectTypeQuery.php`): `isDeleted()`, `active()`.
- **Search** `models/TrainingTypeSearch.php` (copy `models/ProjectTypeSearch.php`).
- **Controller** `controllers/TrainingTypeController.php` (copy `controllers/ProjectTypeController.php`): rename, pjax id `#crud-datatable-training-type-pjax`, Thai titles "ประเภทการอบรม".
- **Views** `views/training-type/{index,_form,_columns,create,update}.php` (copy `views/project-type/*`): in `_form.php` replace ProjectType-specific fields with `name` + `validity_years`; `_columns.php` shows `name`, `validity_years`.
- **RBAC + menu**: permission migration (pattern `m190213_102355_update_permission.php`) granting `training-type.*` to ADMIN; add left-menu entry near project-type.

### Item 5 (schema) — `person_training` gets type + expiry
- **Migration** `migrations/mYYMMDD_HHMMSS_add_field_person_training.php`: ALTER `person_training` ADD `training_type_id int NULL`, `expire_date date NULL`.
- **Model** `models/PersonTraining.php`:
  - Add `training_type_id` (integer rule) and `expire_date` (safe/date) to `rules()`; labels "ประเภทการอบรม", "วันหมดอายุ".
  - `getTrainingType()` hasOne.
  - `computeExpireDate()` and call it in `beforeSave()` so `expire_date = start_date + trainingType.validity_years` is **always recomputed server-side** (covers both registration and admin-add; client JS below is UX only). If `validity_years` is null → `expire_date = null`.
- **Query** `models/PersonTrainingQuery.php`: add `expiringInDays($d)` (`DATEDIFF(expire_date, CURDATE()) = $d`) and `expired($asOfDate)` (`expire_date <= $asOfDate`).

### Item 10 (schema) — Person CV last-updated date + signature mirror
- **Migration** `migrations/mYYMMDD_HHMMSS_add_field_person_cv.php`: ALTER `person` ADD `cv_updated_at date NULL`, `cv_signed_at datetime NULL`, `cv_signed_by int NULL` (denormalized latest CV-signature info for cheap item-14 display).

### Items 7–9 (schema) — Document e-signature audit trail
- **Migration** `migrations/mYYMMDD_HHMMSS_create_person_document_audit.php`: table `person_document_audit`:
  `id`, `person_id`, `doc_type smallint` (1=CV, 2=TRAINING), `ref_id int NULL` (`person_training.id` for training), `file_name varchar(255)`, `certify_confirmed smallint`, `certify_statement text` (snapshot of the wording shown), `signed smallint default 0`, `signer_person_id int NULL`, `signer_name varchar(255) NULL`, `signed_at datetime NULL`, `auth_method smallint NULL` (1=PASSWORD, 2=OTP), `stamp_applied smallint default 0`, `stamp_type smallint NULL` (1=PDF_OVERLAY, 2=COVERSHEET), `ip_address varchar(64) NULL`, `user_agent varchar(255) NULL`, `deleted smallint default 0`, audit cols.
- **Model** `models/PersonDocumentAudit.php` (Blameable + Timestamp). Constants `DOC_TYPE_CV=1`, `DOC_TYPE_TRAINING=2`, `AUTH_PASSWORD=1`, `AUTH_OTP=2`, `STAMP_PDF_OVERLAY=1`, `STAMP_COVERSHEET=2`. Statics:
  - `recordSigned($personId, $docType, $refId, $fileName, $statement, $confirmed, $signerPerson, $authMethod)` — creates a fully-signed audit row (one timestamp, signer name snapshot, IP/UA).
  - `find()` → `PersonDocumentAuditQuery` with `isDeleted/person/docType`.

### New Setting constants (Item 10 / 6 thresholds, admin-editable)
Add to `models/Setting.php` constants **and** `getAlertNames()` (so they appear in the existing generic Setting admin CRUD), and seed via migration `migrations/mYYMMDD_HHMMSS_insert_settings_cvgcp.php`:
- `CV_FRESHNESS_MONTHS` (default `6`) — items 10 / 13.1.
- `TRAINING_EXPIRE_ALERT_PERIODS` (default `'60,30'`) — item 6.
- `ESIGN_OTP_ENABLE` (default `0`) — toggles OTP vs password in the edit-case e-sign (decision 1).

Use `Setting::getValue($key) ?: <default>` at call sites (returns null if missing).

---

## Phase 2 — Training entry UI: type select + auto-expiry (Item 5 UI)

Both registration and admin add/update use **`views/person-training/_form.php`**.

- Add a `training_type_id` kartik Select2 above `start_date`: `ArrayHelper::map(TrainingType::find()->isDeleted(false)->all(), 'id', 'name')`.
- Add a **read-only** `expire_date` display field.
- Extend the existing bottom `registerJs` block: emit a `{typeId: validityYears}` JS map; on change of `training_type_id` or `start_date`, add N years to the start date and write into the expiry display (instant feedback only — server `beforeSave` is authoritative).
- No controller change: `PersonTrainingController::actionCreate/Update` already `load($request->post())`.
- Add "ประเภทการอบรม" (`trainingType.name`) and "วันหมดอายุ" (`expire_date`, rendered red if expired via the item-14 helper) to `views/person-training/{_columns.php, index.php, index-register.php, show.php, view.php}`.

---

## Phase 3 — Training expiry warnings (Item 6)

Warn **60 & 30 days** before any training expires, **only** for researchers attached via `project_researcher` to an **active** project (`project.is_active = 1`), **once** per (training, threshold).

- **Once-only tracking** — reuse `notification_history`. **Migration** ALTER `notification_history` ADD `person_training_id int NULL`, `notify_type smallint NULL`, `notify_days int NULL`. (Do **not** reuse `alert_peroid_id` — it has an FK to `alert_peroid`.) Uniqueness = (`person_training_id`, `notify_type`, `notify_days`). Add `NotificationHistory::TYPE_TRAINING_EXPIRE = 1` and query helpers.
- **Cron hook** — `commands/AlertController.php::actionCheck()`, add a block after the existing `$periods` loop (~lines 140–197), mirroring its `Alert` + `EmailQueue::addQueueNoExec` style:
  - `$expirePeriods = explode(',', Setting::getValue(Setting::TRAINING_EXPIRE_ALERT_PERIODS) ?: '60,30')`.
  - For each `$d`: select `PersonTraining` where `expire_date` is exactly `$d` days out, `EXISTS` an active-project link (`project_researcher` join `project` is_active=1), and `NOT EXISTS` a matching `notification_history` row.
  - For each hit: create `Alert` (if `person->user` set), `EmailQueue::addQueueNoExec(EmailQueue::TYPE_TRAINING_EXPIRE_REMINDER, $t->id)`, and insert the `notification_history` row.
  - `actionCheck()` already ends with `EmailQueue::execSendMailCmd()`; cron already runs `php yii alert/check` — no scheduling change.
- **Email** — `models/EmailQueue.php`: add `TYPE_TRAINING_EXPIRE_REMINDER` and a `sendMail()` branch that loads `PersonTraining::findOne($this->model_id)` and composes a new `mail/training-expire-reminder.php` (copy `mail/progress-reminder.php`); guard against deleted training / missing email.
- **Caveat to verify in implementation**: the existing `Alert` rows in `actionCheck()` all set `submission_id`. Training-expiry is not tied to a submission — confirm whether `alert.submission_id` is `NOT NULL`; if so, add a migration to make it nullable, otherwise fall back to email-only for this alert.

---

## Phase 4 — E-signature / stamping + CV freshness (Items 7, 8, 9, 10, 11)

### 4a. Registration — certify + stamp choice, **no re-auth** (decision 1)
`views/person/_contact-info.php` (CV block ~lines 262–307) and the training panel:
- Add `cv_updated_at` DateControl "วันที่ปรับปรุงประวัติล่าสุด" (item 10).
- Add a **mandatory certification checkbox** (item 9): new public attr `Person::$accept_cv_certify`, `required` + compare-to-1 on `SCENARIO_REGISTER` (mirror existing `accept_policy`, `Person.php` line 111). Statement text kept in a constant for the audit snapshot.
- Add a **stamp choice checkbox** (item 11): `Person::$cv_apply_stamp` (default checked).
- Keep the existing raw upload to `person/upload-cv` → `web/tmp/`.
- **On final STEP4 submit** (`PersonController::actionRegister()` `next-step` branch, ~lines 1023–1095, where CV is `rename`d tmp→uploads): after moving the file, if `cv_apply_stamp` → stamp (4c); then `PersonDocumentAudit::recordSigned(... AUTH_PASSWORD ...)` for the CV and each training file with one timestamp + the registering person as signer. Set `person.cv_signed_at/by`. **No password prompt here** — registration already authenticated the user.

### 4b. Profile-edit — explicit "ลงนามอิเล็กทรอนิกส์" with re-auth (decision 1)
New `PersonController::actionEsign($personId, $docType, $refId = null)`, modeled on the existing `actionSignature()` (lines 236–330) GET-render-modal / POST-handle / `forceReload` structure:
- GET → modal with certify checkbox + stamp choice + **auth input** (password field, or OTP field when `ESIGN_OTP_ENABLE = 1`).
- POST → if password mode: `Yii::$app->user->identity->validatePassword($pwd)` (reuse `User::validatePassword`). If OTP mode: verify against a short-lived OTP (see 4d). On success → stamp (4c) + `PersonDocumentAudit::recordSigned(...)` with the correct `auth_method`; return success JSON.
- Add an "ลงนามอิเล็กทรอนิกส์" button next to CV in `views/person/_form.php` and next to each file in the training list.

### 4c. Stamping mechanics (Item 11) — new `components/DocumentStamper.php`
Reuse kartik mPDF exactly as `controllers/ReportController.php` (lines 62–97: `use kartik\mpdf\Pdf; $pdf = new Pdf([...]); $mPdf = $pdf->getApi();`, including the `fontDir`/`fontdata`/`thsarabun` block for Thai).
- **PDF source** → `stampPdf($src, $dest, $lines)`: overlay signer name + signed date + "ลงนามอิเล็กทรอนิกส์" as a footer / fixed position.
- **.docx (and other Office) source** → **convert to PDF first** (decision 2) via the existing `CreateDocx::transformDocument($docxPath, $pdfPath, 'libreoffice', ['homeFolder' => Yii::getAlias('@app')])` (already used in `SubmissionController.php:2982` and `SiteController.php:1165`), then `stampPdf` the result. The stamped PDF becomes the served file.
- **Images / unsupported** → generate a one-page attestation **cover sheet** PDF (signer name, signed date, certification statement, original filename), kept alongside the untouched original; mark audit `stamp_type = COVERSHEET`.
- Always keep the original raw file; write stamped output to a new uniqid name and update `person.cv_file` / `person_training.file` to the served filename. CV/training stay **unencrypted** (current download actions read plain; only signature images use `fileEncryptor`) — encryption is out of scope.

### 4d. OTP (edit-case only, behind `ESIGN_OTP_ENABLE`)
Minimal: generate a 6-digit code, store hash + expiry in session (or a small `otp` row), deliver via the existing `EmailQueue` to the person's email. The `actionEsign` POST verifies it. Isolate verification behind a `verifyReauth($person, $input)` helper so password and OTP share one entry point.

### 4e. CV freshness rule (Item 10)
`Person::isCvFresh($asOfDate = null)`: true if `cv_updated_at` is null (legacy → skip, per 13.1) **or** `cv_updated_at >= asOfDate − CV_FRESHNESS_MONTHS`. Default `$asOfDate` = today; submission/gridview contexts pass the submission date. Per decision 3 this only drives **warnings** (Phases 5–6), not a hard block.

---

## Phase 5 — Qualification warnings when selecting researchers (Items 12–13, warn-only)

Centralize logic on **`models/Person.php`** (single source reused by search, gridview, and project detail):
- `getCvQualificationFail($asOfDate = null)` → CV exists AND `!isCvFresh($asOfDate)` (null `cv_updated_at` → false, legacy skip; item 13.1).
- `getHasExpiredTraining($asOfDate = null)` → EXISTS a non-deleted `person_training` with `expire_date <= $asOfDate` (item 13.2).
- `getQualificationWarning($asOfDate = null)` → null, or warning-icon HTML + Thai tooltip listing reasons. Reuse the icon markup from `User::getSubmissionAlert()` (`<i class="icon wb-warning">`, ~lines 394–395) for consistency.

Wire-up:
- **Dropdown** — `PersonController::actionSearch()` (and `actionSearchConsultant`/COI variants if researchers are picked there): add a `warn` field to the result map (`$person->getQualificationWarning()`, as-of today). Update the `templateResult`/`templateSelection` JsExpressions in `views/project-researcher/_form.php` (lines 40–51) to append `person.warn`.
- **Selection gridview** — `views/project-researcher/_columns.php`: add a `format => raw` "คุณสมบัติ" column = `$model->person->getQualificationWarning(<submission date>)`. N+1 is negligible (few researchers per submission).

---

## Phase 6 — CV/GCP expiry status in project detail (Item 14)

`views/project-researcher/_columns-researcher.php` (rendered via `researcher.php` → `views/submission/project-submission-show.php`; `$submission` is in scope). After the existing CV column (~lines 33–49) add two `format => raw` columns:
1. **CV** — show `person.cv_updated_at`; **green** `label-success` if fresh as-of the submission date, **red** `label-danger` if stale, grey "ไม่มีข้อมูล" if legacy null.
2. **GCP/Training** — per training-type chip (`trainingType.name` + `expire_date`): **green** if not expired vs submission date, **red** if expired.

Implement via reusable `Person::getCvFreshnessStatusHtml($asOfDate)` and `Person::getTrainingExpiryStatusHtml($asOfDate)` (shared with Phase 5). As-of date = the submission's submitted date (fallback `created_at` — confirm exact field during implementation).

---

## Phase 7 — Training requirement matrix per submission type (NEW)

### Why
Today the qualification/expiry checks (Phases 3, 5, 6) flag **any** expired training. The requirement (image) is stricter and **scoped to the project's submission type**: each submission type requires a specific set of training documents. From the image, requirements fall into two buckets:

- **GCP** (`การปฏิบัติการวิจัยทางคลินิกที่ดี (GCP)`) — per type either **REQUIRED** (`ต้องแนบ`) or **NA**.
- **Ethics group** (`Human Subject Protection` / `Biomedical` / `Social Science & Behavioral`) — **at least one of the group** (`ต้องแนบอย่างใดอย่างหนึ่ง`).

Example mapping from the image (must be Admin-editable):

| Submission type | GCP | Ethics group |
|---|---|---|
| โครงการใหม่ทางคลินิก (id 1) | ต้องแนบ | อย่างใดอย่างหนึ่ง |
| โครงการใหม่ทางสังคม (id 2) | NA | อย่างใดอย่างหนึ่ง |
| เข้าข่ายขอยกเว้นการพิจารณา (id 3) | NA | อย่างใดอย่างหนึ่ง |
| เข้าข่ายการพิจารณาแบบเร็ว (id 4) | ต้องแนบ | อย่างใดอย่างหนึ่ง |

A training is only a problem for a given submission **if that submission type requires its category**. "Required & missing or expired" → warn (warn-only, decision 5).

### Decisions confirmed with the user
- Matrix is keyed by **`submission_type`** (ids 1=clinical, 2=social, 3=exemption, 4=expedited, plus others). There is **no separate project-category field** — `submission_type_id` is the category (`models/SubmissionType.php`, `Submission::getSubmissionType()`).
- Stored as **master data**: a `category` grouping on `training_type` + a `submission_type_training_requirement` mapping table — both Admin-editable.
- Failures remain **warn-only**.

### 7a. Schema — group training types + per-type requirement rules
- **Migration** `migrations/m260620_1001NN_alter_training_type_category.php`: ALTER `training_type` ADD `category smallint NULL` (`1=GCP`, `2=ETHICS`, `null=other`). Seed existing rows: id 3 → GCP(1); ids 1,2,4 → ETHICS(2); id 5 (Medical device) → leave null/other (or GCP per committee — confirm). Add `TrainingType::CATEGORY_GCP=1`, `CATEGORY_ETHICS=2` consts + a label map; expose `category` in `views/training-type/_form.php` (dropdown) and `_columns.php`.
- **Migration** `migrations/m260620_1001NN_create_submission_type_training_requirement.php`: table `submission_type_training_requirement`:
  `id`, `submission_type_id int`, `category smallint` (1=GCP, 2=ETHICS), `rule smallint` (`0=NA`, `1=REQUIRED`, `2=ANY_OF` "อย่างใดอย่างหนึ่ง"), `deleted smallint default 0`, audit cols. Unique (`submission_type_id`, `category`). Seed the four rows from the image table:
  - (1, GCP, REQUIRED), (1, ETHICS, ANY_OF)
  - (2, GCP, NA), (2, ETHICS, ANY_OF)
  - (3, GCP, NA), (3, ETHICS, ANY_OF)
  - (4, GCP, REQUIRED), (4, ETHICS, ANY_OF)
  Any submission type without rows = no training requirement (no warning).

### 7b. Models
- **`models/SubmissionTypeTrainingRequirement.php`** (+ Query + Search, copy the TrainingType/ProjectType pattern). Consts `RULE_NA=0`, `RULE_REQUIRED=1`, `RULE_ANY_OF=2`; `getSubmissionType()`, `getCategoryLabel()`, `getRuleLabel()`.
- **`models/SubmissionType.php`**: `getTrainingRequirements()` hasMany; helper `getRequiredTrainingCategories()` → array like `[1 => RULE_REQUIRED, 2 => RULE_ANY_OF]` (rows where rule != NA).
- **`models/TrainingTypeQuery.php`**: add `category($cat)` filter.

### 7c. Person qualification — make it submission-type-aware
Add to **`models/Person.php`** a single evaluator reused everywhere:
- `getTrainingRequirementStatus($submissionType, $asOfDate = null)` → returns a structured result per required category: for each category the SubmissionType requires (`RULE_REQUIRED` → that category's training must exist & be valid; `RULE_ANY_OF` → at least one valid training in the group), compute `ok` / `missing` / `expired`. Implementation: load the person's non-deleted `person_training` joined to `training_type.category`, group by category, compare each `expire_date` to `$asOfDate`.
- Refactor `getQualificationWarning($asOfDate, $submissionType = null)` and `getTrainingExpiryStatusHtml($asOfDate, $submissionType = null)` to take an **optional** `$submissionType`:
  - When `$submissionType` is provided → evaluate against its required categories only (missing-required or expired-required → warn; non-required categories ignored).
  - When null (legacy callers / generic context) → keep the current "any expired training" behavior (backward compatible).
- `getCvQualificationFail` is unchanged (CV freshness is not submission-type specific).

### 7d. Wire submission type into the existing call sites
- `views/project-researcher/_columns.php` (Phase 5) and `views/project-researcher/_columns-researcher.php` (Phase 6) already have `$submission` in scope → pass `$submission->submissionType` into `getQualificationWarning(...)` / `getTrainingExpiryStatusHtml(...)`.
- `PersonController::actionSearch()` (the researcher-selection dropdown) receives `$submissionId` → load the submission, pass its `submissionType` to `getQualificationWarning()`. (Items 12–13 explicitly tie the check to the project being submitted.)
- `commands/AlertController::actionCheck()` (Phase 3) expiry alerts: optionally scope to required categories — i.e. only warn about an expiring training if **some active project's submission type requires that training's category**. Recommended: keep Phase 3 as-is for now (warn on any expiring training the researcher holds) to avoid missing renewals, OR add the category filter if the committee wants strictly-required-only reminders (**confirm during implementation**; default = keep broad).

### 7e. Admin master-data UI
- Add `submission-type-training-requirement` CRUD (controller + views, copy TrainingType pattern), linked in `controllers/SiteController.php::actionMasterList()` under the `submission` group (near `project-type/index` and the new `training-type/index`), Thai title e.g. "เกณฑ์การอบรมตามประเภทโครงการ".
- A simple grid (submission type, category, rule) suffices; a matrix-style editor is optional polish.

---

## Phase 8 — Validation hardening (NEW)

Two small validation additions on top of the shipped CV-GCP work. No schema change.

### 8a. CV last-updated date must not be in the future
`person.cv_updated_at` (item 10 field) is currently only in a generic `safe` rule (`models/Person.php` line 121). Add a date validator that rejects future dates, applied in **both** registration and profile-edit (so make it non-scenario-scoped):
- In `models/Person.php` `rules()`, add:
  `[['cv_updated_at'], 'date', 'format' => 'php:Y-m-d', 'max' => date('Y-m-d'), 'tooBig' => Yii::t('app', 'วันที่ปรับปรุงประวัติ (CV) ต้องไม่เป็นวันในอนาคต')]`
  (the DateControl save format is `php:Y-m-d`, per `config/web.php` `datecontrol.saveSettings`). Keep `cv_updated_at` in the `safe` rule too, or rely on the `date` rule to make it safe — simplest: leave the existing `safe` entry and add the `date` rule (the `date` validator also marks it safe; remove it from the `safe` list to avoid redundancy, optional).
- Optional UX: set `pluginOptions.endDate => '0d'` on the `cv_updated_at` DateControl widgets in `views/person/_contact-info.php` and `views/person/_form.php` so the datepicker can't pick future days client-side. Server rule remains the source of truth.

### 8b. Training type required when adding a training record
`person_training.training_type_id` is currently optional (`models/PersonTraining.php` line 56, integer only). Make it required:
- In `models/PersonTraining.php` `rules()`, add `training_type_id` to the `required` rule (line 55) → `[['person_id', 'name_thai_course', 'training_type_id'], 'required']`, with a Thai message e.g. "กรุณาเลือกประเภทการอบรม".
- The Select2 in `views/person-training/_form.php` (line 31) already exists; with the model rule it will show the required error. Consider removing `allowClear` or keeping it (server rule enforces). The `expire_date` auto-calc already depends on a chosen type, so this also improves data quality.
- Note: existing rows with null `training_type_id` are unaffected (validation only runs on create/update); no data migration needed.

### Verification (Phase 8)
1. Profile edit & registration: set `cv_updated_at` to tomorrow → save → expect error "ต้องไม่เป็นวันในอนาคต"; set to today/past → passes.
2. Add training without selecting a type → expect "กรุณาเลือกประเภทการอบรม"; with a type → saves and `expire_date` auto-computes.
3. `php -l` both models; smoke-test the person-training create modal and profile form load (HTTP 200/302, no fatal).

---

## Migration run order

### Phases 1–6 (DONE — applied as `m260620_100001`…`m260620_100006`)
1. `m260620_100001_create_training_type` (+ seed 5 types @ 5 years)
2. `m260620_100002_alter_person_training` (`training_type_id`, `expire_date`)
3. `m260620_100003_alter_person_cv` (`cv_updated_at`, `cv_signed_at`, `cv_signed_by`)
4. `m260620_100004_create_person_document_audit`
5. `m260620_100005_alter_notification_history` (`person_training_id`, `notify_type`, `notify_days`)
6. `m260620_100006_insert_settings_cvgcp` (`CV_FRESHNESS_MONTHS=6`, `TRAINING_EXPIRE_ALERT_PERIODS='60,30'`, `ESIGN_OTP_ENABLE=0`)

> Note: RBAC migration was **not needed** — ADMIN logs in as superadmin (`ผู้ดูแลระบบ`) and `RbacUser::can()` returns true for all; `alert.submission_id` was already nullable, so no alter needed.

### Phase 7 (NEW)
7. `alter_training_type_category` (`category smallint`; seed id3→GCP, ids1,2,4→ETHICS)
8. `create_submission_type_training_requirement` (+ seed the 4 matrix rows from the image)

## Critical files
- `controllers/PersonController.php` — registration STEP4 (certify/stamp, no re-auth), new `actionEsign` (re-auth + stamp, edit case), `person/search` warn flag.
- `models/PersonTraining.php` — `training_type_id`, `expire_date`, server-side `computeExpireDate()`, query helpers.
- `models/Person.php` — centralized qualification + status helpers (Items 12–14), `cv_updated_at`, certify/stamp attrs.
- `commands/AlertController.php` — 60/30-day training-expiry warnings, once-only via `notification_history`.
- `components/DocumentStamper.php` (new) — mPDF stamp + LibreOffice docx→pdf (reuses `CreateDocx::transformDocument`).
- `views/person-training/_form.php` — training-type select + auto-expiry.
- `views/project-researcher/_columns.php` & `_columns-researcher.php` — qualification warning (12–13) and CV/GCP expiry status (14).
- New master-data set: `models/TrainingType*.php`, `controllers/TrainingTypeController.php`, `views/training-type/*` (copy ProjectType).
- New audit model: `models/PersonDocumentAudit.php`.

### Phase 7 critical files
- `models/Person.php` — submission-type-aware `getTrainingRequirementStatus()`; `getQualificationWarning()` / `getTrainingExpiryStatusHtml()` gain an optional `$submissionType` arg (backward compatible).
- `models/SubmissionType.php` — `getTrainingRequirements()` + `getRequiredTrainingCategories()`.
- `models/TrainingType.php` / `TrainingTypeQuery.php` — `category` field/consts + `category()` query filter; expose in `views/training-type/_form.php` & `_columns.php`.
- New: `models/SubmissionTypeTrainingRequirement*.php`, `controllers/SubmissionTypeTrainingRequirementController.php`, `views/submission-type-training-requirement/*` (copy TrainingType pattern); link in `controllers/SiteController.php::actionMasterList()`.
- Call-site updates: `views/project-researcher/_columns.php`, `views/project-researcher/_columns-researcher.php`, `controllers/PersonController.php::actionSearch()` — pass the submission's `submissionType`.
- Migrations: `alter_training_type_category`, `create_submission_type_training_requirement`.

## Verification
1. **Run migrations**: `php yii migrate` (in the dev container / Docker). Confirm 5 training types seeded with `validity_years = 5` and the 3 new settings exist.
2. **Master data (item 4/5)**: as Admin, open ประเภทการอบรม CRUD, edit a validity year. Add a training record in registration → pick a type + date → expiry auto-fills; save and confirm `expire_date` in DB matches `start_date + validity_years` (server recompute).
3. **Notifications (item 6)**: seed a `person_training` with `expire_date` exactly 60 (then 30) days out for a person on an active project; run `php yii alert/check`; confirm one `Alert` + one queued email + one `notification_history` row, and that re-running produces no duplicates.
4. **E-sign (7–11)**:
   - Registration: upload a PDF CV + tick certify + stamp → finish → CV is stamped (name/date) with **no password prompt**; `person_document_audit` has a signed row.
   - Registration with a `.docx` CV → converted to PDF then stamped.
   - Profile edit: click "ลงนามอิเล็กทรอนิกส์" → wrong password rejected; correct password stamps + records audit (`auth_method=PASSWORD`). Toggle `ESIGN_OTP_ENABLE=1` and verify OTP path.
   - Enter a `cv_updated_at` older than 6 months and confirm freshness flips (item 10).
5. **Selection warnings (12–13)**: in submission STEP3, a researcher with a stale CV or expired training shows a warning icon in both the Select2 dropdown and the gridview.
6. **Project detail (14)**: open project-submission-show researcher tab → CV and GCP columns show green when valid, red when expired, grey when no data.
7. **Phase 7 — submission-type matrix**:
   - Run new migrations; confirm `training_type.category` seeded (id3=GCP, ids1,2,4=ETHICS) and `submission_type_training_requirement` has the 4 image rows.
   - As Admin, open "เกณฑ์การอบรมตามประเภทโครงการ" CRUD and edit a rule (e.g. set type 2 GCP = REQUIRED) — confirm it persists.
   - Pick a researcher who holds **only** an ethics training (no GCP) into a **clinical** submission (type 1, GCP=REQUIRED) → warning shows "ขาด GCP"; into a **social** submission (type 2, GCP=NA) → **no** GCP warning.
   - Give the researcher a valid GCP but an expired ethics → clinical submission still warns (ANY_OF ethics all expired); add one valid ethics → warning clears.
   - Confirm generic/legacy callers (no `$submissionType`) still behave as before (any-expired warning).
