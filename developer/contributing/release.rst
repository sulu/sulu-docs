Release
=======

* Tag a new husky version
* Update `bower.json` in `AdminBundle` and execute `grunt update`
* Pin all `sulu/sulu` dependencies to a tagged version
* Build javascript of all bundles
* Make sure all tests are running
* Tag new version of `sulu/sulu`
* Update version in `composer.json` of `sulu-standard`
* Adjust dependencies in `composer.json` as needed
* Update version attribute in `composer.json`
* Execute `composer update` in `sulu-standard`
* Copy `CHANGELOG.md` from `sulu/sulu` to `sulu-standard`
* Copy `UPGRADE.md` from `sulu/sulu` to `sulu-standard`
* Tag new release of `sulu-standard`
