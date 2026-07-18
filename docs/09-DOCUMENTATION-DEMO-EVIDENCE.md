# Documentation, Demo and Evidence

## Required documentation set

- **README:** value, limitations, support table, quick start, demo and status badges that cannot mislead.
- **Installation:** prerequisites, clean install, upgrade, uninstall, recovery and environment diagnostics.
- **User guide:** workflows, inputs/outputs, examples, error codes and troubleshooting.
- **API/schema reference:** generated where possible, with stability and deprecation markers.
- **Contributor guide:** setup, commands, architecture map, testing, generated files and PR expectations.
- **Security guide:** trust boundaries, safe deployment, secret handling, dangerous operations and disclosure.
- **Operations/maintenance:** scheduled checks, backup/recovery, incident and release procedures.
- **Compatibility:** exact tested cells, date, git SHA, status and exclusions.
- **Migration guide:** every breaking/deprecated behavior with before/after examples.

## Demo design

The primary demo must complete in under ten minutes after prerequisites and show:

1. the real problem or unsafe/broken baseline;
2. installation and environment doctor output;
3. the main successful workflow;
4. one controlled failure the project detects or prevents;
5. generated report/evidence and how to interpret it;
6. project limitations and supported matrix;
7. how to run the same proof locally.

Provide a Playground bundle when technically appropriate, plus a local reproducible route. A hosted demo is never the only proof.

## Evidence index

For each release, create `docs/evidence/<version>/INDEX.md` linking:

- source tag and commit;
- build and artifact checksums;
- compatibility matrix;
- automated test summaries;
- security/dependency/license results;
- accessibility/manual test records where applicable;
- performance/package budget results;
- upgrade/recovery result;
- known issues and accepted risks;
- demo recording or screenshots with alt text/captions.

## Case-study structure

Use PCAAP and quantify only what was measured. Explain the original failure, technical constraints, decisions and rejected alternatives, implementation, verification, observed result, limitations, maintenance impact and next step. Never invent adoption, users, performance savings or security outcomes.
