#!/usr/bin/env python3
"""Fail when a renamed or removed page has no entry in the ``redirects`` map of conf.py.

Expects the output of ``git diff --name-status --diff-filter=DR -- '*.rst'`` as first argument:
one line per file, ``D<TAB>path`` for a removal, ``R<score><TAB>old<TAB>new`` for a rename.
"""

import re
import sys
from pathlib import Path

changes = Path(sys.argv[1]).read_text().splitlines()
conf = Path(__file__).resolve().parent.parent.joinpath("conf.py").read_text()
redirected = set(re.findall(r'^\s*"([^"]+)"\s*:', conf, flags=re.MULTILINE))

missing = []
for line in changes:
    parts = line.split("\t")
    if len(parts) < 2:
        continue
    old = parts[1]
    if not old.endswith(".rst") or old.endswith(".rst.inc"):
        continue
    docname = old[: -len(".rst")]
    if docname not in redirected:
        target = parts[2][: -len(".rst")] if len(parts) > 2 else "<new location>"
        missing.append((docname, target))

if not missing:
    print("Every renamed or removed page has a redirect.")
    sys.exit(0)

print("The following pages were renamed or removed without a redirect in conf.py.")
print("Add an entry to the `redirects` map, the target being relative to the old page, e.g.:\n")
for docname, target in missing:
    depth = docname.count("/")
    print(f'    "{docname}": "{"../" * depth}{target}.html",')
sys.exit(1)
