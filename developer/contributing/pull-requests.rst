Creating a Pull Request
=======================

When creating a pull request:

* Clone the Sulu repository from git.
* Add a branch and name it after the following format: ``<type>/<description>``, where
  type is one of ``feature``, ``bugfix``, ``hotfix`` or ``enhancement``. For
  example: ``feature/what-my-pr-does``. Note that dashes should be used
  instead of spaces (not underscores).
* Add your feature/bugfix/enhancement.
* Write tests.
* :doc:`test-your-code`.
* Add a line in the format of "<type> <pr-number>
  [<affected bundle or component>] <description>" to the ``CHANGELOG.md`` file
  in the root directory.
* In case you have some changes breaking backwards compability you also have to
  add a description to the ``UPGRADE.md`` file in the root directory.
* Use a meaningful name for the pull request.
* Create the pull request as soon as possible.

If you are a member of the `Sulu organization`_ you should also:

* Add a label for the type of the PR:

 * **feature**: provides a new feature
 * **enhancement**: the PR improves existing features
 * **bugfix**: provides a bug fix
 * **hotfix**: the PR is a bugfix that should be made against a stable branch
   (e.g. ``master``)

Template
--------

Template is divided in two parts: *tasks* (for developer) and *information*
(for reviewer).

The information should include the following fields:

* **Fixed tickets**: **list of tickets**; Tickets to close after merge. These
  should be of the form ``fixes #123, fixes #321``.
* **Related PRs**: **link to PRs`**, PRs that are required for the current one
* **BC breaks**: **none or description**; any compatibility breaking changes
* **Documentation PR**: **link to PR**; Link to the documentation PR
  (if applicable)

.. note::

  When an issue number is prefixed with ``fixes`` it tells GitHub to
  automatically close the referenced ticket when the PR is merged

You can copy and paste the following template:

.. code-block:: none

    [short description]:

    __tasks:__

    - [ ] test coverage
    - [ ] submit changes to the documentation
    - [ ] ... <add your own tasks>


    __informations:__

    | q                | a
    | ---------------- | ---
    | Fixed tickets    |
    | Related PRs      |
    | BC breaks        |
    | Documentation PR |

Example
-------

Create user command:

.. code-block:: none

    __tasks:__

    - [ ] test coverage
    - [ ] submit changes to the documentation
    - [ ] remove request defaults
    - [ ] fix #1


    __informations:__

    | q                | a
    | ---------------- | ---
    | Fixed tickets    | fixes #1 , fixes #2
    | Related PRs      | #3, #4
    | BC breaks        | none
    | Documentation PR | https://github.com/sulu-cmf/sulu-docs/pull/14

.. _Sulu organization: https://github.com/sulu-cmf
