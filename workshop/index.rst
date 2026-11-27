Sulu workshop (sulu-workshop)
#############################

This section is a **step-by-step companion** to the **Sulu workshop** project (a training repository; follow its
README to obtain the same codebase) and targets developers who are **new to Sulu** and
have **basic Symfony** experience.

**How the guides are structured** (similar in spirit to the `Symfony documentation`_ style):

* Each page states a **goal**, **what you learn**, and **prerequisites** (usually the previous assignment).
* The **walkthrough** explains the tasks in order. Where it helps, we call out Sulu concepts (template XML, webspace,
  admin resources) and point to the main Sulu manual.
* A **Reference solution** section shows **real code** taken from the Git branch ``assignment/NN`` in the workshop
  repository. That branch is **cumulative**: ``assignment/12`` contains everything from earlier tasks. You can
  ``git show origin/assignment/12:path/to/file`` to inspect any file.

  .. note::

     Your own solution may differ slightly (extra fields, formatting, labels). The reference is the **intended** shape
     of the exercise, not the only valid implementation.

* **See also** links the Sulu Book / Reference and, where relevant, the Symfony documentation (forms, Doctrine,
  routing).

The workshop’s original briefs live in ``assignments/01.md`` … ``12.md`` in that repository; these pages **expand** them
with narrative and full examples.

.. _Symfony documentation: https://symfony.com/doc/current/index.html

.. toctree::
   :maxdepth: 1
   :caption: Assignments

   assignment-01
   assignment-02
   assignment-03
   assignment-04
   assignment-05
   assignment-06
   assignment-07
   assignment-08
   assignment-09
   assignment-10
   assignment-11
   assignment-12
