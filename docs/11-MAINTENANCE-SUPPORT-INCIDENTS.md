# Maintenance, Support and Incidents

## Support channels

- Bugs and reproducible defects: GitHub Issues.
- Usage questions: Discussions or documented community channel.
- Security: private address/advisory route in `SECURITY.md`.
- Conduct: private code-of-conduct enforcement contact.
- Commercial/private implementation: clearly separate from free community support.

## Compatibility maintenance

Review platform releases and deprecations at least monthly during active development and before every release. Scheduled CI tests next beta/RC as non-blocking until the project commits support; failures must be triaged before the dependent platform becomes stable.

## Deprecation

A deprecation notice names replacement, first deprecated version, planned removal version/date policy, migration steps and telemetry/evidence if collected ethically. Security or data-loss behavior may require accelerated removal with a clear advisory.

## Incident process

1. Detect and record time/source without exposing sensitive details.
2. Assign incident lead and severity.
3. Contain: pause release, revoke credentials, disable unsafe path or publish workaround.
4. Preserve relevant logs/artifacts with access control.
5. Diagnose root cause and affected versions/environments.
6. Fix with regression tests and independent review.
7. Release/advisory and notify affected users through documented channels.
8. Retrospective: timeline, contributing factors, control failures, corrective owners/dates.

## Abandonment/archive criteria

Archive honestly when no maintainer can meet security/support obligations, platform architecture makes the project unsafe/obsolete, or a maintained upstream solution supersedes it. Before archive: final notice, security status, alternatives, migration/export path, read-only state and ownership-transfer policy.

## Maintenance scorecard

Monthly: open critical/high issues, oldest confirmed bug, flaky tests, compatibility freshness, dependency/security alerts, unanswered discussions, documentation failures, release backlog, maintainer capacity and next review date.
