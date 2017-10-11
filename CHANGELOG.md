# Changelog

## [0.2.0] - 2017-10-11
### Added
- Support for YAML format
- Missing translation files will be created (as PHP files)

### Changed
- Translation keys from all languages, instead of only the default one, are taken into account when creating the table
- Uses AJAX to push the data on the server, allowing you to refresh the window without POSTing all data again
- Use site/config file to enumerate on languages, instead of the files present in the languages folder