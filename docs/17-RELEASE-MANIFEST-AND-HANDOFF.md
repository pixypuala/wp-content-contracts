# Release Manifest and Maintainer Handoff

## Required release manifest

```yaml
project: wp-content-contracts
version: X.Y.Z
source:
  commit: <full-sha>
  tag: vX.Y.Z
  repository: <canonical-url>
build:
  workflow: <run-url-or-id>
  toolVersions: {}
artifacts:
  - name: <artifact>
    sha256: <hash>
    size: <bytes>
schemas:
  - name: <schema>
    version: <version>
support:
  matrixEvidence: docs/evidence/X.Y.Z/compatibility.md
security:
  threatModelReviewed: YYYY-MM-DD
  knownIssues: []
quality:
  testEvidence: docs/evidence/X.Y.Z/INDEX.md
  upgradeEvidence: <path>
maintenance:
  owners: []
  nextCompatibilityReview: YYYY-MM-DD
```

## Pre-publication handoff

A maintainer who did not author the release must be able to:

1. verify source/tag and reproduce or validate the build;
2. inspect artifact contents/checksums;
3. install the artifact in a clean supported environment;
4. run the critical known-good and known-bad proof;
5. locate compatibility, security, migration and known-issue records;
6. roll back or execute documented recovery;
7. answer where bugs, security reports and usage questions go;
8. identify the next maintenance and platform-review dates.

## Bus-factor package

Keep private maintainer operations separate from public code but document ownership and recovery for release credentials, package registries, domains, security inbox, signing/attestation, automation secrets and emergency revocation. No sole maintainer should be the only person capable of revoking a compromised release credential after the project becomes team-maintained.

## Final truth statement

The manifest proves only the named artifact, source, matrix, tests and date. Adoption, security, accessibility and performance claims require their own evidence and must not be inferred from a successful release workflow.
