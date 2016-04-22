Creating a Pull Request
=======================

When creating a pull request:

* Clone the Sulu repository from git.
* Add a branch and name it after the following format: ``<type>/<description>``, where
  type is one of ``feature``, ``bugfix``, ``hotfix`` or ``enhancement``. For
  example: ``feature/what-my-pr-does``. Note that dashes should be used
  instead of spaces (not underscores).
* Add your feature/bugfix/enhancement.
* Create atomic and logically separate commits (use the power of ``git rebase``
  to have a clean and logical history).
* Write good commit messages (see the tip below).
* Write tests.
* :doc:`test-your-code`.
* Add a line in the format of "<type> <pr-number>
  [<affected bundle or component>] <description>" to the ``CHANGELOG.md`` file
  in the root directory.
* In case you do changes that break backwards compatibility you also have to
  add a description to the ``UPGRADE.md`` file in the root directory.
* Use a meaningful name for the pull request (see the tip below).
* Create the pull request as soon as possible.
* In addition to a pull request to the code repository, you should also send a
  pull request to the `documentation repository`_ to update the documentation when appropriate.

.. tip::

    A good commit message is composed of a summary (the first line),
    optionally followed by a blank line and a more detailed description. Use a
    verb (``Fixed ...``, ``Added ...``, ...) to start the summary and don't
    add a period at the end.

If you are a member of the `Sulu organization`_ you should also:

* Add a label for the type of the PR:

 * **feature**: provides a new feature
 * **enhancement**: the PR improves existing features
 * **bugfix**: provides a bug fix
 * **hotfix**: the PR is a bugfix that should be made against a stable branch
   (e.g. ``master``)

Template
--------

When creating a pull request on GitHub, there is already a pre filled template
for you. The template is divided in several parts.

Checklist
`````````
The pull request must include the following checklist at the top to ensure that
contributions may be reviewed without needless feedback loops and that your
contributions can be included into Sulu as quickly as possible:

An example submission could now look as follows:

.. code-block:: text

    | Q                  | A
    | ------------------ | ---
    | Bug fix?           | yes
    | New feature?       | no
    | BC breaks?         | no
    | Deprecations?      | yes
    | Fixed tickets      | fixes #1243, fixes #1443
    | Related issues/PRs | -
    | License            | MIT
    | Documentation PR   | sulu/sulu-docs#153

Some answers to the questions trigger some more requirements:

* If you answer yes to "Bug fix?", check if the bug is already listed in the
  Sulu issues and reference it/them in "Fixed tickets". These should be of the
  form ``fixes #123, fixes #321``.
* If you answer yes to "New feature?", you must submit a pull request to the
  documentation and reference it under the "Documentation PR" section.
* If you answer yes to "BC breaks?", the pull request must contain updates to
  the ``UPGRADE.md`` file.
* If you answer yes to "Deprecations?", the pull request must contain updates to
  the ``UPGRADE.md`` file.
* If the "license" is not MIT, just don't submit the pull request as it won't
  be accepted anyway.

.. note::

  When an issue number is prefixed with ``fixes`` it tells GitHub to
  automatically close the referenced ticket when the pull request is merged

What's in this PR?
``````````````````

Give as much details as possible about your changes (don't hesitate to give code
examples to illustrate your points).
The pull request description helps the code review and it serves as a reference
when the code is merged.

Why?
````

If your pull request is about adding a new feature or modifying an existing one,
explain the rationale for the changes. Why did you add this feature, which problem
does the PR fix?

Example usage
`````````````

If you add a new feature or make breaking changes, please give us a example
usage (code examples, screenshots, etc.), so we understand what you try to
solve.

Remove this section if not needed.

BC Breaks/Deprecations
``````````````````````

If you have done BC breaks or deprecations, please describe them shortly here
and add them to the ``UPGRADE.md`` file as well.

Remove this section if not needed.

To Do
`````

If some of the requirements in the checklist are not met, use the "To Do"
section and add the relevant items:

.. code-block:: text

    - [ ] Submit changes to the documentation
    - [ ] Document the BC breaks

If the code is not finished yet because you don't have time to finish it or
because you want early feedback on your work, add an item to to-do list:

.. code-block:: text

    - [ ] Finish the code
    - [ ] Add tests as they have not been updated yet
    - [ ] Gather feedback for my changes

Remove this section if not needed.

.. _documentation repository: https://github.com/sulu/sulu-docs
.. _Sulu organization: https://github.com/sulu
