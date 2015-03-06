Creating a Pull Request
=======================

When creating a pull request:

* Use a meaningfull name for the pull request.
* Create the pull request as soon as possible.  
* Name the branch after the following format: ``<type>/<description>``, where
  type is one of ``feature``, ``bugfix``, ``hotfix`` or ``enhancement``. For
  example: ``feature/what-my-pr-does``. Note that dashes should be used
  instead of spaces (not underscores).
* Add a line in the format of "<type> <pr-number>
  [<affected bundle or component>] <description>" to the ``CHANGELOG.md`` file
  in the root directory
* In case you have some changes breaking backwards compability you also have to
  add a description to the ``UPGRADE.md`` file in the root directory

If you are a member of the `Sulu organization`_ you should also:

* Add a label for the type of the PR:

 * **feature**: provides a new feature
 * **enhancement**: the PR improves existing features
 * **bugfix**: provides a bug fix
 * **hotfix**: the PR is a bugfix that should be made against a stable branch
   (e.g. ``master``)

* Add a label for the state of the PR:

 * **in progress**: if you are still working on the PR
 * **review**: when it is reviewable
 * **feedback**: if you have reviewed someone else's pr

We recommend that you use the `Waffle board`_ to manage the state of your PRs
and issues.

Template
--------

Template is divided in two parts: *tasks* (for developer) and *information*
(for reviewer).

The information should include the following fields:

* **Fixed tickets**: **list of tickets**; Tickets to close after merge. These
  should be of the form ``fixes #123, fixes #321``.
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
    - [ ] gather feedback for my changes
    - [ ] submit changes to the documentation
    - [ ] ... <add your own tasks>


    __informations:__

    | q                | a
    | ---------------- | ---
    | Fixed tickets    |
    | BC breaks        |
    | Documentation PR |

Example
-------

Create user command:

.. code-block:: none

    __tasks:__

    - [ ] test coverage
    - [ ] gather feedback for my changes
    - [ ] submit changes to the documentation
    - [ ] remove request defaults
    - [ ] fix #1


    __informations:__

    | q                | a
    | ---------------- | ---
    | Fixed tickets    | fixes #1 , fixes #2
    | BC breaks        | none
    | Documentation PR | https://github.com/sulu-cmf/sulu-docs/pull/14

.. _Sulu organization: https://github.com/sulu-cmf
.. _Waffle board: https://waffle.io/sulu-cmf/sulu
