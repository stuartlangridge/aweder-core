---
layout: page
title: Governance
permalink: /governance/
nav_order: 5
---

# Mission Statement

# Leadership

Awe-der core uses the BDFL (Benovolent Dictator for Life) model for leadership.

This means that the core maintenance of this software, decisions on RFC's
(Request for Change), feature request handling, code merges and all Governence decisions
are handled by the declared BDFL which is the team at:

Jump Twenty-Four Ltd.
Second Floor,
Lambourne's Great Hampton Works,
Great Hampton Row,
Birmingham,
West Midlands,
B19 3JP.

# Communication

Every decision that is the result of a Pull Request, the handling of issue reporting and
the discussion of a feature or roadmap decision will be communicated clearly and in accordance to
the Code of Conduct.

# BDFL usage

The core team at Jump Twenty Four Ltd. maintains the project as Open Source Software (OSS).
Jump Twenty Four Ltd. uses a hosted copy of this software
at [https://awe-der.net/](https://awe-der.net/) which is free for merchants to sign up.

Governance for awe-der.net contains additional Governance and usage documentation that
is different in purpose to Awe-der core.

As OSS, anyone is free to commit to this software or modify by forking it, in accordence
with the MIT License. However, support is only given for awe-der core.

# Roles

#### Maintainer
Maintainers hold responsibility for decisions on merging Pull Requests, ensuring that
standards from a code and ethics standpoint are met according to the guidelines laid
out in this document. Maintainers are elected by the BDFL body.

#### Committer
Any member of the community that has a Pull Request merged into the core can automatically
be listed as a Committer to the project by adding themselves in a PR,
unless they request not to be.

#### Contributor
Any member of the community that contributes towards the project outside of committed code
are listed as a contributor to the project if they request it, or the maintainers ask if
they want to be listed as a contributor. Examples of contributors are testers,
issue reporters and advisors to the core team.

#### Evangelist
Any member of the community that advocates for:
* Contributions towards the software
* Adoption of the software
* Developer feedback on the software

Any member of the community is listed as an Evangelist if they request it (providing they
are undertaking the role in one of the ways listed above), or if the maintainers ask
if they want to be listed as an Evangelist.

#### Request for role
Any request to be listed and counted as one of the project roles will be considered by
the maintainers, with a clear decision given in accordance with the Code of Conduct and
the project ethics.

# Support
The maintainers are responsible for support, and aim to address issues
within 3-5 working days of being raised (depending on the amount 
of work required to resolve it). It should be noted however that this
is Open Source Software, and is presented "as is", with no contractual obligation to
turn issues around. 

# Ethics

# Standards & Expected Coding Patterns

The BDFL organisation for the core adhere to the following characteristics or standards
when submitting work, which all Pull Requests must be compliant with in order 
to merge/contribute:

* Approved PHP Framework Interop Group Standards
[https://www.php-fig.org/psr/](https://www.php-fig.org/psr/)
* All features must have strong corresponding tests executed in PHPUnit
* Separation of Concerns when coding up Services & repository pattern ORM behaviour
* All services must have corresponding interfaces (Contracts)
* Inversion of Control using Laravel's Service Container

# Contribution Guidelines
Please see the [Contribution Guidelines](../Contribution%20Guidelines/contribution-guidelines.md) document for information on Pull Requests
and issue reporting/handling.

# Workflow & Tooling

### Workflow

The project uses the following branch folder structure:
* `/feature/*`
* `/hotfix/*`
* `/release/*`

If the branch relates to an Issue or Feature request, it must contain the ticket
reference in the branch name.

Each PR must be approved by 2 different core maintainers and must pass all unit tests
before it can be merged.

### Tooling

Awe-der is designed to run on any cloud platform provider, and as such is permitted to
contain cloud config markup in the root.

Awe-der is designed to be run locally with Docker. Any changes must comply with the
ability to run the application locally with docker-compose.

# License
This software uses the MIT License:

Copyright © 2020 Awe-der

Permission is hereby granted, free of charge, to any person obtaining a 
copy of this software and associated documentation files (the “Software”), to 
deal in the Software without restriction, including without limitation the 
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
copies of the Software, and to permit persons to whom the Software is 
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included
in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, 
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN 
NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES 
OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR 
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE 
OR OTHER DEALINGS IN THE SOFTWARE.

